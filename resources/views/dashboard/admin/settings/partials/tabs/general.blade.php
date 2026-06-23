<!-- 1. TAB: PROFILE SEKOLAH -->
<div id="tab-general" class="tab-content space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Column 1: Logo Drag & Drop -->
        <div class="flex flex-col space-y-4">
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                Logo Sekolah
            </label>
            
            <div id="dropzone" 
                 class="relative border-2 border-dashed border-slate-300 dark:border-zinc-700 hover:border-indigo-400 dark:hover:border-zinc-500 bg-slate-50 dark:bg-zinc-950 p-6 flex flex-col items-center justify-center min-h-[220px] text-center cursor-pointer transition-all group">
                
                <input type="file" name="logo" id="fileInput" class="hidden" accept="image/*" />
                
                <!-- State Empty/Upload -->
                <div id="upload-state" class="{{ $siteSetting->logo_path ? 'hidden' : '' }} space-y-3 py-4">
                    <div class="p-3 bg-indigo-50 dark:bg-zinc-900 rounded-full inline-block group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-xs">
                        <span class="text-indigo-600 dark:text-indigo-400 font-bold">Klik untuk unggah</span> atau seret dan lepas gambar ke sini
                    </div>
                    <p class="text-[10px] text-slate-400 dark:text-zinc-650">PNG, JPG, JPEG, atau WEBP hingga 2MB</p>
                </div>

                <!-- State Preview -->
                <div id="preview-state" class="{{ $siteSetting->logo_path ? '' : 'hidden' }} w-full flex flex-col items-center justify-center py-2 relative">
                    <div class="w-32 h-32 relative border border-slate-200 dark:border-zinc-800 bg-white p-2">
                        <img id="logo-preview" 
                             src="{{ $siteSetting->logo_path ? asset('storage/' . $siteSetting->logo_path) : '' }}" 
                             class="w-full h-full object-contain" />
                    </div>
                    <button type="button" onclick="resetFileSelection()" class="mt-4 py-1.5 px-3 bg-rose-600 hover:bg-rose-700 text-white font-mono text-[10px] font-bold uppercase tracking-wider transition-colors">
                        Ganti Logo
                    </button>
                </div>
            </div>
        </div>

        <!-- Column 2 & 3: Info Sekolah -->
        <div class="lg:col-span-2 space-y-4">
            <div>
                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nama Sekolah / Website <span class="text-rose-500">*</span></label>
                <input type="text" name="school_name" value="{{ old('school_name', $siteSetting->school_name) }}" required placeholder="Contoh: MAM Limpung"
                       class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
            </div>

            <div>
                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Profil Singkat Sekolah</label>
                <textarea name="about_short" rows="4" placeholder="Teks singkat tentang sekolah yang tampil di footer..."
                          class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-y">{{ old('about_short', $siteSetting->about_short) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
        </div>
    </div>
</div>
