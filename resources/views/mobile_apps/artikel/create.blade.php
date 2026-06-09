@extends('mobile_apps.layouts.apps')

@section('content')
    <div class="px-5 pt-4">
        <!-- Header & Back Button -->
        <div class="flex items-center gap-3 mb-5">
            <a href="{{ route('apps.artikel') }}" class="w-8 h-8 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-600 shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-sora font-bold text-slate-800 text-base">Tulis Artikel Baru</h2>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-slate-100/80 shadow-xs rounded-2xl p-5 mb-6" x-data="articleUpload()">
            <form id="article-create-form" action="{{ route('apps.artikel.store') }}" method="POST" enctype="multipart/form-data" @submit="handleSubmit($event)">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Judul Artikel</label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Masukkan judul artikel yang menarik..."
                               class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">
                        @error('judul')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Kategori</label>
                        <select name="category_id" required
                                class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Ringkasan Singkat</label>
                        <textarea name="ringkasan" rows="2" required placeholder="Tulis ringkasan singkat dalam 1-2 kalimat (max 500 karakter)..."
                                  class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">{{ old('ringkasan') }}</textarea>
                        @error('ringkasan')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

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

                        /* Fullscreen Overlay */
                        .fs-editor-overlay {
                            position: fixed;
                            inset: 0;
                            z-index: 9999;
                            background: #ffffff;
                            display: flex;
                            flex-direction: column;
                            transform: translateY(100%);
                            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                        }
                        .fs-editor-overlay.fs-active {
                            transform: translateY(0);
                        }
                        .fs-editor-body {
                            flex: 1;
                            overflow-y: auto;
                            padding: 16px;
                            /* Beri ruang bawah agar konten tidak tertutup toolbar */
                            padding-bottom: 80px;
                            font-size: 14px;
                            line-height: 1.8;
                            color: #1e293b;
                            -webkit-overflow-scrolling: touch;
                            outline: none;
                        }
                        .fs-editor-body:empty:before {
                            content: attr(placeholder);
                            color: #94a3b8;
                            font-weight: 500;
                            pointer-events: none;
                        }

                        /* Toolbar bawah — naik otomatis saat keyboard muncul */
                        .fs-toolbar-bottom {
                            display: flex;
                            align-items: center;
                            gap: 4px;
                            padding: 8px 10px;
                            background: #f8fafc;
                            border-top: 1px solid #e2e8f0;
                            flex-wrap: nowrap;
                            overflow-x: auto;
                            /* Fixed positioning agar toolbar selalu menempel di atas keyboard */
                            position: fixed;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            z-index: 10000;
                            /* Transisi halus saat keyboard naik/turun */
                            transition: bottom 0.05s linear;
                        }
                        .fs-toolbar-bottom::-webkit-scrollbar { display: none; }

                        body.fs-open {
                            overflow: hidden;
                        }
                    </style>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Konten Artikel</label>

                        <div x-data="richTextEditor()" class="border border-slate-200/80 rounded-2xl overflow-hidden bg-slate-50 shadow-inner">

                            <!-- Toolbar Normal — satu baris, tanpa tombol silang -->
                            <div class="flex items-center gap-1.5 p-2 bg-slate-100/70 border-b border-slate-200/80 overflow-x-auto">
                                <button type="button" @click="exec('bold')"
                                        :class="activeFormats.bold ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                        class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center font-bold text-xs border transition-all cursor-pointer" title="Tebal (Bold)">B</button>
                                <button type="button" @click="exec('italic')"
                                        :class="activeFormats.italic ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                        class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center italic text-xs border transition-all cursor-pointer" title="Miring (Italic)">I</button>
                                <button type="button" @click="exec('underline')"
                                        :class="activeFormats.underline ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                        class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center underline text-xs border transition-all cursor-pointer" title="Garis Bawah (Underline)">U</button>

                                <div class="w-px h-5 bg-slate-200 mx-0.5 shrink-0"></div>

                                <button type="button" @click="exec('insertUnorderedList')"
                                        :class="activeFormats.listUl ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                        class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center border transition-all cursor-pointer" title="Daftar Bulat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                </button>
                                <button type="button" @click="exec('insertOrderedList')"
                                        :class="activeFormats.listOl ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                        class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center border transition-all cursor-pointer" title="Daftar Angka">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                </button>

                                <div class="w-px h-5 bg-slate-200 mx-0.5 shrink-0"></div>

                                <!-- Tombol Layar Penuh — satu baris dengan tombol lain -->
                                <button type="button" @click="openFullscreen()"
                                        class="shrink-0 flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 border border-indigo-200 text-[10px] font-bold transition-all cursor-pointer whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                                    </svg>
                                    Layar Penuh
                                </button>
                            </div>

                            <!-- Editor Area Normal -->
                            <div x-ref="editor"
                                 contenteditable="true"
                                 @input="updateContent()"
                                 @blur="updateContent()"
                                 @keyup="checkActiveFormats()"
                                 @click="checkActiveFormats()"
                                 class="w-full bg-slate-50/50 px-4 py-3 text-xs text-slate-800 min-h-[180px] focus:outline-none transition-all font-medium overflow-y-auto outline-none editor-content"
                                 placeholder="Tulis isi artikel di sini... (ketuk 'Layar Penuh' untuk pengalaman menulis lebih nyaman)">
                                {!! old('konten') !!}
                            </div>

                            <input type="hidden" name="konten" :value="content">

                            <!-- FULLSCREEN OVERLAY -->
                            <div id="fs-editor-overlay"
                                 class="fs-editor-overlay"
                                 x-ref="fsOverlay">

                                <!-- Header -->
                                <div class="flex items-center gap-3 px-4 bg-white border-b border-slate-200 shrink-0"
                                     style="padding-top: max(12px, env(safe-area-inset-top, 12px)); padding-bottom: 12px;">
                                    <button type="button" @click="closeFullscreen()"
                                            class="w-9 h-9 bg-slate-100 hover:bg-slate-200 rounded-xl flex items-center justify-center transition-colors cursor-pointer shrink-0">
                                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-slate-800 text-sm leading-tight">Konten Artikel</p>
                                        <p class="text-[10px] text-slate-400 font-semibold" x-text="wordCount + ' kata'"></p>
                                    </div>
                                    <button type="button" @click="closeFullscreen()"
                                            class="shrink-0 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all cursor-pointer">
                                        Selesai
                                    </button>
                                </div>

                                <!-- Area Tulis -->
                                <div x-ref="fsEditor"
                                     contenteditable="true"
                                     @input="syncFromFullscreen()"
                                     @keyup="checkActiveFormats()"
                                     @mouseup="checkActiveFormats()"
                                     class="fs-editor-body editor-content"
                                     placeholder="Tulis isi artikel di sini secara lengkap dan detail...">
                                </div>

                                <!-- Toolbar Bawah — naik bersama keyboard -->
                                <div class="fs-toolbar-bottom" id="fs-toolbar-bottom">
                                    <button type="button" @click="exec('bold')"
                                            :class="activeFormats.bold ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                            class="w-9 h-9 shrink-0 rounded-xl flex items-center justify-center font-bold text-sm border transition-all cursor-pointer" title="Tebal">B</button>
                                    <button type="button" @click="exec('italic')"
                                            :class="activeFormats.italic ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                            class="w-9 h-9 shrink-0 rounded-xl flex items-center justify-center italic text-sm border transition-all cursor-pointer" title="Miring">I</button>
                                    <button type="button" @click="exec('underline')"
                                            :class="activeFormats.underline ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                            class="w-9 h-9 shrink-0 rounded-xl flex items-center justify-center underline text-sm border transition-all cursor-pointer" title="Garis Bawah">U</button>

                                    <div class="w-px h-6 bg-slate-200 mx-1 shrink-0"></div>

                                    <button type="button" @click="exec('insertUnorderedList')"
                                            :class="activeFormats.listUl ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                            class="w-9 h-9 shrink-0 rounded-xl flex items-center justify-center border transition-all cursor-pointer" title="Daftar Bulat">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                    </button>
                                    <button type="button" @click="exec('insertOrderedList')"
                                            :class="activeFormats.listOl ? 'bg-indigo-500/10 text-indigo-600 border-indigo-200' : 'text-slate-600 hover:bg-slate-200 border-transparent'"
                                            class="w-9 h-9 shrink-0 rounded-xl flex items-center justify-center border transition-all cursor-pointer" title="Daftar Angka">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                    </button>

                                    <div class="w-px h-6 bg-slate-200 mx-1 shrink-0"></div>

                                    <!-- Tombol minimize -->
                                    <button type="button" @click="closeFullscreen()"
                                            class="ml-auto w-9 h-9 shrink-0 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-200 border border-transparent transition-all cursor-pointer" title="Tutup Layar Penuh">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9L3.75 3.75M9 9v4.5M9 9H4.5m10.5 6l5.25 5.25M15 15v-4.5m0 4.5h4.5M9 15l-5.25 5.25M9 15H4.5M9 15v4.5m6-10.5l5.25-5.25M15 9h-4.5m4.5 0V4.5" />
                                        </svg>
                                    </button>
                                </div>

                            </div>
                            <!-- END FULLSCREEN OVERLAY -->

                        </div>
                        @error('konten')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Foto Cover / Gambar Thumbnail</label>

                        <div class="relative group" x-show="!selectedFile"
                             @dragover.prevent="dragOver = true"
                             @dragleave.prevent="dragOver = false"
                             @drop.prevent="handleDrop($event)">
                            <input type="file" name="thumbnail" required accept="image/*" id="thumbnail-input" x-ref="fileInput"
                                   @change="handleSelect($event)"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="border-2 border-dashed rounded-2xl p-6 text-center transition-all duration-300"
                                 :class="dragOver ? 'border-primary-500 bg-primary-50/30' : 'border-slate-200 bg-slate-50 group-hover:border-primary-400'">
                                <svg class="w-8 h-8 text-slate-400 mx-auto mb-2 transition-transform duration-300"
                                     :class="dragOver ? 'scale-110 text-primary-500' : 'group-hover:text-primary-500'"
                                     fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-slate-500 font-bold" x-text="dragOver ? 'Lepaskan foto di sini...' : 'Pilih foto cover artikel'"></p>
                                <p class="text-[10px] text-slate-400 mt-1">Maksimal 2MB (format JPG, PNG, WEBP)</p>
                            </div>
                        </div>
                        @error('thumbnail')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror

                        <div class="mt-2" x-show="selectedFile" style="display: none;">
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3 relative overflow-hidden group shadow-xs">
                                <div class="aspect-[16/9] w-full rounded-xl overflow-hidden bg-slate-200 relative">
                                    <template x-if="selectedFile && selectedFile.status === 'ready'">
                                        <img :src="selectedFile.dataUrl" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="selectedFile && selectedFile.status === 'loading'">
                                        <div class="w-full h-full bg-slate-200 flex flex-col items-center justify-center p-3">
                                            <svg class="animate-spin h-6 w-6 text-primary-500 mb-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            <span class="text-xs font-bold text-slate-500">Membaca file...</span>
                                        </div>
                                    </template>
                                    <button type="button" @click="removeFile()" x-show="selectedFile && selectedFile.status === 'ready'"
                                            class="absolute top-3 right-3 w-8 h-8 bg-rose-500 hover:bg-rose-600 text-white rounded-full flex items-center justify-center shadow-md cursor-pointer transition-transform active:scale-90">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
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
                                    <div class="truncate pr-4">
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

                    <button type="submit" id="article-submit-btn" :disabled="submitting || !selectedFile"
                            class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-md active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer mt-4 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" x-show="!submitting">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" x-show="submitting" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span x-text="submitting ? 'Mengirim & Mengunggah...' : 'Simpan & Ajukan Konfirmasi'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function articleUpload() {
            return {
                selectedFile: null,
                dragOver: false,
                submitting: false,

                triggerSelect() {
                    const inputEl = document.getElementById('thumbnail-input');
                    if (inputEl) inputEl.click();
                },
                handleDrop(e) {
                    this.dragOver = false;
                    const files = Array.from(e.dataTransfer.files);
                    if (files.length > 0) this.processFile(files[0]);
                },
                handleSelect(e) {
                    const files = Array.from(e.target.files);
                    if (files.length > 0) this.processFile(files[0]);
                },
                processFile(file) {
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
                    if (!file.type.match('image.*')) {
                        alert('File harus berupa gambar.');
                        return;
                    }
                    this.selectedFile = { name: file.name, size: file.size, dataUrl: '', progress: 0, status: 'loading' };
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
                    if (inputEl) inputEl.value = '';
                },
                formatSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                },
                handleSubmit(e) {
                    this.submitting = true;
                    if (window.showGlobalLoader) {
                        window.showGlobalLoader('Mengunggah Artikel...', 'Sedang memproses tulisan dan gambar cover Anda');
                    }
                }
            }
        }

        function richTextEditor() {
            return {
                content: '',
                isFullscreen: false,
                wordCount: 0,
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

                    this.$nextTick(() => {
                        if (this.$refs.editor && this.$refs.fsEditor) {
                            this.$refs.fsEditor.innerHTML = this.$refs.editor.innerHTML;
                        }
                    });

                    // =====================================================
                    // KUNCI: visualViewport — toolbar naik saat keyboard muncul
                    // =====================================================
                    if (window.visualViewport) {
                        window.visualViewport.addEventListener('resize', () => {
                            if (!this.isFullscreen) return;
                            const toolbar = document.getElementById('fs-toolbar-bottom');
                            if (!toolbar) return;

                            // Hitung selisih tinggi layar penuh vs tinggi viewport saat ini
                            const keyboardHeight = window.innerHeight - window.visualViewport.height;
                            toolbar.style.bottom = keyboardHeight > 0 ? keyboardHeight + 'px' : '0px';
                        });

                        window.visualViewport.addEventListener('scroll', () => {
                            if (!this.isFullscreen) return;
                            const toolbar = document.getElementById('fs-toolbar-bottom');
                            if (!toolbar) return;
                            const keyboardHeight = window.innerHeight - window.visualViewport.height;
                            toolbar.style.bottom = keyboardHeight > 0 ? keyboardHeight + 'px' : '0px';
                        });
                    }
                },

                exec(cmd) {
                    const activeEditor = this.isFullscreen ? this.$refs.fsEditor : this.$refs.editor;
                    activeEditor.focus();
                    document.execCommand(cmd, false, null);
                    this.syncEditors(this.isFullscreen ? 'fs' : 'normal');
                    this.updateContent();
                    this.checkActiveFormats();
                },

                updateContent() {
                    const html = this.$refs.editor.innerHTML;
                    if (html === '<p><br></p>' || html === '<br>' || this.$refs.editor.textContent.trim() === '') {
                        this.content = '';
                        this.wordCount = 0;
                    } else {
                        this.content = html;
                        this.wordCount = this.$refs.editor.textContent.trim().split(/\s+/).filter(w => w.length > 0).length;
                    }
                },

                syncFromFullscreen() {
                    this.$refs.editor.innerHTML = this.$refs.fsEditor.innerHTML;
                    this.updateContent();
                },

                syncEditors(source) {
                    this.$nextTick(() => {
                        if (source === 'fs') {
                            this.$refs.editor.innerHTML = this.$refs.fsEditor.innerHTML;
                        } else {
                            this.$refs.fsEditor.innerHTML = this.$refs.editor.innerHTML;
                        }
                    });
                },

                checkActiveFormats() {
                    this.activeFormats.bold      = document.queryCommandState('bold');
                    this.activeFormats.italic    = document.queryCommandState('italic');
                    this.activeFormats.underline = document.queryCommandState('underline');
                    this.activeFormats.listUl    = document.queryCommandState('insertUnorderedList');
                    this.activeFormats.listOl    = document.queryCommandState('insertOrderedList');
                },

                openFullscreen() {
                    this.$refs.fsEditor.innerHTML = this.$refs.editor.innerHTML;
                    this.isFullscreen = true;
                    this.$refs.fsOverlay.classList.add('fs-active');
                    document.body.classList.add('fs-open');

                    // Reset posisi toolbar dulu
                    const toolbar = document.getElementById('fs-toolbar-bottom');
                    if (toolbar) toolbar.style.bottom = '0px';

                    setTimeout(() => {
                        this.$refs.fsEditor.focus();
                        const range = document.createRange();
                        const sel = window.getSelection();
                        range.selectNodeContents(this.$refs.fsEditor);
                        range.collapse(false);
                        sel.removeAllRanges();
                        sel.addRange(range);
                    }, 320);
                },

                closeFullscreen() {
                    this.$refs.editor.innerHTML = this.$refs.fsEditor.innerHTML;
                    this.updateContent();
                    this.isFullscreen = false;
                    this.$refs.fsOverlay.classList.remove('fs-active');
                    document.body.classList.remove('fs-open');

                    // Reset posisi toolbar
                    const toolbar = document.getElementById('fs-toolbar-bottom');
                    if (toolbar) toolbar.style.bottom = '0px';
                }
            }
        }
    </script>
@endsection