@extends('admin.layouts.app')
@section('title', 'Buat Rapat')

@section('content')
<div>
    <div class="page-header">
        <a href="{{ route('admin.meetings.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali ke Daftar Rapat
        </a>
        <h1>Buat Rapat Baru</h1>
        <p>Isi detail rapat yang akan dibuat</p>
    </div>

    <div class="card p-6" x-data="{ meetingType: '{{ old('tipe_rapat', 'terjadwal') }}', aksesMode: '{{ old('akses_meeting', 'semua_orang') }}', userSearch: '', searchResults: [], selectedUsers: [], showUserSearch: false }">
        <form method="POST" action="{{ route('admin.meetings.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Nama Rapat <span class="text-red-400">*</span></label>
                <input type="text" name="nama_rapat" value="{{ old('nama_rapat') }}" required placeholder="Contoh: Rapat Koordinasi Bulanan"
                       class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                @error('nama_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Deskripsi (opsional)</label>
                <textarea name="deskripsi_rapat" rows="3" placeholder="Deskripsi rapat"
                          class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm resize-none">{{ old('deskripsi_rapat') }}</textarea>
                @error('deskripsi_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3" x-show="meetingType === 'terjadwal'" style="display: none;">
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Tanggal <span class="text-red-400">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                           min="{{ date('Y-m-d') }}"
                           :required="meetingType === 'terjadwal'"
                           class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    @error('tanggal') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Waktu <span class="text-red-400">*</span></label>
                    <input type="time" name="waktu" value="{{ old('waktu', date('H:i')) }}"
                           :required="meetingType === 'terjadwal'"
                           class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    @error('waktu') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2" style="color:var(--text-secondary)">Tipe Rapat <span class="text-red-400">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="tipe_rapat" value="instan" x-model="meetingType" class="peer sr-only">
                        <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                            <div class="text-lg mb-0.5">⚡</div>
                            <div class="font-semibold text-sm" style="color:var(--text-primary)">Instan</div>
                            <div class="text-xs mt-0.5" style="color:var(--text-muted)">Mulai sekarang</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="tipe_rapat" value="terjadwal" x-model="meetingType" class="peer sr-only">
                        <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                            <div class="text-lg mb-0.5">📅</div>
                            <div class="font-semibold text-sm" style="color:var(--text-primary)">Terjadwal</div>
                            <div class="text-xs mt-0.5" style="color:var(--text-muted)">Atur jadwal</div>
                        </div>
                    </label>
                </div>
                @error('tipe_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <input type="hidden" name="jenis_rapat" value="Online">

            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Status</label>
                <select name="status_rapat" class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    <option value="Menunggu" {{ old('status_rapat', 'Menunggu') === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Berlangsung" {{ old('status_rapat') === 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="Selesai" {{ old('status_rapat') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Hak Akses Rapat -->
            <div>
                <label class="block text-sm font-semibold mb-2" style="color:var(--text-secondary)">Hak Akses Rapat</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="akses_meeting" value="semua_orang" x-model="aksesMode" class="peer sr-only">
                        <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition"
                            style="border-color:var(--card-border);color:var(--text-secondary)">
                            <div class="text-lg mb-0.5">👥</div>
                            <div class="font-semibold text-sm" style="color:var(--text-primary)">Semua Orang</div>
                            <div class="text-xs mt-0.5" style="color:var(--text-muted)">Bisa gabung pakai kode</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="akses_meeting" value="pilih_user" x-model="aksesMode" class="peer sr-only">
                        <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition"
                            style="border-color:var(--card-border);color:var(--text-secondary)">
                            <div class="text-lg mb-0.5">🔒</div>
                            <div class="font-semibold text-sm" style="color:var(--text-primary)">Undang User</div>
                            <div class="text-xs mt-0.5" style="color:var(--text-muted)">Pilih siapa yang boleh masuk</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- User Picker (jika pilih_user) -->
            <div x-show="aksesMode === 'pilih_user'" style="display: none;" x-cloak>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Pilih Pengguna</label>
                <div class="relative">
                    <input type="text" placeholder="Ketik nama untuk mencari..."
                        x-model="userSearch"
                        @input.debounce.300ms="if(userSearch.length >= 2) { fetch('/api/users?search=' + userSearch).then(r => r.json()).then(d => searchResults = d) } else { searchResults = [] }"
                        @focus="if(userSearch.length >= 2 && searchResults.length > 0) showUserSearch = true"
                        class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    <div x-show="showUserSearch && searchResults.length > 0" @click.away="showUserSearch = false"
                        class="absolute z-20 mt-1 w-full bg-gray-800 border border-gray-700 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                        <template x-for="user in searchResults" :key="user.id">
                            <div @click="if(!selectedUsers.find(u => u.id === user.id)) { selectedUsers.push(user); searchResults = searchResults.filter(u => u.id !== user.id); userSearch = ''; showUserSearch = false }"
                                class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-700 cursor-pointer transition">
                                <div class="w-8 h-8 rounded-full bg-violet-600 flex items-center justify-center text-white text-xs font-bold" x-text="user.name.charAt(0).toUpperCase()"></div>
                                <div>
                                    <div class="text-sm font-medium text-white" x-text="user.name"></div>
                                    <div class="text-xs text-gray-400" x-text="user.email"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <!-- Selected Users -->
                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="(user, idx) in selectedUsers" :key="user.id">
                        <div class="flex items-center gap-1.5 bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs px-2.5 py-1 rounded-full">
                            <span x-text="user.name"></span>
                            <button type="button" @click="selectedUsers.splice(idx, 1)" class="hover:text-white transition">&times;</button>
                            <input type="hidden" name="akses_user_ids[]" :value="user.id">
                        </div>
                    </template>
                </div>
                <div x-show="selectedUsers.length === 0" class="text-xs mt-2" style="color:var(--text-muted)">
                    Belum ada pengguna yang dipilih. Ketik nama di atas untuk mencari.
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t" style="border-color:var(--divider)">
                <button type="submit" class="btn-primary">Simpan Rapat</button>
                <a href="{{ route('admin.meetings.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection