@extends('layouts.app')

@section('schema_json_ld')
    @php
        $schemaData = [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            '@id' => config('app.url') . '#school',
            'name' => $siteSettings->school_name ?? 'MA Muhammadiyah Limpung',
            'url' => config('app.url'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => !empty($siteSettings->logo_path)
                    ? asset('storage/' . $siteSettings->logo_path)
                    : asset('images/logo.png'),
            ],
            'description' => $siteSettings->about_short ?? '',
            'telephone' => $siteSettings->phone ?? '',
            'email' => $siteSettings->email ?? '',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $siteSettings->address ?? '',
                'addressLocality' => 'Limpung',
                'addressRegion' => 'Jawa Tengah',
                'postalCode' => $siteSettings->postal_code ?? '51271',
                'addressCountry' => 'ID',
            ],
            'sameAs' => [
                $siteSettings->tiktok ?? '',
                $siteSettings->instagram ?? '',
            ],
        ];
    @endphp
    <script type="application/ld+json">
  {!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endsection
@section('content')
    <div class="overflow-x-hidden">
        <!-- Hero Section: Split Layout with Stacked Cards -->
        <!-- Hero Section: Sambutan Kepala Madrasah, Background Gedung Beranimasi -->
<section class="relative min-h-[92vh] md:min-h-[90vh] flex items-center overflow-hidden bg-blue-950">

    <!-- Background: Foto Gedung dengan animasi zoom perlahan -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/img/school-1.png') }}"
            class="w-full h-full object-cover motion-safe:animate-hero-zoom"
            alt="Gedung Madrasah Aliyah Muhammadiyah Limpung"
            fetchpriority="high" loading="eager">
        <!-- Overlay ganda: gradasi + vignette agar teks tetap kontras di segala kondisi foto -->
        <div class="absolute inset-0 bg-gradient-to-b from-blue-950/95 via-blue-950/80 to-blue-950/95"></div>
        <div class="absolute inset-0 bg-blue-950/20"></div>
    </div>

    <!-- Aksen bulatan blur mengambang (dekorasi ringan, tidak ganggu keterbacaan) -->
    <div class="absolute top-10 left-10 w-64 h-64 bg-blue-500/10 rounded-full blur-[100px] motion-safe:animate-float-slow -z-0"></div>
    <div class="absolute bottom-10 right-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-[100px] motion-safe:animate-float-slow-reverse -z-0"></div>

    <div class="container mx-auto px-6 relative z-10 py-14">
        <div class="max-w-4xl mx-auto">

            <!-- 2 Box: Sambutan (kiri) & Foto Kepala Madrasah (kanan) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-6 items-center mb-10">

                <!-- Box Kiri: Teks Sambutan -->
                <div class="text-center md:text-right" data-aos="fade-right">
                    <span class="inline-block text-blue-300 font-bold tracking-[0.35em] text-[10px] uppercase mb-4">
                        Selamat Datang di
                    </span>
                    <h1 class="font-black text-white leading-[1.15] tracking-tighter mb-4 uppercase"
                        style="font-size: clamp(1.75rem, 4vw, 2.5rem);">
                        Madrasah Aliyah Muhammadiyah <span class="text-blue-400">Limpung</span>
                    </h1>
                    <p class="text-blue-100/80 leading-relaxed font-medium" style="font-size: clamp(0.85rem, 1.5vw, 1rem);">
                        {{ $siteSettings->tagline ?? 'Unggul dalam Ilmu, Kreatif, dan Anggun dalam Perilaku' }}
                    </p>
                </div>

                <!-- Box Kanan: Foto Kepala Madrasah -->
                <div class="flex justify-center md:justify-start" data-aos="fade-left">
                    <div class="relative w-[220px] md:w-[260px] group">
                        <!-- Pulse ring halus -->
                        <div class="absolute -inset-3 rounded-3xl bg-blue-400/10 motion-safe:animate-ping-slow"></div>

                        <!-- Foto portrait -->
                        <div class="relative aspect-[4/5] rounded-3xl overflow-hidden border-4 border-white/90 shadow-2xl">
                            <img src="{{ $kepalaSekolah?->foto ? asset('storage/' . $kepalaSekolah->foto) : asset('assets/img/kepala-sekolah.png') }}"
                                class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105"
                                alt="{{ $kepalaSekolah?->nama ?? 'Kepala Madrasah' }} - Kepala Madrasah Aliyah Muhammadiyah Limpung"
                                loading="eager">

                            <!-- Gradasi agar teks di bawah tetap terbaca -->
                            <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-black/85 via-black/40 to-transparent"></div>

                            <!-- Nama & Jabatan (overlay di dalam foto) -->
                            <div class="absolute inset-x-0 bottom-0 p-4 text-left">
                                <h4 class="text-sm md:text-base font-black text-white uppercase tracking-wide leading-tight mb-0.5">
                                    {{ $kepalaSekolah?->nama ?? 'Nama Kepala Madrasah' }}
                                </h4>
                                <p class="text-[9px] text-blue-200/90 uppercase tracking-widest font-bold">
                                    Kepala Madrasah
                                </p>
                                {{-- @if($kepalaSekolah?->quote)
                                    <p class="text-[8px] text-blue-300/70 italic mt-1 leading-tight max-w-[200px] truncate">
                                        "{{ $kepalaSekolah->quote }}"
                                    </p>
                                @endif --}}
                            </div>
                        </div>

                        <!-- Badge Akreditasi mengambang -->
                        {{-- <div class="absolute -bottom-3 -right-3 bg-amber-400 text-blue-950 text-[10px] font-black w-11 h-11 rounded-full flex items-center justify-center shadow-lg border-2 border-white"
                            data-aos="zoom-in" data-aos-delay="400" title="Terakreditasi A">
                            B
                        </div> --}}
                    </div>
                </div>
            </div>

            <!-- 2 Tombol (center, touch-friendly) -->
            <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 mb-9" data-aos="fade-up" data-aos-delay="150">
                <a href="{{ route('frontend.ppdb.form') }}"
                    class="min-h-[48px] bg-white text-blue-900 px-10 py-3.5 font-bold hover:bg-blue-50 hover:-translate-y-0.5 hover:shadow-xl active:translate-y-0 transition-all duration-300 uppercase tracking-widest text-xs inline-flex items-center justify-center gap-2 group focus-visible:outline focus-visible:outline-2 focus-visible:outline-white focus-visible:outline-offset-2">
                    Daftar Sekarang
                    <i class="fa-solid fa-arrow-right transform group-hover:translate-x-1 transition-transform" aria-hidden="true"></i>
                </a>
                <a href="/profile"
                    class="min-h-[48px] border-2 border-white/40 text-white px-10 py-3.5 font-bold hover:bg-white hover:text-blue-900 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 uppercase tracking-widest text-xs inline-flex items-center justify-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-white focus-visible:outline-offset-2">
                    Sejarah Sekolah
                </a>
            </div>

            <!-- Alamat & Admin: chip style, lebih mudah di-scan -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 border-t border-white/10 pt-6"
                data-aos="fade-up" data-aos-delay="250">
                <div class="flex items-center gap-2 bg-white/5 hover:bg-white/10 transition-colors px-4 py-2 rounded-full">
                    <i class="fa-solid fa-location-dot text-blue-300 text-xs" aria-hidden="true"></i>
                    <p class="text-xs text-blue-100/80 font-semibold">
                        {{ $siteSettings->address ?? 'Jl. Raya Limpung, Kab. Batang, Jawa Tengah' }}
                    </p>
                </div>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings->whatsapp ?? $siteSettings->phone ?? '') }}"
                    target="_blank" rel="noopener"
                    aria-label="Hubungi admin PPDB via WhatsApp"
                    class="flex items-center gap-2 bg-white/5 hover:bg-emerald-500/20 transition-colors px-4 py-2 rounded-full min-h-[36px]">
                    <i class="fa-brands fa-whatsapp text-emerald-400 text-xs" aria-hidden="true"></i>
                    <span class="text-xs text-blue-100/80 font-semibold">
                        {{ $siteSettings->whatsapp ?? $siteSettings->phone ?? '0812-xxxx-xxxx' }}
                    </span>
                </a>
            </div>
        </div>
    </div>


</section>

<style>
    /* Zoom perlahan background gedung */
    @keyframes heroZoom {
        0% { transform: scale(1); }
        100% { transform: scale(1.1); }
    }
    .animate-hero-zoom {
        animation: heroZoom 22s ease-in-out infinite alternate;
    }

    /* Bulatan dekorasi mengambang */
    @keyframes floatSlow {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(20px, -20px); }
    }
    @keyframes floatSlowReverse {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(-20px, 20px); }
    }
    .animate-float-slow { animation: floatSlow 12s ease-in-out infinite; }
    .animate-float-slow-reverse { animation: floatSlowReverse 14s ease-in-out infinite; }

    /* Pulse ring di belakang foto kepala madrasah */
    @keyframes pingSlow {
        0% { transform: scale(1); opacity: 0.6; }
        100% { transform: scale(1.35); opacity: 0; }
    }
    .animate-ping-slow { animation: pingSlow 2.5s cubic-bezier(0, 0, 0.2, 1) infinite; }

    /* Scroll indicator bounce */
    @keyframes bounceSlow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(6px); }
    }
    .animate-bounce-slow { animation: bounceSlow 1.8s ease-in-out infinite; }

    /* Hormati preferensi pengguna yang sensitif terhadap animasi */
    @media (prefers-reduced-motion: reduce) {
        .animate-hero-zoom,
        .animate-float-slow,
        .animate-float-slow-reverse,
        .animate-ping-slow,
        .animate-bounce-slow {
            animation: none !important;
        }
    }
</style>

        <x-announcement.ad-horizontal />

        <!-- Section Vidio Profile: Unique & Engaging -->
        <section class="py-24 bg-white relative overflow-hidden">
            <!-- Decorative Background -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
                <div class="absolute -top-24 -left-24 w-[500px] h-[500px] bg-blue-50/50 rounded-full blur-3xl opacity-50">
                </div>
                <div
                    class="absolute top-1/2 -right-24 w-[400px] h-[400px] bg-emerald-50/50 rounded-full blur-3xl opacity-50">
                </div>
            </div>

            <div class="container mx-auto px-6">
                <!-- Header Section -->
                <div class="text-center max-w-3xl mx-auto mb-20" data-aos="fade-up">
                    <span class="text-blue-700 font-bold text-[10px] tracking-[0.4em] uppercase mb-4 block">
                        Mari Berkenalan
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 leading-[1.1] uppercase tracking-tighter mb-6">
                        Mengenal MAM Limpung <br />Lebih Dekat.
                    </h2>
                    <p class="text-gray-500 text-lg font-medium leading-relaxed">
                        Sekilas vidio profile MAM Limpung, fasilitas unggulan, dan semangat kebersamaan yang membentuk
                        karakter emas generasi masa depan.
                    </p>
                </div>

                <!-- Video Box: Unique Layout -->
                <div class="relative max-w-5xl mx-auto" data-aos="zoom-in" data-aos-delay="100" x-data="{ videoOpen: false }">

                    <!-- Main Video Container -->
                    <div class="relative group cursor-pointer" @click="videoOpen = true">
                        <!-- Background Accent Layers (Creates the 'beda dari biasanya' stacked effect) -->
                        <div
                            class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-emerald-500 rounded-none opacity-20 blur-lg group-hover:opacity-40 transition duration-1000 group-hover:duration-200">
                        </div>
                        <div
                            class="absolute -inset-2 bg-blue-900 rounded-none transform rotate-[-2deg] group-hover:rotate-[-1deg] transition-transform duration-700">
                        </div>
                        <div
                            class="absolute -inset-2 bg-emerald-600 rounded-none transform rotate-[2deg] group-hover:rotate-[1deg] transition-transform duration-700">
                        </div>

                        <!-- Core Video Box -->
                        <div
                            class="relative h-[400px] md:h-[550px] w-full bg-gray-900 overflow-hidden shadow-2xl transition-all duration-700 group-hover:scale-[1.01] border-4 border-white">
                            <!-- Thumbnail -->
                            <img src="{{ asset('assets/img/school-2.png') }}"
                                class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:opacity-50 transition-opacity duration-700 group-hover:scale-105"
                                alt="Video Profile">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                            <!-- Play Button (Center) -->
                            <div class="absolute inset-0 flex items-center justify-center z-20">
                                <div
                                    class="relative w-24 h-24 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                    <div
                                        class="absolute inset-0 bg-white/30 rounded-full backdrop-blur-sm animate-ping opacity-75">
                                    </div>
                                    <div
                                        class="relative w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-2xl text-blue-900 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                        <i class="fa-solid fa-play text-2xl ml-2"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Video Info Overlay -->
                            <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full flex justify-between items-end z-20">
                                <div>
                                    <span
                                        class="bg-blue-600 text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest mb-4 inline-block">Official
                                        Video</span>
                                    <h3
                                        class="text-2xl md:text-4xl font-black text-white uppercase tracking-tighter leading-none mb-2">
                                        Profil Singkat <br />MAM Limpung</h3>
                                </div>
                                <div
                                    class="hidden md:flex items-center space-x-2 text-white/80 border border-white/20 px-4 py-2 backdrop-blur-md bg-black/20">
                                    <i class="fa-regular fa-clock"></i>
                                    <span class="font-bold text-sm tracking-widest">03:45</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Stats relative to video box -->
                    {{-- <div class="absolute -left-8 md:-left-16 top-1/4 bg-white p-6 shadow-2xl border-l-4 border-amber-400 z-10 hidden sm:block transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                     <h4 class="text-3xl font-black text-gray-900 mb-1">15+</h4> 
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Fasilitas <br/>Ekstrakurikuler</p>
                </div> --}}

                    {{-- <div class="absolute -right-8 md:-right-16 bottom-1/4 bg-white p-6 shadow-2xl border-l-4 border-blue-600 z-10 hidden sm:block transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    <h4 class="text-3xl font-black text-gray-900 mb-1">B</h4>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Akreditasi <br/>Institusi</p>
                </div> --}}

                    <!-- Modal Video (Alpine.js) -->
                    <template x-teleport="body">
                        <div x-show="videoOpen"
                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/95 backdrop-blur-md p-4"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            style="display: none;">

                            <div class="relative w-full max-w-5xl aspect-video bg-gray-900 overflow-hidden shadow-2xl border border-gray-800"
                                @click.away="videoOpen = false">
                                <!-- Close Button -->
                                <button @click="videoOpen = false"
                                    class="absolute top-4 right-4 z-30 w-12 h-12 bg-black/50 hover:bg-white rounded-full flex items-center justify-center text-white hover:text-black transition-colors border border-white/20">
                                    <i class="fa-solid fa-xmark text-xl"></i>
                                </button>

                                <template x-if="videoOpen">
                                    <iframe class="absolute inset-0 w-full h-full z-20 bg-black"
                                        src="https://www.youtube-nocookie.com/embed/tQ2foa6OK7U?autoplay=1"
                                        title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                                    </iframe>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        <!-- Bento Grid Section: Keunggulan Kami -->
        <section class="py-24 bg-gray-50">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 auto-rows-[300px]">

                    <!-- Card 1: Modern Tech (Large) -->
                    <div class="md:col-span-8 md:row-span-2 bg-white relative overflow-hidden group cursor-pointer border border-gray-100 shadow-sm"
                        data-aos="fade-right">
                        <img src="https://images.unsplash.com/photo-1531297484001-80022131f5a1?q=80&w=2020&auto=format&fit=crop"
                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000"
                            alt="Tech Hub">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-10 z-10">
                            <span
                                class="text-blue-400 font-bold text-[10px] tracking-[0.3em] uppercase mb-3 block">Fasilitas</span>
                            <h3 class="text-4xl font-black text-white uppercase tracking-tighter leading-none mb-4">LAB
                                KOMPUTER.</h3>
                            <p class="text-gray-300 text-sm max-w-sm font-medium">Fasilitas komputer untuk menunjang
                                kreativitas digitalmu.</p>
                        </div>
                    </div>

                    <!-- Card 2: Prestasi (Achievement) -->
                    <div class="md:col-span-4 bg-white relative overflow-hidden group cursor-pointer border border-gray-100 shadow-sm hover:border-blue-600 transition-all"
                        data-aos="fade-left">
                        <img src="https://images.unsplash.com/photo-1569512441031-45c9be5003e4?q=80&w=2070&auto=format&fit=crop"
                            class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:scale-110 transition-transform duration-1000"
                            alt="Prestasi">
                        <div class="absolute inset-0 bg-gradient-to-t from-white via-white/40 to-transparent"></div>
                        <div class="relative p-8 h-full flex flex-col justify-between z-10">
                            <div class="flex justify-between items-start">
                                <div class="w-12 h-12 bg-blue-600 text-white flex items-center justify-center shadow-lg">
                                    <i class="fa-solid fa-award text-2xl"></i>
                                </div>
                                <i
                                    class="fa-solid fa-arrow-up-right-from-square text-gray-400 text-sm opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                            <div>
                                <h4 class="text-gray-900 font-black text-2xl uppercase tracking-tighter mb-1 leading-none">
                                    PRESTASI<br />NASIONAL</h4>
                                <p class="text-blue-700 text-[10px] uppercase tracking-widest font-bold mt-2">Juara 1
                                    Robotik & Karya Ilmiah</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Creative Arts (Seni & Kreativitas) -->
                    <div class="md:col-span-4 bg-white relative overflow-hidden border border-gray-100 p-8 flex flex-col justify-between group cursor-pointer shadow-sm hover:border-blue-600 transition-all"
                        data-aos="fade-up">
                        <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?q=80&w=2070&auto=format&fit=crop"
                            class="absolute inset-0 w-full h-full object-cover opacity-10 group-hover:scale-110 transition-transform duration-1000"
                            alt="Seni">
                        <div class="relative z-10 h-full flex flex-col justify-between">
                            <div class="w-12 h-12 bg-amber-50 text-amber-600 flex items-center justify-center mb-6">
                                <i class="fa-solid fa-palette text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-gray-900 font-black text-xl uppercase tracking-tighter mb-2 leading-none">
                                    SENI &<br />KREATIVITAS</h4>
                                <p class="text-gray-500 text-xs leading-relaxed font-medium">Eksplorasi bakat senimu dalam
                                    lingkungan yang suportif.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Community (Komunitas) -->
                    <div class="md:col-span-4 md:row-span-2 bg-white relative overflow-hidden group border border-gray-100 shadow-sm"
                        data-aos="zoom-in">
                        <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=2032&auto=format&fit=crop"
                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                            alt="Students">
                        <div
                            class="absolute inset-0 bg-blue-900/10 group-hover:bg-blue-900/40 transition-colors duration-500">
                        </div>
                        <div class="absolute bottom-0 left-0 p-8 z-10 w-full">
                            <div class="bg-white p-6 shadow-2xl border-l-4 border-blue-900">
                                <h4 class="text-gray-900 font-black text-lg uppercase tracking-tighter mb-1 leading-none">
                                    KOMUNITAS<br />SISWA</h4>
                                <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest mt-1">Kebersamaan
                                    Adalah Kunci</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5: Sports (Olahraga) -->
                    <div class="md:col-span-4 bg-gray-900 relative overflow-hidden group cursor-pointer"
                        data-aos="fade-up" data-aos-delay="100">
                        <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=2093&auto=format&fit=crop"
                            class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:scale-110 transition-transform duration-1000"
                            alt="Olahraga">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900 via-transparent to-transparent"></div>
                        <div class="relative p-8 h-full flex flex-col justify-between z-10">
                            <i class="fa-solid fa-basketball text-3xl text-white/50"></i>
                            <div>
                                <h4 class="text-white font-black text-xl uppercase tracking-tighter mb-1 leading-none">
                                    FASILITAS<br />OLAHRAGA</h4>
                                <p class="text-blue-100 text-[10px] uppercase font-bold tracking-widest">
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6: Religion (Pondasi Akhlaq) -->
                    <div class="md:col-span-4 bg-emerald-600 relative overflow-hidden group cursor-pointer"
                        data-aos="fade-up" data-aos-delay="200">
                        <img src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=2070&auto=format&fit=crop"
                            class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:scale-110 transition-transform duration-1000"
                            alt="Religiusitas">
                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-900 via-transparent to-transparent">
                        </div>
                        <div class="relative p-8 h-full flex flex-col justify-between z-10">
                            <div class="flex items-center space-x-2 text-white">
                                <i class="fa-solid fa-star-and-crescent text-xl"></i>
                                <span class="font-black text-[10px] uppercase tracking-widest">Religiusitas</span>
                            </div>
                            <h4 class="text-white font-black text-2xl uppercase tracking-tighter leading-none">
                                PONDASI<br />AKHLAK.</h4>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Path Selection: Choose Your Specialization -->
        <section class="py-32 bg-white">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-end mb-20 border-b border-gray-100 pb-10">
                    <div class="max-w-2xl text-left" data-aos="fade-right">
                        <h2 class="text-5xl font-black text-gray-900 uppercase tracking-tighter leading-none mb-6">PILIH
                            JALUR <br />MASA DEPANMU.</h2>
                        <p class="text-gray-500 font-medium leading-relaxed">Spesialisasi kurikulum yang dirancang untuk
                            membantumu mencapai target universitas dan karir impian.</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-32 h-1 bg-blue-900"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
                    <!-- Program 1 -->
                    <div class="group border-b md:border-b-0 md:border-r border-gray-100 py-16 px-10 hover:bg-blue-50 transition-colors cursor-pointer"
                        data-aos="fade-up" data-aos-delay="0">
                        <div class="mb-10 overflow-hidden h-48 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?q=80&w=2070&auto=format&fit=crop"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                alt="">
                        </div>
                        <span class="text-blue-600 font-bold text-xs tracking-[0.3em] uppercase mb-6 block">Kategori
                            01</span>
                        <h3
                            class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-6 group-hover:text-blue-900 transition-colors italic">
                            M I P A</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-10 font-medium">Eksplorasi ilmu sains dan
                            teknologi dengan fasilitas laboratorium yang lengkap.</p>
                        <div
                            class="w-10 h-10 border border-gray-200 flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </div>
                    </div>

                    <!-- Program 2 -->
                    <div class="group border-b md:border-b-0 md:border-r border-gray-100 py-16 px-10 hover:bg-blue-50 transition-colors cursor-pointer"
                        data-aos="fade-up" data-aos-delay="100">
                        <div class="mb-10 overflow-hidden h-48 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1454165833767-02a6ed30c996?q=80&w=2070&auto=format&fit=crop"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                alt="">
                        </div>
                        <span class="text-blue-600 font-bold text-xs tracking-[0.3em] uppercase mb-6 block">Kategori
                            02</span>
                        <h3
                            class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-6 group-hover:text-blue-900 transition-colors italic">
                            I P S</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-10 font-medium">Kuasai ilmu sosial, ekonomi, dan
                            geopolitik untuk menjadi pemimpin masyarakat.</p>
                        <div
                            class="w-10 h-10 border border-gray-200 flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </div>
                    </div>

                    <!-- Program 3 -->
                    <div class="group py-16 px-10 hover:bg-blue-50 transition-colors cursor-pointer" data-aos="fade-up"
                        data-aos-delay="200">
                        <div class="mb-10 overflow-hidden h-48 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1590076175571-4b54592925b6?q=80&w=2070&auto=format&fit=crop"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                alt="">
                        </div>
                        <span class="text-blue-600 font-bold text-xs tracking-[0.3em] uppercase mb-6 block">Kategori
                            03</span>
                        <h3
                            class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-6 group-hover:text-blue-900 transition-colors italic">
                            AGAMA</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-10 font-medium">Perdalam ilmu syariat dan
                            karakter islami untuk bekal dunia dan akhirat.</p>
                        <div
                            class="w-10 h-10 border border-gray-200 flex items-center justify-center group-hover:bg-blue-900 group-hover:text-white group-hover:border-blue-900 transition-all">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Install Aplikasi (PWA) -->
        {{-- <section class="py-24 bg-linear-to-br from-indigo-50/50 via-white to-slate-50/50 border-t border-gray-100 relative overflow-hidden">
        <div class="absolute top-1/2 left-0 -translate-y-1/2 w-96 h-96 bg-blue-50 rounded-full blur-[100px] opacity-40 -z-10"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                <!-- Teks Deskripsi (Left - 7 cols) -->
                <div class="lg:col-span-7" data-aos="fade-right">
                    <span class="inline-block text-blue-700 font-bold tracking-[0.3em] text-[10px] uppercase mb-4 pl-3 border-l-4 border-blue-700">
                        Portal Mobile Siswa
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 leading-[1.1] uppercase tracking-tighter mb-6">
                        INSTALL APLIKASI <br/>
                        <span class="text-blue-700">PORTAL SISWA MAM</span>
                    </h2>
                    <p class="text-gray-500 text-base leading-relaxed mb-8 font-medium">
                        Pantau tugas sekolah, tulis artikel mading digital, bagikan momen kegiatan sekolah melalui galeri, dan akses info akademik penting secara real-time langsung dari smartphone kamu.
                    </p>
                    
                    <!-- Steps Checklist -->
                    <div class="space-y-4 mb-10">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">1</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm uppercase tracking-wide">Buka Portal Siswa</h4>
                                <p class="text-xs text-gray-500 mt-1">Akses halaman web portal dari browser HP/smartphone Anda.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">2</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm uppercase tracking-wide">Tambahkan ke Layar Utama</h4>
                                <p class="text-xs text-gray-500 mt-1">Ketuk ikon menu browser (titik tiga atau tombol share), lalu pilih <strong>"Tambahkan ke Layar Utama"</strong> atau <strong>"Install Aplikasi"</strong>.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">3</div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm uppercase tracking-wide">Siap Digunakan</h4>
                                <p class="text-xs text-gray-500 mt-1">Aplikasi PWA akan terpasang di beranda HP Anda dan berjalan lancar tanpa menggunakan memori berlebih.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 items-center">
                        <a href="{{ route('apps.home') }}" class="w-full sm:w-auto bg-blue-900 text-white px-10 py-4.5 font-bold hover:bg-black transition-all duration-300 uppercase tracking-widest text-[11px] inline-flex items-center justify-center group shadow-xl shadow-blue-900/10">
                            Buka Web App
                            <i class="fa-solid fa-mobile-screen-button ml-3 transform group-hover:scale-110 transition-transform"></i>
                        </a>
                        <button id="pwa-homepage-install-btn" class="hidden w-full sm:w-auto bg-amber-500 text-white px-10 py-4.5 font-bold hover:bg-amber-600 transition-all duration-300 uppercase tracking-widest text-[11px] inline-flex items-center justify-center group shadow-xl shadow-amber-500/10 cursor-pointer">
                            Install Aplikasi
                            <i class="fa-solid fa-download ml-3 transform group-hover:scale-110 transition-transform"></i>
                        </button>
                        <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Mendukung Android & iOS</span>
                    </div>

                    <script>
                        (function() {
                            let deferredPrompt;
                            const installBtn = document.getElementById('pwa-homepage-install-btn');

                            window.addEventListener('beforeinstallprompt', (e) => {
                                e.preventDefault();
                                deferredPrompt = e;
                                if (installBtn) {
                                    installBtn.classList.remove('hidden');
                                }
                            });

                            if (installBtn) {
                                installBtn.addEventListener('click', async () => {
                                    if (deferredPrompt) {
                                        deferredPrompt.prompt();
                                        const { outcome } = await deferredPrompt.userChoice;
                                        console.log('Homepage PWA install choice:', outcome);
                                        deferredPrompt = null;
                                        installBtn.classList.add('hidden');
                                    } else {
                                        alert('Untuk memasang aplikasi di HP:\n- Android (Chrome): Ketuk titik tiga di pojok kanan atas, lalu pilih "Instal aplikasi".\n- iOS (Safari): Ketuk ikon "Bagikan" (Share) di bawah, lalu pilih "Tambahkan ke Layar Utama" (Add to Home Screen).');
                                    }
                                });
                            }
                        })();
                    </script>
                </div>

                <!-- Phone Mockup & QR Code (Right - 5 cols) -->
                <div class="lg:col-span-5 flex justify-center" data-aos="fade-left">
                    <div class="relative">
                        <!-- Outer Shadow / Glow -->
                        <div class="absolute inset-0 bg-blue-900/10 rounded-[40px] blur-2xl"></div>

                        <!-- Phone Frame Container -->
                        <div class="relative w-[280px] h-[520px] bg-slate-900 rounded-[40px] p-3 shadow-2xl border-4 border-slate-800 flex flex-col overflow-hidden">
                            <!-- Speaker / Camera Notch -->
                            <div class="absolute top-4 left-1/2 -translate-x-1/2 w-24 h-4 bg-slate-900 rounded-full z-30"></div>
                            
                            <!-- Phone Screen -->
                            <div class="w-full h-full bg-slate-50 rounded-[30px] overflow-hidden flex flex-col p-4 relative z-20 border border-slate-950/10">
                                <!-- App Header Mock -->
                                <div class="flex items-center gap-2 mb-4 mt-2">
                                    <img src="{{ asset('assets/img/logo.png') }}" class="w-6 h-6 object-contain" alt="Logo">
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-[10px] leading-tight font-sora">MAM <span class="text-amber-500">Limpung</span></h4>
                                        <p class="text-[7px] text-gray-400 uppercase tracking-widest leading-none mt-0.5">Portal Siswa</p>
                                    </div>
                                </div>

                                <!-- App Body Mock -->
                                <div class="flex-1 flex flex-col justify-center items-center text-center p-3">
                                    <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-2xl mb-4 w-full flex flex-col items-center shadow-xs">
                                        <!-- QR Code Icon Placeholder -->
                                        <div class="w-20 h-20 bg-white border border-slate-100 p-1.5 shadow-sm rounded-xl mb-3 flex items-center justify-center relative group">
                                            <!-- Simple CSS generated simulated QR code -->
                                            <div class="w-full h-full bg-slate-800 opacity-90 rounded" style="background-image: radial-gradient(circle, #334155 20%, transparent 20%), radial-gradient(circle, transparent 20%, #334155 20%); background-size: 8px 8px; background-position: 0 0, 4px 4px;"></div>
                                            <div class="absolute inset-0 bg-white/95 flex items-center justify-center p-2 rounded-xl text-[8px] font-bold text-blue-900 uppercase text-center leading-tight">
                                                Scan to<br/>Install
                                            </div>
                                        </div>
                                        <p class="text-[9px] text-slate-500 font-semibold leading-relaxed">Pindai QR ini atau akses langsung <span class="text-blue-700 font-bold">mamlimpung.sch.id/apps</span> di HP kamu.</p>
                                    </div>

                                    <!-- Mock App Dashboard Screen -->
                                    <div class="w-full bg-white border border-slate-100 shadow-xs rounded-xl p-3 flex gap-2 items-center text-left">
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-150 flex items-center justify-center text-amber-600 shrink-0">
                                            <i class="fa-solid fa-list-check text-xs"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-center">
                                                <span class="text-[7px] bg-red-50 text-red-600 font-bold px-1.5 py-0.5 rounded">Tinggi</span>
                                                <span class="text-[6px] text-slate-400 font-bold">2 Hari</span>
                                            </div>
                                            <h5 class="font-bold text-gray-800 text-[8px] truncate mt-1">Tugas Matematika</h5>
                                        </div>
                                    </div>
                                </div>

                                <!-- App Nav Bar Mock -->
                                <div class="border-t border-slate-100 pt-2 flex justify-around items-center mt-auto">
                                    <div class="flex flex-col items-center text-blue-700"><i class="fa-solid fa-house text-[10px]"></i><span class="text-[6px] font-bold mt-0.5">Beranda</span></div>
                                    <div class="flex flex-col items-center text-slate-300"><i class="fa-solid fa-images text-[10px]"></i><span class="text-[6px] font-bold mt-0.5">Galeri</span></div>
                                    <div class="flex flex-col items-center text-slate-300"><i class="fa-solid fa-list text-[10px]"></i><span class="text-[6px] font-bold mt-0.5">Tugas</span></div>
                                    <div class="flex flex-col items-center text-slate-300"><i class="fa-solid fa-user text-[10px]"></i><span class="text-[6px] font-bold mt-0.5">Profil</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

        <!-- Final CTA Banner: Bright Style -->
        <section class="py-24 bg-white border-t border-gray-100 relative overflow-hidden">
            <div class="container mx-auto px-6 relative z-10 text-center" data-aos="zoom-in">
                <h2 class="text-4xl md:text-6xl font-black text-gray-900 uppercase tracking-tighter mb-8 leading-none">MASA
                    DEPANMU <br />DIMULAI HARI INI.</h2>
                <p class="text-gray-500 max-w-xl mx-auto mb-12 font-medium text-lg">Pendaftaran PPDB Online 2026/2027 telah
                    dibuka. Jadilah bagian dari keluarga besar MAM Limpung sekarang.</p>
                <a href="/ppdb"
                    class="inline-block bg-blue-900 text-white px-16 py-6 font-bold hover:bg-black transition-all duration-300 uppercase tracking-[0.3em] text-xs">
                    Daftar Sekarang
                </a>
            </div>
        </section>
    </div>
@endsection
