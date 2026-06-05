@extends('mobile_apps.layouts.apps')

@section('content')
    <div class="px-5 pt-4">
        <!-- Header & Back Button -->
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <a href="{{ route('apps.galeri') }}" class="w-8 h-8 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-600 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="font-sora font-bold text-slate-800 text-sm">Detail Galeri</h2>
            </div>
            
            <span class="text-[9px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide
                {{ $galeri->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : ($galeri->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-100' : 'bg-amber-50 text-amber-700 border border-amber-100') }}">
                {{ $galeri->status }}
            </span>
        </div>

        @if($galeri->status === 'rejected' && $galeri->rejected_reason)
            <div class="mb-4 bg-rose-50 border border-rose-100 text-rose-800 text-xs rounded-2xl p-4 flex flex-col gap-1 shadow-xs">
                <span class="font-bold">Alasan Penolakan:</span>
                <span class="font-medium italic">"{{ $galeri->rejected_reason }}"</span>
            </div>
        @endif

        <!-- Card Content -->
        <div class="bg-white border border-slate-100 shadow-xs rounded-3xl overflow-hidden mb-6">
            <!-- Cover / Carousel (just list images) -->
            <div class="space-y-2 p-3">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Foto Kegiatan ({{ $galeri->photos->count() }})</p>
                <div class="grid grid-cols-1 gap-3">
                    @foreach($galeri->photos as $photo)
                        <div class="w-full aspect-[4/3] rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 relative group">
                            <img src="{{ Storage::url($photo->file_path) }}" alt="Photo" class="w-full h-full object-cover">
                            @if($photo->is_cover)
                                <span class="absolute top-3 left-3 text-[8px] bg-primary-600 text-white font-bold px-2 py-0.5 rounded-md uppercase tracking-wider shadow-xs">Cover Utama</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Meta details -->
            <div class="p-5 border-t border-slate-150">
                <span class="text-[9px] bg-indigo-50 text-primary-600 border border-primary-100 px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">{{ $galeri->kategori }}</span>
                <span class="text-[9px] bg-slate-50 text-slate-500 border border-slate-100 px-2 py-0.5 rounded-md font-bold uppercase tracking-wider ml-1">Tahun {{ $galeri->tahun }}</span>
                
                <h3 class="font-sora font-bold text-slate-800 text-base mt-3 leading-snug">{{ $galeri->judul }}</h3>
                <p class="text-xs text-slate-500 font-medium leading-relaxed mt-2.5 whitespace-pre-line">{{ $galeri->deskripsi }}</p>
                
                <p class="text-[9px] text-slate-400 mt-5 font-semibold">Diunggah pada: {{ $galeri->created_at->format('d M Y - H:i') }} WIB</p>
            </div>
        </div>

        <!-- Action options for edit / delete -->
        <div class="flex gap-3 mb-8">
            <a href="{{ route('apps.galeri.edit', $galeri) }}" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-center rounded-2xl text-xs font-bold transition-colors">
                Edit Galeri
            </a>
            
            <form id="delete-gallery-form-{{ $galeri->id }}" action="{{ route('apps.galeri.destroy', $galeri) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDeleteGallery({{ $galeri->id }})" class="w-full py-3 bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 rounded-2xl text-xs font-bold transition-all cursor-pointer">
                    Hapus Galeri
                </button>
            </form>
        </div>
    </div>

    <script>
        function confirmDeleteGallery(id) {
            if (window.MobilePopup) {
                window.MobilePopup.confirm({
                    title: 'Hapus Galeri?',
                    description: 'Tindakan ini akan menghapus galeri secara permanen beserta seluruh fotonya dari server. Apakah Anda yakin?',
                    confirmText: 'Ya, Hapus',
                    cancelText: 'Batal',
                    onConfirm: () => {
                        document.getElementById('delete-gallery-form-' + id).submit();
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus galeri ini?')) {
                    document.getElementById('delete-gallery-form-' + id).submit();
                }
            }
        }
    </script>
@endsection
