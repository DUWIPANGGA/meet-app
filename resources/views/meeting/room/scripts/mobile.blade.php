// ======================== MOBILE MORE DROPDOWN ========================
const mobileMoreBtn = document.getElementById('mobileMoreBtn');
const mobileMoreDropdown = document.getElementById('mobileMoreDropdown');
const moreGrid = mobileMoreDropdown?.querySelector('.more-grid');

const mobileHiddenButtons = [{
        id: 'participantBtn',
        label: 'Partisipan',
        icon: '<svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>'
    },
    {
        id: 'screenShareBtn',
        label: 'Share Screen',
        icon: '<svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z"/></svg>'
    },
    {
        id: 'layoutBtn',
        label: 'Layout',
        icon: '<svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"/></svg>'
    },
    {
        id: 'recordScreenBtn',
        label: 'Rekam',
        icon: '<svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4zM14 13h-3v3H9v-3H6v-2h3V8h2v3h3v2z"/></svg>'
    },
];

if (moreGrid && mobileMoreBtn && mobileMoreDropdown) {
    function closeMobileMoreDropdown() {
        mobileMoreDropdown.style.display = 'none';
    }

    mobileHiddenButtons.unshift({
        id: 'aiNotulenTriggerBtn',
        label: 'AI Notulen',
        icon: '<svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M7.5 5.6L10 7 8.6 4.5 10 2 7.5 3.4 5 2l1.4 2.5L5 7zm12 9.8L17 14l1.4 2.5L17 19l2.5-1.4L22 19l-1.4-2.5L22 14zM22 2l-2.5 1.4L17 2l1.4 2.5L17 7l2.5-1.4L22 7l-1.4-2.5zm-7.63 5.29c-.39-.39-1.02-.39-1.41 0L1.29 18.96c-.39.39-.39 1.02 0 1.41l2.34 2.34c.39.39 1.02.39 1.41 0L16.7 11.05c.39-.39.39-1.02 0-1.41l-2.33-2.35zm-1.03 5.49l-2.12-2.12 2.44-2.44 2.12 2.12-2.44 2.44z"/></svg>'
    });

    mobileHiddenButtons.forEach(function(item) {
        const btn = document.createElement('button');
        btn.setAttribute('data-target', item.id);
        btn.innerHTML = '<div class="h-12 flex items-center justify-center">' + item.icon + '</div><span>' +
            item.label + '</span>';
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            closeMobileMoreDropdown();
            if (item.id === 'shareBtn') {
                const popup = document.getElementById('sharePopup');
                if (popup) {
                    popup.style.position = 'fixed';
                    popup.style.bottom = '80px';
                    popup.style.left = '50%';
                    popup.style.transform = 'translateX(-50%)';
                    popup.style.zIndex = '200';
                    popup.classList.remove('hidden');
                    setTimeout(function() {
                        popup.classList.remove('opacity-0');
                    }, 10);
                }
            } else if (item.id === 'layoutBtn') {
                const layouts = ['grid', 'speaker', 'sidebar', 'spotlight'];
                const currentIdx = layouts.indexOf(currentLayout);
                const nextLayout = layouts[(currentIdx + 1) % layouts.length];
                applyLayout(nextLayout);
            } else {
                const target = document.getElementById(item.id);
                if (target) target.click();
            }
        });
        moreGrid.appendChild(btn);
    });

    mobileMoreBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        if (mobileMoreDropdown.style.display === 'none' || !mobileMoreDropdown.style.display) {
            mobileMoreDropdown.style.display = 'block';
        } else {
            mobileMoreDropdown.style.display = 'none';
        }
    });

    document.addEventListener('click', function(e) {
        if (!mobileMoreBtn.contains(e.target) && !mobileMoreDropdown.contains(e.target)) {
            mobileMoreDropdown.style.display = 'none';
        }
        const sharePopup = document.getElementById('sharePopup');
        if (sharePopup && !sharePopup.classList.contains('hidden') && mobileMoreBtn && mobileMoreBtn
            .contains(e.target)) {
            sharePopup.classList.add('opacity-0');
            setTimeout(function() {
                sharePopup.classList.add('hidden');
            }, 300);
        }
    });
}
