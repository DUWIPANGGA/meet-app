@extends('admin.layouts.app')
@section('title', 'Meetings')

@section('content')
<div>
    <div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1>Meetings</h1>
            <p>Total {{ $meetings->total() }} meeting terselenggara</p>
        </div>
        <a href="{{ route('admin.meetings.create') }}" class="btn-primary shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Rapat Baru
        </a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>Pembuat</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $m)
                    <tr>
                        <td class="font-medium" style="color:var(--text-primary)">{{ $m->nama_rapat }}</td>
                        <td><span class="badge" style="background:{{ $m->tipe_rapat === 'Online' ? 'rgba(124,58,237,0.1)' : 'rgba(245,158,11,0.1)' }};color:{{ $m->tipe_rapat === 'Online' ? '#7c3aed' : '#d97706' }}">{{ $m->tipe_rapat }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($m->tanggal)->translatedFormat('d M Y') }} {{ $m->waktu ? substr($m->waktu, 0, 5) : '' }}</td>
                        <td>{{ $m->creator?->name ?? '-' }}</td>
                        <td><span class="badge" style="background:{{ $m->status_rapat === 'Berlangsung' ? 'rgba(16,185,129,0.1)' : 'rgba(99,102,241,0.1)' }};color:{{ $m->status_rapat === 'Berlangsung' ? '#10b981' : '#6366f1' }}">{{ $m->status_rapat }}</span></td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.meetings.show', $m) }}" class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#6366f1"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.meetings.edit', $m) }}" class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#f59e0b"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.meetings.destroy', $m) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rapat ini?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#ef4444"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <p>Belum ada meeting.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($meetings->hasPages())
        <div class="px-4 py-3 border-t" style="border-color:var(--divider)">
            {{ $meetings->links() }}
        </div>
        @endif
    </div>

</div>
@endsection