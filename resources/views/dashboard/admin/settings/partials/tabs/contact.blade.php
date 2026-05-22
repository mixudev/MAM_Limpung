<!-- 2. TAB: CONTACT -->
<div id="tab-contact" class="tab-content hidden space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Email Sekolah</label>
            <input type="email" name="email" value="{{ old('email', $siteSetting->email) }}" placeholder="info@mamlimpung.sch.id"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nomor Telepon Kantor</label>
            <input type="text" name="phone" value="{{ old('phone', $siteSetting->phone) }}" placeholder="+62 21 1234 5678"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">WhatsApp Official</label>
            <input type="text" name="whatsapp" value="{{ old('whatsapp', $siteSetting->whatsapp) }}" placeholder="628123456789"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>
    </div>

    <div>
        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Alamat Lengkap Sekolah</label>
        <textarea name="address" rows="3" placeholder="Jl. Cokronegoro No.34, Gepor, Limpung..."
                  class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-y">{{ old('address', $siteSetting->address) }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Google Maps Embed Iframe Code</label>
        <textarea name="google_maps_iframe" rows="4" placeholder="Masukkan tag <iframe> dari Google Maps..."
                  class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-y">{{ old('google_maps_iframe', $siteSetting->google_maps_iframe) }}</textarea>
        <p class="text-[10px] text-slate-400 dark:text-zinc-600 mt-1">Petunjuk: Cari lokasi di Google Maps -> Bagikan -> Sematkan Peta -> Salin HTML.</p>
    </div>
</div>
