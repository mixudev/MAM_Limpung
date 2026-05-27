@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Kategori Artikel';
        }
    });
</script>

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Kelola Kategori Artikel</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Buat, perbarui, dan hapus kategori artikel untuk mengelompokkan artikel literasi sekolah.</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/60 p-4 text-emerald-800 dark:text-emerald-400 text-xs font-semibold rounded-none flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button class="text-emerald-600 hover:text-emerald-800 dark:hover:text-white font-bold" onclick="this.parentElement.remove()">
                &times;
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/60 p-4 text-red-800 dark:text-red-400 text-xs font-semibold rounded-none">
            <p class="font-bold mb-1">Terjadi kesalahan input:</p>
            <ul class="list-disc list-inside space-y-1 font-mono">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Categories List (Takes 2 Cols) -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider font-mono">Daftar Kategori</h3>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Total: {{ $categories->total() }} kategori terdaftar.</p>
                </div>
                
                <!-- Search Box -->
                <form action="{{ route('admin.article-categories.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." 
                        class="px-3 py-1.5 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]" />
                    <button type="submit" class="py-1.5 px-3 bg-slate-900 dark:bg-zinc-800 text-white dark:text-zinc-300 font-bold text-xs rounded-none hover:bg-slate-800 transition-all font-mono">
                        CARI
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.article-categories.index') }}" class="py-1.5 px-3 bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none border border-slate-200 dark:border-zinc-700 hover:bg-slate-200 transition-all font-mono">
                            RESET
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto border border-slate-100 dark:border-zinc-800">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-800 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                            <th class="py-3 px-4">Nama & Slug</th>
                            <th class="py-3 px-4">Deskripsi</th>
                            <th class="py-3 px-4 text-center w-24">Artikel</th>
                            <th class="py-3 px-4 text-right w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-800 dark:text-zinc-300">{{ $cat->name }}</div>
                                    <div class="text-[10px] text-slate-400 dark:text-zinc-500 mt-0.5 font-mono">{{ $cat->slug }}</div>
                                </td>
                                <td class="py-3 px-4 text-slate-500 dark:text-zinc-400">
                                    {{ Str::limit($cat->description ?: '-', 80) }}
                                </td>
                                <td class="py-3 px-4 text-center font-bold text-slate-700 dark:text-zinc-400">
                                    <span class="px-2 py-0.5 bg-slate-100 dark:bg-zinc-800 font-mono text-[10px]">
                                        {{ $cat->articles_count }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right space-x-1.5 whitespace-nowrap">
                                    <a href="{{ route('admin.article-categories.index', ['edit' => $cat->slug] + request()->except('edit')) }}" 
                                       class="inline-block py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider">
                                        Edit
                                    </a>
                                    @can('delete', $cat)
                                        <form action="{{ route('admin.article-categories.destroy', $cat->slug) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua artikel dalam kategori ini akan diubah menjadi tanpa kategori (NULL).')">
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
                                <td colspan="4" class="py-8 text-center text-slate-400 dark:text-zinc-500 italic">
                                    Tidak ada kategori yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pt-2">
                {{ $categories->links() }}
            </div>
        </div>

        <!-- Right: Form Box (Takes 1 Col) -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm">
            @php
                $editCat = null;
                if(request()->filled('edit')) {
                    $editCat = \App\Models\ArticleCategory::where('slug', request('edit'))->first();
                }
            @endphp

            @if($editCat)
                <!-- Edit Category Form -->
                <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider font-mono border-b border-slate-100 dark:border-zinc-800 pb-3 mb-4">Edit Kategori</h3>
                
                <form action="{{ route('admin.article-categories.update', $editCat->slug) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $editCat->name) }}" required
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>

                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Deskripsi Kategori</label>
                        <textarea name="description" rows="5"
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">{{ old('description', $editCat->description) }}</textarea>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="w-full py-2 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all font-mono tracking-wider">
                            PERBARUI
                        </button>
                        <a href="{{ route('admin.article-categories.index', request()->except('edit')) }}" 
                           class="w-full py-2 text-center bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-bold text-xs rounded-none transition-all font-mono tracking-wider">
                            BATAL
                        </a>
                    </div>
                </form>
            @else
                <!-- Create Category Form -->
                <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider font-mono border-b border-slate-100 dark:border-zinc-800 pb-3 mb-4">Tambah Kategori Baru</h3>
                
                <form action="{{ route('admin.article-categories.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Kegiatan Sekolah"
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>

                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Deskripsi Kategori</label>
                        <textarea name="description" rows="5" placeholder="Masukkan penjelasan singkat kategori..."
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">{{ old('description') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all font-mono tracking-wider">
                            SIMPAN KATEGORI
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
