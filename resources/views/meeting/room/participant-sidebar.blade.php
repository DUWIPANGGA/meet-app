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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
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
