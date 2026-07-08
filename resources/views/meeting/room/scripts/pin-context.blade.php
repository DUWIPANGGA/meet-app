// ======================== PIN VIEW ========================
function togglePin(identity) {
    const idx = pinnedIdentities.indexOf(identity);
    if (idx >= 0) {
        pinnedIdentities.splice(idx, 1);
    } else {
        pinnedIdentities.push(identity);
    }
    updateParticipantUI();
    updatePinIndicators();
}

function isPinned(identity) {
    return pinnedIdentities.indexOf(identity) >= 0;
}

function updatePinIndicators() {
    document.querySelectorAll('[id^="remote-card-"]').forEach(card => {
        const id = card.dataset?.identity || '';
        card.classList.toggle('pinned-card', isPinned(id));
    });
    const localCard = document.getElementById('localVideoContainer');
    if (localCard) {
        localCard.classList.toggle('pinned-card', isPinned(String(currentUserId)));
    }
    document.querySelectorAll('[id^="pin-btn-"]').forEach(el => {
        el.classList.toggle('active', isPinned(el.dataset.identity));
    });
    const pinSsBtn = document.getElementById('pinScreenShareBtn');
    if (pinSsBtn) {
        pinSsBtn.classList.toggle('active', pinnedIdentities.indexOf('screen-share') >= 0);
    }
}

// ======================== CONTEXT MENU ========================
let contextTargetIdentity = null;

function showContextMenu(event, identity, displayName) {
    event.preventDefault();
    contextTargetIdentity = identity;
    const menu = document.getElementById('contextMenu');
    if (!menu) return;
    const pinBtn = document.getElementById('contextPinBtn');
    if (pinBtn) {
        if (isPinned(identity)) {
            pinBtn.innerHTML =
                '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/></svg> Unpin ' +
                escapeHtml(displayName);
        } else {
            pinBtn.innerHTML =
                '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/></svg> Pin ' +
                escapeHtml(displayName);
        }
        pinBtn.onclick = () => {
            togglePin(identity);
            hideContextMenu();
        };
    }
    menu.classList.remove('hidden');
    menu.style.left = Math.min(event.clientX, window.innerWidth - 150) + 'px';
    menu.style.top = Math.min(event.clientY, window.innerHeight - 80) + 'px';
}

function hideContextMenu() {
    const menu = document.getElementById('contextMenu');
    if (menu) menu.classList.add('hidden');
    contextTargetIdentity = null;
}

const participantListEl = document.getElementById('participantList');
if (participantListEl) {
    participantListEl.addEventListener('contextmenu', (e) => {
        const item = e.target.closest('[data-identity]');
        if (!item) return;
        e.preventDefault();
        const identity = item.dataset.identity;
        const nameSpan = item.querySelector('span');
        const displayName = nameSpan?.textContent?.trim() || identity;
        showContextMenu(e, identity, displayName);
    });
}
document.addEventListener('click', (e) => {
    if (!e.target.closest('#contextMenu')) hideContextMenu();
});
