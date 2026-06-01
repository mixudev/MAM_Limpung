    <section class="bg-gray-900 relative overflow-hidden pt-safe">


        <!-- Top bar -->
        <div class="relative z-10 flex items-center justify-between px-5 pt-5 pb-2">
            <div>
                {{-- <p class="text-white/60 text-xs font-medium tracking-widest uppercase">Portal Siswa</p> --}}
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
            <div class="glass rounded-sm p-4">
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
                {{-- <div class="grid grid-cols-3 gap-2 mt-3 pt-3 border-t border-white/10">
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
                </div> --}}
            </div>
        </div>
    </section>