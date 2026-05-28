@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Daftar Galeri Foto';
        }
    });
</script>

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Kelola Galeri Foto</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin'))
                    Kelola, setujui, atau hapus album foto kegiatan madrasah yang diunggah oleh civitas akademika.
                @else
                    Unggah dan kelola foto kegiatan madrasah Anda. Kiriman Anda akan ditinjau oleh Administrator sebelum dipublikasikan.
                @endif
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.galeri.create') }}" class="py-2.5 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider flex items-center justify-center gap-2 font-mono">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                TAMBAH GALERI
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/60 p-4 text-emerald-800 dark:text-emerald-400 text-xs font-semibold rounded-none flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button class="text-emerald-600 hover:text-emerald-800 dark:hover:text-white font-bold text-sm" onclick="this.parentElement.remove()">
                &times;
            </button>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm">
        <form action="{{ route('admin.galeri.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Cari Album</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, kategori..." 
                    class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]" />
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Kategori</label>
                <select name="kategori" class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                    <option value="">Semua Kategori</option>
                    <option value="Belajar" {{ request('kategori') === 'Belajar' ? 'selected' : '' }}>Belajar</option>
                    <option value="Ekskul" {{ request('kategori') === 'Ekskul' ? 'selected' : '' }}>Ekskul</option>
                    <option value="Fasilitas" {{ request('kategori') === 'Fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                    <option value="Event Seru" {{ request('kategori') === 'Event Seru' ? 'selected' : '' }}>Event Seru</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Status Persetujuan</label>
                <select name="status" class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 bg-slate-950 dark:bg-zinc-800 hover:bg-slate-900 text-white dark:text-zinc-300 font-bold text-xs rounded-none transition-all font-mono tracking-wider">
                    FILTER
                </button>
                @if(request()->anyFilled(['search', 'kategori', 'status']))
                    <a href="{{ route('admin.galeri.index') }}" class="w-full py-2 text-center bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-bold text-xs rounded-none transition-all font-mono tracking-wider font-semibold">
                        RESET
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col space-y-4">
        <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider font-mono">Daftar Album Foto</h3>
        
        <div class="overflow-x-auto border border-slate-100 dark:border-zinc-800">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-800 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                        <th class="py-3.5 px-4 w-16 text-center">Sampul</th>
                        <th class="py-3.5 px-4">Judul & Kategori</th>
                        <th class="py-3.5 px-4 w-32">Pengunggah</th>
                        <th class="py-3.5 px-4 w-28 text-center">Jumlah Foto</th>
                        <th class="py-3.5 px-4 w-20 text-center">Tahun</th>
                        <th class="py-3.5 px-4 w-28 text-center">Status</th>
                        <th class="py-3.5 px-4 w-44 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                    @forelse($galeris as $gal)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="py-3 px-4">
                                <div class="w-12 h-12 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-800 overflow-hidden flex items-center justify-center relative">
                                    <img src="{{ $gal->coverUrl() }}" class="w-full h-full object-cover" alt="Sampul">
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-800 dark:text-zinc-300 leading-snug">{{ $gal->judul }}</div>
                                <div class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1 font-mono uppercase tracking-wider">Kategori: {{ $gal->kategori ?? 'Umum' }}</div>
                            </td>
                            <td class="py-3 px-4 text-slate-700 dark:text-zinc-300">
                                {{ $gal->pengunggah->name ?? 'Anonim' }}
                            </td>
                            <td class="py-3 px-4 text-center text-slate-700 dark:text-zinc-300 font-mono">
                                {{ $gal->photos->count() }} Foto
                            </td>
                            <td class="py-3 px-4 text-center text-slate-500 dark:text-zinc-400 font-mono">
                                {{ $gal->tahun }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase border
                                    @if($gal->status === 'approved') bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-800/40
                                    @elseif($gal->status === 'pending') bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-800/40
                                    @else bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-800/40
                                    @endif">
                                    {{ strtoupper($gal->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.galeri.show', $gal->uuid) }}" 
                                   class="inline-block py-1 px-2.5 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 font-bold text-[10px] uppercase font-mono tracking-wider">
                                    Detail
                                </a>

                                @can('update', $gal)
                                    <a href="{{ route('admin.galeri.edit', $gal->uuid) }}" 
                                       class="inline-block py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider">
                                        Edit
                                    </a>
                                @endcan

                                @can('delete', $gal)
                                    <form action="{{ route('admin.galeri.destroy', $gal->uuid) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus galeri ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="py-1 px-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] uppercase font-mono tracking-wider">
                                            Hapus
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 dark:text-zinc-500 italic">
                                Belum ada album foto kegiatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-2">
            {{ $galeris->links() }}
        </div>
    </div>
</div>
@endsection
