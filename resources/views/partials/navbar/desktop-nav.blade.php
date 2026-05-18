{{-- Desktop Navigation Menu --}}
<div class="hidden md:flex items-center space-x-1 h-full">

    {{-- Home --}}
    <a href="{{ route('frontend.home') }}"
       class="h-16 px-4 flex items-center text-[14px] font-semibold border-b-2 transition-all duration-300 {{ request()->routeIs('frontend.home') ? 'border-blue-900 text-blue-900' : 'border-transparent text-gray-600 hover:text-blue-900' }}">
        Beranda
    </a>

    {{-- Profil Sekolah --}}
    <a href="{{ route('frontend.profile') }}"
       class="h-16 px-4 flex items-center text-[14px] font-semibold border-b-2 transition-all duration-300 {{ request()->routeIs('frontend.profile') ? 'border-blue-900 text-blue-900' : 'border-transparent text-gray-600 hover:text-blue-900' }}">
        Profil
    </a>

    {{-- Akademik Dropdown: Show active line only when a sub-route is active, but not on hover --}}
    <div class="relative h-16 flex items-center" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
        <button class="group h-full px-4 flex items-center text-[14px] font-semibold border-b-2 transition-all duration-300 gap-1.5 focus:outline-none {{ request()->routeIs('frontend.jurusan') || request()->routeIs('frontend.kurikulum') || request()->routeIs('frontend.ekstrakurikuler') ? 'border-blue-900 text-blue-900' : 'border-transparent text-gray-600 hover:text-blue-900' }}">
            Akademik
            <svg class="w-3 h-3 transition-all duration-300 ease-out transform" 
                 :class="open ? 'rotate-180 translate-y-0.5 text-blue-900' : 'text-gray-400 group-hover:translate-y-0.5 group-hover:text-blue-900'" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor" 
                 stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        
        {{-- Dropdown Container: Wide, Sharp Box, Premium Accent top --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="absolute top-full left-0 w-80 bg-white border border-gray-200 border-t-4 border-t-blue-900 shadow-2xl z-50 rounded-none py-2"
             style="display:none">
            
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 px-4 py-1.5 block">Program & Pelajaran</span>
            
            {{-- Jurusan --}}
            <a href="{{ route('frontend.jurusan') }}"
               class="group flex items-start gap-4 px-4 py-3 hover:bg-slate-50 transition-colors border-b border-gray-50 rounded-none">
                <div class="w-9 h-9 bg-blue-50 text-blue-900 flex items-center justify-center flex-shrink-0 rounded-none group-hover:bg-blue-900 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-graduation-cap text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-900 block group-hover:text-blue-900 transition-colors">Jurusan Spesialisasi</span>
                    <span class="text-[11px] text-gray-400 font-medium block mt-0.5 leading-normal">Pilihan jurusan MIPA, IPS, dan Keagamaan.</span>
                </div>
            </a>

            {{-- Kurikulum --}}
            <a href="{{ route('frontend.kurikulum') }}"
               class="group flex items-start gap-4 px-4 py-3 hover:bg-slate-50 transition-colors border-b border-gray-50 rounded-none">
                <div class="w-9 h-9 bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 rounded-none group-hover:bg-amber-500 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-book text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-900 block group-hover:text-blue-900 transition-colors">Kurikulum Belajar</span>
                    <span class="text-[11px] text-gray-400 font-medium block mt-0.5 leading-normal">Pembelajaran modern berbasis akhlak mulia.</span>
                </div>
            </a>

            {{-- Ekstrakurikuler --}}
            <a href="{{ route('frontend.ekstrakurikuler') }}"
               class="group flex items-start gap-4 px-4 py-3 hover:bg-slate-50 transition-colors rounded-none">
                <div class="w-9 h-9 bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 rounded-none group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-volleyball text-sm"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-900 block group-hover:text-blue-900 transition-colors">Ekstrakurikuler</span>
                    <span class="text-[11px] text-gray-400 font-medium block mt-0.5 leading-normal">Wadah minat, bakat, olahraga, & seni.</span>
                </div>
            </a>

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
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
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
