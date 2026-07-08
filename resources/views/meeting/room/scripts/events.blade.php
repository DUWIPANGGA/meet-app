// ======================== EVENT LISTENERS ========================
if (enableAudioBtn) enableAudioBtn.addEventListener('click', () => {
    document.querySelectorAll('#remoteVideos video').forEach(v => {
        v.muted = false;
        v.play().catch(e => console.warn);
    });
    audioEnabledByUser = true;
    enableAudioBtn.style.display = 'none';
});

if (muteBtn) {
    muteBtn.addEventListener('click', () => {
        isMuted = !isMuted;
        saveDeviceState();
        if (room) {
            const pub = room.localParticipant.getTrackPublication(LiveKit.Track.Source.Microphone);
            if (pub && pub.track) {
                if (isMuted) {
                    pub.track.mute().catch(e => console.warn(e));
                } else {
                    pub.track.unmute().catch(e => console.warn(e));
                }
            }
        }
        if (localStream) {
            localStream.getAudioTracks().forEach(t => t.enabled = !isMuted);
        }
        if (isMuted) {
            muteBtn.classList.add('text-red-400');
            muteBtn.classList.remove('text-white');
        } else {
            muteBtn.classList.add('text-white');
            muteBtn.classList.remove('text-red-400');
        }
        toggleMicIcons(isMuted);
    });
}

if (cameraBtn) {
    cameraBtn.addEventListener('click', () => {
        isCameraOff = !isCameraOff;
        saveDeviceState();
        if (room) {
            const pub = room.localParticipant.getTrackPublication(LiveKit.Track.Source.Camera);
            if (pub && pub.track) {
                if (isCameraOff) {
                    pub.track.mute().catch(e => console.warn(e));
                } else {
                    pub.track.unmute().catch(e => console.warn(e));
                }
            }
        }
        if (localStream) {
            localStream.getVideoTracks().forEach(t => t.enabled = !isCameraOff);
        }
        const localAvatar = document.getElementById('localAvatar');
        const localAvatarText = document.getElementById('localAvatarText');
        if (isCameraOff) {
            cameraBtn.classList.add('text-red-400');
            cameraBtn.classList.remove('text-white');
            if (localAvatar) {
                localAvatar.classList.remove('hidden');
                if (localAvatarText) localAvatarText.textContent = authName.charAt(0).toUpperCase();
            }
            startAudioMonitor();
        } else {
            cameraBtn.classList.add('text-white');
            cameraBtn.classList.remove('text-red-400');
            if (localAvatar) localAvatar.classList.add('hidden');
            stopAudioMonitor();
        }
        toggleCamIcons(isCameraOff);
        sendBroadcast({
            type: 'camera-toggle',
            isOff: isCameraOff
        });
    });
}

const aiNotulenActiveDot = document.getElementById('aiNotulenActiveDot');
const confirmEndMeetingModal = document.getElementById('confirmEndMeetingModal');
const cancelEndMeetingBtn = document.getElementById('cancelEndMeetingBtn');
const confirmEndMeetingBtn = document.getElementById('confirmEndMeetingBtn');
const confirmEndTitle = document.getElementById('confirmEndTitle');
const confirmEndDesc = document.getElementById('confirmEndDesc');

if (leaveBtn) leaveBtn.addEventListener('click', () => {
    const isActive = liveTranscriptionActive || (typeof pipelineStatus !== 'undefined' && pipelineStatus === 'processing');
    if (isActive) {
        if (confirmEndTitle) confirmEndTitle.textContent = 'Akhiri Rapat?';
        if (confirmEndDesc) confirmEndDesc.textContent = liveTranscriptionActive
            ? 'AI Notulen sedang aktif. Mengakhiri rapat akan menghentikan proses transkrip dan notulensi.'
            : 'Proses notulensi sedang berjalan. Mengakhiri rapat akan membatalkan proses ini.';
        if (confirmEndMeetingModal) confirmEndMeetingModal.classList.remove('hidden');
    } else {
        leaveMeeting();
    }
});

if (cancelEndMeetingBtn) cancelEndMeetingBtn.addEventListener('click', () => {
    if (confirmEndMeetingModal) confirmEndMeetingModal.classList.add('hidden');
});

if (confirmEndMeetingBtn) confirmEndMeetingBtn.addEventListener('click', () => {
    if (confirmEndMeetingModal) confirmEndMeetingModal.classList.add('hidden');
    leaveMeeting();
});

const participantBtn = document.getElementById('participantBtn');
const participantSidebar = document.getElementById('participantSidebar');
const closeParticipantBtn = document.getElementById('closeParticipantBtn');

if (participantBtn && participantSidebar) {
    participantBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (participantSidebar.classList.contains('translate-x-full')) {
            participantSidebar.classList.remove('translate-x-full');
            updateParticipantUI();
        } else {
            participantSidebar.classList.add('translate-x-full');
        }
    });
}
if (closeParticipantBtn && participantSidebar) {
    closeParticipantBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        participantSidebar.classList.add('translate-x-full');
    });
}

const aiNotulenTriggerBtn = document.getElementById('aiNotulenTriggerBtn');
const confirmNotulenModal = document.getElementById('confirmNotulenModal');
const cancelNotulenBtn = document.getElementById('cancelNotulenBtn');
const simpanNotulenBtn = document.getElementById('simpanNotulenBtn');
const aiNotulenActiveDot = document.getElementById('aiNotulenActiveDot');

if (aiNotulenTriggerBtn) {
    aiNotulenTriggerBtn.addEventListener('click', async () => {
        if (liveTranscriptionActive) {
            if (confirmNotulenModal) confirmNotulenModal.classList.remove('hidden');
        } else {
            try {
                await fetch('/meeting/{{ $meeting->id }}/start-recording', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                await startLiveTranscription();
                sendBroadcast({
                    type: 'start-recording-broadcast'
                });
                if (aiNotulenActiveDot) aiNotulenActiveDot.classList.remove('hidden');
                const headerInd = document.getElementById('aiNotulenHeaderIndicator');
                if (headerInd) headerInd.classList.remove('hidden');
                if (transcriptSidebar) transcriptSidebar.classList.remove('collapsed');
                if (openSidebarBtn) openSidebarBtn.classList.add('hidden');
            } catch (err) {
                alert('Gagal memulai notulensi: ' + err.message);
            }
        }
    });
}

if (cancelNotulenBtn) {
    cancelNotulenBtn.addEventListener('click', () => {
        if (confirmNotulenModal) confirmNotulenModal.classList.add('hidden');
    });
}

if (simpanNotulenBtn) {
    simpanNotulenBtn.addEventListener('click', () => {
        if (confirmNotulenModal) confirmNotulenModal.classList.add('hidden');
        if (!liveTranscriptionActive) return;
        sendBroadcast({
            type: 'stop-recording-broadcast'
        });
        stopLiveTranscription();
        if (aiNotulenActiveDot) aiNotulenActiveDot.classList.add('hidden');
        const headerInd = document.getElementById('aiNotulenHeaderIndicator');
        if (headerInd) headerInd.classList.add('hidden');
        triggerGeminiNotulensi();
    });
}

if (toggleSidebarBtn) toggleSidebarBtn.addEventListener('click', () => {
    transcriptSidebar.classList.add('collapsed');
    openSidebarBtn.classList.remove('hidden');
    const activeDot = document.getElementById('sidebarActiveDot');
    if (activeDot && liveTranscriptionActive) activeDot.classList.remove('hidden');
});
if (openSidebarBtn) openSidebarBtn.addEventListener('click', () => {
    transcriptSidebar.classList.remove('collapsed');
    openSidebarBtn.classList.add('hidden');
    const activeDot = document.getElementById('sidebarActiveDot');
    if (activeDot) activeDot.classList.add('hidden');
    transcriptMessages.scrollTop = transcriptMessages.scrollHeight;
});

const closeModalBtns = [document.getElementById('closeNotulensiModalBtn'), document.getElementById(
    'closeNotulensiModalFooterBtn')];
closeModalBtns.forEach(btn => btn && btn.addEventListener('click', () => openNotulensiModal(false)));
if (notulensiModal) notulensiModal.addEventListener('click', (e) => {
    if (e.target === notulensiModal) openNotulensiModal(false);
});

// Spotlight overlay click
document.addEventListener('click', (e) => {
    const overlayCard = e.target.closest('.spotlight-overlay');
    if (overlayCard && currentLayout === 'spotlight') {
        spotlightTargetIdentity = overlayCard.dataset.identity;
        updateParticipantUI();
    }
});

// Recording button events
if (recordScreenBtn) {
    recordScreenBtn.addEventListener('click', async () => {
        if (isRecordingScreen) {
            await stopScreenRecording();
        } else if (isCountdownActive) {
            cancelCountdown();
        } else {
            await startCountdown().then(() => startScreenRecording());
        }
    });
}
const recordingPopupClose = document.getElementById('recordingPopupClose');
if (recordingPopupClose) {
    recordingPopupClose.addEventListener('click', (e) => {
        e.stopPropagation();
        hideRecordingPopup();
    });
}

// ── Layout Dropdown Toggle ──
(function() {
    const layoutBtn = document.getElementById('layoutBtn');
    const layoutDropdown = document.getElementById('layoutDropdown');

    if (!layoutBtn || !layoutDropdown) return;

    function showLayoutDropdown() {
        layoutDropdown.style.display = 'block';
        requestAnimationFrame(() => {
            layoutDropdown.style.opacity = '1';
        });
    }

    function hideLayoutDropdown() {
        layoutDropdown.style.opacity = '0';
        setTimeout(() => {
            layoutDropdown.style.display = 'none';
        }, 200);
    }

    layoutBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isHidden = layoutDropdown.style.display === 'none' || layoutDropdown.style.display === '';
        if (isHidden) {
            showLayoutDropdown();
        } else {
            hideLayoutDropdown();
        }
    });

    document.addEventListener('click', function(e) {
        if (!layoutBtn.contains(e.target) && !layoutDropdown.contains(e.target)) {
            layoutDropdown.style.display = 'none';
            layoutDropdown.style.opacity = '0';
        }
    });

    layoutDropdown.querySelectorAll('button').forEach(function(btn) {
        btn.addEventListener('click', function() {
            applyLayout(btn.dataset.layout);
            hideLayoutDropdown();
        });
    });

    const active = layoutDropdown.querySelector('[data-layout="' + currentLayout + '"]');
    if (active) active.classList.add('active-layout');
})();

// ── Navbar Layout Dropdown Toggle ──
(function() {
    const layoutNavBtn = document.getElementById('layoutNavBtn');
    const layoutNavDropdown = document.getElementById('layoutNavDropdown');

    if (!layoutNavBtn || !layoutNavDropdown) return;

    function showNavLayoutDropdown() {
        layoutNavDropdown.style.display = 'block';
        requestAnimationFrame(function() {
            layoutNavDropdown.style.opacity = '1';
        });
    }

    function hideNavLayoutDropdown() {
        layoutNavDropdown.style.opacity = '0';
        setTimeout(function() {
            layoutNavDropdown.style.display = 'none';
        }, 200);
    }

    layoutNavBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isHidden = layoutNavDropdown.style.display === 'none' || layoutNavDropdown.style.display === '';
        if (isHidden) {
            showNavLayoutDropdown();
        } else {
            hideNavLayoutDropdown();
        }
    });

    document.addEventListener('click', function(e) {
        if (!layoutNavBtn.contains(e.target) && !layoutNavDropdown.contains(e.target)) {
            layoutNavDropdown.style.display = 'none';
            layoutNavDropdown.style.opacity = '0';
        }
    });

    layoutNavDropdown.querySelectorAll('button').forEach(function(btn) {
        btn.addEventListener('click', function() {
            applyLayout(btn.dataset.layout);
            hideNavLayoutDropdown();
        });
    });

    function syncNavLayoutActive() {
        layoutNavDropdown.querySelectorAll('button').forEach(function(btn) {
            btn.classList.toggle('active-layout', btn.dataset.layout === currentLayout);
        });
    }
    syncNavLayoutActive();
})();
