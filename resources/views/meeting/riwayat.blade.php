@extends('layouts.app')
@section('title', 'Arsip')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="p-4 sm:p-6 w-full max-w-5xl mx-auto" x-data="arsipShare()">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-medium tracking-tight" style="color:var(--text-primary)">Arsip</h1>
        <p class="mt-1 text-sm" style="color:var(--text-secondary)">
            Semua arsip rapat, notulensi, dan transkrip yang bisa Anda akses.
        </p>
    </div>

    @if (session('success'))
    <div class="surface-card px-4 py-3 rounded-xl mb-6 flex items-center gap-3" style="border:1px solid rgba(34,197,94,0.3);color:#16a34a">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ═══════ SECTION: Arsip Rapat ═══════ --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(124,58,237,0.1)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </div>
            <h2 class="text-lg font-semibold" style="color:var(--text-primary)">Arsip Rapat</h2>
            <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(124,58,237,0.1);color:#7c3aed">{{ $meetings->total() }}</span>
        </div>

        <div class="space-y-4">
            @forelse($meetings as $m)
            @php($trans = $m->transkrip)
            @php($not = $m->notulensi)
            @php($rek = $m->rekamanAudio->first())
            <div class="page-card overflow-hidden">
                {{-- Header --}}
                <div class="flex items-center justify-between gap-4 p-5 border-b" style="border-color:var(--divider);background:var(--surface-bg)">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                            style="background:rgba(124,58,237,0.1)">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                style="color:#7c3aed">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold truncate" style="color:var(--text-primary)">{{ $m->nama_rapat }}</p>
                            <p class="text-xs" style="color:var(--text-muted)">
                                {{ \Carbon\Carbon::parse($m->tanggal)->translatedFormat('d M Y') }}
                                @if($m->waktu) &middot; {{ substr($m->waktu, 0, 5) }} @endif
                                <span class="inline-block text-[10px] px-1.5 py-0.5 rounded-full ml-1.5"
                                    style="background:{{ $m->tipe_rapat === 'Online' ? 'rgba(124,58,237,0.1)' : 'rgba(245,158,11,0.1)' }};color:{{ $m->tipe_rapat === 'Online' ? '#7c3aed' : '#d97706' }}">
                                    {{ $m->tipe_rapat }}
                                </span>
                                @if($m->creator)
                                <span class="ml-1.5">&middot; Oleh {{ $m->creator->name }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @php($isOwner = $m->dibuat_oleh === auth()->id())
                    <div class="flex items-center gap-1.5 shrink-0 flex-wrap justify-end">
                        @if($not)
                        <a href="{{ route('meeting.notulensi.show', $m) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg transition hover:opacity-80"
                            style="background:rgba(16,185,129,0.1);color:#10b981">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Notulensi
                        </a>
                        @endif
                        @if($not && $not->file_pdf)
                        <a href="{{ route('meeting.notulensi.pdf', $m) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg transition hover:opacity-80"
                            style="background:rgba(59,130,246,0.1);color:#3b82f6">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </a>
                        @endif
                        @if($not && $isOwner)
                        <button @click="openShareModal({{ $m->id }}, {{ $not->id }}, '{{ addslashes($m->nama_rapat) }}', '{{ $not->akses_notulensi }}', {{ Js::from($not->accessUsers->pluck('id')) }})"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg transition hover:opacity-80"
                            style="background:rgba(251,191,36,0.1);color:#d97706">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            Share
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Audio Player --}}
                @if($rek && ($rek->extracted_audio_path || $rek->raw_recording_path))
                <div class="px-5 py-4 border-b" style="border-color:var(--divider)">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            style="color:var(--text-muted)">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                        </svg>
                        <span class="text-sm font-medium" style="color:var(--text-secondary)">Rekaman Audio</span>
                        @if($rek->durasi)
                        <span class="text-xs" style="color:var(--text-muted)">{{ $rek->durasi }}</span>
                        @endif
                    </div>
                    <audio controls preload="metadata" style="width:100%;height:40px;border-radius:8px">
                        <source
                            src="{{ route('admin.rekaman-audio.play', $rek) }}"
                            type="{{ $rek->mime_type ?: 'audio/mpeg' }}">
                    </audio>
                </div>
                @endif

                {{-- Transcript --}}
                @if($trans && $trans->hasil_transkrip)
                <div class="px-5 py-4 border-b" style="border-color:var(--divider)" x-data="{ expanded: false }">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" style="color:var(--text-muted)">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-medium" style="color:var(--text-secondary)">Hasil Transkrip</span>
                            @if($trans->openai_model)
                            <span class="text-xs px-2 py-0.5 rounded-full"
                                style="background:rgba(139,92,246,0.1);color:#7c3aed">
                                {{ $trans->openai_model }}
                            </span>
                            @endif
                        </div>
                        @if(strlen($trans->hasil_transkrip) > 500)
                        <button @click="expanded = !expanded"
                            class="text-xs font-medium transition hover:opacity-70" style="color:#7c3aed">
                            <span x-show="!expanded">Lihat Lengkap</span>
                            <span x-show="expanded">Sembunyikan</span>
                        </button>
                        @endif
                    </div>
                    <div class="text-sm leading-relaxed whitespace-pre-line"
                        style="color:var(--text-secondary);line-height:1.8"
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
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" style="color:var(--text-muted)">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-medium" style="color:var(--text-secondary)">Ringkasan Notulensi</span>
                            @if($not->openai_model)
                            <span class="text-xs px-2 py-0.5 rounded-full"
                                style="background:rgba(16,185,129,0.1);color:#10b981">
                                {{ $not->openai_model }}
                            </span>
                            @endif
                        </div>
                        @if(strlen($not->ringkasan) > 500)
                        <button @click="expanded = !expanded"
                            class="text-xs font-medium transition hover:opacity-70" style="color:#10b981">
                            <span x-show="!expanded">Lihat Lengkap</span>
                            <span x-show="expanded">Sembunyikan</span>
                        </button>
                        @endif
                    </div>
                    <div class="text-sm leading-relaxed whitespace-pre-line"
                        style="color:var(--text-secondary);line-height:1.8"
                        :class="expanded ? '' : 'line-clamp-4'"
                        x-bind:style="expanded ? '' : 'display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden'">
                        {{ $not->ringkasan }}
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="page-card">
                <div class="text-center py-12 px-6">
                    <svg class="mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                        style="width:40px;height:40px;color:var(--text-muted)">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm" style="color:var(--text-muted)">Belum ada arsip rapat.</p>
                </div>
            </div>
            @endforelse

            @if($meetings->hasPages())
            <div class="flex justify-center">
                {{ $meetings->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- ═══════ SECTION: Arsip Audio Notulensi ═══════ --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(6,182,212,0.1)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#06b6d4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                </svg>
            </div>
            <h2 class="text-lg font-semibold" style="color:var(--text-primary)">Arsip Audio Notulensi</h2>
            <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(6,182,212,0.1);color:#06b6d4">{{ $liveAudios->total() }}</span>
        </div>

        <div class="space-y-4">
            @forelse($liveAudios as $audio)
            <div class="page-card overflow-hidden">
                {{-- Header --}}
                <div class="flex items-center justify-between gap-4 p-5 border-b" style="border-color:var(--divider);background:var(--surface-bg)">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                            style="background:rgba(6,182,212,0.1)">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                style="color:#06b6d4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold truncate" style="color:var(--text-primary)">
                                Audio Notulensi #{{ $audio->id }}
                            </p>
                            <p class="text-xs" style="color:var(--text-muted)">
                                {{ $audio->tanggal_rekam ? $audio->tanggal_rekam->translatedFormat('d M Y, H:i') : '-' }}
                                @if($audio->durasi) &middot; {{ $audio->durasi }} @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        @if($audio->notulensi)
                        <a href="{{ route('audio.show', $audio) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg transition hover:opacity-80"
                            style="background:rgba(16,185,129,0.1);color:#10b981">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Lihat
                        </a>
                        @endif
                        @if($audio->notulensi && $audio->notulensi->file_pdf)
                        <a href="{{ route('audio.pdf', $audio) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg transition hover:opacity-80"
                            style="background:rgba(59,130,246,0.1);color:#3b82f6">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Audio Player --}}
                @if($audio->file_path)
                <div class="px-5 py-4 border-b" style="border-color:var(--divider)">
                    <audio controls preload="metadata" style="width:100%;height:40px;border-radius:8px">
                        <source src="{{ asset('storage/' . $audio->file_path) }}" type="{{ $audio->mime_type ?: 'audio/mpeg' }}">
                    </audio>
                </div>
                @endif

                {{-- Transcript --}}
                @if($audio->transcript)
                <div class="px-5 py-4 border-b" style="border-color:var(--divider)" x-data="{ expanded: false }">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" style="color:var(--text-muted)">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-medium" style="color:var(--text-secondary)">Transkrip</span>
                        </div>
                        @if(strlen($audio->transcript) > 500)
                        <button @click="expanded = !expanded"
                            class="text-xs font-medium transition hover:opacity-70" style="color:#7c3aed">
                            <span x-show="!expanded">Lihat Lengkap</span>
                            <span x-show="expanded">Sembunyikan</span>
                        </button>
                        @endif
                    </div>
                    <div class="text-sm leading-relaxed whitespace-pre-line"
                        style="color:var(--text-secondary);line-height:1.8"
                        :class="expanded ? '' : 'line-clamp-6'"
                        x-bind:style="expanded ? '' : 'display:-webkit-box;-webkit-line-clamp:6;-webkit-box-orient:vertical;overflow:hidden'">
                        {{ $audio->transcript }}
                    </div>
                </div>
                @endif

                {{-- Notulensi --}}
                @if($audio->notulensi_teks)
                <div class="px-5 py-4" x-data="{ expanded: false }">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" style="color:var(--text-muted)">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-medium" style="color:var(--text-secondary)">Notulensi</span>
                        </div>
                        @if(strlen($audio->notulensi_teks) > 500)
                        <button @click="expanded = !expanded"
                            class="text-xs font-medium transition hover:opacity-70" style="color:#10b981">
                            <span x-show="!expanded">Lihat Lengkap</span>
                            <span x-show="expanded">Sembunyikan</span>
                        </button>
                        @endif
                    </div>
                    <div class="text-sm leading-relaxed whitespace-pre-line"
                        style="color:var(--text-secondary);line-height:1.8"
                        :class="expanded ? '' : 'line-clamp-4'"
                        x-bind:style="expanded ? '' : 'display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden'">
                        {{ $audio->notulensi_teks }}
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="page-card">
                <div class="text-center py-12 px-6">
                    <svg class="mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                        style="width:40px;height:40px;color:var(--text-muted)">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                    </svg>
                    <p class="text-sm" style="color:var(--text-muted)">Belum ada arsip audio notulensi.</p>
                </div>
            </div>
            @endforelse

            @if($liveAudios->hasPages())
            <div class="flex justify-center">
                {{ $liveAudios->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- ═══════ SECTION: Notulensi Audio yang Dibagikan ═══════ --}}
    @if($sharedNotulensis->count())
    <div class="mt-6">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(124,58,237,0.1)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold" style="color:var(--text-primary)">Notulensi Audio yang Dibagikan</h2>
            <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(124,58,237,0.1);color:#7c3aed">{{ $sharedNotulensis->count() }}</span>
        </div>

        <div class="space-y-4">
            @foreach($sharedNotulensis as $not)
            @php($audio = $not->liveAudio)
            <div class="page-card overflow-hidden">
                <div class="flex items-center justify-between gap-4 p-5 border-b" style="border-color:var(--divider);background:var(--surface-bg)">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(124,58,237,0.1)">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold truncate" style="color:var(--text-primary)">
                                Audio Notulensi #{{ $audio->id ?? $not->id }}
                            </p>
                            <p class="text-xs" style="color:var(--text-muted)">
                                Dibagikan kepada Anda
                                @if($not->created_at) &middot; {{ $not->created_at->translatedFormat('d M Y') }} @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        @if($audio)
                        <a href="{{ route('audio.show', $audio) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg transition hover:opacity-80"
                            style="background:rgba(16,185,129,0.1);color:#10b981">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Lihat
                        </a>
                        @endif
                        @if($not->file_pdf)
                        <a href="{{ $audio ? route('audio.pdf', $audio) : '#' }}" target="_blank"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg transition hover:opacity-80"
                            style="background:rgba(59,130,246,0.1);color:#3b82f6">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            PDF
                        </a>
                        @endif
                    </div>
                </div>
                @if($not->ringkasan)
                <div class="px-5 py-4" x-data="{ expanded: false }">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm font-medium" style="color:var(--text-secondary)">Ringkasan</span>
                        </div>
                        @if(strlen($not->ringkasan) > 500)
                        <button @click="expanded = !expanded" class="text-xs font-medium transition hover:opacity-70" style="color:#7c3aed">
                            <span x-show="!expanded">Lihat Lengkap</span>
                            <span x-show="expanded">Sembunyikan</span>
                        </button>
                        @endif
                    </div>
                    <div class="text-sm leading-relaxed whitespace-pre-line" style="color:var(--text-secondary);line-height:1.8"
                        :class="expanded ? '' : 'line-clamp-6'"
                        x-bind:style="expanded ? '' : 'display:-webkit-box;-webkit-line-clamp:6;-webkit-box-orient:vertical;overflow:hidden'">
                        {{ $not->ringkasan }}
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Share Modal -->
    <div x-show="showShareModal" style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
        <div @click.away="showShareModal = false" x-show="showShareModal"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="page-card w-full max-w-md overflow-hidden">
            <div class="px-6 py-5 border-b flex items-center justify-between" style="border-color:var(--divider)">
                <div>
                    <h3 class="text-lg font-semibold" style="color:var(--text-primary)">Bagikan Hasil Notulensi</h3>
                    <p class="text-xs mt-0.5" style="color:var(--text-muted)" x-text="shareMeetingName"></p>
                </div>
                <button @click="showShareModal = false" class="p-1.5 hover:bg-white/10 rounded-full transition" style="color:var(--text-muted)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-5">
                <!-- Akses Notulensi -->
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:var(--text-secondary)">Siapa yang bisa melihat?</label>
                    <div class="space-y-2">
                        <label class="cursor-pointer flex items-center gap-3 p-3 rounded-xl border-2 transition"
                            :style="shareAkses === 'participants' ? 'border-violet-500;background:rgba(124,58,237,0.08)' : 'border-color:var(--card-border);background:var(--surface-bg)'">
                            <input type="radio" name="share_akses" value="participants" x-model="shareAkses" class="peer sr-only">
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                                :style="shareAkses === 'participants' ? 'border-violet-500' : 'border-color:var(--text-muted)'">
                                <div x-show="shareAkses === 'participants'" class="w-2.5 h-2.5 rounded-full bg-violet-500"></div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold" style="color:var(--text-primary)">Peserta Rapat</div>
                                <div class="text-xs" style="color:var(--text-muted)">Hanya user yang join meet ini</div>
                            </div>
                        </label>
                        <label class="cursor-pointer flex items-center gap-3 p-3 rounded-xl border-2 transition"
                            :style="shareAkses === 'all_users' ? 'border-violet-500;background:rgba(124,58,237,0.08)' : 'border-color:var(--card-border);background:var(--surface-bg)'">
                            <input type="radio" name="share_akses" value="all_users" x-model="shareAkses" class="peer sr-only">
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                                :style="shareAkses === 'all_users' ? 'border-violet-500' : 'border-color:var(--text-muted)'">
                                <div x-show="shareAkses === 'all_users'" class="w-2.5 h-2.5 rounded-full bg-violet-500"></div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold" style="color:var(--text-primary)">Semua User</div>
                                <div class="text-xs" style="color:var(--text-muted)">Semua user di sistem bisa melihat</div>
                            </div>
                        </label>
                        <label class="cursor-pointer flex items-center gap-3 p-3 rounded-xl border-2 transition"
                            :style="shareAkses === 'pilih_user' ? 'border-violet-500;background:rgba(124,58,237,0.08)' : 'border-color:var(--card-border);background:var(--surface-bg)'">
                            <input type="radio" name="share_akses" value="pilih_user" x-model="shareAkses" class="peer sr-only">
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                                :style="shareAkses === 'pilih_user' ? 'border-violet-500' : 'border-color:var(--text-muted)'">
                                <div x-show="shareAkses === 'pilih_user'" class="w-2.5 h-2.5 rounded-full bg-violet-500"></div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold" style="color:var(--text-primary)">Pilih User</div>
                                <div class="text-xs" style="color:var(--text-muted)">Pilih user tertentu yang bisa melihat</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- User Picker -->
                <div x-show="shareAkses === 'pilih_user'" x-cloak>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Pilih Pengguna</label>
                    <div class="relative" @click.outside="showShareUserSearch = false">
                        <input type="text" placeholder="Ketik nama untuk mencari..."
                            x-model="shareUserSearch"
                            @input.debounce.300ms="if(shareUserSearch.length >= 2) { fetch('/api/users?search=' + shareUserSearch).then(r => r.json()).then(d => { shareSearchResults = d; showShareUserSearch = true }) } else { shareSearchResults = []; showShareUserSearch = false }"
                            @focus="if(shareUserSearch.length >= 2 && shareSearchResults.length > 0) showShareUserSearch = true"
                            class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                        <div x-show="showShareUserSearch && shareSearchResults.length > 0"
                            class="absolute z-20 mt-1 w-full bg-gray-800 border border-gray-700 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                            <template x-for="user in shareSearchResults" :key="user.id">
                                <div @mousedown.prevent="if(!shareSelectedUsers.find(u => u.id === user.id)) { shareSelectedUsers.push(user); shareSearchResults = shareSearchResults.filter(u => u.id !== user.id); shareUserSearch = ''; showShareUserSearch = false }"
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
                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(user, idx) in shareSelectedUsers" :key="user.id">
                            <div class="flex items-center gap-1.5 bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs px-2.5 py-1 rounded-full">
                                <span x-text="user.name"></span>
                                <button type="button" @click="shareSelectedUsers.splice(idx, 1)" class="hover:text-white transition">&times;</button>
                            </div>
                        </template>
                    </div>
                    <div x-show="shareSelectedUsers.length === 0" class="text-xs mt-2" style="color:var(--text-muted)">
                        Belum ada pengguna yang dipilih.
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex gap-3 pt-2">
                    <button @click="showShareModal = false"
                        class="flex-1 px-4 py-2.5 rounded-xl font-medium transition text-sm"
                        style="border:1px solid var(--card-border);color:var(--text-secondary);background:var(--surface-bg)">
                        Batal
                    </button>
                    <button @click="saveShareAccess()"
                        class="flex-1 px-4 py-2.5 rounded-xl text-white font-semibold transition text-sm shadow-lg shadow-violet-500/20"
                        style="background:linear-gradient(135deg, #7c3aed, #4f46e5)"
                        :disabled="shareSaving">
                        <span x-show="!shareSaving">Simpan</span>
                        <span x-show="shareSaving">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function arsipShare() {
    return {
        showShareModal: false,
        shareMeetingId: null,
        shareNotulensiId: null,
        shareMeetingName: '',
        shareAkses: 'participants',
        shareUserSearch: '',
        shareSearchResults: [],
        shareSelectedUsers: [],
        showShareUserSearch: false,
        shareSaving: false,

        openShareModal(meetingId, notulensiId, meetingName, akses, userIds) {
            this.shareMeetingId = meetingId;
            this.shareNotulensiId = notulensiId;
            this.shareMeetingName = meetingName;
            this.shareAkses = akses || 'participants';
            this.shareSelectedUsers = [];
            this.shareUserSearch = '';
            this.shareSearchResults = [];
            this.showShareModal = true;

            if (userIds && userIds.length > 0) {
                fetch('/api/users?search=')
                    .then(r => r.json())
                    .then(all => {
                        this.shareSelectedUsers = all.filter(u => userIds.includes(u.id));
                    });
            }
        },

        saveShareAccess() {
            this.shareSaving = true;
            fetch(`/meeting/${this.shareMeetingId}/notulensi-access`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    akses_notulensi: this.shareAkses,
                    akses_user_ids: this.shareSelectedUsers.map(u => u.id),
                }),
            })
            .then(r => r.json())
            .then(data => {
                this.shareSaving = false;
                this.showShareModal = false;
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Akses notulensi berhasil diperbarui.',
                    timer: 1500,
                    showConfirmButton: false,
                });
            })
            .catch(err => {
                this.shareSaving = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan.',
                });
            });
        },
    };
}
</script>
@endsection
