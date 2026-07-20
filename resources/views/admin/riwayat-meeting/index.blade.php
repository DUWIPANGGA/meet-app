@extends('admin.layouts.app')
@section('title', 'Arsip Meeting')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-3 mb-1">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            <h1>Arsip Meeting</h1>
        </div>
        <p>Total {{ $meetings->total() }} meeting dengan notulensi atau transkrip</p>
    </div>
</div>

<div class="space-y-6">
    @forelse($meetings as $m)
    @php($trans = $m->transkrip)
    @php($not = $m->notulensi)
    @php($rek = $m->rekamanAudio->first())
    <div class="card overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between gap-4 p-5 border-b" style="border-color:var(--divider);background:var(--surface-bg)">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(124,58,237,0.1)">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold truncate" style="color:var(--text-primary)">{{ $m->nama_rapat }}</p>
                    <p class="text-xs" style="color:var(--text-muted)">
                        {{ \Carbon\Carbon::parse($m->tanggal)->translatedFormat('d M Y') }}
                        @if($m->waktu) &middot; {{ substr($m->waktu, 0, 5) }} @endif
                        <span class="badge" style="background:{{ $m->tipe_rapat === 'Online' ? 'rgba(124,58,237,0.1)' : 'rgba(245,158,11,0.1)' }};color:{{ $m->tipe_rapat === 'Online' ? '#7c3aed' : '#d97706' }};font-size:10px;padding:1px 6px;margin-left:6px">{{ $m->tipe_rapat }}</span>
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
                <a href="{{ route('admin.meetings.show', $m) }}" class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Detail Meeting">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#6366f1"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <button type="button" onclick="confirmDelete({{ $m->id }})" class="p-2 rounded-lg hover:bg-red-500/10 transition" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#ef4444"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                <form id="delete-form-{{ $m->id }}" action="{{ route('admin.riwayat-meeting.destroy', $m) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
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
        @endif

        {{-- Transcript --}}
        @if($trans && $trans->hasil_transkrip)
        <div class="px-5 py-4 border-b" style="border-color:var(--divider)" x-data="{ expanded: false }">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-sm font-medium" style="color:var(--text-secondary)">Hasil Transkrip</span>
                    @if($trans->openai_model)
                    <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(139,92,246,0.1);color:#7c3aed">
                        {{ $trans->openai_model }}
                    </span>
                    @endif
                </div>
                @if(strlen($trans->hasil_transkrip) > 500)
                <button @click="expanded = !expanded" class="text-xs font-medium transition hover:opacity-70" style="color:#7c3aed">
                    <span x-show="!expanded">Lihat Lengkap</span>
                    <span x-show="expanded">Sembunyikan</span>
                </button>
                @endif
            </div>
            <div class="text-sm leading-relaxed whitespace-pre-line" style="color:var(--text-secondary);line-height:1.8"
                 :class="expanded ? '' : 'line-clamp-6'"
                 x-bind:style="expanded ? '' : 'display:-webkit-box;-webkit-line-clamp:6;-webkit-box-orient:vertical;overflow:hidden'">
                {{ $trans->hasil_transkrip }}
            </div>
        </div>
        @endif

        {{-- Notulensi Summary --}}
        @if($not && $not->ringkasan)
        <div class="px-5 py-4" x-data="{ expanded: false }">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-sm font-medium" style="color:var(--text-secondary)">Ringkasan Notulensi</span>
                    @if($not)
                    <a href="{{ route('admin.notulensis.show', $not) }}" class="p-1 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Lihat Notulensi">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#10b981"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    @endif
                    @if($not->openai_model)
                    <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(16,185,129,0.1);color:#10b981">
                        {{ $not->openai_model }}
                    </span>
                    @endif
                </div>
                @if(strlen($not->ringkasan) > 500)
                <button @click="expanded = !expanded" class="text-xs font-medium transition hover:opacity-70" style="color:#10b981">
                    <span x-show="!expanded">Lihat Lengkap</span>
                    <span x-show="expanded">Sembunyikan</span>
                </button>
                @endif
            </div>
            <div class="text-sm leading-relaxed whitespace-pre-line" style="color:var(--text-secondary);line-height:1.8"
                 :class="expanded ? '' : 'line-clamp-4'"
                 x-bind:style="expanded ? '' : 'display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden'">
                {{ $not->ringkasan }}
            </div>
            @if($not->file_pdf)
            <div class="mt-3">
                <a href="{{ route('admin.notulensis.pdf', $not) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-medium transition hover:opacity-70" style="color:#10b981">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Unduh PDF
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
    @empty
    <div class="card">
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width:48px;height:48px"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            <p>Belum ada arsip meeting.</p>
        </div>
    </div>
    @endforelse

    @if($meetings->hasPages())
    <div class="flex justify-center">
        {{ $meetings->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Riwayat Meeting?',
        text: 'Notulensi, transkrip, rekaman, dan meeting akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush
@endsection