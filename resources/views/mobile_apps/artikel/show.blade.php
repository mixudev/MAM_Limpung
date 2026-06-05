@extends('mobile_apps.layouts.apps')

@section('content')
    <div class="px-5 pt-4">
        <!-- Header & Back Button -->
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <a href="{{ route('apps.artikel') }}" class="w-8 h-8 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-600 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-sora font-bold text-slate-800 text-sm">Baca Artikel</h2>
            </div>
            
            <span class="text-[9px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide
                {{ $article->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                {{ $article->status === 'published' ? 'Terbit' : 'Draf' }}
            </span>
        </div>

        <!-- Thumbnail Image -->
        @if($article->thumbnail)
            <div class="w-full aspect-[16/9] rounded-3xl overflow-hidden border border-slate-100 shadow-xs mb-5">
                <img src="{{ Storage::url($article->thumbnail) }}" alt="Thumbnail" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Card Content -->
        <div class="bg-white border border-slate-100 shadow-xs rounded-3xl p-5 mb-6">
            <div class="flex items-center gap-2 text-[10px] text-slate-400 font-bold mb-3">
                <span class="text-primary-600 bg-primary-50 px-2 py-0.5 rounded-md uppercase tracking-wider">{{ $article->category->name }}</span>
                <span>&bull;</span>
                <span>{{ $article->created_at->format('d M Y') }}</span>
            </div>

            <h3 class="font-sora font-bold text-slate-800 text-base leading-snug mb-4">{{ $article->judul }}</h3>
            
            <!-- Summary Callout -->
            <div class="bg-slate-50 border-l-4 border-primary-500 p-3 rounded-r-xl mb-5 text-[11px] text-slate-600 leading-relaxed font-semibold italic">
                {{ $article->ringkasan }}
            </div>

            <!-- Content body -->
            <div class="prose prose-sm max-w-none text-xs text-slate-700 leading-relaxed font-medium">
                {!! $article->konten !!}
            </div>
        </div>

        <!-- Action options for edit / delete -->
        <div class="flex gap-3 mb-8">
            <a href="{{ route('apps.artikel.edit', $article) }}" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-center rounded-2xl text-xs font-bold transition-colors">
                Edit Artikel
            </a>
            
            <form id="delete-article-form-{{ $article->id }}" action="{{ route('apps.artikel.destroy', $article) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDeleteArticle({{ $article->id }})" class="w-full py-3 bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 rounded-2xl text-xs font-bold transition-all cursor-pointer">
                    Hapus Artikel
                </button>
            </form>
        </div>
    </div>

    <script>
        function confirmDeleteArticle(id) {
            if (window.MobilePopup) {
                window.MobilePopup.confirm({
                    title: 'Hapus Artikel?',
                    description: 'Tindakan ini akan menghapus artikel secara permanen beserta datanya dari server. Apakah Anda yakin?',
                    confirmText: 'Ya, Hapus',
                    cancelText: 'Batal',
                    onConfirm: () => {
                        document.getElementById('delete-article-form-' + id).submit();
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
                    document.getElementById('delete-article-form-' + id).submit();
                }
            }
        }
    </script>
@endsection
