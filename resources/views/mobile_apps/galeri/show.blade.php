@extends('mobile_apps.layouts.apps')

@section('content')
<div class="min-h-screen bg-slate-50">

    {{-- Topbar --}}
    <div class="flex items-center justify-between px-4 pt-4 pb-2.5">
        <div class="flex items-center gap-2.5 py-3">
            <a href="{{ route('apps.galeri') }}"
               class="w-8 h-8 bg-white border border-slate-100 flex items-center justify-center text-slate-600"
               style="border-radius:6px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-sora font-medium text-slate-800 text-[13px]">Detail Galeri</h2>
        </div>
        <span class="text-[9px] font-medium px-2.5 py-1 uppercase tracking-wide"
            style="border-radius:3px;
            {{ $galeri->status === 'approved'
                ? 'background:#3B6D11;color:#EAF3DE;'
                : ($galeri->status === 'rejected'
                    ? 'background:#A32D2D;color:#FCEBEB;'
                    : 'background:#854F0B;color:#FAEEDA;') }}">
            {{ $galeri->status }}
        </span>
    </div>

    {{-- Rejected reason --}}
    @if($galeri->status === 'rejected' && $galeri->rejected_reason)
    <div class="mx-4 mb-3 flex gap-2.5 items-start p-3"
         style="background:#FCEBEB;border:0.5px solid #F7C1C1;border-radius:6px;">
        <svg width="14" height="14" fill="none" stroke="#A32D2D" stroke-width="2" viewBox="0 0 24 24" class="shrink-0 mt-0.5">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16" stroke-width="3"/>
        </svg>
        <div>
            <p class="text-[10px] font-medium text-rose-800 m-0">Alasan penolakan</p>
            <p class="text-[11px] text-rose-700 mt-0.5 m-0 italic">{{ $galeri->rejected_reason }}</p>
        </div>
    </div>
    @endif

    {{-- Carousel --}}
    <div class="mx-4 overflow-hidden bg-slate-200 relative" style="border-radius:8px;" id="cWrap">
        <div class="flex transition-transform duration-400" id="cTrack" style="transition: transform .4s cubic-bezier(.25,.46,.45,.94);">
            @foreach($galeri->photos as $photo)
            <div class="shrink-0 w-full " style="aspect-ratio:4/3;">
                <img src="{{ Storage::url($photo->file_path) }}"
                     alt="Foto"
                     class="w-full h-full object-cover block">
            </div>
            @endforeach
        </div>

        @if($galeri->photos->where('is_cover', true)->count())
        <span id="coverBadge"
              class="absolute top-2.5 left-2.5 text-[9px] font-medium px-2 py-0.5"
              style="border-radius:3px;background:#534AB7;color:#EEEDFE;transition:opacity .3s;">
            Cover utama
        </span>
        @endif
    </div>

    {{-- Dots --}}
    @if($galeri->photos->count() > 1)
    <div class="flex justify-center gap-1.5 mt-2.5" id="dotWrap">
        @foreach($galeri->photos as $i => $photo)
        <div class="dot h-1.5 transition-all duration-300 {{ $i === 0 ? 'w-4 bg-indigo-600' : 'w-1.5 bg-slate-300' }}"
             style="border-radius:3px;"></div>
        @endforeach
    </div>
    @endif

    {{-- Thumbnail strip --}}
    @if($galeri->photos->count() > 1)
    <div class="flex gap-1.5 overflow-x-auto px-4 mt-2.5 pb-0.5" id="thumbStrip"
         style="scrollbar-width:none;">
        @foreach($galeri->photos as $i => $photo)
        <div class="shrink-0 cursor-pointer thumb {{ $i === 0 ? 'ring-1 ring-indigo-500' : '' }}"
             data-idx="{{ $i }}"
             style="width:48px;height:48px;border-radius:5px;overflow:hidden;border:1.5px solid {{ $i === 0 ? '#534AB7' : 'transparent' }};transition:border-color .2s;">
            <img src="{{ Storage::url($photo->file_path) }}"
                 alt="Thumb"
                 class="w-full h-full object-cover block">
        </div>
        @endforeach
    </div>
    @endif

    {{-- Meta --}}
    <div class="px-4 mt-3.5 bg-white border border-slate-100 overflow-hidden mb-4" style="border-radius:8px;">
        <div class="flex gap-1.5 px-3.5 pt-3.5">
            <span class="text-[9px] font-medium px-2 py-0.5 uppercase tracking-wide"
                  style="border-radius:3px;background:#EEEDFE;color:#534AB7;">
                {{ $galeri->kategori }}
            </span>
            <span class="text-[9px] font-medium px-2 py-0.5 uppercase tracking-wide border border-slate-200"
                  style="border-radius:3px;background:#f8fafc;color:#64748b;">
                {{ $galeri->tahun }}
            </span>
        </div>

        <h3 class="font-sora font-medium text-slate-800 text-[15px] leading-snug px-3.5 pt-2.5">
            {{ $galeri->judul }}
        </h3>

        <p class="text-xs text-slate-500 leading-relaxed px-3.5 pt-2 pb-0 whitespace-pre-line">
            {{ $galeri->deskripsi }}
        </p>

        <div class="flex items-center gap-1.5 px-3.5 py-3 mt-3 border-t border-slate-100">
            <svg width="11" height="11" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            <p class="text-[10px] text-slate-400 m-0">
                Diunggah {{ $galeri->created_at->format('d M Y') }} &middot; {{ $galeri->created_at->format('H:i') }} WIB
            </p>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-2.5 px-4 pb-8">
        <a href="{{ route('apps.galeri.edit', $galeri) }}"
           class="flex-1 py-3 text-center text-xs font-medium flex items-center justify-center gap-1.5"
           style="border-radius:6px;background:#EEEDFE;color:#534AB7;border:0.5px solid #AFA9EC;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Edit galeri
        </a>
        <form id="delete-gallery-form-{{ $galeri->id }}"
              action="{{ route('apps.galeri.destroy', $galeri) }}"
              method="POST" class="flex-1">
            @csrf
            @method('DELETE')
            <button type="button"
                    onclick="confirmDeleteGallery({{ $galeri->id }})"
                    class="w-full py-3 text-xs font-medium flex items-center justify-center gap-1.5 cursor-pointer"
                    style="border-radius:6px;background:#FCEBEB;color:#A32D2D;border:0.5px solid #F7C1C1;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
                Hapus
            </button>
        </form>
    </div>
</div>

<script>
(function(){
    const track  = document.getElementById('cTrack');
    const dotWrap= document.getElementById('dotWrap');
    const strip  = document.getElementById('thumbStrip');
    const badge  = document.getElementById('coverBadge');
    if (!track) return;

    const dots   = dotWrap  ? dotWrap.querySelectorAll('.dot')   : [];
    const thumbs = strip    ? strip.querySelectorAll('.thumb')   : [];
    const total  = track.children.length;
    const coverIdx = {{ $galeri->photos->search(fn($p) => $p->is_cover) ?? 0 }};
    let cur = 0, timer = null;

    function goTo(idx) {
        cur = (idx + total) % total;
        track.style.transform = `translateX(-${cur * 100}%)`;

        dots.forEach((d, i) => {
            d.style.width    = i === cur ? '16px' : '6px';
            d.style.background = i === cur ? '#4338ca' : '#cbd5e1';
        });

        thumbs.forEach((t, i) => {
            t.style.borderColor = i === cur ? '#534AB7' : 'transparent';
        });

        if (badge) badge.style.opacity = cur === coverIdx ? '1' : '0';

        if (thumbs[cur]) {
            thumbs[cur].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    }

    function startAuto() {
        clearInterval(timer);
        timer = setInterval(() => goTo(cur + 1), 3500);
    }

    thumbs.forEach(t => {
        t.addEventListener('click', () => {
            goTo(parseInt(t.dataset.idx));
            startAuto();
        });
    });

    if (total > 1) startAuto();
})();

function confirmDeleteGallery(id) {
    if (window.MobilePopup) {
        window.MobilePopup.confirm({
            title: 'Hapus galeri?',
            description: 'Semua foto dalam galeri ini akan dihapus permanen dari server.',
            confirmText: 'Ya, hapus',
            cancelText: 'Batal',
            onConfirm: () => document.getElementById('delete-gallery-form-' + id).submit()
        });
    } else {
        if (confirm('Yakin ingin menghapus galeri ini?')) {
            document.getElementById('delete-gallery-form-' + id).submit();
        }
    }
}
</script>
@endsection