let audioMonitorInterval = null;

function startAudioMonitor() {
    stopAudioMonitor();
    audioMonitorInterval = setInterval(() => {
        const level = room?.localParticipant?.audioLevel || 0;
        if (!localAvatarCircle) return;
        if (level > 0.02) {
            localAvatarCircle.classList.add('speaking-ring');
            const localId = 'local_' + currentUserId;
            recordingSpeakerQueue = [localId, ...recordingSpeakerQueue.filter(id => id !== localId)].slice(
                0, 20);
            if (recordingVideoCache.has(localId)) recordingVideoCache.get(localId).isSpeaking = true;
        } else {
            localAvatarCircle.classList.remove('speaking-ring');
            const localId = 'local_' + currentUserId;
            if (recordingVideoCache.has(localId)) recordingVideoCache.get(localId).isSpeaking = false;
        }
    }, 200);
}

function stopAudioMonitor() {
    if (audioMonitorInterval) {
        clearInterval(audioMonitorInterval);
        audioMonitorInterval = null;
    }
    if (localAvatarCircle) localAvatarCircle.classList.remove('speaking-ring');
}
