// ======================== RECORDING HELPERS ========================
function updateRecordingParticipants() {
    const speakers = recordingActiveSpeakers;
    const speakerIds = new Set(speakers.filter(p => !p.isLocal).map(p => p.identity));

    recordingVideoCache.forEach((entry) => {
        entry.isSpeaking = speakerIds.has(entry.identity);
    });

    const ordered = [];
    const added = new Set();

    for (const p of speakers) {
        if (p.isLocal) continue;
        const entry = recordingVideoCache.get(p.identity);
        if (entry && !added.has(p.identity)) {
            ordered.push(entry);
            added.add(p.identity);
        }
    }

    if (ordered.length < 4) {
        for (const id of recordingSpeakerQueue) {
            if (added.has(id)) continue;
            const entry = recordingVideoCache.get(id);
            if (entry) {
                ordered.push(entry);
                added.add(id);
            }
        }
    }

    if (ordered.length < 4) {
        const localEntry = recordingVideoCache.get('local_' + currentUserId);
        if (localEntry && !added.has('local_' + currentUserId)) {
            ordered.push(localEntry);
            added.add('local_' + currentUserId);
        }
    }

    if (ordered.length < 4) {
        recordingVideoCache.forEach((entry, id) => {
            if (!added.has(id) && ordered.length < 4) {
                ordered.push(entry);
                added.add(id);
            }
        });
    }

    recordingParticipants = ordered.slice(0, 4);
    bgDirty = true;
}

function drawAvatar(ctx, name, x, y, w, h) {
    ctx.fillStyle = '#374151';
    ctx.beginPath();
    ctx.roundRect(x, y, w, h, 8);
    ctx.fill();
    ctx.fillStyle = '#fff';
    ctx.font = 'bold 48px Inter, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText((name || 'P').charAt(0).toUpperCase(), x + w / 2, y + h / 2);
    ctx.textBaseline = 'alphabetic';
}

function drawNameLabel(ctx, name, x, y, w, h) {
    ctx.fillStyle = 'rgba(0,0,0,0.6)';
    ctx.beginPath();
    ctx.roundRect(x, y + h - 28, w, 28, 8);
    ctx.fill();
    ctx.fillStyle = '#fff';
    ctx.font = '13px Inter, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText(name || '-', x + w / 2, y + h - 8);
}

function drawSpeakingRing(ctx, x, y, w, h) {
    ctx.strokeStyle = '#22c55e';
    ctx.lineWidth = 3;
    ctx.beginPath();
    ctx.roundRect(x - 1.5, y - 1.5, w + 3, h + 3, 9);
    ctx.stroke();
}

// ======================== COUNTDOWN ========================
const countdownOverlay = document.getElementById('countdownOverlay');
const countdownNumber = document.getElementById('countdownNumber');
let isCountdownActive = false;
let countdownInterval = null;
let countdownResolve = null;

function cancelCountdown() {
    if (!isCountdownActive) return;
    clearInterval(countdownInterval);
    countdownOverlay.classList.add('hidden');
    isCountdownActive = false;
    if (countdownResolve) countdownResolve();
}

function startCountdown() {
    return new Promise((resolve) => {
        isCountdownActive = true;
        countdownResolve = resolve;
        countdownOverlay.classList.remove('hidden');
        let count = 3;
        countdownNumber.textContent = count;
        countdownNumber.style.transform = 'scale(0.5)';
        countdownNumber.style.transition = 'transform 0.3s ease';
        countdownNumber.offsetHeight;
        countdownNumber.style.transform = 'scale(1)';

        countdownInterval = setInterval(() => {
            if (!isCountdownActive) return;
            count--;
            if (count > 0) {
                countdownNumber.textContent = count;
                countdownNumber.style.transform = 'scale(0.5)';
                countdownNumber.offsetHeight;
                countdownNumber.style.transform = 'scale(1)';
            } else {
                clearInterval(countdownInterval);
                countdownOverlay.classList.add('hidden');
                isCountdownActive = false;
                resolve();
            }
        }, 1000);
    });
}

// ======================== SCREEN RECORDING ========================
async function startScreenRecording() {
    if (isRecordingScreen) return;
    if (recordingByOther) {
        alert('Meeting sedang direkam oleh peserta lain.');
        return;
    }
    recordingCanvas = document.getElementById('recordingCanvas');
    if (!recordingCanvas) {
        alert('Canvas tidak ditemukan.');
        return;
    }
    recordingCanvasCtx = recordingCanvas.getContext('2d');
    recordingChunks = [];

    if (localVideo) {
        recordingVideoCache.set('local_' + currentUserId, {
            videoEl: localVideo,
            name: authName,
            identity: 'local_' + currentUserId,
            isSpeaking: false
        });
    }

    const W = recordingCanvas.width;
    const H = recordingCanvas.height;
    recordingBgCanvas = document.createElement('canvas');
    recordingBgCanvas.width = W;
    recordingBgCanvas.height = H;
    recordingThumbCanvas = document.createElement('canvas');
    recordingThumbCanvas.width = W;
    recordingThumbCanvas.height = H;
    bgDirty = true;
    thumbnailsDirty = true;

    try {
        const audioCtx = new(window.AudioContext || window.webkitAudioContext)();
        recordingAudioDestination = audioCtx.createMediaStreamDestination();

        if (localStream) {
            const localTracks = localStream.getAudioTracks();
            if (localTracks.length > 0) {
                const localSource = audioCtx.createMediaStreamSource(new MediaStream(localTracks));
                localSource.connect(recordingAudioDestination);
            }
        }

        if (room) {
            room.remoteParticipants.forEach((participant) => {
                const audioPub = participant.getTrackPublication(LiveKit.Track.Source.Microphone);
                if (audioPub && audioPub.track) {
                    const ms = audioPub.track.mediaStream;
                    if (ms && ms.getAudioTracks().length > 0) {
                        const source = audioCtx.createMediaStreamSource(new MediaStream(ms
                            .getAudioTracks()));
                        source.connect(recordingAudioDestination);
                    }
                }
            });
        }

        recordingAudioMixer = audioCtx;
    } catch (e) {
        console.warn('Audio mixer error, recording without audio:', e);
    }

    let recordingGridLayout = [];

    function computeGridLayout(count) {
        const layouts = [];
        if (count === 1) {
            layouts.push({
                x: 0,
                y: 0,
                w: W,
                h: H
            });
        } else if (count === 2) {
            layouts.push({
                x: 0,
                y: 0,
                w: W / 2,
                h: H
            });
            layouts.push({
                x: W / 2,
                y: 0,
                w: W / 2,
                h: H
            });
        } else if (count === 3) {
            layouts.push({
                x: 0,
                y: 0,
                w: W / 2,
                h: H / 2
            });
            layouts.push({
                x: W / 2,
                y: 0,
                w: W / 2,
                h: H / 2
            });
            layouts.push({
                x: 0,
                y: H / 2,
                w: W,
                h: H / 2
            });
        } else {
            layouts.push({
                x: 0,
                y: 0,
                w: W / 2,
                h: H / 2
            });
            layouts.push({
                x: W / 2,
                y: 0,
                w: W / 2,
                h: H / 2
            });
            layouts.push({
                x: 0,
                y: H / 2,
                w: W / 2,
                h: H / 2
            });
            layouts.push({
                x: W / 2,
                y: H / 2,
                w: W / 2,
                h: H / 2
            });
        }
        return layouts;
    }

    function renderBackground() {
        const ctx = recordingBgCanvas.getContext('2d');
        ctx.clearRect(0, 0, W, H);
        ctx.fillStyle = '#1a1a2e';
        ctx.fillRect(0, 0, W, H);

        const ssVideo = document.getElementById('screenShareVideo');
        const hasScreenShare = ssVideo && ssVideo.srcObject && !screenShareContainer?.classList.contains(
            'hidden');
        if (hasScreenShare && ssVideo.readyState >= 2) {
            ctx.drawImage(ssVideo, 0, 0, W, H);
        } else {
            const participants = recordingParticipants;
            const count = Math.min(participants.length, 4);
            if (count > 0) {
                recordingGridLayout = computeGridLayout(count);
                for (let i = 0; i < count; i++) {
                    const p = participants[i];
                    const l = recordingGridLayout[i];
                    ctx.fillStyle = '#374151';
                    ctx.fillRect(l.x, l.y, l.w, l.h);
                    ctx.fillStyle = '#fff';
                    ctx.font = 'bold 64px Inter, sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText((p.name || 'P').charAt(0).toUpperCase(), l.x + l.w / 2, l.y + l.h / 2);
                    ctx.textBaseline = 'alphabetic';
                    ctx.fillStyle = 'rgba(0,0,0,0.5)';
                    ctx.fillRect(l.x, l.y + l.h - 30, l.w, 30);
                    ctx.fillStyle = '#fff';
                    ctx.font = '14px Inter, sans-serif';
                    ctx.textAlign = 'center';
                    ctx.fillText(p.name || '-', l.x + l.w / 2, l.y + l.h - 9);
                }
            } else {
                ctx.fillStyle = '#333';
                ctx.font = 'bold 48px Inter, sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('{{ $meeting->nama_rapat }}', W / 2, H / 2 - 20);
                ctx.font = '24px Inter, sans-serif';
                ctx.fillStyle = '#666';
                ctx.fillText(new Date().toLocaleString(), W / 2, H / 2 + 40);
            }
        }
        bgDirty = false;
    }

    function renderFrame() {
        if (bgDirty) renderBackground();

        const ctx = recordingCanvasCtx;
        ctx.clearRect(0, 0, W, H);
        ctx.drawImage(recordingBgCanvas, 0, 0);

        const ssVideo = document.getElementById('screenShareVideo');
        const hasScreenShare = ssVideo && ssVideo.srcObject && !screenShareContainer?.classList.contains(
            'hidden');
        if (!hasScreenShare || ssVideo.readyState < 2) {
            const participants = recordingParticipants;
            const count = Math.min(participants.length, 4);
            if (count > 0 && recordingGridLayout.length >= count) {
                const layout = recordingGridLayout;
                for (let i = 0; i < count; i++) {
                    const p = participants[i];
                    const l = layout[i];
                    const videoEl = p.videoEl;
                    if (videoEl && videoEl.readyState >= 2) {
                        ctx.drawImage(videoEl, l.x, l.y, l.w, l.h);
                    }
                }
            }
        }

        const fc = recordingFrameCounter++;
        if (fc % 30 < 15) {
            ctx.fillStyle = '#ef4444';
            ctx.beginPath();
            ctx.arc(40, 40, 14, 0, Math.PI * 2);
            ctx.fill();
            ctx.fillStyle = '#fff';
            ctx.font = 'bold 14px Inter, sans-serif';
            ctx.textAlign = 'left';
            ctx.fillText('REC', 60, 47);
        }
    }

    let recordingFrameCounter = 0;
    isRecordingScreen = true;
    recordingRenderTimer = setInterval(() => {
        if (!isRecordingScreen) return;
        renderFrame();
    }, 66);

    if (!recordingCanvas.captureStream) {
        alert('Browser tidak mendukung fitur rekam layar (canvas.captureStream). Gunakan Chrome atau Firefox versi terbaru.');
        isRecordingScreen = false;
        clearInterval(recordingRenderTimer);
        recordingRenderTimer = null;
        return;
    }

    let videoStream, combinedStream;
    try {
        videoStream = recordingCanvas.captureStream(15);
    } catch (e) {
        alert('Gagal memulai rekaman: ' + e.message);
        isRecordingScreen = false;
        clearInterval(recordingRenderTimer);
        recordingRenderTimer = null;
        return;
    }

    try {
        if (recordingAudioDestination) {
            const audioTracks = recordingAudioDestination.stream.getAudioTracks();
            if (audioTracks.length > 0) {
                combinedStream = new MediaStream([
                    ...videoStream.getVideoTracks(),
                    audioTracks[0]
                ]);
            } else {
                combinedStream = videoStream;
            }
        } else {
            combinedStream = videoStream;
        }
    } catch (e) {
        combinedStream = videoStream;
    }

    let mimeType = 'video/webm';
    try {
        if (MediaRecorder.isTypeSupported('video/webm;codecs=vp8,opus')) {
            mimeType = 'video/webm;codecs=vp8,opus';
        } else if (MediaRecorder.isTypeSupported('video/webm;codecs=vp9,opus')) {
            mimeType = 'video/webm;codecs=vp9,opus';
        }
    } catch (e) {}

    try {
        recordingMediaRecorder = new MediaRecorder(combinedStream, { mimeType });
    } catch (e) {
        try {
            recordingMediaRecorder = new MediaRecorder(combinedStream);
        } catch (e2) {
            alert('Gagal membuat MediaRecorder: ' + e2.message);
            isRecordingScreen = false;
            clearInterval(recordingRenderTimer);
            recordingRenderTimer = null;
            return;
        }
    }

    recordingMediaRecorder.ondataavailable = (e) => {
        if (e.data.size > 0) recordingChunks.push(e.data);
    };
    recordingMediaRecorder.onerror = (e) => {
        console.error('MediaRecorder error:', e);
        stopScreenRecording();
    };
    recordingMediaRecorder.onstop = () => {
        if (recordingChunks.length > 0) {
            const blob = new Blob(recordingChunks, {
                type: 'video/webm'
            });
            uploadScreenRecording(blob);
        }
    };
    recordingMediaRecorder.start(1000);

    if (recordIconDefault) recordIconDefault.classList.add('hidden');
    if (recordIconActive) recordIconActive.classList.remove('hidden');
    if (recordActiveDot) recordActiveDot.classList.remove('hidden');
    if (recordScreenBtn) recordScreenBtn.querySelector('span')?.classList.add('text-red-400');
    showRecordingPopup(true, null);
    sendBroadcast({
        type: 'screen-recording-start',
        name: authName,
        sender_id: currentUserId
    });
}

function stopScreenRecording() {
    if (!isRecordingScreen) return;
    isRecordingScreen = false;
    if (recordingRenderTimer) {
        clearInterval(recordingRenderTimer);
        recordingRenderTimer = null;
    }
    if (recordingMediaRecorder && recordingMediaRecorder.state !== 'inactive') {
        recordingMediaRecorder.stop();
    }
    if (recordingAudioMixer) {
        recordingAudioMixer.close().catch(() => {});
        recordingAudioMixer = null;
    }
    recordingCanvasCtx = null;
    recordingAudioDestination = null;
    recordingBgCanvas = null;
    recordingThumbCanvas = null;
    recordingVideoCache.delete('local_' + currentUserId);

    if (recordIconDefault) recordIconDefault.classList.remove('hidden');
    if (recordIconActive) recordIconActive.classList.add('hidden');
    if (recordActiveDot) recordActiveDot.classList.add('hidden');
    if (recordScreenBtn) recordScreenBtn.querySelector('span')?.classList.remove('text-red-400');
    hideRecordingPopup();
    sendBroadcast({
        type: 'screen-recording-stop'
    });
}

async function uploadScreenRecording(blob) {
    try {
        const formData = new FormData();
        formData.append('recording', blob, `meeting_${meetingId}_${Date.now()}.webm`);
        formData.append('duration_seconds', Math.floor(blob.size / 500000));
        const res = await fetch(baseUrl + '/upload-screen-recording', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        const data = await res.json();
        if (res.ok) {
            alert('Rekaman terupload! Pipeline diproses di background.');
        } else {
            alert('Gagal upload: ' + (data.message || 'Unknown error'));
        }
    } catch (e) {
        alert('Gagal upload rekaman: ' + e.message);
    }
    recordingChunks = [];
}

function getVisibleParticipants() {
    const allCards = getParticipantCards();
    const localEl = document.getElementById('localVideoContainer');
    const remotes = allCards.filter(c => c.id !== 'localVideoContainer');
    const result = [];
    if (localEl) result.push(localEl);
    const pinned = [];
    const unpinned = [];
    remotes.forEach(el => {
        const id = el.dataset?.identity || '';
        const pinIdx = pinnedIdentities.indexOf(id);
        if (pinIdx >= 0) {
            pinned[pinIdx] = el;
        } else {
            unpinned.push(el);
        }
    });
    pinned.forEach(el => {
        if (el) result.push(el);
    });
    unpinned.forEach(el => result.push(el));
    return result;
}
