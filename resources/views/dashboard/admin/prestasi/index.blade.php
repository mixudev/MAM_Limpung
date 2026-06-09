@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Daftar Prestasi';
        }
    });
</script>

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Kelola Prestasi Sekolah</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Kelola data prestasi, penghargaan, dan piagam yang diraih oleh siswa maupun tim MA Muhammadiyah Limpung.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            {{-- <a href="{{ route('admin.prestasi.template') }}" class="py-2.5 px-4 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-none transition-all tracking-wider flex items-center justify-center gap-2 font-mono">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                TEMPLATE
            </a> --}}
            <a href="{{ route('admin.prestasi.import.page') }}" class="py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-none transition-all tracking-wider flex items-center justify-center gap-2 font-mono">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                IMPORT
            </a>
            <a href="{{ route('admin.prestasi.create') }}" class="py-2.5 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider flex items-center justify-center gap-2 font-mono">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                TAMBAH DATA
            </a>
        </div>
    </div>

    <!-- Flash Messages & Import Errors -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/60 p-4 text-emerald-800 dark:text-emerald-400 text-xs font-semibold rounded-none flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button class="text-emerald-600 hover:text-emerald-800 dark:hover:text-white font-bold text-sm" onclick="this.parentElement.remove()">
                &times;
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/60 p-4 text-rose-800 dark:text-rose-400 text-xs font-semibold rounded-none">
            <div class="flex items-center justify-between mb-2">
                <span class="font-bold">Terjadi Kesalahan:</span>
                <button class="text-rose-600 hover:text-rose-850 dark:hover:text-white font-bold text-sm" onclick="this.parentElement.parentElement.remove()">
                    &times;
                </button>
            </div>
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('import_errors'))
        <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/60 p-4 text-amber-800 dark:text-amber-400 text-xs font-semibold rounded-none max-h-60 overflow-y-auto">
            <div class="flex items-center justify-between mb-2">
                <span class="font-bold">Laporan Kesalahan Impor (Baris dilewati):</span>
                <button class="text-amber-600 hover:text-amber-850 dark:hover:text-white font-bold text-sm" onclick="this.parentElement.parentElement.remove()">
                    &times;
                </button>
            </div>
            <ul class="list-disc pl-4 space-y-1 font-mono text-[11px]">
                @foreach(session('import_errors') as $impErr)
                    <li>{{ $impErr }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm">
        <form action="{{ route('admin.prestasi.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Cari Prestasi</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, peraih, penyelenggara..." 
                    class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]" />
            </div>

            <!-- Tingkat -->
            <div>
                <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Tingkat</label>
                <select name="tingkat" class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                    <option value="">Semua Tingkat</option>
                    <option value="sekolah" {{ request('tingkat') === 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                    <option value="kabupaten" {{ request('tingkat') === 'kabupaten' ? 'selected' : '' }}>Kabupaten/Kota</option>
                    <option value="provinsi" {{ request('tingkat') === 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                    <option value="nasional" {{ request('tingkat') === 'nasional' ? 'selected' : '' }}>Nasional</option>
                    <option value="internasional" {{ request('tingkat') === 'internasional' ? 'selected' : '' }}>Internasional</option>
                </select>
            </div>

            <!-- Jenis -->
            <div>
                <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Jenis</label>
                <select name="jenis" class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                    <option value="">Semua Jenis</option>
                    <option value="akademik" {{ request('jenis') === 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="non_akademik" {{ request('jenis') === 'non_akademik' ? 'selected' : '' }}>Non-Akademik</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 bg-slate-950 dark:bg-zinc-800 hover:bg-slate-900 text-white dark:text-zinc-300 font-bold text-xs rounded-none transition-all font-mono tracking-wider">
                    FILTER
                </button>
                @if(request()->anyFilled(['search', 'tingkat', 'jenis']))
                    <a href="{{ route('admin.prestasi.index') }}" class="w-full py-2 text-center bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-bold text-xs rounded-none transition-all font-mono tracking-wider">
                        RESET
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider font-mono">Daftar Rekaman Prestasi</h3>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.prestasi.export.excel', request()->query()) }}" class="inline-flex py-1.5 px-3 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 font-bold text-[10px] uppercase font-mono tracking-wider">
                    EXPORT EXCEL
                </a>
                <a href="{{ route('admin.prestasi.export.pdf', request()->query()) }}" target="_blank" class="inline-flex py-1.5 px-3 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 font-bold text-[10px] uppercase font-mono tracking-wider">
                    EXPORT PDF
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto border border-slate-100 dark:border-zinc-800">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-800 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                        <th class="py-3.5 px-4 w-16 text-center">Foto</th>
                        <th class="py-3.5 px-4">Judul & Peraih</th>
                        <th class="py-3.5 px-4 w-28 text-center">Tingkat</th>
                        <th class="py-3.5 px-4 w-28 text-center">Jenis</th>
                        <th class="py-3.5 px-4 w-28">Juara</th>
                        <th class="py-3.5 px-4 w-20 text-center">Tahun</th>
                        <th class="py-3.5 px-4 w-24 text-center">Utama</th>
                        <th class="py-3.5 px-4 w-36 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                    @forelse($prestasis as $pres)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="py-3 px-4">
                                <div class="w-12 h-9 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-800 overflow-hidden flex items-center justify-center">
                                    @if($pres->foto)
                                        <img src="{{ asset('storage/' . $pres->foto) }}" class="w-full h-full object-cover" alt="Foto">
                                    @else
                                        <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-800 dark:text-zinc-300 leading-snug">{{ $pres->judul }}</div>
                                <div class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1 font-mono uppercase tracking-wider">Peraih: {{ $pres->peraih }}</div>
                                @if($pres->penyelenggara)
                                    <div class="text-[9px] text-slate-400 italic">Penyelenggara: {{ $pres->penyelenggara }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase border 
                                    @if($pres->tingkat === 'internasional') bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/20 dark:text-purple-400 dark:border-purple-800/40
                                    @elseif($pres->tingkat === 'nasional') bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-800/40
                                    @elseif($pres->tingkat === 'provinsi') bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-800/40
                                    @elseif($pres->tingkat === 'kabupaten') bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-800/40
                                    @else bg-slate-50 text-slate-700 border-slate-200 dark:bg-zinc-800 dark:text-zinc-400 dark:border-zinc-700
                                    @endif">
                                    {{ $pres->tingkatLabel() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase border
                                    @if($pres->jenis === 'akademik') bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-800/40
                                    @else bg-zinc-100 text-zinc-700 border-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:border-zinc-700
                                    @endif">
                                    {{ $pres->jenis === 'akademik' ? 'Akademik' : 'Non-Akad' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-semibold text-slate-700 dark:text-zinc-300">
                                {{ $pres->juara ?: '-' }}
                            </td>
                            <td class="py-3 px-4 text-center text-slate-500 dark:text-zinc-400 font-mono">
                                {{ $pres->tahun }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($pres->is_featured)
                                    <span class="inline-flex w-2.5 h-2.5 bg-amber-500 rounded-full" title="Featured"></span>
                                @else
                                    <span class="inline-flex w-2.5 h-2.5 bg-slate-200 dark:bg-zinc-700 rounded-full" title="Standard"></span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.prestasi.edit', $pres->id) }}" 
                                   class="inline-block py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider">
                                    Edit
                                </a>
                                <form action="{{ route('admin.prestasi.destroy', $pres->id) }}" method="POST" class="inline" id="delete-form-{{ $pres->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                    type="button"
                                    onclick="AppPopup.confirm({
                                                title: 'Peringatan',
                                                description: 'Apakah Anda yakin ingin menghapus data prestasi ini?',
                                                confirmText: 'Ya, Hapus',
                                                cancelText: 'Batal',
                                                onConfirm: function() {
                                                    document.getElementById('delete-form-{{ $pres->id }}').submit();
                                                }
                                            })
                                            "
                                    class="py-1 px-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] uppercase font-mono tracking-wider">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 dark:text-zinc-500 italic">
                                Tidak ada data prestasi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-2">
            {{ $prestasis->links() }}
        </div>
    </div>
</div>
@endsection
