@extends('admin.layouts.app')
@section('title', 'Detail Transkrip')

@section('content')
<div class="max-w-4xl">
    <div class="page-header">
        <a href="{{ route('admin.transkrips.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <div class="flex items-center gap-3 mb-1">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <h1>Detail Transkrip</h1>
        </div>
        <p>{{ $transkrip->meeting?->nama_rapat ?? 'Rapat #'.$transkrip->meeting_id }}</p>
    </div>

    <div class="card p-6 space-y-6">
        {{-- Info --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Meeting</p>
                <p style="color:var(--text-primary)">{{ $transkrip->meeting?->nama_rapat ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Tanggal Generate</p>
                <p style="color:var(--text-primary)">{{ $transkrip->tanggal_generate?->translatedFormat('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Model AI</p>
                <p style="color:var(--text-primary)">{{ $transkrip->openai_model ?? '-' }}</p>
            </div>
        </div>

        {{-- Audio Player --}}
        @php($rek = $transkrip->meeting?->rekamanAudio->first())
        @if($rek && ($rek->extracted_audio_path || $rek->raw_recording_path))
        <hr style="border-color:var(--divider)">
        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider mb-3" style="color:var(--text-muted)">Rekaman Audio</h3>
            <audio controls preload="metadata" style="width:100%;height:40px;border-radius:8px">
                <source src="{{ route('admin.rekaman-audio.play', $rek) }}" type="{{ $rek->mime_type ?: 'audio/mpeg' }}">
            </audio>
        </div>
        @endif

        {{-- Transkrip Content --}}
        <hr style="border-color:var(--divider)">
        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider mb-3" style="color:var(--text-muted)">Hasil Transkrip</h3>
            <div class="text-sm leading-relaxed whitespace-pre-line" style="color:var(--text-secondary);line-height:1.8">
                {{ $transkrip->hasil_transkrip }}
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-4 border-t" style="border-color:var(--divider)">
            <a href="{{ route('admin.transkrips.edit', $transkrip) }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form action="{{ route('admin.transkrips.destroy', $transkrip) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transkrip ini?');" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </form>
            <a href="{{ route('admin.transkrips.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
