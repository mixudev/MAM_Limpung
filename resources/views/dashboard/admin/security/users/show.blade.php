@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'User Accounts / Info Detail';
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
                            <span class="px-2 py-0.5 text-[10px] font-mono font-bold uppercase tracking-wider rounded-none bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800/50">
                                {{ $role->display_name ?: $role->name }}
                            </span>
                        @endforeach
                    </div>

                    <div class="w-full h-px bg-slate-100 dark:bg-zinc-800 my-5"></div>

                    <div class="w-full flex justify-between items-center text-xs">
                        <span class="text-slate-500 dark:text-zinc-400">Status Akun:</span>
                        @if ($user->is_active)
                            <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400">ACTIVE</span>
                        @else
                            <span class="font-mono font-bold text-rose-600 dark:text-rose-400">INACTIVE</span>
                        @endif
                    </div>
                    <div class="w-full flex justify-between items-center text-xs mt-2">
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
                    Tindakan Cepat
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

                    <!-- Password Reset Action -->
                    <form method="POST" action="{{ Auth::user()->hasRole('super-admin') ? route('super-admin.users.reset-password-link', $user) : route('admin.users.reset-password-link', $user) }}">
                        @csrf
                        <button type="submit"
                                class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-white border border-transparent font-mono font-bold text-[10px] uppercase tracking-wider flex items-center justify-center gap-2 transition-all cursor-pointer shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m-9 5a2 2 0 01-2-2m7-7a2 2 0 00-2-2m0 0a2 2 0 00-2 2m0 0V4a2 2 0 00-2-2H5a2 2 0 00-2 2v3a2 2 0 002 2h4a2 2 0 002-2v-1z" />
                            </svg>
                            Generate Link Reset Password
                        </button>
                    </form>

                    <!-- Verification URL Display -->
                    @if (session('verification_url'))
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-950/20 border border-indigo-200 dark:border-indigo-800/50 rounded-none text-xs space-y-2 mt-4" x-data="{ copied: false }">
                            <p class="font-mono text-[10px] text-indigo-800 dark:text-indigo-400 font-bold uppercase">Copy Link Verifikasi (WhatsApp/Lainnya):</p>
                            <textarea readonly class="w-full p-2 bg-white dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-[10px] font-mono focus:outline-none" rows="3">{{ session('verification_url') }}</textarea>
                            <button type="button" @click="navigator.clipboard.writeText('{{ session('verification_url') }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                    class="w-full py-1.5 px-3 bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-200 font-mono text-[9px] uppercase font-bold tracking-wider flex items-center justify-center gap-1">
                                <span x-text="copied ? 'Tersalin!' : 'Salin Tautan'"></span>
                            </button>
                        </div>
                    @endif

                    <!-- Reset URL Display -->
                    @if (session('reset_url'))
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/50 rounded-none text-xs space-y-2 mt-4" x-data="{ copied: false }">
                            <p class="font-mono text-[10px] text-amber-800 dark:text-amber-400 font-bold uppercase">Copy Link Reset Password (Sekali Pakai):</p>
                            <textarea readonly class="w-full p-2 bg-white dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-[10px] font-mono focus:outline-none" rows="3">{{ session('reset_url') }}</textarea>
                            <button type="button" @click="navigator.clipboard.writeText('{{ session('reset_url') }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                    class="w-full py-1.5 px-3 bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-200 font-mono text-[9px] uppercase font-bold tracking-wider flex items-center justify-center gap-1">
                                <span x-text="copied ? 'Tersalin!' : 'Salin Tautan'"></span>
                            </button>
                        </div>
                    @endif
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

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-2">Status Login</label>
                            <label class="inline-flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                       class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600 dark:bg-zinc-950 dark:border-zinc-700 dark:focus:ring-emerald-500 transition-colors">
                                <span class="text-sm font-medium text-slate-800 dark:text-zinc-200">
                                    Izinkan Akses Login (Active)
                                </span>
                            </label>
                        </div>

                        <!-- Roles Section -->
                        <div class="border-t border-slate-100 dark:border-zinc-800 pt-5">
                            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-3">Hak Akses & Peran (Roles) <span class="text-rose-500">*</span></label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4 bg-slate-50 dark:bg-zinc-950/50 border border-slate-200 dark:border-zinc-800 rounded-none">
                                @php
                                    $userRoleNames = $user->roles->pluck('name')->toArray();
                                @endphp

                                @foreach ($roles as $role)
                                    <label class="flex items-start gap-3 cursor-pointer p-2 hover:bg-slate-100 dark:hover:bg-zinc-900 transition-colors border border-transparent hover:border-slate-200 dark:hover:border-zinc-800">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                               {{ in_array($role->name, old('roles', $userRoleNames)) ? 'checked' : '' }}
                                               class="w-4 h-4 rounded border-slate-300 text-[#4f45b2] focus:ring-[#4f45b2] mt-0.5 dark:bg-zinc-950 dark:border-zinc-700">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider">
                                                {{ $role->display_name ?: $role->name }}
                                            </span>
                                            <span class="text-[10px] text-slate-500 dark:text-zinc-500 font-mono mt-0.5">
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
