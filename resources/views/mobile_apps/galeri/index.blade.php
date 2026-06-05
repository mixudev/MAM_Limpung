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
            <h2 class="font-sora font-bold text-slate-800 text-base">Moment</h2>
        </div>



        <!-- Floating Action Button (FAB) for adding new gallery -->
        <a href="{{ route('apps.galeri.create') }}" 
           class="fixed bottom-24 right-5 z-40 w-12 h-12 bg-primary-600 hover:bg-primary-700 text-white rounded-full shadow-[0_6px_20px_rgba(79,69,178,0.4)] active:scale-95 flex items-center justify-center transition-all duration-200 cursor-pointer"
           title="Unggah Galeri Baru">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </a>

        <!-- Gallery Grid Section (2 Boxes per Row) -->
        <div>
            
            <div class="grid grid-cols-2 gap-3">
                @forelse($galleries as $gallery)
                    <div class="bg-white border border-slate-100 rounded-2xl p-2.5 shadow-xs flex flex-col justify-between relative">
                        <div>
                            <!-- Aspect ratio square cover image -->
                            <div class="aspect-square w-full rounded-xl overflow-hidden bg-slate-50 relative shrink-0">
                                <img src="{{ $gallery->coverUrl() }}" alt="Cover" class="w-full h-full object-cover">
                                
                                <!-- Status Badge on top of image -->
                                <span class="absolute top-2 left-2 text-[7px] px-2 py-0.5 rounded-md font-bold uppercase tracking-wider text-white shadow-xs
                                    {{ $gallery->status === 'approved' ? 'bg-emerald-500' : ($gallery->status === 'rejected' ? 'bg-rose-500' : 'bg-amber-500') }}">
                                    {{ $gallery->status }}
                                </span>
                            </div>
                            
                            <div class="mt-2.5 px-1">
                                <h4 class="font-sora font-bold text-slate-800 text-[10px] leading-tight truncate" title="{{ $gallery->judul }}">{{ $gallery->judul }}</h4>
                                <p class="text-[8px] text-slate-400 font-semibold mt-0.5">{{ $gallery->kategori }} &middot; {{ $gallery->tahun }}</p>
                                
                                @if($gallery->status === 'rejected' && $gallery->rejected_reason)
                                    <p class="text-[7px] text-rose-500 mt-1 font-medium italic truncate" title="{{ $gallery->rejected_reason }}">
                                        {{ $gallery->rejected_reason }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Action Row -->
                        <div class="flex gap-1.5 border-t border-slate-50 pt-2 mt-3 px-1">
                            <a href="{{ route('apps.galeri.show', $gallery) }}" class="flex-1 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-lg text-[9px] font-bold text-center border border-slate-100 transition-colors">
                                Lihat
                            </a>
                            <a href="{{ route('apps.galeri.edit', $gallery) }}" class="flex-1 py-1.5 bg-indigo-50/50 hover:bg-indigo-50 text-primary-600 rounded-lg text-[9px] font-bold text-center border border-indigo-50 transition-colors">
                                Edit
                            </a>
                            <form id="delete-gallery-form-{{ $gallery->id }}" action="{{ route('apps.galeri.destroy', $gallery) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeleteGallery({{ $gallery->id }}, '{{ addslashes($gallery->judul) }}')" class="py-1.5 px-2 bg-rose-50 hover:bg-rose-100/50 text-rose-600 rounded-lg text-[9px] font-bold text-center border border-rose-100/50 cursor-pointer transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 bg-slate-50 border border-slate-100 rounded-2xl p-6 text-center">
                        <p class="text-xs text-slate-400 font-medium">Belum ada riwayat usulan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        // Mobile Delete Confirmation Alert
        function confirmDeleteGallery(id, title) {
            if (window.MobilePopup) {
                window.MobilePopup.confirm({
                    title: 'Hapus Galeri?',
                    description: `Apakah Anda yakin ingin menghapus galeri "<strong>${title}</strong>" beserta seluruh fotonya secara permanen dari server?`,
                    confirmText: 'Ya, Hapus',
                    cancelText: 'Batal',
                    onConfirm: () => {
                        document.getElementById('delete-gallery-form-' + id).submit();
                    }
                });
            } else {
                if (confirm(`Apakah Anda yakin ingin menghapus galeri "${title}"?`)) {
                    document.getElementById('delete-gallery-form-' + id).submit();
                }
            }
        }
    </script>
@endsection
