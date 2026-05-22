<!-- 5. TAB: DATA SEKOLAH -->
<div id="tab-school-data" class="tab-content hidden space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Kode Sekolah (NPSN/NSSS)</label>
            <input type="text" name="school_code" value="{{ old('school_code', $siteSetting->school_code) }}" placeholder="Contoh: 20401152"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Tahun Berdiri Sekolah</label>
            <input type="number" name="school_founding_year" value="{{ old('school_founding_year', $siteSetting->school_founding_year) }}" placeholder="Contoh: 1980" min="1900" max="{{ date('Y') }}"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Status Sekolah</label>
            <select name="school_status"
                    class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                <option value="">-- Pilih Status --</option>
                <option value="Negeri" {{ old('school_status', $siteSetting->school_status) === 'Negeri' ? 'selected' : '' }}>Negeri</option>
                <option value="Swasta" {{ old('school_status', $siteSetting->school_status) === 'Swasta' ? 'selected' : '' }}>Swasta</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Akreditasi</label>
            <select name="school_accreditation"
                    class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                <option value="">-- Pilih Akreditasi --</option>
                <option value="A" {{ old('school_accreditation', $siteSetting->school_accreditation) === 'A' ? 'selected' : '' }}>A (Sangat Baik)</option>
                <option value="B" {{ old('school_accreditation', $siteSetting->school_accreditation) === 'B' ? 'selected' : '' }}>B (Baik)</option>
                <option value="C" {{ old('school_accreditation', $siteSetting->school_accreditation) === 'C' ? 'selected' : '' }}>C (Cukup)</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Motto / Semangat Sekolah</label>
        <textarea name="school_motto" rows="3" placeholder="Contoh: Unggul dalam Akademik, Utama dalam Akhlak, Unikan dalam Inovasi"
                  class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-y">{{ old('school_motto', $siteSetting->school_motto) }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Website Sekolah</label>
            <input type="url" name="school_website" value="{{ old('school_website', $siteSetting->school_website) }}" placeholder="https://mamlimpung.sch.id"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>

        <div>
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Email Resmi Sekolah</label>
            <input type="email" name="school_email_official" value="{{ old('school_email_official', $siteSetting->school_email_official) }}" placeholder="sekolah@mamlimpung.sch.id"
                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
        </div>
    </div>
</div>
