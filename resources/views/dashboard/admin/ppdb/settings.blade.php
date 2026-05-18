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
                <h3 class="text-sm font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8]">
                    Daftar Berkas Syarat Unggah
                </h3>
                <button type="button" onclick="addNewRequirementRow()" class="py-1 px-3 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-slate-700 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-mono font-bold text-[10px] uppercase tracking-wider rounded-none">
                    + Tambah Berkas Baru
                </button>
            </div>

            <p class="text-xs text-slate-400 dark:text-zinc-500 mb-6">Kelola dokumen scan yang wajib diunggah oleh calon siswa baru pada formulir pendaftaran.</p>

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

        <!-- Custom Fields Workspace -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-850 pb-3 mb-5">
                <h3 class="text-sm font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8]">
                    Daftar Kolom Kustom Dinamis
                </h3>
                <button type="button" onclick="openAddFieldModal()" class="py-1 px-3 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-mono font-bold text-[10px] uppercase tracking-wider rounded-none transition-all active:scale-[.98]">
                    + Tambah Kolom Kustom
                </button>
            </div>

            <!-- Custom Fields List -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-zinc-800/40 border-b border-slate-100 dark:border-zinc-800/80">
                            <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">ID Slug</th>
                            <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Label Input</th>
                            <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Tipe Data</th>
                            <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Sifat</th>
                            <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Keterangan Opsi</th>
                            <th class="px-4 py-3.5 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/50">
                        @forelse($formFields as $field)
                            <tr class="hover:bg-slate-50/20 transition-all">
                                <td class="px-4 py-4 text-xs font-mono text-[#4f45b2] dark:text-[#8c84c8] font-bold">
                                    {{ $field['id'] }}
                                </td>
                                <td class="px-4 py-4 text-sm font-semibold text-slate-800 dark:text-white">
                                    {{ $field['label'] }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-0.5 text-[9px] font-mono font-bold bg-indigo-50 dark:bg-indigo-950/20 text-[#4f45b2] dark:text-[#8c84c8] border border-indigo-100 dark:border-indigo-900/30 uppercase rounded-none">
                                        {{ $field['type'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if($field['required'])
                                        <span class="text-xs font-semibold text-red-600 dark:text-red-400">Wajib Diisi</span>
                                    @else
                                        <span class="text-xs text-slate-400 dark:text-zinc-500">Opsional</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-500 dark:text-zinc-400 max-w-[200px] truncate">
                                    @if(!empty($field['options']))
                                        {{ implode(', ', $field['options']) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right whitespace-nowrap text-sm">
                                    <!-- Delete Field Form -->
                                    <form action="{{ route('admin.ppdb.settings.fields.destroy', $field['id']) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="confirmFieldDelete(event, '{{ $field['label'] }}')"
                                            class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100/50 rounded-none font-bold text-[10px] transition-all">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-slate-400 dark:text-zinc-500 text-xs">Belum ada kolom kustom tambahan. Formulir pendaftaran hanya memuat kolom inti.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- Modal Dialog: Tambah Field Baru -->
<div id="addFieldModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs transition-opacity" onclick="closeAddFieldModal()"></div>
        
        <div class="inline-block w-full max-w-md bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-2xl p-6 text-left transform transition-all rounded-none relative z-10">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Tambah Kolom Input Kustom</h3>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mb-4">Input baru ini akan langsung ditambahkan ke dalam formulir pendaftaran online siswa dan disimpan sebagai berkas digital tambahan.</p>
            
            <form action="{{ route('admin.ppdb.settings.fields.store') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Label -->
                <div>
                    <label for="label" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1">Label Pertanyaan / Input</label>
                    <input type="text" name="label" id="label" required placeholder="Contoh: Pekerjaan Ibu / Hobi Calon Siswa"
                        class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1">Tipe Kolom Input</label>
                    <select name="type" id="type" onchange="toggleOptionsInput()" required
                        class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                        <option value="text">Teks Singkat (Text)</option>
                        <option value="number">Angka (Number)</option>
                        <option value="select">Pilihan Ganda / Dropdown (Select)</option>
                        <option value="date">Tanggal (Date)</option>
                        <option value="textarea">Deskripsi / Teks Panjang (Textarea)</option>
                    </select>
                </div>

                <!-- Dropdown options -->
                <div id="options-field" class="hidden">
                    <label for="options" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1">Pilihan Menu Dropdown (Pisahkan dengan Koma)</label>
                    <input type="text" name="options" id="options" placeholder="Contoh: Pilihan 1, Pilihan 2, Pilihan 3"
                        class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                </div>

                <!-- Required Toggle -->
                <div>
                    <label for="required" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1">Sifat Input</label>
                    <select name="required" id="required"
                        class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                        <option value="0">OPSIONAL (Optional)</option>
                        <option value="1">WAJIB DIISI (Required)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2.5 pt-2">
                    <button type="button" onclick="closeAddFieldModal()" class="flex-1 py-2.5 px-4 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-300 font-bold text-xs rounded-none transition-all active:scale-[.98]">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all active:scale-[.98]">
                        Tambah & Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // ════════════ 1. Tab Switching Controller ════════════
    function switchTab(tabId) {
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

    // ════════════ 2. Interactive Requirements Table Manager ════════════
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
    }

    function removeRequirementRow(rowId) {
        const row = document.getElementById(`req-row-${rowId}`);
        if (row) {
            row.remove();
        }

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
            // Slugify the label into camel_case/snake_case
            const slugged = label.toLowerCase()
                                 .replace(/[^a-z0-9_]+/g, '_')
                                 .replace(/^_+|_+$/g, '');
            idInput.value = slugged;
        }
    }

    // ════════════ 3. Form Builder Modal & Options ════════════
    function openAddFieldModal() {
        document.getElementById('addFieldModal').classList.remove('hidden');
    }

    function closeAddFieldModal() {
        document.getElementById('addFieldModal').classList.add('hidden');
    }

    function toggleOptionsInput() {
        const typeSelect = document.getElementById('type');
        const optionsField = document.getElementById('options-field');
        const optionsInput = document.getElementById('options');

        if (typeSelect.value === 'select') {
            optionsField.classList.remove('hidden');
            optionsInput.required = true;
        } else {
            optionsField.classList.add('hidden');
            optionsInput.required = false;
            optionsInput.value = '';
        }
    }

    function confirmFieldDelete(e, label) {
        e.preventDefault();
        const form = e.target.closest('form');
        
        AppPopup.confirm({
            title: 'Hapus Kolom Kustom',
            description: `Apakah Anda yakin ingin menghapus kolom kustom <strong>${label}</strong>? Seluruh data isian berkas yang telah dikirim calon siswa pada kolom ini akan hilang!`,
            confirmText: 'Ya, Hapus',
            cancelText: 'Batal',
            onConfirm: () => {
                form.submit();
            }
        });
    }
</script>
@endsection
