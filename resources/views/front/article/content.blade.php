@extends('layouts.app')

@section('seo_title', $article->seo->meta_title ?? $article->judul)
@section('seo_description', $article->seo->meta_description ?? ($article->ringkasan ?? str($article->konten)->stripTags()->limit(160)))
@section('seo_keywords', $article->seo->meta_keywords ?? '')
@section('seo_robots', ($article->seo->is_indexed ?? true ? 'index' : 'noindex') . ', ' . ($article->seo->is_followed ?? true ? 'follow' : 'nofollow'))
@section('canonical_url', $article->seo->canonical_url ?? route('frontend.article.show', $article->slug))

@section('og_type', 'article')
@section('og_title', $article->seo->og_title ?? $article->seo->meta_title ?? $article->judul)
@section('og_description', $article->seo->og_description ?? $article->seo->meta_description ?? ($article->ringkasan ?? str($article->konten)->stripTags()->limit(160)))
@section('og_image', $article->seo->og_image ? asset('storage/' . $article->seo->og_image) : $article->thumbnailUrl())

@section('schema_json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "@@id": "{{ route('frontend.article.show', $article->slug) }}#breadcrumb",
      "itemListElement": [
        {
          "@@type": "ListItem",
          "position": 1,
          "name": "Beranda",
          "item": "{{ route('frontend.home') }}"
        },
        {
          "@@type": "ListItem",
          "position": 2,
          "name": "Artikel",
          "item": "{{ route('frontend.article.index') }}"
        },
        {
          "@@type": "ListItem",
          "position": 3,
          "name": "{{ $article->judul }}",
          "item": "{{ route('frontend.article.show', $article->slug) }}"
        }
      ]
    },
    {
      "@@type": "NewsArticle",
      "@@id": "{{ route('frontend.article.show', $article->slug) }}#article",
      "isPartOf": {
        "@@id": "{{ route('frontend.home') }}#website"
      },
      "headline": "{{ $article->seo->meta_title ?? $article->judul }}",
      "description": "{{ $article->seo->meta_description ?? ($article->ringkasan ?? str($article->konten)->stripTags()->limit(160)) }}",
      "image": [
        "{{ $article->thumbnailUrl() }}"
      ],
      "datePublished": "{{ $article->published_at ? $article->published_at->toIso8601String() : $article->created_at->toIso8601String() }}",
      "dateModified": "{{ $article->updated_at->toIso8601String() }}",
      "author": {
        "@@type": "Person",
        "name": "{{ $article->penulis ? $article->penulis->name : 'MA Muhammadiyah Limpung' }}"
      },
      "publisher": {
        "@@type": "Organization",
        "name": "{{ $siteSettings->school_name ?? 'MA Muhammadiyah Limpung' }}",
        "logo": {
          "@@type": "ImageObject",
          "url": "{{ !empty($siteSettings->logo_path) ? asset('storage/' . $siteSettings->logo_path) : asset('images/logo.png') }}"
        }
      },
      "mainEntityOfPage": "{{ route('frontend.article.show', $article->slug) }}"
    }
  ]
}
</script>
@endsection

@section('content')

<!-- Zen Reading Environment -->
<div class="bg-slate-50 min-h-screen pt-8 md:pt-12 pb-24 md:pb-32 font-sans selection:bg-blue-200 selection:text-slate-900">
    
    <article class="container mx-auto px-4 sm:px-6 max-w-3xl">
        
        <!-- Breadcrumbs Navigation -->
        <nav class="mb-6 md:mb-8 text-[10px] sm:text-xs font-mono font-semibold uppercase tracking-wider text-slate-500 text-center sm:text-left" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                <li>
                    <a href="{{ route('frontend.home') }}" class="hover:text-blue-700 transition-colors flex items-center gap-1">
                        <i class="fa-solid fa-house text-[9px]"></i> Beranda
                    </a>
                </li>
                <li class="text-slate-350">/</li>
                <li>
                    <a href="{{ route('frontend.article.index') }}" class="hover:text-blue-700 transition-colors">Artikel</a>
                </li>
                <li class="text-slate-350">/</li>
                <li class="text-slate-800 truncate max-w-[200px] sm:max-w-xs" aria-current="page">{{ $article->judul }}</li>
            </ol>
        </nav>

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
            <img src="{{ $article->thumbnailUrl() }}" alt="{{ $article->judul }}" class="w-full h-full object-cover" loading="lazy">
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

        <!-- Social Media Share Section -->
        <div x-data="{ copied: false }" class="mt-12 mb-8 p-6 bg-white border border-slate-200 rounded-none shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h4 class="font-serif text-lg font-bold text-slate-900 mb-1">Bagikan Artikel Ini</h4>
                <p class="text-xs text-slate-500 font-mono">Dukung sekolah kami dengan menyebarkan berita bermanfaat ini.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- WhatsApp -->
                <a href="https://api.whatsapp.com/send?text={{ rawurlencode($article->judul . ' - ' . route('frontend.article.show', $article->slug)) }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="w-10 h-10 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md"
                   title="Bagikan ke WhatsApp">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                </a>
                
                <!-- Facebook -->
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('frontend.article.show', $article->slug)) }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="w-10 h-10 rounded-full bg-[#1877F2] hover:bg-[#166FE5] text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md"
                   title="Bagikan ke Facebook">
                    <i class="fa-brands fa-facebook-f text-base"></i>
                </a>
                
                <!-- Twitter / X -->
                <a href="https://twitter.com/intent/tweet?text={{ rawurlencode($article->judul) }}&url={{ urlencode(route('frontend.article.show', $article->slug)) }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="w-10 h-10 rounded-full bg-black hover:bg-slate-900 text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md"
                   title="Bagikan ke Twitter / X">
                    <i class="fa-brands fa-x-twitter text-base"></i>
                </a>
                
                <!-- Telegram -->
                {{-- <a href="https://telegram.me/share/url?url={{ urlencode(route('frontend.article.show', $article->slug)) }}&text={{ rawurlencode($article->judul) }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="w-10 h-10 rounded-full bg-[#0088cc] hover:bg-[#0077b5] text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md"
                   title="Bagikan ke Telegram">
                    <i class="fa-brands fa-telegram text-base"></i>
                </a> --}}
                
                <!-- LinkedIn -->
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('frontend.article.show', $article->slug)) }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="w-10 h-10 rounded-full bg-[#0077B5] hover:bg-[#046294] text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md"
                   title="Bagikan ke LinkedIn">
                    <i class="fa-brands fa-linkedin-in text-base"></i>
                </a>
                
                <!-- Copy Link -->
                <button @click="navigator.clipboard.writeText('{{ route('frontend.article.show', $article->slug) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="w-10 h-10 rounded-full bg-[#0088cc] hover:bg-[#0077b5] text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md"
                        title="Salin Tautan">
                    <i class="fa-solid text-base" :class="copied ? 'fa-check text-emerald-600' : 'fa-link'"></i>
                    {{-- <span x-text="copied ? 'Tersalin!' : 'Salin Link'"></span> --}}
                </button>
            </div>
        </div>

        <section class="mt-5">
            {{-- back --}}
            <a href="{{ route('frontend.article.index') }}" class="w-full h-10 rounded-sm bg-indigo-700 hover:bg-[#0077b5] text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md" title="Kembali">
                <i class="fa-solid fa-arrow-left text-base mr-2"></i> Kembali
            </a>
        </section>

        <!-- Related Articles Recommendations -->
        @if($relatedArticles->isNotEmpty())
        <div class="mt-16 pt-10 border-t border-slate-200">
            <h3 class="font-serif text-2xl font-bold text-slate-900 mb-6">Artikel Terkait</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedArticles as $related)
                    <a href="{{ route('frontend.article.show', $related->slug) }}" class="group flex flex-col">
                        <div class="w-full aspect-video overflow-hidden bg-slate-200 mb-3 relative">
                            <img src="{{ $related->thumbnailUrl() }}" alt="{{ $related->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
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