// ======================== DEVICE STATE PERSISTENCE ========================
function saveDeviceState() {
    try {
        localStorage.setItem('device_' + meetingId, JSON.stringify({
            m: isMuted,
            c: isCameraOff
        }));
    } catch (e) {}
}

function toggleMicIcons(muted) {
    const mic = document.getElementById('micIcon');
    const micOff = document.getElementById('micOffIcon');
    if (mic && micOff) {
        mic.classList.toggle('hidden', muted);
        micOff.classList.toggle('hidden', !muted);
    }
}

function toggleCamIcons(off) {
    const cam = document.getElementById('camIcon');
    const camOff = document.getElementById('camOffIcon');
    if (cam && camOff) {
        cam.classList.toggle('hidden', off);
        camOff.classList.toggle('hidden', !off);
    }
}

function applyDeviceState() {
    if (muteBtn) {
        muteBtn.classList.toggle('text-red-400', isMuted);
        muteBtn.classList.toggle('text-white', !isMuted);
        toggleMicIcons(isMuted);
    }
    if (cameraBtn) {
        cameraBtn.classList.toggle('text-red-400', isCameraOff);
        cameraBtn.classList.toggle('text-white', !isCameraOff);
        toggleCamIcons(isCameraOff);
    }
    document.body.classList.toggle('camera-off', isCameraOff);
    if (localAvatar) localAvatar.classList.toggle('hidden', !isCameraOff);
    if (localAvatarText && isCameraOff) localAvatarText.textContent = authName.charAt(0).toUpperCase();
}
applyDeviceState();
const localAvatarCircle = document.getElementById('localAvatarCircle');
