{{-- <!-- Beautiful GCP Implementation Tutorial Box (Full Width below settings) -->
<div class="bg-slate-900 text-white border border-slate-805 p-6 shadow-xl relative overflow-hidden group">
    <!-- Sleek abstract geometric background glow -->
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#4f45b2]/10 rounded-full blur-3xl group-hover:bg-[#4f45b2]/20 transition-all duration-500"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl"></div>

    <div class="relative">
        <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-zinc-800">
            <svg class="w-5 h-5 text-[#8c84c8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h4 class="text-sm font-mono font-bold uppercase tracking-widest text-[#8c84c8]">
                Panduan Konfigurasi Google Cloud & Google Sheets
            </h4>
        </div>

        <!-- Steps List: Going Downwards Vertically -->
        <div class="flex flex-col space-y-4 text-xs leading-relaxed text-zinc-300 font-sans mt-4">
            <!-- Step 1 -->
            <div class="p-4 bg-slate-955/40 border border-zinc-800/80 flex items-start gap-4">
                <span class="w-6 h-6 bg-zinc-800 text-zinc-300 font-mono text-xs font-bold rounded-none flex items-center justify-center flex-shrink-0">1</span>
                <div>
                    <strong class="text-white text-sm block mb-1">Buat Project Google Cloud Platform (GCP)</strong>
                    <p class="text-zinc-400 text-[11px] leading-relaxed">
                        Masuk ke <a href="https://console.cloud.google.com/" target="_blank" class="text-[#8c84c8] hover:underline font-bold">GCP Console</a> dan buat proyek baru untuk sekolah Anda.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="p-4 bg-slate-955/40 border border-zinc-800/80 flex items-start gap-4">
                <span class="w-6 h-6 bg-zinc-800 text-zinc-300 font-mono text-xs font-bold rounded-none flex items-center justify-center flex-shrink-0">2</span>
                <div>
                    <strong class="text-white text-sm block mb-1">Aktifkan API Google</strong>
                    <p class="text-zinc-400 text-[11px] leading-relaxed">
                        Cari di menu pencarian GCP untuk Google Sheets API dan Google Drive API, kemudian aktifkan keduanya secara berurutan.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="p-4 bg-slate-955/40 border border-zinc-800/80 flex items-start gap-4">
                <span class="w-6 h-6 bg-zinc-800 text-zinc-300 font-mono text-xs font-bold rounded-none flex items-center justify-center flex-shrink-0">3</span>
                <div>
                    <strong class="text-white text-sm block mb-1">Buat Service Account</strong>
                    <p class="text-zinc-400 text-[11px] leading-relaxed">
                        Buka APIs & Services > Credentials, pilih Create Credentials > Service Account. Tambahkan Key baru bertipe JSON, lalu file kredensial JSON akan otomatis diunduh ke komputer Anda.
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="p-4 bg-slate-955/40 border border-zinc-800/80 flex items-start gap-4">
                <span class="w-6 h-6 bg-zinc-800 text-zinc-300 font-mono text-xs font-bold rounded-none flex items-center justify-center flex-shrink-0">4</span>
                <div class="flex-1">
                    <strong class="text-white text-sm block mb-1">Undang Ke Spreadsheet</strong>
                    <p class="text-zinc-400 text-[11px] leading-relaxed mb-2">
                        Bagikan Google Sheet tujuan Anda ke email akun layanan ini sebagai Editor:
                    </p>
                    @if($hasCredentials && $clientEmail !== '-')
                    <div class="flex items-center gap-1.5 bg-zinc-850 p-1.5 border border-zinc-850 mt-1 max-w-md">
                        <span class="font-mono text-[9px] text-[#8c84c8] truncate block flex-1" id="copy-email-tutorial">{{ $clientEmail }}</span>
                        <button type="button" onclick="copyToClipboard('{{ $clientEmail }}')" class="text-zinc-500 hover:text-white" title="Salin Email">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </button>
                    </div>
                    @else
                    <span class="text-amber-500 italic block mt-0.5">Simpan kredensial terlebih dahulu untuk melihat email akun layanan</span>
                    @endif
                </div>
            </div>

            <!-- Step 5 -->
            <div class="p-4 bg-slate-955/40 border border-zinc-800/80 flex items-start gap-4">
                <span class="w-6 h-6 bg-zinc-800 text-zinc-300 font-mono text-xs font-bold rounded-none flex items-center justify-center flex-shrink-0">5</span>
                <div>
                    <strong class="text-white text-sm block mb-1">Tempel & Simpan</strong>
                    <p class="text-zinc-400 text-[11px] leading-relaxed">
                        Buka file JSON terunduh tadi, salin isinya secara penuh, lalu tempelkan ke kolom form Kredensial Service Account di atas dan klik Simpan Pengaturan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div> --}}
