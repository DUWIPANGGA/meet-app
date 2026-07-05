@extends('admin.layouts.app')
@section('title', 'Edit Notulensi')

@section('content')
@php
$s = $notulensi->structured_summary ?? [];

$topikDibahas = old('structured_summary.topik_dibahas', $s['topik_dibahas'] ?? ['']);
$keputusan = old('structured_summary.keputusan', $s['keputusan'] ?? ['']);
$actionItems = old('structured_summary.action_items', $s['action_items'] ?? [['task' => '', 'pic' => '', 'deadline' => '']]);
$risikoCatatan = old('structured_summary.risiko_catatan', $s['risiko_catatan'] ?? ['']);
@endphp
<div class="page-header">
    <a href="{{ route('admin.notulensis.show', $notulensi) }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
        Kembali
    </a>
    <h1>Edit Notulensi</h1>
    <p style="color:var(--text-muted)">{{ $notulensi->meeting?->nama_rapat ?? '-' }} &middot; {{ $notulensi->tanggal_generate?->translatedFormat('d M Y') ?? '-' }}</p>
</div>

<form action="{{ route('admin.notulensis.update', $notulensi) }}" method="POST">
    @csrf @method('PUT')

    <div class="card p-6 space-y-8">
        {{-- Nama Meeting --}}
        @if ($notulensi->meeting && blank($notulensi->meeting->nama_rapat))
        <div>
            <label for="nama_rapat" class="block text-sm font-semibold mb-2" style="color:var(--text-primary)">Nama Rapat</label>
            <input type="text" name="nama_rapat" id="nama_rapat" value="{{ old('nama_rapat', $notulensi->meeting?->nama_rapat ?? '') }}" placeholder="Masukkan nama rapat..." class="w-full rounded-xl px-4 py-3 text-sm border transition focus:outline-none focus:ring-2 focus:ring-violet-500/40" style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-primary)">
            @error('nama_rapat')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <hr style="border-color:var(--divider)">
        @endif

        {{-- Ringkasan --}}
        <div>
            <label for="ringkasan" class="block text-sm font-semibold mb-2" style="color:var(--text-primary)">Ringkasan</label>
            <textarea name="ringkasan" id="ringkasan" rows="8" class="w-full rounded-xl px-4 py-3 text-sm border transition focus:outline-none focus:ring-2 focus:ring-violet-500/40" style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-primary);resize:vertical">{{ old('ringkasan', $notulensi->ringkasan) }}</textarea>
            @error('ringkasan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <hr style="border-color:var(--divider)">

        {{-- Topik Dibahas --}}
        <div x-data='@json(["items" => $topikDibahas])'>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Topik Dibahas</h3>
                <button type="button" @click="items.push('')" class="text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1" style="color:var(--accent);background:rgba(124,58,237,0.1)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
            </div>
            <div class="space-y-2">
                <template x-for="(item, i) in items" :key="i">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0" style="color:#10b981">•</span>
                        <input type="text" x-model="items[i]" name="structured_summary[topik_dibahas][]" class="flex-1 rounded-xl px-4 py-2.5 text-sm border transition focus:outline-none focus:ring-2 focus:ring-violet-500/40" style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-primary)">
                        <button type="button" @click="items.splice(i, 1)" class="shrink-0 p-2 rounded-lg hover:bg-red-500/10 transition" style="color:#ef4444">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <hr style="border-color:var(--divider)">

        {{-- Keputusan Penting --}}
        <div x-data='@json(["items" => $keputusan])'>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Keputusan Penting</h3>
                <button type="button" @click="items.push('')" class="text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1" style="color:var(--accent);background:rgba(124,58,237,0.1)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
            </div>
            <div class="space-y-2">
                <template x-for="(item, i) in items" :key="i">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0" style="color:#f59e0b">•</span>
                        <input type="text" x-model="items[i]" name="structured_summary[keputusan][]" class="flex-1 rounded-xl px-4 py-2.5 text-sm border transition focus:outline-none focus:ring-2 focus:ring-violet-500/40" style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-primary)">
                        <button type="button" @click="items.splice(i, 1)" class="shrink-0 p-2 rounded-lg hover:bg-red-500/10 transition" style="color:#ef4444">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <hr style="border-color:var(--divider)">

        {{-- Action Items --}}
        <div x-data='@json(["items" => $actionItems])'>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Action Items</h3>
                <button type="button" @click="items.push({task:'',pic:'',deadline:''})" class="text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1" style="color:var(--accent);background:rgba(124,58,237,0.1)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
            </div>
            <div class="overflow-x-auto rounded-xl border" style="border-color:var(--card-border)">
                <table>
                    <thead>
                        <tr>
                            <th>Tugas</th>
                            <th>PIC</th>
                            <th>Deadline</th>
                            <th class="w-12"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, i) in items" :key="i">
                            <tr>
                                <td><input type="text" x-model="row.task" :name="'structured_summary[action_items]['+i+'][task]'" class="w-full bg-transparent border-0 px-2 py-2 text-sm focus:outline-none" style="color:var(--text-primary)" placeholder="Tugas"></td>
                                <td><input type="text" x-model="row.pic" :name="'structured_summary[action_items]['+i+'][pic]'" class="w-full bg-transparent border-0 px-2 py-2 text-sm focus:outline-none" style="color:var(--text-primary)" placeholder="PIC"></td>
                                <td><input type="text" x-model="row.deadline" :name="'structured_summary[action_items]['+i+'][deadline]'" class="w-full bg-transparent border-0 px-2 py-2 text-sm focus:outline-none" style="color:var(--text-primary)" placeholder="Deadline"></td>
                                <td>
                                    <button type="button" @click="items.splice(i, 1)" class="p-1.5 rounded-lg hover:bg-red-500/10 transition" style="color:#ef4444">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <hr style="border-color:var(--divider)">

        {{-- Risiko / Catatan --}}
        <div x-data='@json(["items" => $risikoCatatan])'>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold uppercase tracking-wider" style="color:var(--text-muted)">Risiko / Catatan</h3>
                <button type="button" @click="items.push('')" class="text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1" style="color:var(--accent);background:rgba(124,58,237,0.1)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah
                </button>
            </div>
            <div class="space-y-2">
                <template x-for="(item, i) in items" :key="i">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0" style="color:#ef4444">•</span>
                        <input type="text" x-model="items[i]" name="structured_summary[risiko_catatan][]" class="flex-1 rounded-xl px-4 py-2.5 text-sm border transition focus:outline-none focus:ring-2 focus:ring-violet-500/40" style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-primary)">
                        <button type="button" @click="items.splice(i, 1)" class="shrink-0 p-2 rounded-lg hover:bg-red-500/10 transition" style="color:#ef4444">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t" style="border-color:var(--divider)">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.notulensis.show', $notulensi) }}" class="btn-secondary">Batal</a>
        </div>
    </div>
</form>
@endsection