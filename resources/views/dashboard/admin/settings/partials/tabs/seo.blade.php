<!-- 6. TAB: SEO & ANALYTICS -->
<div id="tab-seo" class="tab-content hidden space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
            <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 mb-1">Pengaturan SEO Global</h3>
            <p class="text-xs text-slate-400 dark:text-zinc-500">Kelola meta tag dan visibilitas mesin pencari website sekolah.</p>
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Meta Title (SEO)</label>
            <input type="text" name="meta_title" value="{{ old('meta_title', $siteSetting->meta_title) }}" placeholder="MAM Limpung - Unggul dan Berprestasi"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Meta Description (SEO)</label>
            <input type="text" name="meta_description" value="{{ old('meta_description', $siteSetting->meta_description) }}" placeholder="Deskripsi pencarian Google..."
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Meta Keywords Global (Pisahkan dengan koma)</label>
            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $siteSetting->meta_keywords) }}" placeholder="mam limpung, ma muhammadiyah limpung, sekolah islam limpung, batang"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div class="md:col-span-2 flex items-start gap-3 bg-slate-50 dark:bg-zinc-950 p-4 border border-slate-200 dark:border-zinc-800 rounded-md">
            <div class="flex items-center h-5">
                <input type="checkbox" name="is_indexed" id="is_indexed" value="1" {{ old('is_indexed', $siteSetting->is_indexed) ? 'checked' : '' }}
                       class="w-4 h-4 text-indigo-605 border-slate-350 rounded focus:ring-indigo-500">
            </div>
            <div class="text-xs leading-none">
                <label for="is_indexed" class="font-bold text-slate-700 dark:text-zinc-300">Izinkan Mesin Pencari Mengindeks Website Ini (Index Globally)</label>
                <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1">Jika dinonaktifkan (misalnya saat mode perbaikan/staging), robots.txt akan otomatis melarang seluruh Google bot/crawler mengindeks website.</p>
            </div>
        </div>

        <div class="md:col-span-2 border-t border-slate-200 dark:border-zinc-800 pt-6">
            <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 mb-1">Integrasi Google & Analitik</h3>
            <p class="text-xs text-slate-400 dark:text-zinc-500">Masukkan kode/ID integrasi untuk memantau trafik pengunjung dan performa pencarian sekolah.</p>
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Google Analytics (GA4) Measurement ID</label>
            <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $siteSetting->google_analytics_id) }}" placeholder="Contoh: G-XXXXXXXXXX"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Google Search Console Verification Code</label>
            <input type="text" name="google_search_console_id" value="{{ old('google_search_console_id', $siteSetting->google_search_console_id) }}" placeholder="Contoh: abcd1234efgh5678"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
            <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1">Hanya masukkan kode konten dari Google HTML tag verification.</p>
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Google Tag Manager ID</label>
            <input type="text" name="google_tag_manager_id" value="{{ old('google_tag_manager_id', $siteSetting->google_tag_manager_id) }}" placeholder="Contoh: GTM-XXXXXXX"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>
    </div>
</div>
