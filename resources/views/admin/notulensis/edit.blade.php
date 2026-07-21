@extends('admin.layouts.app')
@section('title', 'Edit Notulensi')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
@php
$s = $notulensi->structured_summary ?? [];

$topikDibahas = old('structured_summary.topik_dibahas', $s['topik_dibahas'] ?? ['']);
$keputusan = old('structured_summary.keputusan', $s['keputusan'] ?? ['']);
$actionItems = old('structured_summary.action_items', $s['action_items'] ?? [['task' => '', 'pic' => '', 'deadline' => '']]);
$risikoCatatan = old('structured_summary.risiko_catatan', $s['risiko_catatan'] ?? ['']);
@endphp
<div x-data="adminNotulensiShare()">
    <div class="page-header">
        <a href="{{ route('admin.notulensis.show', $notulensi) }}" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition hover:opacity-70" style="color:var(--text-muted)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali
        </a>
        <div class="flex items-center gap-3 mb-1">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h1>Edit Notulensi</h1>
        </div>
        <p style="color:var(--text-muted)">{{ $notulensi->meeting?->nama_rapat ?? '-' }} &middot; {{ $notulensi->tanggal_generate?->translatedFormat('d M Y') ?? '-' }}</p>
    </div>

    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(251,191,36,0.1)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#d97706"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-medium" style="color:var(--text-muted)">Akses Notulensi</p>
                    <p class="text-sm font-semibold" style="color:var(--text-primary)">
                        @if($notulensi->akses_notulensi === 'all_users')
                            Semua User
                        @elseif($notulensi->akses_notulensi === 'pilih_user')
                            Pilih User ({{ $notulensi->accessUsers->count() }} user)
                        @else
                            Peserta Rapat ({{ $notulensi->meeting?->participants?->count() ?? 0 }} orang)
                        @endif
                    </p>
                </div>
            </div>
            <button type="button" @click="openShareModal({{ $notulensi->meeting_id ?? 'null' }}, {{ $notulensi->id }}, '{{ addslashes($notulensi->meeting?->nama_rapat ?? '') }}', '{{ $notulensi->akses_notulensi }}', @json($notulensi->accessUsers->pluck('id')))"
                class="btn-secondary text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                Ubah Akses
            </button>
        </div>
        @if($notulensi->akses_notulensi === 'pilih_user' && $notulensi->accessUsers->count())        <div class="pt-3 border-t" style="border-color:var(--card-border)">
            <p class="text-xs font-medium mb-2" style="color:var(--text-muted)">User yang diizinkan:</p>
            <div class="flex flex-wrap gap-1.5">
                @foreach($notulensi->accessUsers as $user)
                <div class="flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full" style="background:rgba(124,58,237,0.1);color:#7c3aed">
                    <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-[10px] font-bold" style="background:#7c3aed">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    {{ $user->name }}
                </div>
                @endforeach
            </div>
        </div>
        @elseif($notulensi->akses_notulensi === 'participants' && $notulensi->meeting?->participants?->count())
        <div class="pt-3 border-t" style="border-color:var(--card-border)">
            <p class="text-xs font-medium mb-2" style="color:var(--text-muted)">Peserta rapat:</p>
            <div class="flex flex-wrap gap-1.5">
                @foreach($notulensi->meeting?->participants ?? [] as $p)
                <div class="flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full" style="background:rgba(16,185,129,0.1);color:#10b981">
                    <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-[10px] font-bold" style="background:#10b981">{{ strtoupper(substr($p->user?->name ?? '?', 0, 1)) }}</span>
                    {{ $p->user?->name ?? '-' }}
                </div>
                @endforeach
            </div>
        </div>
        @endif
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

    <!-- Share Modal -->
    <div x-show="showShareModal" style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
        <div @click.outside="showShareModal = false" x-show="showShareModal"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="card w-full max-w-md"
             style="max-height:90vh;overflow-y:auto">
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
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:var(--text-secondary)">Siapa yang bisa melihat?</label>
                    <div class="space-y-2">
                        <label class="cursor-pointer flex items-center gap-3 p-3 rounded-xl border-2 transition"
                            :style="shareAkses === 'participants' ? 'border-violet-500;background:rgba(124,58,237,0.08)' : 'border-color:var(--card-border);background:var(--surface-bg)'">
                            <input type="radio" value="participants" x-model="shareAkses" class="peer sr-only">
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
                            <input type="radio" value="all_users" x-model="shareAkses" class="peer sr-only">
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
                            <input type="radio" value="pilih_user" x-model="shareAkses" class="peer sr-only">
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
function adminNotulensiShare() {
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
            fetch(`/admin/notulensis/${this.shareNotulensiId}/access`, {
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
                }).then(() => location.reload());
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