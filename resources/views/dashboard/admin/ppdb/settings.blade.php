@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Pengaturan PPDB';
        }
    });
</script>

<div class="space-y-6">

    <!-- Header Panel -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Pengaturan & Konfigurasi PPDB</h1>
            <p class="text-sm text-slate-500 dark:text-zinc-400 mt-1">Konfigurasi alur penerimaan, checklist berkas syarat, dan kelola input formulir dinamis.</p>
        </div>
        <a href="{{ route('admin.ppdb.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center">
            Kembali ke Pendaftar
        </a>
    </div>

    <!-- Alert Success / Errors -->
    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/60 p-4 text-emerald-800 dark:text-emerald-400 text-xs font-semibold flex items-center gap-3 animate-fadeIn">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>
            @php
                $parts = explode('|', session('success'));
                echo count($parts) > 1 ? "<strong>{$parts[0]}:</strong> {$parts[1]}" : session('success');
            @endphp
        </span>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/60 p-4 text-red-800 dark:text-red-400 text-xs font-semibold rounded-none">
        <p class="font-bold mb-2">Terjadi kesalahan validasi:</p>
        <ul class="list-disc list-inside space-y-1 font-mono">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Tab Selection Buttons -->
    <div class="border-b border-slate-200 dark:border-zinc-800 flex flex-wrap gap-2">
        <button onclick="switchTab('tab-umum')" id="btn-tab-umum" class="tab-btn px-5 py-3 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-[#4f45b2] text-[#4f45b2] dark:text-white transition-all rounded-none">
            1. Umum & Alur PPDB
        </button>
        <button onclick="switchTab('tab-persyaratan')" id="btn-tab-persyaratan" class="tab-btn px-5 py-3 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 transition-all rounded-none">
            2. Persyaratan Berkas
        </button>
        <button onclick="switchTab('tab-formulir')" id="btn-tab-formulir" class="tab-btn px-5 py-3 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 transition-all rounded-none">
            3. Pembangun Formulir
        </button>
    </div>

    <!-- Tab 1: Umum & Alur PPDB -->
    <div id="tab-umum" class="tab-content space-y-6">
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none shadow-sm">
            <h3 class="text-sm font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-100 dark:border-zinc-850 pb-3 mb-5">
                Konfigurasi Umum Penerimaan
            </h3>

            <form action="{{ route('admin.ppdb.settings.general') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status Switch -->
                    <div>
                        <label for="is_open" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Status Gerbang PPDB</label>
                        <select name="is_open" id="is_open" class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                            <option value="1" {{ $general['is_open'] ? 'selected' : '' }}>BUKA - Pendaftaran Aktif</option>
                            <option value="0" {{ !$general['is_open'] ? 'selected' : '' }}>TUTUP - Pendaftaran Nonaktif</option>
                        </select>
                    </div>

                    <!-- Tahun Pelajaran -->
                    <div>
                        <label for="tahun_ajaran" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Tahun Pelajaran Berjalan</label>
                        <input type="number" name="tahun_ajaran" id="tahun_ajaran" value="{{ $general['tahun_ajaran'] }}" required min="2020" max="2100"
                            class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                    </div>

                    <!-- Target Kuota -->
                    <div>
                        <label for="target_quota" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Target Kuota Tampung (Siswa)</label>
                        <input type="number" name="target_quota" id="target_quota" value="{{ $general['target_quota'] }}" required min="1" max="5000"
                            class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                    </div>

                    <!-- Biaya Pendaftaran -->
                    <div>
                        <label for="registration_fee" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Biaya Registrasi Pendaftaran (Rp)</label>
                        <input type="number" name="registration_fee" id="registration_fee" value="{{ $general['registration_fee'] }}" required min="0"
                            class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="start_date" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Tanggal Mulai Pendaftaran</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $general['start_date'] }}" required
                            class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                    </div>

                    <!-- Tanggal Tutup -->
                    <div>
                        <label for="end_date" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Tanggal Selesai Pendaftaran</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $general['end_date'] }}" required
                            class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-zinc-850 pt-4 flex justify-end">
                    <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98]">
                        Simpan Pengaturan Umum
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab 2: Persyaratan Berkas -->
    <div id="tab-persyaratan" class="tab-content space-y-6 hidden">
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-850 pb-3 mb-5">
                <div>
                    <h3 class="text-sm font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8]">
                        Daftar Berkas Syarat Unggah
                    </h3>
                    <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Kelola dokumen scan yang wajib diunggah oleh calon siswa baru pada formulir pendaftaran.</p>
                </div>
                <button type="button" onclick="addNewRequirementRow()" class="py-2 px-4 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-mono font-bold text-[10px] uppercase tracking-wider rounded-none">
                    + Tambah Berkas Baru
                </button>
            </div>

            <form action="{{ route('admin.ppdb.settings.requirements') }}" method="POST" id="requirementsForm">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="requirementsTable">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-zinc-800/40 border-b border-slate-100 dark:border-zinc-800/80">
                                <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">ID Kode Berkas (Slug)</th>
                                <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Nama Dokumen Persyaratan</th>
                                <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Sifat Dokumen</th>
                                <th class="px-4 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/50" id="requirementsTableBody">
                            @forelse($requirements as $index => $req)
                                <tr class="hover:bg-slate-50/20 transition-all" id="req-row-{{ $index }}">
                                    <!-- ID / Key -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="text" name="requirements[{{ $index }}][id]" value="{{ $req['id'] }}" required readonly
                                            class="w-full bg-slate-100 dark:bg-zinc-800/80 border border-slate-200 dark:border-zinc-700 rounded-none text-xs font-mono text-slate-500 dark:text-zinc-400 py-1.5 px-2.5">
                                    </td>
                                    <!-- Label -->
                                    <td class="px-4 py-3">
                                        <input type="text" name="requirements[{{ $index }}][label]" value="{{ $req['label'] }}" required
                                            class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                    </td>
                                    <!-- Mandatory / Optional Toggle -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <select name="requirements[{{ $index }}][required]" 
                                            class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                                            <option value="1" {{ $req['required'] ? 'selected' : '' }}>WAJIB (Required)</option>
                                            <option value="0" {{ !$req['required'] ? 'selected' : '' }}>OPSIONAL (Optional)</option>
                                        </select>
                                    </td>
                                    <!-- Delete Row -->
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <button type="button" onclick="removeRequirementRow('{{ $index }}')" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100/50 rounded-none transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="no-requirements-placeholder">
                                    <td colspan="4" class="text-center py-8 text-slate-400 dark:text-zinc-500 text-xs">Belum ada persyaratan berkas yang didaftarkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-100 dark:border-zinc-850 pt-4 flex justify-end mt-4">
                    <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98]">
                        Simpan Daftar Persyaratan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab 3: Pembangun Formulir -->
    <div id="tab-formulir" class="tab-content space-y-6 hidden">
        
        <!-- Core Database Form Fields Info (Core / Reserved) -->
        <div class="bg-slate-50 dark:bg-zinc-950 p-6 border border-slate-200 dark:border-zinc-850 rounded-none">
            <h4 class="text-xs font-mono font-bold uppercase tracking-wider text-[#4f45b2] dark:text-[#8c84c8] flex items-center gap-2 mb-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
                Kolom Formulir Utama (Core System Fields)
            </h4>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mb-4">Input berikut adalah kolom bawaan sistem data administrasi terpusat. Kolom ini bersifat permanen demi menjamin kecocokan laporan database.</p>
            <div class="flex flex-wrap gap-2">
                @php
                    $cores = ['Nama Lengkap', 'NISN', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Nomor HP', 'Email', 'Nama Ayah', 'Nama Ibu', 'Alamat Lengkap', 'Sekolah Asal', 'Ukuran Seragam', 'Pratinjau Foto'];
                @endphp
                @foreach($cores as $core)
                    <span class="px-2.5 py-1 text-[10px] font-mono border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-400 dark:text-zinc-500 rounded-none">
                        {{ $core }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Custom Fields Workspace (Interactive Table-Edit Batch) -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-850 pb-3 mb-5">
                <div>
                    <h3 class="text-sm font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8]">
                        Daftar Kolom Kustom Dinamis
                    </h3>
                    <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Buat kolom isian tambahan sesuai kebutuhan sekolah Anda dan akan terintegrasi langsung di form pendaftaran siswa.</p>
                </div>
                <button type="button" onclick="addNewFormFieldRow()" class="py-2 px-4 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-mono font-bold text-[10px] uppercase tracking-wider rounded-none">
                    + Tambah Kolom Kustom
                </button>
            </div>

            <form action="{{ route('admin.ppdb.settings.fields.update') }}" method="POST" id="formFieldsForm">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="formFieldsTable">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-zinc-800/40 border-b border-slate-100 dark:border-zinc-800/80">
                                <th class="px-2 py-3.5 w-10 text-center text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500"></th>
                                <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">ID Slug</th>
                                <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Label Pertanyaan / Input</th>
                                <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Tipe Input</th>
                                <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Sifat</th>
                                <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/50" id="formFieldsTableBody">
                            @forelse($formFields as $index => $field)
                                <tr class="hover:bg-slate-50/20 transition-all cursor-move" id="field-row-{{ $index }}" draggable="true">
                                    <!-- Drag Handle -->
                                    <td class="drag-handle px-2 py-3 text-center whitespace-nowrap text-slate-450 dark:text-zinc-600 cursor-grab active:cursor-grabbing">
                                        <svg class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                        </svg>
                                    </td>
                                    <!-- ID Slug (Read-only for existing ones) -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="text" name="fields[{{ $index }}][id]" value="{{ $field['id'] }}" required readonly
                                            class="w-full bg-slate-100 dark:bg-zinc-800/80 border border-slate-200 dark:border-zinc-700 rounded-none text-xs font-mono text-slate-500 dark:text-zinc-400 py-1.5 px-2.5">
                                    </td>
                                    <!-- Label & Options -->
                                    <td class="px-4 py-3">
                                        <div class="space-y-2">
                                            <input type="text" name="fields[{{ $index }}][label]" id="field-label-input-{{ $index }}" value="{{ $field['label'] }}" required
                                                oninput="syncFieldSlug({{ $index }})"
                                                class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                            
                                            <div id="field-options-wrapper-{{ $index }}" class="{{ $field['type'] !== 'select' ? 'hidden' : '' }}">
                                                <span class="text-[10px] font-mono font-bold text-slate-400 dark:text-zinc-500 block mb-1">PILIHAN DROPDOWN (Pisahkan dengan koma):</span>
                                                <input type="text" name="fields[{{ $index }}][options]" id="field-options-input-{{ $index }}"
                                                    value="{{ !empty($field['options']) ? implode(', ', $field['options']) : '' }}"
                                                    placeholder="e.g. Pilihan 1, Pilihan 2, Pilihan 3"
                                                    class="w-full bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 rounded-none text-xs py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Type -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <select name="fields[{{ $index }}][type]" id="field-type-select-{{ $index }}" onchange="toggleOptionsInputRow({{ $index }})"
                                            class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                                            <option value="text" {{ $field['type'] === 'text' ? 'selected' : '' }}>Teks Singkat</option>
                                            <option value="number" {{ $field['type'] === 'number' ? 'selected' : '' }}>Angka</option>
                                            <option value="select" {{ $field['type'] === 'select' ? 'selected' : '' }}>Dropdown Menu</option>
                                            <option value="date" {{ $field['type'] === 'date' ? 'selected' : '' }}>Tanggal</option>
                                            <option value="textarea" {{ $field['type'] === 'textarea' ? 'selected' : '' }}>Teks Panjang</option>
                                        </select>
                                    </td>
                                    <!-- Required Toggle -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <select name="fields[{{ $index }}][required]" 
                                            class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                                            <option value="0" {{ !$field['required'] ? 'selected' : '' }}>OPSIONAL</option>
                                            <option value="1" {{ $field['required'] ? 'selected' : '' }}>WAJIB</option>
                                        </select>
                                    </td>
                                    <!-- Delete Row -->
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <button type="button" onclick="removeFormFieldRow('{{ $index }}', '{{ $field['label'] }}')" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100/50 rounded-none transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="no-fields-placeholder">
                                    <td colspan="6" class="text-center py-10 text-slate-400 dark:text-zinc-500 text-xs">Belum ada kolom kustom tambahan. Formulir pendaftaran hanya memuat kolom inti.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-100 dark:border-zinc-850 pt-4 flex justify-end mt-4">
                    <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98]">
                        Simpan Daftar Kolom Kustom
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    // ════════════ 1. State Pelacakan Perubahan (Dirty States) ════════════
    let requirementsDirty = false;
    let formFieldsDirty = false;

    // Track dirty states on user input
    document.getElementById('requirementsForm').addEventListener('input', () => {
        requirementsDirty = true;
    });

    document.getElementById('formFieldsForm').addEventListener('input', () => {
        formFieldsDirty = true;
    });

    // Reset dirty state when forms are successfully submitted
    document.getElementById('requirementsForm').addEventListener('submit', () => {
        requirementsDirty = false;
    });

    document.getElementById('formFieldsForm').addEventListener('submit', () => {
        formFieldsDirty = false;
    });

    // Native browser confirm dialog on page close or reload
    window.addEventListener('beforeunload', function (e) {
        if (requirementsDirty || formFieldsDirty) {
            e.preventDefault();
            e.returnValue = 'Perubahan Anda belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
        }
    });

    // ════════════ 2. Tab Switching Controller ════════════
    function switchTab(tabId) {
        const activeTab = localStorage.getItem('active_ppdb_setting_tab') || 'tab-umum';
        
        // Interrupt tab switching if there are unsaved changes
        if (activeTab === 'tab-persyaratan' && requirementsDirty) {
            if (!confirm('Perubahan pada Persyaratan Berkas belum disimpan. Lanjutkan ke tab lain tanpa menyimpan?')) {
                return;
            }
            requirementsDirty = false; // Reset if user chooses to proceed anyway
        }

        if (activeTab === 'tab-formulir' && formFieldsDirty) {
            if (!confirm('Perubahan pada Pembangun Formulir belum disimpan. Lanjutkan ke tab lain tanpa menyimpan?')) {
                return;
            }
            formFieldsDirty = false; // Reset if user chooses to proceed anyway
        }

        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // Show active tab
        document.getElementById(tabId).classList.remove('hidden');

        // Reset all buttons style
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-[#4f45b2]', 'text-[#4f45b2]', 'dark:text-white');
            btn.classList.add('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        });

        // Set active button style
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        activeBtn.classList.add('border-[#4f45b2]', 'text-[#4f45b2]', 'dark:text-white');
        
        // Save tab state to localStorage
        localStorage.setItem('active_ppdb_setting_tab', tabId);
    }

    // Load active tab on boot
    document.addEventListener("DOMContentLoaded", function() {
        const storedTab = localStorage.getItem('active_ppdb_setting_tab');
        if (storedTab && document.getElementById(storedTab)) {
            switchTab(storedTab);
        }
    });

    // ════════════ 3. Interactive Requirements Table Manager ════════════
    let reqIndexCount = {{ count($requirements) }};

    function addNewRequirementRow() {
        const tableBody = document.getElementById('requirementsTableBody');
        const placeholder = document.getElementById('no-requirements-placeholder');
        if (placeholder) {
            placeholder.remove();
        }

        const newRow = document.createElement('tr');
        newRow.id = `req-row-${reqIndexCount}`;
        newRow.className = 'hover:bg-slate-50/20 transition-all';
        newRow.innerHTML = `
            <td class="px-4 py-3 whitespace-nowrap">
                <input type="text" name="requirements[${reqIndexCount}][id]" id="req-id-input-${reqIndexCount}" required placeholder="e.g. scan_skhun"
                    class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs font-mono text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                    oninput="syncReqSlug(${reqIndexCount})">
            </td>
            <td class="px-4 py-3">
                <input type="text" name="requirements[${reqIndexCount}][label]" id="req-label-input-${reqIndexCount}" required placeholder="e.g. Scan SKHUN SMP/MTs"
                    class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                    oninput="syncReqSlug(${reqIndexCount})">
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
                <select name="requirements[${reqIndexCount}][required]" 
                    class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                    <option value="1">WAJIB (Required)</option>
                    <option value="0">OPSIONAL (Optional)</option>
                </select>
            </td>
            <td class="px-4 py-3 text-right whitespace-nowrap">
                <button type="button" onclick="removeRequirementRow('${reqIndexCount}')" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100/50 rounded-none transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        `;
        tableBody.appendChild(newRow);
        reqIndexCount++;
        requirementsDirty = true; // Mark as modified
    }

    function removeRequirementRow(rowId) {
        const row = document.getElementById(`req-row-${rowId}`);
        if (row) {
            row.remove();
        }
        requirementsDirty = true; // Mark as modified

        const tableBody = document.getElementById('requirementsTableBody');
        if (tableBody.children.length === 0) {
            tableBody.innerHTML = `
                <tr id="no-requirements-placeholder">
                    <td colspan="4" class="text-center py-8 text-slate-400 dark:text-zinc-500 text-xs">Belum ada persyaratan berkas yang didaftarkan.</td>
                </tr>
            `;
        }
    }

    function syncReqSlug(rowId) {
        const label = document.getElementById(`req-label-input-${rowId}`).value;
        const idInput = document.getElementById(`req-id-input-${rowId}`);
        if (idInput && label) {
            const slugged = label.toLowerCase()
                                 .replace(/[^a-z0-9_]+/g, '_')
                                 .replace(/^_+|_+$/g, '');
            idInput.value = slugged;
        }
    }

    // ════════════ 4. Interactive Form Builder Table Manager ════════════
    let fieldIndexCount = {{ count($formFields) }};

    function addNewFormFieldRow() {
        const tableBody = document.getElementById('formFieldsTableBody');
        const placeholder = document.getElementById('no-fields-placeholder');
        if (placeholder) {
            placeholder.remove();
        }

        const newRow = document.createElement('tr');
        newRow.id = `field-row-${fieldIndexCount}`;
        newRow.className = 'hover:bg-slate-50/20 transition-all cursor-move';
        newRow.draggable = true;
        newRow.innerHTML = `
            <!-- Drag Handle -->
            <td class="drag-handle px-2 py-3 text-center whitespace-nowrap text-slate-455 dark:text-zinc-650 cursor-grab active:cursor-grabbing">
                <svg class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </td>
            <!-- ID Slug -->
            <td class="px-4 py-3 whitespace-nowrap">
                <input type="text" name="fields[${fieldIndexCount}][id]" id="field-id-input-${fieldIndexCount}" required placeholder="e.g. pekerjaan_ibu"
                    class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs font-mono text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                    oninput="syncFieldSlug(${fieldIndexCount})">
            </td>
            <!-- Label & Options -->
            <td class="px-4 py-3">
                <div class="space-y-2">
                    <input type="text" name="fields[${fieldIndexCount}][label]" id="field-label-input-${fieldIndexCount}" required placeholder="e.g. Pekerjaan Ibu Kandung"
                        class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                        oninput="syncFieldSlug(${fieldIndexCount})">
                    
                    <div id="field-options-wrapper-${fieldIndexCount}" class="hidden">
                        <span class="text-[10px] font-mono font-bold text-slate-400 dark:text-zinc-500 block mb-1">PILIHAN DROPDOWN (Pisahkan dengan koma):</span>
                        <input type="text" name="fields[${fieldIndexCount}][options]" id="field-options-input-${fieldIndexCount}" placeholder="e.g. Pilihan 1, Pilihan 2, Pilihan 3"
                            class="w-full bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 rounded-none text-xs py-1.5 px-2.5 focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                    </div>
                </div>
            </td>
            <!-- Type -->
            <td class="px-4 py-3 whitespace-nowrap">
                <select name="fields[${fieldIndexCount}][type]" id="field-type-select-${fieldIndexCount}"
                    class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2"
                    onchange="toggleOptionsInputRow(${fieldIndexCount})">
                    <option value="text">Teks Singkat</option>
                    <option value="number">Angka</option>
                    <option value="select">Dropdown Menu</option>
                    <option value="date">Tanggal</option>
                    <option value="textarea">Teks Panjang</option>
                </select>
            </td>
            <!-- Required -->
            <td class="px-4 py-3 whitespace-nowrap">
                <select name="fields[${fieldIndexCount}][required]" 
                    class="bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-xs text-slate-700 dark:text-zinc-300 py-1.5 px-2">
                    <option value="0">OPSIONAL</option>
                    <option value="1">WAJIB</option>
                </select>
            </td>
            <!-- Delete Action -->
            <td class="px-4 py-3 text-right whitespace-nowrap">
                <button type="button" onclick="removeFormFieldRow('${fieldIndexCount}')" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100/50 rounded-none transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        `;
        tableBody.appendChild(newRow);
        addDragListeners(newRow);
        fieldIndexCount++;
        formFieldsDirty = true;
    }

    function removeFormFieldRow(rowId, fieldLabel = '') {
        const title = fieldLabel ? `Hapus ${fieldLabel}` : 'Hapus Kolom';
        const desc = fieldLabel 
            ? `Apakah Anda yakin ingin menghapus kolom kustom <strong>${fieldLabel}</strong> dari pendaftaran? Semua isian calon siswa pada kolom ini akan hilang setelah form disimpan.` 
            : 'Apakah Anda yakin ingin menghapus kolom kustom ini?';

        AppPopup.confirm({
            title: title,
            description: desc,
            confirmText: 'Ya, Hapus',
            cancelText: 'Batal',
            onConfirm: () => {
                const row = document.getElementById(`field-row-${rowId}`);
                if (row) {
                    row.remove();
                }
                formFieldsDirty = true; // Mark as modified
                reindexFormFields();

                const tableBody = document.getElementById('formFieldsTableBody');
                if (tableBody.children.length === 0) {
                    tableBody.innerHTML = `
                        <tr id="no-fields-placeholder">
                            <td colspan="6" class="text-center py-10 text-slate-400 dark:text-zinc-500 text-xs">Belum ada kolom kustom tambahan. Formulir pendaftaran hanya memuat kolom inti.</td>
                        </tr>
                    `;
                }
            }
        });
    }

    function syncFieldSlug(rowId) {
        const label = document.getElementById(`field-label-input-${rowId}`).value;
        const idInput = document.getElementById(`field-id-input-${rowId}`);
        if (idInput && label) {
            const slugged = label.toLowerCase()
                                 .replace(/[^a-z0-9_]+/g, '_')
                                 .replace(/^_+|_+$/g, '');
            idInput.value = slugged;
        }
    }

    function toggleOptionsInputRow(rowId) {
        const typeSelect = document.getElementById(`field-type-select-${rowId}`);
        const optionsWrapper = document.getElementById(`field-options-wrapper-${rowId}`);
        const optionsInput = document.getElementById(`field-options-input-${rowId}`);
        
        if (typeSelect && optionsWrapper && optionsInput) {
            if (typeSelect.value === 'select') {
                optionsWrapper.classList.remove('hidden');
                optionsInput.required = true;
            } else {
                optionsWrapper.classList.add('hidden');
                optionsInput.required = false;
                optionsInput.value = '';
            }
        }
    }

    function reindexFormFields() {
        const rows = document.querySelectorAll('#formFieldsTableBody tr');
        let idx = 0;
        rows.forEach(row => {
            if (row.id === 'no-fields-placeholder') return;
            
            row.id = `field-row-${idx}`;
            
            const idInput = row.querySelector('input[name$="[id]"]');
            if (idInput) {
                idInput.name = `fields[${idx}][id]`;
                idInput.id = `field-id-input-${idx}`;
                idInput.setAttribute('oninput', `syncFieldSlug(${idx})`);
            }
            
            const labelInput = row.querySelector('input[name$="[label]"]');
            if (labelInput) {
                labelInput.name = `fields[${idx}][label]`;
                labelInput.id = `field-label-input-${idx}`;
                labelInput.setAttribute('oninput', `syncFieldSlug(${idx})`);
            }
            
            const typeSelect = row.querySelector('select[name$="[type]"]');
            if (typeSelect) {
                typeSelect.name = `fields[${idx}][type]`;
                typeSelect.id = `field-type-select-${idx}`;
                typeSelect.setAttribute('onchange', `toggleOptionsInputRow(${idx})`);
            }
            
            const requiredSelect = row.querySelector('select[name$="[required]"]');
            if (requiredSelect) {
                requiredSelect.name = `fields[${idx}][required]`;
            }
            
            const optionsInput = row.querySelector('input[name$="[options]"]');
            if (optionsInput) {
                optionsInput.name = `fields[${idx}][options]`;
                optionsInput.id = `field-options-input-${idx}`;
            }
            
            const optionsWrapper = row.querySelector('[id^="field-options-wrapper-"]');
            if (optionsWrapper) {
                optionsWrapper.id = `field-options-wrapper-${idx}`;
            }
            
            const deleteBtn = row.querySelector('button[onclick^="removeFormFieldRow"]');
            if (deleteBtn) {
                const labelVal = labelInput ? labelInput.value.replace(/'/g, "\\'") : '';
                deleteBtn.setAttribute('onclick', `removeFormFieldRow(${idx}, '${labelVal}')`);
            }
            
            idx++;
        });
        fieldIndexCount = idx;
    }

    // Drag and Drop implementation for Form Fields Table Rows
    let dragSrcEl = null;

    function addDragListeners(row) {
        row.addEventListener('dragstart', handleDragStart, false);
        row.addEventListener('dragover', handleDragOver, false);
        row.addEventListener('dragenter', handleDragEnter, false);
        row.addEventListener('dragleave', handleDragLeave, false);
        row.addEventListener('drop', handleDrop, false);
        row.addEventListener('dragend', handleDragEnd, false);
    }

    function handleDragStart(e) {
        const handle = e.target.closest('td');
        if (!handle || !handle.classList.contains('drag-handle')) {
            e.preventDefault();
            return;
        }
        this.classList.add('opacity-40', 'bg-slate-100', 'dark:bg-zinc-800');
        dragSrcEl = this;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.outerHTML);
    }

    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        this.classList.add('border-t-2', 'border-[#4f45b2]');
        return false;
    }

    function handleDragEnter(e) {
        // Already handled by dragover styling
    }

    function handleDragLeave(e) {
        this.classList.remove('border-t-2', 'border-[#4f45b2]');
    }

    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        
        this.classList.remove('border-t-2', 'border-[#4f45b2]');
        
        if (dragSrcEl !== this) {
            const list = Array.from(this.parentNode.children);
            const dragIdx = list.indexOf(dragSrcEl);
            const targetIdx = list.indexOf(this);
            
            if (dragIdx < targetIdx) {
                this.parentNode.insertBefore(dragSrcEl, this.nextSibling);
            } else {
                this.parentNode.insertBefore(dragSrcEl, this);
            }
            
            reindexFormFields();
            formFieldsDirty = true;
        }
        return false;
    }

    function handleDragEnd(e) {
        this.classList.remove('opacity-40', 'bg-slate-100', 'dark:bg-zinc-800');
        document.querySelectorAll('#formFieldsTableBody tr').forEach(row => {
            row.classList.remove('border-t-2', 'border-[#4f45b2]');
        });
    }

    // Initialize drag events for existing rows
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('#formFieldsTableBody tr').forEach(row => {
            if (row.id !== 'no-fields-placeholder') {
                addDragListeners(row);
            }
        });
    });
</script>
@endsection
