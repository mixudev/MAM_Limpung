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