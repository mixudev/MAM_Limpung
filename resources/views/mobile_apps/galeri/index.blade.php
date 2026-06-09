@extends('mobile_apps.layouts.apps')

@section('content')
    <div class="px-5 pt-4 pb-20 relative">




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
                <div class="bg-white border border-slate-100 overflow-hidden flex flex-col" style="border-radius: 8px;">

                    {{-- Thumbnail --}}
                    <div class="aspect-square w-full relative bg-slate-50 overflow-hidden">
                        <img src="{{ $gallery->coverUrl() }}" alt="Cover" class="w-full h-full object-cover block">

                        {{-- Status badge --}}
                        <span class="absolute top-1.5 left-1.5 text-[9px] font-medium px-1.5 py-0.5 tracking-wide"
                            style="border-radius: 3px;
                            {{ $gallery->status === 'approved'
                                ? 'background:#3B6D11; color:#EAF3DE;'
                                : ($gallery->status === 'rejected'
                                    ? 'background:#A32D2D; color:#FCEBEB;'
                                    : 'background:#854F0B; color:#FAEEDA;') }}">
                            {{ $gallery->status }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="px-2.5 pt-2">
                        <p class="font-sora font-medium text-slate-800 text-[11px] truncate leading-tight m-0">
                            {{ $gallery->judul }}
                        </p>
                        <p class="text-[10px] text-slate-400 mt-0.5 m-0">
                            {{ $gallery->kategori }} &middot; {{ $gallery->tahun }}
                        </p>
                        @if($gallery->status === 'rejected' && $gallery->rejected_reason)
                            <p class="text-[10px] mt-1 truncate m-0" style="color:#A32D2D;" title="{{ $gallery->rejected_reason }}">
                                {{ $gallery->rejected_reason }}
                            </p>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-1.5 mt-2 px-2.5 pb-2.5 pt-2 border-t border-slate-100">
                        <a href="{{ route('apps.galeri.show', $gallery) }}"
                        class="flex-1 py-1.5 text-center text-[10px] font-medium text-slate-600 bg-slate-50 border border-slate-200 transition-colors hover:bg-slate-100"
                        style="border-radius: 4px;">
                            Lihat
                        </a>
                        <a href="{{ route('apps.galeri.edit', $gallery) }}"
                        class="flex-1 py-1.5 text-center text-[10px] font-medium transition-colors hover:opacity-80"
                        style="border-radius: 4px; background:#EEEDFE; color:#534AB7; border:0.5px solid #AFA9EC;">
                            Edit
                        </a>
                        <form id="delete-gallery-form-{{ $gallery->id }}" action="{{ route('apps.galeri.destroy', $gallery) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    onclick="confirmDeleteGallery({{ $gallery->id }}, '{{ addslashes($gallery->judul) }}')"
                                    class="py-1.5 px-2.5 transition-colors hover:opacity-80 cursor-pointer"
                                    style="border-radius: 4px; background:#FCEBEB; color:#A32D2D; border:0.5px solid #F7C1C1; font-size:12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                @empty
                <div class="col-span-2 flex flex-col items-center justify-center gap-2.5 py-10 text-center border border-dashed border-slate-200" style="border-radius: 8px;">
                    <div class="w-11 h-11 flex items-center justify-center bg-slate-50" style="border-radius: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/><line x1="2" y1="2" x2="22" y2="22"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-medium text-slate-700 m-0">Belum ada galeri</p>
                        <p class="text-[11px] text-slate-400 mt-0.5 m-0">Foto yang kamu upload akan muncul di sini.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        // Mobile Delete Confirmation Alert
        function confirmDeleteGallery(id, title) {
            if (window.MobilePopup) {
                window.MobilePopup.warning({
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
