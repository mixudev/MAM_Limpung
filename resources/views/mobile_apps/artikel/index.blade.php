@extends('mobile_apps.layouts.apps')

@section('content')
    <div class="px-5 pt-4 pb-20 relative">
        <!-- Header & Back Button -->
        <div class="flex items-center gap-3 mb-5">
            <a href="{{ route('apps.home') }}" class="w-8 h-8 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-600 shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-sora font-bold text-slate-800 text-base">Artikel Saya</h2>
        </div>



        <!-- Floating Action Button (FAB) pointing to new creation page -->
        <a href="{{ route('apps.artikel.create') }}" 
           class="fixed bottom-24 right-5 z-40 w-12 h-12 bg-primary-600 hover:bg-primary-700 text-white rounded-full shadow-[0_6px_20px_rgba(79,69,178,0.4)] active:scale-95 flex items-center justify-center transition-all duration-200 cursor-pointer"
           title="Tulis Artikel Baru">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </a>

        <!-- Articles List Section -->
        <div>
            <div class="space-y-3">
                @forelse($articles as $article)
                    <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-xs flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 relative shrink-0">
                                    <img src="{{ $article->thumbnailUrl() }}" alt="Thumbnail" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-sora font-bold text-slate-800 text-xs truncate max-w-[170px]">{{ $article->judul }}</h4>
                                    <p class="text-[9px] text-slate-400 mt-0.5">{{ $article->category->name ?? 'Kategori' }} &middot; {{ $article->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            
                            <div>
                                @if($article->status === 'published')
                                    <span class="text-[8px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Terbit
                                    </span>
                                @elseif($article->status === 'pending')
                                    <span class="text-[8px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-100">
                                        Pending
                                    </span>
                                @else
                                    <span class="text-[8px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider bg-slate-50 text-slate-500 border border-slate-200">
                                        Draf
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Action Row -->
                        <div class="flex gap-2 border-t border-slate-50 pt-2">
                            <a href="{{ route('apps.artikel.show', $article) }}" class="flex-1 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold text-center border border-slate-100 transition-colors">
                                Baca
                            </a>
                            <a href="{{ route('apps.artikel.edit', $article) }}" class="flex-1 py-1.5 bg-indigo-50/50 hover:bg-indigo-50 text-primary-600 rounded-lg text-[10px] font-bold text-center border border-indigo-50 transition-colors">
                                Edit
                            </a>
                            <form id="delete-article-form-{{ $article->id }}" action="{{ route('apps.artikel.destroy', $article) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeleteArticle({{ $article->id }}, '{{ addslashes($article->judul) }}')" class="w-full py-1.5 bg-rose-50 hover:bg-rose-100/50 text-rose-600 rounded-lg text-[10px] font-bold text-center border border-rose-100/50 cursor-pointer transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 text-center">
                        <p class="text-xs text-slate-400 font-medium">Belum ada riwayat artikel.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        // Mobile Delete Confirmation Alert
        function confirmDeleteArticle(id, title) {
            if (window.MobilePopup) {
                window.MobilePopup.confirm({
                    title: 'Hapus Artikel?',
                    description: `Apakah Anda yakin ingin menghapus artikel "<strong>${title}</strong>" secara permanen dari server?`,
                    confirmText: 'Ya, Hapus',
                    cancelText: 'Batal',
                    onConfirm: () => {
                        document.getElementById('delete-article-form-' + id).submit();
                    }
                });
            } else {
                if (confirm(`Apakah Anda yakin ingin menghapus artikel "${title}"?`)) {
                    document.getElementById('delete-article-form-' + id).submit();
                }
            }
        }
    </script>
@endsection
