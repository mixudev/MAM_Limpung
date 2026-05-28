@extends('dashboard.layouts.main')

@section('content')
<!-- Load Alpine.js CDN for dynamic client-side interactions -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Manajemen Pengguna / Detail Akun';
        }
    });
</script>

<div class="max-w-6xl">
    <!-- Header Navigation -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ Auth::user()->hasRole('super-admin') ? route('super-admin.users.index') : route('admin.users.index') }}" 
           class="inline-flex items-center gap-2 text-xs font-mono font-bold text-slate-500 hover:text-slate-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: User Summary & Actions -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Profile Card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm p-6">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-[#4f45b2]/10 text-[#4f45b2] dark:bg-indigo-900/30 dark:text-indigo-400 rounded-full flex items-center justify-center mb-4">
                        <span class="text-3xl font-bold font-mono">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-zinc-100">{{ $user->name }}</h2>
                    <p class="text-xs font-mono text-slate-500 dark:text-zinc-400 mt-1">{{ $user->email }}</p>
                    
                    <div class="mt-4 flex flex-wrap justify-center gap-1.5">
                        @foreach ($user->roles as $role)
                            <span class="px-2.5 py-0.5 text-[9px] font-mono font-bold uppercase tracking-wider rounded-none bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800/50">
                                {{ $role->display_name ?: $role->name }}
                            </span>
                        @endforeach
                    </div>

                    <div class="w-full h-px bg-slate-100 dark:bg-zinc-800 my-5"></div>

                    <div class="w-full flex justify-between items-center text-xs">
                        <span class="text-slate-500 dark:text-zinc-400">Status Akun:</span>
                        @if ($user->is_active)
                            <span class="px-2 py-0.5 text-[9px] font-bold font-mono text-emerald-700 bg-emerald-50 border border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-850/40">AKTIF</span>
                        @else
                            <span class="px-2 py-0.5 text-[9px] font-bold font-mono text-rose-700 bg-rose-50 border border-rose-200 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-850/40">DIBLOKIR</span>
                        @endif
                    </div>
                    <div class="w-full flex justify-between items-center text-xs mt-3">
                        <span class="text-slate-500 dark:text-zinc-400">Login Terakhir:</span>
                        <span class="font-semibold text-slate-700 dark:text-zinc-300">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum Pernah' }}
                        </span>
                    </div>
                    @if ($user->last_login_ip)
                    <div class="w-full flex justify-between items-center text-xs mt-2">
                        <span class="text-slate-500 dark:text-zinc-400">IP Address:</span>
                        <span class="font-mono text-slate-700 dark:text-zinc-300">{{ $user->last_login_ip }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-100 mb-4 border-b border-slate-100 dark:border-zinc-800 pb-2">
                    Tindakan Pengamanan
                </h3>
                
                <div class="space-y-4">
                    <!-- Email Verification Action -->
                    @if ($user->email_verified_at)
                        <div class="w-full py-2.5 px-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 font-mono font-bold text-[10px] uppercase tracking-wider flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Email Terverifikasi
                        </div>
                    @else
                        <form method="POST" action="{{ Auth::user()->hasRole('super-admin') ? route('super-admin.users.verify-email', $user) : route('admin.users.verify-email', $user) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full py-2.5 px-4 bg-[#4f45b2] hover:bg-[#6366f1] text-white border border-transparent font-mono font-bold text-[10px] uppercase tracking-wider flex items-center justify-center gap-2 transition-all cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Kirim Link Verifikasi
                            </button>
                        </form>
                    @endif

                    <!-- Password Reset Action (With Alert Warning Popup) -->
                    <form id="reset-password-form" method="POST" action="{{ Auth::user()->hasRole('super-admin') ? route('super-admin.users.reset-password-link', $user) : route('admin.users.reset-password-link', $user) }}">
                        @csrf
                        <button type="button"
                                onclick="AppPopup.confirm({
                                    title: 'Kirim Link Reset Password?',
                                    description: 'Aksi ini akan mengirimkan tautan reset kata sandi resmi ke email <strong>{{ $user->email }}</strong> secara aman. Tautan verifikasi hanya berlaku sekali pakai.',
                                    confirmText: 'Ya, Kirim Email',
                                    cancelText: 'Batal',
                                    onConfirm: () => document.getElementById('reset-password-form').submit()
                                })"
                                class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-white border border-transparent font-mono font-bold text-[10px] uppercase tracking-wider flex items-center justify-center gap-2 transition-all cursor-pointer shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m-9 5a2 2 0 01-2-2m7-7a2 2 0 00-2-2m0 0a2 2 0 00-2 2m0 0V4a2 2 0 00-2-2H5a2 2 0 00-2 2v3a2 2 0 002 2h4a2 2 0 002-2v-1z" />
                            </svg>
                            Kirim Tautan Reset Sandi
                        </button>
                    </form>
                </div>
            </div>
            
        </div>

        <!-- Right Column: Edit Form -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center bg-slate-50 dark:bg-zinc-950/30">
                    <h2 class="text-sm font-bold text-slate-800 dark:text-zinc-100 uppercase tracking-wider">Form Edit Akun User</h2>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ Auth::user()->hasRole('super-admin') ? route('super-admin.users.update', $user) : route('admin.users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Grid Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 rounded-none transition-colors">
                                @error('name')
                                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 rounded-none transition-colors">
                                @error('email')
                                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Security -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1.5">Password Baru (Opsional)</label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter..."
                                   class="w-full md:w-1/2 px-3 py-2 text-sm bg-white dark:bg-zinc-950 border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 rounded-none transition-colors">
                            <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1.5 font-mono">
                                * Kosongkan bagian ini jika tidak ingin mengubah kata sandi pengguna.
                            </p>
                            @error('password')
                                <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status (Segmented Toggle / Radio cards instead of standard Checkbox) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-2">Status Login & Akses Akun</label>
                            <div class="flex gap-2 md:w-2/3">
                                {{-- Active segment button --}}
                                <label class="flex-1 cursor-pointer select-none">
                                    <input type="radio" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="px-4 py-2.5 text-center text-xs font-mono font-bold uppercase border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-500 dark:text-zinc-500 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/20 peer-checked:text-emerald-700 dark:peer-checked:bg-emerald-950/15 dark:peer-checked:text-emerald-400 peer-checked:ring-1 peer-checked:ring-emerald-500 transition-all hover:bg-slate-50 dark:hover:bg-zinc-800/40">
                                        AKTIF (ACTIVE)
                                    </div>
                                </label>

                                {{-- Blocked segment button --}}
                                <label class="flex-1 cursor-pointer select-none">
                                    <input type="radio" name="is_active" value="0" {{ !old('is_active', $user->is_active) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="px-4 py-2.5 text-center text-xs font-mono font-bold uppercase border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-500 dark:text-zinc-500 peer-checked:border-rose-500 peer-checked:bg-rose-50/20 peer-checked:text-rose-700 dark:peer-checked:bg-rose-950/15 dark:peer-checked:text-rose-400 peer-checked:ring-1 peer-checked:ring-rose-500 transition-all hover:bg-slate-50 dark:hover:bg-zinc-800/40">
                                        BLOKIR (BANNED)
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Roles Section (Redesigned with minimal cards without checkbox circle) -->
                        <div class="border-t border-slate-100 dark:border-zinc-800 pt-5">
                            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Peran & Tingkat Otoritas (Roles) <span class="text-rose-500">*</span></label>
                            <p class="text-[11px] text-slate-400 dark:text-zinc-500 mb-3">Tentukan tingkat kewenangan sistem yang dimiliki akun ini.</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @php
                                    $userRoleNames = $user->roles->pluck('name')->toArray();
                                @endphp

                                @foreach ($roles as $role)
                                    <label class="relative cursor-pointer block select-none">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                               {{ in_array($role->name, old('roles', $userRoleNames)) ? 'checked' : '' }}
                                               class="absolute opacity-0 pointer-events-none w-0 h-0 peer">
                                        <div class="p-3.5 text-center border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/60 hover:bg-slate-50 dark:hover:bg-zinc-800/40 transition-all peer-checked:border-[#4f45b2] dark:peer-checked:border-indigo-500 peer-checked:bg-indigo-50/10 dark:peer-checked:bg-indigo-950/10 peer-checked:ring-1 peer-checked:ring-[#4f45b2] flex flex-col justify-center items-center">
                                            <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                                                {{ $role->display_name ?: $role->name }}
                                            </span>
                                            <span class="text-[9px] text-slate-400 dark:text-zinc-500 font-mono mt-0.5">
                                                Level Akses: {{ $role->level }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('roles')
                                <p class="text-[10px] text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Direct Permissions Section (Custom reactive labels & AppModal) -->
                        <div x-data='{
                            assignedPermissions: @json($user->permissions->pluck('name')->toArray()),
                            allPermissions: @json($permissions->pluck('name')->toArray()),
                            searchQuery: "",
                            
                            get unassignedPermissions() {
                                return this.allPermissions.filter(p => !this.assignedPermissions.includes(p));
                            },
                            
                            get filteredUnassignedPermissions() {
                                if (!this.searchQuery) return this.unassignedPermissions;
                                const q = this.searchQuery.toLowerCase();
                                return this.unassignedPermissions.filter(p => p.toLowerCase().includes(q));
                            },
                            
                            addPermission(name) {
                                if (!this.assignedPermissions.includes(name)) {
                                    this.assignedPermissions.push(name);
                                }
                            },
                            
                            removePermission(name) {
                                this.assignedPermissions = this.assignedPermissions.filter(p => p !== name);
                            }
                        }' class="border-t border-slate-100 dark:border-zinc-800 pt-5">
                            
                            <!-- Inherited Permissions (Daftar hak akses bawaan dari Peran - Collapsible) -->
                            <div class="mb-5" x-data="{ showInherited: false }">
                                <div @click="showInherited = !showInherited" 
                                     class="flex items-center justify-between cursor-pointer select-none p-3 bg-slate-50 hover:bg-slate-100/80 dark:bg-zinc-950/40 dark:hover:bg-zinc-950/60 border border-slate-200 dark:border-zinc-800 transition-colors">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 cursor-pointer mb-0">
                                            Hak Akses Bawaan Peran (Inherited Permissions)
                                        </label>
                                        <span class="px-1.5 py-0.2 text-[9px] font-mono font-bold bg-slate-200/60 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 border border-slate-300/40 dark:border-zinc-700">
                                            {{ count($user->getPermissionsViaRoles()) }}
                                        </span>
                                    </div>
                                    <div class="text-slate-400 hover:text-slate-650 dark:hover:text-zinc-300">
                                        <i class="fa-solid" :class="showInherited ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                    </div>
                                </div>

                                <div x-show="showInherited" x-transition class="p-3 border-x border-b border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/40">
                                    <p class="text-[11px] text-slate-400 dark:text-zinc-500 mb-3">Daftar izin akses yang didapatkan secara otomatis melalui peran (roles) aktif di atas.</p>
                                    
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse ($user->getPermissionsViaRoles() as $perm)
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-[9px] font-mono font-bold uppercase tracking-wider bg-slate-100 text-slate-500 dark:bg-zinc-800 dark:text-zinc-450 border border-slate-200 dark:border-zinc-700" title="Didapatkan secara otomatis dari peran">
                                                {{ $perm->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400 dark:text-zinc-550 italic py-1">Belum ada hak akses bawaan dari peran (silakan pilih peran di atas).</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Direct/Custom Permissions (Hak Akses Tambahan) -->
                            <div class="border-t border-dashed border-slate-100 dark:border-zinc-800 pt-5">
                                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Hak Akses Kustom Tambahan (Direct Permissions)</label>
                                <p class="text-[11px] text-slate-400 dark:text-zinc-500 mb-3">Tambahkan atau hapus izin akses spesifik langsung ke akun ini tanpa merubah peran global.</p>
                                
                                {{-- Trigger Button --}}
                                <div class="mb-4">
                                    <button type="button" @click="AppModal.open('addPermissionModal')"
                                        class="inline-flex py-1.5 px-3 bg-white dark:bg-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-700 border border-slate-300 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 font-mono font-bold text-[9px] uppercase tracking-wider transition-all rounded-none items-center gap-1.5 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Tambah Hak Akses
                                    </button>
                                </div>

                                {{-- Active Permission Tags --}}
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <template x-for="perm in assignedPermissions" :key="perm">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-mono font-bold uppercase tracking-wider border border-indigo-200 dark:border-indigo-800 bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20 dark:text-indigo-400">
                                            <span x-text="perm"></span>
                                            <button type="button" @click="removePermission(perm)" class="text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-200 font-bold focus:outline-none text-[13px] leading-none cursor-pointer">
                                                &times;
                                            </button>
                                            {{-- Hidden inputs to submit to PHP backend request --}}
                                            <input type="hidden" name="permissions[]" :value="perm">
                                        </span>
                                    </template>
                                    
                                    <template x-if="assignedPermissions.length === 0">
                                        <span class="text-xs text-slate-400 dark:text-zinc-500 italic py-1">Belum ada hak akses kustom tambahan untuk user ini.</span>
                                    </template>
                                </div>
                            </div>

                            {{-- ── Add Permission Modal (x-app-modal) ── --}}
                            <x-app-modal id="addPermissionModal" title="Pilih Hak Akses Baru" maxWidth="lg" iconColor="indigo"
                                icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>'>
                                <div class="space-y-4">
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Pilih izin akses kustom di bawah untuk ditambahkan langsung ke pengguna ini.</p>
                                    
                                    {{-- Search Box --}}
                                    <div class="relative">
                                        <input type="text" x-model="searchQuery" placeholder="Cari hak akses..." 
                                            class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:border-[#4f45b2] focus:ring-1 focus:ring-[#4f45b2]">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-zinc-500">
                                            <i class="fa-solid fa-magnifying-glass text-[11px]"></i>
                                        </div>
                                        <button type="button" x-show="searchQuery" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-650 text-xs">
                                            &times;
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-60 overflow-y-auto custom-scrollbar p-1">
                                        <template x-for="perm in filteredUnassignedPermissions" :key="perm">
                                            <button type="button" @click="addPermission(perm); AppModal.close('addPermissionModal'); searchQuery = ''"
                                                class="w-full text-left p-3 border border-slate-200 dark:border-zinc-800 bg-white hover:bg-indigo-50/50 dark:bg-zinc-900/50 dark:hover:bg-zinc-800/40 text-[11px] font-mono text-slate-600 hover:text-[#4f45b2] dark:text-zinc-400 dark:hover:text-indigo-400 transition-all rounded-none focus:outline-none cursor-pointer">
                                                <span x-text="perm"></span>
                                            </button>
                                        </template>
                                        
                                        <template x-if="filteredUnassignedPermissions.length === 0">
                                            <div class="col-span-2 text-center py-6 text-xs text-slate-400 dark:text-zinc-500 italic">
                                                Semua hak akses sistem sudah dimiliki atau tidak ditemukan.
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <x-slot name="footer">
                                    <button type="button" onclick="AppModal.close('addPermissionModal'); searchQuery = ''" class="modal-btn-cancel">
                                        TUTUP
                                    </button>
                                </x-slot>
                            </x-app-modal>
                        </div>

                        <!-- Actions -->
                        <div class="pt-6 border-t border-slate-100 dark:border-zinc-800 flex justify-end gap-3">
                            <a href="{{ Auth::user()->hasRole('super-admin') ? route('super-admin.users.index') : route('admin.users.index') }}" 
                               class="py-2.5 px-6 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 font-mono font-bold text-xs uppercase tracking-wider transition-colors border border-slate-200 dark:border-zinc-700">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-mono font-bold text-xs uppercase tracking-wider transition-all shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
