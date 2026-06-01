<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <meta name="theme-color" content="#4f45b2" />
    <meta name="description" content="Portal Akademik Digital MAM Limpung — Sekolah Modern, Berkarakter, dan Berprestasi." />
    <title>Beranda — MAM Limpung</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sora: ['Sora', 'sans-serif'],
                        jakarta: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#4f45b2',
                            50:  '#f0efff',
                            100: '#e3e1ff',
                            200: '#cbc7ff',
                            300: '#a89fff',
                            400: '#8272ff',
                            500: '#6254ff',
                            600: '#4f45b2',
                            700: '#3d3491',
                            800: '#2e2672',
                            900: '#1e1850',
                        },
                        amber: {
                            DEFAULT: '#f59e0b',
                        },
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease both',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4,0,0.6,1) infinite',
                        'float': 'float 4s ease-in-out infinite',
                        'shimmer': 'shimmer 2s linear infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-8px)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' },
                        },
                    },
                    backdropBlur: {
                        xs: '2px',
                    },
                },
            },
        };
    </script>

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

    </style>
</head>

<body class="bg-slate-50 font-jakarta">
<div class="flex flex-col min-h-screen max-w-md mx-auto relative bg-slate-50">

    @include('mobile_apps.partials.header')

    <main class="flex-1 overflow-y-auto pb-28 -mt-2 bg-slate-50 rounded-t-3xl">

        @yield('content')

    </main>

    @include('mobile_apps.partials.bottom_nav')

</div>{{-- end max-w-md --}}

<script>
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
</script>

</body>
</html>