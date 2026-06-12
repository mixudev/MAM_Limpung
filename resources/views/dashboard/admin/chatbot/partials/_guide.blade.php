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
         LANGKAH 1 — DAPATKAN API KEY GEMINI
    ════════════════════════════════════════ --}}
    <div id="guide-step1" class="scroll-mt-4">
        {{-- Step header --}}
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-200 dark:border-zinc-700">
            <span class="w-9 h-9 bg-[#4f45b2] text-white font-bold text-base flex items-center justify-center shrink-0">1</span>
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-key text-amber-500"></i> Mendapatkan Google Gemini API Key
                </h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">API Key adalah kunci akses ke model AI Google Gemini. Tersedia <strong>gratis</strong> tanpa kartu kredit.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-4">
                {{-- Sub-steps --}}
                @foreach([
                    ['1', 'Buka Google AI Studio', 'Kunjungi <a href="https://aistudio.google.com" target="_blank" class="text-[#4f45b2] underline font-semibold">aistudio.google.com</a> di browser Anda. Login menggunakan akun Google — bisa akun pribadi maupun akun sekolah.'],
                    ['2', 'Klik menu "Get API Key"', 'Di sidebar kiri Google AI Studio, cari dan klik menu <strong>Get API Key</strong>. Halaman daftar API Key akan terbuka.'],
                    ['3', 'Klik "Create API key"', 'Klik tombol <strong>Create API key in new project</strong>. Google akan otomatis membuat project baru dan menggenerate API Key untuk Anda dalam beberapa detik.'],
                    ['4', 'Salin API Key', 'Setelah API Key muncul, klik ikon <strong>Copy</strong> di sebelah kanan. API Key berbentuk string panjang yang diawali huruf <code class="bg-slate-100 dark:bg-zinc-800 px-1 font-mono">AIza…</code>. Simpan di tempat aman.'],
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
                {{-- Contoh API Key --}}
                <div class="border border-slate-200 dark:border-zinc-700">
                    <div class="px-4 py-2.5 bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700">
                        <p class="text-[10px] font-bold font-mono uppercase tracking-wider text-slate-500 dark:text-zinc-400">Contoh tampilan API Key</p>
                    </div>
                    <div class="p-4 bg-slate-900 overflow-x-auto">
                        <code class="text-emerald-400 text-xs font-mono">AIzaSyBxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</code>
                    </div>
                </div>

                {{-- Model rekomendasi --}}
                <div class="border border-blue-200 dark:border-blue-800/50">
                    <div class="px-4 py-2.5 bg-blue-50 dark:bg-blue-950/20 border-b border-blue-200 dark:border-blue-800/50">
                        <p class="text-[10px] font-bold font-mono uppercase tracking-wider text-blue-600 dark:text-blue-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-star"></i> Rekomendasi Model
                        </p>
                    </div>
                    <div class="divide-y divide-blue-100 dark:divide-blue-900/30">
                        <div class="px-4 py-3 flex items-start gap-3">
                            <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold font-mono shrink-0">REKOMENDASI</span>
                            <div>
                                <code class="text-sm font-mono font-bold text-slate-800 dark:text-zinc-200">gemini-1.5-flash</code>
                                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Cepat, hemat kuota, cocok untuk chatbot sekolah. Tersedia gratis.</p>
                            </div>
                        </div>
                        <div class="px-4 py-3 flex items-start gap-3">
                            <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-600 text-[10px] font-bold font-mono shrink-0">ALTERNATIF</span>
                            <div>
                                <code class="text-sm font-mono font-bold text-slate-800 dark:text-zinc-200">gemini-1.5-pro</code>
                                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Lebih detail dan akurat, tapi lebih lambat. Cocok untuk pertanyaan kompleks.</p>
                            </div>
                        </div>
                        <div class="px-4 py-3 flex items-start gap-3">
                            <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-600 text-[10px] font-bold font-mono shrink-0">TERBARU</span>
                            <div>
                                <code class="text-sm font-mono font-bold text-slate-800 dark:text-zinc-200">gemini-2.0-flash</code>
                                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Versi terbaru 2025, lebih pintar dari 1.5-flash. Tersedia gratis.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Batas gratis --}}
                <div class="flex gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/50 text-xs text-amber-700 dark:text-amber-400">
                    <i class="fa-solid fa-triangle-exclamation shrink-0 mt-0.5"></i>
                    <div>
                        <strong>Batas Gratis (Free Tier):</strong> ~15 request/menit dan ~1 juta token/hari. Lebih dari cukup untuk penggunaan chatbot sekolah normal.
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
                    <i class="fa-solid fa-circle-plus text-indigo-500"></i> Mendaftarkan API Key ke Sistem
                </h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Setelah mendapat API Key, daftarkan ke halaman ini agar chatbot bisa berkomunikasi dengan AI.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-4">
                @foreach([
                    ['1', 'Klik tab "Kunci API"', 'Di deretan kotak tab di atas halaman ini, klik kotak <strong>Kunci API</strong>. Halaman akan menampilkan daftar key yang sudah terdaftar.'],
                    ['2', 'Klik "TAMBAH API KEY"', 'Klik tombol ungu <strong>TAMBAH API KEY</strong> di pojok kanan. Form isian akan muncul.'],
                    ['3', 'Isi form dengan benar', 'Pilih provider <strong>Google Gemini</strong>, isi nama model (misalnya <code class="font-mono bg-slate-100 dark:bg-zinc-800 px-1">gemini-1.5-flash</code>), lalu paste API Key dari Google AI Studio.'],
                    ['4', 'Aktifkan key', 'Setelah tersimpan, pastikan status key menampilkan badge hijau <strong>AKTIF</strong>. Klik badge untuk toggle aktif/nonaktif.'],
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
                {{-- Panduan isi form --}}
                <div class="border border-slate-200 dark:border-zinc-700">
                    <div class="px-4 py-2.5 bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700">
                        <p class="text-[10px] font-bold font-mono uppercase tracking-wider text-slate-500 dark:text-zinc-400">Panduan Isi Form API Key</p>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                        <div class="px-4 py-3 flex gap-3">
                            <span class="font-bold text-slate-600 dark:text-zinc-400 w-28 shrink-0">Provider</span>
                            <span class="text-slate-500 dark:text-zinc-400">Pilih <strong>Google Gemini</strong></span>
                        </div>
                        <div class="px-4 py-3 flex gap-3">
                            <span class="font-bold text-slate-600 dark:text-zinc-400 w-28 shrink-0">Nama Model</span>
                            <div>
                                <code class="font-mono bg-slate-100 dark:bg-zinc-800 px-1.5 py-0.5">gemini-1.5-flash</code>
                                <span class="text-slate-400 ml-1">(direkomendasikan)</span>
                            </div>
                        </div>
                        <div class="px-4 py-3 flex gap-3">
                            <span class="font-bold text-slate-600 dark:text-zinc-400 w-28 shrink-0">API Key</span>
                            <span class="text-slate-500 dark:text-zinc-400">Paste dari Google AI Studio. Dimulai dengan <code class="font-mono bg-slate-100 dark:bg-zinc-800 px-1">AIza…</code></span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/50 text-xs text-emerald-700 dark:text-emerald-400">
                    <i class="fa-solid fa-shield-halved shrink-0 mt-0.5"></i>
                    <div><strong>Keamanan:</strong> API Key dienkripsi menggunakan Laravel Encryption sebelum disimpan. Nilai asli tidak bisa dilihat kembali dari halaman admin. Anda bisa menambahkan beberapa key sebagai cadangan.</div>
                </div>

                <div class="flex gap-3 px-4 py-3 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800/50 text-xs text-blue-700 dark:text-blue-400">
                    <i class="fa-solid fa-circle-info shrink-0 mt-0.5"></i>
                    <div><strong>Tips:</strong> Tambahkan 2–3 API Key dari project Google yang berbeda sebagai cadangan. Jika satu key mencapai batas, sistem akan otomatis mencoba key berikutnya.</div>
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

        {{-- Tabel topik --}}
        <div class="overflow-x-auto border border-slate-200 dark:border-zinc-700 mb-5">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                        <th class="py-3 px-4 text-left w-28">Topik</th>
                        <th class="py-3 px-4 text-left">Contoh Isi yang Perlu Ditambahkan</th>
                        <th class="py-3 px-4 text-left w-32 hidden md:table-cell">Prioritas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30">
                        <td class="py-3 px-4"><span class="px-2 py-0.5 bg-indigo-50 border border-indigo-200 text-[#4f45b2] text-[9px] font-bold font-mono uppercase">UMUM</span></td>
                        <td class="py-3 px-4 text-slate-600 dark:text-zinc-400">Profil sekolah, visi misi, sejarah berdiri, alamat lengkap, nomor telepon, email, jam operasional, fasilitas, jurusan/program</td>
                        <td class="py-3 px-4 hidden md:table-cell"><span class="px-2 py-0.5 bg-red-50 border border-red-200 text-red-600 text-[9px] font-bold font-mono uppercase">WAJIB</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30">
                        <td class="py-3 px-4"><span class="px-2 py-0.5 bg-sky-50 border border-sky-200 text-sky-700 text-[9px] font-bold font-mono uppercase">PPDB</span></td>
                        <td class="py-3 px-4 text-slate-600 dark:text-zinc-400">Syarat pendaftaran, jadwal penerimaan, biaya pendaftaran, dokumen yang dibutuhkan, prosedur tes/seleksi, daftar ulang, link formulir</td>
                        <td class="py-3 px-4 hidden md:table-cell"><span class="px-2 py-0.5 bg-red-50 border border-red-200 text-red-600 text-[9px] font-bold font-mono uppercase">WAJIB</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30">
                        <td class="py-3 px-4"><span class="px-2 py-0.5 bg-amber-50 border border-amber-200 text-amber-700 text-[9px] font-bold font-mono uppercase">KEGIATAN</span></td>
                        <td class="py-3 px-4 text-slate-600 dark:text-zinc-400">Jadwal ujian semester, kalender akademik, daftar ekstrakurikuler beserta jadwal, agenda tahunan sekolah (upacara, wisuda, dll)</td>
                        <td class="py-3 px-4 hidden md:table-cell"><span class="px-2 py-0.5 bg-amber-50 border border-amber-200 text-amber-700 text-[9px] font-bold font-mono uppercase">PENTING</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30">
                        <td class="py-3 px-4"><span class="px-2 py-0.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-[9px] font-bold font-mono uppercase">BANTUAN</span></td>
                        <td class="py-3 px-4 text-slate-600 dark:text-zinc-400">Cara menghubungi guru/TU, prosedur izin tidak masuk, tata tertib sekolah, info beasiswa, prosedur pengaduan</td>
                        <td class="py-3 px-4 hidden md:table-cell"><span class="px-2 py-0.5 bg-amber-50 border border-amber-200 text-amber-700 text-[9px] font-bold font-mono uppercase">PENTING</span></td>
                    </tr>
                </tbody>
            </table>
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
                    ['2', 'Pilih topik yang sesuai', 'Pilih topik yang sesuai (Umum, PPDB, Kegiatan, Bantuan). FAQ hanya ditampilkan saat pengguna memilih topik yang sama di chatbot.'],
                    ['3', 'Atur nomor urutan', 'Isi nomor urutan tampil. Angka <strong>0</strong> = tampil paling atas. FAQ diurutkan dari angka terkecil ke terbesar.'],
                    ['4', 'Tulis pertanyaan dan jawaban', 'Pertanyaan harus singkat dan jelas (maks. 255 karakter). Jawaban bisa panjang dan mendetail karena langsung ditampilkan ke pengguna.'],
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
                    ['Kapan jadwal PPDB dibuka?', 'PPDB MAM Limpung dibuka setiap tahun pada bulan Januari hingga Maret. Pendaftaran dilakukan secara langsung di sekretariat sekolah atau melalui website resmi sekolah di jam kerja (07.00–14.00 WIB).', 'ppdb', 'bg-sky-50 border-sky-200 text-sky-700'],
                    ['Berapa biaya pendaftaran?', 'Biaya pendaftaran PPDB sebesar Rp 50.000 yang dibayarkan saat pengambilan formulir. Biaya ini sudah termasuk formulir pendaftaran dan kartu peserta seleksi.', 'ppdb', 'bg-sky-50 border-sky-200 text-sky-700'],
                    ['Apa saja ekskul yang tersedia?', 'MAM Limpung memiliki berbagai kegiatan ekstrakurikuler: Pramuka (wajib), PMR, Seni Musik, Seni Tari, Olahraga (sepak bola, voli, bulu tangkis), Komputer, dan KIR. Jadwal ekskul setiap Sabtu pukul 08.00–11.00 WIB.', 'kegiatan', 'bg-amber-50 border-amber-200 text-amber-700'],
                ] as [$q, $a, $t, $cls])
                <div class="border border-slate-200 dark:border-zinc-700 p-3 bg-slate-50 dark:bg-zinc-800/40">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <p class="text-xs font-bold text-slate-800 dark:text-zinc-200">{{ $q }}</p>
                        <span class="px-1.5 py-0.5 border {{ $cls }} text-[9px] font-bold font-mono uppercase shrink-0">{{ $t }}</span>
                    </div>
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
                        ['Minimal 2–3 entri basis pengetahuan topik Umum sudah diisi', true],
                        ['Minimal 2–3 entri basis pengetahuan topik PPDB sudah diisi', true],
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
                        <div class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">Klik tombol tersebut, pilih topik, coba klik FAQ yang muncul — pastikan langsung dijawab.</div>
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
                        'Periksa tab <strong>FAQ Cepat</strong> — pastikan status FAQ adalah <strong>AKTIF</strong>. Periksa juga topik FAQ sudah sesuai dengan topik yang dipilih pengguna di chatbot.',
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
