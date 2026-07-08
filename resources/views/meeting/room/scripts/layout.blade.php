let currentPage = 0;
const PER_PAGE = 6;

function getMainSpeakerIdentity() {
    if (activeSpeakerIdentity) return activeSpeakerIdentity;
    if (pinnedIdentities.length > 0) return pinnedIdentities[0];
    const remotes = getParticipantCards().filter(c => c.id !== 'localVideoContainer');
    if (remotes.length > 0) return remotes[0].dataset?.identity || null;
    return String(currentUserId);
}

function isStripLayout() {
    return currentLayout === 'speaker' || currentLayout === 'sidebar' || currentLayout === 'spotlight';
}

function getNonMainCount() {
    const cards = getParticipantCards();
    if (!isStripLayout()) return cards.length;
    const mainId = getMainSpeakerIdentity();
    return cards.filter(c => {
        if (c.id === 'localVideoContainer') return mainId !== String(currentUserId);
        return c.dataset?.identity !== mainId;
    }).length;
}

function goToPage(page) {
    const totalPages = Math.ceil(getNonMainCount() / PER_PAGE);
    currentPage = Math.max(0, Math.min(page, totalPages - 1));
    updateParticipantUI();
}

function getCurrentPageCards(cards) {
    const start = currentPage * PER_PAGE;
    return cards.slice(start, start + PER_PAGE);
}

function updatePaginationDots() {
    const container = document.getElementById('paginationDots');
    if (!container) return;

    // Mobile: no dots, user swipes strip instead
    const isMobile = window.innerWidth < 768;
    if (isMobile && isStripLayout()) {
        container.style.display = 'none';
        container.innerHTML = '';
        return;
    }

    const totalPages = Math.ceil(getNonMainCount() / PER_PAGE);
    if (totalPages <= 1) {
        container.style.display = 'none';
        container.innerHTML = '';
        return;
    }
    container.style.display = 'flex';
    container.innerHTML = '';
    for (let i = 0; i < totalPages; i++) {
        const dot = document.createElement('button');
        dot.className = 'pagination-dot w-2 h-2 md:w-3 md:h-3 rounded-full transition-all duration-300 ' +
            (i === currentPage ? 'bg-violet-500 scale-125' : 'bg-white/30 hover:bg-white/50');
        dot.setAttribute('aria-label', 'Halaman ' + (i + 1));
        dot.addEventListener('click', () => goToPage(i));
        container.appendChild(dot);
    }
}

function getParticipantCards() {
    const remoteContainer = document.getElementById('remoteVideos');
    const grid = document.getElementById('videoGridMain');
    const remoteCards = new Map();
    // From remoteVideos (not yet moved by layout)
    if (remoteContainer) {
        remoteContainer.querySelectorAll(':scope > [id^="remote-card-"]').forEach(el => {
            remoteCards.set(el.id, el);
        });
    }
    // From grid (moved by layout — recursive to include strip/sidebar children)
    if (grid) {
        grid.querySelectorAll('[id^="remote-card-"]').forEach(el => {
            remoteCards.set(el.id, el);
        });
    }
    const all = [];
    const localEl = document.getElementById('localVideoContainer');
    if (localEl) all.push(localEl);
    remoteCards.forEach(el => all.push(el));
    return all;
}

let _pendingUIUpdate = false;

function scheduleParticipantUIUpdate() {
    if (_pendingUIUpdate) return;
    _pendingUIUpdate = true;
    requestAnimationFrame(() => {
        _pendingUIUpdate = false;
        updateParticipantUI();
    });
}

function applyLayout(mode) {
    currentLayout = mode;
    localStorage.setItem('layout_' + meetingId, mode);
    // Reset active speaker identity to first remote if not set
    if (!activeSpeakerIdentity) {
        activeSpeakerIdentity = getMainSpeakerIdentity();
    }
    currentPage = 0;
    _lastUIUpdateKey = '';
    updateParticipantUI();
    // Update dropdown active state
    document.querySelectorAll('#layoutDropdown button').forEach(btn => {
        btn.classList.toggle('active-layout', btn.dataset.layout === mode);
    });
    document.querySelectorAll('#layoutNavDropdown button').forEach(btn => {
        btn.classList.toggle('active-layout', btn.dataset.layout === mode);
    });
}

function getVideoCardByIdentity(identity) {
    const safeKey = String(identity).replace(/[^a-zA-Z0-9_-]/g, '_');
    if (String(identity) === String(currentUserId)) return document.getElementById('localVideoContainer');
    return document.getElementById('remote-card-' + safeKey);
}

let _lastUIUpdateKey = '';

function updateParticipantUI() {
    const grid = document.getElementById('videoGridMain');
    const remoteContainer = document.getElementById('remoteVideos');
    if (!grid || !remoteContainer) return;

    // Get ALL remote cards (may be in remoteVideos or moved to grid by layout)
    const allCards = getParticipantCards();
    const remotes = allCards.filter(c => c.id !== 'localVideoContainer');
    const totalCount = allCards.length;

    // Reset currentPage if out of bounds
    const totalPages = Math.ceil(getNonMainCount() / PER_PAGE);
    if (currentPage >= totalPages) currentPage = Math.max(0, totalPages - 1);

    const remoteIdentities = remotes.map(el => el.dataset?.identity || el.id).join(',');
    const key = `${currentLayout}|${currentPage}|${totalCount}|${remoteIdentities}`;
    if (_lastUIUpdateKey === key) return;
    _lastUIUpdateKey = key;

    // Remove all layout classes and inline grid styles
    grid.classList.remove('layout-speaker', 'layout-sidebar', 'layout-spotlight');
    grid.className = 'min-w-0 relative z-0';
    grid.style.gridTemplateColumns = '';
    grid.style.gridTemplateRows = '';

    if (currentLayout === 'speaker') {
        applySpeakerLayout(grid, remotes, totalCount);
    } else if (currentLayout === 'sidebar') {
        applySidebarLayout(grid, remotes, totalCount);
    } else if (currentLayout === 'spotlight') {
        applySpotlightLayout(grid, remotes, totalCount);
    } else {
        applyGridLayout(grid, remotes, totalCount);
    }

    const badge = document.getElementById('participantBadge');
    const countText = document.getElementById('participantCountText');
    if (badge) badge.textContent = totalCount;
    if (countText) countText.textContent = totalCount;
    updateParticipantSidebar();

    // Alone mode toggle
    const container = document.getElementById('meetingContainer');
    if (container) {
        container.classList.toggle('alone-mode', totalCount === 1);
    }

    updatePaginationDots();
}

function applyGridLayout(grid, remotes, totalCount) {

    const paginatedCards = getCurrentPageCards(getParticipantCards());
    const visibleCount = paginatedCards.length;

    grid.classList.add('grid', 'gap-2', 'w-full', 'h-full');
    grid.classList.remove('grid-cols-1', 'grid-cols-2', 'grid-cols-3', 'grid-cols-4');
    grid.classList.remove('grid-rows-1', 'grid-rows-2', 'grid-rows-3', 'grid-rows-4', 'grid-rows-5');

    const isMobile = window.innerWidth < 768;
    let cols, rows;

    if (visibleCount <= 1) {
        cols = 1;
        rows = 1;
    } else if (isMobile) {
        if (visibleCount === 2) {
            cols = 1;
            rows = 2;
        } else {
            cols = 2;
            rows = Math.ceil(visibleCount / cols);
        }
    } else {
        if (visibleCount === 2) {
            cols = 2;
            rows = 1;
        } else if (visibleCount <= 4) {
            cols = 2;
            rows = Math.ceil(visibleCount / cols);
        } else {
            cols = 3;
            rows = Math.ceil(visibleCount / cols);
        }
    }

    grid.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;
    grid.style.gridTemplateRows = `repeat(${rows}, 1fr)`;

    const allCards = getParticipantCards();
    paginatedCards.forEach(el => {
        el.style.display = '';
        el.classList.remove('speaker-main-video', 'spotlight-main', 'spotlight-overlay');
    });
    allCards.forEach(el => {
        if (!paginatedCards.includes(el)) {
            el.style.display = 'none';
        }
    });

    grid.querySelectorAll('.speaker-strip, .sidebar-main-area, .sidebar-vertical-strip').forEach(el => el.remove());
}

function applySpeakerLayout(grid, remotes, totalCount) {
    grid.classList.add('layout-speaker');
    const cards = getParticipantCards();
    const mainId = getMainSpeakerIdentity();

    let strip = grid.querySelector('.speaker-strip');
    if (!strip) {
        strip = document.createElement('div');
        strip.className = 'speaker-strip';
        grid.appendChild(strip);
    }
    strip.innerHTML = '';

    // Hide ALL cards first
    cards.forEach(card => {
        card.classList.remove('speaker-main-video');
        card.style.display = 'none';
    });

    // Show main speaker in grid
    let mainCard = null;
    cards.forEach(card => {
        const id = card.dataset?.identity || String(currentUserId);
        if (id === mainId || (mainId === null && card.id === 'localVideoContainer')) {
            card.classList.add('speaker-main-video');
            card.style.display = '';
            if (card.parentElement !== grid) grid.insertBefore(card, strip);
            mainCard = card;
        }
    });

    // Non-main cards go to strip
    const nonMain = cards.filter(c => c !== mainCard);
    const isMobile = window.innerWidth < 768;
    const stripCards = isMobile ? nonMain : getCurrentPageCards(nonMain);
    stripCards.forEach(card => {
        card.style.display = '';
        if (card.parentElement !== strip) strip.appendChild(card);
    });
}

function applySidebarLayout(grid, remotes, totalCount) {
    grid.classList.add('layout-sidebar');
    let mainArea = grid.querySelector('.sidebar-main-area');
    let vstrip = grid.querySelector('.sidebar-vertical-strip');
    if (!mainArea) {
        mainArea = document.createElement('div');
        mainArea.className = 'sidebar-main-area';
        grid.appendChild(mainArea);
    }
    if (!vstrip) {
        vstrip = document.createElement('div');
        vstrip.className = 'sidebar-vertical-strip';
        grid.appendChild(vstrip);
    }

    const cards = getParticipantCards();
    const mainId = getMainSpeakerIdentity();

    const nonMain = [];
    cards.forEach(card => {
        const id = card.dataset?.identity || String(currentUserId);
        if (id === mainId || (mainId === null && card.id === 'localVideoContainer')) {
            card.style.display = '';
            if (card.parentElement !== mainArea) mainArea.appendChild(card);
        } else {
            nonMain.push(card);
        }
    });

    const isMobile = window.innerWidth < 768;
    const stripCards = isMobile ? nonMain : getCurrentPageCards(nonMain);
    stripCards.forEach(card => {
        card.style.display = '';
        if (card.parentElement !== vstrip) vstrip.appendChild(card);
    });
    nonMain.forEach(card => {
        if (!stripCards.includes(card)) {
            card.style.display = 'none';
        }
    });
}

function applySpotlightLayout(grid, remotes, totalCount) {
    grid.classList.add('layout-spotlight');
    const cards = getParticipantCards();
    const target = spotlightTargetIdentity || getMainSpeakerIdentity();

    // Remove old overlay classes
    cards.forEach(c => c.classList.remove('spotlight-main', 'spotlight-overlay'));

    const nonMain = [];
    cards.forEach(card => {
        const id = card.dataset?.identity || String(currentUserId);
        if (id === target || (target === null && card.id === 'localVideoContainer')) {
            card.classList.add('spotlight-main');
            card.style.display = '';
        } else {
            nonMain.push(card);
        }
    });

    const paginatedOverlays = getCurrentPageCards(nonMain);
    const overlayPositions = [
        { bottom: 16, right: 16 },
        { bottom: 16, right: 210 },
        { bottom: 16, right: 404 },
        { bottom: 16, right: 598 },
        { bottom: 16, left: 16 }
    ];
    paginatedOverlays.forEach((card, idx) => {
        card.classList.add('spotlight-overlay');
        card.style.display = '';
        const pos = overlayPositions[idx] || { bottom: 16, right: 16 };
        card.style.bottom = pos.bottom + 'px';
        card.style.right = pos.right + 'px';
        if (pos.left) card.style.left = pos.left + 'px';
    });
    nonMain.forEach(card => {
        if (!paginatedOverlays.includes(card)) {
            card.style.display = 'none';
        }
    });
}

let _sidebarCacheKey = '';

function updateParticipantSidebar() {
    const list = document.getElementById('participantList');
    if (!list) return;
    const allCards = getParticipantCards();
    const remotes = allCards.filter(c => c.id !== 'localVideoContainer');
    const keys = remotes.map(el => el.dataset?.identity || el.id).join(',');
    const cacheKey = `${currentUserId}|${remotes.length}|${keys}`;
    if (_sidebarCacheKey === cacheKey) return;
    _sidebarCacheKey = cacheKey;
    const totalCount = allCards.length;
    let html = `
        <div data-identity="${escapeHtmlAttr(String(currentUserId))}" class="flex items-center justify-between p-3 bg-gray-800 rounded-xl cursor-pointer hover:bg-gray-700 transition-colors sidebar-item">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-violet-600 flex items-center justify-center text-white font-bold text-sm">
                    ${escapeHtml(authName.charAt(0).toUpperCase())}
                </div>
                <span class="font-semibold text-white">${escapeHtml(authName)} (Anda)</span>
            </div>
        </div>
    `;
    remoteParticipants.forEach((meta) => {
        const name = meta.displayName || meta.identity || 'Participant';
        html += `
            <div data-identity="${escapeHtmlAttr(meta.identity)}" class="flex items-center justify-between p-3 bg-gray-800 rounded-xl cursor-pointer hover:bg-gray-700 transition-colors sidebar-item">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-white font-bold text-sm">
                        ${escapeHtml(name.charAt(0).toUpperCase())}
                    </div>
                    <span class="font-semibold text-gray-300">${escapeHtml(name)}</span>
                </div>
            </div>
        `;
    });
    list.innerHTML = html;
}
