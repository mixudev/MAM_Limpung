@extends('layouts.app')

@section('content')

<!-- Zen Reading Environment -->
<div class="bg-slate-50 min-h-screen pt-8 md:pt-12 pb-24 md:pb-32 font-sans selection:bg-blue-200 selection:text-slate-900">
    
    <article class="container mx-auto px-4 sm:px-6 max-w-3xl">
        
        <!-- Back Link -->
        <div class="mb-6 md:mb-8 text-center sm:text-left">
            <a href="{{ route('frontend.article.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-700 transition-colors text-[10px] sm:text-sm font-bold uppercase tracking-widest">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Indeks
            </a>
        </div>

        <!-- Article Header -->
        <header class="mb-8 md:mb-10 text-center sm:text-left">
            <div class="flex items-center justify-center sm:justify-start gap-3 mb-4 md:mb-6">
                <span class="text-[10px] font-bold uppercase tracking-widest text-blue-700 border border-blue-700 px-3 py-1">
                    {{ $article->category ? $article->category->name : 'Umum' }}
                </span>
                <span class="text-slate-500 text-xs sm:text-sm">
                    {{ $article->published_at ? $article->published_at->translatedFormat('d M Y') : $article->created_at->translatedFormat('d M Y') }}
                </span>
            </div>
            
            <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 leading-tight md:leading-[1.1] mb-6 md:mb-8">
                {{ $article->judul }}
            </h1>
            
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 sm:gap-4 text-slate-600 text-xs sm:text-sm">
                <span class="font-bold uppercase tracking-widest text-[10px] sm:text-xs text-slate-900">
                    Oleh {{ $article->penulis ? $article->penulis->name : 'Penulis Sekolah' }}
                </span>
                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                <span class="uppercase tracking-widest text-[10px] sm:text-xs">
                    {{ $readTime }} Menit Membaca
                </span>
            </div>
        </header>

        <!-- Sharp Featured Image -->
        @if($article->thumbnail)
        <div class="w-full aspect-video sm:aspect-[21/9] bg-slate-200 mb-8 md:mb-12 relative">
            <img src="{{ $article->thumbnailUrl() }}" alt="{{ $article->judul }}" class="w-full h-full object-cover">
        </div>
        @endif

        <!-- Article Body (Optimized for Readability) -->
        <div class="prose prose-slate prose-base sm:prose-lg md:prose-xl mx-auto 
                    prose-p:text-slate-800 prose-p:leading-[1.8] prose-p:mb-6 sm:prose-p:mb-8
                    prose-headings:font-serif prose-headings:font-bold prose-headings:text-slate-900 prose-headings:leading-tight
                    prose-a:text-blue-700 prose-a:underline prose-a:decoration-blue-300 hover:prose-a:decoration-blue-700
                    prose-blockquote:border-l-4 prose-blockquote:border-amber-500 prose-blockquote:bg-white prose-blockquote:py-3 sm:prose-blockquote:py-4 prose-blockquote:px-5 sm:prose-blockquote:px-6 prose-blockquote:not-italic prose-blockquote:font-serif prose-blockquote:text-slate-900 prose-blockquote:shadow-sm">
            {!! $article->konten !!}
        </div>

        <!-- Related Articles Recommendations -->
        @if($relatedArticles->isNotEmpty())
        <div class="mt-16 pt-10 border-t border-slate-200">
            <h3 class="font-serif text-2xl font-bold text-slate-900 mb-6">Artikel Terkait</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedArticles as $related)
                    <a href="{{ route('frontend.article.show', $related->slug) }}" class="group flex flex-col">
                        <div class="w-full aspect-video overflow-hidden bg-slate-200 mb-3 relative">
                            <img src="{{ $related->thumbnailUrl() }}" alt="{{ $related->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <span class="text-[9px] font-bold uppercase tracking-widest text-blue-700 mb-1">
                            {{ $related->category ? $related->category->name : 'Umum' }}
                        </span>
                        <h4 class="font-serif text-sm font-bold text-slate-900 leading-snug group-hover:text-blue-700 transition-colors line-clamp-2">
                            {{ $related->judul }}
                        </h4>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Footer Call to Action -->
        <div class="mt-12 md:mt-16 pt-6 md:pt-8 border-t border-slate-200 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-xs text-slate-500 font-mono">
                Bagikan artikel ini kepada rekan atau siswa Anda.
            </div>
            
            <a href="/ppdb" class="group flex items-center justify-center w-full md:w-auto gap-4 bg-slate-900 text-white px-6 py-3 hover:bg-blue-700 transition-colors">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest">Daftar PPDB Online</span>
                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

    </article>

</div>

@endsection