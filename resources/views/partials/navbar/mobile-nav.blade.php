{{-- Mobile Sidebar Navigation --}}

{{-- Header Sidebar: Sharp, Flat, Soft academic look --}}
<div class="flex items-center justify-between p-5 border-b-2 border-gray-100 bg-white">
    <div class="flex items-center space-x-3">
        <img src="{{ asset('assets/img/logo.png') }}" class="w-10 h-10 object-contain" alt="Logo MAM Limpung">
        <div>
            <h2 class="font-bold text-gray-900 text-sm uppercase tracking-wide leading-none">MAM Limpung</h2>
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mt-1">Unggul & Berprestasi</p>
        </div>
    </div>
    <button id="closeSidebarBtn" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all rounded-none border border-gray-100">
        <i class="fa-solid fa-xmark text-lg"></i>
    </button>
</div>

{{-- Navigation Links --}}
<div class="flex-1 overflow-y-auto p-0" id="sidebarContent">

    <nav class="space-y-px bg-white">
        
        {{-- Beranda --}}
        <a href="{{ route('frontend.home') }}"
           class="flex items-center gap-4 px-6 py-4 text-[14px] font-semibold text-gray-700 hover:bg-slate-50 hover:text-blue-900 transition-all border-l-4 {{ request()->routeIs('frontend.home') ? 'bg-slate-50 border-blue-900 text-blue-900' : 'border-transparent' }}">
            <i class="fa-solid fa-house text-sm w-5 text-center text-gray-400 group-hover:text-blue-900"></i>
            Beranda
        </a>

        {{-- Profil --}}
        <a href="{{ route('frontend.profile') }}"
           class="flex items-center gap-4 px-6 py-4 text-[14px] font-semibold text-gray-700 hover:bg-slate-50 hover:text-blue-900 transition-all border-l-4 {{ request()->routeIs('frontend.profile') ? 'bg-slate-50 border-blue-900 text-blue-900' : 'border-transparent' }}">
            <i class="fa-solid fa-school text-sm w-5 text-center text-gray-400"></i>
            Profil Sekolah
        </a>

        {{-- Akademik Dropdown: Detail & Soft-design --}}
        <div x-data="{ openAkademik: false }" class="bg-white">
            <button @click="openAkademik = !openAkademik"
                    class="group w-full flex items-center justify-between px-6 py-4 text-[14px] font-semibold text-gray-700 hover:bg-slate-50 hover:text-blue-900 transition-all border-l-4 border-transparent">
                <span class="flex items-center gap-4">
                    <i class="fa-solid fa-book-open text-sm w-5 text-center text-gray-400"></i>
                    Akademik
                </span>
                <svg class="w-3 h-3 transition-all duration-300 ease-out transform" 
                     :class="openAkademik ? 'rotate-180 text-blue-900' : 'text-gray-400 group-hover:translate-y-0.5 group-hover:text-blue-900'" 
                     fill="none" 
                     viewBox="0 0 24 24" 
                     stroke="currentColor" 
                     stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            {{-- Dropdown Items: Boxy, Detailed --}}
            <div x-show="openAkademik" x-transition class="bg-slate-50 border-t border-b border-gray-100 py-1 pl-4 pr-6 space-y-1" style="display:none">
                
                {{-- Jurusan --}}
                <a href="{{ route('frontend.jurusan') }}"
                   class="flex items-start gap-3 p-3 transition-colors rounded-none group">
                    <div class="w-8 h-8 bg-blue-100 text-blue-900 flex items-center justify-center shrink-0 rounded-none group-hover:bg-blue-900 group-hover:text-white transition-all">
                        <i class="fa-solid fa-graduation-cap text-xs"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-800 block">Jurusan Spesialisasi</span>
                        <span class="text-[10px] text-gray-400 block mt-0.5 leading-tight">Pilihan jurusan MIPA, IPS, & Keagamaan.</span>
                    </div>
                </a>

                {{-- Kurikulum --}}
                <a href="{{ route('frontend.kurikulum') }}"
                   class="flex items-start gap-3 p-3 transition-colors rounded-none group">
                    <div class="w-8 h-8 bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 rounded-none group-hover:bg-amber-500 group-hover:text-white transition-all">
                        <i class="fa-solid fa-book text-xs"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-800 block">Kurikulum Belajar</span>
                        <span class="text-[10px] text-gray-400 block mt-0.5 leading-tight">Pendidikan modern berbasis akhlak.</span>
                    </div>
                </a>

                {{-- Ekstrakurikuler --}}
                <a href="{{ route('frontend.ekstrakurikuler') }}"
                   class="flex items-start gap-3 p-3 transition-colors rounded-none group">
                    <div class="w-8 h-8 bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 rounded-none group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <i class="fa-solid fa-volleyball text-xs"></i>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-800 block">Ekstrakurikuler</span>
                        <span class="text-[10px] text-gray-400 block mt-0.5 leading-tight">Penyaluran minat, bakat, olahraga & seni.</span>
                    </div>
                </a>

            </div>
        </div>

        {{-- Berita --}}
        <a href="{{ route('frontend.article.index') }}"
           class="flex items-center gap-4 px-6 py-4 text-[14px] font-semibold text-gray-700 hover:bg-slate-50 hover:text-blue-900 transition-all border-l-4 {{ request()->routeIs('frontend.article.index') || request()->routeIs('frontend.article.show') ? 'bg-slate-50 border-blue-900 text-blue-900' : 'border-transparent' }}">
            <i class="fa-solid fa-newspaper text-sm w-5 text-center text-gray-400"></i>
            Berita
        </a>

        {{-- Prestasi --}}
        <a href="{{ route('frontend.prestasi') }}"
           class="flex items-center gap-4 px-6 py-4 text-[14px] font-semibold text-gray-700 hover:bg-slate-50 hover:text-blue-900 transition-all border-l-4 {{ request()->routeIs('frontend.prestasi') ? 'bg-slate-50 border-blue-900 text-blue-900' : 'border-transparent' }}">
            <i class="fa-solid fa-trophy text-sm w-5 text-center text-gray-400"></i>
            Prestasi
        </a>

        {{-- Galeri --}}
        <a href="{{ route('frontend.galeri') }}"
           class="flex items-center gap-4 px-6 py-4 text-[14px] font-semibold text-gray-700 hover:bg-slate-50 hover:text-blue-900 transition-all border-l-4 {{ request()->routeIs('frontend.galeri') ? 'bg-slate-50 border-blue-900 text-blue-900' : 'border-transparent' }}">
            <i class="fa-solid fa-images text-sm w-5 text-center text-gray-400"></i>
            Galeri
        </a>

        {{-- Kontak --}}
        <a href="{{ route('frontend.contact') }}"
           class="flex items-center gap-4 px-6 py-4 text-[14px] font-semibold text-gray-700 hover:bg-slate-50 hover:text-blue-900 transition-all border-l-4 {{ request()->routeIs('frontend.contact') ? 'bg-slate-50 border-blue-900 text-blue-900' : 'border-transparent' }}">
            <i class="fa-solid fa-envelope text-sm w-5 text-center text-gray-400"></i>
            Kontak
        </a>
    </nav>

    {{-- PPDB & Auth Actions: Soft Semibold, Boxy flat design --}}
    <div class="p-6 bg-white border-t border-gray-100 space-y-3">
        <a href="{{ route('frontend.ppdb.index') }}"
           class="block w-full text-center bg-blue-900 text-white py-3 text-[13px] font-semibold uppercase tracking-wider transition-colors hover:bg-amber-500 rounded-none border-b-2 border-blue-950 hover:border-amber-600">
            Daftar PPDB
        </a>
        <a href="{{ route('frontend.ppdb.status') }}"
           class="block w-full text-center bg-white text-slate-700 py-3 text-[13px] font-semibold uppercase tracking-wider transition-colors hover:bg-slate-50 hover:text-blue-900 rounded-none border border-slate-300">
            Cek Status Pendaftaran
        </a>
        
        @auth
            <a href="{{ route('dashboard') }}"
               class="block w-full text-center bg-slate-800 text-white py-3 text-[13px] font-semibold uppercase tracking-wider transition-colors hover:bg-slate-700 rounded-none border-b-2 border-slate-900 hover:border-slate-800">
                Dashboard Panel
            </a>
        @else
            <a href="{{ route('login') }}"
               class="block w-full text-center bg-white text-slate-700 py-3 text-[13px] font-semibold uppercase tracking-wider transition-colors hover:bg-slate-50 hover:text-blue-900 rounded-none border border-slate-300">
                Login Portal
            </a>
        @endauth
    </div>

</div>
