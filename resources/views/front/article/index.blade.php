@extends('layouts.app')

@section('content')

<!-- Alpine App Container -->
<div x-data="articleList()" class="min-h-screen bg-slate-50 font-sans text-slate-800 pb-24 pt-8 md:pt-12">

    <!-- Hero Title & Search -->
    <div class="container mx-auto px-4 sm:px-6 max-w-7xl mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-blue-700 font-bold uppercase tracking-widest text-[10px] sm:text-xs mb-2 block">Pusat Literasi</span>
            <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight uppercase text-slate-900">MAM News</h1>
        </div>
        
        <!-- Sharp Search Bar -->
        <div class="relative w-full md:w-96 flex shadow-sm">
            <input x-model="searchQuery" type="text" placeholder="Cari artikel..." class="w-full bg-white border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:border-blue-700 transition-colors rounded-none placeholder-slate-400">
            <button class="bg-slate-900 text-white px-5 py-3 hover:bg-amber-500 hover:text-slate-900 transition-colors rounded-none">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </div>

    <!-- Category Strip -->
    <div class="border-y border-slate-200 bg-white mb-10 md:mb-12 shadow-sm">
        <div class="container mx-auto px-4 sm:px-6 max-w-7xl flex overflow-x-auto hide-scrollbar">
            <template x-for="category in categories" :key="category">
                <button @click="activeCategory = category"
                        class="px-5 sm:px-6 py-4 text-[10px] sm:text-xs font-bold uppercase tracking-widest whitespace-nowrap border-r border-slate-100 hover:text-blue-700 transition-colors"
                        :class="activeCategory === category ? 'text-blue-700 shadow-[inset_0_-2px_0_0_#1d4ed8]' : 'text-slate-500 bg-transparent'"
                        x-text="category">
                </button>
            </template>
        </div>
    </div>

    <!-- Newspaper Hero Split (Shows only when not searching and "Semua" is selected) -->
    @if($headline)
    <div x-show="activeCategory === 'Semua' && searchQuery === ''" class="container mx-auto px-4 sm:px-6 max-w-7xl mb-12 md:mb-16" x-transition>
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            
            <!-- Left: Massive Featured Post -->
            <div class="lg:w-2/3 flex flex-col cursor-pointer group" @click="window.location.href='{{ route('frontend.article.show', $headline->slug) }}'">
                <div class="w-full aspect-video overflow-hidden mb-5 sm:mb-6 bg-slate-200 relative">
                    <img src="{{ $headline->thumbnailUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[2s]" alt="{{ $headline->judul }}">
                    <div class="absolute inset-0 bg-slate-900/5 group-hover:bg-transparent transition-colors duration-500"></div>
                </div>
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-white bg-blue-700 px-2 py-1">Headline</span>
                        <span class="text-xs text-slate-500 font-medium">{{ $headline->published_at ? $headline->published_at->translatedFormat('d M Y') : $headline->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight md:leading-none mb-3 sm:mb-4 group-hover:text-blue-700 text-slate-900 transition-colors">{{ $headline->judul }}</h2>
                    <p class="text-slate-600 text-base sm:text-lg md:text-xl leading-relaxed max-w-3xl line-clamp-3 md:line-clamp-none">{{ $headline->ringkasan ?: str($headline->konten)->stripTags()->limit(150) }}</p>
                </div>
            </div>

            <!-- Right: Trending / Latest Stack -->
            <div class="lg:w-1/3 flex flex-col border-t-2 md:border-t-4 border-slate-900 pt-4">
                <h3 class="text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-4 sm:mb-6 text-slate-500">Terkini</h3>
                
                <div class="flex flex-col">
                    @forelse($latestArticles as $item)
                        <a href="{{ route('frontend.article.show', $item->slug) }}" class="group py-4 sm:py-5 border-b border-slate-200">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-amber-600 mb-1 sm:mb-2 block">{{ $item->category ? $item->category->name : 'Umum' }}</span>
                            <h4 class="font-serif text-lg sm:text-xl font-bold leading-snug group-hover:text-blue-700 text-slate-900 transition-colors mb-2">{{ $item->judul }}</h4>
                            <span class="text-[10px] sm:text-xs text-slate-500">{{ $item->published_at ? $item->published_at->translatedFormat('d M Y') : $item->created_at->translatedFormat('d M Y') }}</span>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400 font-mono py-4">Belum ada berita terkini lainnya.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
    @endif

    <!-- Main Article Grid -->
    <div class="container mx-auto px-4 sm:px-6 max-w-7xl border-t-2 md:border-t-4 border-slate-900 pt-6">
        
        <div class="flex items-center justify-between mb-8 sm:mb-10">
            <h2 class="text-lg sm:text-xl font-bold uppercase tracking-widest text-slate-900" x-text="searchQuery !== '' ? 'Pencarian: ' + searchQuery : (activeCategory === 'Semua' ? 'Arsip Berita' : 'Kategori: ' + activeCategory)"></h2>
            <span class="text-slate-500 text-[10px] sm:text-sm font-medium" x-text="filteredArticles.length + ' Hasil'"></span>
        </div>

        <!-- Empty State (handled by Alpine, fallback not needed for empty state since static loop has articles or handles empty) -->
        <div x-show="filteredArticles.length === 0" class="text-center py-24 sm:py-32 border border-slate-200 bg-white" style="display: none;">
            <i class="fa-regular fa-newspaper text-3xl sm:text-4xl text-slate-300 mb-4 block"></i>
            <h3 class="font-serif text-xl sm:text-2xl font-bold text-slate-900 mb-2">Tidak ada hasil</h3>
            <p class="text-sm sm:text-base text-slate-500 mb-6">Pencarian Anda tidak cocok dengan arsip manapun.</p>
            <button @click="searchQuery = ''; activeCategory = 'Semua'" class="px-5 sm:px-6 py-2 bg-slate-900 text-white font-bold uppercase text-[10px] sm:text-xs tracking-widest hover:bg-blue-700 transition-colors rounded-none">
                Kembali ke Indeks
            </button>
        </div>

        <!-- Static Fallback Grid for SEO Crawlers (Hidden by Alpine immediately once it loads) -->
        <div x-show="false" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10 sm:gap-x-8 sm:gap-y-12">
            @forelse($articles as $article)
                <a href="{{ route('frontend.article.show', $article->slug) }}" class="group flex flex-col">
                    <div class="w-full aspect-[4/3] overflow-hidden bg-slate-200 mb-4 relative">
                        <img src="{{ $article->thumbnailUrl() }}" alt="{{ $article->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[1.5s]">
                        <div class="absolute inset-0 bg-slate-900/5 group-hover:bg-transparent transition-colors duration-500"></div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-3 border-b border-slate-200 pb-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-blue-700">{{ $article->category ? $article->category->name : 'Umum' }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $article->published_at ? $article->published_at->translatedFormat('d M Y') : $article->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <h3 class="font-serif text-xl sm:text-2xl font-bold leading-tight mb-2 sm:mb-3 group-hover:text-blue-700 text-slate-900 transition-colors">{{ $article->judul }}</h3>
                        <p class="text-slate-600 leading-relaxed text-xs sm:text-sm line-clamp-3 mb-4 flex-1">{{ $article->ringkasan ?: str($article->konten)->stripTags()->limit(150) }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-24 border border-slate-200 bg-white">
                    <i class="fa-regular fa-newspaper text-3xl sm:text-4xl text-slate-300 mb-4 block"></i>
                    <h3 class="font-serif text-xl sm:text-2xl font-bold text-slate-900 mb-2">Belum Ada Artikel</h3>
                    <p class="text-sm text-slate-500">Silakan kembali lagi nanti untuk membaca pembaruan literasi sekolah kami.</p>
                </div>
            @endforelse
        </div>

        <!-- Dynamic Grid with Alpine Filtering for JS-Enabled Users -->
        <div x-show="true" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10 sm:gap-x-8 sm:gap-y-12">
            <template x-for="article in filteredArticles" :key="article.id">
                <a :href="article.url" class="group flex flex-col">
                    <div class="w-full aspect-[4/3] overflow-hidden bg-slate-200 mb-4 relative">
                        <img :src="article.image" :alt="article.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[1.5s]">
                        <div class="absolute inset-0 bg-slate-900/5 group-hover:bg-transparent transition-colors duration-500"></div>
                    </div>
                    <div class="flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-3 border-b border-slate-200 pb-2">
                            <span x-text="article.category" class="text-[10px] font-bold uppercase tracking-widest text-blue-700"></span>
                            <span x-text="article.date" class="text-[10px] text-slate-400 font-medium"></span>
                        </div>
                        <h3 x-text="article.title" class="font-serif text-xl sm:text-2xl font-bold leading-tight mb-2 sm:mb-3 group-hover:text-blue-700 text-slate-900 transition-colors"></h3>
                        <p x-text="article.excerpt" class="text-slate-600 leading-relaxed text-xs sm:text-sm line-clamp-3 mb-4 flex-1"></p>
                    </div>
                </a>
            </template>
        </div>

    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('articleList', () => ({
            searchQuery: '',
            activeCategory: 'Semua',
            categories: {!! $categoriesJson !!},
            articles: {!! $articlesJson !!},
            
            get filteredArticles() {
                let result = this.articles;

                if (this.activeCategory !== 'Semua') {
                    result = result.filter(a => a.category === this.activeCategory);
                }

                if (this.searchQuery !== '') {
                    const q = this.searchQuery.toLowerCase();
                    result = result.filter(a => 
                        a.title.toLowerCase().includes(q) || 
                        a.excerpt.toLowerCase().includes(q)
                    );
                }

                return result;
            }
        }))
    })
</script>

@endsection