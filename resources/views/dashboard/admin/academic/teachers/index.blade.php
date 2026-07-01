@extends('dashboard.layouts.main')

@section('content')
<style>
    .dropzone-highlight { border-color: #4f45b2 !important; background: rgba(79,69,178,0.04); }
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const bc = document.getElementById('breadcrumb');
    if (bc) bc.textContent = 'Data Guru';

    const dz = document.getElementById('photoDropzone');
    const fi = document.getElementById('photoInput');
    const pr = document.getElementById('photoPreview');
    if (dz && fi) {
        fi.addEventListener('change', function(e) {
            if (e.target.files.length) {
                const r = new FileReader();
                r.onload = function(ev) { if (pr) { pr.src=ev.target.result; pr.classList.remove('hidden'); } };
                r.readAsDataURL(e.target.files[0]);
            }
        });
        ['dragenter','dragover'].forEach(function(t) { dz.addEventListener(t, function(e) { e.preventDefault(); dz.classList.add('dropzone-highlight'); }); });
        ['dragleave','drop'].forEach(function(t) { dz.addEventListener(t, function(e) { e.preventDefault(); dz.classList.remove('dropzone-highlight'); }); });
        dz.addEventListener('drop', function(e) {
            e.preventDefault();
            if (e.dataTransfer.files.length) { fi.files = e.dataTransfer.files; fi.dispatchEvent(new Event('change')); }
        });
        dz.addEventListener('click', function() { fi.click(); });
    }
});
</script>

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between bg-white dark:bg-zinc-900 p-6 border border-slate-300 dark:border-zinc-800 border-l-4 border-l-[#4f45b2] shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white leading-tight">Manajemen Guru & Staf</h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 font-mono">Kelola data guru, staf, dan tenaga pendidik.</p>
        </div>
        <a href="{{ route('admin.teachers.create') }}"
           class="py-2.5 px-5 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs tracking-wider font-mono transition-all inline-flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-plus"></i> TAMBAH GURU
        </a>
    </div>

    {{-- Filter Toolbar --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800">
        <form method="GET" action="{{ route('admin.teachers.index') }}">
            <div class="flex items-center gap-2 p-4">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 dark:text-zinc-500">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari guru — nama, NIP, atau email..."
                           class="w-full pl-9 pr-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 placeholder:text-slate-400 dark:placeholder:text-zinc-500 focus:outline-none focus:border-[#4f45b2] focus:ring-1 focus:ring-[#4f45b2]/20 transition-all"/>
                </div>

                <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')"
                    class="flex items-center gap-1.5 px-3 py-2 text-xs font-mono font-bold text-slate-500 dark:text-zinc-400 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 hover:border-[#4f45b2] hover:text-[#4f45b2] transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                    </svg>
                    <svg class="w-3 h-3 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs font-mono tracking-wider transition-all inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                    </svg>
                    Cari
                </button>
            </div>

            <div id="filterPanel" class="hidden border-t border-slate-100 dark:border-zinc-800 px-4 pb-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-4">
                    <select name="category"
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2] appearance-none"
                            style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 16 16%27 fill=%27%239ca3af%27%3E%3Cpath fill-rule=%27evenodd%27 d=%27M4.22 6.22a.75.75 0 011.06 0L8 8.94l2.72-2.72a.75.75 0 111.06 1.06l-3.25 3.25a.75.75 0 01-1.06 0L4.22 7.28a.75.75 0 010-1.06z%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1rem;">
                        <option value="">Kategori — Semua</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <select name="status"
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2] appearance-none"
                            style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 16 16%27 fill=%27%239ca3af%27%3E%3Cpath fill-rule=%27evenodd%27 d=%27M4.22 6.22a.75.75 0 011.06 0L8 8.94l2.72-2.72a.75.75 0 111.06 1.06l-3.25 3.25a.75.75 0 01-1.06 0L4.22 7.28a.75.75 0 010-1.06z%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1rem;">
                        <option value="">Status — Semua</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @if(request()->anyFilled(['search', 'category', 'status']))
                    <div class="flex items-end">
                        <a href="{{ route('admin.teachers.index') }}"
                           class="w-full py-2 text-center text-[10px] font-mono font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 hover:bg-rose-100 dark:hover:bg-rose-900/30 transition-colors">
                            Reset
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-800 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                        <th class="py-3.5 px-4">Foto</th>
                        <th class="py-3.5 px-4">Nama</th>
                        <th class="py-3.5 px-4">NIP</th>
                        <th class="py-3.5 px-4">Kategori / Jabatan</th>
                        <th class="py-3.5 px-4">JK</th>
                        <th class="py-3.5 px-4">No. Telepon</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse($teachers as $teacher)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                        <td class="py-3 px-4">
                            @if($teacher->foto)
                                <div class="w-10 h-10 bg-slate-100 dark:bg-zinc-800 overflow-hidden border border-slate-200 dark:border-zinc-700">
                                    <img src="{{ asset('storage/'.$teacher->foto) }}" alt="{{ $teacher->nama }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-10 h-10 bg-[#4f45b2]/10 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 flex items-center justify-center text-[#4f45b2] dark:text-[#4f45b2] text-xs font-bold">
                                    {{ substr($teacher->nama, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-800 dark:text-zinc-200 text-xs">{{ $teacher->nama }}</div>
                            @if($teacher->user->email ?? null)
                                <div class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono mt-0.5">{{ $teacher->user->email }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-600 dark:text-zinc-400">{{ $teacher->nip ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($teacher->categories->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach($teacher->categories as $cat)
                                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-400 text-[10px] font-bold font-mono whitespace-nowrap">{{ $cat->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="font-mono text-[11px] text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-600 dark:text-zinc-400">{{ $teacher->jenis_kelamin }}</td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-600 dark:text-zinc-400">{{ $teacher->no_telepon ?? '-' }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($teacher->status === 'aktif')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold font-mono uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-500 dark:text-zinc-400 text-[10px] font-bold font-mono uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.teachers.edit', $teacher) }}"
                                   class="inline-flex items-center gap-1 py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" class="inline" onsubmit="return confirm('Hapus guru {{ $teacher->nama }}? Semua data terkait akan dihapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                       class="inline-flex items-center py-1 px-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-10 text-center text-slate-400 dark:text-zinc-500">
                            <i class="fa-solid fa-chalkboard-user text-3xl text-slate-300 dark:text-zinc-600 mb-3 block"></i>
                            <p class="text-sm font-semibold text-slate-600 dark:text-zinc-400 font-mono">Belum ada data guru.</p>
                            <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Gunakan tombol "Tambah Guru" untuk menambahkan data pertama.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($teachers->hasPages())
        <div class="px-4 py-3 border-t border-slate-100 dark:border-zinc-800">
            {{ $teachers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
