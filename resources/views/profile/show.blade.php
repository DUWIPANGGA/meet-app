@extends('layouts.app')

@section('content')
<style>
    .profile-header {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 20px 20px 0 0;
    }
    .profile-name { color: #0f172a; }
    .profile-email { color: #475569; }
    .profile-header .avatar-circle { background: #94a3b8; border-color: #cbd5e1; }
    .profile-header .badge { background: rgba(0,0,0,0.08); color: #475569; border-color: rgba(0,0,0,0.1); }
    .profile-header .badge-active { background: rgba(22,163,74,0.12); color: #16a34a; border-color: rgba(22,163,74,0.2); }
    .dark .profile-header {
        background: linear-gradient(to right, #7c3aed, #4f46e5);
    }
    .dark .profile-name { color: #ffffff; }
    .dark .profile-email { color: #c4b5fd; }
    .dark .profile-header .avatar-circle { background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.4); }
    .dark .profile-header .badge { background: rgba(255,255,255,0.2); color: #ffffff; border-color: rgba(255,255,255,0.3); }
    .dark .profile-header .badge-active { background: rgba(74,222,128,0.3); color: #86efac; border-color: rgba(74,222,128,0.4); }
</style>
<div class="p-6 w-full max-w-4xl mx-auto" x-data="{ tab: '{{ session('tab', 'profile') }}' }">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold" style="color:var(--text-primary)">Profil Saya</h1>
        <p class="mt-1" style="color:var(--text-secondary)">Kelola informasi akun dan keamanan Anda.</p>
    </div>

    {{-- Profile Card --}}
    <div class="page-card overflow-hidden mb-6">

        {{-- Avatar Header --}}
        <div class="profile-header px-8 py-8 flex items-center gap-6">
            @if($user->photo)
            <div class="w-20 h-20 rounded-full border-2 overflow-hidden flex-shrink-0" style="border-color:var(--card-border)">
                <img src="{{ asset('storage/'.$user->photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
            </div>
            @else
            <div class="w-20 h-20 rounded-full border-2 flex items-center justify-center text-white text-3xl font-bold select-none flex-shrink-0" style="background:var(--accent);border-color:var(--card-border)">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            @endif
            <div>
                <h2 class="profile-name text-2xl font-bold">{{ $user->name }}</h2>
                <p class="profile-email text-sm mt-0.5">{{ $user->email }}</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 badge text-xs font-medium px-3 py-1 rounded-full">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ $user->jabatan?->nama_jabatan ?? 'Belum diisi' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 badge-active text-xs font-medium px-3 py-1 rounded-full">
                        ● Aktif
                    </span>
                </div>
            </div>
        </div>

        {{-- Tab Switcher --}}
        <div class="flex px-6" style="border-bottom: 1px solid var(--divider)">
            <button @click="tab = 'profile'"
                    class="px-4 py-3.5 text-sm font-medium border-b-2 transition-colors -mb-px"
                    :class="tab === 'profile' ? 'border-violet-500 text-violet-600' : 'border-transparent'"
                    :style="tab === 'profile' ? '' : 'color:var(--text-secondary)'">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Data Diri
                </div>
            </button>
            <button @click="tab = 'password'"
                    class="px-4 py-3.5 text-sm font-medium border-b-2 transition-colors -mb-px"
                    :class="tab === 'password' ? 'border-violet-500 text-violet-600' : 'border-transparent'"
                    :style="tab === 'password' ? '' : 'color:var(--text-secondary)'">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Ubah Password
                </div>
            </button>
        </div>

        {{-- ======================== --}}
        {{-- TAB: DATA DIRI          --}}
        {{-- ======================== --}}
        <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Success Alert --}}
            @if(session('success_profile'))
            <div class="mx-6 mt-5 flex items-center gap-3 px-4 py-3 rounded-xl surface-card" style="border-color:rgba(34,197,94,0.3);color:#16a34a">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ session('success_profile') }}
            </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="px-8 py-7 space-y-6">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Nama Lengkap <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" style="color:var(--text-muted)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl input-theme outline-none text-sm transition @error('name') ring-2 ring-red-400 @enderror"
                               placeholder="Masukkan nama lengkap">
                    </div>
                    @error('name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Alamat Email <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" style="color:var(--text-muted)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl input-theme outline-none text-sm transition @error('email') ring-2 ring-red-400 @enderror"
                               placeholder="nama@domain.com">
                    </div>
                    @error('email')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Photo --}}
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Foto Profil</label>
                    <div class="flex items-center gap-4">
                        @if($user->photo)
                        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-violet-300 flex-shrink-0">
                            <img src="{{ asset('storage/'.$user->photo) }}" alt="" class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="w-16 h-16 rounded-full avatar-circle text-white flex items-center justify-center font-bold text-xl select-none flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp"
                                   class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-violet-500/10 file:text-violet-600 hover:file:bg-violet-500/20 transition">
                            <p class="mt-1 text-xs" style="color:var(--text-muted)">Format: JPG, PNG, WebP. Maks 2MB.</p>
                        </div>
                    </div>
                    @error('photo')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Role (read-only) --}}
                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Role Akun</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" style="color:var(--text-muted)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <input type="text"
                               value="{{ ucfirst($user->role ?? implode(', ', $user->getRoleNames()->toArray()) ?: 'User') }}"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm cursor-not-allowed input-theme opacity-60"
                               readonly disabled>
                    </div>
                    <p class="mt-1 text-xs" style="color:var(--text-muted)">Role tidak dapat diubah sendiri. Hubungi administrator.</p>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-semibold py-2.5 px-6 rounded-xl transition shadow-lg shadow-violet-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- ======================== --}}
        {{-- TAB: UBAH PASSWORD      --}}
        {{-- ======================== --}}
        <div x-show="tab === 'password'" style="display:none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Success Alert --}}
            @if(session('success_password'))
            <div class="mx-6 mt-5 flex items-center gap-3 px-4 py-3 rounded-xl surface-card" style="border-color:rgba(34,197,94,0.3);color:#16a34a">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ session('success_password') }}
            </div>
            @endif

            <form action="{{ route('profile.password') }}" method="POST" class="px-8 py-7 space-y-6">
                @csrf
                @method('PUT')

                {{-- Info box --}}
                <div class="rounded-xl px-4 py-3 flex items-start gap-3 surface-card" style="border-color:rgba(139,92,246,0.2);color:var(--text-secondary)">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm">Password baru minimal <strong>8 karakter</strong>, mengandung <strong>huruf besar, kecil</strong>, dan <strong>angka</strong>.</p>
                </div>

                {{-- Current Password --}}
                <div x-data="{ show: false }">
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Password Saat Ini <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" style="color:var(--text-muted)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="current_password"
                               class="w-full pl-10 pr-10 py-2.5 rounded-xl input-theme outline-none text-sm transition @error('current_password') ring-2 ring-red-400 @enderror"
                               placeholder="Masukkan password saat ini">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center" style="color:var(--text-muted)">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('current_password')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- New Password --}}
                <div x-data="{ show: false }">
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Password Baru <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" style="color:var(--text-muted)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password"
                               class="w-full pl-10 pr-10 py-2.5 rounded-xl input-theme outline-none text-sm transition @error('password') ring-2 ring-red-400 @enderror"
                               placeholder="Password baru (min. 8 karakter)">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center" style="color:var(--text-muted)">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Confirm Password --}}
                <div x-data="{ show: false }">
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text-secondary)">Konfirmasi Password Baru <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" style="color:var(--text-muted)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password_confirmation"
                               class="w-full pl-10 pr-10 py-2.5 rounded-xl input-theme outline-none text-sm transition"
                               placeholder="Ulangi password baru">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center" style="color:var(--text-muted)">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-semibold py-2.5 px-6 rounded-xl transition shadow-lg shadow-violet-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>

    </div>{{-- end card --}}
</div>
@endsection