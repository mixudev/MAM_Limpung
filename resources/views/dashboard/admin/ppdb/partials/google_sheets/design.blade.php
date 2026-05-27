<!-- Tab 4: Design & Theme -->
<div x-show="activeTab === 'design'" class="space-y-6 animate-fadeIn" style="display: none;">
    <!-- Header Style Selection -->
    <div>
        <label class="header_style text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Gaya Tampilan Header Google Sheet</label>
        <select name="header_style" id="header_style" class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
            <option value="purple" {{ $settings['header_style'] === 'purple' ? 'selected' : '' }}>Ungu Premium (Tema Almamater MAM)</option>
            <option value="emerald" {{ $settings['header_style'] === 'emerald' ? 'selected' : '' }}>Hijau Emerald Akademik</option>
            <option value="dark" {{ $settings['header_style'] === 'dark' ? 'selected' : '' }}>Abu Gelap Charcoal / Mewah</option>
            <option value="plain" {{ $settings['header_style'] === 'plain' ? 'selected' : '' }}>Sederhana (Tanpa Warna Latar)</option>
        </select>
    </div>
    
    <div class="p-4 bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-800">
        <h4 class="text-xs font-bold text-slate-800 dark:text-white block mb-2">Tips Tampilan Google Sheets Mewah</h4>
        <ul class="list-disc list-inside text-xs text-slate-400 dark:text-zinc-500 space-y-1.5 leading-relaxed">
            <li>Sistem otomatis membekukan baris pertama (frozen row) agar judul kolom tetap di atas saat Anda melakukan scroll.</li>
            <li>Sistem otomatis melebarkan kolom sesuai panjang data (auto-fit columns) sehingga tampilan tabel rapi dan tidak terpotong.</li>
            <li>Gaya warna sel diselaraskan penuh dengan pilihan tema yang Anda pilih untuk semua tab lembar kerja yang aktif.</li>
        </ul>
    </div>
</div>
