<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:site_name" content="{{ $siteSettings->school_name ?? 'MA Muhammadiyah Limpung' }}">

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

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

        <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            offset: 100,
            once: true,
            easing: 'ease-in-out-sine',
            anchorPlacement: 'top-bottom',
        });
    </script>
</body>
</html>