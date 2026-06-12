<div class="mt-10 space-y-6 pt-16">
    <div class="flex items-center justify-between border-b-2 border-cyan-500 dark:border-cyan-500 pb-4">
        <div class="flex items-center gap-2">
            <span class="p-1.5 bg-[#4f45b2]/10 rounded text-[#4f45b2]">
                <i class="fa-solid fa-chart-line text-sm"></i>
            </span>
            <div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200">Optimasi SEO & Pencarian Google</h3>
                <p class="text-[10px] text-slate-400 dark:text-zinc-500">Sesuaikan tampilan artikel di halaman mesin
                    pencari Google dan media sosial.</p>
            </div>
        </div>
        <span
            class="py-0.5 px-2 bg-[#4f45b2]/10 text-[#4f45b2] dark:text-indigo-400 font-mono text-[9px] font-bold uppercase tracking-wider rounded">Custom
            SEO</span>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Left Column: Inputs (2/3 width) -->
        <div class="xl:col-span-2 space-y-4">
            <!-- Focus Keyword -->
            <div>
                <label
                    class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Kata
                    Kunci Fokus (Focus Keyword)</label>
                <input type="text" name="seo_focus_keyword" id="seo_focus_keyword"
                    value="{{ old('seo_focus_keyword') }}" placeholder="Contoh: prestasi sekolah, ppdb 2026"
                    class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
            </div>

            <!-- Meta Title -->
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label
                        class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Judul
                        SEO (Meta Title)</label>
                    <span id="title-counter" class="text-[10px] font-mono text-slate-400 dark:text-zinc-500">0 / 60
                        karakter</span>
                </div>
                <input type="text" name="seo_meta_title" id="seo_meta_title" value="{{ old('seo_meta_title') }}"
                    placeholder="Kosongkan untuk menggunakan judul artikel utama"
                    class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
            </div>

            <!-- Meta Description -->
            <div> 
                <div class="flex justify-between items-center mb-1.5">
                    <div class="flex items-center gap-1.5">
                        <label
                            class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Deskripsi
                            SEO (Meta Description)</label>
                        <button type="button" id="btn-generate-seo"
                            class="py-0.5 px-2 bg-indigo-50 dark:bg-zinc-800 text-[#4f45b2] dark:text-indigo-400 hover:bg-[#4f45b2]/10 font-bold font-mono text-[9px] uppercase tracking-wider transition-all border border-indigo-100 dark:border-zinc-700">
                            <i class="fa-solid fa-wand-magic-sparkles mr-1"></i> Auto-Generate
                        </button>
                    </div>
                    <span id="desc-counter" class="text-[10px] font-mono text-slate-400 dark:text-zinc-500">0 / 160
                        karakter</span>
                </div>
                <textarea name="seo_meta_description" id="seo_meta_description" rows="3"
                    placeholder="Masukkan ringkasan artikel yang memikat pembaca di hasil pencarian..."
                    class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] resize-y">{{ old('seo_meta_description') }}</textarea>
            </div>

            <!-- Meta Keywords -->
            <div>
                <label
                    class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Kata
                    Kunci Tambahan (Keywords - Pisahkan dengan koma)</label>
                <input type="text" name="seo_meta_keywords" id="seo_meta_keywords"
                    value="{{ old('seo_meta_keywords') }}" placeholder="sekolah unggul, mam limpung, prestasi batang"
                    class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
            </div>

            <!-- Canonical URL -->
            <div>
                <label
                    class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Canonical
                    URL (Kosongkan kecuali merujuk ke website eksternal)</label>
                <input type="text" name="seo_canonical_url" id="seo_canonical_url"
                    value="{{ old('seo_canonical_url') }}" placeholder="https://website-asal.com/artikel-asli"
                    class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
            </div>

            <!-- Advanced SEO Robot Toggles -->
            <div class="grid grid-cols-2 gap-4 pt-2">
                <div class="flex items-start gap-2.5">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="seo_is_indexed" id="seo_is_indexed" value="1"
                            {{ old('seo_is_indexed', '1') == '1' ? 'checked' : '' }}
                            class="w-4 h-4 text-[#4f45b2] border-slate-300 rounded focus:ring-[#4f45b2]">
                    </div>
                    <div class="text-xs">
                        <label for="seo_is_indexed" class="font-bold text-slate-700 dark:text-zinc-300">Izinkan Mesin
                            Pencari (Index)</label>
                        <p class="text-[10px] text-slate-400 dark:text-zinc-500">Jika dimatikan, halaman akan diberi tag
                            'noindex' agar tidak terdaftar di Google.</p>
                    </div>
                </div>
                <div class="flex items-start gap-2.5">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="seo_is_followed" id="seo_is_followed" value="1"
                            {{ old('seo_is_followed', '1') == '1' ? 'checked' : '' }}
                            class="w-4 h-4 text-[#4f45b2] border-slate-300 rounded focus:ring-[#4f45b2]">
                    </div>
                    <div class="text-xs">
                        <label for="seo_is_followed" class="font-bold text-slate-700 dark:text-zinc-300">Ikuti Link di
                            Artikel (Follow)</label>
                        <p class="text-[10px] text-slate-400 dark:text-zinc-500">Mengarahkan Google bot untuk merayap
                            ('follow') seluruh link luar di dalam artikel.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Snippet Preview & SEO Checklist (1/3 width) -->
        <div class="space-y-6">
            <!-- Google Snippet Mockup Container -->
            <div class="p-4 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-850 pb-2">
                    <span
                        class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Google
                        Search Preview</span>
                    <div class="flex gap-2 text-[9px] font-mono">
                        <button type="button" id="preview-tab-mobile"
                            class="px-1.5 py-0.5 font-bold uppercase tracking-wider bg-slate-100 dark:bg-zinc-850 text-[#4f45b2] rounded">Mobile</button>
                        <button type="button" id="preview-tab-desktop"
                            class="px-1.5 py-0.5 font-bold uppercase tracking-wider text-slate-400 rounded">Desktop</button>
                    </div>
                </div>

                <!-- LIVE SNIPPET MOCKUP -->
                <div class="space-y-2 pt-1 font-sans text-left">
                    <!-- Breadcrumb/URL -->
                    <div class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-zinc-400">
                        <span
                            class="w-4 h-4 bg-slate-100 dark:bg-zinc-850 rounded-full flex items-center justify-center text-[9px]"><i
                                class="fa-solid fa-globe"></i></span>
                        <div class="flex flex-col leading-none">
                            <span
                                class="text-[10px] font-medium text-slate-800 dark:text-zinc-300">mamlimpung.sch.id</span>
                            <span id="snippet-url"
                                class="text-[9px] text-slate-400 dark:text-zinc-500 truncate max-w-[200px]">/artikel/contoh-slug</span>
                        </div>
                    </div>

                    <!-- Live Title -->
                    <h4 id="snippet-title"
                        class="text-sm font-medium text-blue-800 dark:text-blue-400 hover:underline cursor-pointer leading-tight line-clamp-2">
                        Judul Artikel Utama Tampil di Sini...
                    </h4>

                    <!-- Live Snippet Text -->
                    <p id="snippet-desc" class="text-xs text-slate-600 dark:text-zinc-400 leading-snug line-clamp-3">
                        Deskripsi pencarian artikel Google. Masukkan ringkasan menarik di kolom deskripsi SEO untuk
                        menggoda pengunjung mencet artikel ini...
                    </p>
                </div>
            </div>

            <!-- SEO Real-time Checklist -->
            <div
                class="p-4 bg-slate-100/50 dark:bg-zinc-950/20 border border-slate-200 dark:border-zinc-800 space-y-3">
                <h4
                    class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 border-b border-slate-200 dark:border-zinc-850 pb-2 flex items-center justify-between">
                    <span>Analisis Real-time SEO</span>
                    <span id="seo-score" class="font-bold text-[#4f45b2]">0% Score</span>
                </h4>

                <ul class="space-y-2.5 text-xs">
                    <li id="check-title-len" class="flex items-start gap-2 text-slate-500 dark:text-zinc-450">
                        <i class="fa-solid fa-circle-xmark text-rose-500 mt-0.5"></i>
                        <span>Judul SEO ideal (50-60 karakter)</span>
                    </li>
                    <li id="check-desc-len" class="flex items-start gap-2 text-slate-500 dark:text-zinc-450">
                        <i class="fa-solid fa-circle-xmark text-rose-500 mt-0.5"></i>
                        <span>Deskripsi SEO ideal (120-160 karakter)</span>
                    </li>
                    <li id="check-keyword-filled" class="flex items-start gap-2 text-slate-500 dark:text-zinc-450">
                        <i class="fa-solid fa-circle-xmark text-rose-500 mt-0.5"></i>
                        <span>Kata Kunci Fokus diisi</span>
                    </li>
                    <li id="check-keyword-in-title" class="flex items-start gap-2 text-slate-500 dark:text-zinc-450">
                        <i class="fa-solid fa-circle-xmark text-rose-500 mt-0.5"></i>
                        <span>Kata Kunci Fokus ada di Judul SEO</span>
                    </li>
                    <li id="check-keyword-in-desc" class="flex items-start gap-2 text-slate-500 dark:text-zinc-450">
                        <i class="fa-solid fa-circle-xmark text-rose-500 mt-0.5"></i>
                        <span>Kata Kunci Fokus ada di Deskripsi SEO</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
