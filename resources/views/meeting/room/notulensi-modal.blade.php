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
                            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
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
                                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-sky-400">Tugas
                                        </th>
                                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-sky-400">PIC
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
