        <!-- Top Bar -->
        <div
            class="static md:absolute md:top-0 md:left-0 md:right-0 px-6 py-4 flex justify-between items-center z-10 top-bar">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                <h1 class="text-xl font-semibold">{{ auth()->user()?->name ?? 'Nama' }}</h1>
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
                    <svg id="roomThemeIconSun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
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
