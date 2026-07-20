@extends('admin.layouts.app')
@section('title', 'Edit Arsip')

@section('content')
<div class="max-w-2xl">
    <div class="page-header">
        <a href="{{ route('admin.arsips.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <div class="flex items-center gap-3 mb-1">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <h1>Edit Arsip</h1>
        </div>
        <p>{{ $arsip->meeting?->nama_rapat ?? '-' }}</p>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('admin.arsips.update', $arsip) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Meeting <span class="text-red-400">*</span></label>
                <select name="meeting_id" required class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    <option value="">-- Pilih Meeting --</option>
                    @foreach($meetings as $m)
                    <option value="{{ $m->id }}" {{ old('meeting_id', $arsip->meeting_id) == $m->id ? 'selected' : '' }}>{{ $m->nama_rapat }} ({{ $m->tanggal }})</option>
                    @endforeach
                </select>
                @error('meeting_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Notulensi (opsional)</label>
                <select name="notulensi_id" class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    <option value="">-- Pilih Notulensi --</option>
                    @foreach($notulensis as $n)
                    <option value="{{ $n->id }}" {{ old('notulensi_id', $arsip->notulensi_id) == $n->id ? 'selected' : '' }}>Notulensi #{{ $n->id }} - {{ $n->meeting?->nama_rapat ?? '-' }} ({{ $n->tanggal_generate }})</option>
                    @endforeach
                </select>
                @error('notulensi_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Tanggal Arsip <span class="text-red-400">*</span></label>
                <input type="date" name="tanggal_arsip" value="{{ old('tanggal_arsip', $arsip->tanggal_arsip) }}" required
                       class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                @error('tanggal_arsip') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-4 border-t" style="border-color:var(--divider)">
                <button type="submit" class="btn-primary">Update Arsip</button>
                <a href="{{ route('admin.arsips.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection