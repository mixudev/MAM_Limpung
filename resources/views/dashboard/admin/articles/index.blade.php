@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Daftar Artikel';
        }
    });
</script>

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Kelola Artikel</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Buat, edit, dan publikasikan artikel, berita, atau literasi untuk website sekolah.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="py-2.5 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider flex items-center justify-center gap-2 font-mono">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            TAMBAH ARTIKEL
        </a>
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

    <!-- Filters Section -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm">
        <form action="{{ route('admin.articles.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Cari Artikel</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul/konten..." 
                    class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]" />
            </div>

            <!-- Category -->
            <div>
                <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5 font-mono">Kategori</label>
                <select name="category_id" class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5 font-mono">Status</label>
                <select name="status" class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi (Pending)</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Diterbitkan (Published)</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Diarsipkan (Archived)</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 bg-slate-950 dark:bg-zinc-800 hover:bg-slate-905 text-white dark:text-zinc-300 font-bold text-xs rounded-none transition-all font-mono tracking-wider">
                    FILTER
                </button>
                @if(request()->anyFilled(['search', 'category_id', 'status']))
                    <a href="{{ route('admin.articles.index') }}" class="w-full py-2 text-center bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-bold text-xs rounded-none transition-all font-mono tracking-wider">
                        RESET
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col space-y-4">
        <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider font-mono">Daftar Artikel</h3>
        
        <div class="overflow-x-auto border border-slate-100 dark:border-zinc-800">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-800 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                        <th class="py-3.5 px-4 w-16">Thumbnail</th>
                        <th class="py-3.5 px-4">Judul Artikel</th>
                        <th class="py-3.5 px-4 w-40">Kategori</th>
                        <th class="py-3.5 px-4 w-32">Penulis</th>
                        <th class="py-3.5 px-4 w-28 text-center">Status</th>
                        <th class="py-3.5 px-4 w-36">Dipublikasikan</th>
                        <th class="py-3.5 px-4 w-36 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                    @forelse($articles as $art)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="py-3 px-4">
                                <div class="w-12 h-9 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-800 overflow-hidden">
                                    <img src="{{ $art->thumbnailUrl() }}" class="w-full h-full object-cover" alt="Thumb">
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-800 dark:text-zinc-300 leading-snug">{{ $art->judul }}</div>
                                <div class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1 font-mono line-clamp-1">{{ route('frontend.article.show', $art->slug) }}</div>
                            </td>
                            <td class="py-3 px-4 text-slate-600 dark:text-zinc-400">
                                {{ $art->category ? $art->category->name : '-' }}
                            </td>
                            <td class="py-3 px-4 text-slate-600 dark:text-zinc-400 font-medium">
                                {{ $art->penulis ? $art->penulis->name : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($art->status === 'published')
                                    <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40">
                                        TERBIT
                                    </span>
                                @elseif($art->status === 'pending')
                                    <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/40">
                                        PENDING
                                    </span>
                                @elseif($art->status === 'draft')
                                    <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-slate-100 dark:bg-zinc-800 text-slate-650 dark:text-zinc-400 border border-slate-200 dark:border-zinc-700">
                                        DRAFT
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-450 border border-rose-200 dark:border-rose-900/40">
                                        ARSIP
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-zinc-400 font-mono text-[10px]">
                                {{ $art->published_at ? $art->published_at->translatedFormat('d M Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                @if($art->status === 'pending')
                                    <form action="{{ route('admin.articles.approve', $art->slug) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menyetujui dan menerbitkan artikel ini?')">
                                        @csrf
                                        <button type="submit" class="inline-block py-1 px-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] uppercase font-mono tracking-wider border border-emerald-600">
                                            Setujui
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.articles.edit', $art->slug) }}" 
                                   class="inline-block py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider">
                                     Edit
                                 </a>
                                <form action="{{ route('admin.articles.destroy', $art->slug) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-1 px-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] uppercase font-mono tracking-wider">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 dark:text-zinc-500 italic">
                                Tidak ada artikel yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-2">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
