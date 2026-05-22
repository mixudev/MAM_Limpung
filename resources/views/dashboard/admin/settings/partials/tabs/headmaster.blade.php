<!-- 4. TAB: KEPALA SEKOLAH -->
<div id="tab-headmaster" class="tab-content hidden space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Column 1: Signature Drag & Drop -->
        <div class="flex flex-col space-y-4">
            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                Tanda Tangan Kepala Sekolah
            </label>
            
            <div id="signature-dropzone" 
                 class="relative border-2 border-dashed border-slate-300 dark:border-zinc-700 hover:border-indigo-400 dark:hover:border-zinc-500 bg-slate-50 dark:bg-zinc-950 p-6 flex flex-col items-center justify-center min-h-[220px] text-center cursor-pointer transition-all group">
                
                <input type="file" name="headmaster_signature" id="signatureFileInput" class="hidden" accept="image/*" />
                
                <!-- State Empty/Upload -->
                <div id="signature-upload-state" class="{{ $siteSetting->headmaster_signature ? 'hidden' : '' }} space-y-3 py-4">
                    <div class="p-3 bg-indigo-50 dark:bg-zinc-900 rounded-full inline-block group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-xs">
                        <span class="text-indigo-600 dark:text-indigo-400 font-bold">Klik untuk unggah</span> atau seret dan lepas gambar ke sini
                    </div>
                    <p class="text-[10px] text-slate-400 dark:text-zinc-650">PNG, JPG, JPEG, atau WEBP hingga 1MB</p>
                </div>

                <!-- State Preview -->
                <div id="signature-preview-state" class="{{ $siteSetting->headmaster_signature ? '' : 'hidden' }} w-full flex flex-col items-center justify-center py-2 relative">
                    <div class="w-32 h-32 relative border border-slate-200 dark:border-zinc-800 bg-white p-2">
                        <img id="signature-preview" 
                             src="{{ $siteSetting->headmaster_signature ? asset('storage/' . $siteSetting->headmaster_signature) : '' }}" 
                             class="w-full h-full object-contain" />
                    </div>
                    <button type="button" onclick="resetSignatureFileSelection()" class="mt-4 py-1.5 px-3 bg-rose-600 hover:bg-rose-700 text-white font-mono text-[10px] font-bold uppercase tracking-wider transition-colors">
                        Ganti TTD
                    </button>
                </div>
            </div>
        </div>

        <!-- Column 2 & 3: Headmaster Info -->
        <div class="lg:col-span-2 space-y-4">
            <div>
                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nama Kepala Sekolah</label>
                <input type="text" name="headmaster_name" value="{{ old('headmaster_name', $siteSetting->headmaster_name) }}" placeholder="Contoh: Drs. Ahmad Wijaya, M.Pd"
                       class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">NIP Kepala Sekolah</label>
                    <input type="text" name="headmaster_nip" value="{{ old('headmaster_nip', $siteSetting->headmaster_nip) }}" placeholder="Contoh: 195805011980031003"
                           class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                </div>

                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nomor Telepon Kepala Sekolah</label>
                    <input type="text" name="headmaster_phone" value="{{ old('headmaster_phone', $siteSetting->headmaster_phone) }}" placeholder="+62 812 3456 789"
                           class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                </div>
            </div>
        </div>
    </div>
</div>
