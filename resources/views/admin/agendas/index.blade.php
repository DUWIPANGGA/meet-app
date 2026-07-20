@extends('admin.layouts.app')
@section('title', 'Agenda')
@push('head')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<style>
    .fc {
        font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        background: transparent;
    }
    .fc .fc-toolbar {
        background: transparent;
    }
    .fc .fc-toolbar-title {
        background: transparent;
    }
    .fc-theme-standard th {
        border-color: var(--divider);
        padding: 8px 0;
        font-weight: 500;
        color: var(--text-secondary);
        background: var(--surface-bg) !important;
    }
    .fc .fc-header-toolbar {
        background: var(--surface-bg);
        padding: 8px 12px;
        border-radius: 12px;
        margin-bottom: 12px !important;
    }
    .fc .fc-popover {
        background: var(--card-bg);
        border-color: var(--card-border);
        box-shadow: var(--card-shadow);
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: var(--divider);
    }
    .fc-theme-standard .fc-scrollgrid {
        background: transparent;
    }
    .fc .fc-view-harness {
        background: transparent;
    }
    .fc .fc-button-primary {
        background: var(--surface-bg);
        border-color: var(--card-border);
        color: var(--text-secondary);
        text-transform: capitalize;
        font-size: 0.8rem !important;
        padding: 4px 8px !important;
    }
    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:hover {
        background: var(--nav-link-hover);
        border-color: var(--card-border);
        color: var(--text-primary);
    }
    .fc .fc-button-primary:disabled {
        background: var(--surface-bg);
        border-color: var(--card-border);
        color: var(--text-muted);
        opacity: 0.5;
    }
    .fc .fc-daygrid-day.fc-day-today {
        background: rgba(139,92,246,0.08) !important;
    }
    .fc .fc-daygrid-day-number {
        color: var(--text-primary);
        font-size: 0.85rem;
    }
    .fc .fc-col-header-cell-cushion {
        color: var(--text-secondary);
        font-size: 0.75rem;
    }
    .fc .fc-daygrid-more-link {
        color: #7c3aed;
    }
    .fc-event {
        cursor: pointer;
        border-radius: 4px;
        padding: 2px 4px;
        font-size: 0.75em;
        border: none;
        transition: transform 0.1s;
    }
    .fc-event:hover {
        transform: scale(1.02);
    }
    .fc-toolbar-title {
        font-size: 1rem !important;
        font-weight: 600 !important;
        color: var(--text-primary);
    }
    .fc .fc-day-other .fc-daygrid-day-number {
        color: var(--text-muted);
    }
    .fc .fc-non-business {
        background: transparent;
    }
    .fc .fc-scrollgrid {
        border-color: var(--divider);
    }
    .fc .fc-popover {
        background: var(--card-bg);
        border-color: var(--card-border);
        box-shadow: var(--card-shadow);
    }
    .fc .fc-popover-title {
        color: var(--text-primary);
    }
    .fc .fc-popover-header {
        background: var(--surface-bg);
    }
    .fc .fc-toolbar {
        flex-wrap: wrap;
        gap: 6px;
    }
    .fc .fc-toolbar-chunk {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .fc .fc-button-group {
        gap: 2px;
    }
    @media (max-width: 639px) {
        .fc .fc-toolbar-title {
            font-size: 0.9rem !important;
        }
        .fc .fc-button-primary {
            font-size: 0.7rem !important;
            padding: 3px 6px !important;
        }
        .fc .fc-daygrid-day-number {
            font-size: 0.75rem;
        }
        .fc .fc-col-header-cell-cushion {
            font-size: 0.65rem;
        }
        .fc .fc-toolbar {
            gap: 4px;
        }
        .fc .fc-header-toolbar {
            margin-bottom: 8px !important;
        }
    }
</style>
@endpush

@section('content')
<div x-data="adminAgendaCalendar()" class="flex flex-col gap-6">

    @if (session('error'))
    <div class="rounded-xl px-4 py-3 flex items-center gap-3 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#ef4444">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ session('error') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="rounded-xl px-4 py-3 text-sm" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#ef4444">
        <div class="flex items-center gap-3 mb-1">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="font-semibold">Terjadi kesalahan:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 pl-8">
            @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h1>Agenda</h1>
            </div>
            <p>Kalender jadwal rapat dan kegiatan</p>
        </div>
        <button @click="showCreateModal = true" type="button" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Buat Agenda
        </button>
    </div>

    <div class="card overflow-hidden p-3 sm:p-5">
        <div id="admin-agenda-calendar" class="w-full"></div>
    </div>

    <!-- ===================== Event Detail Modal ===================== -->
    <div x-show="showEventModal" style="display:none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
        <div @click.away="showEventModal = false" x-show="showEventModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="card w-full max-w-sm overflow-hidden">
            <div class="px-6 py-5 flex items-center justify-between border-b" style="border-color:var(--divider)">
                <h3 class="text-lg font-medium" style="color:var(--text-primary)">Detail</h3>
                <button @click="showEventModal = false" style="color:var(--text-muted)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1 py-0.5 px-2 rounded-full text-xs font-medium border"
                          :style="`background:${selectedEvent.badgeBg};border-color:${selectedEvent.badgeBorder};color:${selectedEvent.badgeText}`"
                          x-text="selectedEvent.tipeLabel">
                    </span>
                </div>
                <h4 class="text-xl font-medium mb-1" style="color:var(--text-primary)" x-text="selectedEvent.title"></h4>
                <p class="text-sm mb-4" style="color:var(--text-muted)" x-text="selectedEvent.time"></p>
                <div class="rounded-lg p-3 mb-4" style="background:var(--surface-bg)">
                    <div class="flex items-center justify-between">
                        <span class="text-sm" style="color:var(--text-muted)">Status</span>
                        <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium border"
                              :style="selectedEvent.status === 'Berlangsung' ? 'background:rgba(16,185,129,0.1);border-color:rgba(16,185,129,0.3);color:#10b981' : 'background:rgba(99,102,241,0.1);border-color:rgba(99,102,241,0.3);color:#6366f1'"
                              x-text="selectedEvent.status">
                        </span>
                    </div>
                </div>

                <template x-if="selectedEvent.deskripsi">
                <div class="rounded-lg p-3 mb-4" style="background:var(--surface-bg)">
                    <div class="text-sm font-semibold mb-1" style="color:var(--text-muted)">Deskripsi</div>
                    <p class="text-sm whitespace-pre-line" style="color:var(--text-primary)" x-text="selectedEvent.deskripsi"></p>
                </div>
                </template>
                <div class="flex items-center gap-2">
                    <button @click="openEditModal()" type="button" class="flex-1 btn-secondary text-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </button>
                    <form method="POST" x-bind:action="deleteUrl(selectedEvent.id)" onsubmit="return confirm('Yakin ingin menghapus agenda ini?');" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full btn-danger justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== Create Agenda Modal ===================== -->
    <div x-show="showCreateModal" style="display:none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
        <div @click.away="showCreateModal = false" x-show="showCreateModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="card w-full max-w-md overflow-hidden">
            <div class="px-6 py-5 border-b flex items-center justify-between" style="border-color:var(--divider)">
                <div>
                    <h3 class="text-lg font-semibold" style="color:var(--text-primary)">Buat Agenda Baru</h3>
                    <p class="text-xs mt-0.5" style="color:var(--text-muted)">Isi detail agenda yang akan dibuat</p>
                </div>
                <button @click="showCreateModal = false" class="p-1.5 hover:bg-white/10 rounded-full transition" style="color:var(--text-muted)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.meetings.store') }}" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="tipe_rapat" value="terjadwal">
                <input type="hidden" name="_source" value="agenda">
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Nama Agenda <span class="text-red-400">*</span></label>
                    <input type="text" name="nama_rapat" required placeholder="Contoh: Rapat Koordinasi Bulanan"
                           class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    @error('nama_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:var(--text-secondary)">Jenis Agenda</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_rapat" value="Online" x-model="createJenis" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="flex justify-center mb-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Online</div>
                                <div class="text-xs mt-0.5" style="color:var(--text-muted)">Rapat dengan link</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_rapat" value="Offline" x-model="createJenis" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="flex justify-center mb-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Offline</div>
                                <div class="text-xs mt-0.5" style="color:var(--text-muted)">Acara / Kegiatan</div>
                            </div>
                        </label>
                    </div>
                    @error('jenis_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <!-- Hak Akses Rapat -->
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:var(--text-secondary)">Hak Akses Rapat</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="akses_meeting" value="semua_orang" x-model="aksesMode" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition"
                                style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="text-lg mb-0.5">👥</div>
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Semua Orang</div>
                                <div class="text-xs mt-0.5" style="color:var(--text-muted)">Bisa gabung pakai kode</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="akses_meeting" value="pilih_user" x-model="aksesMode" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition"
                                style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="text-lg mb-0.5">🔒</div>
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Undang User</div>
                                <div class="text-xs mt-0.5" style="color:var(--text-muted)">Pilih siapa yang boleh masuk</div>
                            </div>
                        </label>
                    </div>
                </div>
                <!-- User Picker (jika pilih_user) -->
                <div x-show="aksesMode === 'pilih_user'" style="display: none;" x-cloak>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Pilih Pengguna</label>
                    <div class="relative" @click.outside="showUserSearch = false">
                        <input type="text" placeholder="Ketik nama untuk mencari..."
                            x-model="userSearch"
                            @input.debounce.300ms="if(userSearch.length >= 2) { fetch('/api/users?search=' + userSearch).then(r => r.json()).then(d => { searchResults = d; showUserSearch = true }) } else { searchResults = []; showUserSearch = false }"
                            @focus="if(userSearch.length >= 2 && searchResults.length > 0) showUserSearch = true"
                            class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                        <div x-show="showUserSearch && searchResults.length > 0"
                            class="absolute z-20 mt-1 w-full bg-gray-800 border border-gray-700 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                            <template x-for="user in searchResults" :key="user.id">
                                <div @mousedown.prevent="if(!selectedUsers.find(u => u.id === user.id)) { selectedUsers.push(user); searchResults = searchResults.filter(u => u.id !== user.id); userSearch = ''; showUserSearch = false }"
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
                        <template x-for="(user, idx) in selectedUsers" :key="user.id">
                            <div class="flex items-center gap-1.5 bg-violet-500/15 border border-violet-500/30 text-violet-300 text-xs px-2.5 py-1 rounded-full">
                                <span x-text="user.name"></span>
                                <button type="button" @click="selectedUsers.splice(idx, 1)" class="hover:text-white transition">&times;</button>
                                <input type="hidden" name="akses_user_ids[]" :value="user.id">
                            </div>
                        </template>
                    </div>
                    <div x-show="selectedUsers.length === 0" class="text-xs mt-2" style="color:var(--text-muted)">
                        Belum ada pengguna yang dipilih. Ketik nama di atas untuk mencari.
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Tanggal <span class="text-red-400">*</span></label>
                        <input type="date" name="tanggal" required min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                        @error('tanggal') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Waktu <span class="text-red-400">*</span></label>
                        <input type="time" name="waktu" required
                               class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                        @error('waktu') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Deskripsi (opsional)</label>
                    <textarea name="deskripsi_rapat" rows="3" placeholder="Topik bahasan, agenda, lokasi, dll."
                              class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm resize-none"></textarea>
                    @error('deskripsi_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div x-show="createJenis === 'Online'" style="display: none;">
                    <div class="surface-card rounded-xl p-3 flex items-start gap-3 text-xs" style="border:1px solid rgba(139,92,246,0.2)">
                        <svg class="w-4 h-4 mt-0.5 text-violet-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="color:var(--text-muted)">Link meeting akan otomatis tergenerate untuk rapat online.</span>
                    </div>
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="button" @click="showCreateModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl font-medium transition text-sm" style="border:1px solid var(--card-border);color:var(--text-secondary);background:var(--surface-bg)">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 rounded-xl text-white font-semibold transition text-sm shadow-lg shadow-violet-500/20" style="background:linear-gradient(135deg, #7c3aed, #4f46e5)">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================== Edit Agenda Modal ===================== -->
    <div x-show="showEditModal" style="display:none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
        <div @click.away="showEditModal = false" x-show="showEditModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="card w-full max-w-md overflow-hidden">
            <div class="px-6 py-5 border-b flex items-center justify-between" style="border-color:var(--divider)">
                <div>
                    <h3 class="text-lg font-semibold" style="color:var(--text-primary)">Edit Agenda</h3>
                    <p class="text-xs mt-0.5" style="color:var(--text-muted)">Perbarui detail agenda</p>
                </div>
                <button @click="showEditModal = false" class="p-1.5 hover:bg-white/10 rounded-full transition" style="color:var(--text-muted)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" x-bind:action="updateUrl(editId)" class="p-6 space-y-5">
                @csrf @method('PUT')
                <input type="hidden" name="_edit_id" :value="editId">
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Nama Agenda <span class="text-red-400">*</span></label>
                    <input type="text" name="nama_rapat" x-model="editForm.nama_rapat" required
                           class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                    @error('nama_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Tanggal <span class="text-red-400">*</span></label>
                        <input type="date" name="tanggal" x-model="editForm.tanggal" required min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                        @error('tanggal') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Waktu <span class="text-red-400">*</span></label>
                        <input type="time" name="waktu" x-model="editForm.waktu" required
                               class="w-full px-3 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                        @error('waktu') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:var(--text-secondary)">Tipe</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_rapat" value="Online" x-model="editForm.tipe_rapat" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Online</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_rapat" value="Offline" x-model="editForm.tipe_rapat" class="peer sr-only">
                            <div class="rounded-xl border-2 px-4 py-3 text-center hover:border-violet-300 peer-checked:border-violet-500 peer-checked:bg-violet-500/10 transition" style="border-color:var(--card-border);color:var(--text-secondary)">
                                <div class="font-semibold text-sm" style="color:var(--text-primary)">Offline</div>
                            </div>
                        </label>
                    </div>
                    @error('tipe_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Deskripsi</label>
                    <textarea name="deskripsi_rapat" x-model="editForm.deskripsi" rows="3"
                              class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm resize-none"></textarea>
                    @error('deskripsi_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Status</label>
                    <select name="status_rapat" x-model="editForm.status" class="w-full px-4 py-2.5 input-theme rounded-xl outline-none transition text-sm">
                        <option value="Menunggu">Menunggu</option>
                        <option value="Berlangsung">Berlangsung</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                    @error('status_rapat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="button" @click="showEditModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl font-medium transition text-sm" style="border:1px solid var(--card-border);color:var(--text-secondary);background:var(--surface-bg)">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 rounded-xl text-white font-semibold transition text-sm shadow-lg shadow-violet-500/20" style="background:linear-gradient(135deg, #7c3aed, #4f46e5)">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('adminAgendaCalendar', () => ({
        showEventModal: false,
        showCreateModal: false,
        showEditModal: false,
        createJenis: 'Online',
        aksesMode: 'semua_orang',
        userSearch: '',
        searchResults: [],
        selectedUsers: [],
        showUserSearch: false,
        editId: null,
        editForm: {
            nama_rapat: '',
            tanggal: '',
            waktu: '',
            tipe_rapat: 'Online',
            deskripsi: '',
            status: 'Menunggu'
        },
            selectedEvent: {
                title:'', time:'', status:'', url:'#', tipe:'online', tipeLabel:'Rapat Online',
                badgeBg:'rgba(139,92,246,0.1)', badgeBorder:'rgba(139,92,246,0.3)', badgeText:'#7c3aed',
                id: null, tanggal:'', waktu:'', deskripsi:'',
                nama_rapat:''
            },
        init() {
            @if (old('_edit_id'))
            this.editId = @json(old('_edit_id'));
            this.editForm = {
                nama_rapat: @json(old('nama_rapat', '')),
                tanggal: @json(old('tanggal', '')),
                waktu: @json(old('waktu', '')),
                tipe_rapat: @json(old('tipe_rapat', 'Online')),
                deskripsi: @json(old('deskripsi_rapat', '')),
                status: @json(old('status_rapat', 'Menunggu'))
            };
            this.showEditModal = true;
            @elseif ($errors->any())
            this.showCreateModal = true;
            @endif
            var el = document.getElementById('admin-agenda-calendar');
            if (!el || typeof FullCalendar === 'undefined') return;
            var events = [
                @foreach($meetings as $m)
                { id:'m-{{ $m->id }}', title:@json($m->nama_rapat), start:'{{ \Carbon\Carbon::parse($m->tanggal)->format("Y-m-d") }}T{{ $m->waktu ? \Carbon\Carbon::parse($m->waktu)->format("H:i") : "00:00" }}', extendedProps:{ status:'{{ $m->status_rapat }}', tipe:'{{ $m->tipe_rapat === "Offline" ? "offline" : "online" }}', url:'{{ $m->tipe_rapat === "Offline" ? "#" : route("meeting.room", $m->id) }}', displayTime:'{{ \Carbon\Carbon::parse($m->tanggal)->translatedFormat("d M Y") }} - {{ $m->waktu ? \Carbon\Carbon::parse($m->waktu)->format("H:i") : "Sepanjang Hari" }}', deskripsi:@json($m->deskripsi_rapat ?? ''), nama_rapat:@json($m->nama_rapat), tanggal:'{{ $m->tanggal ? $m->tanggal->format("Y-m-d") : "" }}', waktu:'{{ $m->waktu ? \Carbon\Carbon::parse($m->waktu)->format("H:i") : "" }}', tipe_db:'{{ $m->tipe_rapat }}' }, backgroundColor:'{{ $m->tipe_rapat === "Offline" ? "#F59E0B" : ($m->status_rapat === "Berlangsung" ? "#10B981" : "#7c3aed") }}' },
                @endforeach
            ];
            var isMobile = window.innerWidth < 640;
            var cal = new FullCalendar.Calendar(el, {
                initialView: isMobile ? 'timeGridDay' : 'dayGridMonth',
                headerToolbar:{ left: isMobile ? 'prev,next' : 'prev,next today', center:'title', right: isMobile ? 'timeGridDay,timeGridWeek,dayGridMonth' : 'dayGridMonth,timeGridWeek,timeGridDay' },
                events:events, height:'auto', contentHeight:'auto',
                eventClick:(info) => {
                    info.jsEvent.preventDefault();
                    var p = info.event.extendedProps, off = p.tipe === 'offline';
                    this.selectedEvent = {
                        ...this.selectedEvent,
                        title: info.event.title,
                        time: p.displayTime,
                        status: p.status,
                        url: p.url,
                        tipe: p.tipe,
                        tipeLabel: off ? 'Kegiatan' : 'Rapat Online',
                        badgeBg: off ? 'rgba(245,158,11,0.1)' : 'rgba(139,92,246,0.1)',
                        badgeBorder: off ? 'rgba(245,158,11,0.3)' : 'rgba(139,92,246,0.3)',
                        badgeText: off ? '#D97706' : '#7c3aed',
                        id: info.event.id,
                        tanggal: p.tanggal,
                        waktu: p.waktu,
                        deskripsi: p.deskripsi
                    };
                    this.showEventModal = true;
                }
            });
            cal.render();
        },
        deleteUrl(id) {
            if (!id) return '#';
            var meetingId = id.replace('m-', '');
            return '/admin/agendas/' + meetingId;
        },
        updateUrl(id) {
            if (!id) return '#';
            return '/admin/agendas/' + id;
        },
        openEditModal() {
            var id = this.selectedEvent.id;
            if (!id) return;
            this.editId = id.replace('m-', '');
            var p = this.selectedEvent;
            this.editForm = {
                nama_rapat: p.title,
                tanggal: p.tanggal || '',
                waktu: p.waktu || '',
                tipe_rapat: p.tipe === 'offline' ? 'Offline' : 'Online',
                deskripsi: p.deskripsi || '',
                status: p.status
            };
            this.showEventModal = false;
            this.showEditModal = true;
        }
    }));
});
</script>
@endsection