@extends('admin.layouts.app')
@section('title', 'Buat Arsip')

@section('content')
<div class="max-w-2xl">
    <div class="page-header">
        <a href="{{ route('admin.arsips.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <h1>Buat Arsip Baru</h1>
        <p>Arsipkan notulensi meeting</p>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('admin.arsips.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Meeting <span class="text-red-400">*</span></label>
                <select name="meeting_id" required class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    <option value="">-- Pilih Meeting --</option>
                    @foreach($meetings as $m)
                    <option value="{{ $m->id }}" {{ old('meeting_id') == $m->id ? 'selected' : '' }}>{{ $m->nama_rapat }} ({{ $m->tanggal }})</option>
                    @endforeach
                </select>
                @error('meeting_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Notulensi (opsional)</label>
                <select name="notulensi_id" class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    <option value="">-- Pilih Notulensi --</option>
                    @foreach($notulensis as $n)
                    <option value="{{ $n->id }}" {{ old('notulensi_id') == $n->id ? 'selected' : '' }}>Notulensi #{{ $n->id }} - {{ $n->meeting?->nama_rapat ?? '-' }} ({{ $n->tanggal_generate }})</option>
                    @endforeach
                </select>
                @error('notulensi_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Tanggal Arsip <span class="text-red-400">*</span></label>
                <input type="date" name="tanggal_arsip" value="{{ old('tanggal_arsip', date('Y-m-d')) }}" required
                       class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                @error('tanggal_arsip') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-4 border-t" style="border-color:var(--divider)">
                <button type="submit" class="btn-primary">Simpan Arsip</button>
                <a href="{{ route('admin.arsips.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection