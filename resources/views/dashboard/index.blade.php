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
                Beranda Portal Akademik
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                Selamat Datang, {{ $user->name }}!
            </h1>
            <p class="text-xs text-indigo-100 font-medium">
                Senang melihat Anda kembali. Hari ini adalah hari yang baik untuk mengelola perkembangan sekolah.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-3 bg-white/15 backdrop-blur-md p-4 border border-white/10 font-mono text-xs">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></div>
            <div>
                <span class="block text-[9px] text-white/70">WAKTU SISTEM</span>
                <span class="font-bold tracking-wider">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Stats for Teachers -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card: PPDB Overview -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                        PENDAFTARAN SISWA BARU (PPDB)
                    </span>
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-[#4f45b2] dark:text-indigo-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-slate-800 dark:text-white font-mono">
                        {{ $stats['total_ppdb'] ?? 0 }}
                    </span>
                    <span class="text-xs text-slate-500 dark:text-zinc-400 block mt-1">Calon Siswa Terdaftar</span>
                </div>
            </div>
            
            <!-- Quick breakdown badges -->
            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800 grid grid-cols-3 gap-2 text-center text-[10px]">
                <div class="bg-emerald-50 dark:bg-emerald-950/20 p-1.5 border border-emerald-100 dark:border-emerald-900/30">
                    <span class="block font-bold text-emerald-700 dark:text-emerald-400 font-mono">{{ $stats['ppdb_diterima'] ?? 0 }}</span>
                    <span class="text-slate-500 dark:text-zinc-500 text-[9px]">Diterima</span>
                </div>
                <div class="bg-amber-50 dark:bg-amber-950/20 p-1.5 border border-amber-100 dark:border-amber-900/30">
                    <span class="block font-bold text-amber-700 dark:text-amber-400 font-mono">{{ $stats['ppdb_pending'] ?? 0 }}</span>
                    <span class="text-slate-500 dark:text-zinc-500 text-[9px]">Menunggu</span>
                </div>
                <div class="bg-rose-50 dark:bg-rose-950/20 p-1.5 border border-rose-100 dark:border-rose-900/30">
                    <span class="block font-bold text-rose-700 dark:text-rose-400 font-mono">{{ $stats['ppdb_ditolak'] ?? 0 }}</span>
                    <span class="text-slate-500 dark:text-zinc-500 text-[9px]">Ditolak</span>
                </div>
            </div>
        </div>

        <!-- Card: Articles and Publications -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                        PUBLIKASI & ARTIKEL
                    </span>
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-[#4f45b2] dark:text-indigo-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2m2 2a2 2 0 00-2 2m2 5V9a2 2 0 00-2-2h-2m-3-4H5a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7.72 2.92a1 1 0 00.94.94H11a1 1 0 00.94-.94V11a1 1 0 00-.94-.94H9a1 1 0 00-.94.94v1.92z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-slate-800 dark:text-white font-mono">
                        {{ $stats['total_artikel'] ?? 0 }}
                    </span>
                    <span class="text-xs text-slate-500 dark:text-zinc-400 block mt-1">Artikel Kegiatan Terbit</span>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800 text-[11px] text-slate-500 dark:text-zinc-500 flex items-center justify-between">
                <span>Informasi terbit aktif</span>
                <span class="font-bold text-[#4f45b2] dark:text-indigo-400 font-mono">Publik & Wali Murid</span>
            </div>
        </div>

        <!-- Card: School Announcements -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                        PENGUMUMAN SEKOLAH
                    </span>
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-[#4f45b2] dark:text-indigo-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-slate-800 dark:text-white font-mono">
                        {{ $stats['total_pengumuman'] ?? 0 }}
                    </span>
                    <span class="text-xs text-slate-500 dark:text-zinc-400 block mt-1">Pengumuman & Alert Aktif</span>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800 text-[11px] text-slate-500 dark:text-zinc-500 flex items-center justify-between">
                <span>Informasi darurat / penting</span>
                <span class="font-bold text-amber-600 dark:text-amber-400 font-mono">Muncul di Frontend</span>
            </div>
        </div>
    </div>

    <!-- Visual Chart: Elegant Progress Stack for PPDB (CSS Graph) -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm">
        <h2 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-4">
            Grafik Visual Status Calon Siswa (PPDB)
        </h2>

        @php
            $total = $stats['total_ppdb'] ?? 0;
            $diterimaPct = $total > 0 ? round(($stats['ppdb_diterima'] / $total) * 100, 1) : 0;
            $pendingPct = $total > 0 ? round(($stats['ppdb_pending'] / $total) * 100, 1) : 0;
            $ditolakPct = $total > 0 ? round(($stats['ppdb_ditolak'] / $total) * 100, 1) : 0;
        @endphp

        <div class="space-y-4">
            <!-- Graphical Bar Stack -->
            <div class="w-full h-5 bg-slate-100 dark:bg-zinc-800 flex overflow-hidden shadow-inner">
                @if($total > 0)
                    <div style="width: {{ $diterimaPct }}%" class="bg-emerald-500 transition-all duration-500" title="Diterima: {{ $diterimaPct }}%"></div>
                    <div style="width: {{ $pendingPct }}%" class="bg-amber-400 transition-all duration-500" title="Menunggu: {{ $pendingPct }}%"></div>
                    <div style="width: {{ $ditolakPct }}%" class="bg-rose-500 transition-all duration-500" title="Ditolak: {{ $ditolakPct }}%"></div>
                @else
                    <div class="w-full bg-slate-200 dark:bg-zinc-800 flex items-center justify-center text-[10px] text-slate-400 dark:text-zinc-500 font-mono">
                        Belum ada data pendaftar untuk digambarkan
                    </div>
                @endif
            </div>

            <!-- Legend with percentages -->
            <div class="flex flex-wrap items-center justify-between gap-4 text-xs font-medium">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-emerald-500 block"></span>
                        <span class="text-slate-700 dark:text-zinc-300">Terverifikasi / Diterima ({{ $diterimaPct }}%)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-amber-400 block"></span>
                        <span class="text-slate-700 dark:text-zinc-300">Menunggu Review ({{ $pendingPct }}%)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-rose-500 block"></span>
                        <span class="text-slate-700 dark:text-zinc-300">Belum Diterima ({{ $ditolakPct }}%)</span>
                    </div>
                </div>
                <div class="text-[10px] font-mono text-slate-400 dark:text-zinc-500">
                    Total: {{ $total }} Calon Siswa
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Cards (Guru Friendly) -->
    <div class="space-y-4">
        <h2 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
            Jalan Pintas Akses Fitur (Guru & Staf)
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
                        Siswa Baru (PPDB)
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1">
                        Lihat daftar pendaftar, ubah status kelulusan, dan konfirmasi WhatsApp.
                    </p>
                </div>
                <span class="text-[10px] font-mono font-bold text-[#4f45b2] dark:text-indigo-400 mt-4 group-hover:translate-x-1.5 transition-transform flex items-center gap-1">
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
                        Tulis Pengumuman
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1">
                        Buat pengumuman penting, banner sapaan, atau pop-up sekolah.
                    </p>
                </div>
                <span class="text-[10px] font-mono font-bold text-[#4f45b2] dark:text-indigo-400 mt-4 group-hover:translate-x-1.5 transition-transform flex items-center gap-1">
                    Buka Fitur &rarr;
                </span>
            </a>
            @endcan

            <!-- Artikel Link -->
            @can('view-articles')
            <a href="{{ route('admin.articles.index') }}" class="group bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-5 shadow-sm hover:border-[#4f45b2] dark:hover:border-indigo-500 transition-all flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-[#4f45b2] dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2m2 2a2 2 0 00-2 2m2 5V9a2 2 0 00-2-2h-2m-3-4H5a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7.72 2.92a1 1 0 00.94.94H11a1 1 0 00.94-.94V11a1 1 0 00-.94-.94H9a1 1 0 00-.94.94v1.92z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-200 mt-4 uppercase font-mono tracking-wider">
                        Artikel & Berita
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1">
                        Tulis berita sekolah, kegiatan ekstra-kurikuler, dan prestasi siswa.
                    </p>
                </div>
                <span class="text-[10px] font-mono font-bold text-[#4f45b2] dark:text-indigo-400 mt-4 group-hover:translate-x-1.5 transition-transform flex items-center gap-1">
                    Buka Fitur &rarr;
                </span>
            </a>
            @endcan

            <!-- Profile Diri Link -->
            <a href="{{ route('admin.profile.edit') }}" class="group bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-5 shadow-sm hover:border-[#4f45b2] dark:hover:border-indigo-500 transition-all flex flex-col justify-between">
                <div>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-[#4f45b2] dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-200 mt-4 uppercase font-mono tracking-wider">
                        Profil Saya
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1">
                        Lengkapi profil pribadi Anda, perbarui foto profil, dan ubah kata sandi secara aman.
                    </p>
                </div>
                <span class="text-[10px] font-mono font-bold text-[#4f45b2] dark:text-indigo-400 mt-4 group-hover:translate-x-1.5 transition-transform flex items-center gap-1">
                    Buka Fitur &rarr;
                </span>
            </a>
        </div>
    </div>

    <!-- Collapsible Advanced System Details (For Super-Admin / Developers) -->
    @if(Auth::user()->hasAnyRole(['admin', 'super-admin']))
    <details class="group bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 p-4 transition-all">
        <summary class="text-xs font-mono font-bold text-slate-400 group-open:text-slate-600 dark:group-open:text-zinc-300 cursor-pointer list-none flex items-center justify-between select-none">
            <span class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 transform group-open:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
                INFORMASI SISTEM LANJUTAN (KREDENSIAL / SECURITY)
            </span>
            <span class="text-[10px] uppercase tracking-wider font-semibold border border-slate-200 dark:border-zinc-800 px-2 py-0.5 group-open:bg-slate-200 dark:group-open:bg-zinc-800 transition-colors">
                Buka Info
            </span>
        </summary>
        
        <div class="mt-4 pt-4 border-t border-slate-200 dark:border-zinc-800/80 grid grid-cols-1 md:grid-cols-3 gap-6 animate-fadeIn">
            <!-- Account Info -->
            <div class="space-y-2 text-xs">
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400">Metadata Login</span>
                <div class="flex justify-between py-1.5 border-b border-slate-200/50 dark:border-zinc-800">
                    <span class="text-slate-500">Email Utama</span>
                    <span class="font-bold text-slate-800 dark:text-zinc-200">{{ $user->email }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-200/50 dark:border-zinc-800">
                    <span class="text-slate-500">Tingkat Hak Akses</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400">Level {{ $user->roles->sortByDesc('level')->first()?->level ?? 0 }}</span>
                </div>
            </div>

            <!-- Session Status -->
            <div class="space-y-2 text-xs">
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400">Terakhir Terhubung</span>
                <div class="flex justify-between py-1.5 border-b border-slate-200/50 dark:border-zinc-800">
                    <span class="text-slate-500">Waktu Login</span>
                    <span class="font-bold text-slate-800 dark:text-zinc-200">{{ $user->last_login_at?->diffForHumans() ?? 'Sesi saat ini' }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-200/50 dark:border-zinc-800">
                    <span class="text-slate-500">Alamat IP Sesi</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-zinc-200">{{ $user->last_login_ip ?? '127.0.0.1' }}</span>
                </div>
            </div>

            <!-- Active Permissions -->
            <div class="space-y-2 text-xs">
                <span class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400">Sistem & Keamanan</span>
                <div class="flex justify-between py-1.5 border-b border-slate-200/50 dark:border-zinc-800">
                    <span class="text-slate-500">Total Izin Aktif</span>
                    <span class="font-bold text-slate-800 dark:text-zinc-200 font-mono">{{ $permissions->count() }} Izin</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-200/50 dark:border-zinc-800">
                    <span class="text-slate-500">Status Akun</span>
                    <span class="px-2 py-0.5 text-[9px] font-mono font-bold bg-emerald-100 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-400">AKTIF</span>
                </div>
            </div>
        </div>
    </details>
    @endif
</div>
@endsection
