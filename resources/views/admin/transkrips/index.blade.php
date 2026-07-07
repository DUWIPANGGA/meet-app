@extends('admin.layouts.app')
@section('title', 'Riwayat Transkrip')

@section('content')
<div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1>Riwayat Transkrip</h1>
        <p>Total {{ $transkrips->total() }} transkrip tersimpan</p>
    </div>
    <a href="{{ route('admin.transkrips.create') }}" class="btn-primary shrink-0" style="display:none">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Transkrip
    </a>
</div>

<div class="space-y-6">
    @forelse($transkrips as $t)
    @php($m = $t->meeting)
    @php($rek = $m?->rekamanAudio->first())
    @php($not = $m?->notulensi)
    @php($arsip = $m?->arsip)
    <div class="card overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between gap-4 p-5 border-b" style="border-color:var(--divider);background:var(--surface-bg)">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(139,92,246,0.1)">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold truncate" style="color:var(--text-primary)">{{ $m?->nama_rapat ?? 'Rapat #'.$t->meeting_id }}</p>
                    <p class="text-xs" style="color:var(--text-muted)">
                        {{ $t->tanggal_generate ? \Carbon\Carbon::parse($t->tanggal_generate)->translatedFormat('d M Y') : $t->created_at->translatedFormat('d M Y H:i') }}
                        @if($t->openai_model) &middot; {{ $t->openai_model }} @endif
                        @if($m) &middot; {{ $m->tipe_rapat }} @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @if($not)
                <a href="{{ route('admin.notulensis.show', $not) }}" class="btn-secondary text-xs px-3 py-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Notulensi
                </a>
                @endif
                @if($arsip)
                <a href="{{ route('admin.arsips.show', $arsip) }}" class="btn-secondary text-xs px-3 py-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    Arsip
                </a>
                @endif
                <a href="{{ route('admin.transkrips.edit', $t) }}" class="p-2 rounded-lg hover:bg-yellow-500/10 transition" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#f59e0b"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('admin.transkrips.destroy', $t) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transkrip ini?');" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 transition" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#ef4444"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Audio Player --}}
        @if($rek && ($rek->extracted_audio_path || $rek->raw_recording_path))
        <div class="px-5 py-4 border-b" style="border-color:var(--divider)">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                <span class="text-sm font-medium" style="color:var(--text-secondary)">Rekaman Audio</span>
                @if($rek->durasi)<span class="text-xs" style="color:var(--text-muted)">{{ $rek->durasi }}</span>@endif
            </div>
            <audio controls preload="metadata" style="width:100%;height:40px;border-radius:8px">
                <source src="{{ route('admin.rekaman-audio.play', $rek) }}" type="{{ $rek->mime_type ?: 'audio/mpeg' }}">
            </audio>
        </div>
        @elseif($rek)
        <div class="px-5 py-4 border-b" style="border-color:var(--divider)">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="text-sm" style="color:var(--text-muted)">File audio tidak tersedia untuk diputar (hanya metadata)</span>
            </div>
        </div>
        @else
        <div class="px-5 py-4 border-b" style="border-color:var(--divider)">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                <span class="text-sm" style="color:var(--text-muted)">Tidak ada rekaman audio</span>
            </div>
        </div>
        @endif

        {{-- Transcript Content --}}
        <div class="p-5" x-data="{ expanded: false }">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-sm font-medium" style="color:var(--text-secondary)">Hasil Transkrip</span>
                    <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(139,92,246,0.1);color:#7c3aed">
                        {{ str_word_count($t->hasil_transkrip) }} kata
                    </span>
                </div>
                @if(strlen($t->hasil_transkrip) > 500)
                <button @click="expanded = !expanded" class="text-xs font-medium transition hover:opacity-70" style="color:#7c3aed">
                    <span x-show="!expanded">Lihat Lengkap</span>
                    <span x-show="expanded">Sembunyikan</span>
                </button>
                @endif
            </div>
            <div class="text-sm leading-relaxed whitespace-pre-line" style="color:var(--text-secondary);line-height:1.8"
                 :class="expanded ? '' : 'line-clamp-6'"
                 x-bind:style="expanded ? '' : 'display:-webkit-box;-webkit-line-clamp:6;-webkit-box-orient:vertical;overflow:hidden'">
                {{ $t->hasil_transkrip }}
            </div>
        </div>
    </div>
    @empty
    <div class="card">
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width:48px;height:48px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p>Belum ada transkrip.</p>
        </div>
    </div>
    @endforelse

    @if($transkrips->hasPages())
    <div class="flex justify-center">
        {{ $transkrips->links() }}
    </div>
    @endif
</div>
@endsection
