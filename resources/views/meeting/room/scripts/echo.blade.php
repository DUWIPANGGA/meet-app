// ======================== ECHO / REVERB SIGNALING ========================
async function subscribeEchoChannel() {
    while (!window.Echo) {
        await new Promise(r => setTimeout(r, 50));
    }
    const pusher = window.Echo?.connector?.pusher;
    if (pusher?.connection) {
        pusher.connection.bind('connected', () => setConnectionStatus('LiveKit: terhubung',
            'text-emerald-400'));
        pusher.connection.bind('disconnected', () => setConnectionStatus('LiveKit: terputus',
            'text-amber-300'));
    }
    const channel = window.Echo.private('meeting.' + meetingId);
    channel.listen('.WebRTCSignal', async (e) => {
        const data = e.data ?? {};
        if (data.type === 'transcription') {
            if (data.sender_id && Number(data.sender_id) === currentUserId) return;
            appendTranscriptMessage(data.sender_id, data.sender_name, data.text);
            return;
        }
        if (data.type === 'start-recording-broadcast') {
            if (transcriptMessages) transcriptMessages.innerHTML =
                '<div id="emptyTranscriptMsg" class="text-gray-500 text-center py-8 italic text-xs">Belum ada transkrip aktif.</div>';
            if (showNotulensiBtn) showNotulensiBtn.classList.add('hidden');
            if (pdfBtn) pdfBtn.classList.add('opacity-40', 'pointer-events-none');
            if (!liveTranscriptionActive) {
                liveTranscriptionActive = true;
                updateSidebarStatus('Menerima transkrip...', 'text-emerald-400', 'bg-emerald-500');
            }
            if (transcriptSidebar) transcriptSidebar.classList.remove('collapsed');
            if (openSidebarBtn) openSidebarBtn.classList.add('hidden');
            const dot = document.getElementById('aiNotulenActiveDot');
            if (dot) dot.classList.remove('hidden');
            const headerInd = document.getElementById('aiNotulenHeaderIndicator');
            if (headerInd) headerInd.classList.remove('hidden');
            return;
        }
        if (data.type === 'stop-recording-broadcast') {
            if (liveTranscriptionActive) {
                stopLiveTranscription();
            }
            const dot = document.getElementById('aiNotulenActiveDot');
            if (dot) dot.classList.add('hidden');
            const headerInd = document.getElementById('aiNotulenHeaderIndicator');
            if (headerInd) headerInd.classList.add('hidden');
            return;
        }
        if (data.type === 'camera-toggle') {
            const identity = String(data.sender_id);
            const safeKey = identity.replace(/[^a-zA-Z0-9_-]/g, '_');
            const avatar = document.getElementById(`remote-avatar-${safeKey}`);
            if (avatar) {
                if (data.isOff) avatar.classList.remove('hidden');
                else avatar.classList.add('hidden');
            }
        }
        if (data.type === 'screen-share-start' || data.type === 'screen-share-takeover') {
            if (data.sender_id && Number(data.sender_id) === currentUserId) return;
            if (isScreenSharing) stopScreenShare();
        }
        if (data.type === 'screen-share-stop') {
            if (!isScreenSharing) hideScreenShare();
        }
        if (data.type === 'screen-recording-start') {
            if (data.sender_id && Number(data.sender_id) === currentUserId) return;
            recordingByOther = true;
            if (recordScreenBtn) {
                recordScreenBtn.classList.add('opacity-40', 'pointer-events-none');
            }
            showRecordingPopup(true, data.name || 'Peserta lain');
        }
        if (data.type === 'screen-recording-stop') {
            recordingByOther = false;
            if (recordScreenBtn) {
                recordScreenBtn.classList.remove('opacity-40', 'pointer-events-none');
            }
            hideRecordingPopup();
        }
    });
}
