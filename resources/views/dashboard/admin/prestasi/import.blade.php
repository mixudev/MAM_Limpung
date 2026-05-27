@extends('dashboard.layouts.main')

@section('content')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const breadcrumb = document.getElementById('breadcrumb');
            if (breadcrumb) breadcrumb.textContent = 'Import Data Prestasi';
        });
    </script>

    <div class="max-w-screen space-y-6">

        {{-- ── Header ──────────────────────────────────────────────────────────── --}}
        <div
            class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">Import Data Prestasi</h1>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Unggah berkas Excel, pratinjau data, perbaiki
                    kesalahan, lalu proses import massal.</p>
            </div>
            <a href="{{ route('admin.prestasi.index') }}"
                class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all font-mono">
                ← KEMBALI
            </a>
        </div>

        {{-- ── Flash Messages ───────────────────────────────────────────────────── --}}
        @if (session('success'))
            <div id="flash-ok"
                class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-300 dark:border-emerald-700/50 p-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-emerald-800 dark:text-emerald-400 text-sm font-semibold">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-emerald-600 font-bold text-xl leading-none">&times;</button>
            </div>
        @endif
        @if ($errors->any())
            <div id="flash-err" class="bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/50 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span
                        class="text-rose-700 dark:text-rose-400 font-bold text-xs font-mono uppercase tracking-wider">Import
                        Gagal</span>
                    <button onclick="this.parentElement.remove()"
                        class="text-rose-500 font-bold text-xl leading-none">&times;</button>
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $e)
                        <li class="text-xs text-rose-700 dark:text-rose-400">{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('import_errors'))
            <div id="flash-skip"
                class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/50 p-4 max-h-48 overflow-y-auto">
                <div class="flex items-center justify-between mb-2">
                    <span
                        class="text-amber-700 dark:text-amber-400 font-bold text-xs font-mono uppercase tracking-wider">Baris
                        Dilewati Server</span>
                    <button onclick="this.parentElement.remove()"
                        class="text-amber-600 font-bold text-xl leading-none">&times;</button>
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach (session('import_errors') as $e)
                        <li class="text-[11px] font-mono text-amber-700 dark:text-amber-400">{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            {{-- ── Left 3/4 ────────────────────────────────────────────────────── --}}
            <div class="lg:col-span-3 space-y-5">

                {{-- Step 1: Download Template --}}
                <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
                    <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-800 flex items-center gap-2">

                        <span
                            class="text-xs font-bold font-mono uppercase tracking-wider text-slate-700 dark:text-zinc-300">Unduh
                            Template Excel</span>
                    </div>
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <p class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed">
                            Gunakan template resmi kami. Isi data mulai <strong
                                class="text-slate-700 dark:text-zinc-300">baris ke-5</strong>. Jangan ubah baris header
                            (1–4).
                        </p>
                        <a href="{{ route('admin.prestasi.template') }}"
                            class="shrink-0 inline-flex items-center gap-2 py-2.5 px-5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-none transition-all tracking-wider font-mono">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            DOWNLOAD TEMPLATE
                        </a>
                    </div>
                </div>

                {{-- Step 2: Drag & Drop --}}
                <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
                    <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-800 flex items-center gap-2">

                        <span
                            class="text-xs font-bold font-mono uppercase tracking-wider text-slate-700 dark:text-zinc-300">Pilih
                            Berkas Excel</span>
                    </div>
                    <div class="p-5">
                        <form id="importForm" action="{{ route('admin.prestasi.import') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="file_excel" name="file_excel" accept=".xlsx,.xls" class="hidden">

                            {{-- Dropzone as label --}}
                            <label id="dropzone" for="file_excel"
                                class="flex items-center justify-center min-h-[150px] w-full border-2 border-dashed border-slate-300 dark:border-zinc-600 hover:border-[#4f45b2] dark:hover:border-indigo-500 bg-slate-50 dark:bg-zinc-800/40 hover:bg-indigo-50/20 rounded-none cursor-pointer transition-all duration-200">
                                <div id="dz-empty" class="text-center space-y-3 px-6 py-6">
                                    <div
                                        class="mx-auto w-12 h-12 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-400 dark:text-zinc-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700 dark:text-zinc-300">Seret &amp; Lepas
                                            file di sini</p>
                                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">atau <span
                                                class="text-[#4f45b2] dark:text-indigo-400 font-bold underline underline-offset-2">klik
                                                untuk memilih file</span></p>
                                    </div>
                                    <p class="text-[10px] text-slate-400 dark:text-zinc-600 font-mono">Format: .XLSX atau
                                        .XLS &nbsp;|&nbsp; Maks. 5 MB</p>
                                </div>
                                <div id="dz-preview" class="hidden w-full px-6 py-5 flex items-center gap-4">
                                    <div
                                        class="w-12 h-14 shrink-0 bg-emerald-600 flex flex-col items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                            <path fill="rgba(255,255,255,0.25)" d="M14 2l6 6h-6V2z" />
                                        </svg>
                                        <span id="dz-ext" class="text-[9px] font-bold font-mono mt-1">XLSX</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p id="dz-name"
                                            class="text-sm font-bold text-slate-800 dark:text-zinc-200 truncate"></p>
                                        <p id="dz-size" class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5"></p>
                                        <span
                                            class="inline-flex items-center gap-1 mt-2 text-[10px] font-mono font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 px-2 py-0.5">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            FILE DIPILIH
                                        </span>
                                    </div>
                                    <button type="button" id="dz-clear"
                                        class="shrink-0 p-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 text-rose-600 dark:text-rose-400 transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </label>
                        </form>
                    </div>
                </div>

                {{-- Step 3: Preview Table (hidden until file loaded) --}}


                {{-- Step 4: Submit --}}
                <div id="submit-section"
                    class="hidden bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
                    <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-800 flex items-center gap-2">

                        <span
                            class="text-xs font-bold font-mono uppercase tracking-wider text-slate-700 dark:text-zinc-300">Proses
                            Import</span>
                    </div>
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div id="submit-info" class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed"></div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('admin.prestasi.index') }}"
                                class="py-2.5 px-5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all font-mono">
                                BATAL
                            </a>
                            <button id="btn-submit" type="button" disabled
                                class="py-2.5 px-6 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold text-xs rounded-none transition-all tracking-wider font-mono flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                                PROSES IMPORT
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Right Info Panel 1/4 ─────────────────────────────────────────── --}}
            <div class="space-y-4">

                {{-- Rules --}}
                <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
                    <div class="px-4 py-3 bg-slate-50 dark:bg-zinc-800/50 border-b border-slate-200 dark:border-zinc-800">
                        <span
                            class="text-[10px] font-bold font-mono uppercase tracking-wider text-slate-500 dark:text-zinc-400">Aturan
                            Import</span>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 shrink-0 mt-0.5 text-emerald-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                            </svg><span class="text-[11px] text-slate-600 dark:text-zinc-400">Format file .xlsx atau
                                .xls</span></div>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 shrink-0 mt-0.5 text-emerald-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                            </svg><span class="text-[11px] text-slate-600 dark:text-zinc-400">Maks. 5 MB</span></div>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 shrink-0 mt-0.5 text-emerald-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                            </svg><span class="text-[11px] text-slate-600 dark:text-zinc-400">Data mulai <strong>baris
                                    ke-5</strong></span></div>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 shrink-0 mt-0.5 text-blue-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01" />
                            </svg><span class="text-[11px] text-slate-600 dark:text-zinc-400">Duplikat (judul+peraih+tahun)
                                diperbarui otomatis</span></div>
                        <div class="flex items-start gap-2"><svg class="w-4 h-4 shrink-0 mt-0.5 text-amber-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01" />
                            </svg><span class="text-[11px] text-slate-600 dark:text-zinc-400">Baris tidak valid
                                <strong>dilewati</strong></span></div>
                    </div>
                </div>

                {{-- Column values --}}
                <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
                    <div class="px-4 py-3 bg-slate-50 dark:bg-zinc-800/50 border-b border-slate-200 dark:border-zinc-800">
                        <span
                            class="text-[10px] font-bold font-mono uppercase tracking-wider text-slate-500 dark:text-zinc-400">Nilai
                            Kolom Valid</span>
                    </div>
                    <div class="divide-y divide-slate-50 dark:divide-zinc-800/50">
                        <div class="px-4 py-3">
                            <p class="text-[10px] font-mono font-bold text-[#4f45b2] dark:text-indigo-400 mb-1">TINGKAT</p>
                            <p class="text-[10px] text-slate-500 dark:text-zinc-400">Sekolah | Kabupaten | Provinsi |
                                Nasional | Internasional</p>
                        </div>
                        <div class="px-4 py-3">
                            <p class="text-[10px] font-mono font-bold text-[#4f45b2] dark:text-indigo-400 mb-1">JENIS</p>
                            <p class="text-[10px] text-slate-500 dark:text-zinc-400">Akademik | Non-Akademik</p>
                        </div>
                        <div class="px-4 py-3">
                            <p class="text-[10px] font-mono font-bold text-[#4f45b2] dark:text-indigo-400 mb-1">UNGGULAN
                            </p>
                            <p class="text-[10px] text-slate-500 dark:text-zinc-400">Ya | Tidak</p>
                        </div>
                    </div>
                </div>

                {{-- Quick Links --}}
                <!-- <div class="bg-indigo-50 dark:bg-indigo-950/20 border border-indigo-200 dark:border-indigo-800/40 p-4 space-y-2">
                    <p class="text-[10px] font-bold font-mono uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Tools Lainnya</p>
                    <div class="flex flex-col gap-2 pt-1">
                        <a href="{{ route('admin.prestasi.export.excel') }}" class="flex items-center gap-1.5 text-[11px] font-bold font-mono text-indigo-700 dark:text-indigo-400 hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            Export Excel
                        </a>
                        <a href="{{ route('admin.prestasi.export.pdf') }}" target="_blank" class="flex items-center gap-1.5 text-[11px] font-bold font-mono text-indigo-700 dark:text-indigo-400 hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Export PDF
                        </a>
                        <a href="{{ route('admin.prestasi.create') }}" class="flex items-center gap-1.5 text-[11px] font-bold font-mono text-indigo-700 dark:text-indigo-400 hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Tambah Manual
                        </a>
                    </div>
                </div> -->
            </div>
        </div>
        <div id="preview-section"
            class="hidden bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
            <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-800 flex items-center justify-between gap-4">
                <div class="flex items-center gap-2">

                    <span
                        class="text-xs font-bold font-mono uppercase tracking-wider text-slate-700 dark:text-zinc-300">Pratinjau
                        Data</span>
                </div>
                <div id="preview-summary" class="flex items-center gap-3">
                    <span id="count-valid"
                        class="inline-flex items-center gap-1 text-[11px] font-mono font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/40 px-2.5 py-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span id="valid-num">0</span> VALID
                    </span>
                    <span id="count-invalid"
                        class="inline-flex items-center gap-1 text-[11px] font-mono font-bold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/40 px-2.5 py-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01" />
                        </svg>
                        <span id="invalid-num">0</span> PERLU DIPERBAIKI
                    </span>
                </div>
            </div>

            {{-- Info if there are invalid rows --}}
            <div id="invalid-hint"
                class="hidden px-5 py-3 bg-amber-50 dark:bg-amber-950/10 border-b border-amber-200 dark:border-amber-800/30 text-xs text-amber-800 dark:text-amber-400 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
                <span>Baris berwarna merah memiliki data yang tidak valid. <strong>Perbaiki file Excel Anda</strong>, lalu
                    upload ulang. Baris tersebut tidak akan diimpor.</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-zinc-800/60 border-b border-slate-200 dark:border-zinc-700">
                            <th
                                class="px-3 py-2 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400 w-8">
                                #</th>
                            <th
                                class="px-3 py-2 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400 whitespace-nowrap">
                                Tanggal</th>
                            <th
                                class="px-3 py-2 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400">
                                Tahun</th>
                            <th
                                class="px-3 py-2 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400 whitespace-nowrap">
                                Peraih <span class="text-rose-500">*</span></th>
                            <th
                                class="px-3 py-2 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400">
                                Judul Prestasi <span class="text-rose-500">*</span></th>
                            <th
                                class="px-3 py-2 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400">
                                Juara</th>
                            <th
                                class="px-3 py-2 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400">
                                Tingkat <span class="text-rose-500">*</span></th>
                            <th
                                class="px-3 py-2 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400">
                                Jenis <span class="text-rose-500">*</span></th>
                            <th
                                class="px-3 py-2 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400">
                                Penyelenggara</th>
                            <th
                                class="px-3 py-2 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400">
                                Unggulan</th>
                            <th
                                class="px-3 py-2 text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-zinc-400 w-8">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody id="preview-tbody" class="divide-y divide-slate-100 dark:divide-zinc-800/60">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SheetJS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /* ── Elements ── */
            const fileInput = document.getElementById('file_excel');
            const dropzone = document.getElementById('dropzone');
            const dzEmpty = document.getElementById('dz-empty');
            const dzPreview = document.getElementById('dz-preview');
            const dzName = document.getElementById('dz-name');
            const dzSize = document.getElementById('dz-size');
            const dzExt = document.getElementById('dz-ext');
            const dzClear = document.getElementById('dz-clear');
            const previewSec = document.getElementById('preview-section');
            const submitSec = document.getElementById('submit-section');
            const tbody = document.getElementById('preview-tbody');
            const validNum = document.getElementById('valid-num');
            const invalidNum = document.getElementById('invalid-num');
            const invalidHint = document.getElementById('invalid-hint');
            const submitInfo = document.getElementById('submit-info');
            const btnSubmit = document.getElementById('btn-submit');
            const importForm = document.getElementById('importForm');

            /* ── Validation helpers ── */
            const VALID_TINGKAT = ['sekolah', 'kabupaten', 'provinsi', 'nasional', 'internasional'];
            const VALID_JENIS = ['akademik', 'non-akademik', 'nonakademik', 'non_akademik'];

            function normTingkat(v) {
                if (!v) return null;
                const c = v.toLowerCase().replace(/[-_\/ ]/g, '');
                if (['sekolah'].includes(c)) return 'Sekolah';
                if (['kabupatenkota', 'kabupaten', 'kota'].includes(c)) return 'Kabupaten';
                if (['provinsi', 'prov'].includes(c)) return 'Provinsi';
                if (['nasional', 'nas'].includes(c)) return 'Nasional';
                if (['internasional', 'intl', 'int'].includes(c)) return 'Internasional';
                return null;
            }

            function normJenis(v) {
                if (!v) return null;
                const c = v.toLowerCase().replace(/[-_\/ ]/g, '');
                if (['akademik', 'akademis'].includes(c)) return 'Akademik';
                if (['nonakademik', 'non'].includes(c)) return 'Non-Akademik';
                return null;
            }

            function parseDate(v) {
                if (!v && v !== 0) return null;
                // SheetJS with cellDates:true returns Date objects
                if (v instanceof Date) {
                    return v.toISOString().substring(0, 10);
                }
                // Try common string formats
                const s = String(v).trim();
                if (!s) return null;
                // YYYY-MM-DD
                if (/^\d{4}-\d{2}-\d{2}$/.test(s)) return s;
                // DD/MM/YYYY
                const m1 = s.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
                if (m1) return `${m1[3]}-${m1[2].padStart(2,'0')}-${m1[1].padStart(2,'0')}`;
                // YYYY/MM/DD
                const m2 = s.match(/^(\d{4})\/(\d{2})\/(\d{2})$/);
                if (m2) return `${m2[1]}-${m2[2]}-${m2[3]}`;
                // Excel numeric date
                if (/^\d+$/.test(s)) {
                    const n = parseInt(s);
                    if (n > 30000) {
                        const d = new Date(Date.UTC(1900, 0, n - 1));
                        return d.toISOString().substring(0, 10);
                    }
                }
                return null;
            }

            function validateRow(row) {
                // row: { tanggal, tahun, peraih, judul, juara, tingkat, jenis, penyelenggara, unggulan, deskripsi }
                const errors = {};

                if (!row.judul || !String(row.judul).trim()) errors.judul = 'Judul tidak boleh kosong';
                if (!row.peraih || !String(row.peraih).trim()) errors.peraih = 'Peraih tidak boleh kosong';

                const tahun = parseInt(row.tahun);
                if (!row.tahun || isNaN(tahun) || tahun < 2000 || tahun > 2100) errors.tahun =
                    'Tahun harus angka 2000–2100';

                if (row.tanggal) {
                    const parsed = parseDate(row.tanggal);
                    if (!parsed) errors.tanggal = 'Format tidak valid (gunakan YYYY-MM-DD)';
                }

                if (!row.tingkat || !normTingkat(String(row.tingkat).trim())) {
                    errors.tingkat = 'Pilih: Sekolah | Kabupaten | Provinsi | Nasional | Internasional';
                }

                if (!row.jenis || !normJenis(String(row.jenis).trim())) {
                    errors.jenis = 'Pilih: Akademik | Non-Akademik';
                }

                return errors;
            }

            /* ── Format bytes ── */
            function formatBytes(b) {
                return b < 1024 ? b + ' B' : b < 1048576 ? (b / 1024).toFixed(1) + ' KB' : (b / 1048576).toFixed(
                    1) + ' MB';
            }

            /* ── Show/Clear file ── */
            function showFileInfo(file) {
                dzName.textContent = file.name;
                dzSize.textContent = formatBytes(file.size);
                dzExt.textContent = file.name.split('.').pop().toUpperCase();
                dzEmpty.classList.add('hidden');
                dzPreview.classList.remove('hidden');
                dropzone.classList.remove('border-slate-300', 'dark:border-zinc-600', 'hover:border-[#4f45b2]',
                    'bg-slate-50', 'dark:bg-zinc-800/40');
                dropzone.classList.add('border-emerald-400', 'dark:border-emerald-700', 'bg-emerald-50/30',
                    'dark:bg-emerald-950/10');
            }

            function clearAll() {
                fileInput.value = '';
                dzEmpty.classList.remove('hidden');
                dzPreview.classList.add('hidden');
                dropzone.classList.add('border-slate-300', 'dark:border-zinc-600', 'hover:border-[#4f45b2]',
                    'bg-slate-50', 'dark:bg-zinc-800/40');
                dropzone.classList.remove('border-emerald-400', 'dark:border-emerald-700', 'bg-emerald-50/30',
                    'dark:bg-emerald-950/10');
                previewSec.classList.add('hidden');
                submitSec.classList.add('hidden');
                tbody.innerHTML = '';
                btnSubmit.disabled = true;
            }

            /* ── Build preview table ── */
            function buildPreview(rows) {
                tbody.innerHTML = '';
                let validCount = 0,
                    invalidCount = 0;

                rows.forEach((row, i) => {
                    const errs = validateRow(row);
                    const isValid = Object.keys(errs).length === 0;
                    if (isValid) validCount++;
                    else invalidCount++;

                    const tr = document.createElement('tr');
                    tr.className = isValid ?
                        'bg-white dark:bg-zinc-900 hover:bg-emerald-50/30 dark:hover:bg-emerald-950/5' :
                        'bg-rose-50/60 dark:bg-rose-950/10 border-l-2 border-rose-500';

                    function cell(val, errKey) {
                        const td = document.createElement('td');
                        td.className =
                            'px-3 py-2 text-[11px] text-slate-700 dark:text-zinc-300 max-w-[180px]';
                        const hasErr = errs[errKey];
                        if (hasErr) {
                            td.className += ' relative';
                            td.innerHTML = `
                        <div class="flex items-start gap-1">
                            <span class="text-rose-600 dark:text-rose-400 font-semibold truncate block">${val !== null && val !== undefined && val !== '' ? String(val) : '<span class="italic opacity-50">kosong</span>'}</span>
                            <div class="relative group shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-rose-500 cursor-help" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                                <div class="absolute left-full top-0 ml-2 z-50 hidden group-hover:block w-48 bg-rose-700 text-white text-[10px] p-2 leading-relaxed shadow-lg pointer-events-none">${hasErr}</div>
                            </div>
                        </div>`;
                        } else {
                            const display = val !== null && val !== undefined && val !== '' ? String(val) :
                                '<span class="italic text-slate-300 dark:text-zinc-600">—</span>';
                            td.innerHTML = `<span class="truncate block">${display}</span>`;
                        }
                        return td;
                    }

                    // Row number
                    const tdNo = document.createElement('td');
                    tdNo.className =
                    'px-3 py-2 text-[10px] font-mono text-slate-400 dark:text-zinc-500 w-8';
                    tdNo.textContent = i + 1;
                    tr.appendChild(tdNo);

                    tr.appendChild(cell(row.tanggal instanceof Date ? row.tanggal.toISOString().substring(0,
                        10) : row.tanggal, 'tanggal'));
                    tr.appendChild(cell(row.tahun, 'tahun'));
                    tr.appendChild(cell(row.peraih, 'peraih'));

                    // Judul — truncate long text
                    const tdJudul = cell(row.judul, 'judul');
                    tdJudul.className += ' max-w-[200px]';
                    tr.appendChild(tdJudul);

                    tr.appendChild(cell(row.juara, null));
                    tr.appendChild(cell(row.tingkat, 'tingkat'));
                    tr.appendChild(cell(row.jenis, 'jenis'));
                    tr.appendChild(cell(row.penyelenggara, null));
                    tr.appendChild(cell(row.unggulan, null));

                    // Status badge
                    const tdStatus = document.createElement('td');
                    tdStatus.className = 'px-3 py-2 w-8';
                    if (isValid) {
                        tdStatus.innerHTML =
                            `<span class="inline-flex items-center gap-1 text-[9px] font-mono font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/40 px-1.5 py-0.5"><svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>OK</span>`;
                    } else {
                        tdStatus.innerHTML =
                            `<span class="inline-flex items-center gap-1 text-[9px] font-mono font-bold text-rose-700 dark:text-rose-400 bg-rose-100 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/40 px-1.5 py-0.5"><svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>PERBAIKI</span>`;
                    }
                    tr.appendChild(tdStatus);

                    tbody.appendChild(tr);
                });

                // Update counters
                validNum.textContent = validCount;
                invalidNum.textContent = invalidCount;

                // Show/hide invalid hint
                if (invalidCount > 0) {
                    invalidHint.classList.remove('hidden');
                } else {
                    invalidHint.classList.add('hidden');
                }

                // Update submit info
                if (validCount === 0) {
                    submitInfo.innerHTML =
                        `<span class="text-rose-600 dark:text-rose-400 font-semibold">Semua baris memiliki data tidak valid. Perbaiki file Excel Anda lalu upload ulang.</span>`;
                    btnSubmit.disabled = true;
                } else if (invalidCount > 0) {
                    submitInfo.innerHTML =
                        `<span class="text-amber-700 dark:text-amber-400"><strong>${validCount}</strong> baris akan diimport. <strong class="text-rose-600 dark:text-rose-400">${invalidCount}</strong> baris dilewati (data tidak valid). Perbaiki dulu untuk mengimport semua baris.</span>`;
                    btnSubmit.disabled = false;
                } else {
                    submitInfo.innerHTML =
                        `<span class="text-emerald-700 dark:text-emerald-400 font-semibold">Semua <strong>${validCount}</strong> baris siap diimport ke database.</span>`;
                    btnSubmit.disabled = false;
                }
            }

            /* ── Parse Excel file with SheetJS ── */
            function parseExcel(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const wb = XLSX.read(e.target.result, {
                            type: 'array',
                            cellDates: true
                        });
                        const ws = wb.Sheets[wb.SheetNames[0]];
                        // Get all rows as array of arrays
                        const raw = XLSX.utils.sheet_to_json(ws, {
                            header: 1,
                            defval: null,
                            dateNF: 'yyyy-mm-dd'
                        });

                        // Skip first 4 rows (title, instructions, empty, headers)
                        const dataRows = raw.slice(4);

                        // Filter empty rows (all cells null/empty)
                        const rows = dataRows.filter(r => r && r.some(c => c !== null && c !== undefined &&
                            c !== ''));

                        if (rows.length === 0) {
                            tbody.innerHTML =
                                `<tr><td colspan="11" class="px-5 py-8 text-center text-sm text-slate-400 dark:text-zinc-500">Tidak ada data ditemukan setelah baris ke-4. Pastikan file menggunakan template yang benar.</td></tr>`;
                            previewSec.classList.remove('hidden');
                            submitSec.classList.remove('hidden');
                            validNum.textContent = '0';
                            invalidNum.textContent = '0';
                            btnSubmit.disabled = true;
                            submitInfo.innerHTML =
                                '<span class="text-rose-600 dark:text-rose-400 font-semibold">Tidak ada data yang bisa diimport.</span>';
                            return;
                        }

                        const mapped = rows.map(r => ({
                            tanggal: r[1] ?? null,
                            tahun: r[2] ?? null,
                            peraih: r[3] ?? null,
                            judul: r[4] ?? null,
                            juara: r[5] ?? null,
                            tingkat: r[6] ?? null,
                            jenis: r[7] ?? null,
                            penyelenggara: r[8] ?? null,
                            unggulan: r[9] ?? null,
                            deskripsi: r[10] ?? null,
                        }));

                        buildPreview(mapped);
                        previewSec.classList.remove('hidden');
                        submitSec.classList.remove('hidden');

                    } catch (err) {
                        tbody.innerHTML =
                            `<tr><td colspan="11" class="px-5 py-6 text-center text-sm text-rose-600 dark:text-rose-400">Gagal membaca file: ${err.message}</td></tr>`;
                        previewSec.classList.remove('hidden');
                        submitSec.classList.remove('hidden');
                        btnSubmit.disabled = true;
                    }
                };
                reader.readAsArrayBuffer(file);
            }

            /* ── File selection handler ── */
            function handleFile(file) {
                if (!file) return;
                const allowed = /\.(xlsx|xls)$/i;
                if (!allowed.test(file.name)) {
                    alert('Format harus .xlsx atau .xls');
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran maksimal 5MB');
                    return;
                }
                showFileInfo(file);
                parseExcel(file);
            }

            /* ── Events ── */
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) handleFile(this.files[0]);
            });

            dzClear.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                clearAll();
            });

            dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.add('border-[#4f45b2]', 'dark:border-indigo-500', '!bg-indigo-50/30');
            });
            dropzone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.remove('border-[#4f45b2]', 'dark:border-indigo-500', '!bg-indigo-50/30');
            });
            dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.remove('border-[#4f45b2]', 'dark:border-indigo-500', '!bg-indigo-50/30');
                const f = e.dataTransfer.files[0];
                if (f) {
                    const dt = new DataTransfer();
                    dt.items.add(f);
                    fileInput.files = dt.files;
                    handleFile(f);
                }
            });

            /* ── Submit ── */
            btnSubmit.addEventListener('click', function() {
                if (btnSubmit.disabled) return;
                btnSubmit.disabled = true;
                btnSubmit.innerHTML =
                    '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg> MEMPROSES...';
                importForm.submit();
            });
        });
    </script>
@endsection
