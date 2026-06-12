<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 ">
    <!-- Left Section: Title, Content, Excerpt (2 Cols) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Judul -->
        <div>
            <label
                class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Judul
                Artikel <span class="text-rose-500">*</span></label>
            <input type="text" name="judul" value="{{ old('judul') }}" required
                placeholder="Contoh: Kemenangan Bersejarah Tim Basket Sekolah"
                class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
        </div>

        <!-- Ringkasan (Excerpt) -->
        <div>
            <label
                class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Ringkasan
                Singkat (Maks 500 Karakter)</label>
            <textarea name="ringkasan" rows="3" placeholder="Masukkan ringkasan artikel untuk deskripsi di halaman depan..."
                class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">{{ old('ringkasan') }}</textarea>
            <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1">Jika dikosongkan, ringkasan akan otomatis
                mengambil 150 karakter pertama dari konten.</p>
        </div>
        <style>
            /* Tinggi area ketik Quill */
            #editor-container .ql-editor {
                min-height: 220px;
                max-height: 650px;
                overflow-y: auto;
                font-size: 14px;
                line-height: 1.75;
            }

            /* Biar toolbar & editor nyatu rapi */
            #editor-container .ql-toolbar {
                border-color: rgb(226 232 240);
                /* slate-200 */
            }

            .dark #editor-container .ql-toolbar,
            .dark #editor-container .ql-container {
                border-color: rgb(63 63 70);
                /* zinc-700 */
            }
        </style>
        <!-- Konten (Rich Text Editor) -->
        <div>
            <label
                class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Isi
                Artikel Lengkap <span class="text-rose-500">*</span></label>
            <input type="hidden" name="konten" id="konten-input" value="{{ old('konten') }}">
            <div class="border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
                <!-- Quill Editor Container -->
                <div id="editor-container" class=" text-slate-800 dark:text-zinc-200 font-sans"
                    style="font-size: 14px;">
                    {!! old('konten') !!}
                </div>
            </div>
        </div>


    </div>

    <!-- Right Section: Category, Metadata, Uploads (1 Col) -->
    <div class="space-y-6 border-t lg:border-t-0 lg:border-l border-slate-100 dark:border-zinc-800 lg:pl-6">
        <!-- Kategori -->
        <div>
            <label
                class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2 font-mono">Kategori
                <span class="text-rose-500">*</span></label>
            <select name="category_id" required
                class="w-full px-3 py-2.5 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                <option value="" disabled selected>Pilih Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}</option>
                @endforeach
            </select>
            <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1">Belum ada kategori yang cocok? Buat dahulu di
                menu kelola kategori.</p>
        </div>

        <!-- Status -->
        <div>
            <label
                class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2 font-mono">Status
                Publikasi <span class="text-rose-500">*</span></label>
            <select name="status" id="status" required
                class="w-full px-3 py-2.5 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                @if (Auth::user()->hasRole('siswa'))
                <option 
                    value="pending" 
                    {{ old('status') === 'pending' ? 'selected' : '' }}> Publish
                </option>
                <option 
                    value="draft" 
                    {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}> Draft
                </option>
                @endif
                
                @if (!Auth::user()->hasRole('siswa'))
                    <option 
                        value="publish_now" 
                        {{ old('status') === 'publish_now' ? 'selected' : '' }}> Terbitkan
                        Sekarang
                    </option>
                    <option 
                        value="publish_custom" 
                        {{ old('status') === 'publish_custom' ? 'selected' : '' }}>
                        Jadwalkan Publikasi
                    </option>
                    <option 
                        value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}> Simpan sebagai
                        Arsip
                    </option>
                @endif
                
            </select>
            <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1">
                <span id="status-hint-draft" class="{{ old('status', 'draft') === 'draft' ? '' : 'hidden' }}">Artikel
                    tidak akan tampil di website.</span>
                <span id="status-hint-pending" class="{{ old('status') === 'pending' ? '' : 'hidden' }}">Menunggu
                    persetujuan dari Admin/Super Admin.</span>
                <span id="status-hint-publish_now"
                    class="{{ old('status') === 'publish_now' ? '' : 'hidden' }}">Artikel langsung tayang di website
                    saat disimpan.</span>
                <span id="status-hint-publish_custom"
                    class="{{ old('status') === 'publish_custom' ? '' : 'hidden' }}">Tentukan tanggal dan jam artikel
                    mulai tayang.</span>
                <span id="status-hint-archived" class="{{ old('status') === 'archived' ? '' : 'hidden' }}">Artikel
                    disembunyikan dan diarsipkan.</span>
            </p>
        </div>

        <!-- Jadwal Publikasi Custom -->
        <div id="publish_time_container" class="{{ old('status') === 'publish_custom' ? '' : 'hidden' }}">
            <label
                class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">
                <i class="fa-regular fa-calendar-clock mr-1"></i> Tanggal & Waktu Rilis
            </label>
            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
            <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1">Artikel akan otomatis tayang pada waktu yang
                dipilih.</p>
        </div>

        <!-- Upload Thumbnail (Drag & Drop + Preview) -->
        <div>
            <label
                class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Gambar
                Mini (Thumbnail) <span class="text-rose-500">*</span></label>

            <!-- Hidden inputs for preserving state across validation failures -->
            <input type="hidden" name="temp_thumbnail" id="temp_thumbnail" value="{{ old('temp_thumbnail') }}">
            <input type="hidden" name="temp_thumbnail_url" id="temp_thumbnail_url"
                value="{{ old('temp_thumbnail_url') }}">

            <!-- Actual hidden file input -->
            <input type="file" id="thumbnail-file-input" name="thumbnail"
                accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" />

            <!-- Drag & Drop Container -->
            <div id="dropzone"
                class="relative group border-2 border-dashed border-slate-200 dark:border-zinc-700 hover:border-[#4f45b2] dark:hover:border-[#4f45b2] bg-slate-50/50 dark:bg-zinc-800/30 p-6 flex flex-col items-center justify-center text-center cursor-pointer transition-all duration-300 rounded-lg min-h-[180px]">

                <!-- State 1: Drop Prompt (No File Selected) -->
                <div id="dropzone-prompt" class="space-y-3">
                    <div
                        class="w-12 h-12 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mx-auto text-slate-500 dark:text-zinc-400 group-hover:scale-110 group-hover:bg-[#4f45b2]/10 group-hover:text-[#4f45b2] transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-700 dark:text-zinc-300">Tarik & lepas gambar ke sini,
                            atau <span class="text-[#4f45b2] underline hover:text-[#4f45b2]/85">telusuri</span></p>
                        <p class="text-[10px] text-slate-400 dark:text-zinc-500">Format: JPG, JPEG, PNG, WEBP. Maks 2MB.
                        </p>
                    </div>
                </div>

                <!-- State 2: Uploading/Progress Indicator -->
                <div id="dropzone-loading" class="hidden space-y-3 w-full max-w-[200px]">
                    <div class="flex justify-between text-[10px] font-mono text-slate-500">
                        <span>Mengunggah...</span>
                        <span id="upload-progress-text">0%</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-zinc-700 rounded-full h-1.5 overflow-hidden">
                        <div id="upload-progress-bar"
                            class="bg-[#4f45b2] h-1.5 rounded-full transition-all duration-200" style="width: 0%">
                        </div>
                    </div>
                </div>

                <!-- State 3: File Uploaded & Preview -->
                <div id="dropzone-preview" class="hidden space-y-4 w-full">
                    <div
                        class="relative max-w-xs mx-auto border border-slate-100 dark:border-zinc-800 shadow-lg rounded-md overflow-hidden bg-white dark:bg-zinc-900 group/preview">
                        <img id="preview-image" src="" alt="Thumbnail Preview"
                            class="w-full max-h-[160px] object-cover">

                        <!-- Hover Overlay with action -->
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover/preview:opacity-100 flex items-center justify-center transition-all duration-200">
                            <button type="button" id="btn-remove-thumbnail"
                                class="p-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full shadow-lg transition-all transform hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="text-[10px] font-mono text-slate-500 dark:text-zinc-400 break-all px-4"
                        id="preview-filename">
                        filename.jpg
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div id="dropzone-error"
                class="hidden text-[10px] font-mono font-bold text-rose-500 mt-1.5 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    class="w-4 h-4 inline-block">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                        clip-rule="evenodd" />
                </svg>
                <span id="dropzone-error-text">Terjadi kesalahan saat mengunggah.</span>
            </div>
        </div>
    </div>
</div>
