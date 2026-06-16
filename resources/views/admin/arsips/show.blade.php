@extends('admin.layouts.app')
@section('title', 'Detail Arsip')

@section('content')
<div class="max-w-3xl">
    <div class="page-header">
        <a href="{{ route('admin.arsips.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <h1>Detail Arsip</h1>
        <p>{{ $arsip->meeting?->nama_rapat ?? '-' }}</p>
    </div>

    <div class="card p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Meeting</p>
                <p style="color:var(--text-primary)">{{ $arsip->meeting?->nama_rapat ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Tanggal Meeting</p>
                <p style="color:var(--text-primary)">{{ $arsip->meeting?->tanggal ? \Carbon\Carbon::parse($arsip->meeting->tanggal)->translatedFormat('d M Y') : '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Tanggal Arsip</p>
                <p style="color:var(--text-primary)">{{ $arsip->tanggal_arsip ? \Carbon\Carbon::parse($arsip->tanggal_arsip)->translatedFormat('d M Y') : '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:var(--text-muted)">Notulensi</p>
                <p style="color:var(--text-primary)">{{ $arsip->notulensi ? 'Notulensi #'.$arsip->notulensi->id : '-' }}</p>
            </div>
        </div>

        @if($arsip->notulensi && $arsip->notulensi->file_pdf)
        <hr class="my-6" style="border-color:var(--divider)">
        <div>
            <a href="{{ asset('storage/'.$arsip->notulensi->file_pdf) }}" target="_blank" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Unduh PDF Notulensi
            </a>
        </div>
        @endif

        <div class="flex items-center gap-3 mt-8 pt-6 border-t" style="border-color:var(--divider)">
            <a href="{{ route('admin.arsips.edit', $arsip) }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form action="{{ route('admin.arsips.destroy', $arsip) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus arsip ini?');" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </form>
            <a href="{{ route('admin.arsips.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection