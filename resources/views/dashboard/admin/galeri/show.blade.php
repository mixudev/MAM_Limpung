@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Detail Galeri Foto';
        }
    });
</script>

<div class="max-w-4xl space-y-6" x-data="{ showRejectModal: false }">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="px-2.5 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase border inline-block mb-2
                @if($galeri->status === 'approved') bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-800/40
                @elseif($galeri->status === 'pending') bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-800/40
                @else bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-800/40
                @endif">
                {{ strtoupper($galeri->status) }}
            </span>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white leading-snug">{{ $galeri->judul }}</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Diunggah oleh <span class="font-semibold text-[#4f45b2] dark:text-indigo-400">{{ $galeri->pengunggah->name }}</span> &bull; Kategori: {{ $galeri->kategori ?? 'Umum' }} &bull; Tahun: {{ $galeri->tahun }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.galeri.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center font-mono">
                KEMBALI
            </a>

            @can('update', $galeri)
                <a href="{{ route('admin.galeri.edit', $galeri->uuid) }}" class="py-2.5 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider flex items-center justify-center gap-2 font-mono">
                    EDIT
                </a>
            @endcan
        </div>
    </div>

    <!-- Alert / Approval Status Cards -->
    @if($galeri->status === 'rejected')
        <div class="bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/50 p-6 shadow-sm space-y-2">
            <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-rose-700 dark:text-rose-450 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Galeri Ditolak oleh Administrator
            </h3>
            <p class="text-xs text-slate-650 dark:text-zinc-400">
                Ditolak oleh: <span class="font-bold text-slate-800 dark:text-zinc-200">{{ $galeri->approvedBy->name ?? 'Admin' }}</span> pada {{ $galeri->approved_at?->translatedFormat('d M Y H:i') }}
            </p>
            <div class="p-3 bg-white dark:bg-zinc-900 border border-rose-100 dark:border-rose-950 text-xs text-slate-700 dark:text-zinc-350 italic rounded-none mt-2">
                "{{ $galeri->rejected_reason }}"
            </div>
        </div>
    @elseif($galeri->status === 'approved')
        <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/50 p-4 shadow-sm text-xs text-slate-700 dark:text-zinc-300">
            Disetujui dan dipublikasikan oleh <span class="font-bold text-slate-800 dark:text-zinc-200">{{ $galeri->approvedBy->name ?? 'Admin' }}</span> pada {{ $galeri->approved_at?->translatedFormat('d M Y H:i') }}
        </div>
    @endif

    <!-- Admin Approval Actions -->
    @if($galeri->status === 'pending' && Auth::user()->can('approve', App\Models\Galeri::class))
        <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-250 dark:border-amber-900/50 p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-amber-700 dark:text-amber-450">Tinjau Galeri Pendulum</h3>
                <p class="text-[11px] text-slate-500 dark:text-zinc-400">Unggahan ini dibuat oleh Siswa/Guru dan memerlukan verifikasi Anda sebelum dipublikasikan ke publik.</p>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <button @click="showRejectModal = true" class="py-2 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-none transition-all tracking-wider font-mono">
                    TOLAK
                </button>
                <form action="{{ route('admin.galeri.approve', $galeri->uuid) }}" 
                    method="POST" class="inline" 
                    id="approve-form-{{ $galeri->uuid }}">
                    @csrf
                    <button 
                    type="button"
                    onclick="AppPopup.confirm({
                                title: 'Setujui Galeri?',
                                description: 'Aksi ini akan menyetujui dan mempublikasikan galeri ke publik.',
                                confirmText: 'Ya, Setujui',
                                cancelText: 'Batal',
                                onConfirm: () => document.getElementById('approve-form-{{ $galeri->uuid }}').submit()
                            })" 
                    class="py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-none transition-all tracking-wider font-mono">
                        SETUJUI & TAMPILKAN
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Description & Gallery Grid -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm space-y-6">
        @if($galeri->deskripsi)
            <div class="space-y-2">
                <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Keterangan / Cerita Album</h3>
                <p class="text-xs text-slate-700 dark:text-zinc-350 leading-relaxed font-sans">{{ $galeri->deskripsi }}</p>
            </div>
        @endif

        <div class="space-y-3">
            <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Daftar Foto / Gambar ({{ $galeri->photos->count() }})</h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($galeri->photos as $photo)
                    <div class="relative border border-slate-200 dark:border-zinc-800 p-2 bg-slate-50 dark:bg-zinc-950 flex flex-col justify-between group">
                        <div class="aspect-square bg-slate-100 dark:bg-zinc-900 overflow-hidden relative border border-slate-200 dark:border-zinc-800">
                            <img src="{{ $photo->imageUrl() }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            @if($photo->is_cover)
                                <div class="absolute top-1.5 left-1.5 bg-amber-500 text-blue-900 text-[8px] font-bold px-1.5 py-0.5 uppercase tracking-wide shadow-sm">
                                    Sampul Utama
                                </div>
                            @endif
                        </div>
                        <div class="mt-2 text-left">
                            <span class="text-[9px] font-mono text-slate-500 truncate block uppercase tracking-wider">
                                {{ $photo->tipe === 'file' ? 'Berkas Lokal' : 'Tautan URL' }}
                            </span>
                            @if($photo->tipe === 'link')
                                <a href="{{ $photo->file_path }}" target="_blank" class="text-[9px] text-[#4f45b2] dark:text-indigo-400 underline truncate block mt-0.5">Buka Tautan Asli &rarr;</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div x-show="showRejectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;" x-transition.opacity>
        <div class="bg-white dark:bg-zinc-900 border border-slate-250 dark:border-zinc-800 w-full max-w-md p-6 shadow-2xl relative" @click.away="showRejectModal = false">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider font-mono mb-4">Masukkan Alasan Penolakan</h3>
            
            <form action="{{ route('admin.galeri.reject', $galeri->uuid) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                    <textarea name="reason" required rows="4" placeholder="Contoh: Foto tidak jelas / kabur, atau judul postingan tidak sopan."
                        class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:border-[#4f45b2] resize-none"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-zinc-800">
                    <button type="button" @click="showRejectModal = false" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all font-mono">
                        BATAL
                    </button>
                    <button type="submit" class="py-2 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-none transition-all tracking-wider font-mono">
                        KIRIM PENOLAKAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
