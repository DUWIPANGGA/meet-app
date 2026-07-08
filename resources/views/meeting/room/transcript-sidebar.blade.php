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
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
