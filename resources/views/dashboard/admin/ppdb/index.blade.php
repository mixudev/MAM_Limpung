@extends('dashboard.layouts.main')

@section('content')
<!-- Custom Breadcrumb Override -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'PPDB Siswa';
        }
    });
</script>

<div class="space-y-6">

    <!-- 1. Header & Filter Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white leading-tight">Penerimaan Peserta Didik Baru (PPDB)</h1>
            <p class="text-sm text-slate-500 dark:text-zinc-400 mt-1">Kelola dan verifikasi pendaftaran calon siswa MAM Limpung secara tersentralisasi.</p>
        </div>

        <!-- Filter Tahun Pelajaran -->
        <form action="{{ route('admin.ppdb.index') }}" method="GET" id="filterForm" class="flex flex-wrap items-center gap-3">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="flex flex-col">
                <label for="tahun_ajaran" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Tahun Pelajaran</label>
                <select name="tahun_ajaran" id="tahun_ajaran" onchange="this.form.submit()" 
                    class="bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3.5 pr-8 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ $selectedYear === $yr ? 'selected' : '' }}>
                            {{ $yr }}/{{ $yr + 1 }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- 2. KPI Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Applicants -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none relative overflow-hidden shadow-sm">
            <div class="absolute left-0 top-0 bottom-0 w-[4px] bg-[#4f45b2]"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Total Pendaftar</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1 leading-none">{{ $stats['total'] }}</h3>
                </div>
                <div class="p-3 bg-[#4f45b2]/10 text-[#4f45b2] dark:text-[#8c84c8] rounded-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-400 dark:text-zinc-500 mt-4 font-mono">Calon Siswa Terdaftar</p>
        </div>

        <!-- Pending Queue -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none relative overflow-hidden shadow-sm">
            <div class="absolute left-0 top-0 bottom-0 w-[4px] bg-amber-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Menunggu Verifikasi</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1 leading-none text-amber-600 dark:text-amber-500">{{ $stats['pending'] }}</h3>
                </div>
                <div class="p-3 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-400 dark:text-zinc-500 mt-4 font-mono">Perlu Penilaian Segera</p>
        </div>

        <!-- Verified -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none relative overflow-hidden shadow-sm">
            <div class="absolute left-0 top-0 bottom-0 w-[4px] bg-emerald-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Terverifikasi</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1 leading-none text-emerald-600 dark:text-emerald-500">{{ $stats['verified'] }}</h3>
                </div>
                <div class="p-3 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-400 dark:text-zinc-500 mt-4 font-mono">Telah Diterima Masuk</p>
        </div>

        <!-- Target Quota -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none relative overflow-hidden shadow-sm">
            <div class="absolute left-0 top-0 bottom-0 w-[4px] bg-blue-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Target Kuota PPDB</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1 leading-none text-blue-600 dark:text-blue-400">{{ $stats['quota_percent'] }}%</h3>
                </div>
                <div class="p-3 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center justify-between text-xs text-slate-400 dark:text-zinc-500 mb-1 font-mono">
                    <span>Terpenuhi: {{ $stats['verified'] }}/{{ $stats['quota_target'] }}</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-zinc-800 h-1.5 rounded-none overflow-hidden">
                    <div class="bg-blue-500 h-full transition-all duration-500" style="width: {{ $stats['quota_percent'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Dual Column: Statistics & Distributions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- School Origin Distribution -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none shadow-sm lg:col-span-2">
            <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-4">Top 5 Sekolah Asal Pendaftar</h3>
            <div class="space-y-4">
                @forelse($distributions['top_schools'] as $school)
                    @php
                        $percentage = $stats['total'] > 0 ? round(($school->count / $stats['total']) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm text-slate-700 dark:text-zinc-300 font-medium mb-1">
                            <span class="truncate">{{ $school->sekolah_asal }}</span>
                            <span class="font-mono text-xs">{{ $school->count }} Siswa ({{ $percentage }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-zinc-800 h-2 rounded-none overflow-hidden">
                            <div class="bg-[#4f45b2] h-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 dark:text-zinc-500 text-sm">Tidak ada data sekolah asal.</div>
                @endforelse
            </div>
        </div>

        <!-- Gender & Size Distribution Info -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-4">Metrik Distribusi Siswa</h3>
                
                <!-- Gender -->
                <div class="mb-5">
                    <p class="text-xs font-semibold text-slate-500 dark:text-zinc-400 mb-2 font-mono uppercase tracking-wider">Jenis Kelamin</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 dark:bg-zinc-800/50 p-3 border border-slate-100 dark:border-zinc-800/80 text-center rounded-none">
                            <span class="text-xs text-slate-400 dark:text-zinc-500 font-medium block">Laki-laki</span>
                            <span class="text-xl font-bold text-slate-800 dark:text-white font-mono mt-0.5 block">{{ $distributions['gender']['L'] }}</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-zinc-800/50 p-3 border border-slate-100 dark:border-zinc-800/80 text-center rounded-none">
                            <span class="text-xs text-slate-400 dark:text-zinc-500 font-medium block">Perempuan</span>
                            <span class="text-xl font-bold text-slate-800 dark:text-white font-mono mt-0.5 block">{{ $distributions['gender']['P'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Sizes -->
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-zinc-400 mb-2 font-mono uppercase tracking-wider">Distribusi Ukuran Seragam</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($distributions['sizes'] as $sz => $cnt)
                            <div class="px-2.5 py-1 text-xs font-mono border border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-800/40 text-slate-600 dark:text-zinc-400 rounded-none flex items-center gap-1.5">
                                <span class="font-bold text-[#4f45b2] dark:text-[#8c84c8]">{{ $sz }}</span>
                                <span class="bg-white dark:bg-zinc-700/50 px-1 border border-slate-200/50 dark:border-zinc-800 rounded-none text-[10px] font-bold">{{ $cnt }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Interactive Data Table & Actions -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm overflow-hidden">
        
        <!-- Table Filters -->
        <div class="p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50">
            <form action="{{ route('admin.ppdb.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="hidden" name="tahun_ajaran" value="{{ $selectedYear }}">

                <!-- Search Input -->
                <div class="relative md:col-span-2">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NISN, registrasi, sekolah..."
                        class="w-full pl-9 pr-4 py-2 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" onchange="this.form.submit()"
                        class="w-full py-2 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima (Terverifikasi)</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-sm rounded-none tracking-wide transition-all active:scale-[.98]">
                        Terapkan
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.ppdb.index', ['tahun_ajaran' => $selectedYear]) }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-600 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-bold text-sm rounded-none text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-zinc-800/40 border-b border-slate-100 dark:border-zinc-800/80">
                        <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Nomor Reg</th>
                        <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Nama Lengkap / NISN</th>
                        <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Sekolah Asal</th>
                        <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Status</th>
                        <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Tanggal Daftar</th>
                        <th class="px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/50">
                    @forelse($applicants as $student)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-zinc-800/20 transition-all">
                            <td class="px-6 py-4 text-sm font-mono font-bold text-[#4f45b2] dark:text-[#8c84c8] whitespace-nowrap">
                                {{ $student->nomor_registrasi }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $student->nama_lengkap }}</div>
                                <div class="text-xs text-slate-400 dark:text-zinc-500 font-mono mt-0.5">NISN: {{ $student->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-zinc-400 whitespace-nowrap">
                                {{ $student->sekolah_asal }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->status === 'diterima')
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-none bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">
                                        Terverifikasi
                                    </span>
                                @elseif($student->status === 'ditolak')
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-none bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30" title="{{ $student->catatan_admin }}">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-none bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-500 dark:text-zinc-500 whitespace-nowrap">
                                {{ $student->submitted_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Detail Action -->
                                    <button type="button" onclick="openDetails('{{ $student->id }}')" 
                                        class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all">
                                        Detail
                                    </button>

                                    @if($student->status === 'pending')
                                        <!-- Verify Action Form -->
                                        <form action="{{ route('admin.ppdb.verify', $student->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" onclick="confirmVerification(event, '{{ $student->nama_lengkap }}')"
                                                class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-none transition-all active:scale-[.98]">
                                                Verifikasi
                                            </button>
                                        </form>

                                        <!-- Reject Action Modal Trigger -->
                                        <button type="button" onclick="openRejectionModal('{{ $student->id }}', '{{ $student->nama_lengkap }}')"
                                            class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white font-bold text-xs rounded-none transition-all active:scale-[.98]">
                                            Tolak
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400 dark:text-zinc-500 text-sm">
                                Tidak ada data calon siswa untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($applicants->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/35 dark:bg-zinc-900/10">
                {{ $applicants->links() }}
            </div>
        @endif
    </div>

</div>

<!-- 5. Split Drawer Slide-Over Detail -->
<div id="detailDrawer" class="fixed inset-0 z-50 overflow-hidden hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-xs transition-opacity duration-300 opacity-0" id="drawerBackdrop" onclick="closeDetails()"></div>
    <div class="absolute inset-y-0 right-0 max-w-full flex pl-10">
        <div id="drawerContent" class="w-screen max-w-lg bg-white dark:bg-zinc-900 border-l border-slate-200 dark:border-zinc-800 shadow-2xl p-6 flex flex-col justify-between translate-x-full transition-transform duration-300 rounded-none relative">
            
            <!-- X Close Button -->
            <button type="button" onclick="closeDetails()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-none transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Loading overlay -->
            <div id="drawerLoading" class="absolute inset-0 bg-white/90 dark:bg-zinc-900/90 z-10 flex flex-col items-center justify-center gap-3">
                <svg class="animate-spin h-8 w-8 text-[#4f45b2]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs font-mono text-slate-400 dark:text-zinc-500">Memuat berkas siswa...</span>
            </div>

            <!-- Drawer Scrollable Content -->
            <div class="flex-1 overflow-y-auto pr-1 space-y-6">
                <!-- Top Header Card -->
                <div class="flex items-center gap-4 border-b border-slate-100 dark:border-zinc-800 pb-5">
                    <div class="w-16 h-16 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 overflow-hidden flex-shrink-0">
                        <img id="d_foto" src="" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <div class="overflow-hidden">
                        <span id="d_status" class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded-none uppercase tracking-wider mb-1.5"></span>
                        <h2 id="d_nama" class="text-lg font-bold text-slate-900 dark:text-white truncate"></h2>
                        <p id="d_nomor_registrasi" class="text-xs font-mono font-bold text-[#4f45b2] dark:text-[#8c84c8]"></p>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="space-y-5 text-sm">
                    <!-- Data Diri -->
                    <div>
                        <h4 class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2.5">Data Diri Calon Siswa</h4>
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-zinc-950 p-4 border border-slate-100 dark:border-zinc-800/80">
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">NISN</span>
                                <span id="d_nisn" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Jenis Kelamin</span>
                                <span id="d_gender" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Tempat, Tanggal Lahir</span>
                                <span id="d_ttl" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Alamat Lengkap</span>
                                <span id="d_alamat" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Kontak & Sekolah -->
                    <div>
                        <h4 class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2.5">Kontak & Akademik</h4>
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-zinc-950 p-4 border border-slate-100 dark:border-zinc-800/80">
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Sekolah Asal</span>
                                <span id="d_sekolah" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Ukuran Baju</span>
                                <span id="d_ukuran" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Nomor HP/WhatsApp</span>
                                <span id="d_hp" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Email Utama</span>
                                <span id="d_email" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Wali Orang Tua -->
                    <div>
                        <h4 class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2.5">Informasi Orang Tua</h4>
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-zinc-950 p-4 border border-slate-100 dark:border-zinc-800/80">
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Nama Ayah</span>
                                <span id="d_ayah" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Nama Ibu</span>
                                <span id="d_ibu" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Verifikasi -->
                    <div id="d_notes_section" class="hidden">
                        <h4 class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2.5">Catatan/Alasan Verifikator</h4>
                        <div class="bg-red-50/50 dark:bg-red-950/10 p-4 border border-red-100 dark:border-red-950/20 text-red-800 dark:text-red-400">
                            <span id="d_notes" class="font-medium text-xs"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Drawer Bottom Action Form -->
            <div id="drawerActions" class="border-t border-slate-100 dark:border-zinc-800 pt-4 mt-4 hidden">
                <div class="flex items-center gap-2">
                    <form id="drawerVerifyForm" action="" method="POST" class="flex-1 inline">
                        @csrf
                        <button type="submit" id="drawerVerifyBtn" class="w-full py-2.5 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-none transition-all active:scale-[.98] text-center">
                            Verifikasi Sekarang
                        </button>
                    </form>
                    <button type="button" id="drawerRejectBtn" class="flex-1 py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white font-bold text-xs rounded-none transition-all active:scale-[.98] text-center">
                        Tolak Pendaftaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 6. Custom Modal Rejection Reason -->
<div id="rejectionModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs transition-opacity" onclick="closeRejectionModal()"></div>
        
        <div class="inline-block w-full max-w-md bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-2xl p-6 text-left transform transition-all rounded-none relative z-10">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2" id="rejectModalTitle">Tolak Pendaftaran</h3>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mb-4">Berikan catatan alasan yang valid mengapa berkas calon siswa ini ditolak. Alasan ini akan dibaca oleh siswa di laman cek status.</p>
            
            <form id="rejectionForm" action="" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="catatan_admin" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1">Alasan Penolakan</label>
                    <textarea name="catatan_admin" id="catatan_admin" rows="4" placeholder="Contoh: NISN tidak valid / Dokumen pas foto tidak buram dan jelas..." required
                        class="w-full p-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-red-400/20 focus:border-red-500"></textarea>
                </div>

                <div class="flex items-center gap-2.5 pt-2">
                    <button type="button" onclick="closeRejectionModal()" class="flex-1 py-2.5 px-4 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-300 font-bold text-xs rounded-none transition-all active:scale-[.98]">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white font-bold text-xs rounded-none transition-all active:scale-[.98]">
                        Tolak & Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Interactivity -->
<script>
    // ════════════ 1. AppPopup Confirmation Trigger ════════════
    function confirmVerification(e, studentName) {
        e.preventDefault();
        const form = e.target.closest('form');
        
        AppPopup.confirm({
            title: 'Verifikasi Calon Siswa',
            description: `Apakah Anda yakin ingin menyetujui dan memverifikasi pendaftaran dari <strong>${studentName}</strong>?`,
            confirmText: 'Ya, Setujui',
            cancelText: 'Batal',
            onConfirm: () => {
                form.submit();
            }
        });
    }

    // ════════════ 2. Slide-over Detail Drawer Controller ════════════
    function openDetails(studentId) {
        const drawer = document.getElementById('detailDrawer');
        const backdrop = document.getElementById('drawerBackdrop');
        const content = document.getElementById('drawerContent');
        const loading = document.getElementById('drawerLoading');

        // Reset forms
        document.getElementById('drawerVerifyForm').action = `/admin/ppdb/${studentId}/verify`;
        document.getElementById('drawerActions').classList.add('hidden');
        document.getElementById('d_notes_section').classList.add('hidden');

        // Show drawer shell
        drawer.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            content.classList.remove('translate-x-full');
            content.classList.add('translate-x-0');
        }, 10);

        loading.classList.remove('hidden');

        // Fetch detail candidate data
        fetch(`/admin/ppdb/${studentId}`)
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const d = res.data;
                    document.getElementById('d_foto').src = d.foto_url;
                    document.getElementById('d_nama').textContent = d.nama_lengkap;
                    document.getElementById('d_nomor_registrasi').textContent = d.nomor_registrasi;
                    document.getElementById('d_nisn').textContent = d.nisn;
                    document.getElementById('d_gender').textContent = d.jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan';
                    document.getElementById('d_ttl').textContent = `${d.tempat_lahir}, ${d.formatted_dob}`;
                    document.getElementById('d_alamat').textContent = d.alamat_lengkap;
                    document.getElementById('d_sekolah').textContent = d.sekolah_asal;
                    document.getElementById('d_ukuran').textContent = d.ukuran_baju;
                    document.getElementById('d_hp').textContent = d.nomor_hp;
                    document.getElementById('d_email').textContent = d.email;
                    document.getElementById('d_ayah').textContent = d.nama_ayah;
                    document.getElementById('d_ibu').textContent = d.nama_ibu;

                    // Status label setup
                    const statusSpan = document.getElementById('d_status');
                    statusSpan.textContent = d.status_label;
                    statusSpan.className = `inline-flex px-2 py-0.5 text-[10px] font-bold rounded-none uppercase tracking-wider mb-1.5 `;
                    if (d.status === 'diterima') {
                        statusSpan.className += 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                    } else if (d.status === 'ditolak') {
                        statusSpan.className += 'bg-red-50 text-red-600 border border-red-100';
                        document.getElementById('d_notes').textContent = d.catatan_admin;
                        document.getElementById('d_notes_section').classList.remove('hidden');
                    } else {
                        statusSpan.className += 'bg-amber-50 text-amber-600 border border-amber-100';
                        
                        // Setup action buttons inside slide-over
                        document.getElementById('drawerVerifyBtn').onclick = (e) => confirmVerification(e, d.nama_lengkap);
                        document.getElementById('drawerRejectBtn').onclick = () => {
                            closeDetails();
                            openRejectionModal(d.id, d.nama_lengkap);
                        };
                        document.getElementById('drawerActions').classList.remove('hidden');
                    }
                }
            })
            .catch(err => {
                console.error("Gagal memuat detail pendaftar:", err);
                closeDetails();
            })
            .finally(() => {
                loading.classList.add('hidden');
            });
    }

    function closeDetails() {
        const backdrop = document.getElementById('drawerBackdrop');
        const content = document.getElementById('drawerContent');
        const drawer = document.getElementById('detailDrawer');

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        content.classList.remove('translate-x-0');
        content.classList.add('translate-x-full');

        setTimeout(() => {
            drawer.classList.add('hidden');
        }, 300);
    }

    // ════════════ 3. Rejection Modal Controller ════════════
    function openRejectionModal(studentId, studentName) {
        document.getElementById('rejectModalTitle').innerHTML = `Tolak Pendaftaran <strong>${studentName}</strong>`;
        document.getElementById('rejectionForm').action = `/admin/ppdb/${studentId}/reject`;
        document.getElementById('catatan_admin').value = '';
        
        const modal = document.getElementById('rejectionModal');
        modal.classList.remove('hidden');
    }

    function closeRejectionModal() {
        const modal = document.getElementById('rejectionModal');
        modal.classList.add('hidden');
    }
</script>
@endsection
