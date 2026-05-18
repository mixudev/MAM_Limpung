@extends('layouts.app')

@section('content')

<!-- Zen Reading Environment -->
<div class="bg-slate-50 min-h-screen pt-8 md:pt-12 pb-24 md:pb-32 font-sans selection:bg-blue-200 selection:text-slate-900">
    
    <article class="container mx-auto px-4 sm:px-6 max-w-3xl">
        
        <!-- Back Link -->
        <div class="mb-6 md:mb-8 text-center sm:text-left">
            <a href="{{ route('frontend.article.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-700 transition-colors text-[10px] sm:text-sm font-bold uppercase tracking-widest">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Indeks
            </a>
        </div>

        <!-- Article Header -->
        <header class="mb-8 md:mb-10 text-center sm:text-left">
            <div class="flex items-center justify-center sm:justify-start gap-3 mb-4 md:mb-6">
                <span class="text-[10px] font-bold uppercase tracking-widest text-blue-700 border border-blue-700 px-3 py-1">Prestasi</span>
                <span class="text-slate-500 text-xs sm:text-sm">15 Mei 2026</span>
            </div>
            
            <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-slate-900 leading-tight md:leading-[1.1] mb-6 md:mb-8">
                Tim Robotik MAM Limpung Raih Juara 1 Tingkat Nasional 2025
            </h1>
            
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 sm:gap-4 text-slate-600 text-xs sm:text-sm">
                <span class="font-bold uppercase tracking-widest text-[10px] sm:text-xs text-slate-900">Oleh Ustadz Budi Santoso</span>
                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                <span class="uppercase tracking-widest text-[10px] sm:text-xs">5 Menit Membaca</span>
            </div>
        </header>

        <!-- Sharp Featured Image -->
        <div class="w-full aspect-video sm:aspect-[21/9] bg-slate-200 mb-8 md:mb-12 relative">
            <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2070&auto=format&fit=crop" alt="Robotik" class="w-full h-full object-cover">
            <div class="absolute bottom-0 right-0 bg-slate-900 text-white text-[8px] sm:text-[10px] uppercase tracking-widest px-2 sm:px-3 py-1">
                Foto: Dokumentasi Sekolah
            </div>
        </div>

        <!-- Article Body (Optimized for Readability) -->
        <div class="prose prose-slate prose-base sm:prose-lg md:prose-xl mx-auto 
                    prose-p:text-slate-800 prose-p:leading-[1.8] prose-p:mb-6 sm:prose-p:mb-8
                    prose-headings:font-serif prose-headings:font-bold prose-headings:text-slate-900 prose-headings:leading-tight
                    prose-a:text-blue-700 prose-a:underline prose-a:decoration-blue-300 hover:prose-a:decoration-blue-700
                    prose-blockquote:border-l-4 prose-blockquote:border-amber-500 prose-blockquote:bg-white prose-blockquote:py-3 sm:prose-blockquote:py-4 prose-blockquote:px-5 sm:prose-blockquote:px-6 prose-blockquote:not-italic prose-blockquote:font-serif prose-blockquote:text-slate-900 prose-blockquote:shadow-sm">
            
            <p class="text-lg sm:text-xl md:text-2xl font-serif leading-relaxed mb-8 sm:mb-10 text-slate-900">
                <span class="float-left text-6xl sm:text-7xl font-serif font-bold text-slate-900 leading-none pr-3 sm:pr-4 pt-1 sm:pt-2">K</span>
                abar menggembirakan datang dari tim Ekstrakurikuler Robotik Madrasah Aliyah Muhammadiyah (MAM) Limpung. Pada ajang kompetisi merakit robot pintar tingkat nasional, tim kebanggaan sekolah sukses menyabet gelar Juara 1 mengalahkan ratusan peserta lain.
            </p>

            <p>
                Ajang yang diselenggarakan di Jakarta Expo Center pada akhir pekan lalu tersebut mempertandingkan kreativitas, logika pemrograman, dan kecepatan robot dalam menyelesaikan rintangan. Tim MAM Limpung yang beranggotakan tiga siswa kelas XI ini berhasil mencatatkan waktu tercepat berkat algoritma pencarian rute yang sangat efisien.
            </p>

            <h3>Proses Persiapan yang Panjang</h3>
            <p>
                Kemenangan ini tentu tidak didapatkan secara instan. Menurut Ustadz Budi selaku pembina ekstrakurikuler, tim telah mempersiapkan desain prototipe dan latihan koding sejak 6 bulan yang lalu. Mereka secara rutin menggunakan fasilitas Laboratorium Komputer sekolah setiap sore hari.
            </p>

            <blockquote>
                <p class="mb-2 text-xl sm:text-2xl leading-snug">"Anak-anak menunjukkan dedikasi yang luar biasa. Mereka rela mengorbankan waktu libur untuk melakukan uji coba dan memperbaiki bug. Ini bukti madrasah kita mampu mencetak talenta unggul di bidang teknologi."</p>
                <footer class="text-xs sm:text-sm font-sans font-bold uppercase tracking-widest text-slate-500">— Ustadz Budi Santoso</footer>
            </blockquote>

            <h3>Membangun Karakter Melalui Teknologi</h3>
            <p>
                Selain keahlian teknis, kompetisi ini juga sangat melatih karakter siswa dalam hal kerja sama tim, memecahkan masalah, dan kemampuan menahan tekanan saat bertanding. Ke depannya, sekolah berencana untuk menambah alat peraga robotik agar semakin banyak siswa yang bisa berpartisipasi.
            </p>

        </div>

        <!-- Minimalist Tags & Author Footer -->
        <div class="mt-12 md:mt-16 pt-6 md:pt-8 border-t border-slate-200 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex flex-wrap justify-center gap-2">
                <a href="#" class="px-3 py-1 border border-slate-200 text-slate-600 hover:border-blue-700 hover:text-blue-700 text-[10px] sm:text-xs font-bold uppercase tracking-widest transition-colors">Robotik</a>
                <a href="#" class="px-3 py-1 border border-slate-200 text-slate-600 hover:border-blue-700 hover:text-blue-700 text-[10px] sm:text-xs font-bold uppercase tracking-widest transition-colors">Prestasi</a>
            </div>
            
            <!-- Soft CTA -->
            <a href="#" class="group flex items-center justify-center w-full md:w-auto gap-4 bg-slate-900 text-white px-6 py-3 hover:bg-blue-700 transition-colors">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest">Daftar PPDB 2026</span>
                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

    </article>

</div>

@endsection