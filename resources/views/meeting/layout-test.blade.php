<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="mock-csrf-token">
    <title>Meeting Layout Simulation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
            overflow-x: hidden;
            background: rgba(0, 0, 0, 0.2);
            border-left: 1px solid rgba(255, 255, 255, 0.05);
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        #videoGridMain.layout-sidebar .sidebar-vertical-strip::-webkit-scrollbar {
            display: none;
        }

        #videoGridMain.layout-sidebar .sidebar-vertical-strip .video-card {
            aspect-ratio: 4/3;
            max-height: 150px;
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

            .video-grid-container {
                margin: 0 10px !important;
                border-radius: 12px !important;
                padding-bottom: 70px !important;
            }

            .alone-mode .video-grid-container {
                margin: 0 10px !important;
                border-radius: 12px !important;
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

            #sharePopup,
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

            /* Sidebar: stack vertically on mobile */
            #videoGridMain.layout-sidebar {
                flex-direction: column !important;
            }

            #videoGridMain.layout-sidebar .sidebar-main-area {
                flex: 1 !important;
                min-height: 0 !important;
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

            /* Spotlight: overlay in bottom-right corner */
            .spotlight-overlay {
                width: 100px !important;
                height: 70px !important;
                bottom: 8px !important;
                right: 8px !important;
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

</head>

<body class="bg-gray-900 text-white h-screen overflow-hidden" style="font-family:'Inter',system-ui,sans-serif">

    <div id="meetingContainer" class="h-screen flex flex-col relative meeting-bg text-white overflow-hidden font-sans">

        <!-- Top Bar -->
        <div
            class="static md:absolute md:top-0 md:left-0 md:right-0 px-6 py-4 flex justify-between items-center z-10 top-bar">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                <h1 class="text-xl font-semibold" id="topBarUserName">Anda</h1>
            </div>
            <div class="flex items-center gap-2">
                <div id="aiNotulenHeaderIndicator"
                    class="hidden md:hidden items-center gap-1.5 bg-red-500/10 border border-red-500/30 rounded-full px-2.5 py-1">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-red-400 uppercase tracking-wider">AI</span>
                </div>
                <button id="roomThemeToggle"
                    class="p-2 hover:bg-white/10 rounded-full transition text-white/70 hover:text-white"
                    title="Toggle tema">
                    <svg id="roomThemeIconSun" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="roomThemeIconMoon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                <div class="relative flex items-center">
                    <button id="layoutNavBtn"
                        class="p-2 hover:bg-white/10 rounded-full transition text-white/70 hover:text-white"
                        title="Ganti layout">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                        </svg>
                    </button>
                    <div id="layoutNavDropdown"
                        style="display:none;opacity:0"
                        class="absolute top-full mt-2 right-0 layout-dropdown min-w-[160px] transition-opacity z-[70]">
                        <button data-layout="grid" class="active-layout"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"/></svg> Grid</button>
                        <button data-layout="speaker"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg> Speaker</button>
                        <button data-layout="sidebar"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3v18h18V3H3zm8 16H5V5h6v14zm8 0h-6V5h6v14z"/></svg> Sidebar</button>
                        <button data-layout="spotlight"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h10v2H7z"/></svg> Spotlight</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden statuses -->
        <div class="hidden">
            <span id="connectionStatus"></span>
            <span id="pipelineStatus"></span>
            <button id="enableAudioBtn">Audio</button>
        </div>

        <!-- Video Grid Area -->
        <div class="flex-1 min-h-0 p-1 md:p-2 pb-24 relative flex flex-col max-h-[90vh]">
            <div id="videoGridWrapper"
                class="h-dvh md:flex-1 overflow-hidden relative flex flex-row video-grid-container m-1 md:m-2 md:max-h-[80vh] max-h-[70vh]">
                <!-- Screen Share Display -->
                <div id="screenShareContainer" class="hidden flex-1 min-w-0 relative screen-share-container m-2"
                    style="background:#111">
                    <video id="screenShareVideo" autoplay playsinline class="w-full h-full object-contain"></video>
                    <div id="screenShareOverlay"
                        class="absolute top-0 left-0 right-0 bg-gradient-to-b from-black/60 to-transparent p-3 flex items-center gap-2">
                        <span id="screenShareLabel" class="text-white text-sm font-semibold"></span>
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    </div>
                    <button id="pinScreenShareBtn" class="absolute top-3 right-3 pin-btn text-xs px-2 py-1.5 z-30"><svg
                            class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z" />
                        </svg></button>
                    <button id="stopScreenShareBtn"
                        class="absolute top-3 right-14 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg z-30 hidden shadow-lg shadow-red-600/20">Stop
                        Sharing</button>
                </div>
                <div id="videoGridMain" class="min-w-0 relative z-0 h-full" style="flex:1">
                    <!-- Local Video -->
                    <div id="localVideoContainer" class="relative rounded-lg overflow-hidden h-full video-card m-1">
                        <video id="localVideo" autoplay muted playsinline class="w-full h-full object-cover"
                            style="transform: scaleX(-1)"></video>
                        <div id="localAvatar" class="absolute inset-0 flex items-center justify-center hidden z-10"
                            style="background:rgba(0,0,0,0.6)">
                            <div class="relative">
                                <div id="localAvatarCircle"
                                    style="width:80px;height:80px;border-radius:50%;background:#4b5563;display:flex;align-items:center;justify-content:center;transition:all 0.3s">
                                    <span id="localAvatarText"
                                        style="font-size:2.25rem;color:#fff;font-weight:700;text-transform:uppercase"></span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="absolute top-2 left-2 rec-badge text-white px-2 py-0.5 rounded text-[10px] font-bold z-20 tracking-wider">
                            REC</div>
                    </div>
                    <!-- Remote Videos Container -->
                    <div id="remoteVideos" class="contents"></div>
                </div>
            </div>
            <!-- Pagination Dots -->
            <div id="paginationDots"
                class="flex justify-center items-center gap-1 md:gap-2 py-1 md:py-2 flex-shrink-0"
                style="display:none">
            </div>

            <!-- "Simpan Notulen Rapat?" Confirmation Modal -->
            <div id="confirmNotulenModal"
                class="absolute inset-0 flex items-center justify-center z-40 hidden backdrop-blur-sm bg-black/40">
                <div class="bg-[#242424] border border-gray-700 shadow-2xl rounded-2xl p-8 flex flex-col items-center">
                    <h2 class="text-2xl font-bold text-white mb-6">Simpan Notulen Rapat?</h2>
                    <div class="flex gap-4">
                        <button id="cancelNotulenBtn"
                            class="bg-black text-white px-8 py-2 font-bold text-lg hover:bg-gray-900 transition">Cancel</button>
                        <button id="simpanNotulenBtn"
                            class="bg-black text-white px-8 py-2 font-bold text-lg hover:bg-gray-900 transition">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Toolbar -->
        <div
            class="absolute bottom-0 left-0 right-0 bottom-toolbar border-t border-gray-700/50 py-3 px-6 flex justify-between md:justify-center md:gap-16 items-center z-50 shadow-[0_-8px_30px_rgba(0,0,0,0.6)] overflow-visible">
            <!-- Kamera -->
            <button id="cameraBtn"
                class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn">
                <div class="h-12 flex items-center justify-center">
                    <svg id="camIcon" class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z" />
                    </svg>
                    <svg id="camOffIcon" class="w-10 h-10 hidden" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M21 6.5l-4 4V7c0-.55-.45-1-1-1H9.82L21 17.18V6.5zM3.27 2L2 3.27 4.73 6H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.21 0 .39-.08.54-.18L19.73 21 21 19.73 3.27 2z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold mt-1">Kamera</span>
            </button>

            <!-- Audio (Mic) -->
            <button id="muteBtn"
                class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn">
                <div class="h-12 flex items-center justify-center">
                    <svg id="micIcon" class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.91-3c-.49 0-.9.36-.98.85C16.52 14.2 14.47 16 12 16s-4.52-1.8-4.93-4.15c-.08-.49-.49-.85-.98-.85-.61 0-1.09.54-1 1.14.49 3 2.89 5.35 5.91 5.78V21h2v-3.08c3.02-.43 5.42-2.78 5.91-5.78.09-.6-.39-1.14-1-1.14z" />
                    </svg>
                    <svg id="micOffIcon" class="w-10 h-10 hidden" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 14c1.66 0 2.99-1.34 2.99-3L15 5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 14 6.7 11H5c0 3.41 2.72 6.23 6 6.72V21h2v-3.28c3.28-.49 6-3.31 6-6.72h-1.7zM3.27 3L2 4.27l18.73 18.73L22 21.73 3.27 3z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold mt-1">Audio</span>
            </button>

            <!-- AI Notulen -->
            <div class="relative flex flex-col items-center mobile-hide">
                <button id="aiNotulenTriggerBtn"
                    class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn relative">
                    <div class="h-12 flex items-center justify-center">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M7.5 5.6L10 7 8.6 4.5 10 2 7.5 3.4 5 2l1.4 2.5L5 7zm12 9.8L17 14l1.4 2.5L17 19l2.5-1.4L22 19l-1.4-2.5L22 14zM22 2l-2.5 1.4L17 2l1.4 2.5L17 7l2.5-1.4L22 7l-1.4-2.5zm-7.63 5.29c-.39-.39-1.02-.39-1.41 0L1.29 18.96c-.39.39-.39 1.02 0 1.41l2.34 2.34c.39.39 1.02.39 1.41 0L16.7 11.05c.39-.39.39-1.02 0-1.41l-2.33-2.35zm-1.03 5.49l-2.12-2.12 2.44-2.44 2.12 2.12-2.44 2.44z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold mt-1">AI Notulen</span>
                    <span id="aiNotulenActiveDot"
                        class="hidden absolute top-0 right-2 w-3 h-3 bg-red-500 rounded-full animate-pulse border-2 border-[#9ea3a8]"></span>
                </button>
                <div id="aiLoadingOverlay"
                    class="hidden absolute bottom-full mb-4 left-1/2 -translate-x-1/2 w-72 bg-white rounded-lg shadow-xl border border-gray-200 p-4 text-gray-800 z-50 transition-opacity opacity-0">
                    <div class="flex items-center gap-3">
                        <div
                            class="animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-violet-500 shrink-0">
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">AI Sedang Menyusun Notulensi...</p>
                            <p class="text-xs text-gray-500 mt-0.5">Menganalisis transkrip percakapan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Share -->
            <div class="relative flex flex-col items-center">
                <button id="shareBtn"
                    class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn">
                    <div class="h-12 flex items-center justify-center">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold mt-1">Share</span>
                </button>

                <div id="sharePopup"
                    class="absolute bottom-full mb-4 left-1/2 -translate-x-1/2 w-72 bg-white rounded-lg shadow-xl border border-gray-200 p-4 text-gray-800 z-50 hidden transition-opacity opacity-0">
                    <h4 class="font-medium mb-2 text-sm text-left">Bagikan info rapat ini</h4>
                    <p class="text-xs text-gray-500 mb-3 text-left">Berikan link atau ID rapat ini kepada peserta lain.
                    </p>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="MT-001"
                                class="flex-1 bg-gray-50 border border-gray-300 rounded px-2 py-1.5 text-xs text-gray-800 font-mono outline-none">
                            <span class="text-xs text-gray-500 w-12">ID</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="#"
                                class="flex-1 bg-gray-50 border border-gray-300 rounded px-2 py-1.5 text-xs text-gray-600 outline-none">
                            <button onclick="navigator.clipboard.writeText('#'); alert('Link disalin ke clipboard!')"
                                class="p-1.5 bg-blue-50 text-[#0284c7] hover:bg-blue-100 rounded transition shrink-0"
                                title="Salin link">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partisipan -->
            <button id="participantBtn"
                class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn relative mobile-hide">
                <div class="h-12 flex items-center justify-center relative">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                    <span id="participantBadge"
                        class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">1</span>
                </div>
                <span class="text-sm font-semibold mt-1">Partisipan</span>
            </button>

            <!-- Share Screen -->
            <button id="screenShareBtn"
                class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn relative mobile-hide">
                <div class="h-12 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z">
                    </svg>
                </div>
                <span class="text-sm font-semibold mt-1">Share Screen</span>
                <span id="screenShareActiveDot"
                    class="hidden absolute -top-0.5 right-1 w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
            </button>

            <!-- More (mobile) -->
            <button id="mobileMoreBtn"
                class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn">
                <div class="h-12 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="5" r="2" />
                        <circle cx="12" cy="12" r="2" />
                        <circle cx="12" cy="19" r="2" />
                    </svg>
                </div>
                <span class="text-sm font-semibold mt-1">Lainnya</span>
            </button>
            <div id="mobileMoreDropdown">
                <div class="more-grid"></div>
            </div>

            <!-- Layout Selector -->
            <div class="relative flex flex-col items-center mobile-hide">
                <button id="layoutBtn"
                    class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn">
                    <div class="h-12 flex items-center justify-center">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold mt-1">Layout</span>
                </button>
                <div id="layoutDropdown" style="display:none;opacity:0"
                    class="absolute bottom-full mb-4 left-1/2 -translate-x-1/2 layout-dropdown min-w-[160px] transition-opacity">
                    <button data-layout="grid" class="active-layout"><svg class="w-4 h-4" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z" />
                        </svg> Grid</button>
                    <button data-layout="speaker"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" />
                        </svg> Speaker</button>
                    <button data-layout="sidebar"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3v18h18V3H3zm8 16H5V5h6v14zm8 0h-6V5h6v14z" />
                        </svg> Sidebar</button>
                    <button data-layout="spotlight"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h10v2H7z" />
                        </svg> Spotlight</button>
                </div>
            </div>

            <!-- Rekam Layar -->
            <div class="relative flex flex-col items-center mobile-hide">
                <button id="recordScreenBtn"
                    class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn relative">
                    <div class="h-12 flex items-center justify-center">
                        <svg id="recordIconDefault" class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4zM14 13h-3v3H9v-3H6v-2h3V8h2v3h3v2z" />
                        </svg>
                        <svg id="recordIconActive" class="hidden w-10 h-10 text-red-500" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z" />
                            <rect x="6" y="9" width="8" height="6" rx="1" fill="#ef4444" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold mt-1">Rekam</span>
                    <span id="recordActiveDot"
                        class="hidden absolute -top-0.5 right-1 w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></span>
                </button>
                <div id="recordingPopup"
                    class="hidden absolute bottom-full mb-4 left-1/2 -translate-x-1/2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 p-4 text-gray-800 z-50 transition-opacity opacity-0">
                    <div class="flex items-start gap-3">
                        <span class="flex h-3 w-3 relative mt-0.5 shrink-0">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-gray-900">Meeting sedang direkam</p>
                            <p id="recordingByName" class="text-xs text-gray-500 mt-0.5">oleh Anda</p>
                        </div>
                        <button id="recordingPopupClose"
                            class="text-gray-400 hover:text-gray-600 transition shrink-0 -mr-1 -mt-1 p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Akhiri / Keluar -->
            <button id="leaveBtn" class="flex flex-col items-center transition toolbar-btn ml-8">
                <div class="h-12 flex items-center justify-center">
                    <div class="btn-danger text-white font-bold rounded-xl px-5 py-2.5 shadow-lg tracking-wide">
                        Akhiri
                    </div>
                </div>
            </button>
        </div>

        <!-- Participant Sidebar -->
        <div id="participantSidebar"
            class="absolute top-0 right-0 h-full w-80 max-w-[85vw] participant-sidebar z-40 transform translate-x-full transition-transform duration-300 flex flex-col">
            <div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                <h2 class="text-lg font-bold text-white flex items-center gap-2"><svg class="w-5 h-5 text-violet-400"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg> Partisipan <span class="text-violet-400 font-bold">(<span
                            id="participantCountText">1</span>)</span></h2>
                <button id="closeParticipantBtn"
                    class="text-gray-500 hover:text-white transition hover:bg-white/5 rounded-lg p-1.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div id="participantList"
                class="flex-1 p-4 overflow-y-auto flex flex-col gap-3 custom-scrollbar text-gray-300">
            </div>
            <div id="contextMenu" class="hidden fixed z-50 context-menu py-1 min-w-[140px]">
                <button id="contextPinBtn"
                    class="w-full text-left px-4 py-2.5 text-sm text-gray-200 hover:text-white flex items-center gap-2"><svg
                        class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z" />
                    </svg>
                    Pin</button>
            </div>
        </div>

        <!-- Transcript Sidebar -->
        <div id="transcriptSidebar"
            class="collapsed absolute top-0 right-0 h-full w-96 max-w-[90vw] z-40 flex flex-col border-l border-white/5 bg-gray-900/90 backdrop-blur-xl transform transition-all duration-300">
            <div class="p-4 border-b border-white/5 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <span id="sidebarStatusIndicator" class="relative inline-flex rounded-full h-2 w-2 bg-gray-500">
                        <span id="sidebarPulse"
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-gray-400 opacity-75"></span>
                    </span>
                    <h3 class="text-sm font-bold text-white">Transkrip Rapat</h3>
                </div>
                <div class="flex items-center gap-1">
                    <button id="toggleSidebarBtn" title="Sembunyikan transkrip"
                        class="text-gray-400 hover:text-white hover:bg-white/10 rounded-lg p-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="px-4 py-2 border-b border-white/5 shrink-0">
                <span id="transcribeStatus"
                    class="text-gray-500 font-semibold uppercase tracking-wider text-xs">Nonaktif</span>
            </div>
            <div id="transcriptMessages" class="flex-1 min-h-0 overflow-y-auto p-3 space-y-3 custom-scrollbar">
                <div id="emptyTranscriptMsg" class="text-gray-500 text-center py-8 italic text-xs">Belum ada transkrip
                    aktif.</div>
            </div>
        </div>

        <button id="openSidebarBtn"
            class="hidden fixed right-4 top-1/2 -translate-y-1/2 z-50 bg-gray-800/90 hover:bg-gray-700 border border-white/10 text-white p-3 rounded-l-xl shadow-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span id="sidebarActiveDot"
                class="absolute -top-1 -left-1 w-3 h-3 bg-emerald-500 rounded-full animate-pulse hidden"></span>
        </button>

        <button id="startRecordBtn" class="hidden"></button>
        <button id="stopUploadBtn" class="hidden"></button>
        <a id="showNotulensiBtn" class="hidden"></a>
        <a id="pdfBtn" class="hidden"></a>

        <!-- Hidden canvas for screen recording - NOT display:none, because captureStream() needs canvas to be painted -->
        <canvas id="recordingCanvas" width="1920" height="1080" style="position:fixed;top:-9999px;left:-9999px;pointer-events:none;opacity:0.01"></canvas>

        <!-- Countdown overlay -->
        <div id="countdownOverlay" class="fixed inset-0 z-[9999] flex items-center justify-center hidden" style="background:rgba(0,0,0,0.7)">
            <div id="countdownNumber" class="text-white font-bold select-none" style="font-size:18rem;line-height:1;text-shadow:0 0 60px rgba(139,92,246,0.6)">3</div>
        </div>

    </div>

    <!-- Modal Notulensi -->
    <div id="notulensiModal"
        class="fixed inset-0 bg-black/75 backdrop-blur-md flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300">
        <div
            class="bg-gray-900/90 border border-gray-700/80 rounded-2xl w-11/12 max-w-4xl h-[85vh] max-h-[92vh] md:h-[85vh] flex flex-col shadow-2xl scale-95 transition-transform duration-300 overflow-hidden">
            <div class="p-6 border-b border-gray-800 flex justify-between items-center bg-gray-950/40">
                <div class="flex items-center gap-3"><span class="flex h-3 w-3 relative"><span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-violet-400 opacity-75"></span><span
                            class="relative inline-flex rounded-full h-3 w-3 bg-violet-500"></span></span>
                    <h2 class="text-lg font-bold text-white">Notulensi Rapat AI (Gemini)</h2>
                </div>
                <button id="closeNotulensiModalBtn"
                    class="text-gray-400 hover:text-white transition text-sm font-semibold bg-gray-800 hover:bg-gray-700 px-3.5 py-1.5 rounded-lg">Tutup</button>
            </div>
            <div class="flex-1 p-6 overflow-y-auto space-y-5 custom-scrollbar">
                <div id="modalNotulensiContent" class="space-y-5">
                    <div class="rounded-xl border p-5"
                        style="background:rgba(139,92,246,0.04);border-color:rgba(139,92,246,0.18)">
                        <div class="flex items-center gap-2.5 mb-3">
                            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            <h3 class="text-sm font-bold text-violet-400">Ringkasan Eksekutif</h3>
                            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-medium"
                                style="background:rgba(139,92,246,0.12);color:#a78bfa">Gemini AI</span>
                        </div>
                        <p id="modalRingkasan" class="text-sm leading-relaxed whitespace-pre-line text-gray-300">
                            Memproses...</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="rounded-xl p-5"
                            style="background:rgba(16,185,129,0.03);border:1px solid rgba(16,185,129,0.15)">
                            <div class="flex items-center gap-2.5 mb-3">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="text-sm font-bold text-emerald-400">Topik Dibahas</h3>
                            </div>
                            <ul id="modalTopik" class="space-y-2"></ul>
                        </div>
                        <div class="rounded-xl p-5"
                            style="background:rgba(251,191,36,0.03);border:1px solid rgba(251,191,36,0.15)">
                            <div class="flex items-center gap-2.5 mb-3">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-sm font-bold text-amber-400">Keputusan Penting</h3>
                            </div>
                            <ul id="modalKeputusan" class="space-y-2"></ul>
                        </div>
                    </div>
                    <div class="rounded-xl p-5"
                        style="background:rgba(56,189,248,0.03);border:1px solid rgba(56,189,248,0.15)">
                        <div class="flex items-center gap-2.5 mb-3">
                            <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <h3 class="text-sm font-bold text-sky-400">Action Items</h3>
                        </div>
                        <div class="overflow-x-auto rounded-lg border" style="border-color:rgba(56,189,248,0.12)">
                            <table class="w-full text-left text-sm">
                                <thead style="background:rgba(56,189,248,0.05)">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-sky-400">
                                            Tugas
                                        </th>
                                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-sky-400">
                                            PIC
                                        </th>
                                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-sky-400">
                                            Deadline</th>
                                    </tr>
                                </thead>
                                <tbody id="modalActionItems" class="divide-y text-gray-300"
                                    style="border-color:rgba(56,189,248,0.06)"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="rounded-xl p-5"
                        style="background:rgba(244,63,94,0.03);border:1px solid rgba(244,63,94,0.15)">
                        <div class="flex items-center gap-2.5 mb-3">
                            <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <h3 class="text-sm font-bold text-rose-400">Risiko / Catatan</h3>
                        </div>
                        <ul id="modalRisiko" class="space-y-2"></ul>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-800 bg-gray-950/40 flex justify-end gap-3">
                <a id="modalPdfBtn" href="#" target="_blank"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition">Unduh
                    PDF</a>
                <button id="closeNotulensiModalFooterBtn"
                    class="bg-gray-800 hover:bg-gray-700 text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // ======================== MOCK DATA ========================
        var mockParticipants = [{
                id: 1,
                name: 'Anda'
            },
            {
                id: 2,
                name: 'Budi Santoso'
            },
            {
                id: 3,
                name: 'Siti Rahma'
            },
            {
                id: 4,
                name: 'Ahmad Fauzi'
            },
            {
                id: 5,
                name: 'Dewi Lestari'
            },
            {
                id: 6,
                name: 'Rudi Hermawan'
            },
        ];

        // ======================== DEKLARASI VARIABEL ========================
        let isMuted = false,
            isCameraOff = false,
            audioEnabledByUser = false;
        let isScreenSharing = false;
        let screenShareStream = null;
        let pinnedIdentities = [];
        let currentLayout = localStorage.getItem('layout_MT-001') || 'grid';
        let activeSpeakerIdentity = null;
        let spotlightTargetIdentity = null;
        const meetingId = 'MT-001';
        // Load saved device state
        try {
            const s = JSON.parse(localStorage.getItem('device_' + meetingId));
            if (s) {
                isMuted = !!s.m;
                isCameraOff = !!s.c;
            }
        } catch (e) {}
        const currentUserId = 1;
        const authName = 'Anda';
        const baseUrl = '/meeting/' + meetingId;
        const isCreator = true;
        const isAdmin = true;

        // DOM references
        const remoteVideos = document.getElementById('remoteVideos');
        const muteBtn = document.getElementById('muteBtn');
        const cameraBtn = document.getElementById('cameraBtn');
        const leaveBtn = document.getElementById('leaveBtn');
        const startRecordBtn = document.getElementById('startRecordBtn');
        const stopUploadBtn = document.getElementById('stopUploadBtn');
        const recordScreenBtn = document.getElementById('recordScreenBtn');
        const recordActiveDot = document.getElementById('recordActiveDot');
        const recordIconDefault = document.getElementById('recordIconDefault');
        const recordIconActive = document.getElementById('recordIconActive');
        const layoutBtn = document.getElementById('layoutBtn');
        const layoutDropdown = document.getElementById('layoutDropdown');
        const pdfBtn = document.getElementById('pdfBtn');
        const connectionStatusEl = document.getElementById('connectionStatus');
        const enableAudioBtn = document.getElementById('enableAudioBtn');
        const localAvatar = document.getElementById('localAvatar');
        const localAvatarText = document.getElementById('localAvatarText');
        const transcriptSidebar = document.getElementById('transcriptSidebar');
        const transcriptMessages = document.getElementById('transcriptMessages');
        const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
        const openSidebarBtn = document.getElementById('openSidebarBtn');

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
            if (localAvatar) localAvatar.classList.toggle('hidden', !isCameraOff);
            if (localAvatarText) localAvatarText.textContent = isCameraOff ? authName.charAt(0).toUpperCase() : '';
        }
        applyDeviceState();
        const localAvatarCircle = document.getElementById('localAvatarCircle');
        const sidebarPulse = document.getElementById('sidebarPulse');
        const sidebarStatusIndicator = document.getElementById('sidebarStatusIndicator');
        const transcribeStatusEl = document.getElementById('transcribeStatus');
        const showNotulensiBtn = document.getElementById('showNotulensiBtn');
        const notulensiModal = document.getElementById('notulensiModal');

        // Remote participant metadata
        const remoteParticipants = new Map();

        // ======================== FUNGSI UTILITY ========================
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

        async function leaveMeeting() {
            if (screenShareStream) {
                screenShareStream.getTracks().forEach(t => t.stop());
                screenShareStream = null;
            }
            isScreenSharing = false;
            window.location.href = '/join';
        }

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
            // Update debug panel layout name
            const debugName = document.getElementById('debugLayoutName');
            if (debugName) debugName.textContent = mode.charAt(0).toUpperCase() + mode.slice(1);
            // Update debug layout buttons
            document.querySelectorAll('#debugPanel button[onclick*="applyLayout"]').forEach(btn => {
                const match = btn.getAttribute('onclick')?.match(/'([^']+)'/);
                if (match) btn.classList.toggle('bg-violet-500/20', match[1] === mode);
                btn.classList.toggle('text-violet-400', match && match[1] === mode);
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

            // Move ALL cards back to original containers before applying new layout
            allCards.forEach(card => {
                card.classList.remove('speaker-main-video', 'spotlight-main', 'spotlight-overlay');
                if (card.id === 'localVideoContainer') {
                    if (card.parentElement !== grid) grid.appendChild(card);
                } else if (remoteContainer && card.parentElement !== remoteContainer) {
                    remoteContainer.appendChild(card);
                }
            });

            // Remove layout container elements
            grid.querySelectorAll('.speaker-strip, .sidebar-main-area, .sidebar-vertical-strip').forEach(el => el.remove());

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

            const remoteContainer = document.getElementById('remoteVideos');
            const allCards = getParticipantCards();

            // Hide cards not on current page, show cards on current page
            paginatedCards.forEach(el => {
                el.style.display = '';
                el.classList.remove('speaker-main-video', 'spotlight-main', 'spotlight-overlay');
                if (el.id !== 'localVideoContainer' && remoteContainer && el.parentElement !== remoteContainer) {
                    remoteContainer.appendChild(el);
                }
                if (el.id === 'localVideoContainer' && el.parentElement !== grid) {
                    grid.appendChild(el);
                }
            });
            allCards.forEach(el => {
                if (!paginatedCards.includes(el)) {
                    el.style.display = 'none';
                }
            });
        }

        function applySpeakerLayout(grid, remotes, totalCount) {
            grid.classList.add('layout-speaker');
            const cards = getParticipantCards();
            const mainId = getMainSpeakerIdentity();

            // Create/maintain speaker strip container
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
            const isMobile = window.innerWidth < 768;
            const overlayPositions = isMobile ? [
                { bottom: 8, right: 8 },
                { bottom: 8, right: 116 },
                { bottom: 80, right: 8 },
                { bottom: 80, right: 116 },
                { bottom: 8, left: 8 }
            ] : [
                { bottom: 16, right: 16 },
                { bottom: 16, right: 210 },
                { bottom: 16, right: 404 },
                { bottom: 16, right: 598 },
                { bottom: 16, left: 16 }
            ];
            paginatedOverlays.forEach((card, idx) => {
                card.classList.add('spotlight-overlay');
                card.style.display = '';
                const pos = overlayPositions[idx] || {
                    bottom: 16,
                    right: 16
                };
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
            const remoteContainer = document.getElementById('remoteVideos');
            if (!list || !remoteContainer) return;
            const remotes = Array.from(remoteContainer.querySelectorAll(':scope > [id^="remote-card-"]'));
            const keys = remotes.map(el => el.dataset.identity).join(',');
            const cacheKey = `${currentUserId}|${remotes.length}|${keys}`;
            if (_sidebarCacheKey === cacheKey) return;
            _sidebarCacheKey = cacheKey;
            const totalCount = 1 + remotes.length;
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

        // ======================== MOCK INIT ========================
        function initMockMeeting() {
            setParticipantCount(mockParticipants.length);
        }

        // ======================== CREATE REMOTE CARD (matches livekit.blade.php) ========================
        function createMockRemoteCard(identity, displayName) {
            const safeKey = identity.replace(/[^a-zA-Z0-9_-]/g, '_');
            const cardId = 'remote-card-' + safeKey;
            const videoId = 'remote-video-' + safeKey;
            let card = document.getElementById(cardId);
            if (card) return;

            const idx = mockParticipants.findIndex(p => String(p.id) === identity);
            const initials = displayName.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
            const colors = ['#7c3aed', '#0891b2', '#d97706', '#059669', '#dc2626', '#7c3aed', '#db2777',
                '#0284c7', '#65a30d', '#ea580c', '#9333ea', '#0d9488', '#dc2626', '#ca8a04'
            ];
            const color = colors[(idx > 0 ? idx - 1 : 0) % colors.length];

            card = document.createElement('div');
            card.id = cardId;
            card.dataset.identity = identity;
            card.className = 'rounded-2xl overflow-hidden shadow-xl h-full w-full relative video-card m-1';

            const video = document.createElement('video');
            video.id = videoId;
            video.autoplay = true;
            video.playsInline = true;
            video.className = 'w-full h-full object-cover';
            video.muted = true;

            const avatar = document.createElement('div');
            avatar.id = 'remote-avatar-' + safeKey;
            avatar.className = 'absolute inset-0 flex items-center justify-center z-10';
            avatar.style.background = 'rgba(0,0,0,0.6)';
            avatar.innerHTML = `<div class="relative"><div id="remote-avatar-circle-${safeKey}" style="width:112px;height:112px;border-radius:50%;background:${color};display:flex;align-items:center;justify-content:center;transition:all 0.3s"><span style="font-size:3rem;color:#fff;font-weight:700;text-transform:uppercase">${initials}</span></div></div>`;

            const pinBtn = document.createElement('button');
            pinBtn.id = 'pin-btn-' + safeKey;
            pinBtn.dataset.identity = identity;
            pinBtn.className = 'absolute top-2 right-2 pin-btn text-xs px-1.5 py-0.5 z-20 transition-colors';
            pinBtn.innerHTML = '<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/></svg>';

            const label = document.createElement('div');
            label.className = 'absolute bottom-2 left-2 text-xs px-2 py-1 rounded name-label text-gray-200';
            label.textContent = displayName;

            card.appendChild(video);
            card.appendChild(avatar);
            card.appendChild(pinBtn);
            card.appendChild(label);

            remoteVideos.appendChild(card);
            remoteParticipants.set(identity, {
                identity,
                displayName,
                cardId,
                videoId
            });
        }

        // ======================== SCREEN SHARE (simplified) ========================
        const screenShareBtn = document.getElementById('screenShareBtn');
        const screenShareContainer = document.getElementById('screenShareContainer');
        const screenShareVideo = document.getElementById('screenShareVideo');
        const screenShareLabel = document.getElementById('screenShareLabel');
        const stopScreenShareBtn = document.getElementById('stopScreenShareBtn');
        const screenShareActiveDot = document.getElementById('screenShareActiveDot');

        function toggleScreenShare() {
            isScreenSharing = !isScreenSharing;
            if (isScreenSharing) {
                screenShareBtn.classList.add('text-green-400');
                screenShareBtn.classList.remove('text-white');
                if (screenShareActiveDot) screenShareActiveDot.classList.remove('hidden');
            } else {
                screenShareBtn.classList.remove('text-green-400');
                screenShareBtn.classList.add('text-white');
                if (screenShareActiveDot) screenShareActiveDot.classList.add('hidden');
                screenShareContainer.classList.add('hidden');
            }
        }

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
            // Toggle pinned-card class on video cards
            document.querySelectorAll('[id^="remote-card-"]').forEach(card => {
                const id = card.dataset?.identity || '';
                card.classList.toggle('pinned-card', isPinned(id));
            });
            const localCard = document.getElementById('localVideoContainer');
            if (localCard) {
                localCard.classList.toggle('pinned-card', isPinned(String(currentUserId)));
            }
            // Toggle pin button highlights
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

        // Context menu via event delegation on participantList
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

        function hideScreenShare() {
            if (isScreenSharing) return;
            screenShareContainer.classList.add('hidden');
            screenShareContainer.style.display = '';
            if (screenShareVideo) screenShareVideo.srcObject = null;
        }

        if (screenShareBtn) {
            screenShareBtn.addEventListener('click', toggleScreenShare);
        }
        if (stopScreenShareBtn) {
            stopScreenShareBtn.addEventListener('click', toggleScreenShare);
        }
        const pinScreenShareBtn = document.getElementById('pinScreenShareBtn');
        if (pinScreenShareBtn) {
            pinScreenShareBtn.addEventListener('click', () => togglePin('screen-share'));
        }

        // Layout selector
        if (layoutBtn && layoutDropdown) {
            function showLayoutDropdown() {
                layoutDropdown.style.display = '';
                layoutDropdown.style.opacity = '0';
                void layoutDropdown.offsetHeight;
                layoutDropdown.style.opacity = '1';
            }

            function hideLayoutDropdown() {
                layoutDropdown.style.opacity = '0';
                setTimeout(() => {
                    layoutDropdown.style.display = 'none';
                }, 200);
            }
            layoutBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (layoutDropdown.style.display === 'none') {
                    showLayoutDropdown();
                } else {
                    hideLayoutDropdown();
                }
            });
            document.addEventListener('click', (e) => {
                if (!layoutBtn.contains(e.target) && !layoutDropdown.contains(e.target) &&
                    layoutDropdown.style.display !== 'none') {
                    hideLayoutDropdown();
                }
            });
            layoutDropdown.querySelectorAll('button').forEach(btn => {
                btn.addEventListener('click', () => {
                    applyLayout(btn.dataset.layout);
                    hideLayoutDropdown();
                });
            });
            // Set initial active state
            layoutDropdown.querySelector(`[data-layout="${currentLayout}"]`)?.classList.add('active-layout');
        }

        // Navbar layout selector
        const layoutNavBtn = document.getElementById('layoutNavBtn');
        const layoutNavDropdown = document.getElementById('layoutNavDropdown');
        if (layoutNavBtn && layoutNavDropdown) {
            function showNavLayoutDropdown() {
                layoutNavDropdown.style.display = '';
                layoutNavDropdown.style.opacity = '0';
                void layoutNavDropdown.offsetHeight;
                layoutNavDropdown.style.opacity = '1';
            }
            function hideNavLayoutDropdown() {
                layoutNavDropdown.style.opacity = '0';
                setTimeout(() => { layoutNavDropdown.style.display = 'none'; }, 200);
            }
            layoutNavBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (layoutNavDropdown.style.display === 'none') {
                    showNavLayoutDropdown();
                } else {
                    hideNavLayoutDropdown();
                }
            });
            document.addEventListener('click', (e) => {
                if (!layoutNavBtn.contains(e.target) && !layoutNavDropdown.contains(e.target) &&
                    layoutNavDropdown.style.display !== 'none') {
                    hideNavLayoutDropdown();
                }
            });
            layoutNavDropdown.querySelectorAll('button').forEach(btn => {
                btn.addEventListener('click', () => {
                    applyLayout(btn.dataset.layout);
                    hideNavLayoutDropdown();
                });
            });
            function syncNavLayoutActive() {
                layoutNavDropdown.querySelectorAll('button').forEach(btn => {
                    btn.classList.toggle('active-layout', btn.dataset.layout === currentLayout);
                });
            }
            syncNavLayoutActive();
        }

        // Screen recording
        if (recordScreenBtn) {
            recordScreenBtn.addEventListener('click', () => {
                // Placeholder: recording disabled in mock mode
                alert('Screen recording is disabled in layout test mode.');
            });
        }
        const recordingPopupClose = document.getElementById('recordingPopupClose');
        if (recordingPopupClose) {
            recordingPopupClose.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }

        // Spotlight: click overlay to switch spotlight target
        document.addEventListener('click', (e) => {
            const overlayCard = e.target.closest('.spotlight-overlay');
            if (overlayCard && currentLayout === 'spotlight') {
                spotlightTargetIdentity = overlayCard.dataset.identity;
                updateParticipantUI();
            }
        });

        function getVisibleParticipants() {
            const allCards = getParticipantCards();
            const localEl = document.getElementById('localVideoContainer');
            const remotes = allCards.filter(c => c.id !== 'localVideoContainer');
            const result = [];
            if (localEl) result.push(localEl);
            const pinned = [];
            const unpinned = [];
            remotes.forEach(el => {
                const id = el.dataset?.identity || '';
                const pinIdx = pinnedIdentities.indexOf(id);
                if (pinIdx >= 0) {
                    pinned[pinIdx] = el;
                } else {
                    unpinned.push(el);
                }
            });
            pinned.forEach(el => {
                if (el) result.push(el);
            });
            unpinned.forEach(el => result.push(el));
            return result;
        }

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
                const localAvatar = document.getElementById('localAvatar');
                const localAvatarText = document.getElementById('localAvatarText');
                if (isCameraOff) {
                    cameraBtn.classList.add('text-red-400');
                    cameraBtn.classList.remove('text-white');
                    if (localAvatar) {
                        localAvatar.classList.remove('hidden');
                        if (localAvatarText) localAvatarText.textContent = authName.charAt(0).toUpperCase();
                    }
                } else {
                    cameraBtn.classList.add('text-white');
                    cameraBtn.classList.remove('text-red-400');
                    if (localAvatar) localAvatar.classList.add('hidden');
                }
                toggleCamIcons(isCameraOff);
            });
        }

        if (leaveBtn) leaveBtn.addEventListener('click', leaveMeeting);

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
                if (confirmNotulenModal) confirmNotulenModal.classList.remove('hidden');
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
                if (aiNotulenActiveDot) aiNotulenActiveDot.classList.add('hidden');
                const headerInd = document.getElementById('aiNotulenHeaderIndicator');
                if (headerInd) headerInd.classList.add('hidden');
            });
        }

        if (toggleSidebarBtn) toggleSidebarBtn.addEventListener('click', () => {
            transcriptSidebar.classList.add('collapsed');
            openSidebarBtn.classList.remove('hidden');
            const activeDot = document.getElementById('sidebarActiveDot');
            if (activeDot) activeDot.classList.remove('hidden');
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
        closeModalBtns.forEach(btn => btn && btn.addEventListener('click', () => {
            const modal = document.getElementById('notulensiModal');
            if (modal) modal.classList.add('hidden');
        }));
        if (notulensiModal) notulensiModal.addEventListener('click', (e) => {
            if (e.target === notulensiModal) notulensiModal.classList.add('hidden');
        });

        // ======================== DELEGATED CLICKS (pin, focus) ========================
        document.addEventListener('click', function(e) {
            // Pin button click
            const pinBtn = e.target.closest('[id^="pin-btn-"]');
            if (pinBtn && pinBtn.dataset.identity) {
                togglePin(pinBtn.dataset.identity);
                return;
            }
            // Video card click → set active speaker
            const tile = e.target.closest('.video-card');
            if (!tile) return;
            const identity = tile.dataset.identity;
            if (identity && String(identity) !== String(currentUserId)) {
                activeSpeakerIdentity = identity;
                if (currentLayout === 'speaker' || currentLayout === 'sidebar' || currentLayout === 'spotlight') {
                    updateParticipantUI();
                }
                document.querySelectorAll('.video-card').forEach(c => c.classList.remove('speaking-ring'));
                tile.classList.add('speaking-ring');
            }
        });

        // Auto-rotate active speaker every 5 seconds
        setInterval(function() {
            const allCards = getParticipantCards();
            const remoteCards = allCards.filter(c => c.id !== 'localVideoContainer');
            if (remoteCards.length === 0) return;
            const identities = remoteCards.map(c => c.dataset?.identity).filter(Boolean);
            if (identities.length === 0) return;
            const currentIdx = identities.indexOf(activeSpeakerIdentity);
            const nextIdx = (currentIdx + 1) % identities.length;
            activeSpeakerIdentity = identities[nextIdx];
            if (isStripLayout()) {
                _lastUIUpdateKey = '';
                updateParticipantUI();
            }
            document.querySelectorAll('.video-card').forEach(c => c.classList.remove('speaking-ring'));
            const target = document.querySelector(`[data-identity="${activeSpeakerIdentity}"]`);
            if (target) target.classList.add('speaking-ring');
        }, 5000);

        // ======================== INIT ========================
        initMockMeeting();
        updateParticipantUI();

        // Update debug panel initial state
        const debugName = document.getElementById('debugLayoutName');
        if (debugName) debugName.textContent = currentLayout.charAt(0).toUpperCase() + currentLayout.slice(1);

        // Set initial speaking ring on first remote
        setTimeout(function() {
            const firstRemote = getParticipantCards().find(c => c.id !== 'localVideoContainer');
            if (firstRemote) {
                firstRemote.classList.add('speaking-ring');
                activeSpeakerIdentity = firstRemote.dataset?.identity;
            }
        }, 100);

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
                // Close share popup if mobile more is clicked
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
        // ======================== DEBUG PANEL ========================
        // Toggle debug panel
        document.addEventListener('keydown', function(e) {
            if (e.key === 'd' && e.ctrlKey) {
                const panel = document.getElementById('debugPanel');
                if (panel) panel.classList.toggle('hidden');
            }
        });

        function setParticipantCount(count) {
            count = Math.max(1, Math.min(15, count));
            currentPage = 0;
            const localName = 'Anda';
            const names = ['Budi Santoso', 'Siti Rahma', 'Ahmad Fauzi', 'Dewi Lestari', 'Rudi Hermawan',
                'Mega Putri', 'Adi Pratama', 'Rina Wijaya', 'Deni Saputra', 'Fitri Handayani',
                'Agus Setiawan', 'Wulan Sari', 'Hendra Gunawan', 'Indah Permata'
            ];

            // Rebuild mockParticipants
            mockParticipants.length = 0;
            mockParticipants.push({
                id: 1,
                name: localName
            });
            for (let i = 0; i < count - 1; i++) {
                mockParticipants.push({
                    id: i + 2,
                    name: names[i % names.length]
                });
            }

            // Rebuild remote cards
            const remoteContainer = document.getElementById('remoteVideos');
            const grid = document.getElementById('videoGridMain');
            // Remove old layout containers
            grid.querySelectorAll('.speaker-strip, .sidebar-main-area, .sidebar-vertical-strip').forEach(el => el.remove());
            // Remove existing remote cards
            remoteContainer.querySelectorAll('[id^="remote-card-"]').forEach(el => el.remove());
            // Reset local card position
            const localEl = document.getElementById('localVideoContainer');
            if (localEl && localEl.parentElement !== grid) grid.appendChild(localEl);

            // Clear remoteParticipants map
            remoteParticipants.clear();

            // Create new remote cards
            mockParticipants.filter(p => p.id !== 1).forEach((p) => {
                const identity = String(p.id);
                createMockRemoteCard(identity, p.name);
            });

            // Update display
            document.getElementById('debugCount').textContent = mockParticipants.length;

            // Preserve activeSpeakerIdentity if still valid
            const prevSpeaker = activeSpeakerIdentity;
            const speakerStillExists = prevSpeaker && mockParticipants.some(p => String(p.id) === prevSpeaker);
            if (!speakerStillExists) {
                activeSpeakerIdentity = null;
            }

            updateParticipantUI();

            // Reset speaking ring
            document.querySelectorAll('.video-card').forEach(c => c.classList.remove('speaking-ring'));
            setTimeout(function() {
                const speakerId = activeSpeakerIdentity;
                if (speakerId) {
                    const target = document.querySelector(`[data-identity="${speakerId}"]`);
                    if (target) {
                        target.classList.add('speaking-ring');
                        return;
                    }
                }
                const firstRemote = getParticipantCards().find(c => c.id !== 'localVideoContainer');
                if (firstRemote) {
                    firstRemote.classList.add('speaking-ring');
                    activeSpeakerIdentity = firstRemote.dataset?.identity;
                }
            }, 50);
        }

        function resetLayout() {
            localStorage.removeItem('layout_' + meetingId);
            currentLayout = 'grid';
            applyLayout('grid');
        }
    </script>

    <!-- Floating Debug Panel -->
    <div id="debugPanel"
        class="fixed top-4 right-4 z-[999] bg-gray-900/90 backdrop-blur-xl border border-white/10 rounded-xl p-4 min-w-[200px] shadow-2xl select-none font-sans">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-violet-400">Debug Panel</span>
            <span class="text-[10px] text-gray-500">Ctrl+D</span>
        </div>

        <div class="space-y-3">
            <div>
                <label class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Participants</label>
                <div class="flex items-center gap-2 mt-1">
                    <button onclick="setParticipantCount(mockParticipants.length - 1)"
                        class="w-7 h-7 rounded-lg bg-red-500/20 hover:bg-red-500/40 text-red-400 flex items-center justify-center text-sm font-bold transition">&minus;</button>
                    <span id="debugCount" class="text-white font-bold text-sm min-w-[24px] text-center">6</span>
                    <button onclick="setParticipantCount(mockParticipants.length + 1)"
                        class="w-7 h-7 rounded-lg bg-green-500/20 hover:bg-green-500/40 text-green-400 flex items-center justify-center text-sm font-bold transition">+</button>
                </div>
            </div>

            <div>
                <label class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Current Layout</label>
                <div id="debugLayoutName" class="text-white font-bold text-sm mt-1 capitalize">Grid</div>
            </div>

            <div class="flex gap-1.5">
                <button onclick="applyLayout('grid')"
                    class="flex-1 text-[10px] px-2 py-1.5 rounded-lg bg-white/5 hover:bg-violet-500/20 text-gray-400 hover:text-violet-400 transition font-semibold">Grid</button>
                <button onclick="applyLayout('speaker')"
                    class="flex-1 text-[10px] px-2 py-1.5 rounded-lg bg-white/5 hover:bg-violet-500/20 text-gray-400 hover:text-violet-400 transition font-semibold">Speaker</button>
                <button onclick="applyLayout('sidebar')"
                    class="flex-1 text-[10px] px-2 py-1.5 rounded-lg bg-white/5 hover:bg-violet-500/20 text-gray-400 hover:text-violet-400 transition font-semibold">Sidebar</button>
                <button onclick="applyLayout('spotlight')"
                    class="flex-1 text-[10px] px-2 py-1.5 rounded-lg bg-white/5 hover:bg-violet-500/20 text-gray-400 hover:text-violet-400 transition font-semibold">Spotlight</button>
            </div>

            <button id="debugResetLayout"
                class="w-full text-[10px] px-2 py-1.5 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 transition font-semibold"
                onclick="resetLayout()">
                Reset Layout
            </button>
        </div>
    </div>
</body>

</html>
