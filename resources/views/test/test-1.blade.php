<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mobile App — Akademik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
</head>

<body class="bg-gray-500 font-sans">
    <div class="flex flex-col h-screen max-w-md mx-auto relative">

        <!-- Content Area -->
        <main class="flex-1 overflow-y-auto px-4 pt-6 pb-24">
            <div class="space-y-4">
                <!-- konten halaman di sini -->
            </div>
        </main>

        <!--
        =====================================================
        OPSI A — Pill Background Indicator (direkomendasikan)
        Aktif: latar biru muda + ikon & teks biru
        =====================================================
        -->
        <nav id="nav-a" class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white border-t border-gray-100 px-2 py-2 z-50 shadow-md">
            <div class="flex justify-around items-center">

                <button onclick="setActive(this)" data-nav="a"
                    class="nav-item-a flex flex-col items-center justify-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 bg-blue-50 min-w-[56px]">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                    </svg>
                    <span class="text-[10px] font-semibold text-blue-600 leading-none">Beranda</span>
                </button>

                <button onclick="setActive(this)" data-nav="a"
                    class="nav-item-a flex flex-col items-center justify-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 min-w-[56px]">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-400 leading-none">Jelajahi</span>
                </button>

                <button onclick="setActive(this)" data-nav="a"
                    class="nav-item-a flex flex-col items-center justify-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 min-w-[56px]">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-400 leading-none">Notifikasi</span>
                </button>

                <button onclick="setActive(this)" data-nav="a"
                    class="nav-item-a flex flex-col items-center justify-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 min-w-[56px]">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-400 leading-none">Pesan</span>
                </button>

                <button onclick="setActive(this)" data-nav="a"
                    class="nav-item-a flex flex-col items-center justify-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 min-w-[56px]">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="7" r="4" />
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-400 leading-none">Profil</span>
                </button>

            </div>
        </nav>

        <!--
        =====================================================
        OPSI B — Top Border Indicator (ganti komentar dengan nav ini)
        Aktif: garis biru di atas + ikon & teks biru
        Ganti nav di atas dengan blok ini jika diinginkan:
        =====================================================

        <nav id="nav-b" class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white z-50">
            <div class="flex justify-around items-stretch border-t border-gray-100">

                <button onclick="setActiveB(this)"
                    class="nav-item-b flex flex-col items-center justify-center gap-1 px-3 py-3 transition-all duration-200 flex-1 border-t-2 border-blue-500">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                    </svg>
                    <span class="text-[10px] font-semibold text-blue-600 leading-none">Beranda</span>
                </button>

                <button onclick="setActiveB(this)"
                    class="nav-item-b flex flex-col items-center justify-center gap-1 px-3 py-3 transition-all duration-200 flex-1 border-t-2 border-transparent">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-400 leading-none">Jelajahi</span>
                </button>

                <button onclick="setActiveB(this)"
                    class="nav-item-b flex flex-col items-center justify-center gap-1 px-3 py-3 transition-all duration-200 flex-1 border-t-2 border-transparent">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-400 leading-none">Notifikasi</span>
                </button>

                <button onclick="setActiveB(this)"
                    class="nav-item-b flex flex-col items-center justify-center gap-1 px-3 py-3 transition-all duration-200 flex-1 border-t-2 border-transparent">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-400 leading-none">Pesan</span>
                </button>

                <button onclick="setActiveB(this)"
                    class="nav-item-b flex flex-col items-center justify-center gap-1 px-3 py-3 transition-all duration-200 flex-1 border-t-2 border-transparent">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="7" r="4" />
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    </svg>
                    <span class="text-[10px] font-semibold text-gray-400 leading-none">Profil</span>
                </button>

            </div>
        </nav>
        -->

    </div>

    <script>
        /* ——— Opsi A: Pill Background ——— */
        function setActive(el) {
            document.querySelectorAll('.nav-item-a').forEach(btn => {
                btn.classList.remove('bg-blue-50');
                btn.querySelectorAll('svg, span').forEach(el => {
                    el.classList.remove('text-blue-600');
                    el.classList.add('text-gray-400');
                });
            });
            el.classList.add('bg-blue-50');
            el.querySelectorAll('svg, span').forEach(child => {
                child.classList.remove('text-gray-400');
                child.classList.add('text-blue-600');
            });
        }

        /* ——— Opsi B: Top Border ——— */
        function setActiveB(el) {
            document.querySelectorAll('.nav-item-b').forEach(btn => {
                btn.classList.remove('border-blue-500');
                btn.classList.add('border-transparent');
                btn.querySelectorAll('svg, span').forEach(child => {
                    child.classList.remove('text-blue-600');
                    child.classList.add('text-gray-400');
                });
            });
            el.classList.remove('border-transparent');
            el.classList.add('border-blue-500');
            el.querySelectorAll('svg, span').forEach(child => {
                child.classList.remove('text-gray-400');
                child.classList.add('text-blue-600');
            });
        }
    </script>
</body>

</html>