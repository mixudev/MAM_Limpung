@extends('dashboard.layouts.main')

@section('content')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const breadcrumb = document.getElementById('breadcrumb');
            if (breadcrumb) {
                breadcrumb.textContent = 'Edit Profil Diri';
            }
        });

        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <div class="max-w-6xl space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Kelola Profil Diri</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Perbarui data diri pribadi Anda dan ganti kata sandi
                secara aman untuk perlindungan akun Anda.</p>
        </div>

        <!-- Alert Messages (handled elegantly by the layout, but let's provide standard session alert in case) -->
        @if (session('success'))
            @php
                $parts = explode('|', session('success'));
                $title = $parts[0] ?? 'Sukses';
                $msg = $parts[1] ?? '';
            @endphp
            <div
                class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 text-emerald-800 dark:text-emerald-400 text-xs flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mt-0.5 flex-shrink-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <strong class="block font-bold text-slate-800 dark:text-zinc-200">{{ $title }}</strong>
                    @if ($msg)
                        <span class="block mt-1 leading-relaxed">{{ $msg }}</span>
                    @endif
                </div>
            </div>
        @endif

        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- Left: Profile Summary Avatar Card -->
            <div
                class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm text-center space-y-4">
                <span
                    class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block text-left border-b border-slate-100 dark:border-zinc-800 pb-2">
                    REKAPITULASI AKUN
                </span>

                <div class="flex flex-col items-center py-4">
                    <div class="relative group cursor-pointer w-28 h-28 mx-auto" onclick="document.getElementById('avatar-input').click()">
                        <img id="avatar-preview" 
                            src="{{ auth()->user()->avatarUrl() }}"
                            alt="{{ $user->name }}"
                            class="w-28 h-28 border-4 border-indigo-50 dark:border-zinc-800 shadow-sm transition-all group-hover:scale-105 duration-300 object-cover">
                        <div
                            class="absolute inset-0 bg-black/40 flex items-center justify-center text-[10px] text-white opacity-0 group-hover:opacity-100 transition-opacity font-mono cursor-pointer">
                            Ganti Avatar
                        </div>
                    </div>
                    
                    <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                    
                    @error('avatar')
                        <span class="text-[10px] text-rose-500 block mt-2 text-center">{{ $message }}</span>
                    @enderror

                    <h2 class="text-base font-extrabold text-slate-800 dark:text-white mt-4 tracking-tight leading-tight">
                        {{ $user->name }}
                    </h2>
                    <span class="text-xs font-mono font-bold text-[#4f45b2] dark:text-indigo-400 mt-1 uppercase">
                        @foreach ($user->roles as $role)
                            {{ $role->name }}{{ !$loop->last ? ' | ' : '' }}
                        @endforeach
                    </span>
                </div>

                <!-- Meta statistics -->
                <div class="space-y-2 text-left text-xs border-t border-slate-100 dark:border-zinc-800 pt-4">
                    <div class="flex justify-between py-1.5 border-b border-slate-50 dark:border-zinc-800/50">
                        <span class="text-slate-500">Email Utama</span>
                        <span class="font-bold text-slate-800 dark:text-zinc-200 truncate max-w-[150px]"
                            title="{{ $user->email }}">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50 dark:border-zinc-800/50">
                        <span class="text-slate-500">Tanggal Terdaftar</span>
                        <span
                            class="font-bold text-slate-800 dark:text-zinc-200 font-mono text-[10px]">{{ $user->created_at?->format('d M Y') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50 dark:border-zinc-800/50">
                        <span class="text-slate-500">IP Terhubung</span>
                        <span
                            class="font-bold text-slate-800 dark:text-zinc-200 font-mono text-[10px]">{{ request()->ip() }}</span>
                    </div>
                </div>
            </div>

            <!-- Right: Edit Form Card -->
            <div
                class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm space-y-6">
                <div class="space-y-6">

                    <!-- Section 1: Personal Data -->
                    <div>
                        <h3
                            class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 border-b border-slate-100 dark:border-zinc-800 pb-2 mb-4">
                            Informasi Profil Pribadi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name input -->
                            <div class="space-y-1.5">
                                <label for="name" class="text-xs font-bold text-slate-800 dark:text-zinc-200">Nama
                                    Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                    required
                                    class="w-full text-xs rounded-none border-slate-350 dark:border-zinc-700 bg-white dark:bg-zinc-950 text-slate-800 dark:text-zinc-100 focus:ring-[#4f45b2]/45 focus:border-[#4f45b2] p-2.5">
                                @error('name')
                                    <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email input -->
                            <div class="space-y-1.5">
                                <label for="email" class="text-xs font-bold text-slate-800 dark:text-zinc-200">Alamat
                                    Email</label>
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $user->email) }}" required
                                    class="w-full text-xs rounded-none border-slate-350 dark:border-zinc-700 bg-white dark:bg-zinc-950 text-slate-800 dark:text-zinc-100 focus:ring-[#4f45b2]/45 focus:border-[#4f45b2] p-2.5">
                                @error('email')
                                    <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Password Update -->
                    <div>
                        <h3
                            class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 border-b border-slate-100 dark:border-zinc-800 pb-2 mb-3">
                            Ganti Kata Sandi (Keamanan)
                        </h3>
                        <p
                            class="text-[10px] text-slate-500 dark:text-zinc-400 mb-4 bg-slate-50 dark:bg-zinc-950 p-2.5 border border-slate-200/50 dark:border-zinc-800">
                            * Biarkan kolom-kolom kata sandi di bawah ini <strong>KOSONG</strong> jika Anda tidak berniat
                            melakukan penggantian kata sandi Anda saat ini.
                        </p>

                        <div class="space-y-4">
                            <!-- Current Password -->
                            <div class="space-y-1.5">
                                <label for="current_password"
                                    class="text-xs font-bold text-slate-800 dark:text-zinc-200">Kata Sandi Saat Ini</label>
                                <input type="password" name="current_password" id="current_password"
                                    placeholder="Ketik password lama Anda untuk konfirmasi perubahan"
                                    class="w-full text-xs rounded-none border-slate-350 dark:border-zinc-700 bg-white dark:bg-zinc-950 text-slate-800 dark:text-zinc-100 focus:ring-[#4f45b2]/45 focus:border-[#4f45b2] p-2.5">
                                @error('current_password')
                                    <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- New Password -->
                                <div class="space-y-1.5">
                                    <label for="new_password"
                                        class="text-xs font-bold text-slate-800 dark:text-zinc-200">Kata Sandi Baru</label>
                                    <input type="password" name="new_password" id="new_password"
                                        placeholder="Minimal 8 karakter"
                                        class="w-full text-xs rounded-none border-slate-350 dark:border-zinc-700 bg-white dark:bg-zinc-950 text-slate-800 dark:text-zinc-100 focus:ring-[#4f45b2]/45 focus:border-[#4f45b2] p-2.5">
                                    @error('new_password')
                                        <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Confirm New Password -->
                                <div class="space-y-1.5">
                                    <label for="new_password_confirmation"
                                        class="text-xs font-bold text-slate-800 dark:text-zinc-200">Konfirmasi Kata Sandi
                                        Baru</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                        placeholder="Ulangi kata sandi baru"
                                        class="w-full text-xs rounded-none border-slate-350 dark:border-zinc-700 bg-white dark:bg-zinc-950 text-slate-800 dark:text-zinc-100 focus:ring-[#4f45b2]/45 focus:border-[#4f45b2] p-2.5">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-100 dark:border-zinc-800 flex justify-end">
                        <button type="submit"
                            class="py-2.5 px-6 bg-[#4f45b2] hover:bg-indigo-700 text-white font-mono font-bold text-xs uppercase tracking-wider transition-all active:scale-[.98]">
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
