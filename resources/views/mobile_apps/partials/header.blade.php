    <section class="bg-linear-to-br from-indigo-50 via-primary-50/30 to-white relative overflow-hidden pt-safe border-b border-primary-100/50">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-40">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none">
                <defs>
                    <pattern id="grid" width="24" height="24" patternUnits="userSpaceOnUse">
                        <path d="M 24 0 L 0 0 0 24" fill="none" stroke="rgba(79, 69, 178, 0.04)" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        <!-- Top bar -->
        <div class="relative z-10 flex items-center justify-between px-5 pt-5 pb-2">
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                <div>
                    <h1 class="font-sora text-primary-900 font-bold text-base leading-tight">MAM <span class="text-amber-500">Limpung</span></h1>
                    <p class="text-[9px] text-slate-500 font-semibold tracking-wider uppercase leading-none mt-0.5">Portal Mobile Siswa</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <!-- Notification Bell -->
                <button id="btn-notif" class="relative w-9 h-9 bg-white border border-slate-100 rounded-xl flex items-center justify-center transition-all duration-200 active:scale-95 shadow-xs">
                    <svg class="text-slate-600 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-2.5 right-2.5 w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                </button>
                
                <!-- Logout Form Helper / Button -->
                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="button" onclick="confirmMobileLogout()" title="Keluar" class="w-9 h-9 bg-white border border-rose-100 rounded-xl flex items-center justify-center text-rose-500 transition-all duration-200 active:scale-95 shadow-xs cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
                <script>
                    function confirmMobileLogout() {
                        if (window.MobilePopup) {
                            window.MobilePopup.confirm({
                                title: 'Keluar Aplikasi?',
                                description: 'Apakah Anda yakin ingin keluar dari akun Anda?',
                                confirmText: 'Ya, Keluar',
                                cancelText: 'Batal',
                                onConfirm: () => {
                                    document.getElementById('logout-form').submit();
                                }
                            });
                        } else {
                            if (confirm('Apakah Anda yakin ingin keluar dari akun Anda?')) {
                                document.getElementById('logout-form').submit();
                            }
                        }
                    }
                </script>
            </div>
        </div>

        @if(request()->routeIs('apps.home'))
        <!-- Greeting & welcome card -->
        <div class="relative z-10 px-5 pb-5 pt-3">
            <div class="bg-white/80 backdrop-blur-md border border-white/60 shadow-sm rounded-2xl p-4">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatarUrl() }}" alt="Avatar" class="w-12 h-12 rounded-xl object-cover border border-primary-100 shrink-0">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-700 font-sora font-bold text-lg shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-slate-400 text-[10px] uppercase tracking-wider font-semibold">Selamat datang kembali 👋</p>
                            <h2 class="font-sora text-slate-800 font-bold text-base leading-tight mt-0.5">{{ Auth::user()->name }}</h2>
                            <p class="text-slate-500 text-[10px] mt-1 font-medium">Kelas XI &middot; Semester Genap &middot; TP 2025/2026</p>
                        </div>
                    </div>
                    <div>
                        <span class="text-[9px] bg-emerald-50 text-emerald-700 border border-emerald-100 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Aktif</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>