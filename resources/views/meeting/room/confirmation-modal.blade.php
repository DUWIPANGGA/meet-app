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

            <!-- "Akhiri Rapat?" Confirmation Modal -->
            <div id="confirmEndMeetingModal"
                class="absolute inset-0 flex items-center justify-center z-40 hidden backdrop-blur-sm bg-black/40">
                <div class="bg-[#242424] border border-gray-700 shadow-2xl rounded-2xl p-8 flex flex-col items-center max-w-sm">
                    <div class="text-amber-400 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h2 id="confirmEndTitle" class="text-xl font-bold text-white mb-2 text-center">Akhiri Rapat?</h2>
                    <p id="confirmEndDesc" class="text-gray-400 text-sm mb-6 text-center">AI Notulen sedang aktif. Mengakhiri rapat akan menghentikan proses transkrip.</p>
                    <div class="flex gap-4">
                        <button id="cancelEndMeetingBtn"
                            class="bg-gray-700 text-white px-6 py-2 font-bold text-lg hover:bg-gray-600 transition rounded-lg">Batal</button>
                        <button id="confirmEndMeetingBtn"
                            class="bg-red-600 text-white px-6 py-2 font-bold text-lg hover:bg-red-700 transition rounded-lg">Ya, Akhiri</button>
                    </div>
                </div>
            </div>
