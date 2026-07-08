// ======================== FUNGSI UTILITY ========================
function setConnectionStatus(text, colorClass) {
    if (connectionStatusEl) {
        connectionStatusEl.textContent = text;
        connectionStatusEl.className = 'text-xs ' + (colorClass || 'text-amber-300');
    }
}

function updateSidebarStatus(statusText, textColorClass, pulseColorClass) {
    if (transcribeStatusEl) {
        transcribeStatusEl.textContent = statusText;
        transcribeStatusEl.className = `${textColorClass} font-semibold uppercase tracking-wider`;
    }
    if (sidebarStatusIndicator) sidebarStatusIndicator.className =
        `relative inline-flex rounded-full h-2 w-2 ${pulseColorClass}`;
    if (sidebarPulse) sidebarPulse.className =
        `animate-ping absolute inline-flex h-full w-full rounded-full ${pulseColorClass} opacity-75`;
}

function escapeHtml(str) {
    return str?.replace(/[&<>]/g, m => m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;') || '';
}

function escapeHtmlAttr(str) {
    return (str ?? '').replace(/[&<>"']/g, m => {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        if (m === '"') return '&quot;';
        if (m === "'") return '&#39;';
        return m;
    });
}

function appendTranscriptMessage(userId, name, text) {
    if (!transcriptMessages) return;
    const emptyMsg = document.getElementById('emptyTranscriptMsg');
    if (emptyMsg) emptyMsg.remove();

    if (userId === lastSpeakerId && lastMessageElement) {
        const textSpan = lastMessageElement.querySelector('.transcript-text');
        if (textSpan) {
            textSpan.textContent += ' ' + text;
            transcriptMessages.scrollTop = transcriptMessages.scrollHeight;
            return;
        }
    }

    const time = new Date().toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
    });
    const isMe = Number(userId) === currentUserId;
    const nameColors = ['text-indigo-400', 'text-emerald-400', 'text-amber-400', 'text-pink-400', 'text-sky-400',
        'text-purple-400'
    ];
    const avatarBgColors = ['bg-indigo-500/20', 'bg-emerald-500/20', 'bg-amber-500/20', 'bg-pink-500/20',
        'bg-sky-500/20', 'bg-purple-500/20'
    ];
    const colorIndex = Number(userId) % nameColors.length;
    const nameColor = isMe ? 'text-violet-400' : nameColors[colorIndex];
    const avatarBg = isMe ? 'bg-violet-500/20' : avatarBgColors[colorIndex];
    const avatarTextColor = isMe ? 'text-violet-400' : nameColors[colorIndex];
    const initial = (name || '?').charAt(0).toUpperCase();

    const div = document.createElement('div');
    div.className = 'flex gap-2.5 items-start';
    div.innerHTML = `
        <div class="w-8 h-8 rounded-full ${avatarBg} flex items-center justify-center ${avatarTextColor} text-xs font-bold shrink-0 mt-0.5">${initial}</div>
        <div class="min-w-0 flex-1">
            <div class="flex items-baseline gap-2 mb-0.5">
                <span class="${nameColor} font-semibold text-xs">${escapeHtml(name)}</span>
                <span class="text-gray-600 text-[10px] font-mono">${time}</span>
            </div>
            <div class="bg-white/5 rounded-lg rounded-tl-none px-3 py-2 text-gray-300 text-xs leading-relaxed transcript-text">${escapeHtml(text)}</div>
        </div>
    `;
    transcriptMessages.appendChild(div);
    transcriptMessages.scrollTop = transcriptMessages.scrollHeight;
    lastSpeakerId = userId;
    lastMessageElement = div;
}

async function syncTranscriptToLaravel(text, speakerId, speakerName) {
    try {
        await fetch(saveLiveTranscriptUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                text,
                speaker_id: speakerId,
                speaker_name: speakerName
            })
        });
    } catch (e) {
        console.error(e);
    }
}

function sendBroadcast(data) {
    fetch(broadcastUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    });
}

async function leaveMeeting() {
    stopAudioMonitor();
    if (screenShareStream) {
        screenShareStream.getTracks().forEach(t => t.stop());
        screenShareStream = null;
    }
    isScreenSharing = false;
    if (room) {
        await room.disconnect();
        room = null;
    }
    const url = (isCreator || isAdmin) ? endUrl : leaveUrl;
    fetch(url, {
        method: 'POST',
        keepalive: true,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).finally(() => {
        const redirectUrl = isAdmin ?
            '{{ route('admin.meetings.index') }}' :
            '/join';
        window.location.href = redirectUrl;
    });
}

async function fetchLiveKitToken() {
    const res = await fetch(livekitTokenUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    });
    if (!res.ok) throw new Error('Gagal mendapatkan token LiveKit');
    return res.json();
}
