@extends('mobile_apps.layouts.apps')

@section('content')
    <!-- Statistics Row -->
    <section class="reveal px-5 pt-4">
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-white border border-slate-100/80 rounded-2xl p-3 flex flex-col items-center shadow-xs">
                <span class="text-xs font-semibold text-slate-400">Tugas Aktif</span>
                <span class="text-lg font-bold text-amber-500 mt-1">{{ $stats['total_tugas'] }}</span>
            </div>
            <div class="bg-white border border-slate-100/80 rounded-2xl p-3 flex flex-col items-center shadow-xs">
                <span class="text-xs font-semibold text-slate-400">Galeri Saya</span>
                <span class="text-lg font-bold text-primary-600 mt-1">{{ $stats['total_galeri'] }}</span>
            </div>
            <div class="bg-white border border-slate-100/80 rounded-2xl p-3 flex flex-col items-center shadow-xs">
                <span class="text-xs font-semibold text-slate-400">Artikel Saya</span>
                <span class="text-lg font-bold text-emerald-600 mt-1">{{ $stats['total_artikel'] }}</span>
            </div>
        </div>
    </section>

    <!-- Quick Access Menu -->
    <section class="reveal px-5 pt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-sora font-bold text-slate-800 text-sm">Akses Fitur Utama</h3>
        </div>
        <div class="grid grid-cols-4 gap-3">
            <!-- Galeri -->
            <a href="{{ route('apps.galeri') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shadow-xs group-active:scale-95 transition-all">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="text-[10px] text-slate-600 font-bold text-center leading-tight">Unggah Galeri</span>
            </a>
            <!-- Artikel -->
            <a href="{{ route('apps.artikel') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center shadow-xs group-active:scale-95 transition-all">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <span class="text-[10px] text-slate-600 font-bold text-center leading-tight">Tulis Artikel</span>
            </a>
            <!-- Tugas -->
            <a href="{{ route('apps.tugas') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center shadow-xs group-active:scale-95 transition-all">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span class="text-[10px] text-slate-600 font-bold text-center leading-tight">Tugas Sekolah</span>
            </a>
            <!-- Profil -->
            <a href="{{ route('apps.profile') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center shadow-xs group-active:scale-95 transition-all">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span class="text-[10px] text-slate-600 font-bold text-center leading-tight">Profil Saya</span>
            </a>
        </div>


    </section>

    <!-- Recent Tasks Section -->
    {{-- <section class="reveal px-5 mt-7">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-sora font-bold text-slate-800 text-sm">Tugas Mendatang</h3>
            <a href="{{ route('apps.tugas') }}" class="text-xs text-primary-600 font-bold">Lihat Semua</a>
        </div>
        
        <div class="space-y-3">
            <div class="bg-white border border-slate-100 shadow-xs rounded-2xl p-4 flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] bg-red-50 text-red-600 border border-red-100 px-2 py-0.5 rounded-full font-bold">Tinggi</span>
                        <span class="text-[10px] text-slate-400 font-medium">Sisa 2 hari</span>
                    </div>
                    <h4 class="font-sora font-bold text-slate-800 text-xs mt-1.5">Turunan Fungsi Trigonometri</h4>
                    <p class="text-[10px] text-slate-500 mt-0.5 font-semibold">Matematika Peminatan &middot; Dra. Siti Rahmah</p>
                </div>
            </div>

            <div class="bg-white border border-slate-100 shadow-xs rounded-2xl p-4 flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] bg-amber-50 text-amber-600 border border-amber-100 px-2 py-0.5 rounded-full font-bold">Sedang</span>
                        <span class="text-[10px] text-slate-400 font-medium">Sisa 5 hari</span>
                    </div>
                    <h4 class="font-sora font-bold text-slate-800 text-xs mt-1.5">Analytical Exposition Essay</h4>
                    <p class="text-[10px] text-slate-500 mt-0.5 font-semibold">Bahasa Inggris &middot; Budi Santoso, S.Pd.</p>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Recent Uploads Section -->
    {{-- <section class="reveal px-5 mt-7">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-sora font-bold text-slate-800 text-sm">Galeri Saya</h3>
            <a href="{{ route('apps.galeri') }}" class="text-xs text-primary-600 font-bold">Kelola</a>
        </div>

        @if($recentGalleries->isEmpty())
            <div class="bg-white border border-slate-100 rounded-2xl p-6 text-center shadow-xs">
                <svg class="w-10 h-10 text-slate-300 mx-auto" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-xs text-slate-400 font-medium mt-2">Belum ada usulan galeri dari Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-3 gap-2">
                @foreach($recentGalleries as $gallery)
                    <div class="bg-white border border-slate-100 shadow-xs rounded-xl overflow-hidden p-1">
                        <div class="aspect-square bg-slate-100 rounded-lg overflow-hidden relative">
                            <img src="{{ $gallery->coverUrl() }}" alt="{{ $gallery->judul }}" class="w-full h-full object-cover">
                            <span class="absolute bottom-1 left-1 text-[8px] px-1.5 py-0.5 rounded-md font-bold text-white shadow-xs
                                {{ $gallery->status === 'approved' ? 'bg-emerald-500' : ($gallery->status === 'rejected' ? 'bg-rose-500' : 'bg-amber-500') }}">
                                {{ ucfirst($gallery->status) }}
                            </span>
                        </div>
                        <p class="text-[9px] font-bold text-slate-700 truncate px-1 mt-1">{{ $gallery->judul }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </section> --}}

    <!-- Recent Articles Section -->
    <section class="reveal px-5 mt-7">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-sora font-bold text-slate-800 text-sm">Artikel Saya</h3>
            <a href="{{ route('apps.artikel') }}" class="text-xs text-primary-600 font-bold">Kelola</a>
        </div>

        @if($recentArticles->isEmpty())
            <div class="bg-white border border-slate-100 rounded-2xl p-6 text-center shadow-xs">
                <svg class="w-10 h-10 text-slate-300 mx-auto" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <p class="text-xs text-slate-400 font-medium mt-2">Belum ada draf artikel dari Anda.</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($recentArticles as $article)
                    <div class="bg-white border border-slate-100 shadow-xs rounded-2xl p-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $article->thumbnailUrl() }}" alt="{{ $article->judul }}" class="w-12 h-12 rounded-lg object-cover bg-slate-50 shrink-0">
                            <div>
                                <h4 class="font-sora font-bold text-slate-800 text-xs truncate max-w-[180px]">{{ $article->judul }}</h4>
                                <p class="text-[9px] text-slate-400 mt-0.5">{{ $article->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <span class="text-[9px] px-2 py-0.5 rounded-full font-bold
                            {{ $article->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                            {{ $article->status === 'published' ? 'Terbit' : 'Draf' }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <script>
        function showDummyAlert(featureName) {
            if (window.MobilePopup) {
                window.MobilePopup.info({
                    title: 'Fitur ' + featureName,
                    description: 'Halaman ' + featureName + ' sedang dalam pengembangan sistem mobile app Portal Siswa MAM Limpung.',
                    confirmText: 'Ok, Mengerti'
                });
            } else {
                alert('Halaman ' + featureName + ' sedang dalam pengembangan sistem mobile app Portal Siswa MAM Limpung.');
            }
        }
    </script>
@endsection