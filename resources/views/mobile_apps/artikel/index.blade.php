@extends('mobile_apps.layouts.apps')

@section('content')
    <div class="px-5 pt-4 pb-20 relative">
        <!-- Header & Back Button -->
        {{-- <div class="flex items-center gap-3 mb-5">
            <a href="{{ route('apps.home') }}" class="w-8 h-8 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-600 shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-sora font-bold text-slate-800 text-base">Artikel Saya</h2>
        </div> --}}



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
            <div class="space-y-3 h-full">
                @forelse($articles as $article)
                    <div class="bg-white border border-slate-100 rounded-2xl p-3.5 shadow-xs flex flex-col gap-2.5">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-11 h-11 rounded-xl overflow-hidden bg-slate-100 shrink-0 flex items-center justify-center">
                                    <img src="{{ $article->thumbnailUrl() }}" alt="Thumbnail"
                                        class="w-full h-full object-cover"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                    <span style="display:none" class="w-full h-full items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-sora font-medium text-slate-800 text-[13px] truncate max-w-[155px]">
                                        {{ $article->judul }}
                                    </h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $article->category->name ?? 'Kategori' }} &middot; {{ $article->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>

                            @if($article->status === 'published')
                                <span class="text-[9px] px-2 py-0.5 rounded-full font-medium uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">Terbit</span>
                            @elseif($article->status === 'pending')
                                <span class="text-[9px] px-2 py-0.5 rounded-full font-medium uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-200 shrink-0">Pending</span>
                            @else
                                <span class="text-[9px] px-2 py-0.5 rounded-full font-medium uppercase tracking-wide bg-slate-50 text-slate-500 border border-slate-200 shrink-0">Draf</span>
                            @endif
                        </div>

                        <div class="flex gap-1.5 border-t border-slate-100 pt-2.5">
                            <a href="{{ route('apps.artikel.show', $article) }}"
                            class="flex-1 py-1.5 flex items-center justify-center gap-1 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-lg text-[11px] font-medium border border-slate-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Baca
                            </a>
                            <a href="{{ route('apps.artikel.edit', $article) }}"
                            class="flex-1 py-1.5 flex items-center justify-center gap-1 bg-indigo-50 hover:bg-indigo-100/70 text-indigo-600 rounded-lg text-[11px] font-medium border border-indigo-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </a>
                            <form id="delete-article-form-{{ $article->id }}" action="{{ route('apps.artikel.destroy', $article) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        onclick="confirmDeleteArticle({{ $article->id }}, '{{ addslashes($article->judul) }}')"
                                        class="w-full py-1.5 flex items-center justify-center gap-1 bg-rose-50 hover:bg-rose-100/70 text-rose-600 rounded-lg text-[11px] font-medium border border-rose-100 transition-colors cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    @empty
                        <div class="border border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center min-h-[280px] py-12 px-6 gap-3 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2z"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="13" x2="11" y2="13"/><line x1="17" y1="17" x2="17.01" y2="17"/></svg>
                            </div>
                            <div>
                                <p class="text-[13px] font-medium text-slate-700">Belum ada artikel</p>
                            <p class="text-[12px] text-slate-400 mt-1">Artikel yang kamu buat akan muncul di sini.</p>
                        </div>
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
