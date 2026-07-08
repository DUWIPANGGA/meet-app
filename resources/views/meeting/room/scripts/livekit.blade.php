// ======================== LIVEKIT SFU ========================
function createRemoteVideoCard(identity, displayName) {
    const safeKey = identity.replace(/[^a-zA-Z0-9_-]/g, '_');
    const cardId = `remote-card-${safeKey}`;
    const videoId = `remote-video-${safeKey}`;
    let card = document.getElementById(cardId);
    if (card) {
        return {
            card,
            video: document.getElementById(videoId),
            safeKey
        };
    }
    card = document.createElement('div');
    card.id = cardId;
    card.dataset.identity = identity;
    card.className = 'rounded-2xl overflow-hidden shadow-xl h-full w-full min-h-0 relative video-card m-1';
    const video = document.createElement('video');
    video.id = videoId;
    video.autoplay = true;
    video.playsInline = true;
    video.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover';
    video.muted = true;
    const label = document.createElement('div');
    label.className = 'absolute bottom-2 left-2 text-xs px-2 py-1 rounded name-label text-gray-200';
    label.textContent = displayName || identity;
    const pinBtn = document.createElement('button');
    pinBtn.id = `pin-btn-${safeKey}`;
    pinBtn.dataset.identity = identity;
    pinBtn.className = 'absolute top-2 right-2 pin-btn text-xs px-1.5 py-0.5 z-20 transition-colors';
    pinBtn.innerHTML =
        '<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/></svg>';
    pinBtn.onclick = (e) => {
        e.stopPropagation();
        togglePin(identity);
    };
    const avatar = document.createElement('div');
    avatar.id = `remote-avatar-${safeKey}`;
    avatar.className = 'absolute inset-0 flex items-center justify-center hidden z-10';
    avatar.style.background = 'rgba(0,0,0,0.6)';
    avatar.innerHTML =
        `<div class="relative"><div id="remote-avatar-circle-${safeKey}" style="width:112px;height:112px;border-radius:50%;background:#4b5563;display:flex;align-items:center;justify-content:center;transition:all 0.3s"><span style="font-size:3rem;color:#fff;font-weight:700;text-transform:uppercase">${(displayName || identity || 'P').charAt(0).toUpperCase()}</span></div></div>`;
    card.appendChild(video);
    card.appendChild(pinBtn);
    card.appendChild(avatar);
    card.appendChild(label);
    remoteVideos.appendChild(card);
    remoteParticipants.set(identity, {
        identity,
        displayName,
        cardId,
        videoId
    });
    scheduleParticipantUIUpdate();
    return {
        card,
        video,
        safeKey
    };
}

function removeRemoteVideoCard(identity) {
    const safeKey = (identity || '').replace(/[^a-zA-Z0-9_-]/g, '_');
    const card = document.getElementById(`remote-card-${safeKey}`);
    if (card) card.remove();
    remoteParticipants.delete(identity);
    scheduleParticipantUIUpdate();
}

async function waitForLiveKit(timeout = 15000) {
    const start = Date.now();
    while (typeof window.LiveKit === 'undefined') {
        if (Date.now() - start > timeout) {
            try {
                await new Promise((resolve, reject) => {
                    const s = document.createElement('script');
                    s.src =
                        'https://cdn.jsdelivr.net/npm/livekit-client/dist/livekit-client.umd.min.js';
                    s.onload = () => {
                        if (window.LiveKit) resolve();
                        else reject(new Error('LiveKit CDN load failed'));
                    };
                    s.onerror = reject;
                    document.head.appendChild(s);
                });
                return;
            } catch (e) {
                throw new Error('LiveKit library gagal dimuat.');
            }
        }
        await new Promise(r => setTimeout(r, 100));
    }
}

async function connectToLiveKit() {
    try {
        setConnectionStatus('LiveKit: menghubungkan...', 'text-amber-300');
        if (!navigator.mediaDevices?.getUserMedia) {
            alert('Akses kamera/mikrofon tidak tersedia. Buka halaman ini via HTTPS atau localhost.');
            setConnectionStatus('Media tidak tersedia', 'text-red-400');
            return;
        }
        await waitForLiveKit();
        const {
            token,
            serverUrl
        } = await fetchLiveKitToken();
        room = new LiveKit.Room({
            adaptiveStream: true,
            dynacast: true,
            videoCaptureDefaults: {
                resolution: LiveKit.VideoPresets.h720
            },
        });
        room.on(LiveKit.RoomEvent.TrackSubscribed, (track, publication, participant) => {
            if (publication.source === LiveKit.Track.Source.ScreenShare) {
                if (participant.isLocal) return;
                showRemoteScreenShare(track, participant);
                return;
            }
            if (publication.source === LiveKit.Track.Source.Microphone && !participant.isLocal &&
                liveTranscriptionActive && !participantTranscribers.has(participant.identity)) {
                const tracks = [];
                if (track.mediaStream) {
                    track.mediaStream.getAudioTracks().forEach(t => tracks.push(t));
                }
                if (tracks.length === 0 && track.mediaStreamTrack) {
                    tracks.push(track.mediaStreamTrack);
                }
                if (tracks.length > 0) {
                    const userId = Number(participant.identity) || participant.identity;
                    const displayName = participant.name || participant.identity;
                    const state = createTrackTranscriber(participant.identity, userId, displayName);
                    startTrackAudioCapture(state, tracks);
                }
                return;
            }
            if (participant.isLocal) return;
            const identity = participant.identity;
            const displayName = participant.name || identity;
            const {
                video
            } = createRemoteVideoCard(identity, displayName);
            track.attach(video);
            recordingVideoCache.set(identity, {
                videoEl: video,
                name: displayName,
                identity,
                isSpeaking: false
            });
            updateRecordingParticipants();
        });
        room.on(LiveKit.RoomEvent.TrackUnsubscribed, (track, publication, participant) => {
            if (publication?.source === LiveKit.Track.Source.ScreenShare) {
                hideScreenShare();
                bgDirty = true;
                return;
            }
            if (publication?.source === LiveKit.Track.Source.Camera) {
                scheduleParticipantUIUpdate();
            }
            track.detach();
            recordingVideoCache.delete(participant.identity);
            updateRecordingParticipants();
        });
        room.on(LiveKit.RoomEvent.ParticipantDisconnected, (participant) => {
            pinnedIdentities = pinnedIdentities.filter(id => id !== participant.identity);
            updatePinIndicators();
            removeRemoteVideoCard(participant.identity);
            if (liveTranscriptionActive) {
                removeTrackTranscriber(participant.identity);
            }
            recordingVideoCache.delete(participant.identity);
            updateRecordingParticipants();
        });
        room.on(LiveKit.RoomEvent.ConnectionStateChanged, (state) => {
            if (state === LiveKit.ConnectionState.Connected) {
                setConnectionStatus('LiveKit: terhubung', 'text-emerald-400');
            } else if (state === LiveKit.ConnectionState.Disconnected) {
                setConnectionStatus('LiveKit: terputus', 'text-amber-300');
            } else if (state === LiveKit.ConnectionState.Reconnecting) {
                setConnectionStatus('LiveKit: reconnect...', 'text-amber-300');
            }
        });
        room.on(LiveKit.RoomEvent.ActiveSpeakersChanged, (speakers) => {
            document.querySelectorAll('[id^="remote-avatar-circle-"].speaking-ring').forEach(el => el
                .classList.remove('speaking-ring'));
            recordingActiveSpeakers = speakers;
            const newActive = speakers.find(p => !p.isLocal);
            activeSpeakerIdentity = newActive ? newActive.identity : null;
            speakers.forEach(p => {
                if (p.isLocal) return;
                const safeKey = (p.identity || '').replace(/[^a-zA-Z0-9_-]/g, '_');
                const circle = document.getElementById('remote-avatar-circle-' + safeKey);
                if (circle) circle.classList.add('speaking-ring');
                recordingSpeakerQueue = [p.identity, ...recordingSpeakerQueue.filter(id =>
                    id !== p.identity)].slice(0, 20);
            });
            if (currentLayout === 'speaker' || currentLayout === 'sidebar' || currentLayout ===
                'spotlight') {
                scheduleParticipantUIUpdate();
            }
            updateRecordingParticipants();
        });
        await room.connect(serverUrl, token);
        subscribeEchoChannel();
        localStream = await navigator.mediaDevices.getUserMedia({
            video: true,
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                autoGainControl: true
            }
        });
        localVideo.srcObject = localStream;
        const videoTrack = localStream.getVideoTracks()[0];
        const audioTrack = localStream.getAudioTracks()[0];
        if (isCameraOff && videoTrack) videoTrack.enabled = false;
        if (isMuted && audioTrack) audioTrack.enabled = false;
        if (videoTrack) {
            try {
                await room.localParticipant.publishTrack(videoTrack, {
                    name: 'camera',
                    source: LiveKit.Track.Source.Camera,
                });
            } catch (pubErr) {
                console.warn('publish camera (retry 1):', pubErr);
                await new Promise(r => setTimeout(r, 2000));
                await room.localParticipant.publishTrack(videoTrack, {
                    name: 'camera',
                    source: LiveKit.Track.Source.Camera,
                });
            }
            if (isCameraOff) {
                const pub = room.localParticipant.getTrackPublication(LiveKit.Track.Source.Camera);
                if (pub?.track) pub.track.mute().catch(e => console.warn(e));
            }
        }
        if (audioTrack) {
            try {
                await room.localParticipant.publishTrack(audioTrack, {
                    name: 'microphone',
                    source: LiveKit.Track.Source.Microphone,
                });
            } catch (pubErr) {
                console.warn('publish mic (retry 1):', pubErr);
                await new Promise(r => setTimeout(r, 2000));
                await room.localParticipant.publishTrack(audioTrack, {
                    name: 'microphone',
                    source: LiveKit.Track.Source.Microphone,
                });
            }
            if (isMuted) {
                const pub = room.localParticipant.getTrackPublication(LiveKit.Track.Source.Microphone);
                if (pub?.track) pub.track.mute().catch(e => console.warn(e));
            }
        }
        setConnectionStatus('LiveKit: terhubung', 'text-emerald-400');
        updateParticipantUI();
        if (isCameraOff) startAudioMonitor();
        if (@json($meeting->pipeline_status ?? 'idle') === 'processing') startPipelinePolling();
    } catch (error) {
        console.error(error);
        setConnectionStatus('LiveKit: gagal', 'text-red-400');
        alert('Gagal terhubung ke server meeting.');
    }
}
