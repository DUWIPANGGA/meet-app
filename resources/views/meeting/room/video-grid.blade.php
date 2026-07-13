            <div id="videoGridWrapper"
                class="md:flex-1 h-full overflow-hidden relative flex flex-row video-grid-container m-1 md:m-2">
                <!-- Screen Share Display -->
                <div id="screenShareContainer" class="hidden flex-1 min-w-0 relative screen-share-container m-2"
                    style="background:#111">
                    {{-- ini bad --}}
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
                    <div id="localVideoContainer" class="relative rounded-lg overflow-hidden h-full w-full video-card">
                        <video id="localVideo" autoplay muted playsinline
                            style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:scaleX(-1)"></video>
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
            <div id="paginationDots" class="flex justify-center items-center gap-1 md:gap-2 py-1 md:py-2 flex-shrink-0"
                style="display:none">
            </div>
