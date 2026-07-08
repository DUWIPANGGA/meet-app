        <!-- Bottom Toolbar -->
        <div
            class="absolute bottom-0 left-0 right-0 bottom-toolbar border-t border-gray-700/50 py-3 px-6 flex justify-between md:justify-center md:gap-16 items-center z-50 shadow-[0_-8px_30px_rgba(0,0,0,0.6)] overflow-visible">
            <!-- Kamera -->
            <button id="cameraBtn" class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn">
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
            <button id="muteBtn" class="flex flex-col items-center text-white hover:text-gray-200 transition toolbar-btn">
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
                        <div class="animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-violet-500 shrink-0">
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
                    <p class="text-xs text-gray-500 mb-3 text-left">Berikan link atau ID rapat ini kepada peserta lain.</p>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ $meeting->id }}"
                                class="flex-1 bg-gray-50 border border-gray-300 rounded px-2 py-1.5 text-xs text-gray-800 font-mono outline-none">
                            <span class="text-xs text-gray-500 w-12">ID</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ route('meeting.room', $meeting->id) }}"
                                class="flex-1 bg-gray-50 border border-gray-300 rounded px-2 py-1.5 text-xs text-gray-600 outline-none">
                            <button
                                onclick="navigator.clipboard.writeText('{{ route('meeting.room', $meeting->id) }}'); alert('Link disalin ke clipboard!')"
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
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
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
            <div class="relative flex flex-col items-center">
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
                <div id="layoutDropdown"
                    style="display:none;opacity:0"
                    class="absolute bottom-full mb-4 left-1/2 -translate-x-1/2 layout-dropdown min-w-[160px] transition-opacity">
                    <button data-layout="grid"><svg class="w-4 h-4" fill="currentColor"
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
            @php
                $canEnd = $isCreator || $isAdmin;
            @endphp
            <button id="leaveBtn" class="flex flex-col items-center transition toolbar-btn ml-8">
                <div class="h-12 flex items-center justify-center">
                    <div class="btn-danger text-white font-bold rounded-xl px-5 py-2.5 shadow-lg tracking-wide">
                        {{ $canEnd ? 'Akhiri' : 'Keluar' }}
                    </div>
                </div>
            </button>
        </div>
