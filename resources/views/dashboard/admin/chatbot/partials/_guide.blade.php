{{-- ═══════════════════════════════════════════
     PANDUAN PENGGUNAAN AI CHATBOT
     Ditampilkan sebagai tab di halaman — bukan popup
═══════════════════════════════════════════ --}}
<div class="p-6 space-y-8">

    {{-- ── Intro Banner ── --}}
    <div class="flex flex-col md:flex-row gap-5 p-5 bg-[#4f45b2] text-white">
        <div class="flex items-center justify-center w-16 h-16 bg-white/15 shrink-0 text-3xl">
            <i class="fa-solid fa-robot"></i>
        </div>
        <div>
            <h2 class="text-base font-bold">Panduan Lengkap AI Chatbot MAM Limpung</h2>
            <p class="text-sm text-white/80 mt-1 leading-relaxed">
                Panduan ini menjelaskan langkah demi langkah mulai dari mendapatkan API Key Google Gemini secara gratis,
                mendaftarkannya ke sistem, mengisi basis pengetahuan sekolah, membuat FAQ cepat, hingga chatbot siap digunakan oleh pengunjung website.
            </p>
            <div class="flex flex-wrap gap-2 mt-3">
                <a href="#guide-step1" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 hover:bg-white/30 text-xs font-bold font-mono uppercase tracking-wider transition-colors">
                    <i class="fa-solid fa-key text-amber-300"></i> 1. Ambil API Key
                </a>
                <a href="#guide-step2" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 hover:bg-white/30 text-xs font-bold font-mono uppercase tracking-wider transition-colors">
                    <i class="fa-solid fa-circle-plus"></i> 2. Daftarkan Key
                </a>
                <a href="#guide-step3" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 hover:bg-white/30 text-xs font-bold font-mono uppercase tracking-wider transition-colors">
                    <i class="fa-solid fa-book-open"></i> 3. Isi Pengetahuan
                </a>
                <a href="#guide-step4" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 hover:bg-white/30 text-xs font-bold font-mono uppercase tracking-wider transition-colors">
                    <i class="fa-solid fa-circle-question"></i> 4. Buat FAQ
                </a>
                <a href="#guide-step5" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 hover:bg-white/30 text-xs font-bold font-mono uppercase tracking-wider transition-colors">
                    <i class="fa-solid fa-robot"></i> 5. Uji Chatbot
                </a>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         LANGKAH 1 — DAPATKAN API KEY PROVIDER
    ════════════════════════════════════════ --}}
    <div id="guide-step1" class="scroll-mt-4">
        {{-- Step header --}}
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-200 dark:border-zinc-700">
            <span class="w-9 h-9 bg-[#4f45b2] text-white font-bold text-base flex items-center justify-center shrink-0">1</span>
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-key text-amber-500"></i> Mendapatkan API Key AI (Gemini, Groq, DeepSeek, OpenRouter)
                </h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Sistem chatbot mendukung beberapa provider AI terbaik secara fleksibel.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                {{-- Sub-steps --}}
                <div class="flex gap-4">
                    <div class="w-6 h-6 bg-[#4f45b2] text-white text-[11px] font-bold flex items-center justify-center shrink-0 mt-0.5">A</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200">Google Gemini API Key</p>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 leading-relaxed">
                            Kunjungi <a href="https://aistudio.google.com" target="_blank" class="text-[#4f45b2] underline font-semibold">aistudio.google.com</a>. Login dengan Akun Google, lalu klik <strong>Get API Key</strong> dan buat kunci baru di project baru.
                        </p>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <div class="w-6 h-6 bg-[#4f45b2] text-white text-[11px] font-bold flex items-center justify-center shrink-0 mt-0.5">B</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200">Groq AI API Key</p>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 leading-relaxed">
                            Buka <a href="https://console.groq.com" target="_blank" class="text-[#4f45b2] underline font-semibold">console.groq.com</a>. Daftar/login, masuk ke menu <strong>API Keys</strong>, lalu klik <strong>Create API Key</strong>.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="w-6 h-6 bg-[#4f45b2] text-white text-[11px] font-bold flex items-center justify-center shrink-0 mt-0.5">C</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200">DeepSeek API Key</p>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 leading-relaxed">
                            Buka <a href="https://platform.deepseek.com" target="_blank" class="text-[#4f45b2] underline font-semibold">platform.deepseek.com</a>. Login, buka menu <strong>API Keys</strong>, klik <strong>Create API Key</strong> (sangat murah dan performa cerdas).
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="w-6 h-6 bg-[#4f45b2] text-white text-[11px] font-bold flex items-center justify-center shrink-0 mt-0.5">D</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200">OpenRouter API Key</p>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 leading-relaxed">
                            Kunjungi <a href="https://openrouter.ai" target="_blank" class="text-[#4f45b2] underline font-semibold">openrouter.ai</a>. Memberikan akses ke ratusan model AI open-source (termasuk model gratis) dengan satu saldo.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                {{-- Model rekomendasi --}}
                <div class="border border-blue-200 dark:border-blue-800/50">
                    <div class="px-4 py-2.5 bg-blue-50 dark:bg-blue-950/20 border-b border-blue-200 dark:border-blue-800/50">
                        <p class="text-[10px] font-bold font-mono uppercase tracking-wider text-blue-600 dark:text-blue-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-star"></i> Rekomendasi Model per Provider
                        </p>
                    </div>
                    <div class="divide-y divide-blue-100 dark:divide-blue-900/30 text-xs">
                        <div class="px-4 py-2.5">
                            <span class="font-bold text-slate-700 dark:text-zinc-300">Google Gemini:</span>
                            <code class="font-mono bg-slate-100 dark:bg-zinc-800 px-1 py-0.5 text-[#4f45b2] text-[10px] font-bold ml-1">gemini-2.5-flash</code> atau <code class="font-mono bg-slate-100 dark:bg-zinc-800 px-1 py-0.5 text-[10px] ml-1">gemini-1.5-flash</code>
                        </div>
                        <div class="px-4 py-2.5">
                            <span class="font-bold text-slate-700 dark:text-zinc-300">Groq AI:</span>
                            <code class="font-mono bg-slate-100 dark:bg-zinc-800 px-1 py-0.5 text-[#4f45b2] text-[10px] font-bold ml-1">llama-3.3-70b-versatile</code>
                        </div>
                        <div class="px-4 py-2.5">
                            <span class="font-bold text-slate-700 dark:text-zinc-300">DeepSeek:</span>
                            <code class="font-mono bg-slate-100 dark:bg-zinc-800 px-1 py-0.5 text-[#4f45b2] text-[10px] font-bold ml-1">deepseek-chat</code>
                        </div>
                        <div class="px-4 py-2.5">
                            <span class="font-bold text-slate-700 dark:text-zinc-300">OpenRouter:</span>
                            <code class="font-mono bg-slate-100 dark:bg-zinc-800 px-1 py-0.5 text-[#4f45b2] text-[10px] font-bold ml-1">google/gemini-2.5-flash</code> atau model gratis
                        </div>
                    </div>
                </div>

                {{-- Batas gratis --}}
                <div class="flex gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/50 text-xs text-amber-700 dark:text-amber-400">
                    <i class="fa-solid fa-circle-info shrink-0 mt-0.5"></i>
                    <div>
                        <strong>Token Saving:</strong> Sistem telah dioptimalkan secara otomatis menggunakan penyaringan kata kunci (keyword matching) sehingga asisten AI hanya memuat informasi penting. Histori obrolan juga dipotong menjadi 6 pesan terakhir untuk menghemat token Anda hingga 80%.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-slate-200 dark:border-zinc-700">

    {{-- ════════════════════════════════════════
         LANGKAH 2 — DAFTARKAN API KEY KE SISTEM
    ════════════════════════════════════════ --}}
    <div id="guide-step2" class="scroll-mt-4">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-200 dark:border-zinc-700">
            <span class="w-9 h-9 bg-[#4f45b2] text-white font-bold text-base flex items-center justify-center shrink-0">2</span>
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-circle-plus text-indigo-500"></i> Pendaftaran & Rotasi API Key (Failover)
                </h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Daftarkan beberapa kunci API untuk memastikan chatbot tetap menyala meskipun satu provider sedang limit.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-4">
                @foreach([
                    ['1', 'Buka halaman "Kunci API"', 'Klik tombol <strong>TAMBAH API KEY</strong> di halaman kelola Kunci API.'],
                    ['2', 'Pilih Provider & Model', 'Pilih provider yang Anda inginkan (misal: Groq AI). Sistem akan otomatis mengisi placeholder dan nama model default seperti <code class="font-mono bg-slate-100 dark:bg-zinc-800 px-1">llama-3.3-70b-versatile</code>.'],
                    ['3', 'Tempel API Key & Simpan', 'Masukkan API Key yang Anda dapatkan dari konsol provider bersangkutan, lalu klik tombol Simpan.'],
                    ['4', 'Rotasi Kunci Otomatis', 'Disarankan mendaftarkan minimal 2 API Key. Jika API Key pertama terkena rate limit (Error 429) atau mengalami downtime, sistem secara otomatis melakukan <strong>rollback (failover)</strong> mencoba API Key berikutnya.'],
                ] as [$n, $title, $desc])
                <div class="flex gap-4">
                    <div class="w-6 h-6 bg-[#4f45b2] text-white text-[11px] font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $n }}</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200">{{ $title }}</p>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 leading-relaxed">{!! $desc !!}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="space-y-4">
                <div class="flex gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/50 text-xs text-emerald-700 dark:text-emerald-400">
                    <i class="fa-solid fa-shield-halved shrink-0 mt-0.5"></i>
                    <div><strong>Keamanan Enkripsi:</strong> Semua API Key dienkripsi di dalam database menggunakan algoritma enkripsi standar industri Laravel. Nilai asli tidak dapat dibaca dari dashboard admin oleh siapa pun.</div>
                </div>

                <div class="flex gap-3 px-4 py-3 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800/50 text-xs text-blue-700 dark:text-blue-400">
                    <i class="fa-solid fa-bug shrink-0 mt-0.5"></i>
                    <div><strong>Log Error Detail & Rantai Fallback:</strong> Buka tab <strong>Log Aktivitas</strong> untuk memantau detail error. Jika terjadi fallback, tombol <strong>Detail</strong> akan menampilkan runtutan kegagalan secara visual (misalnya: Percobaan #1 Gemini API kena limit 429, dialihkan ke Percobaan #2 Groq API yang sukses).</div>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-slate-200 dark:border-zinc-700">

    {{-- ════════════════════════════════════════
         LANGKAH 3 — ISI BASIS PENGETAHUAN
    ════════════════════════════════════════ --}}
    <div id="guide-step3" class="scroll-mt-4">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-200 dark:border-zinc-700">
            <span class="w-9 h-9 bg-[#4f45b2] text-white font-bold text-base flex items-center justify-center shrink-0">3</span>
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-book-open text-indigo-500"></i> Mengisi Basis Pengetahuan Sekolah
                </h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Basis pengetahuan adalah "otak" chatbot — sumber informasi yang dibaca AI sebelum menjawab pertanyaan.</p>
            </div>
        </div>

        <div class="flex gap-3 mb-5 px-4 py-3 bg-indigo-50 dark:bg-indigo-950/20 border border-indigo-200 dark:border-indigo-800/50 text-xs text-[#4f45b2] dark:text-indigo-400">
            <i class="fa-solid fa-lightbulb shrink-0 mt-0.5"></i>
            <div><strong>Cara kerjanya:</strong> Ketika pengguna bertanya, sistem mengambil pengetahuan yang relevan berdasarkan topik dan menyertakannya ke AI sebagai konteks. AI kemudian menjawab berdasarkan informasi tersebut. Semakin lengkap isinya, semakin akurat jawaban AI.</div>
        </div>

        {{-- Penjelasan Basis Data Pengetahuan --}}
        <div class="border border-slate-200 dark:border-zinc-700 p-5 mb-5 bg-slate-50 dark:bg-zinc-800/40">
            <h4 class="font-bold text-slate-800 dark:text-zinc-200 text-sm leading-snug mb-2 flex items-center gap-1.5"><i class="fa-solid fa-circle-info text-[#4f45b2]"></i> Informasi Sekolah yang Dapat Ditambahkan</h4>
            <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed mb-3">
                Anda dapat menambahkan informasi apa saja mengenai sekolah kita ke dalam Basis Pengetahuan. AI akan mempelajari seluruh data tersebut untuk merespons pertanyaan pengguna secara dinamis.
            </p>
            <ul class="space-y-1.5 text-xs text-slate-600 dark:text-zinc-400">
                <li class="flex gap-2"><span class="text-[#4f45b2] font-bold">•</span> <strong>Profil Umum:</strong> Visi misi, sejarah berdiri, struktur organisasi, alamat lengkap, fasilitas, kontak resmi, dll.</li>
                <li class="flex gap-2"><span class="text-[#4f45b2] font-bold">•</span> <strong>Informasi PPDB:</strong> Syarat administrasi, jadwal pendaftaran offline, alur seleksi masuk, rincian seragam, dll.</li>
                <li class="flex gap-2"><span class="text-[#4f45b2] font-bold">•</span> <strong>Akademik & Kegiatan:</strong> Program keahlian (jurusan), kurikulum, kalender akademik, jadwal ekstrakurikuler, dll.</li>
            </ul>
        </div>

        {{-- Tips --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border border-slate-200 dark:border-zinc-700 p-4">
                <p class="text-xs font-bold font-mono uppercase tracking-wider text-slate-600 dark:text-zinc-400 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-check-circle text-emerald-500"></i> Tips Menulis Konten yang Baik
                </p>
                <ul class="space-y-2 text-xs text-slate-600 dark:text-zinc-400">
                    <li class="flex gap-2"><span class="text-emerald-500 shrink-0">✓</span> Tulis dalam bahasa Indonesia yang jelas dan lengkap</li>
                    <li class="flex gap-2"><span class="text-emerald-500 shrink-0">✓</span> Sertakan angka dan fakta spesifik (Rp, tanggal, jam, nomor)</li>
                    <li class="flex gap-2"><span class="text-emerald-500 shrink-0">✓</span> Hindari singkatan yang ambigu — tulis lengkap</li>
                    <li class="flex gap-2"><span class="text-emerald-500 shrink-0">✓</span> Pisahkan satu topik per entri agar mudah diperbarui</li>
                    <li class="flex gap-2"><span class="text-emerald-500 shrink-0">✓</span> Perbarui secara berkala saat ada perubahan kebijakan</li>
                    <li class="flex gap-2"><span class="text-emerald-500 shrink-0">✓</span> Minimal 3–5 entri per topik untuk hasil terbaik</li>
                </ul>
            </div>
            <div class="border border-red-200 dark:border-red-800/50 p-4 bg-red-50/30 dark:bg-red-950/10">
                <p class="text-xs font-bold font-mono uppercase tracking-wider text-red-600 dark:text-red-400 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-xmark-circle text-red-500"></i> Yang Perlu Dihindari
                </p>
                <ul class="space-y-2 text-xs text-slate-600 dark:text-zinc-400">
                    <li class="flex gap-2"><span class="text-red-400 shrink-0">✗</span> Konten terlalu pendek (kurang dari 2 kalimat)</li>
                    <li class="flex gap-2"><span class="text-red-400 shrink-0">✗</span> Data lama yang sudah tidak berlaku</li>
                    <li class="flex gap-2"><span class="text-red-400 shrink-0">✗</span> Informasi sensitif seperti data siswa atau password</li>
                    <li class="flex gap-2"><span class="text-red-400 shrink-0">✗</span> Duplikasi konten yang sama di beberapa entri</li>
                    <li class="flex gap-2"><span class="text-red-400 shrink-0">✗</span> Teks dalam format tabel atau bullet yang tidak jelas</li>
                </ul>
            </div>
        </div>
    </div>

    <hr class="border-slate-200 dark:border-zinc-700">

    {{-- ════════════════════════════════════════
         LANGKAH 4 — BUAT FAQ CEPAT
    ════════════════════════════════════════ --}}
    <div id="guide-step4" class="scroll-mt-4">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-200 dark:border-zinc-700">
            <span class="w-9 h-9 bg-[#4f45b2] text-white font-bold text-base flex items-center justify-center shrink-0">4</span>
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-circle-question text-cyan-500"></i> Membuat FAQ Cepat
                </h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">FAQ adalah tombol pintasan pertanyaan di awal chatbot — dijawab instan dari database, tidak memanggil AI.</p>
            </div>
        </div>

        <div class="flex gap-3 mb-5 px-4 py-3 bg-cyan-50 dark:bg-cyan-950/20 border border-cyan-200 dark:border-cyan-800/50 text-xs text-cyan-700 dark:text-cyan-400">
            <i class="fa-solid fa-bolt shrink-0 mt-0.5"></i>
            <div><strong>Perbedaan FAQ vs Pengetahuan:</strong> FAQ muncul sebagai tombol pintasan dan langsung dijawab dari database (lebih cepat, hemat API quota). Pengetahuan digunakan AI sebagai konteks untuk menjawab pertanyaan bebas yang diketik pengguna.</div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-4">
                @foreach([
                    ['1', 'Buka tab "FAQ Cepat"', 'Klik kotak tab <strong>FAQ Cepat</strong> di deretan tab atas. Klik tombol <strong>TAMBAH FAQ</strong>.'],
                    ['2', 'Atur nomor urutan', 'Isi nomor urutan tampil. Angka <strong>0</strong> = tampil paling atas. FAQ diurutkan dari angka terkecil ke terbesar.'],
                    ['3', 'Tulis pertanyaan dan jawaban', 'Pertanyaan harus singkat dan jelas (maks. 255 karakter). Jawaban bisa panjang dan mendetail karena langsung ditampilkan ke pengguna.'],
                ] as [$n, $title, $desc])
                <div class="flex gap-4">
                    <div class="w-6 h-6 bg-[#4f45b2] text-white text-[11px] font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $n }}</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200">{{ $title }}</p>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 leading-relaxed">{!! $desc !!}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="space-y-3">
                <p class="text-xs font-bold font-mono uppercase tracking-wider text-slate-500 dark:text-zinc-400">Contoh FAQ yang Baik</p>
                @foreach([
                    ['Kapan jadwal PPDB dibuka?', 'PPDB MAM Limpung dibuka setiap tahun pada bulan Januari hingga Maret. Pendaftaran dilakukan secara langsung di sekretariat sekolah atau melalui website resmi sekolah di jam kerja (07.00–14.00 WIB).'],
                    ['Berapa biaya pendaftaran?', 'Biaya pendaftaran PPDB sebesar Rp 50.000 yang dibayarkan saat pengambilan formulir. Biaya ini sudah termasuk formulir pendaftaran dan kartu peserta seleksi.'],
                    ['Apa saja ekskul yang tersedia?', 'MAM Limpung memiliki berbagai kegiatan ekstrakurikuler: Pramuka (wajib), PMR, Seni Musik, Seni Tari, Olahraga (sepak bola, voli, bulu tangkis), Komputer, dan KIR. Jadwal ekskul setiap Sabtu pukul 08.00–11.00 WIB.'],
                ] as [$q, $a])
                <div class="border border-slate-200 dark:border-zinc-700 p-3 bg-slate-50 dark:bg-zinc-800/40">
                    <p class="text-xs font-bold text-slate-800 dark:text-zinc-200 mb-2">{{ $q }}</p>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 leading-relaxed">{{ $a }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <hr class="border-slate-200 dark:border-zinc-700">

    {{-- ════════════════════════════════════════
         LANGKAH 5 — UJI DAN PANTAU CHATBOT
    ════════════════════════════════════════ --}}
    <div id="guide-step5" class="scroll-mt-4">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-200 dark:border-zinc-700">
            <span class="w-9 h-9 bg-emerald-600 text-white font-bold text-base flex items-center justify-center shrink-0">5</span>
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-robot text-emerald-500"></i> Menguji dan Memantau Chatbot
                </h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Setelah semua langkah selesai, chatbot siap digunakan. Lakukan pengujian dan pantau performa secara berkala.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-5">
            {{-- Checklist --}}
            <div class="border border-slate-200 dark:border-zinc-700 p-5">
                <p class="text-xs font-bold font-mono uppercase tracking-wider text-slate-600 dark:text-zinc-400 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-[#4f45b2]"></i> Checklist Sebelum Uji Coba
                </p>
                <div class="space-y-2.5">
                    @foreach([
                        ['Minimal 1 API Key terdaftar dan statusnya AKTIF', true],
                        ['Minimal 3-5 entri basis pengetahuan sekolah sudah diisi', true],
                        ['Minimal 3–5 FAQ cepat sudah dibuat dan statusnya AKTIF', true],
                        ['Coba buka website di tab browser baru', false],
                    ] as [$item, $done])
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 border-2 {{ $done ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800' }} flex items-center justify-center shrink-0">
                            @if($done)<i class="fa-solid fa-check text-white text-[9px]"></i>@endif
                        </div>
                        <span class="text-xs text-slate-700 dark:text-zinc-300">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Cara uji --}}
            <div class="border border-slate-200 dark:border-zinc-700 p-5">
                <p class="text-xs font-bold font-mono uppercase tracking-wider text-slate-600 dark:text-zinc-400 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-vial text-indigo-500"></i> Cara Melakukan Pengujian
                </p>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="w-6 h-6 bg-[#4f45b2] text-white text-[11px] font-bold flex items-center justify-center shrink-0">1</div>
                        <div class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">Buka website sekolah di tab baru. Akan ada tombol robot <i class="fa-solid fa-robot text-[#4f45b2]"></i> di pojok kanan bawah halaman.</div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-6 h-6 bg-[#4f45b2] text-white text-[11px] font-bold flex items-center justify-center shrink-0">2</div>
                        <div class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">Klik tombol tersebut, coba klik FAQ cepat yang muncul — pastikan langsung dijawab.</div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-6 h-6 bg-[#4f45b2] text-white text-[11px] font-bold flex items-center justify-center shrink-0">3</div>
                        <div class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">Ketik pertanyaan bebas dan tunggu respons AI (biasanya 2–5 detik). Periksa apakah jawaban akurat.</div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-6 h-6 bg-[#4f45b2] text-white text-[11px] font-bold flex items-center justify-center shrink-0">4</div>
                        <div class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">Kembali ke halaman admin, buka tab <strong>Riwayat</strong> untuk melihat percakapan yang baru saja dilakukan.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Troubleshooting --}}
        <div class="border border-slate-200 dark:border-zinc-700">
            <div class="px-5 py-3.5 bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700">
                <p class="text-xs font-bold font-mono uppercase tracking-wider text-slate-600 dark:text-zinc-400 flex items-center gap-2">
                    <i class="fa-solid fa-wrench text-amber-500"></i> Troubleshooting — Masalah Umum & Solusinya
                </p>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-zinc-800">
                @foreach([
                    [
                        'fa-xmark-circle text-red-500',
                        'Chatbot tidak merespons sama sekali',
                        'Pastikan ada minimal 1 API Key dengan status <strong>AKTIF</strong> (badge hijau). Coba nonaktifkan lalu aktifkan kembali. Jika tetap tidak merespons, coba tambahkan API Key baru yang fresh dari Google AI Studio.',
                    ],
                    [
                        'fa-triangle-exclamation text-amber-500',
                        'Jawaban AI tidak akurat atau tidak relevan',
                        'Tambahkan lebih banyak konten di tab <strong>Pengetahuan</strong> untuk topik yang sering ditanyakan. Pastikan konten ditulis dengan kalimat lengkap dan faktual. Perbarui data yang sudah usang.',
                    ],
                    [
                        'fa-gauge-high text-orange-500',
                        'Muncul pesan "API limit reached" atau error 429',
                        'API Key telah mencapai batas request harian. Tambahkan API Key cadangan dari project Google yang berbeda, atau tunggu reset kuota keesokan harinya (reset tengah malam UTC).',
                    ],
                    [
                        'fa-eye-slash text-slate-500',
                        'FAQ tidak muncul di chatbot',
                        'Periksa tab <strong>FAQ Cepat</strong> — pastikan status FAQ adalah <strong>AKTIF</strong>.',
                    ],
                    [
                        'fa-clock text-indigo-500',
                        'Respons AI terasa sangat lambat (>10 detik)',
                        'Coba ganti ke model <code class="font-mono bg-slate-100 dark:bg-zinc-800 px-1">gemini-1.5-flash</code> yang lebih cepat. Jika sudah menggunakan flash, kemungkinan koneksi internet server sedang lambat.',
                    ],
                ] as [$icon, $problem, $solution])
                <div class="px-5 py-4">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid {{ $icon }} mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200">{{ $problem }}</p>
                            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 leading-relaxed">{!! $solution !!}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Bottom CTA ── --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-5 bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-700">
        <div>
            <p class="text-sm font-bold text-slate-800 dark:text-zinc-200">Siap memulai?</p>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Mulai dari tab Kunci API untuk mendaftarkan API Key Anda.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" onclick="chatbotTab('apikeys')"
                class="inline-flex items-center gap-2 py-2.5 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs font-mono uppercase tracking-wider transition-colors cursor-pointer">
                <i class="fa-solid fa-key"></i> Buka Tab Kunci API
            </button>
            <a href="https://aistudio.google.com" target="_blank"
                class="inline-flex items-center gap-2 py-2.5 px-4 bg-white dark:bg-zinc-900 hover:bg-slate-50 dark:hover:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs font-mono uppercase tracking-wider transition-colors cursor-pointer">
                <i class="fa-solid fa-arrow-up-right-from-square text-[#4f45b2]"></i> Google AI Studio
            </a>
        </div>
    </div>

</div>
