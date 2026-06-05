    <nav id="bottom-nav" style="padding-bottom: env(safe-area-inset-bottom, 0px);">
        <div class="flex justify-around items-center">

            <!-- Beranda -->
            <a href="{{ route('apps.home') }}" id="nav-beranda"
                class="nav-item flex flex-col items-center justify-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 min-w-[64px] {{ request()->routeIs('apps.home') ? 'bg-primary-50 text-primary-600' : 'text-slate-400 hover:text-slate-600' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('apps.home') ? 'text-primary-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[9px] font-bold leading-none mt-0.5">Beranda</span>
            </a>

            <!-- Galeri -->
            <a href="{{ route('apps.galeri') }}" id="nav-galeri"
                class="nav-item flex flex-col items-center justify-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 min-w-[64px] {{ request()->routeIs('apps.galeri*') ? 'bg-primary-50 text-primary-600' : 'text-slate-400 hover:text-slate-600' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('apps.galeri*') ? 'text-primary-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-[9px] font-bold leading-none mt-0.5">Galeri</span>
            </a>

            <!-- Tugas -->
            <a href="{{ route('apps.tugas') }}" id="nav-tugas"
                class="nav-item flex flex-col items-center justify-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 min-w-[64px] {{ request()->routeIs('apps.tugas*') ? 'bg-primary-50 text-primary-600' : 'text-slate-400 hover:text-slate-600' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('apps.tugas*') ? 'text-primary-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span class="text-[9px] font-bold leading-none mt-0.5">Tugas</span>
            </a>

            <!-- Artikel -->
            <a href="{{ route('apps.artikel') }}" id="nav-artikel"
                class="nav-item flex flex-col items-center justify-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 min-w-[64px] {{ request()->routeIs('apps.artikel*') ? 'bg-primary-50 text-primary-600' : 'text-slate-400 hover:text-slate-600' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('apps.artikel*') ? 'text-primary-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span class="text-[9px] font-bold leading-none mt-0.5">Artikel</span>
            </a>

            <!-- Profil -->
            <a href="{{ route('apps.profile') }}" id="nav-profil"
                class="nav-item flex flex-col items-center justify-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 min-w-[64px] {{ request()->routeIs('apps.profile*') ? 'bg-primary-50 text-primary-600' : 'text-slate-400 hover:text-slate-600' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('apps.profile*') ? 'text-primary-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[9px] font-bold leading-none mt-0.5">Profil</span>
            </a>

        </div>
    </nav>