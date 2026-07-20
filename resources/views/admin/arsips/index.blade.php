@extends('admin.layouts.app')
@section('title', 'Arsip')

@section('content')
<div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-3 mb-1">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <h1>Arsip</h1>
        </div>
        <p>Dokumen arsip notulensi meeting yang telah selesai</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.transkrips.index') }}" class="btn-secondary shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Riwayat Transkrip
        </a>
        <a href="{{ route('admin.arsips.create') }}" class="btn-primary shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Arsip
        </a>
    </div>
</div>

<div class="card overflow-hidden">
    @if($arsips->count())
    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th>Meeting</th>
                    <th>Notulensi</th>
                    <th>Tanggal Arsip</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arsips as $arsip)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(124,58,237,0.1)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </div>
                            <span class="font-medium" style="color:var(--text-primary)">{{ $arsip->meeting?->nama_rapat ?? '-' }}</span>
                        </div>
                    </td>
                    <td>{{ $arsip->notulensi?->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                    <td style="color:var(--text-primary)">{{ $arsip->tanggal_arsip ? \Carbon\Carbon::parse($arsip->tanggal_arsip)->translatedFormat('d M Y') : '-' }}</td>
                    <td>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.arsips.show', $arsip) }}" class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#6366f1"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.arsips.edit', $arsip) }}" class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Edit" style="display:none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#f59e0b"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.arsips.destroy', $arsip) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus arsip ini?');" class="inline" style="display:none">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#ef4444"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($arsips->hasPages())
    <div class="px-4 py-3 border-t" style="border-color:var(--divider)">
        {{ $arsips->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
        <p>Belum ada arsip.</p>
    </div>
    @endif
</div>
@endsection