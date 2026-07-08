// ======================== INIT ========================
updateParticipantUI();
connectToLiveKit();

const shareBtn = document.getElementById('shareBtn');
const sharePopup = document.getElementById('sharePopup');
if (shareBtn && sharePopup) {
    shareBtn.addEventListener('click', () => {
        if (window.innerWidth >= 768) {
            sharePopup.style.position = '';
            sharePopup.style.bottom = '';
            sharePopup.style.left = '';
            sharePopup.style.transform = '';
            sharePopup.style.zIndex = '';
        } else {
            sharePopup.style.position = 'fixed';
            sharePopup.style.bottom = '80px';
            sharePopup.style.left = '50%';
            sharePopup.style.transform = 'translateX(-50%)';
            sharePopup.style.zIndex = '200';
        }
        sharePopup.classList.toggle('hidden');
        setTimeout(() => {
            sharePopup.classList.toggle('opacity-0');
        }, 10);
    });
    document.addEventListener('click', (e) => {
        if (!shareBtn.contains(e.target) && !sharePopup.contains(e.target) && !sharePopup.classList
            .contains('hidden')) {
            sharePopup.classList.add('opacity-0');
            setTimeout(() => {
                sharePopup.classList.add('hidden');
            }, 300);
        }
    });
}

// ── Theme toggle (room) — always starts dark ──
(function() {
    const html = document.documentElement;
    const toggle = document.getElementById('roomThemeToggle');
    const sunIcon = document.getElementById('roomThemeIconSun');
    const moonIcon = document.getElementById('roomThemeIconMoon');

    function setTheme(dark) {
        html.classList.toggle('dark', dark);
        if (sunIcon) sunIcon.classList.toggle('hidden', !dark);
        if (moonIcon) moonIcon.classList.toggle('hidden', dark);
    }
    html.classList.add('dark');
    if (sunIcon) sunIcon.classList.add('hidden');
    if (moonIcon) moonIcon.classList.remove('hidden');
    if (toggle) toggle.addEventListener('click', () => setTheme(!html.classList.contains('dark')));
})();
