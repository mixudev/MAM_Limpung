@extends('mobile_apps.layouts.apps')

@section('content')
    <div class="px-5 pt-4">
        <!-- Header & Back Button -->
        <div class="flex items-center gap-3 mb-5">
            <a href="{{ route('apps.artikel.show', $article) }}" class="w-8 h-8 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-600 shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-sora font-bold text-slate-800 text-base">Ubah Artikel</h2>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-slate-100/80 shadow-xs rounded-2xl p-5 mb-6" x-data="articleUpload()">
            <form id="article-edit-form" action="{{ route('apps.artikel.update', $article) }}" method="POST" enctype="multipart/form-data" @submit="handleSubmit($event)">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Judul Artikel</label>
                        <input type="text" name="judul" value="{{ old('judul', $article->judul) }}" required placeholder="Masukkan judul menarik..."
                               class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">
                        @error('judul')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Kategori</label>
                            <select name="category_id" required
                                    class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Ringkasan Singkat</label>
                        <textarea name="ringkasan" rows="2" required placeholder="Tulis ringkasan max 500 karakter..."
                                  class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">{{ old('ringkasan', $article->ringkasan) }}</textarea>
                        @error('ringkasan')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rich Text Editor Style Override -->
                    <style>
                        .editor-content ul {
                            list-style-type: disc !important;
                            padding-left: 1.25rem !important;
                            margin-top: 0.5rem !important;
                            margin-bottom: 0.5rem !important;
                        }
                        .editor-content ol {
                            list-style-type: decimal !important;
                            padding-left: 1.25rem !important;
                            margin-top: 0.5rem !important;
                            margin-bottom: 0.5rem !important;
                        }
                        .editor-content p {
                            margin-bottom: 0.75rem !important;
                        }
                        .editor-content:empty:before {
                            content: attr(placeholder);
                            color: #94a3b8;
                            font-weight: 500;
                        }
                    </style>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Konten Artikel</label>
                        
                        <!-- Rich Text Editor Box -->
                        <div x-data="richTextEditor()" class="border border-slate-200/80 rounded-2xl overflow-hidden bg-slate-50 shadow-inner">
                            <!-- Toolbar -->
                            <div class="flex items-center gap-1.5 p-2 bg-slate-100/70 border-b border-slate-200/80 flex-wrap">
                                <button type="button" @click="exec('bold')" 
                                        :class="activeFormats.bold ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs border transition-all cursor-pointer" title="Tebal (Bold)">
                                    B
                                </button>
                                <button type="button" @click="exec('italic')"
                                        :class="activeFormats.italic ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center italic text-xs border transition-all cursor-pointer" title="Miring (Italic)">
                                    I
                                </button>
                                <button type="button" @click="exec('underline')"
                                        :class="activeFormats.underline ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center underline text-xs border transition-all cursor-pointer" title="Garis Bawah (Underline)">
                                    U
                                </button>
                                <div class="w-px h-5 bg-slate-200 mx-0.5"></div>
                                <button type="button" @click="exec('insertUnorderedList')"
                                        :class="activeFormats.listUl ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center border transition-all cursor-pointer" title="Daftar Bulat (Unordered List)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </button>
                                <button type="button" @click="exec('insertOrderedList')"
                                        :class="activeFormats.listOl ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center border transition-all cursor-pointer" title="Daftar Angka (Ordered List)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </button>
                                <div class="w-px h-5 bg-slate-200 mx-0.5"></div>
                                <button type="button" @click="exec('removeFormat')" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-all cursor-pointer" title="Hapus Format">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Editor Area -->
                            <div x-ref="editor"
                                 contenteditable="true"
                                 @input="updateContent()"
                                 @blur="updateContent()"
                                 @keyup="checkActiveFormats()"
                                 @click="checkActiveFormats()"
                                 class="w-full bg-slate-50/50 px-4 py-3 text-xs text-slate-800 min-h-[220px] focus:outline-none transition-all font-medium overflow-y-auto outline-none editor-content"
                                 placeholder="Tulis isi tulisan lengkap Anda di sini secara detail...">
                                {!! old('konten', $article->konten) !!}
                            </div>

                            <!-- Hidden Form Input -->
                            <input type="hidden" name="konten" :value="content">
                        </div>
                        @error('konten')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Single Image Interactive Upload Component -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Thumbnail Gambar</label>
                            <span class="text-[9px] text-slate-400 font-semibold">(Kosongkan jika tidak diganti)</span>
                        </div>
                        
                        <!-- Dropzone (visible only if no file selected/loading) -->
                        <div class="relative group" x-show="!selectedFile"
                             @dragover.prevent="dragOver = true"
                             @dragleave.prevent="dragOver = false"
                             @drop.prevent="handleDrop($event)">
                            
                            <input type="file" name="thumbnail" accept="image/*" id="thumbnail-input" x-ref="fileInput"
                                   @change="handleSelect($event)"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div class="border-2 border-dashed rounded-2xl p-6 text-center transition-all duration-300"
                                 :class="dragOver ? 'border-primary-500 bg-primary-50/30' : 'border-slate-200 bg-slate-50 group-hover:border-primary-400'">
                                
                                <svg class="w-8 h-8 text-slate-400 mx-auto mb-2 transition-transform duration-300"
                                     :class="dragOver ? 'scale-110 text-primary-500' : 'group-hover:text-primary-500'" 
                                     fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                
                                <p class="text-xs text-slate-500 font-bold" x-text="dragOver ? 'Lepaskan foto di sini...' : 'Pilih foto thumbnail baru'"></p>
                                <p class="text-[10px] text-slate-400 mt-1">Maksimal 2MB</p>
                            </div>
                        </div>
                        @error('thumbnail')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror

                        <!-- Preview & Upload Progress State -->
                        <div class="mt-2" x-show="selectedFile" style="display: none;">
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3 relative overflow-hidden group shadow-xs">
                                <!-- Image Preview container -->
                                <div class="aspect-[16/9] w-full rounded-xl overflow-hidden bg-slate-200 relative">
                                    <template x-if="selectedFile && selectedFile.status === 'ready'">
                                        <img :src="selectedFile.dataUrl" class="w-full h-full object-cover">
                                    </template>
                                    
                                    <!-- Shimmer/Loading state when progress < 100 -->
                                    <template x-if="selectedFile && selectedFile.status === 'loading'">
                                        <div class="w-full h-full bg-slate-200 flex flex-col items-center justify-center p-3">
                                            <!-- Loading spinner -->
                                            <svg class="animate-spin h-6 w-6 text-primary-500 mb-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            <span class="text-xs font-bold text-slate-500">Membaca file...</span>
                                        </div>
                                    </template>

                                    <!-- Delete Button overlay -->
                                    <button type="button" @click="removeFile()" x-show="selectedFile && selectedFile.status === 'ready'"
                                            class="absolute top-3 right-3 w-8 h-8 bg-rose-500 hover:bg-rose-600 text-white rounded-full flex items-center justify-center shadow-md cursor-pointer transition-transform active:scale-90">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Progress Bar (Upload Animation) -->
                                <div class="mt-3" x-show="selectedFile && selectedFile.status === 'loading'">
                                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-primary-500 h-1.5 transition-all duration-100 ease-out" :style="'width: ' + selectedFile.progress + '%'"></div>
                                    </div>
                                    <div class="flex justify-between text-[8px] text-slate-400 mt-1 font-bold">
                                        <span x-text="formatSize(selectedFile.size)"></span>
                                        <span x-text="selectedFile.progress + '%'"></span>
                                    </div>
                                </div>

                                <div class="mt-3 px-1 flex justify-between items-center" x-show="selectedFile && selectedFile.status === 'ready'">
                                    <div class="truncate pr-4 flex-1">
                                        <p class="text-xs text-slate-700 font-bold truncate" :title="selectedFile.name" x-text="selectedFile.name"></p>
                                        <p class="text-[10px] text-slate-400 font-semibold" x-text="formatSize(selectedFile.size)"></p>
                                    </div>
                                    <button type="button" @click="triggerSelect()" class="shrink-0 text-[10px] font-bold text-primary-600 hover:text-primary-700 bg-primary-50 hover:bg-primary-100/50 px-3 py-1.5 rounded-lg transition-colors cursor-pointer">
                                        Ganti Foto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="article-submit-btn" :disabled="submitting"
                            class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-md active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer mt-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-show="!submitting">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" x-show="submitting" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span x-text="submitting ? 'Menyimpan & Mengunggah...' : 'Simpan Perubahan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function articleUpload() {
            return {
                selectedFile: @if($article->thumbnail) {
                    name: 'Foto Thumbnail Saat Ini',
                    size: 0,
                    dataUrl: '{{ Storage::url($article->thumbnail) }}',
                    progress: 100,
                    status: 'ready',
                    isExisting: true
                } @else null @endif,
                dragOver: false,
                submitting: false,

                triggerSelect() {
                    const inputEl = document.getElementById('thumbnail-input');
                    if (inputEl) {
                        inputEl.click();
                    }
                },

                handleDrop(e) {
                    this.dragOver = false;
                    const files = Array.from(e.dataTransfer.files);
                    if (files.length > 0) {
                        this.processFile(files[0]);
                    }
                },

                handleSelect(e) {
                    const files = Array.from(e.target.files);
                    if (files.length > 0) {
                        this.processFile(files[0]);
                    }
                },

                processFile(file) {
                    // Max 2MB limit
                    if (file.size > 2 * 1024 * 1024) {
                        if (window.MobilePopup) {
                            window.MobilePopup.error({
                                title: 'Thumbnail Terlalu Besar',
                                description: 'Ukuran foto thumbnail melebihi 2MB. Silakan pilih foto yang lebih kecil.',
                                confirmText: 'Tutup'
                            });
                        } else {
                            alert('Thumbnail melebihi batas 2MB.');
                        }
                        this.removeFile();
                        return;
                    }

                    // Validate file type
                    if (!file.type.match('image.*')) {
                        alert('File harus berupa gambar.');
                        return;
                    }

                    this.selectedFile = {
                        name: file.name,
                        size: file.size,
                        dataUrl: '',
                        progress: 0,
                        status: 'loading'
                    };

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selectedFile.dataUrl = e.target.result;
                        this.selectedFile.progress = 100;
                        this.selectedFile.status = 'ready';
                    };
                    reader.readAsDataURL(file);
                },

                removeFile() {
                    this.selectedFile = null;
                    const inputEl = document.getElementById('thumbnail-input');
                    if (inputEl) {
                        inputEl.value = '';
                    }
                },

                formatSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    if (bytes === 0 && this.selectedFile.isExisting) return 'Foto Tersimpan';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB'];
                    const i = Math.log(bytes) > 0 ? Math.floor(Math.log(bytes) / Math.log(k)) : 0;
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                },

                handleSubmit(e) {
                    this.submitting = true;
                    if (window.showGlobalLoader) {
                        window.showGlobalLoader('Menyimpan Perubahan...', 'Sedang memperbarui konten artikel Anda');
                    }
                }
            }
        }

        function richTextEditor() {
            return {
                content: '',
                activeFormats: {
                    bold: false,
                    italic: false,
                    underline: false,
                    listUl: false,
                    listOl: false
                },
                init() {
                    this.updateContent();
                    this.checkActiveFormats();
                },
                exec(cmd) {
                    document.execCommand(cmd, false, null);
                    this.updateContent();
                    this.checkActiveFormats();
                    this.$refs.editor.focus();
                },
                updateContent() {
                    let html = this.$refs.editor.innerHTML;
                    if (html === '<p><br></p>' || html === '<br>' || this.$refs.editor.textContent.trim() === '') {
                        this.content = '';
                    } else {
                        this.content = html;
                    }
                },
                checkActiveFormats() {
                    this.activeFormats.bold = document.queryCommandState('bold');
                    this.activeFormats.italic = document.queryCommandState('italic');
                    this.activeFormats.underline = document.queryCommandState('underline');
                    this.activeFormats.listUl = document.queryCommandState('insertUnorderedList');
                    this.activeFormats.listOl = document.queryCommandState('insertOrderedList');
                }
            }
        }
    </script>
@endsection
