<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="/manifest.json">

    <!-- SEO Meta Tags -->
    @php
        $defaultTitle = $siteSettings->meta_title ?? ($siteSettings->school_name ?? 'MA Muhammadiyah Limpung');
        $defaultDesc = $siteSettings->meta_description ?? ($siteSettings->about_short ?? 'MA Muhammadiyah Limpung adalah lembaga pendidikan Islam yang unggul, berkarakter, dan berprestasi.');
        $defaultKeywords = $siteSettings->meta_keywords ?? 'mam limpung, ma muhammadiyah limpung, pondok pesantren limpung, madrasah aliyah batang';
        $isIndexedGlobal = $siteSettings->is_indexed ?? true;
        
        $seoTitle = View::hasSection('seo_title') ? View::yieldContent('seo_title') : $defaultTitle;
        $seoDesc = View::hasSection('seo_description') ? View::yieldContent('seo_description') : $defaultDesc;
        $seoKeywords = View::hasSection('seo_keywords') ? View::yieldContent('seo_keywords') : $defaultKeywords;
        
        $robotsDirective = 'index, follow';
        if (!$isIndexedGlobal) {
            $robotsDirective = 'noindex, nofollow';
        } elseif (View::hasSection('seo_robots')) {
            $robotsDirective = View::yieldContent('seo_robots');
        }

        $canonicalUrl = View::hasSection('canonical_url') ? View::yieldContent('canonical_url') : request()->url();
        
        $ogType = View::hasSection('og_type') ? View::yieldContent('og_type') : 'website';
        $ogTitle = View::hasSection('og_title') ? View::yieldContent('og_title') : $seoTitle;
        $ogDesc = View::hasSection('og_description') ? View::yieldContent('og_description') : $seoDesc;
        
        $ogImageDefault = !empty($siteSettings->logo_path) ? asset('storage/' . $siteSettings->logo_path) : asset('images/default-share.jpg');
        $ogImage = View::hasSection('og_image') ? View::yieldContent('og_image') : $ogImageDefault;
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    @if(!empty($seoKeywords))
        <meta name="keywords" content="{{ $seoKeywords }}">
    @endif
    <meta name="robots" content="{{ $robotsDirective }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta name="author" content="{{ $siteSettings->school_name }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="{{ $siteSettings->school_name ?? 'MA Muhammadiyah Limpung' }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="id_ID">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- Google Search Console Verification -->
    @if(!empty($siteSettings->google_search_console_id))
        <meta name="google-site-verification" content="{{ $siteSettings->google_search_console_id }}">
    @endif

    <!-- Google Analytics (GA4) -->
    @if(!empty($siteSettings->google_analytics_id))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings->google_analytics_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $siteSettings->google_analytics_id }}');
        </script>
    @endif

    <!-- Google Tag Manager -->
    @if(!empty($siteSettings->google_tag_manager_id))
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ $siteSettings->google_tag_manager_id }}');</script>
    @endif

    <!-- Schema.org JSON-LD Markup -->
    @yield('schema_json_ld')

    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('assets/css/layouts-front.css') }}">

    <!-- Fonts / Styles / Scripts bundled via Vite (Alpine, AOS, Turbo) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome CDN -->
    <script src="https://kit.fontawesome.com/fd9d533b12.js" crossorigin="anonymous"></script>
</head>
    <body class="font-sans antialiased bg-white overflow-x-hidden"> 
        <!-- Google Tag Manager (noscript) -->
        @if(!empty($siteSettings->google_tag_manager_id))
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $siteSettings->google_tag_manager_id }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        @endif 
        <!-- Announcements, Popups, and Banners Overlay -->
        @include('partials.announcement_overlays')

        <!-- Preloader -->
        {{-- @include('partials.loader') --}}

        <!-- Opening Ceremony Sapaan -->
        {{-- @include('partials.sapaan') --}}

        <!-- Navbar -->
        @include('partials.navbar')

        @yield('content')

        <!-- Footer -->
        @include('partials.footer')

        <!-- Floating Mobile App Installer / Download Button -->
        {{-- <div x-data="{ showDownloadModal: false }" class="relative">
            <!-- Floating Action Button -->
            <button @click="showDownloadModal = true"
                    class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 bg-gradient-to-br from-[#4f45b2] to-indigo-800 text-white rounded-full shadow-[0_8px_30px_rgb(79,69,178,0.4)] hover:shadow-[0_8px_30px_rgb(79,69,178,0.6)] active:scale-95 hover:scale-105 transition-all duration-300 group cursor-pointer"
                    title="Unduh Aplikasi Mobile Portal Siswa">
                <i class="fa-solid fa-mobile-screen text-xl group-hover:animate-bounce"></i>
                <!-- Small notification dot -->
                <span class="absolute top-0.5 right-0.5 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
            </button>

            <!-- Elegant Download Modal -->
            <div x-show="showDownloadModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                 style="display: none;">
                 
                <div @click.away="showDownloadModal = false" 
                     class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 relative overflow-hidden animate-fade-in-up">
                    
                    <!-- Decorative background shapes -->
                    <div class="absolute -top-12 -right-12 w-28 h-28 bg-indigo-50 rounded-full opacity-60"></div>
                    
                    <!-- Close button -->
                    <button @click="showDownloadModal = false" 
                            class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center cursor-pointer">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>

                    <div class="text-center mt-3">
                        <div class="w-14 h-14 bg-indigo-50 border border-indigo-100 text-[#4f45b2] rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl shadow-xs">
                            <i class="fa-solid fa-mobile-screen-button"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg">Aplikasi Mobile Portal Siswa</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed font-medium">Nikmati kemudahan akses tugas, upload galeri kegiatan, dan tulis artikel langsung dari smartphone Anda!</p>
                    </div>

                    <!-- QR Code & Scanner Info -->
                    <div class="my-6 p-4 bg-slate-50 border border-slate-100 rounded-2xl flex flex-col items-center">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2.5">Pindai QR Code untuk Membuka</p>
                        <div class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-xs">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('apps.home')) }}" 
                                 alt="Scan to Download App" class="w-32 h-32 object-contain">
                        </div>
                        <p class="text-[9px] text-slate-500 mt-2.5 text-center font-bold leading-relaxed">Buka kamera HP Anda, arahkan ke QR Code di atas, atau klik tombol di bawah ini:</p>
                    </div>

                    <!-- Action buttons -->
                    <div class="space-y-2">
                        <a href="{{ route('apps.home') }}" 
                           class="block w-full py-3 bg-gradient-to-br from-[#4f45b2] to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg active:scale-98 transition-all text-center">
                            <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Buka Portal Mobile
                        </a>
                        
                        <div class="p-3 bg-indigo-50/50 border border-indigo-100/50 rounded-xl">
                            <p class="text-[9.5px] text-slate-600 leading-relaxed font-semibold">
                                <span class="text-amber-500 font-bold"><i class="fa-solid fa-star"></i> Tips PWA:</span> Setelah halaman mobile terbuka di smartphone Anda, pilih menu browser lalu klik <span class="font-bold">"Tambahkan ke Layar Utama" (Add to Home Screen)</span> untuk menginstalnya seperti aplikasi native!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- AOS initialized via app.js -->
        @if($siteSettings->is_chatbot_active ?? true)
            @include('partials.chatbot_widget')
        @endif
</body>
</html>