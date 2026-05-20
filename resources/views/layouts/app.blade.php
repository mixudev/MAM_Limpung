<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MAM Limpung</title>

    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('assets/css/brush.css') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- cdn tailwindcss --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}   

    <!-- Font Awesome CDN -->
<script src="https://kit.fontawesome.com/fd9d533b12.js" crossorigin="anonymous"></script>
</head>
    <body class="font-sans antialiased bg-white overflow-x-hidden"> 
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