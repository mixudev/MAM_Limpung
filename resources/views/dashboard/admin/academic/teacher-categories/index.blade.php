@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const bc = document.getElementById('breadcrumb');
        if (bc) bc.textContent = 'Kategori Guru';
    });
</script>

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between bg-white dark:bg-zinc-900 p-6 border border-slate-300 dark:border-zinc-800 border-l-4 border-l-[#4f45b2] shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white leading-tight">Kategori Guru</h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 font-mono">Kelola kategori untuk guru dan staf.</p>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Table --}}
        <div class="lg:w-3/5">
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
                {{-- Filter Toolbar --}}
                <div class="bg-white dark:bg-zinc-900 border-b border-slate-100 dark:border-zinc-800/60">
                    <form method="GET" action="{{ route('admin.teacher-categories.index') }}">
                        <div class="flex items-center gap-2 p-4">
                            <div class="relative flex-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 dark:text-zinc-500">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                                    </svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Cari kategori..."
                                       class="w-full pl-9 pr-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 placeholder:text-slate-400 dark:placeholder:text-zinc-500 focus:outline-none focus:border-[#4f45b2] focus:ring-1 focus:ring-[#4f45b2]/20 transition-all"/>
                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs font-mono tracking-wider transition-all inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                                </svg>
                                Cari
                            </button>
                        </div>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-800 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                                <th class="py-3.5 px-4">Nama</th>
                                <th class="py-3.5 px-4">Deskripsi</th>
                                <th class="py-3.5 px-4 text-center w-20">Guru</th>
                                <th class="py-3.5 px-4 text-right w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                                <td class="py-3 px-4 font-bold text-slate-800 dark:text-zinc-200 text-xs">{{ $category->name }}</td>
                                <td class="py-3 px-4 text-slate-500 dark:text-zinc-400 text-[11px] max-w-[250px] truncate">{{ $category->description ?? '-' }}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="w-8 h-7 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-400 font-bold font-mono text-[11px] inline-flex items-center justify-center">
                                        {{ $category->teachers_count }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.teacher-categories.index', ['edit' => $category->id]) }}"
                                           class="inline-flex items-center gap-1 py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors {{ request('edit') == $category->id ? 'ring-2 ring-[#4f45b2]' : '' }}">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.teacher-categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
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
                                <td colspan="4" class="py-10 text-center text-slate-400 dark:text-zinc-500">
                                    <i class="fa-solid fa-tags text-3xl text-slate-300 dark:text-zinc-600 mb-3 block"></i>
                                    <p class="text-sm font-semibold text-slate-600 dark:text-zinc-400 font-mono">Belum ada kategori guru.</p>
                                    <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Tambahkan kategori melalui form di sebelah kanan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($categories->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 dark:border-zinc-800">
                    {{ $categories->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- Form --}}
        <div class="lg:w-2/5">
            <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 border-t-4 border-t-[#4f45b2] shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
                <div class="p-6 border-b border-slate-100 dark:border-zinc-800">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 flex items-center gap-2">
                        @if($editCategory)
                            <i class="fa-solid fa-pen text-[#4f45b2]"></i> Edit Kategori
                        @else
                            <i class="fa-solid fa-plus text-emerald-600"></i> Tambah Kategori Baru
                        @endif
                    </h3>
                </div>
                <div class="p-6">
                    @if($editCategory)
                        <form method="POST" action="{{ route('admin.teacher-categories.update', $editCategory) }}">
                            @csrf @method('PUT')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Nama Kategori</label>
                                    <input type="text" name="name" value="{{ old('name', $editCategory->name) }}"
                                           class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                                    @error('name') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Deskripsi</label>
                                    <textarea name="description" rows="3"
                                              class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">{{ old('description', $editCategory->description) }}</textarea>
                                </div>
                                <div class="flex gap-2 pt-2">
                                    <button type="submit"
                                        class="flex-1 py-2.5 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs font-mono tracking-wider transition-all">
                                        <i class="fa-solid fa-save mr-1"></i> SIMPAN
                                    </button>
                                    <a href="{{ route('admin.teacher-categories.index') }}"
                                       class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs font-mono tracking-wider transition-all">BATAL</a>
                                </div>
                            </div>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.teacher-categories.store') }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Nama Kategori</label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                           placeholder="Contoh: Guru Mapel, Wali Kelas"
                                           class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                                    @error('name') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Deskripsi</label>
                                    <textarea name="description" rows="3" placeholder="Deskripsi kategori..."
                                              class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">{{ old('description') }}</textarea>
                                </div>
                                <button type="submit"
                                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs font-mono tracking-wider transition-all">
                                    <i class="fa-solid fa-plus mr-1"></i> TAMBAH KATEGORI
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
