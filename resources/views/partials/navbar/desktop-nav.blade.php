{{-- Desktop Navigation Menu --}}
<div class="hidden md:flex items-center space-x-1 h-full">

    {{-- Home --}}
    <a href="{{ route('frontend.home') }}"
       class="h-16 px-4 flex items-center text-[14px] font-semibold border-b-2 transition-all duration-300 {{ request()->routeIs('frontend.home') ? 'border-blue-900 text-blue-900' : 'border-transparent text-gray-600 hover:text-blue-900' }}">
        Beranda
    </a>

    {{-- Akademik Dropdown: Show active line only when a sub-route is active, but not on hover --}}
    <div class="relative h-16 flex items-center" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
        <button class="group h-full px-4 flex items-center text-[14px] font-semibold border-b-2 transition-all duration-300 gap-1.5 focus:outline-none {{ request()->routeIs('frontend.profile.*') ? 'border-blue-900 text-blue-900' : 'border-transparent text-gray-600 hover:text-blue-900' }}">
            Profil
            <svg class="w-3 h-3 transition-all duration-300 ease-out transform"
                :class="open ? 'rotate-180 translate-y-0.5 text-blue-900' : 'text-gray-400 group-hover:translate-y-0.5 group-hover:text-blue-900'"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        {{-- Dropdown Container: Wide 2-column grid, Sharp Box, Premium Accent top --}}
        <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="absolute top-full left-1/2 -translate-x-1/2 w-[480px] bg-white border border-gray-200 border-t-4 border-t-blue-900 shadow-2xl z-50 rounded-none py-3">

            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 px-4 py-1.5 block mb-1">Profil Sekolah</span>

            <div class="grid grid-cols-2 gap-x-2 gap-y-1 px-2">

                {{-- Selayang Pandang --}}
                <a href="{{ route('frontend.profile.selayang-pandang') }}"
                class="group flex items-start gap-3 px-2 py-3 hover:bg-slate-50 transition-colors rounded-none">
                    <div class="w-9 h-9 bg-blue-50 text-blue-900 flex items-center justify-center shrink-0 rounded-none group-hover:bg-blue-900 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-compass text-sm"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-900 block group-hover:text-blue-900 transition-colors">Selayang Pandang</span>
                        <span class="text-[11px] text-gray-400 font-medium block mt-0.5 leading-normal">Sekilas sejarah madrasah.</span>
                    </div>
                </a>

                {{-- Visi dan Misi --}}
                <a href="{{ route('frontend.profile.visi-misi') }}"
                class="group flex items-start gap-3 px-2 py-3 hover:bg-slate-50 transition-colors rounded-none">
                    <div class="w-9 h-9 bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 rounded-none group-hover:bg-amber-500 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-bullseye text-sm"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-900 block group-hover:text-blue-900 transition-colors">Visi dan Misi</span>
                        <span class="text-[11px] text-gray-400 font-medium block mt-0.5 leading-normal">Arah dan tujuan pendidikan.</span>
                    </div>
                </a>

                {{-- Periodisasi Kepala --}}
                <a href="{{ route('frontend.profile.periodisasi-kepala') }}"
                class="group flex items-start gap-3 px-2 py-3 hover:bg-slate-50 transition-colors rounded-none">
                    <div class="w-9 h-9 bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 rounded-none group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-900 block group-hover:text-blue-900 transition-colors">Periodisasi Kepala</span>
                        <span class="text-[11px] text-gray-400 font-medium block mt-0.5 leading-normal">Rekam jejak kepemimpinan.</span>
                    </div>
                </a>

                {{-- Struktur Organisasi --}}
                <a href="{{ route('frontend.profile.struktur-organisasi') }}"
                class="group flex items-start gap-3 px-2 py-3 hover:bg-slate-50 transition-colors rounded-none">
                    <div class="w-9 h-9 bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 rounded-none group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-sitemap text-sm"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-900 block group-hover:text-blue-900 transition-colors">Struktur Organisasi</span>
                        <span class="text-[11px] text-gray-400 font-medium block mt-0.5 leading-normal">Susunan pengurus madrasah.</span>
                    </div>
                </a>

                {{-- Program Madrasah --}}
                <a href="{{ route('frontend.profile.program-madrasah') }}"
                class="group flex items-start gap-3 px-2 py-3 hover:bg-slate-50 transition-colors rounded-none">
                    <div class="w-9 h-9 bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 rounded-none group-hover:bg-slate-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-900 block group-hover:text-blue-900 transition-colors">Program Madrasah</span>
                        <span class="text-[11px] text-gray-400 font-medium block mt-0.5 leading-normal">Program unggulan madrasah.</span>
                    </div>
                </a>

                {{-- Mualim Master Class (MMC) - ditonjolkan sebagai program unggulan --}}
                <a href="{{ route('frontend.profile.mmc') }}"
                class="group flex items-start gap-3 px-2 py-3 hover:bg-amber-50/60 transition-colors rounded-none">
                    <div class="w-9 h-9 bg-amber-400 text-blue-950 flex items-center justify-center shrink-0 rounded-none group-hover:bg-blue-900 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-crown text-sm"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-900 block group-hover:text-blue-900 transition-colors">Mualim Master Class</span>
                        <span class="text-[11px] text-gray-400 font-medium block mt-0.5 leading-normal">Kelas unggulan (MMC).</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Berita --}}
    <a href="{{ route('frontend.article.index') }}"
       class="h-16 px-4 flex items-center text-[14px] font-semibold border-b-2 transition-all duration-300 {{ request()->routeIs('frontend.article.index') || request()->routeIs('frontend.article.show') ? 'border-blue-900 text-blue-900' : 'border-transparent text-gray-600 hover:text-blue-900' }}">
        Berita
    </a>

    {{-- Prestasi --}}
    <a href="{{ route('frontend.prestasi') }}"
       class="h-16 px-4 flex items-center text-[14px] font-semibold border-b-2 transition-all duration-300 {{ request()->routeIs('frontend.prestasi') ? 'border-blue-900 text-blue-900' : 'border-transparent text-gray-600 hover:text-blue-900' }}">
        Prestasi
    </a>

    {{-- Galeri --}}
    <a href="{{ route('frontend.galeri') }}"
       class="h-16 px-4 flex items-center text-[14px] font-semibold border-b-2 transition-all duration-300 {{ request()->routeIs('frontend.galeri') ? 'border-blue-900 text-blue-900' : 'border-transparent text-gray-600 hover:text-blue-900' }}">
        Galeri
    </a>

    {{-- PPDB & Auth Actions --}}
    <div class="flex items-center space-x-2 ml-4">

        <a href="{{ route('frontend.ppdb.index') }}"
           class="px-5 py-2.5 bg-blue-900 text-white text-[12px] font-semibold hover:bg-amber-500 transition-colors uppercase tracking-wider rounded-none border-b-2 border-blue-950 hover:border-amber-600">
            Daftar PPDB
        </a>
        
        @auth
            <a href="{{ route('dashboard') }}"
               class="px-5 py-2.5 bg-slate-800 text-white text-[12px] font-semibold hover:bg-slate-700 transition-colors uppercase tracking-wider rounded-none border-b-2 border-slate-900 hover:border-slate-800">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}"
               class="px-5 py-[9px] bg-white text-slate-700 text-[12px] font-semibold border border-slate-300 hover:bg-slate-50 hover:text-blue-900 transition-colors uppercase tracking-wider rounded-none">
                Login
            </a>
        @endauth
    </div>

</div>
