@extends('admin.layouts.app')
@section('title', 'Edit Transkrip')

@section('content')
<div class="max-w-3xl">
    <div class="page-header">
        <a href="{{ route('admin.transkrips.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <h1>Edit Transkrip</h1>
        <p style="color:var(--text-muted)">{{ $transkrip->meeting?->nama_rapat ?? 'Rapat #'.$transkrip->meeting_id }} &middot; {{ $transkrip->tanggal_generate?->translatedFormat('d M Y') ?? '-' }}</p>
    </div>

    <form action="{{ route('admin.transkrips.update', $transkrip) }}" method="POST">
        @csrf @method('PUT')

        <div class="card p-6 space-y-6">
            {{-- Meeting --}}
            <div>
                <label for="meeting_id" class="block text-sm font-semibold mb-2" style="color:var(--text-primary)">Meeting</label>
                <select name="meeting_id" id="meeting_id" required
                    class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:outline-none focus:ring-2 focus:ring-violet-500/40"
                    style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-primary)">
                    @foreach($meetings as $m)
                    <option value="{{ $m->id }}" {{ old('meeting_id', $transkrip->meeting_id) == $m->id ? 'selected' : '' }}>
                        {{ $m->nama_rapat }} &middot; {{ \Carbon\Carbon::parse($m->tanggal)->translatedFormat('d M Y') }}
                    </option>
                    @endforeach
                </select>
                @error('meeting_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Tanggal Generate --}}
            <div>
                <label for="tanggal_generate" class="block text-sm font-semibold mb-2" style="color:var(--text-primary)">Tanggal Generate</label>
                <input type="date" name="tanggal_generate" id="tanggal_generate" value="{{ old('tanggal_generate', $transkrip->tanggal_generate?->format('Y-m-d') ?? date('Y-m-d')) }}" required
                    class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:outline-none focus:ring-2 focus:ring-violet-500/40"
                    style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-primary)">
                @error('tanggal_generate')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- OpenAI Model --}}
            <div>
                <label for="openai_model" class="block text-sm font-semibold mb-2" style="color:var(--text-primary)">Model AI <span class="text-xs" style="color:var(--text-muted)">(opsional)</span></label>
                <input type="text" name="openai_model" id="openai_model" value="{{ old('openai_model', $transkrip->openai_model) }}" maxlength="64"
                    class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:outline-none focus:ring-2 focus:ring-violet-500/40"
                    style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-primary)">
                @error('openai_model')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Hasil Transkrip --}}
            <div>
                <label for="hasil_transkrip" class="block text-sm font-semibold mb-2" style="color:var(--text-primary)">Hasil Transkrip</label>
                <textarea name="hasil_transkrip" id="hasil_transkrip" rows="15" required
                    class="w-full rounded-xl px-4 py-3 text-sm border transition focus:outline-none focus:ring-2 focus:ring-violet-500/40 font-mono"
                    style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-primary);resize:vertical;line-height:1.7">{{ old('hasil_transkrip', $transkrip->hasil_transkrip) }}</textarea>
                @error('hasil_transkrip')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 pt-4 border-t" style="border-color:var(--divider)">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.transkrips.index') }}" class="btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
