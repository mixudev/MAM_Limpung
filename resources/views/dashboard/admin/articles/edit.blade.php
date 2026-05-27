@extends('dashboard.layouts.main')

@section('content')
<!-- Quill Rich Text Editor Style -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Edit Artikel';
        }

        // Toggle published_at input based on status
        const statusSelect = document.getElementById('status');
        const publishTimeDiv = document.getElementById('publish_time_container');
        
        function togglePublishTime() {
            if (statusSelect.value === 'published') {
                publishTimeDiv.classList.remove('hidden');
            } else {
                publishTimeDiv.classList.add('hidden');
            }
        }

        statusSelect.addEventListener('change', togglePublishTime);
        togglePublishTime(); // Run once on load
    });
</script>

<div class="max-w-5xl space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Edit Artikel</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Perbarui isi artikel literasi sekolah di bawah ini. Pastikan konten tetap aman dan edukatif.</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center font-mono">
            KEMBALI
        </a>
    </div>

    <!-- Error Flash -->
    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/60 p-4 text-red-800 dark:text-red-400 text-xs font-semibold rounded-none">
            <p class="font-bold mb-2">Terjadi kesalahan input:</p>
            <ul class="list-disc list-inside space-y-1 font-mono">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm p-6">
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" id="articleForm" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Section: Title, Content, Excerpt (2 Cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Judul -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Judul Artikel <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul', $article->judul) }}" required placeholder="Contoh: Kemenangan Bersejarah Tim Basket Sekolah"
                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                    </div>

                    <!-- Ringkasan (Excerpt) -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Ringkasan Singkat (Maks 500 Karakter)</label>
                        <textarea name="ringkasan" rows="3" placeholder="Masukkan ringkasan artikel untuk deskripsi di halaman depan..."
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">{{ old('ringkasan', $article->ringkasan) }}</textarea>
                        <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1">Jika dikosongkan, ringkasan akan otomatis mengambil 150 karakter pertama dari konten.</p>
                    </div>

                    <!-- Konten (Rich Text Editor) -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Isi Artikel Lengkap <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="konten" id="konten-input" value="{{ old('konten', $article->konten) }}">
                        <div class="border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
                            <!-- Quill Editor Container -->
                            <div id="editor-container" class="h-96 text-slate-800 dark:text-zinc-200 font-sans" style="font-size: 14px;">
                                {!! old('konten', $article->konten) !!}
                            </div>
                        </div>
                    </div>

                    
                </div>

                <!-- Right Section: Category, Metadata, Uploads (1 Col) -->
                <div class="space-y-6 border-t lg:border-t-0 lg:border-l border-slate-100 dark:border-zinc-800 lg:pl-6">
                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2 font-mono">Kategori <span class="text-rose-500">*</span></label>
                        <select name="category_id" required
                            class="w-full px-3 py-2.5 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                            <option value="" disabled>Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1">Belum ada kategori yang cocok? Buat dahulu di menu kelola kategori.</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2 font-mono">Status Publikasi <span class="text-rose-500">*</span></label>
                        <select name="status" id="status" required
                            class="w-full px-3 py-2.5 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                            <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                            <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Terbitkan Langsung</option>
                            <option value="archived" {{ old('status', $article->status) === 'archived' ? 'selected' : '' }}>Simpan sebagai Arsip</option>
                        </select>
                    </div>

                    <!-- Tanggal Publikasi -->
                    <div id="publish_time_container" class="hidden">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Tanggal & Waktu Rilis</label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                        <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1">Kosongkan/biarkan default jika ingin artikel terbit langsung saat disimpan.</p>
                    </div>

                    <!-- Upload Thumbnail (Drag & Drop + Preview) -->
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Gambar Mini (Thumbnail) <span class="text-rose-500">*</span></label>
                        
                        <!-- Hidden inputs for preserving state across validation failures -->
                        <input type="hidden" name="temp_thumbnail" id="temp_thumbnail" value="{{ old('temp_thumbnail') }}">
                        <input type="hidden" name="temp_thumbnail_url" id="temp_thumbnail_url" value="{{ old('temp_thumbnail_url') }}">
                        
                        <!-- Actual hidden file input -->
                        <input type="file" id="thumbnail-file-input" name="thumbnail" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" />
                        
                        <!-- Drag & Drop Container -->
                        <div id="dropzone" class="relative group border-2 border-dashed border-slate-200 dark:border-zinc-700 hover:border-[#4f45b2] dark:hover:border-[#4f45b2] bg-slate-50/50 dark:bg-zinc-800/30 p-6 flex flex-col items-center justify-center text-center cursor-pointer transition-all duration-300 rounded-lg min-h-[180px]">
                            
                            <!-- State 1: Drop Prompt (No File Selected) -->
                            <div id="dropzone-prompt" class="space-y-3">
                                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mx-auto text-slate-500 dark:text-zinc-400 group-hover:scale-110 group-hover:bg-[#4f45b2]/10 group-hover:text-[#4f45b2] transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs font-bold text-slate-700 dark:text-zinc-300">Tarik & lepas gambar ke sini, atau <span class="text-[#4f45b2] underline hover:text-[#4f45b2]/85">telusuri</span></p>
                                    <p class="text-[10px] text-slate-400 dark:text-zinc-500">Format: JPG, JPEG, PNG, WEBP. Maks 2MB. Kosongkan jika tidak ingin mengubah thumbnail.</p>
                                </div>
                            </div>

                            <!-- State 2: Uploading/Progress Indicator -->
                            <div id="dropzone-loading" class="hidden space-y-3 w-full max-w-[200px]">
                                <div class="flex justify-between text-[10px] font-mono text-slate-500">
                                    <span>Mengunggah...</span>
                                    <span id="upload-progress-text">0%</span>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-zinc-700 rounded-full h-1.5 overflow-hidden">
                                    <div id="upload-progress-bar" class="bg-[#4f45b2] h-1.5 rounded-full transition-all duration-200" style="width: 0%"></div>
                                </div>
                            </div>

                            <!-- State 3: File Uploaded & Preview -->
                            <div id="dropzone-preview" class="hidden space-y-4 w-full">
                                <div class="relative max-w-xs mx-auto border border-slate-100 dark:border-zinc-800 shadow-lg rounded-md overflow-hidden bg-white dark:bg-zinc-900 group/preview">
                                    <img id="preview-image" src="" alt="Thumbnail Preview" class="w-full max-h-[160px] object-cover">
                                    
                                    <!-- Hover Overlay with action -->
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/preview:opacity-100 flex items-center justify-center transition-all duration-200">
                                        <button type="button" id="btn-remove-thumbnail" class="p-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full shadow-lg transition-all transform hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-[10px] font-mono text-slate-500 dark:text-zinc-400 break-all px-4" id="preview-filename">
                                    filename.jpg
                                </div>
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div id="dropzone-error" class="hidden text-[10px] font-mono font-bold text-rose-500 mt-1.5 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline-block">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            <span id="dropzone-error-text">Terjadi kesalahan saat mengunggah.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL SEO & PENCARIAN -->
                    <div class="mt-8 p-6 bg-slate-50/50 dark:bg-zinc-800/20 border border-slate-200 dark:border-zinc-800 rounded-lg space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-800 pb-4">
                            <div class="flex items-center gap-2">
                                <span class="p-1.5 bg-[#4f45b2]/10 rounded text-[#4f45b2]">
                                    <i class="fa-solid fa-chart-line text-sm"></i>
                                </span>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200">Optimasi SEO & Pencarian Google</h3>
                                    <p class="text-[10px] text-slate-400 dark:text-zinc-500">Sesuaikan tampilan artikel di halaman mesin pencari Google dan media sosial.</p>
                                </div>
                            </div>
                            <span class="py-0.5 px-2 bg-[#4f45b2]/10 text-[#4f45b2] dark:text-indigo-400 font-mono text-[9px] font-bold uppercase tracking-wider rounded">Custom SEO</span>
                        </div>

                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                            <!-- Left Column: Inputs (2/3 width) -->
                            <div class="xl:col-span-2 space-y-4">
                                <!-- Focus Keyword -->
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Kata Kunci Fokus (Focus Keyword)</label>
                                    <input type="text" name="seo_focus_keyword" id="seo_focus_keyword" value="{{ old('seo_focus_keyword', $article->seo->focus_keyword ?? '') }}" placeholder="Contoh: prestasi sekolah, ppdb 2026"
                                        class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                                </div>

                                <!-- Meta Title -->
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Judul SEO (Meta Title)</label>
                                        <span id="title-counter" class="text-[10px] font-mono text-slate-400 dark:text-zinc-500">0 / 60 karakter</span>
                                    </div>
                                    <input type="text" name="seo_meta_title" id="seo_meta_title" value="{{ old('seo_meta_title', $article->seo->meta_title ?? '') }}" placeholder="Kosongkan untuk menggunakan judul artikel utama"
                                        class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                                </div>

                                <!-- Meta Description -->
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <div class="flex items-center gap-1.5">
                                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Deskripsi SEO (Meta Description)</label>
                                            <button type="button" id="btn-generate-seo" class="py-0.5 px-2 bg-indigo-50 dark:bg-zinc-800 text-[#4f45b2] dark:text-indigo-400 hover:bg-[#4f45b2]/10 font-bold font-mono text-[9px] uppercase tracking-wider transition-all border border-indigo-100 dark:border-zinc-700">
                                                <i class="fa-solid fa-wand-magic-sparkles mr-1"></i> Auto-Generate
                                            </button>
                                        </div>
                                        <span id="desc-counter" class="text-[10px] font-mono text-slate-400 dark:text-zinc-500">0 / 160 karakter</span>
                                    </div>
                                    <textarea name="seo_meta_description" id="seo_meta_description" rows="3" placeholder="Masukkan ringkasan artikel yang memikat pembaca di hasil pencarian..."
                                        class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] resize-y">{{ old('seo_meta_description', $article->seo->meta_description ?? '') }}</textarea>
                                </div>

                                <!-- Meta Keywords -->
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Kata Kunci Tambahan (Keywords - Pisahkan dengan koma)</label>
                                    <input type="text" name="seo_meta_keywords" id="seo_meta_keywords" value="{{ old('seo_meta_keywords', $article->seo->meta_keywords ?? '') }}" placeholder="sekolah unggul, mam limpung, prestasi batang"
                                        class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                                </div>

                                <!-- Canonical URL -->
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Canonical URL (Kosongkan kecuali merujuk ke website eksternal)</label>
                                    <input type="text" name="seo_canonical_url" id="seo_canonical_url" value="{{ old('seo_canonical_url', $article->seo->canonical_url ?? '') }}" placeholder="https://website-asal.com/artikel-asli"
                                        class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                                </div>

                                <!-- Advanced SEO Robot Toggles -->
                                <div class="grid grid-cols-2 gap-4 pt-2">
                                    <div class="flex items-start gap-2.5">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="seo_is_indexed" id="seo_is_indexed" value="1" {{ old('seo_is_indexed', $article->seo->is_indexed ?? true) ? 'checked' : '' }}
                                                class="w-4 h-4 text-[#4f45b2] border-slate-300 rounded focus:ring-[#4f45b2]">
                                        </div>
                                        <div class="text-xs">
                                            <label for="seo_is_indexed" class="font-bold text-slate-700 dark:text-zinc-300">Izinkan Mesin Pencari (Index)</label>
                                            <p class="text-[10px] text-slate-400 dark:text-zinc-500">Jika dimatikan, halaman akan diberi tag 'noindex' agar tidak terdaftar di Google.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2.5">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="seo_is_followed" id="seo_is_followed" value="1" {{ old('seo_is_followed', $article->seo->is_followed ?? true) ? 'checked' : '' }}
                                                class="w-4 h-4 text-[#4f45b2] border-slate-300 rounded focus:ring-[#4f45b2]">
                                        </div>
                                        <div class="text-xs">
                                            <label for="seo_is_followed" class="font-bold text-slate-700 dark:text-zinc-300">Ikuti Link di Artikel (Follow)</label>
                                            <p class="text-[10px] text-slate-400 dark:text-zinc-500">Mengarahkan Google bot untuk merayap ('follow') seluruh link luar di dalam artikel.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Snippet Preview & SEO Checklist (1/3 width) -->
                            <div class="space-y-6">
                                <!-- Google Snippet Mockup Container -->
                                <div class="p-4 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm space-y-3">
                                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-850 pb-2">
                                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Google Search Preview</span>
                                        <div class="flex gap-2 text-[9px] font-mono">
                                            <button type="button" id="preview-tab-mobile" class="px-1.5 py-0.5 font-bold uppercase tracking-wider bg-slate-100 dark:bg-zinc-850 text-[#4f45b2] rounded">Mobile</button>
                                            <button type="button" id="preview-tab-desktop" class="px-1.5 py-0.5 font-bold uppercase tracking-wider text-slate-400 rounded">Desktop</button>
                                        </div>
                                    </div>

                                    <!-- LIVE SNIPPET MOCKUP -->
                                    <div class="space-y-2 pt-1 font-sans text-left">
                                        <!-- Breadcrumb/URL -->
                                        <div class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-zinc-400">
                                            <span class="w-4 h-4 bg-slate-100 dark:bg-zinc-850 rounded-full flex items-center justify-center text-[9px]"><i class="fa-solid fa-globe"></i></span>
                                            <div class="flex flex-col leading-none">
                                                <span class="text-[10px] font-medium text-slate-800 dark:text-zinc-300">mamlimpung.sch.id</span>
                                                <span id="snippet-url" class="text-[9px] text-slate-400 dark:text-zinc-500 truncate max-w-[200px]">/artikel/{{ $article->slug }}</span>
                                            </div>
                                        </div>

                                        <!-- Live Title -->
                                        <h4 id="snippet-title" class="text-sm font-medium text-blue-800 dark:text-blue-400 hover:underline cursor-pointer leading-tight line-clamp-2">
                                            {{ $article->seo->meta_title ?? $article->judul }}
                                        </h4>

                                        <!-- Live Snippet Text -->
                                        <p id="snippet-desc" class="text-xs text-slate-600 dark:text-zinc-400 leading-snug line-clamp-3">
                                            {{ $article->seo->meta_description ?? $article->ringkasan ?? 'Deskripsi pencarian artikel Google. Masukkan ringkasan menarik di kolom deskripsi SEO untuk menggoda pengunjung mencet artikel ini...' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- SEO Real-time Checklist -->
                                <div class="p-4 bg-slate-100/50 dark:bg-zinc-950/20 border border-slate-200 dark:border-zinc-800 space-y-3">
                                    <h4 class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 border-b border-slate-200 dark:border-zinc-850 pb-2 flex items-center justify-between">
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

            <!-- Submit Section -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('admin.articles.index') }}" class="py-2.5 px-5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all font-mono">
                    BATAL
                </a>
                <button type="submit" class="py-2.5 px-5 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider font-mono">
                    PERBARUI ARTIKEL
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Quill Rich Text Editor Script -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Quill editor with clean look
        const quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        // Set text alignment support in Quill
        quill.root.style.fontFamily = 'inherit';

        // Bind form submit to quill content translation
        const form = document.getElementById('articleForm');
        form.addEventListener('submit', function(e) {
            // Copy Editor HTML content to the hidden text input
            const htmlContent = quill.root.innerHTML;
            
            // If Quill has only empty tags, set input to empty
            if (htmlContent === '<p><br></p>' || quill.getText().trim() === '') {
                document.getElementById('konten-input').value = '';
            } else {
                document.getElementById('konten-input').value = htmlContent;
            }
        });

        // ─── DRAG & DROP THUMBNAIL UPLOAD ──────────────────────────────────────
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('thumbnail-file-input');
        const tempThumbnailInput = document.getElementById('temp_thumbnail');
        const tempThumbnailUrlInput = document.getElementById('temp_thumbnail_url');
        
        const promptState = document.getElementById('dropzone-prompt');
        const loadingState = document.getElementById('dropzone-loading');
        const previewState = document.getElementById('dropzone-preview');
        const previewImage = document.getElementById('preview-image');
        const previewFilename = document.getElementById('preview-filename');
        const btnRemove = document.getElementById('btn-remove-thumbnail');
        const errorState = document.getElementById('dropzone-error');
        const errorText = document.getElementById('dropzone-error-text');
        
        const progressBar = document.getElementById('upload-progress-bar');
        const progressText = document.getElementById('upload-progress-text');

        // Check state on page load
        if (tempThumbnailInput.value && tempThumbnailUrlInput.value) {
            // Recover from old input (validation error)
            showPreview(tempThumbnailUrlInput.value, tempThumbnailInput.value);
        } else if ("{{ $article->thumbnail }}") {
            // Show currently stored active database thumbnail
            showPreview("{{ $article->thumbnailUrl() }}", "Gambar Saat Ini");
        }

        // Trigger file browser on dropzone click
        dropzone.addEventListener('click', function(e) {
            if (!e.target.closest('#btn-remove-thumbnail')) {
                fileInput.click();
            }
        });

        // Handle file input selection
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFileUpload(this.files[0]);
            }
        });

        // Drag & Drop events
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-[#4f45b2]', 'bg-[#4f45b2]/5');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-[#4f45b2]', 'bg-[#4f45b2]/5');
            }, false);
        });

        // Handle drop event
        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                handleFileUpload(files[0]);
            }
        });

        function handleFileUpload(file) {
            // Validate client-side first
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                showError('Format berkas tidak valid. Harap pilih gambar JPG, JPEG, PNG, atau WEBP.');
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                showError('Ukuran gambar terlalu besar. Maksimum ukuran adalah 2MB.');
                return;
            }

            hideError();
            showLoading();

            const formData = new FormData();
            formData.append('thumbnail', file);
            
            // Get CSRF Token
            const csrfToken = document.querySelector('input[name="_token"]').value;

            // Use XMLHttpRequest to track progress
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("admin.articles.upload-temp") }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressText.textContent = percentComplete + '%';
                }
            });

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    tempThumbnailInput.value = response.path;
                    tempThumbnailUrlInput.value = response.url;
                    showPreview(response.url, file.name);
                } else {
                    let errMsg = 'Gagal mengunggah gambar ke server.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errMsg = response.message;
                        } else if (response.error) {
                            errMsg = response.error;
                        }
                    } catch (e) {}
                    showError(errMsg);
                    resetDropzone();
                }
            };

            xhr.onerror = function() {
                showError('Terjadi kesalahan jaringan saat mengunggah.');
                resetDropzone();
            };

            xhr.send(formData);
        }

        function showLoading() {
            promptState.classList.add('hidden');
            previewState.classList.add('hidden');
            loadingState.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
        }

        function showPreview(url, filename) {
            loadingState.classList.add('hidden');
            promptState.classList.add('hidden');
            previewState.classList.remove('hidden');
            previewImage.src = url;
            previewFilename.textContent = filename;
        }

        function resetDropzone() {
            loadingState.classList.add('hidden');
            previewState.classList.add('hidden');
            promptState.classList.remove('hidden');
            fileInput.value = '';
        }

        function showError(msg) {
            errorState.classList.remove('hidden');
            errorText.textContent = msg;
        }

        function hideError() {
            errorState.classList.add('hidden');
        }

        // Remove thumbnail handler
        btnRemove.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            tempThumbnailInput.value = '';
            tempThumbnailUrlInput.value = '';
            resetDropzone();
            hideError();
        });

        // ─── LIVE SEO & SEARCH ENGINE SNIPPET PREVIEW ─────────────────────────────────
        const judulInput = document.querySelector('input[name="judul"]');
        const seoTitleInput = document.getElementById('seo_meta_title');
        const seoDescInput = document.getElementById('seo_meta_description');
        const seoKeywordInput = document.getElementById('seo_focus_keyword');
        
        const snippetTitle = document.getElementById('snippet-title');
        const snippetDesc = document.getElementById('snippet-desc');
        const snippetUrl = document.getElementById('snippet-url');
        
        const titleCounter = document.getElementById('title-counter');
        const descCounter = document.getElementById('desc-counter');
        
        const btnGenerateSeo = document.getElementById('btn-generate-seo');
        
        // Checklist Elements
        const checkTitleLen = document.getElementById('check-title-len');
        const checkDescLen = document.getElementById('check-desc-len');
        const checkKeywordFilled = document.getElementById('check-keyword-filled');
        const checkKeywordInTitle = document.getElementById('check-keyword-in-title');
        const checkKeywordInDesc = document.getElementById('check-keyword-in-desc');
        const seoScore = document.getElementById('seo-score');

        // Helper: Convert Title to Slug
        function generateSlug(text) {
            return text.toString().toLowerCase().trim()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/&/g, '-and-')         // Replace & with 'and'
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-');        // Replace multiple - with single -
        }

        // Live preview updates
        function updateSeoPreview() {
            let titleVal = seoTitleInput.value.trim() || judulInput.value.trim() || 'Judul Artikel Utama Tampil di Sini...';
            let descVal = seoDescInput.value.trim() || 'Deskripsi pencarian artikel Google. Masukkan ringkasan menarik di kolom deskripsi SEO untuk menggoda pengunjung mencet artikel ini...';
            let slugVal = generateSlug(judulInput.value.trim() || 'contoh-slug');
            
            snippetTitle.textContent = titleVal;
            snippetDesc.textContent = descVal;
            snippetUrl.textContent = '/artikel/' + slugVal;
            
            // Character counters
            titleCounter.textContent = `${seoTitleInput.value.length} / 60 karakter`;
            descCounter.textContent = `${seoDescInput.value.length} / 160 karakter`;
            
            if (seoTitleInput.value.length > 60) {
                titleCounter.classList.add('text-rose-500', 'font-bold');
            } else {
                titleCounter.classList.remove('text-rose-500', 'font-bold');
            }
            
            if (seoDescInput.value.length > 160) {
                descCounter.classList.add('text-rose-500', 'font-bold');
            } else {
                descCounter.classList.remove('text-rose-500', 'font-bold');
            }
            
            runSeoAnalysis();
        }

        // SEO Real-time Checker
        function runSeoAnalysis() {
            let title = seoTitleInput.value.trim() || judulInput.value.trim() || '';
            let desc = seoDescInput.value.trim() || '';
            let keyword = seoKeywordInput.value.trim().toLowerCase();
            
            let passedCount = 0;
            let totalChecks = 5;

            // 1. Title Length
            let titleLen = title.length;
            if (titleLen >= 40 && titleLen <= 60) {
                setCheckPassed(checkTitleLen, true);
                passedCount++;
            } else {
                setCheckPassed(checkTitleLen, false, 'Panjang judul ideal: 40-60 karakter (' + titleLen + ')');
            }

            // 2. Description Length
            let descLen = desc.length;
            if (descLen >= 110 && descLen <= 160) {
                setCheckPassed(checkDescLen, true);
                passedCount++;
            } else {
                setCheckPassed(checkDescLen, false, 'Panjang deskripsi ideal: 110-160 karakter (' + descLen + ')');
            }

            // 3. Keyword Filled
            if (keyword.length > 0) {
                setCheckPassed(checkKeywordFilled, true);
                passedCount++;
                
                // 4. Keyword in Title
                if (title.toLowerCase().includes(keyword)) {
                    setCheckPassed(checkKeywordInTitle, true);
                    passedCount++;
                } else {
                    setCheckPassed(checkKeywordInTitle, false);
                }

                // 5. Keyword in Description
                if (desc.toLowerCase().includes(keyword)) {
                    setCheckPassed(checkKeywordInDesc, true);
                    passedCount++;
                } else {
                    setCheckPassed(checkKeywordInDesc, false);
                }
            } else {
                setCheckPassed(checkKeywordFilled, false);
                setCheckPassed(checkKeywordInTitle, false);
                setCheckPassed(checkKeywordInDesc, false);
            }

            // Update score
            let scorePercent = Math.round((passedCount / totalChecks) * 100);
            seoScore.textContent = `${scorePercent}% Score`;
            
            if (scorePercent >= 80) {
                seoScore.className = 'font-bold text-emerald-500';
            } else if (scorePercent >= 50) {
                seoScore.className = 'font-bold text-amber-500';
            } else {
                seoScore.className = 'font-bold text-rose-500';
            }
        }

        function setCheckPassed(element, isPassed, customText = null) {
            const icon = element.querySelector('i');
            const label = element.querySelector('span');
            if (isPassed) {
                element.className = "flex items-start gap-2 text-emerald-600 dark:text-emerald-500 font-medium";
                icon.className = "fa-solid fa-circle-check text-emerald-500 mt-0.5";
            } else {
                element.className = "flex items-start gap-2 text-slate-500 dark:text-zinc-450";
                icon.className = "fa-solid fa-circle-xmark text-rose-500 mt-0.5";
            }
            if (customText) {
                label.textContent = customText;
            }
        }

        // Auto-Generate Meta Description from Quill Content
        btnGenerateSeo.addEventListener('click', function() {
            // Get content from Quill editor
            let cleanText = quill.getText().trim();
            
            // Clean up double spaces or newlines
            cleanText = cleanText.replace(/\s+/g, ' ');
            
            if (cleanText.length > 0) {
                // Take first 155 characters and add '...' if longer
                let excerpt = cleanText.substring(0, 155);
                if (cleanText.length > 155) {
                    excerpt += '...';
                }
                seoDescInput.value = excerpt;
                updateSeoPreview();
            } else {
                alert('Tulis isi artikel terlebih dahulu untuk men-generate deskripsi otomatis.');
            }
        });

        // Event listeners
        [judulInput, seoTitleInput, seoDescInput, seoKeywordInput].forEach(el => {
            if (el) {
                el.addEventListener('input', updateSeoPreview);
            }
        });

        // Toggle Preview Mobile vs Desktop
        const btnMobile = document.getElementById('preview-tab-mobile');
        const btnDesktop = document.getElementById('preview-tab-desktop');
        
        btnMobile.addEventListener('click', function() {
            btnMobile.classList.add('bg-slate-100', 'dark:bg-zinc-850', 'text-[#4f45b2]');
            btnMobile.classList.remove('text-slate-400');
            btnDesktop.classList.remove('bg-slate-100', 'dark:bg-zinc-850', 'text-[#4f45b2]');
            btnDesktop.classList.add('text-slate-400');
            
            snippetTitle.classList.remove('text-base');
            snippetTitle.classList.add('text-sm');
        });
        
        btnDesktop.addEventListener('click', function() {
            btnDesktop.classList.add('bg-slate-100', 'dark:bg-zinc-850', 'text-[#4f45b2]');
            btnDesktop.classList.remove('text-slate-400');
            btnMobile.classList.remove('bg-slate-100', 'dark:bg-zinc-850', 'text-[#4f45b2]');
            btnMobile.classList.add('text-slate-400');
            
            snippetTitle.classList.remove('text-sm');
            snippetTitle.classList.add('text-base');
        });

        // Initial trigger
        updateSeoPreview();
    });
</script>
@endsection
