@extends('dashboard.layouts.main')

@section('content')
<!-- Load Alpine.js CDN for Page-Specific Modern Interactive Tabs -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Google Sheets Integration';
        }
    });
</script>

<div class="space-y-6">

    <!-- Header Panel -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Integrasi Google Sheets</h1>
            <p class="text-sm text-slate-500 dark:text-zinc-400 mt-1">Hubungkan basis data PPDB MAM Limpung secara dinamis dengan Google Sheets API secara aman.</p>
        </div>
        <a href="{{ route('admin.ppdb.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center">
            Kembali ke Pendaftar
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/60 p-4 text-emerald-800 dark:text-emerald-400 text-xs font-semibold flex items-center gap-3 animate-fadeIn">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Settings Form & Operations -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Connection Status Dashboard -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm">
                <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-100 dark:border-zinc-850 pb-3 mb-4">
                    Status Integrasi API
                </h3>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <!-- Icon Badge -->
                        <div id="status-badge-icon" class="p-3 bg-amber-50 dark:bg-amber-950/20 text-amber-500 rounded-none flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-mono font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider block">Status Koneksi</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span id="status-title" class="text-base font-extrabold text-amber-600 dark:text-amber-500">Mengecek Koneksi...</span>
                                <span id="status-dot" class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                            </div>
                            <p id="status-desc" class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Mengautentikasi kredensial Google API...</p>
                        </div>
                    </div>

                    <!-- Instant Action Panel -->
                    <div class="flex flex-wrap gap-2.5 w-full sm:w-auto">
                        <button type="button" id="btn-test-connection" class="flex-1 sm:flex-initial py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all flex items-center justify-center gap-2">
                            <svg id="icon-test-conn" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span id="label-test-conn">Uji Koneksi</span>
                        </button>
                        
                        <button type="button" id="btn-sync-all" class="flex-1 sm:flex-initial py-2 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:pointer-events-none">

                            <svg id="icon-sync-all" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9"/>
                                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z"/>
                            </svg>
                            <span id="label-sync-all">Sinkronisasi Sekarang</span>
                        </button>
                    </div>
                </div>

                <!-- Live Error Message Block -->
                <div id="error-block" class="hidden mt-4 p-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/60 rounded-none text-red-800 dark:text-red-400 text-xs font-mono tracking-tight leading-relaxed animate-fadeIn">
                    <!-- Error text injected here -->
                </div>
            </div>

            <!-- Configuration Form Card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm">
                <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-100 dark:border-zinc-850 pb-3 mb-6">
                    Form Pengaturan Google Sheets
                </h3>

                <form id="google-sheets-form" action="{{ route('admin.ppdb.google-sheets.update') }}" method="POST" class="space-y-6" x-data="{ activeTab: 'connection' }">
                    @csrf

                    <!-- Toggle: Enable Integration -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-850/40 border border-slate-200 dark:border-zinc-800">
                        <div>
                            <label for="is_enabled" class="text-sm font-bold text-slate-800 dark:text-white block cursor-pointer">Aktifkan Integrasi Otomatis</label>
                            <span class="text-xs text-slate-400 dark:text-zinc-500 block mt-0.5">Jika aktif, data calon siswa akan dikirim ke Google Sheets secara real-time saat status kelulusan diubah.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" name="is_enabled" id="is_enabled" value="1" {{ $settings['is_enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 dark:bg-zinc-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#4f45b2]"></div>
                        </label>
                    </div>

                    <!-- Tab Buttons Navigation -->
                    <div class="flex flex-wrap border-b border-slate-100 dark:border-zinc-800 gap-1">
                        <button type="button" @click="activeTab = 'connection'" :class="activeTab === 'connection' ? 'border-[#4f45b2] text-[#4f45b2] border-b-2 font-bold' : 'border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-600 dark:hover:text-zinc-300'" class="px-4 py-2.5 text-xs uppercase tracking-wider font-mono transition-all">
                            1. Koneksi
                        </button>
                        <button type="button" @click="activeTab = 'structure'" :class="activeTab === 'structure' ? 'border-[#4f45b2] text-[#4f45b2] border-b-2 font-bold' : 'border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-600 dark:hover:text-zinc-300'" class="px-4 py-2.5 text-xs uppercase tracking-wider font-mono transition-all">
                            2. Tab Sheet
                        </button>
                        <button type="button" @click="activeTab = 'columns'" :class="activeTab === 'columns' ? 'border-[#4f45b2] text-[#4f45b2] border-b-2 font-bold' : 'border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-600 dark:hover:text-zinc-300'" class="px-4 py-2.5 text-xs uppercase tracking-wider font-mono transition-all">
                            3. Kolom Data
                        </button>
                        <button type="button" @click="activeTab = 'design'" :class="activeTab === 'design' ? 'border-[#4f45b2] text-[#4f45b2] border-b-2 font-bold' : 'border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-600 dark:hover:text-zinc-300'" class="px-4 py-2.5 text-xs uppercase tracking-wider font-mono transition-all">
                            4. Desain
                        </button>
                    </div>

                    <!-- Tab Contents -->
                    <div class="space-y-6 mt-4">

                        <!-- Tab 1: Connection Settings -->
                        <div x-show="activeTab === 'connection'" class="space-y-6 animate-fadeIn">
                            <!-- Spreadsheet ID -->
                            <div>
                                <label for="spreadsheet_id" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Google Spreadsheet ID</label>
                                <input type="text" name="spreadsheet_id" id="spreadsheet_id" value="{{ old('spreadsheet_id', $settings['spreadsheet_id']) }}" required class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] font-mono" placeholder="Contoh: 1aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890">
                                @error('spreadsheet_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Service Account Credentials (JSON) -->
                            <div>
                                <label for="service_account_json" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Kredensial Service Account (format JSON)</label>
                                <textarea name="service_account_json" id="service_account_json" rows="8" class="w-full py-2.5 px-3 text-xs bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2] font-mono" placeholder="Tempel/paste isi file JSON Service Account Anda di sini secara utuh...">{{ old('service_account_json', $maskedJson) }}</textarea>
                                <div class="mt-2 flex items-center justify-between">
                                    <p class="text-[11px] text-slate-400 dark:text-zinc-500">
                                        @if($hasCredentials)
                                        <span class="text-emerald-600 dark:text-emerald-500 font-semibold">Kredensial sudah tersimpan dengan sangat aman (Terenkripsi).</span> Kosongkan atau biarkan teks bertanda aman jika tidak ingin mengubahnya.
                                        @else
                                        <span class="text-amber-600 dark:text-amber-500 font-semibold">Kredensial belum dikonfigurasi.</span> Tempelkan file JSON Anda untuk memulai.
                                        @endif
                                    </p>
                                    <button type="button" onclick="clearJsonTextarea()" class="text-[9px] font-mono font-bold uppercase tracking-wide text-red-500 hover:underline">Hapus Input</button>
                                </div>
                                @error('service_account_json')
                                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tab 2: Tab Structure & Custom Sheet Names -->
                        <div x-show="activeTab === 'structure'" class="space-y-6 animate-fadeIn" style="display: none;">
                            <!-- Split Tab Mode Toggle -->
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-850/40 border border-slate-200 dark:border-zinc-800">
                                <div>
                                    <label for="split_by_status" class="text-sm font-bold text-slate-800 dark:text-white block cursor-pointer">Pisahkan Berdasarkan Status Seleksi (Multi-Tab)</label>
                                    <span class="text-xs text-slate-400 dark:text-zinc-500 block mt-0.5">Jika aktif, data calon siswa otomatis dipisah ke tab lembar kerja terpisah sesuai kelulusan mereka.</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" name="split_by_status" id="split_by_status" value="1" {{ $settings['split_by_status'] ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 dark:bg-zinc-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#4f45b2]"></div>
                                </label>
                            </div>

                            <!-- Active Sheets Toggle Checklist -->
                            <div>
                                <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-3">Pilih Lembar Kerja (Tab/Sheet) yang Ingin Ditampilkan</label>
                                
                                <div class="space-y-4">
                                    @php
                                        $availableSheets = [
                                            'semua' => ['label' => 'Semua Pendaftar (Seluruh Calon Siswa)', 'desc' => 'Menampilkan daftar lengkap seluruh siswa yang mendaftar.'],
                                            'diterima' => ['label' => 'Siswa Diterima', 'desc' => 'Menampilkan daftar khusus calon siswa yang dinyatakan diterima.'],
                                            'pending' => ['label' => 'Dalam Proses (Seleksi)', 'desc' => 'Menampilkan daftar calon siswa yang status seleksinya masih ditinjau.'],
                                            'ditolak' => ['label' => 'Siswa Ditolak', 'desc' => 'Menampilkan daftar calon siswa yang dinyatakan ditolak.'],
                                            'ringkasan' => ['label' => 'Ringkasan Data & Statistik (Summary Dashboard)', 'desc' => 'Menampilkan statistik pendaftaran, profil rasio gender, dan sekolah asal terpopuler secara visual.'],
                                        ];
                                    @endphp

                                    @foreach($availableSheets as $key => $sheetInfo)
                                    <div class="p-4 bg-slate-50 dark:bg-zinc-850/40 border border-slate-200 dark:border-zinc-800/80 flex items-start gap-4">
                                        <input type="checkbox" name="active_sheets[]" value="{{ $key }}" id="sheet_active_{{ $key }}" {{ in_array($key, $settings['active_sheets'] ?? []) ? 'checked' : '' }} class="mt-1 rounded-none text-[#4f45b2] focus:ring-[#4f45b2]/40 border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-850 w-4 h-4">
                                        <div class="flex-1">
                                            <label for="sheet_active_{{ $key }}" class="text-xs font-bold text-slate-800 dark:text-white cursor-pointer block">{{ $sheetInfo['label'] }}</label>
                                            <span class="text-[11px] text-slate-400 dark:text-zinc-500 mt-0.5 block leading-relaxed">{{ $sheetInfo['desc'] }}</span>
                                            
                                            <!-- Custom Sheet Name Input -->
                                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <div>
                                                    <label class="text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1">Nama Tab di Google Sheet</label>
                                                    <input type="text" name="sheet_names[{{ $key }}]" value="{{ old('sheet_names.'.$key, $settings['sheet_names'][$key] ?? '') }}" placeholder="Contoh: {{ $settings['sheet_names'][$key] ?? '' }}" class="w-full py-1.5 px-3 text-xs bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Tab 3: Column Fields -->
                        <div x-show="activeTab === 'columns'" class="space-y-6 animate-fadeIn" style="display: none;">
                            <div>
                                <label class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-3">Kolom Data yang Disinkronkan</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @php
                                        $fieldsList = [
                                            'no_registrasi' => 'No. Registrasi',
                                            'nama_lengkap' => 'Nama Lengkap',
                                            'nisn' => 'NISN',
                                            'jenis_kelamin' => 'Jenis Kelamin',
                                            'sekolah_asal' => 'Sekolah Asal',
                                            'no_hp' => 'No. HP / WA',
                                            'email' => 'Email',
                                            'status' => 'Status Seleksi',
                                            'tanggal_daftar' => 'Tanggal Daftar',
                                            'custom_fields' => 'Formulir Kustom Tambahan'
                                        ];
                                    @endphp
                                    @foreach($fieldsList as $key => $label)
                                    <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-zinc-850/40 border border-slate-200 dark:border-zinc-800 cursor-pointer hover:bg-slate-100/50 dark:hover:bg-zinc-800/40 transition-all select-none">
                                        <input type="checkbox" name="sync_fields[]" value="{{ $key }}" {{ in_array($key, $settings['sync_fields'] ?? []) ? 'checked' : '' }} class="rounded-none text-[#4f45b2] focus:ring-[#4f45b2]/40 border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 w-4 h-4">
                                        <span class="text-xs font-bold text-slate-700 dark:text-zinc-300">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @error('sync_fields')
                                <p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tab 4: Design & Theme -->
                        <div x-show="activeTab === 'design'" class="space-y-6 animate-fadeIn" style="display: none;">
                            <!-- Header Style Selection -->
                            <div>
                                <label for="header_style" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Gaya Tampilan Header Google Sheet</label>
                                <select name="header_style" id="header_style" class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                                    <option value="purple" {{ $settings['header_style'] === 'purple' ? 'selected' : '' }}>Ungu Premium (Tema Almamater MAM)</option>
                                    <option value="emerald" {{ $settings['header_style'] === 'emerald' ? 'selected' : '' }}>Hijau Emerald Akademik</option>
                                    <option value="dark" {{ $settings['header_style'] === 'dark' ? 'selected' : '' }}>Abu Gelap Charcoal / Mewah</option>
                                    <option value="plain" {{ $settings['header_style'] === 'plain' ? 'selected' : '' }}>Sederhana (Tanpa Warna Latar)</option>
                                </select>
                            </div>
                            
                            <div class="p-4 bg-slate-50 dark:bg-zinc-850/40 border border-slate-200 dark:border-zinc-800">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-white block mb-2">Tips Tampilan Google Sheets Mewah</h4>
                                <ul class="list-disc list-inside text-xs text-slate-400 dark:text-zinc-500 space-y-1.5 leading-relaxed">
                                    <li>Sistem otomatis membekukan baris pertama (frozen row) agar judul kolom tetap di atas saat Anda melakukan scroll.</li>
                                    <li>Sistem otomatis melebarkan kolom sesuai panjang data (auto-fit columns) sehingga tampilan tabel rapi dan tidak terpotong.</li>
                                    <li>Gaya warna sel diselaraskan penuh dengan pilihan tema yang Anda pilih untuk semua tab lembar kerja yang aktif.</li>
                                </ul>
                            </div>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="border-t border-slate-100 dark:border-zinc-850 pt-5 flex justify-end">
                        <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.97] shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <span>Simpan Pengaturan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right 1 Col: GCP Guide & Info -->
        <div class="space-y-6">
            
            <!-- Secure Metadata Info Card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-5 shadow-sm">
                <h4 class="text-xs font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-100 dark:border-zinc-850 pb-2 mb-3">
                    Info Kredensial Aktif
                </h4>
                <div class="space-y-3">
                    <div>
                        <span class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-wider">Email Akun Layanan</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span id="active-email-badge" class="px-2 py-1 bg-slate-100 dark:bg-zinc-800 text-[10px] font-mono text-slate-700 dark:text-zinc-300 font-bold block truncate max-w-full" title="{{ $clientEmail }}">
                                {{ $clientEmail }}
                            </span>
                            @if($hasCredentials && $clientEmail !== '-')
                            <button onclick="copyToClipboard('{{ $clientEmail }}')" class="text-[#4f45b2] dark:text-[#8c84c8] hover:text-[#4f45b2]/75 flex-shrink-0" title="Salin Email">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-wider">Proteksi Keamanan</span>
                        <div class="flex items-center gap-2 mt-1 text-xs text-slate-600 dark:text-zinc-400 leading-normal">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Enkripsi <strong>AES-256-CBC</strong> didukung Laravel Native Crypt. Kredensial Anda 100% terlindung dari eksploitasi kebocoran database.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Beautiful GCP Implementation Tutorial Box (Full Width below settings) -->
    <div class="bg-slate-900 text-white border border-slate-800 p-6 shadow-xl relative overflow-hidden group">
        <!-- Sleek abstract geometric background glow -->
        <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#4f45b2]/10 rounded-full blur-3xl group-hover:bg-[#4f45b2]/20 transition-all duration-500"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl"></div>

        <div class="relative">
            <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-zinc-800">
                <svg class="w-5 h-5 text-[#8c84c8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h4 class="text-sm font-mono font-bold uppercase tracking-widest text-[#8c84c8]">
                    Panduan Konfigurasi Google Cloud & Google Sheets
                </h4>
            </div>

            <!-- Steps List: Going Downwards Vertically -->
            <div class="flex flex-col space-y-4 text-xs leading-relaxed text-zinc-300 font-sans mt-4">
                <!-- Step 1 -->
                <div class="p-4 bg-slate-950/40 border border-zinc-800/80 flex items-start gap-4">
                    <span class="w-6 h-6 bg-zinc-800 text-zinc-300 font-mono text-xs font-bold rounded-none flex items-center justify-center flex-shrink-0">1</span>
                    <div>
                        <strong class="text-white text-sm block mb-1">Buat Project Google Cloud Platform (GCP)</strong>
                        <p class="text-zinc-400 text-[11px] leading-relaxed">
                            Masuk ke <a href="https://console.cloud.google.com/" target="_blank" class="text-[#8c84c8] hover:underline font-bold">GCP Console</a> dan buat proyek baru untuk sekolah Anda.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="p-4 bg-slate-950/40 border border-zinc-800/80 flex items-start gap-4">
                    <span class="w-6 h-6 bg-zinc-800 text-zinc-300 font-mono text-xs font-bold rounded-none flex items-center justify-center flex-shrink-0">2</span>
                    <div>
                        <strong class="text-white text-sm block mb-1">Aktifkan API Google</strong>
                        <p class="text-zinc-400 text-[11px] leading-relaxed">
                            Cari di menu pencarian GCP untuk Google Sheets API dan Google Drive API, kemudian aktifkan keduanya secara berurutan.
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="p-4 bg-slate-950/40 border border-zinc-800/80 flex items-start gap-4">
                    <span class="w-6 h-6 bg-zinc-800 text-zinc-300 font-mono text-xs font-bold rounded-none flex items-center justify-center flex-shrink-0">3</span>
                    <div>
                        <strong class="text-white text-sm block mb-1">Buat Service Account</strong>
                        <p class="text-zinc-400 text-[11px] leading-relaxed">
                            Buka APIs & Services > Credentials, pilih Create Credentials > Service Account. Tambahkan Key baru bertipe JSON, lalu file kredensial JSON akan otomatis diunduh ke komputer Anda.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="p-4 bg-slate-950/40 border border-zinc-800/80 flex items-start gap-4">
                    <span class="w-6 h-6 bg-zinc-800 text-zinc-300 font-mono text-xs font-bold rounded-none flex items-center justify-center flex-shrink-0">4</span>
                    <div class="flex-1">
                        <strong class="text-white text-sm block mb-1">Undang Ke Spreadsheet</strong>
                        <p class="text-zinc-400 text-[11px] leading-relaxed mb-2">
                            Bagikan Google Sheet tujuan Anda ke email akun layanan ini sebagai Editor:
                        </p>
                        @if($hasCredentials && $clientEmail !== '-')
                        <div class="flex items-center gap-1.5 bg-zinc-850 p-1.5 border border-zinc-850 mt-1 max-w-md">
                            <span class="font-mono text-[9px] text-[#8c84c8] truncate block flex-1" id="copy-email-tutorial">{{ $clientEmail }}</span>
                            <button type="button" onclick="copyToClipboard('{{ $clientEmail }}')" class="text-zinc-500 hover:text-white" title="Salin Email">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </button>
                        </div>
                        @else
                        <span class="text-amber-500 italic block mt-0.5">Simpan kredensial terlebih dahulu untuk melihat email akun layanan</span>
                        @endif
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="p-4 bg-slate-950/40 border border-zinc-800/80 flex items-start gap-4">
                    <span class="w-6 h-6 bg-zinc-800 text-zinc-300 font-mono text-xs font-bold rounded-none flex items-center justify-center flex-shrink-0">5</span>
                    <div>
                        <strong class="text-white text-sm block mb-1">Tempel & Simpan</strong>
                        <p class="text-zinc-400 text-[11px] leading-relaxed">
                            Buka file JSON terunduh tadi, salin isinya secara penuh, lalu tempelkan ke kolom form Kredensial Service Account di atas dan klik Simpan Pengaturan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-allert />

<script>
    function clearJsonTextarea() {
        const textarea = document.getElementById('service_account_json');
        if (textarea) {
            textarea.value = '';
            textarea.focus();
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            if (window.AppPopup) {
                AppPopup.success({
                    title: 'Berhasil Disalin',
                    description: 'Email Akun Layanan berhasil disalin ke clipboard.'
                });
            } else {
                alert('Email Service Account berhasil disalin ke clipboard!');
            }
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const btnTest = document.getElementById('btn-test-connection');
        const btnSync = document.getElementById('btn-sync-all');
        
        const statusBadgeIcon = document.getElementById('status-badge-icon');
        const statusTitle = document.getElementById('status-title');
        const statusDot = document.getElementById('status-dot');
        const statusDesc = document.getElementById('status-desc');
        const errorBlock = document.getElementById('error-block');

        // Function to update status panel visually
        function updateStatusUI(status, details = '', email = '') {
            errorBlock.classList.add('hidden');
            errorBlock.textContent = '';
            
            if (status === 'connected') {
                statusBadgeIcon.className = "p-3 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500 rounded-none flex-shrink-0";
                statusTitle.className = "text-base font-extrabold text-emerald-600 dark:text-emerald-500";
                statusTitle.textContent = "Terhubung (Connected)";
                statusDot.className = "w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse";
                statusDesc.textContent = "Koneksi ke Google Sheets API aktif & siap digunakan.";
                if (email) {
                    const badge = document.getElementById('active-email-badge');
                    if (badge) {
                        badge.textContent = email;
                        badge.title = email;
                    }
                }
                btnSync.disabled = false;
            } else if (status === 'unconfigured') {
                statusBadgeIcon.className = "p-3 bg-amber-50 dark:bg-amber-950/20 text-amber-500 rounded-none flex-shrink-0";
                statusTitle.className = "text-base font-extrabold text-amber-600 dark:text-amber-500";
                statusTitle.textContent = "Belum Dikonfigurasi";
                statusDot.className = "w-2.5 h-2.5 rounded-full bg-amber-500";
                statusDesc.textContent = "Spreadsheet ID atau file kredensial JSON belum diatur.";
                btnSync.disabled = true;
            } else {
                statusBadgeIcon.className = "p-3 bg-red-50 dark:bg-red-950/20 text-red-500 rounded-none flex-shrink-0";
                statusTitle.className = "text-base font-extrabold text-red-600 dark:text-red-500";
                statusTitle.textContent = "Koneksi Terputus / Gagal";
                statusDot.className = "w-2.5 h-2.5 rounded-full bg-red-500";
                statusDesc.textContent = "Gagal memverifikasi akses Google API.";
                
                if (details) {
                    errorBlock.textContent = details;
                    errorBlock.classList.remove('hidden');
                }
                btnSync.disabled = true;
            }
        }

        // Run initial connection test on page load (checks 24h cache first)
        function runInitialTest() {
            fetch("{{ route('admin.ppdb.google-sheets.test') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateStatusUI('connected', '', data.client_email);
                } else {
                    // Check if actually unconfigured
                    const spreadsheetInput = document.getElementById('spreadsheet_id').value;
                    if (!spreadsheetInput || data.message.includes('Konfigurasi Google Sheets kosong')) {
                        updateStatusUI('unconfigured');
                    } else {
                        updateStatusUI('failed', data.message);
                    }
                }
            })
            .catch(err => {
                updateStatusUI('failed', 'Gagal menghubungi server web lokal: ' + err.message);
            });
        }

        runInitialTest();

        // Uji Koneksi Button Handler (forces real-time connection test bypassing cache)
        btnTest.addEventListener('click', function() {
            // Put into loading state
            btnTest.disabled = true;
            const icon = document.getElementById('icon-test-conn');
            const label = document.getElementById('label-test-conn');
            label.textContent = 'Menguji Koneksi...';
            icon.classList.add('animate-spin');

            statusTitle.textContent = 'Mencoba Menghubungkan...';
            statusDot.className = 'w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse';

            fetch("{{ route('admin.ppdb.google-sheets.test') }}?force=1", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateStatusUI('connected', '', data.client_email);
                    if (window.AppPopup) {
                        AppPopup.success({
                            title: 'Koneksi Sukses',
                            description: 'Koneksi sukses! Konfigurasi API Google Sheets valid dan siap digunakan.'
                        });
                    } else {
                        alert('Koneksi sukses! Konfigurasi API Google Sheets valid.');
                    }
                } else {
                    updateStatusUI('failed', data.message);
                    if (window.AppPopup) {
                        AppPopup.error({
                            title: 'Koneksi Gagal',
                            description: 'Koneksi gagal! Silakan periksa detail log kesalahan di layar.'
                        });
                    } else {
                        alert('Koneksi gagal! Silakan periksa detail log eror di layar.');
                    }
                }
            })
            .catch(err => {
                updateStatusUI('failed', err.message);
                if (window.AppPopup) {
                    AppPopup.error({
                        title: 'Kesalahan Sistem',
                        description: 'Gagal memproses uji koneksi karena kesalahan jaringan.'
                    });
                } else {
                    alert('Eror AJAX: Gagal memproses uji koneksi.');
                }
            })
            .finally(() => {
                btnTest.disabled = false;
                label.textContent = 'Uji Koneksi';
                icon.classList.remove('animate-spin');
            });
        });

        // Sinkronisasi Sekarang Button Handler using beautiful custom confirmation
        btnSync.addEventListener('click', function() {
            if (window.AppPopup) {
                AppPopup.warning({
                    title: 'Konfirmasi Sinkronisasi',
                    description: 'Apakah Anda yakin ingin menyinkronkan total seluruh data pendaftar saat ini ke Google Sheets? Tindakan ini akan mengosongkan sheet dan menulis ulang data terbaru.',
                    confirmText: 'Ya, Lanjutkan',
                    cancelText: 'Batal',
                    onConfirm: function() {
                        // Put into loading state
                        btnSync.disabled = true;
                        btnTest.disabled = true;
                        const icon = document.getElementById('icon-sync-all');
                        const label = document.getElementById('label-sync-all');
                        label.textContent = 'Sinkronisasi Berjalan...';
                        icon.classList.add('animate-spin');

                        fetch("{{ route('admin.ppdb.google-sheets.sync-now') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                AppPopup.success({
                                    title: 'Sinkronisasi Sukses',
                                    description: 'Proses sinkronisasi massal berhasil diselesaikan!'
                                });
                            } else {
                                AppPopup.error({
                                    title: 'Sinkronisasi Gagal',
                                    description: 'Gagal menyinkronkan data: ' + data.message
                                });
                            }
                        })
                        .catch(err => {
                            AppPopup.error({
                                title: 'Kesalahan Sistem',
                                description: 'Gagal memproses sinkronisasi massal karena kesalahan jaringan.'
                            });
                            console.error(err);
                        })
                        .finally(() => {
                            btnSync.disabled = false;
                            btnTest.disabled = false;
                            label.textContent = 'Sinkronisasi Sekarang';
                            icon.classList.remove('animate-spin');
                            runInitialTest(); // Refresh connection status
                        });
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin menyinkronkan total seluruh data pendaftar saat ini ke Google Sheets?\nTindakan ini akan mengosongkan sheet dan menulis ulang data terbaru.')) {
                    // Put into loading state
                    btnSync.disabled = true;
                    btnTest.disabled = true;
                    const icon = document.getElementById('icon-sync-all');
                    const label = document.getElementById('label-sync-all');
                    label.textContent = 'Sinkronisasi Berjalan...';
                    icon.classList.add('animate-spin');

                    fetch("{{ route('admin.ppdb.google-sheets.sync-now') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Sinkronisasi sukses! ' + data.message);
                        } else {
                            alert('Sinkronisasi gagal: ' + data.message);
                        }
                    })
                    .catch(err => {
                        alert('Eror AJAX: Gagal memproses sinkronisasi massal.');
                        console.error(err);
                    })
                    .finally(() => {
                        btnSync.disabled = false;
                        btnTest.disabled = false;
                        label.textContent = 'Sinkronisasi Sekarang';
                        icon.classList.remove('animate-spin');
                        runInitialTest(); // Refresh connection status
                    });
                }
            }
        });
    });
</script>
@endsection
