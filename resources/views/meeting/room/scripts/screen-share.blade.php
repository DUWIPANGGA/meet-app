// ======================== SCREEN SHARE ========================
const screenShareBtn = document.getElementById('screenShareBtn');
const screenShareContainer = document.getElementById('screenShareContainer');
const screenShareVideo = document.getElementById('screenShareVideo');
const screenShareLabel = document.getElementById('screenShareLabel');
const stopScreenShareBtn = document.getElementById('stopScreenShareBtn');
const screenShareActiveDot = document.getElementById('screenShareActiveDot');

function isOtherSharing() {
    if (!room) return false;
    for (const p of room.remoteParticipants.values()) {
        const pub = p.getTrackPublication(LiveKit.Track.Source.ScreenShare);
        if (pub && pub.track) return true;
    }
    return false;
}

async function toggleScreenShare() {
    if (isScreenSharing) {
        await stopScreenShare();
        return;
    }
    if (!navigator.mediaDevices?.getDisplayMedia) {
        alert('Screen share tidak didukung di browser ini. Gunakan Chrome/Edge atau pastikan akses via HTTPS.');
        return;
    }
    if (isOtherSharing()) {
        if (!confirm('Peserta lain sedang share layar. Ambil alih?')) return;
        sendBroadcast({
            type: 'screen-share-takeover'
        });
    }
    try {
        await room.localParticipant.setScreenShareEnabled(true);
        isScreenSharing = true;
        bgDirty = true;
        screenShareBtn.classList.add('text-green-400');
        screenShareBtn.classList.remove('text-white');
        if (screenShareActiveDot) screenShareActiveDot.classList.remove('hidden');
        sendBroadcast({
            type: 'screen-share-start',
            name: authName,
            sender_id: currentUserId
        });
        const pub = room.localParticipant.getTrackPublication(LiveKit.Track.Source.ScreenShare);
        if (pub && pub.track) {
            screenShareStream = new MediaStream();
            const tracks = pub.track.mediaStream?.getVideoTracks() || [];
            tracks.forEach(t => {
                t.addEventListener('ended', () => setTimeout(() => stopScreenShare(), 500));
                screenShareStream.addTrack(t);
            });
            showLocalScreenShareUI(true, tracks[0]);
        } else {
            showLocalScreenShareUI(true);
        }
    } catch (e) {
        console.warn('Screen share failed:', e);
        const msg = e.name === 'NotAllowedError'
            ? 'Izin screen share ditolak atau dibatalkan.'
            : 'Screen share gagal: ' + (e.message || e.name);
        alert(msg);
    }
}

async function stopScreenShare() {
    console.log('stopScreenShare called, reason:', new Error().stack);
    if (room) {
        await room.localParticipant.setScreenShareEnabled(false).catch(() => {});
    }
    if (screenShareStream) {
        screenShareStream.getTracks().forEach(t => t.stop());
        screenShareStream = null;
    }
    isScreenSharing = false;
    bgDirty = true;
    screenShareBtn.classList.remove('text-green-400');
    screenShareBtn.classList.add('text-white');
    if (screenShareActiveDot) screenShareActiveDot.classList.add('hidden');
    sendBroadcast({
        type: 'screen-share-stop'
    });
    showLocalScreenShareUI(false);
    hideScreenShare();
}

function showLocalScreenShareUI(showing, track) {
    if (showing && track) {
        screenShareContainer.classList.remove('hidden');
        screenShareContainer.style.display = 'flex';
        screenShareContainer.style.alignItems = 'center';
        screenShareContainer.style.justifyContent = 'center';
        screenShareVideo.srcObject = new MediaStream([track]);
        if (screenShareLabel) screenShareLabel.textContent = 'Anda sedang share layar';
        if (stopScreenShareBtn) stopScreenShareBtn.classList.remove('hidden');
    } else {
        screenShareContainer.classList.add('hidden');
        screenShareContainer.style.display = '';
        screenShareVideo.srcObject = null;
        if (stopScreenShareBtn) stopScreenShareBtn.classList.add('hidden');
    }
}

function showRemoteScreenShare(track, participant) {
    const name = participant.name || participant.identity || 'Participant';
    hideScreenShare();
    screenShareContainer.classList.remove('hidden');
    screenShareContainer.style.display = 'flex';
    screenShareContainer.style.alignItems = 'center';
    screenShareContainer.style.justifyContent = 'center';
    track.attach(screenShareVideo);
    bgDirty = true;
    if (screenShareLabel) screenShareLabel.textContent = name + ' sedang share layar';
    if (stopScreenShareBtn) stopScreenShareBtn.classList.add('hidden');
}

function hideScreenShare() {
    if (isScreenSharing) return;
    screenShareContainer.classList.add('hidden');
    screenShareContainer.style.display = '';
    screenShareVideo.srcObject = null;
    bgDirty = true;
}

if (screenShareBtn) {
    screenShareBtn.addEventListener('click', toggleScreenShare);
}
if (stopScreenShareBtn) {
    stopScreenShareBtn.addEventListener('click', () => stopScreenShare());
}
const pinScreenShareBtn = document.getElementById('pinScreenShareBtn');
if (pinScreenShareBtn) {
    pinScreenShareBtn.addEventListener('click', () => togglePin('screen-share'));
}
