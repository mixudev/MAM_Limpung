@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Dashboard';
        }
    });
</script>

<div class="max-w-6xl space-y-6">
    <!-- Welcome Header Banner -->
    <div class="bg-gradient-to-r from-[#4f45b2] to-indigo-700 p-8 text-white shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <!-- Absolute decorative circles -->
        <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-32 -bottom-20 w-32 h-32 bg-indigo-500/20 rounded-full blur-xl"></div>
        
        <div class="relative z-10 space-y-2">
            <span class="px-2.5 py-1 text-[10px] font-mono font-bold bg-white/20 uppercase tracking-wider">
                Overview Panel
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                Selamat Datang Kembali, {{ $user->name }}!
            </h1>
            <p class="text-xs text-indigo-100 font-mono">
                Anda masuk sebagai peran: 
                @foreach ($roles as $role)
                    <span class="underline font-bold">{{ strtoupper($role) }}</span>{{ !$loop->last ? ',' : '' }}
                @endforeach
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-3 bg-white/10 backdrop-blur-md p-4 border border-white/10 font-mono text-xs">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></div>
            <div>
                <span class="block text-[10px] text-white/60">STATUS SISTEM</span>
                <span class="font-bold uppercase tracking-wider">Aktif & Sinkron</span>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Account Info -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                    Informasi Akun
                </span>
                <div class="mt-4 space-y-2 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-zinc-800">
                        <span class="text-slate-500">Email</span>
                        <span class="font-bold text-slate-800 dark:text-zinc-200">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-zinc-800">
                        <span class="text-slate-500">Tingkat Hak Akses</span>
                        <span class="font-bold text-indigo-600 dark:text-indigo-400">Level {{ $user->roles->sortByDesc('level')->first()?->level ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800 text-[10px] font-mono text-slate-400 dark:text-zinc-500">
                Terdaftar sejak: {{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}
            </div>
        </div>

        <!-- Session Status -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                    Metadata Sesi Terakhir
                </span>
                <div class="mt-4 space-y-2 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-zinc-800">
                        <span class="text-slate-500">Waktu Login</span>
                        <span class="font-bold text-slate-800 dark:text-zinc-200">{{ $user->last_login_at?->diffForHumans() ?? 'Sesi saat ini' }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-zinc-800">
                        <span class="text-slate-500">Alamat IP</span>
                        <span class="font-mono font-bold text-slate-800 dark:text-zinc-200">{{ $user->last_login_ip ?? '127.0.0.1' }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800 text-[10px] font-mono text-slate-400 dark:text-zinc-500">
                Browser: Laravel Unified Session
            </div>
        </div>

        <!-- System Settings Quick Access -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                    Aktivitas & Logika
                </span>
                <div class="mt-4 space-y-2 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-zinc-800">
                        <span class="text-slate-500">Total Izin Aktif</span>
                        <span class="font-bold text-slate-800 dark:text-zinc-200 font-mono">{{ $permissions->count() }} Izin</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-zinc-800">
                        <span class="text-slate-500">Status Akun</span>
                        <span class="px-2 py-0.5 text-[10px] font-mono font-bold bg-emerald-100 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-400">AKTIF</span>
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800 text-[10px] font-mono text-slate-400 dark:text-zinc-500">
                Log Keamanan: Sinkron
            </div>
        </div>
    </div>

    <!-- Quick Navigation Cards -->
    <div class="space-y-4">
        <h2 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
            Jalan Pintas Akses Fitur
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- PPDB Link -->
            @can('view-ppdb')
            <a href="{{ route('admin.ppdb.index') }}" class="group bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-5 shadow-sm hover:border-[#4f45b2] dark:hover:border-indigo-500 transition-all flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-[#4f45b2] dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-200 mt-4 uppercase font-mono tracking-wider">
                        Pendaftar PPDB
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1">
                        Kelola data calon siswa, status pembayaran, verifikasi, dan ekspor.
                    </p>
                </div>
                <span class="text-[10px] font-mono font-bold text-indigo-600 dark:text-indigo-400 mt-4 group-hover:translate-x-1.5 transition-transform flex items-center gap-1">
                    Buka Fitur &rarr;
                </span>
            </a>
            @endcan

            <!-- Pengumuman Link -->
            @can('view-announcements')
            <a href="{{ route('admin.announcements.index') }}" class="group bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-5 shadow-sm hover:border-[#4f45b2] dark:hover:border-indigo-500 transition-all flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-[#4f45b2] dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-200 mt-4 uppercase font-mono tracking-wider">
                        Pengumuman & Alert
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1">
                        Atur banner sapaan dan popup pemberitahuan di halaman frontend.
                    </p>
                </div>
                <span class="text-[10px] font-mono font-bold text-indigo-600 dark:text-indigo-400 mt-4 group-hover:translate-x-1.5 transition-transform flex items-center gap-1">
                    Buka Fitur &rarr;
                </span>
            </a>
            @endcan

            <!-- User Accounts Link -->
            @can('view-users')
            <a href="{{ Auth::user()->hasRole('super-admin') ? route('super-admin.users.index') : route('admin.users.index') }}" class="group bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-5 shadow-sm hover:border-[#4f45b2] dark:hover:border-indigo-500 transition-all flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-[#4f45b2] dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-200 mt-4 uppercase font-mono tracking-wider">
                        Kelola Akun User
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1">
                        Manajemen kredensial pengguna, status login, dan penugasan peran (roles).
                    </p>
                </div>
                <span class="text-[10px] font-mono font-bold text-indigo-600 dark:text-indigo-400 mt-4 group-hover:translate-x-1.5 transition-transform flex items-center gap-1">
                    Buka Fitur &rarr;
                </span>
            </a>
            @endcan

            <!-- Web Settings Link -->
            @can('manage-settings')
            <a href="{{ route('admin.settings.edit') }}" class="group bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-5 shadow-sm hover:border-[#4f45b2] dark:hover:border-indigo-500 transition-all flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-[#4f45b2] dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-200 mt-4 uppercase font-mono tracking-wider">
                        Pengaturan Web
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1">
                        Konfigurasi identitas sekolah, media sosial, kontak, dan logo seret-lepas.
                    </p>
                </div>
                <span class="text-[10px] font-mono font-bold text-indigo-600 dark:text-indigo-400 mt-4 group-hover:translate-x-1.5 transition-transform flex items-center gap-1">
                    Buka Fitur &rarr;
                </span>
            </a>
            @endcan
        </div>
    </div>
</div>
@endsection
