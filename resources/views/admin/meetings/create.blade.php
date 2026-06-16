@extends('admin.layouts.app')
@section('title', 'Buat Rapat')

@section('content')
<div class="max-w-2xl">
    <div class="page-header">
        <a href="{{ route('admin.meetings.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <h1>Buat Rapat Baru</h1>
        <p>Isi detail rapat yang akan dibuat</p>
    </div>

    <div class="card p-6">
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

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Tanggal <span class="text-red-400">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    @error('tanggal') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Waktu <span class="text-red-400">*</span></label>
                    <input type="time" name="waktu" value="{{ old('waktu', date('H:i')) }}" required
                           class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    @error('waktu') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2" style="color:var(--text-secondary)">Tipe Rapat <span class="text-red-400">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="tipe_rapat" value="Online" {{ old('tipe_rapat', 'Online') === 'Online' ? 'checked' : '' }} class="peer sr-only">
                        <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                            <div class="flex justify-center mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="font-semibold text-sm" style="color:var(--text-primary)">Online</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="tipe_rapat" value="Offline" {{ old('tipe_rapat') === 'Offline' ? 'checked' : '' }} class="peer sr-only">
                        <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                            <div class="flex justify-center mb-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="font-semibold text-sm" style="color:var(--text-primary)">Offline</div>
                        </div>
                    </label>
                </div>
                @error('tipe_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Status</label>
                <select name="status_rapat" class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    <option value="Menunggu" {{ old('status_rapat', 'Menunggu') === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Berlangsung" {{ old('status_rapat') === 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="Selesai" {{ old('status_rapat') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-4 border-t" style="border-color:var(--divider)">
                <button type="submit" class="btn-primary">Simpan Rapat</button>
                <a href="{{ route('admin.meetings.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection