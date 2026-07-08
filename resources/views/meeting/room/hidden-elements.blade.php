        <!-- Hidden statuses -->
        <div class="hidden">
            <span id="connectionStatus"></span>
            <span id="pipelineStatus"></span>
            <button id="enableAudioBtn">Audio</button>
        </div>

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

        <!-- Layout containers -->
        <div id="speakerMainVideo" class="hidden"></div>
        <div id="speakerStrip" class="hidden"></div>
        <div id="sidebarMainArea" class="hidden"></div>
        <div id="sidebarStrip" class="hidden"></div>
