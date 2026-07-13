        <!-- Top Bar -->
        <div
            class="px-6 py-4 flex justify-between items-center z-10 top-bar">
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

            </div>
        </div>
