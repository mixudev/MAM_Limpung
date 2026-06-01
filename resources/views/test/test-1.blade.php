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

        /* Hero gradient mesh */
        .hero-mesh {
            background:
                radial-gradient(ellipse at 20% 10%, rgba(130,114,255,0.35) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 80%, rgba(245,158,11,0.18) 0%, transparent 50%),
                linear-gradient(135deg, #4f45b2 0%, #3d3491 40%, #1e1850 100%);
        }

        /* Glass card */
        .glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.15);
        }

        /* Shimmer skeleton */
        .skeleton {
            background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        /* Pill glow */
        .badge-glow {
            box-shadow: 0 0 12px rgba(245,158,11,0.45);
        }

        /* Nav active pill */
        .nav-active { background: rgba(255, 255, 255, 0.12); }
        .nav-active svg, .nav-active span { color: #4f45b2 !important; }

        /* Card hover lift */
        .card-lift {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .card-lift:active {
            transform: scale(0.97);
        }

        /* Section reveal animation */
        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.55s ease, transform 0.55s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Stat number counter */
        .stat-value {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            line-height: 1;
        }

        /* Quick access icon ring */
        .qa-icon {
            background: linear-gradient(135deg, #f0efff 0%, #e3e1ff 100%);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .qa-icon:active {
            transform: scale(0.92);
            box-shadow: 0 0 0 4px rgba(79,69,178,0.2);
        }

        /* Horizontal scroll snap */
        .snap-x-scroll {
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
        }
        .snap-card { scroll-snap-align: start; }

        /* Progress bar glow */
        .progress-glow {
            box-shadow: 0 0 8px rgba(79,69,178,0.5);
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            pointer-events: none;
        }
    </style>
</head>

<body class="bg-slate-50 font-jakarta">
<div class="flex flex-col min-h-screen max-w-md mx-auto relative bg-slate-50">

    {{-- ═══════════════════════════════════════════════════
         HERO BANNER — Futuristic gradient mesh
    ════════════════════════════════════════════════════ --}}
    <section class="hero-mesh relative overflow-hidden pt-safe">

        <!-- Floating decorative orbs -->
        <div class="orb w-48 h-48 bg-purple-500/30 top-[-30px] right-[-40px]" style="animation: float 5s ease-in-out infinite;"></div>
        <div class="orb w-32 h-32 bg-amber-400/20 bottom-[-20px] left-[10px]" style="animation: float 6s ease-in-out 1s infinite;"></div>

        <!-- Top bar -->
        <div class="relative z-10 flex items-center justify-between px-5 pt-5 pb-2">
            <div>
                <p class="text-white/60 text-xs font-medium tracking-widest uppercase">Portal Siswa</p>
                <h1 class="font-sora text-white font-bold text-lg leading-tight mt-0.5">MAM <span class="text-amber-400">Limpung</span></h1>
            </div>
            <div class="flex items-center gap-2">
                <!-- Notification Bell -->
                <button id="btn-notif" class="relative w-9 h-9 glass border-2 border-white/30 rounded-xl flex items-center justify-center transition-all duration-200 active:scale-90">
                    <svg class="text-white w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-amber-400 rounded-full badge-glow"></span>
                </button>
                <!-- Avatar -->
                <button id="btn-avatar" class="w-9 h-9 rounded-xl overflow-hidden glass border-2 border-white/30 flex items-center justify-center transition-all duration-200 active:scale-90">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="7" r="4"/>
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Greeting & welcome card -->
        <div class="relative z-10 px-5 pb-5 pt-3">
            <div class="glass rounded-2xl p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-white/70 text-xs mb-1">Selamat datang kembali 👋</p>
                        <h2 class="font-sora text-white font-semibold text-xl leading-tight">Lazuardi Mandegar</h2>
                        <p class="text-white/60 text-xs mt-1">TP 2025/2026 &middot; Semester Genap</p>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-[10px] bg-amber-400/20 text-amber-300 border border-amber-400/30 px-2 py-0.5 rounded-full font-semibold">Aktif</span>
                        <span class="text-[10px] text-white/50">Kelas XI</span>
                    </div>
                </div>

                <!-- Quick stats row -->
                <div class="grid grid-cols-3 gap-2 mt-3 pt-3 border-t border-white/10">
                    <div class="text-center">
                        <p class="stat-value text-white">94<span class="text-xs font-normal text-white/60">%</span></p>
                        <p class="text-[10px] text-white/50 mt-0.5">Kehadiran</p>
                    </div>
                    <div class="text-center border-x border-white/10">
                        <p class="stat-value text-amber-400">8</p>
                        <p class="text-[10px] text-white/50 mt-0.5">Tugas Aktif</p>
                    </div>
                    <div class="text-center">
                        <p class="stat-value text-emerald-400">3</p>
                        <p class="text-[10px] text-white/50 mt-0.5">Pengumuman</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════
         SCROLLABLE CONTENT AREA
    ════════════════════════════════════════════════════ --}}
    <main class="flex-1 overflow-y-auto pb-28 -mt-2 bg-slate-50 rounded-t-3xl">

        {{-- ── QUICK ACCESS MENU ─────────────────────────── --}}
        <section class="reveal px-5 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-sora font-semibold text-slate-800 text-sm">Akses Cepat</h3>
                <button class="text-xs text-primary-600 font-semibold">Lihat Semua</button>
            </div>
            <div class="grid grid-cols-4 gap-3">
                <!-- Jadwal -->
                <button id="qa-jadwal" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Jadwal</span>
                </button>
                <!-- Nilai -->
                <button id="qa-nilai" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Nilai</span>
                </button>
                <!-- PPDB -->
                <button id="qa-ppdb" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">PPDB</span>
                </button>
                <!-- Galeri -->
                <button id="qa-galeri" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Galeri</span>
                </button>
                <!-- Artikel -->
                <button id="qa-artikel" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Artikel</span>
                </button>
                <!-- Prestasi -->
                <button id="qa-prestasi" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="6"/>
                            <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Prestasi</span>
                </button>
                <!-- Ekskul -->
                <button id="qa-ekskul" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Ekskul</span>
                </button>
                <!-- Kontak -->
                <button id="qa-kontak" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Hubungi</span>
                </button>
            </div>
        </section>

        {{-- ── KEPALA SEKOLAH ────────────────────────────── --}}
        <section class="reveal px-5 mt-7 mb-4">
            <div class="bg-gradient-to-br from-primary-700 via-primary-800 to-primary-900 rounded-2xl p-5 relative overflow-hidden">
                <!-- Deco -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full translate-x-10 -translate-y-10"></div>
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-14 h-14 bg-white/15 rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="7" r="4"/>
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white/60 text-[10px] uppercase tracking-widest font-semibold">Kepala Sekolah</p>
                        <h4 class="font-sora font-bold text-white text-sm mt-0.5">Drs. H. Ahmad Fauzi, M.Pd.</h4>
                        <p class="text-amber-400 text-[11px] font-medium mt-0.5">"Berilmu, Berkarakter, dan Berprestasi"</p>
                    </div>
                </div>
                <p class="relative z-10 text-white/60 text-[11px] mt-3 leading-relaxed">
                    MAM Limpung berkomitmen mencetak generasi unggul yang beriman, berilmu, dan berakhlak mulia untuk kemajuan bangsa.
                </p>
            </div>
        </section>

    </main>

    {{-- ═══════════════════════════════════════════════════
         BOTTOM NAVIGATION BAR
    ════════════════════════════════════════════════════ --}}
    <nav id="bottom-nav" class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white/95 backdrop-blur-sm border-t border-slate-100 px-2 pt-2 z-50 shadow-[0_-4px_24px_rgba(0,0,0,0.06)]"
         style="padding-bottom: env(safe-area-inset-bottom, 0px);">
        <div class="flex justify-around items-center">

            <button id="nav-beranda" onclick="setActive(this)" data-tab="beranda"
                class="nav-item flex flex-col items-center justify-center gap-1 px-3 py-3 rounded-xl transition-all duration-200 nav-active min-w-[56px]">
                <svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                <span class="text-[10px] font-semibold text-primary-600 leading-none">Beranda</span>
            </button>

            <button id="nav-jelajah" onclick="setActive(this)" data-tab="jelajah"
                class="nav-item flex flex-col items-center justify-center gap-1 px-3 py-3 rounded-xl transition-all duration-200 min-w-[56px]">
                <svg class="w-6 h-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera" viewBox="0 0 16 16">
  <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z"/>
  <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
</svg> 
                <span class="text-[10px] font-semibold text-slate-400 leading-none">Galeri</span>
            </button>

            <button id="nav-notif" onclick="setActive(this)" data-tab="notif"
                class="nav-item flex flex-col items-center justify-center gap-1 px-3 py-3 rounded-xl transition-all duration-200 min-w-[56px] relative">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span class="text-[10px] font-semibold text-slate-400 leading-none">Notifikasi</span>
                <span class="absolute top-1.5 right-3.5 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
            </button>

            <button id="nav-pesan" onclick="setActive(this)" data-tab="pesan"
                class="nav-item flex flex-col items-center justify-center gap-1 px-3 py-3 rounded-xl transition-all duration-200 min-w-[56px]">
                <svg class="w-6 h-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
                    <path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                    <path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.4 5.4 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2z"/>
                </svg>
                <span class="text-[10px] font-semibold text-slate-400 leading-none">Tugas</span>
            </button>

            <button id="nav-profil" onclick="setActive(this)" data-tab="profil"
                class="nav-item flex flex-col items-center justify-center gap-1 px-3 py-3 rounded-xl transition-all duration-200 min-w-[56px]">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <circle cx="12" cy="7" r="4"/>
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                </svg>
                <span class="text-[10px] font-semibold text-slate-400 leading-none">Profil</span>
            </button>

        </div>
    </nav>

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

    /* ── Announcement scroll dots ── */
    const announceContainer = document.querySelector('.snap-x-scroll');
    if (announceContainer) {
        const dots = document.querySelectorAll('#dots-announce span');
        announceContainer.addEventListener('scroll', () => {
            const index = Math.round(announceContainer.scrollLeft / 268);
            dots.forEach((dot, i) => {
                dot.className = i === index
                    ? 'w-4 h-1.5 bg-primary-600 rounded-full transition-all duration-300'
                    : 'w-1.5 h-1.5 bg-slate-300 rounded-full transition-all duration-300';
            });
        }, { passive: true });
    }
</script>

</body>
</html>