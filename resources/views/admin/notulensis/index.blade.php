@extends('admin.layouts.app')
@section('title', 'Riwayat Notulensi')

@section('content')
<div class="page-header">
    <div class="flex items-center gap-3 mb-1">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed;flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h1>Riwayat Notulensi</h1>
    </div>
    <p>Total {{ $notulensis->total() }} notulensi dibuat</p>
</div>

<div class="card overflow-hidden">
    @if($notulensis->count())
    <div class="overflow-x-auto">
        <table>
            <thead>
                <tr>
                    <th>Meeting</th>
                    <th>Tanggal Generate</th>
                    <th>Model AI</th>
                    <th>Ringkasan</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notulensis as $n)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(124,58,237,0.1)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="font-medium" style="color:var(--text-primary)">{{ $n->meeting?->nama_rapat ?? '-' }}</span>
                        </div>
                    </td>
                    <td>{{ $n->tanggal_generate ? \Carbon\Carbon::parse($n->tanggal_generate)->translatedFormat('d M Y') : $n->created_at->translatedFormat('d M Y') }}</td>
                    <td><span class="badge" style="background:rgba(16,185,129,0.1);color:#10b981">{{ $n->openai_model ?? 'Gemini' }}</span></td>
                    <td class="max-w-xs" style="color:var(--text-secondary)"><span class="line-clamp-2">{{ $n->ringkasan }}</span></td>
                    <td>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.notulensis.show', $n) }}" class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#6366f1"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.notulensis.edit', $n) }}" class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#f59e0b"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @if($n->file_pdf || $n->live_audio_id)
                            <a href="{{ route('admin.notulensis.pdf', $n) }}" target="_blank" class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Download PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#22c55e"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </a>
                            @endif
                            <form action="{{ route('admin.notulensis.destroy', $n) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus notulensi ini?');" class="inline">
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
    @if($notulensis->hasPages())
    <div class="px-4 py-3 border-t" style="border-color:var(--divider)">
        {{ $notulensis->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p>Belum ada notulensi.</p>
    </div>
    @endif
</div>
@endsection