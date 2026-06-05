<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <meta name="theme-color" content="#4f45b2" />
    <meta name="description" content="Portal Akademik Digital MAM Limpung — Sekolah Modern, Berkarakter, dan Berprestasi." />
    <title>Portal Siswa — MAM Limpung</title>
    <link rel="manifest" href="/manifest.json" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <link rel="apple-touch-icon" href="/assets/img/logo.png" />

    <!-- Compiled Assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Hotwire Turbo for SPA transitions without page refresh -->
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js" defer></script>

    <!-- AlpineJS globally loaded -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, .font-sora { font-family: 'Sora', sans-serif; }

        /* Scrollbar hidden */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Glass card */
        .glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.15);
        }

        /* Nav active pill */
        .nav-active { background: rgba(255, 255, 255, 0.12); }
        .nav-active svg, .nav-active span { color: #4f45b2 !important; }

        /* Critical CSS to prevent layout shift of mobile shell and bottom nav on load/transitions */
        .mobile-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            max-width: 448px; /* max-w-md is 448px */
            margin-left: auto;
            margin-right: auto;
            position: relative;
            background-color: #f8fafc; /* bg-slate-50 */
        }
        #bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 448px;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid #f1f5f9;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            z-index: 50;
            box-shadow: 0 -4px 24px rgba(0,0,0,0.04);
        }
    </style>
</head>

<body class="bg-slate-50 font-jakarta">
<div class="mobile-container">

    @include('mobile_apps.partials.header')

    <main class="flex-1 overflow-y-auto pb-28 -mt-2 bg-slate-50 rounded-t-3xl">

        @yield('content')

    </main>

    @include('mobile_apps.partials.bottom_nav')

    <x-mobile-allert />

</div>{{-- end mobile-container --}}

<!-- Global Loader Overlay -->
{{-- <div id="global-loader" class="fixed inset-0 z-[99999] flex flex-col items-center justify-center bg-slate-950/60 backdrop-blur-xs transition-all duration-300 opacity-0 pointer-events-none">
    <div class="bg-white rounded-3xl p-6 shadow-2xl flex flex-col items-center gap-4 max-w-xs text-center border border-slate-100 animate-fade-in-up">
        <!-- Premium Animated Spinner -->
        <div class="relative w-16 h-16 flex items-center justify-center">
            <!-- Outer spinning ring -->
            <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
            <div class="absolute inset-0 rounded-full border-4 border-t-primary-600 border-r-primary-600 animate-spin"></div>
            <!-- Inner logo icon or custom graphic -->
            <svg class="w-6 h-6 text-primary-500 animate-pulse-slow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <div>
            <h4 class="font-sora font-bold text-slate-800 text-sm" id="global-loader-title">Memuat...</h4>
            <p class="text-[10px] text-slate-400 font-semibold mt-1" id="global-loader-desc">Harap tunggu sebentar</p>
        </div>
    </div>
</div> --}}

<script>
    /* ── Global Loader Helper functions ── */
    window.showGlobalLoader = function(title = 'Memuat...', desc = 'Harap tunggu sebentar') {
        const loader = document.getElementById('global-loader');
        const titleEl = document.getElementById('global-loader-title');
        const descEl = document.getElementById('global-loader-desc');
        if (loader) {
            if (titleEl) titleEl.innerText = title;
            if (descEl) descEl.innerText = desc;
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100');
        }
    };

    window.hideGlobalLoader = function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0', 'pointer-events-none');
        }
    };

    // Turbo events for page transition animations
    document.addEventListener('turbo:visit', () => {
        window.showGlobalLoader('Memuat Halaman...', 'Menyiapkan konten untuk Anda');
    });
    document.addEventListener('turbo:load', () => {
        window.hideGlobalLoader();
    });

    /* ── Bottom Nav Active State ── */
    function setActive(el) {
        document.querySelectorAll('.nav-item').forEach(btn => {
            btn.classList.remove('nav-active');
            btn.querySelectorAll('svg, span').forEach(child => {
                child.classList.remove('text-primary-600');
                child.classList.add('text-slate-400');
            });
        });
        el.classList.add('nav-active');
        el.querySelectorAll('svg, span').forEach(child => {
            child.classList.remove('text-slate-400');
            child.classList.add('text-primary-600');
        });
    }

    /* ── Intersection Observer — section reveal ── */
    const revealEls = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach(el => io.observe(el));

    /* ── Service Worker Registration for PWA ── */
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('Service Worker registered', reg))
                .catch(err => console.error('Service Worker registration failed', err));
        });
    }
</script>

</body>
</html>