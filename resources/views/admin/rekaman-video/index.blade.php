@extends('admin.layouts.app')
@section('title', 'Rekaman Video')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div x-data="videoShare()">
    <div class="page-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed;flex-shrink:0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                </svg>
                <h1>Rekaman Video</h1>
            </div>
            <p>Total {{ $rekamans->total() }} rekaman video tersimpan</p>
        </div>
    </div>

    @if($rekamans->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($rekamans as $rek)
        <div class="card overflow-hidden flex flex-col">
            <div class="relative bg-black" style="aspect-ratio:16/9">
                <video
                    preload="metadata"
                    style="width:100%;height:100%;object-fit:contain"
                    src="{{ route('admin.rekaman-video.stream', $rek->id) }}#t=0.1">
                </video>
                <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 hover:opacity-100 transition-opacity">
                    <a href="{{ route('admin.rekaman-video.stream', $rek->id) }}" target="_blank"
                       class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg transition-transform hover:scale-110"
                       style="background:rgba(124,58,237,0.9);color:#fff">
                        <svg class="w-6 h-6 ml-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </a>
                </div>
            </div>
            <div class="p-4 flex-1 flex flex-col justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="badge" style="background:rgba(124,58,237,0.1);color:#7c3aed">Video</span>
                        @if($rek->durasi)
                        <span class="text-xs" style="color:var(--text-muted)">{{ $rek->durasi }}</span>
                        @endif
                    </div>
                    <h3 class="font-semibold text-sm truncate" style="color:var(--text-primary)">
                        {{ $rek->meeting?->nama_rapat ?? 'Tanpa Meeting' }}
                    </h3>
                    <p class="text-xs mt-0.5" style="color:var(--text-muted)">
                        {{ $rek->tanggal_upload ? \Carbon\Carbon::parse($rek->tanggal_upload)->translatedFormat('d M Y') : $rek->created_at->translatedFormat('d M Y') }}
                        @if($rek->file_size_bytes)
                        &middot; {{ number_format($rek->file_size_bytes / 1024 / 1024, 1) }} MB
                        @endif
                    </p>
                </div>
                <div class="flex items-center justify-between pt-2" style="border-top:1px solid var(--divider)">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.rekaman-video.stream', $rek->id) }}" target="_blank"
                           class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Putar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7c3aed"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </a>
                        <a href="{{ route('admin.rekaman-video.download', $rek->id) }}"
                           class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Download">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-secondary)"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                        <button @click="openShareModal({{ $rek->id }}, '{{ addslashes($rek->meeting?->nama_rapat ?? 'Video #'.$rek->id) }}', '{{ $rek->akses_rekaman }}', {{ Js::from($rek->accessUsers->pluck('id')) }})"
                            class="p-2 rounded-lg hover:bg-[var(--nav-link-hover)] transition" title="Bagikan">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#d97706"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        </button>
                    </div>
                    <form action="{{ route('admin.rekaman-video.destroy', $rek->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rekaman video ini?');" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 transition" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#ef4444"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-5">
        {{ $rekamans->links() }}
    </div>
    @else
    <div class="card">
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width:40px;height:40px;margin:0 auto 12px;display:block;color:var(--text-muted)"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            <p>Belum ada rekaman video.</p>
        </div>
    </div>
    @endif

{{-- Share Modal --}}
<div x-show="showShareModal" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div @click.away="showShareModal = false" x-show="showShareModal"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="page-card w-full max-w-md overflow-hidden">
        <div class="px-6 py-5 border-b flex items-center justify-between" style="border-color:var(--divider)">
            <div>
                <h3 class="text-lg font-semibold" style="color:var(--text-primary)">Bagikan Rekaman Video</h3>
                <p class="text-xs mt-0.5" style="color:var(--text-muted)" x-text="shareRekamanName"></p>
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
                        :style="shareAkses === 'pemilik' ? 'border-violet-500;background:rgba(124,58,237,0.08)' : 'border-color:var(--card-border);background:var(--surface-bg)'">
                        <input type="radio" name="share_akses" value="pemilik" x-model="shareAkses" class="peer sr-only">
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                            :style="shareAkses === 'pemilik' ? 'border-violet-500' : 'border-color:var(--text-muted)'">
                            <div x-show="shareAkses === 'pemilik'" class="w-2.5 h-2.5 rounded-full bg-violet-500"></div>
                        </div>
                        <div>
                            <div class="text-sm font-semibold" style="color:var(--text-primary)">Pemilik</div>
                            <div class="text-xs" style="color:var(--text-muted)">Hanya pembuat rekaman</div>
                        </div>
                    </label>
                    <label class="cursor-pointer flex items-center gap-3 p-3 rounded-xl border-2 transition"
                        :style="shareAkses === 'semua_orang' ? 'border-violet-500;background:rgba(124,58,237,0.08)' : 'border-color:var(--card-border);background:var(--surface-bg)'">
                        <input type="radio" name="share_akses" value="semua_orang" x-model="shareAkses" class="peer sr-only">
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0"
                            :style="shareAkses === 'semua_orang' ? 'border-violet-500' : 'border-color:var(--text-muted)'">
                            <div x-show="shareAkses === 'semua_orang'" class="w-2.5 h-2.5 rounded-full bg-violet-500"></div>
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

<script>
function videoShare() {
    return {
        showShareModal: false,
        shareRekamanId: null,
        shareRekamanName: '',
        shareAkses: 'pemilik',
        shareUserSearch: '',
        shareSearchResults: [],
        shareSelectedUsers: [],
        showShareUserSearch: false,
        shareSaving: false,

        openShareModal(rekamanId, rekamanName, akses, userIds) {
            this.shareRekamanId = rekamanId;
            this.shareRekamanName = rekamanName;
            this.shareAkses = akses || 'pemilik';
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
            fetch(`/admin/rekaman-video/${this.shareRekamanId}/access`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    akses_rekaman: this.shareAkses,
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
                    text: 'Akses rekaman berhasil diperbarui.',
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
</div>
<style>
    .card video::-webkit-media-controls { display:none !important; }
</style>
@endsection