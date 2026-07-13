<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

    * {
        font-family: 'Inter', system-ui, sans-serif;
    }

    /* ── Theme variables ── */
    :root {
        --meeting-bg: radial-gradient(ellipse 80% 60% at 20% 80%, rgba(139, 92, 246, 0.06) 0%, transparent 60%),
            radial-gradient(ellipse 60% 50% at 80% 20%, rgba(6, 182, 212, 0.04) 0%, transparent 50%),
            linear-gradient(160deg, #e2e8f0 0%, #f1f5f9 50%, #f8fafc 100%);
        --grid-bg: rgba(0, 0, 0, 0.03);
        --grid-border: #e2e8f0;
        --card-bg: rgba(255, 255, 255, 0.85);
        --card-border: #e2e8f0;
        --card-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        --screen-share-bg: rgba(255, 255, 255, 0.85);
        --toolbar-bg: rgba(255, 255, 255, 0.85);
        --toolbar-border: #e2e8f0;
        --sidebar-bg: rgba(255, 255, 255, 0.92);
        --sidebar-border: #e2e8f0;
        --text-primary: #000000;
        --text-secondary: #1f2937;
        --text-muted: #4b5563;
        --name-label-bg: rgba(0, 0, 0, 0.5);
        --rec-bg: linear-gradient(135deg, #ef4444, #dc2626);
        --meeting-container-bg: rgba(0, 0, 0, 0.03);
        --hover-bg: rgba(0, 0, 0, 0.05);
        --icon-color: #000000;
        --icon-hover: #000000;
        --toolbar-icon: #000000;
        --toolbar-icon-hover: #000000;
        --page-color: #000000;
    }

    :root.dark,
    .dark {
        --meeting-bg: radial-gradient(ellipse 80% 60% at 20% 80%, rgba(139, 92, 246, 0.12) 0%, transparent 60%),
            radial-gradient(ellipse 60% 50% at 80% 20%, rgba(6, 182, 212, 0.08) 0%, transparent 50%),
            radial-gradient(ellipse 50% 40% at 50% 50%, rgba(236, 72, 153, 0.05) 0%, transparent 40%),
            linear-gradient(160deg, #0f0f13 0%, #1a1a25 50%, #0d0d12 100%);
        --grid-bg: rgba(0, 0, 0, 0.35);
        --grid-border: rgba(255, 255, 255, 0.05);
        --card-bg: rgba(0, 0, 0, 0.6);
        --card-border: rgba(255, 255, 255, 0.06);
        --card-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        --screen-share-bg: rgba(0, 0, 0, 0.7);
        --toolbar-bg: rgba(12, 12, 20, 0.85);
        --toolbar-border: rgba(255, 255, 255, 0.04);
        --sidebar-bg: rgba(15, 15, 25, 0.92);
        --sidebar-border: rgba(255, 255, 255, 0.04);
        --text-primary: #e5e7eb;
        --text-secondary: #9ca3af;
        --name-label-bg: rgba(0, 0, 0, 0.5);
        --rec-bg: linear-gradient(135deg, #ef4444, #dc2626);
        --meeting-container-bg: rgba(0, 0, 0, 0.35);
        --hover-bg: rgba(255, 255, 255, 0.08);
        --icon-color: #fff;
        --icon-hover: #e5e7eb;
        --toolbar-icon: #fff;
        --toolbar-icon-hover: #e5e7eb;
        --page-color: #fff;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: var(--scrollbar-thumb, rgba(139, 92, 246, 0.3));
        border-radius: 9999px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: var(--scrollbar-thumb-hover, rgba(139, 92, 246, 0.5));
    }

    #transcriptSidebar.collapsed {
        width: 0px !important;
        min-width: 0 !important;
        border-left-width: 0px !important;
        opacity: 0;
        pointer-events: none;
        padding: 0;
        overflow: hidden;
    }

    /* ── Animated background ── */
    .meeting-bg {
        background: var(--meeting-bg);
    }

    /* ── Glass card base ── */
    .glass-card {
        background: var(--card-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        transform: translateZ(0);
    }

    .glass-card:hover {
        border-color: rgba(139, 92, 246, 0.2);
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.4), 0 0 20px rgba(139, 92, 246, 0.05);
    }

    /* ── Video cards ── */
    .video-card {
        display: flex;
        
        justify-content: center;
        align-items: center;
        background: var(--card-bg);
        backdrop-filter: blur(8px);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
        transform: translateZ(0);
    }

    .video-card:hover {
        transform: translateY(-4px) scale(1.01);
        border-color: rgba(139, 92, 246, 0.25);
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.5), 0 0 30px rgba(139, 92, 246, 0.08);
    }

    .video-card.pinned-card {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.5), 0 16px 48px rgba(0, 0, 0, 0.5), 0 0 40px rgba(245, 158, 11, 0.1);
    }

    /* ── Video grid wrapper ── */
    .video-grid-container {
        background: var(--meeting-container-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--grid-border);
        border-radius: 24px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.05), 0 0 60px rgba(139, 92, 246, 0.03);
        transform: translateZ(0);
    }

    /* ── Screen share ── */
    .screen-share-container {
        border-radius: 20px;
        background: var(--screen-share-bg);
        backdrop-filter: blur(8px);
        border: 1px solid var(--card-border);
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5);
    }

    /* ── Toolbar ── */
    .bottom-toolbar {
        background: var(--toolbar-bg);
        backdrop-filter: blur(20px) saturate(1.4);
        -webkit-backdrop-filter: blur(20px) saturate(1.4);
        border-top: 1px solid var(--toolbar-border);
        box-shadow: 0 -8px 40px rgba(0, 0, 0, 0.5);
    }

    .toolbar-btn {
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.3s, opacity 0.3s;
        position: relative;
    }

    .toolbar-btn::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 16px;
        background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(139, 92, 246, 0.15), transparent 70%);
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
    }

    .toolbar-btn:hover::after {
        opacity: 1;
    }

    .toolbar-btn:hover {
        transform: translateY(-4px);
    }

    .toolbar-btn:active {
        transform: translateY(-1px) scale(0.96);
    }

    .toolbar-btn svg {
        filter: drop-shadow(0 2px 8px rgba(139, 92, 246, 0));
        transition: filter 0.3s;
    }

    .toolbar-btn:hover svg {
        filter: drop-shadow(0 2px 12px rgba(139, 92, 246, 0.3));
    }

    /* ── Sidebar ── */
    .participant-sidebar {
        background: var(--sidebar-bg);
        backdrop-filter: blur(20px) saturate(1.3);
        -webkit-backdrop-filter: blur(20px) saturate(1.3);
        border-left: 1px solid var(--sidebar-border);
        box-shadow: -8px 0 40px rgba(0, 0, 0, 0.4);
        transform: translateZ(0);
    }

    .sidebar-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.04);
        border-radius: 14px;
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), background 0.25s, border-color 0.25s, box-shadow 0.25s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transform: translateZ(0);
    }

    .sidebar-item:hover {
        background: rgba(139, 92, 246, 0.08);
        border-color: rgba(139, 92, 246, 0.2);
        transform: translateX(6px);
        box-shadow: 0 4px 16px rgba(139, 92, 246, 0.1);
    }

    /* ── Pagination dots ── */
    .pagination-dot {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .pagination-dot:hover {
        transform: scale(1.4);
    }

    /* ── Top bar ── */
    .top-bar {
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.6) 0%, transparent 100%);
        backdrop-filter: blur(8px);
        transform: translateZ(0);
    }

    /* ── Theme-aware colors (override Tailwind text-white) ── */
    :root .meeting-bg {
        color: var(--page-color);
    }

    :root .meeting-bg .toolbar-btn {
        color: var(--toolbar-icon);
    }

    :root .meeting-bg .toolbar-btn:hover {
        color: var(--toolbar-icon-hover);
    }

    :root .meeting-bg .toolbar-btn.text-white {
        color: var(--toolbar-icon);
    }

    :root .meeting-bg .toolbar-btn.text-red-400 {
        color: #f87171;
    }

    :root .sidebar-item {
        background: var(--hover-bg);
        border-color: var(--card-border);
    }

    :root .top-bar h1 {
        color: var(--page-color);
    }

    :root .top-bar .text-white {
        color: var(--page-color);
    }

    :root .top-bar .text-white\/70 {
        color: var(--text-secondary);
        opacity: 0.85;
    }

    :root .top-bar .text-white\/70:hover {
        color: var(--page-color);
        opacity: 1;
    }

    :root #roomThemeToggle svg {
        color: var(--page-color);
    }

    :root.camera-off #roomThemeToggle svg {
        color: #fff;
    }

    :root.camera-off #roomThemeToggle {
        color: #fff;
    }

    :root .participant-sidebar h2 {
        color: var(--page-color);
    }

    :root .participant-sidebar .text-gray-500 {
        color: var(--text-muted);
    }

    :root .participant-sidebar .text-gray-500:hover {
        color: var(--page-color);
    }

    :root #participantList {
        color: var(--text-secondary);
    }

    :root #participantList .text-gray-200 {
        color: var(--text-secondary);
    }

    :root #participantList .text-gray-200:hover {
        color: var(--page-color);
    }

    :root #participantList .text-gray-300 {
        color: var(--text-secondary);
    }

    .dark .top-bar h1 {
        color: #fff;
    }

    .dark .top-bar .text-white {
        color: #fff;
    }

    .dark .top-bar .text-white\/70 {
        color: rgba(255, 255, 255, 0.7);
    }

    .dark .top-bar .text-white\/70:hover {
        color: #fff;
    }

    /* ── Glow button (Akhiri) ── */
    .btn-danger {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        box-shadow: 0 4px 20px rgba(220, 38, 38, 0.3);
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(220, 38, 38, 0.4);
    }

    .btn-danger:active {
        transform: translateY(0) scale(0.97);
    }

    /* ── Pin button ── */
    .pin-btn {
        background: rgba(55, 65, 81, 0.7);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.06);
        transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
        border-radius: 8px;
    }

    .pin-btn:hover {
        background: rgba(55, 65, 81, 0.9);
        transform: scale(1.1);
    }

    .pin-btn.active {
        background: #eab308;
        color: #000;
        box-shadow: 0 0 20px rgba(234, 179, 8, 0.3);
    }

    /* ── Name label ── */
    .name-label {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 8px;
    }

    /* ── Speaking ring animation ── */
    @keyframes speak-pulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
        }

        50% {
            box-shadow: 0 0 0 8px rgba(34, 197, 94, 0), 0 0 20px 4px rgba(34, 197, 94, 0.15);
        }
    }

    .speaking-ring {
        animation: speak-pulse 1.2s ease-in-out infinite !important;
        border: 3px solid #22c55e !important;
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.2) !important;
        transform: translateZ(0);
    }

    /* ── REC badge ── */
    .rec-badge {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        box-shadow: 0 2px 12px rgba(239, 68, 68, 0.3);
        border-radius: 6px;
        animation: rec-pulse 2s ease-in-out infinite;
    }

    @keyframes rec-pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    /* ── Context menu ── */
    .context-menu {
        background: var(--dropdown-bg, rgba(20, 20, 30, 0.95));
        backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.06));
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        border-radius: 12px;
        overflow: hidden;
        transform: translateZ(0);
    }

    .context-menu button {
        transition: background 0.15s;
    }

    .context-menu button:hover {
        background: rgba(139, 92, 246, 0.15);
    }

    /* ── Modal ── */
    .modal-glass {
        background: var(--dropdown-bg, rgba(20, 20, 30, 0.95));
        backdrop-filter: blur(24px);
        border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.06));
        box-shadow: 0 16px 64px rgba(0, 0, 0, 0.5);
        border-radius: 20px;
        transform: translateZ(0);
    }

    .modal-glass button {
        border-radius: 12px;
        transition: transform 0.2s;
    }

    .modal-glass button:hover {
        transform: translateY(-1px);
    }

    /* ── Layout modes ── */
    #videoGridMain.layout-speaker {
        display: flex;
        flex-direction: column;
    }

    #videoGridMain.layout-speaker .speaker-main-video {
        flex: 1;
        min-height: 0;
    }

    #videoGridMain.layout-speaker .speaker-strip {
        display: flex;
        gap: 8px;
        padding: 4px;
        height: 140px;
        overflow-x: auto;
        flex-shrink: 0;
        align-items: center;
        background: rgba(0, 0, 0, 0.2);
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    #videoGridMain.layout-speaker .speaker-strip::-webkit-scrollbar {
        display: none;
    }

    #videoGridMain.layout-speaker .speaker-strip .video-card {
        width: 171px;
        max-width: 171px;
        height: 128px;
        flex-shrink: 0;
    }

    #videoGridMain.layout-speaker .speaker-strip .video-card.speaking-ring {
        border-color: #22c55e !important;
    }

    /* Safety: hide any video-card in grid that is NOT the main speaker and NOT inside strip */
    #videoGridMain.layout-speaker > .video-card:not(.speaker-main-video) {
        display: none !important;
    }

    /* ── Alone mode: full screen when only 1 participant ── */
    .alone-mode .video-grid-container {
        margin: 0 !important;
        border-radius: 0 !important;
        border: none !important;
        max-height: none !important;
    }

    .alone-mode > .flex-1.min-h-0 {
        max-height: none !important;
    }

    .alone-mode .video-grid-container .video-card {
        border-radius: 0 !important;
    }

    .alone-mode .flex-1.min-h-0 {
        padding: 0 !important;
    }

    #videoGridMain.layout-sidebar {
        display: flex;
        flex-direction: row;
    }

    #videoGridMain.layout-sidebar .sidebar-main-area {
        flex: 1;
        min-width: 0;
        display: grid;
        gap: 2px;
    }

    #videoGridMain.layout-sidebar .sidebar-vertical-strip {
        width: 200px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding: 4px;
        overflow-y: auto;
        background: rgba(0, 0, 0, 0.2);
        border-left: 1px solid rgba(255, 255, 255, 0.05);
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    #videoGridMain.layout-sidebar .sidebar-vertical-strip::-webkit-scrollbar {
        display: none;
    }

    #videoGridMain.layout-sidebar .sidebar-vertical-strip .video-card {
        min-height: 120px;
        flex-shrink: 0;
    }

    #videoGridMain.layout-sidebar .video-card:not(.sidebar-main-area .video-card):not(.sidebar-vertical-strip .video-card) {
        display: none;
    }

    #videoGridMain.layout-spotlight {
        position: relative;
    }

    #videoGridMain.layout-spotlight .video-card.spotlight-main {
        position: absolute;
        inset: 0;
        z-index: 1;
        margin: 0;
        border-radius: 0;
    }

    #videoGridMain.layout-spotlight .video-card.spotlight-overlay {
        position: absolute;
        z-index: 2;
        width: 180px;
        height: 120px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
        cursor: pointer;
        transition: transform 0.3s;
    }

    #videoGridMain.layout-spotlight .video-card.spotlight-overlay:hover {
        transform: scale(1.05);
        z-index: 3;
    }

    #videoGridMain.layout-spotlight .video-card:not(.spotlight-main):not(.spotlight-overlay) {
        display: none;
    }

    /* Layout selector dropdown */
    .layout-dropdown {
        background: var(--dropdown-bg, rgba(20, 20, 30, 0.95));
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        overflow: hidden;
        z-index: 60;
        transform: translateZ(0);
    }

    .layout-dropdown button {
        transition: background 0.15s, color 0.15s;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        width: 100%;
        text-align: left;
        font-size: 13px;
        color: #d1d5db;
    }

    .layout-dropdown button:hover {
        background: rgba(139, 92, 246, 0.15);
        color: #fff;
    }

    .layout-dropdown button.active-layout {
        background: rgba(139, 92, 246, 0.12);
        color: #a78bfa;
        font-weight: 600;
        border-left: 3px solid #a78bfa;
    }

    /* ── Entry animations ── */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .video-card {
        animation: fadeInScale 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }

    .sidebar-item {
        animation: fadeInUp 0.3s ease-out both;
    }

    /* ── Mobile More Dropdown ── */
    #mobileMoreBtn {
        display: none;
    }

    #mobileMoreDropdown {
        display: none;
    }

    /* ── Desktop Screen Share Layout ── */
    #videoGridWrapper:has(#screenShareContainer:not(.hidden)) {
        flex-direction: row !important;
    }

    #videoGridWrapper:has(#screenShareContainer:not(.hidden)) #screenShareContainer {
        flex: 3 !important;
        min-width: 0 !important;
        max-width: 75% !important;
    }

    #videoGridWrapper:has(#screenShareContainer:not(.hidden)) #videoGridMain {
        flex: 1 !important;
        min-width: 180px !important;
        max-width: 25% !important;
        display: flex !important;
        flex-direction: column !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        gap: 4px !important;
        padding: 4px !important;
    }

    #videoGridWrapper:has(#screenShareContainer:not(.hidden)) #videoGridMain .video-card {
        width: 100% !important;
        min-height: 100px !important;
        max-height: 200px !important;
        flex-shrink: 0 !important;
        margin: 0 !important;
    }

    #videoGridWrapper:has(#screenShareContainer:not(.hidden)) #remoteVideos {
        display: flex !important;
        flex-direction: column !important;
        gap: 4px !important;
    }

    #videoGridWrapper:has(#screenShareContainer:not(.hidden)) #remoteVideos .video-card {
        width: 100% !important;
        min-height: 100px !important;
        max-height: 200px !important;
        flex-shrink: 0 !important;
        margin: 0 !important;
    }

    /* ── Mobile Responsive ── */
    @media (max-width: 767px) {
        #videoGridMain.layout-grid {
            gap: 4px !important;
        }

        #videoGridMain.layout-grid.grid-cols-3,
        #videoGridMain.layout-grid.grid-cols-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        .top-bar {
            padding: 14px 16px !important;
        }

        .top-bar h1 {
            font-size: 20px !important;
            font-weight: 700 !important;
        }

        .top-bar>div:first-child svg {
            width: 34px !important;
            height: 34px !important;
        }

        .top-bar>div:first-child {
            gap: 12px !important;
        }

        #roomThemeToggle {
            padding: 8px !important;
        }

        #roomThemeToggle svg {
            width: 22px !important;
            height: 22px !important;
        }

        #videoGridWrapper {
            height: 70vh !important;
        }

        .video-grid-container {
            margin: 0 10px !important;
            border-radius: 12px !important;
        }

        .alone-mode #videoGridWrapper {
            height: 100dvh !important;
        }

        .alone-mode .video-grid-container {
            margin: 0 10px !important;
            border-radius: 12px !important;
            max-height: none !important;
        }

        .alone-mode > .flex-1.min-h-0 {
            max-height: none !important;
        }

        #videoGridWrapper:has(#screenShareContainer:not(.hidden)) {
            flex-direction: column !important;
        }

        #videoGridWrapper:has(#screenShareContainer:not(.hidden)) #screenShareContainer {
            flex: 3 !important;
            min-height: 0 !important;
        }

        #videoGridWrapper:has(#screenShareContainer:not(.hidden)) #videoGridMain {
            flex: 1 !important;
            min-height: 0 !important;
            max-height: 25vh !important;
            display: flex !important;
            flex-direction: row !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            gap: 4px !important;
            padding: 0 4px !important;
        }

        #videoGridWrapper:has(#screenShareContainer:not(.hidden)) #videoGridMain .video-card {
            min-width: 120px !important;
            max-width: 160px !important;
            flex-shrink: 0 !important;
            height: 100% !important;
            margin: 0 !important;
        }

        #videoGridWrapper:has(#screenShareContainer:not(.hidden)) #remoteVideos {
            display: flex !important;
            flex-direction: row !important;
            gap: 4px !important;
        }

        #videoGridWrapper:has(#screenShareContainer:not(.hidden)) #remoteVideos .video-card {
            min-width: 120px !important;
            max-width: 160px !important;
            flex-shrink: 0 !important;
            height: 100% !important;
            margin: 0 !important;
        }

        #videoGridMain .video-card {
            border-radius: 12px !important;
        }

        #videoGridMain .video-card.m-1 {
            margin: 0 !important;
        }

        #videoGridMain.grid-cols-1.grid-rows-2 {
            max-height: 55dvh !important;
        }

        .alone-mode #videoGridMain .video-card.m-1 {
            margin: 0 !important;
        }

        .alone-mode .flex-1.min-h-0 {
            padding: 0 !important;
        }

        #localVideo {
            object-fit: cover !important;
        }

        #localAvatarCircle {
            width: 48px !important;
            height: 48px !important;
        }

        #localAvatarCircle span {
            font-size: 1.5rem !important;
        }

        #localAvatarText {
            font-size: 1.25rem !important;
        }

        [id^="remote-avatar-circle-"] {
            width: 64px !important;
            height: 64px !important;
        }

        [id^="remote-avatar-circle-"] span {
            font-size: 2rem !important;
        }

        .bottom-toolbar {
            padding: 10px 8px !important;
            gap: 16px !important;
            overflow: visible !important;
            flex-wrap: nowrap !important;
            justify-content: center !important;
            position: fixed !important;
        }

        .bottom-toolbar .toolbar-btn {
            flex-shrink: 0;
            min-width: 0;
            padding: 0 8px;
        }

        .bottom-toolbar .toolbar-btn .h-12 {
            height: 36px !important;
        }

        .bottom-toolbar .toolbar-btn svg {
            width: 26px !important;
            height: 26px !important;
        }

        .bottom-toolbar .toolbar-btn span:last-child {
            font-size: 11px !important;
            margin-top: 2px !important;
        }

        .bottom-toolbar #leaveBtn {
            margin-left: 4px !important;
        }

        .bottom-toolbar #leaveBtn .btn-danger {
            padding: 6px 12px !important;
            font-size: 13px !important;
            border-radius: 8px !important;
        }

        #participantBadge {
            font-size: 9px !important;
            padding: 2px 4px !important;
            top: -4px !important;
            right: -4px !important;
        }

        .bottom-toolbar .ml-8 {
            margin-left: 4px !important;
        }

        .mobile-hide {
            display: none !important;
        }

        #mobileMoreBtn {
            display: flex !important;
        }

        #mobileMoreDropdown {
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100vw - 16px);
            max-width: 340px;
            background: var(--toolbar-bg);
            backdrop-filter: blur(20px) saturate(1.4);
            border: 1px solid var(--toolbar-border);
            border-radius: 16px;
            box-shadow: 0 -8px 40px rgba(0, 0, 0, 0.5);
            z-index: 100;
            padding: 12px;
        }

        #mobileMoreDropdown .more-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        #mobileMoreDropdown .more-grid button {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 10px 4px;
            border-radius: 12px;
            background: transparent;
            color: var(--toolbar-icon);
            transition: background 0.2s;
            font-size: 10px;
            border: none;
            cursor: pointer;
            min-width: 0;
        }

        #mobileMoreDropdown .more-grid button:hover {
            background: var(--hover-bg);
        }

        #mobileMoreDropdown .more-grid button svg {
            width: 22px !important;
            height: 22px !important;
        }

        #mobileMoreDropdown .more-grid button .h-12 {
            height: 28px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #mobileMoreDropdown .more-grid button span {
            font-size: 9px !important;
            font-weight: 600;
        }

        #participantSidebar {
            width: 100vw !important;
        }

        #transcriptSidebar {
            width: 100vw !important;
        }

        #transcriptSidebar .p-4 {
            padding: 10px 12px !important;
        }

        #openSidebarBtn {
            right: 8px !important;
            padding: 8px !important;
        }

        #openSidebarBtn svg {
            width: 18px !important;
            height: 18px !important;
        }

        #recordingPopup,
        #aiLoadingOverlay {
            width: 260px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
        }

        #notulensiModal>div {
            width: 95% !important;
            height: 92vh !important;
            border-radius: 16px !important;
        }

        #notulensiModal .p-6 {
            padding: 14px !important;
        }

        #notulensiModal .grid-cols-1.md\:grid-cols-2 {
            grid-template-columns: 1fr !important;
        }

        #notulensiModal .text-lg {
            font-size: 15px !important;
        }

        #notulensiModal table {
            font-size: 12px !important;
        }

        #notulensiModal th,
        #notulensiModal td {
            padding: 6px 8px !important;
        }

        .speaker-strip {
            height: 80px !important;
        }

        .speaker-strip .video-card {
            width: 96px !important;
            max-width: 96px !important;
            height: 72px !important;
        }

        #videoGridMain.layout-sidebar {
            flex-direction: column !important;
        }

        #videoGridMain.layout-sidebar .sidebar-main-area {
            flex: 1;
            min-height: 0;
        }

        .sidebar-vertical-strip {
            width: 100% !important;
            flex-direction: row !important;
            overflow-y: hidden !important;
            overflow-x: auto !important;
            border-left: none !important;
            border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
            max-height: 90px !important;
        }

        .sidebar-vertical-strip .video-card {
            min-width: 120px !important;
            max-width: 120px !important;
            aspect-ratio: 4/3 !important;
            max-height: none !important;
            flex-shrink: 0 !important;
        }

        .spotlight-overlay {
            width: 100px !important;
            height: 70px !important;
        }

        #confirmNotulenModal>div {
            padding: 20px !important;
            width: 90% !important;
        }

        #confirmNotulenModal h2 {
            font-size: 18px !important;
        }

        #confirmNotulenModal button {
            font-size: 15px !important;
            padding: 8px 20px !important;
        }
    }
</style>
