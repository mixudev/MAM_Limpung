<!-- 5. Split Drawer Slide-Over Detail -->
<div id="detailDrawer" class="fixed inset-0 z-50 overflow-hidden hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-xs transition-opacity duration-300 opacity-0" id="drawerBackdrop" onclick="closeDetails()"></div>
    <div class="absolute inset-y-0 right-0 max-w-full flex pl-10">
        <div id="drawerContent" class="w-screen max-w-lg bg-white dark:bg-zinc-900 border-l border-slate-200 dark:border-zinc-800 shadow-2xl p-6 flex flex-col justify-between translate-x-full transition-transform duration-300 rounded-none relative">
            
            <!-- X Close Button -->
            <button type="button" onclick="closeDetails()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-none transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Loading overlay -->
            <div id="drawerLoading" class="absolute inset-0 bg-white/90 dark:bg-zinc-900/90 z-10 flex flex-col items-center justify-center gap-3">
                <svg class="animate-spin h-8 w-8 text-[#4f45b2]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs font-mono text-slate-400 dark:text-zinc-500">Memuat berkas siswa...</span>
            </div>

            <!-- Drawer Scrollable Content -->
            <div class="flex-1 overflow-y-auto pr-1 space-y-6">
                <!-- Top Header Card -->
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-5 gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-16 h-16 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 overflow-hidden flex-shrink-0">
                            <img id="d_foto" src="" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <span id="d_status" class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded-none uppercase tracking-wider mb-1.5"></span>
                            <h2 id="d_nama" class="text-lg font-bold text-slate-900 dark:text-white truncate"></h2>
                            <p id="d_nomor_registrasi" class="text-xs font-mono font-bold text-[#4f45b2] dark:text-[#8c84c8]"></p>
                        </div>
                    </div>
                    
                    <!-- Cetak Button -->
                    <div class="flex-shrink-0 mr-6">
                        <button type="button" id="d_print_btn" class="inline-flex items-center gap-1.5 py-1.5 px-3 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-mono font-bold text-[10px] uppercase tracking-wider rounded-none transition-all active:scale-[.98]">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak
                        </button>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="space-y-5 text-sm">
                    <!-- Data Diri -->
                    <div>
                        <h4 class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2.5">Data Diri Calon Siswa</h4>
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-zinc-955 p-4 border border-slate-100 dark:border-zinc-800/80">
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">NISN</span>
                                <span id="d_nisn" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Jenis Kelamin</span>
                                <span id="d_gender" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Tempat, Tanggal Lahir</span>
                                <span id="d_ttl" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Alamat Lengkap</span>
                                <span id="d_alamat" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Kontak & Sekolah -->
                    <div>
                        <h4 class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2.5">Kontak & Akademik</h4>
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-zinc-955 p-4 border border-slate-100 dark:border-zinc-800/80">
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Sekolah Asal</span>
                                <span id="d_sekolah" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Ukuran Baju</span>
                                <span id="d_ukuran" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Nomor HP/WhatsApp</span>
                                <span id="d_hp" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Email Utama</span>
                                <span id="d_email" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Wali Orang Tua -->
                    <div>
                        <h4 class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2.5">Informasi Orang Tua</h4>
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-zinc-955 p-4 border border-slate-100 dark:border-zinc-800/80">
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Nama Ayah</span>
                                <span id="d_ayah" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 block uppercase font-mono">Nama Ibu</span>
                                <span id="d_ibu" class="font-semibold text-slate-800 dark:text-zinc-200"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan (Dynamic Fields) -->
                    <div id="d_additional_section" class="hidden">
                        <h4 class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2.5">Informasi Tambahan</h4>
                        <div id="d_additional_grid" class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-zinc-955 p-4 border border-slate-100 dark:border-zinc-800/80">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- Berkas Persyaratan (Dynamic Uploads) -->
                    <div id="d_requirements_section" class="hidden">
                        <h4 class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2.5">Berkas Persyaratan Mandiri</h4>
                        <div id="d_requirements_list" class="space-y-2 bg-slate-50 dark:bg-zinc-955 p-4 border border-slate-100 dark:border-zinc-800/80">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- Catatan Verifikasi -->
                    <div id="d_notes_section" class="hidden">
                        <h4 class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2.5">Catatan/Alasan Verifikator</h4>
                        <div class="bg-red-50/50 dark:bg-red-950/10 p-4 border border-red-100 dark:border-red-955/20 text-red-800 dark:text-red-400">
                            <span id="d_notes" class="font-medium text-xs"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Drawer Bottom Action Form -->
            <div id="drawerActions" class="border-t border-slate-100 dark:border-zinc-800 pt-4 mt-4 hidden">
                <div class="flex items-center gap-2">
                    <form id="drawerVerifyForm" action="" method="POST" class="flex-1 inline">
                        @csrf
                        <button type="submit" id="drawerVerifyBtn" class="w-full py-2.5 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-none transition-all active:scale-[.98] text-center">
                            Verifikasi Sekarang
                        </button>
                    </form>
                    <button type="button" id="drawerRejectBtn" class="flex-1 py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white font-bold text-xs rounded-none transition-all active:scale-[.98] text-center">
                        Tolak Pendaftaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
