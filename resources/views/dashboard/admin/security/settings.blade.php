@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Keamanan & Backup';
        }
        if (typeof toggleStorageFolders === 'function') {
            toggleStorageFolders();
        }
    });
</script>

<div class="max-w-6xl space-y-6">
    <!-- Header with premium look & Alert -->
    <div class="bg-gradient-to-r from-[#4f45b2] via-[#6366f1] to-indigo-700 dark:from-zinc-900 dark:to-zinc-950 p-6 border-b-4 border-indigo-500 rounded-none shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4 text-white">
        <div>
            <h1 class="text-xl font-bold tracking-tight">Pusat Keamanan & Sistem Backup</h1>
            <p class="text-xs text-indigo-100 dark:text-zinc-400 mt-1">Kelola kredensial Google API secara terpusat, atur penjadwalan kompresi backup, amankan berkas dengan enkripsi militer AES-256-CBC, dan sinkronisasi otomatis ke Google Drive.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="flex h-3 w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-mono font-bold tracking-wider uppercase bg-white/10 dark:bg-zinc-800/60 px-3 py-1.5 border border-white/20 dark:border-zinc-700">Sistem Aktif</span>
        </div>
    </div>

    <!-- Alert Success/Error standard dashboard -->
    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-950/20 border-l-4 border-emerald-500 p-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="p-1 bg-emerald-500 text-white rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-emerald-800 dark:text-emerald-300">Berhasil!</p>
                <p class="text-[11px] text-emerald-600 dark:text-emerald-400/90">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 p-4 shadow-sm space-y-1">
        <div class="flex items-center gap-3">
            <div class="p-1 bg-rose-500 text-white rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <p class="text-xs font-bold text-rose-800 dark:text-rose-300">Terjadi Kesalahan!</p>
        </div>
        <ul class="list-disc list-inside text-[11px] text-rose-600 dark:text-rose-400/90 pl-8">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form & Tabs Card -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm overflow-hidden">
        
        <!-- Tab Navigation -->
        <div class="flex border-b border-slate-200 dark:border-zinc-850 bg-slate-50 dark:bg-zinc-950 overflow-x-auto">
            <button type="button" onclick="switchTab('tab-credentials')" id="btn-tab-credentials" 
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-indigo-600 text-indigo-600 dark:text-white focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-key mr-2"></i> Kredensial Google API
            </button>
            <button type="button" onclick="switchTab('tab-settings')" id="btn-tab-settings" 
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-sliders mr-2"></i> Pengaturan Backup
            </button>
            <button type="button" onclick="switchTab('tab-history')" id="btn-tab-history" 
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i> Riwayat & Manual Run
            </button>
            <button type="button" onclick="switchTab('tab-verification')" id="btn-tab-verification" 
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-shield-halved mr-2"></i> Validasi & Dekripsi
            </button>
        </div>

        <div class="p-6">
            <!-- TAB 1: GOOGLE SERVICE ACCOUNT CREDENTIALS -->
            <div id="tab-credentials" class="tab-content space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Column Left: Information -->
                    <div class="space-y-4">
                        <div class="p-4 bg-indigo-50/50 dark:bg-zinc-950 border border-indigo-100 dark:border-zinc-800 text-xs space-y-3">
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold font-mono tracking-wider uppercase block text-[10px]">INTEGRASI KREDENSIAL TERPUSAT</span>
                            <p class="text-slate-600 dark:text-zinc-400 leading-relaxed">Penyimpanan Google Service Account JSON kini dipusatkan di sini untuk menjamin keamanan yang maksimal dan kemudahan pengelolaan.</p>
                            <div class="h-px bg-slate-200 dark:bg-zinc-850"></div>
                            <p class="text-[11px] text-slate-500 dark:text-zinc-400 leading-relaxed font-mono">Digunakan oleh:</p>
                            <ul class="list-disc list-inside space-y-1 text-slate-500 dark:text-zinc-400 pl-2">
                                <li>Google Sheets (Sinkronisasi PPDB)</li>
                                <li>Google Drive Backup (Penyimpanan Arsip Awan)</li>
                            </ul>
                            <div class="mt-4 pt-3 border-t border-slate-200 dark:border-zinc-800">
                                <span class="text-slate-500 dark:text-zinc-400 font-semibold block mb-1">Status Kredensial:</span>
                                @if($hasGoogleCredentials)
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-check"></i> Tersimpan Secara Aman
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-exclamation"></i> Belum Dikonfigurasi
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-850 text-[11px] space-y-2">
                            <span class="text-slate-700 dark:text-zinc-350 font-bold uppercase tracking-wider block">Panduan Singkat GCP Console:</span>
                            <ol class="list-decimal list-inside space-y-1.5 text-slate-500 dark:text-zinc-400">
                                <li>Buat project baru di GCP Console.</li>
                                <li>Aktifkan API berikut:
                                    <ul class="list-disc list-inside pl-4 mt-0.5 text-indigo-600 dark:text-indigo-400">
                                        <li>Google Sheets API</li>
                                        <li>Google Drive API</li>
                                    </ul>
                                </li>
                                <li>Buat Service Account, unduh berkas kunci dalam format <span class="font-mono bg-slate-200 dark:bg-zinc-800 px-1">JSON</span>.</li>
                                <li>Salin dan tempelkan seluruh isi file JSON tersebut ke editor di sebelah kanan.</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Column Right: Form Editor -->
                    <div class="lg:col-span-2 space-y-4">
                        <form action="{{ route('admin.security.credentials.update') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Kunci Service Account (JSON)</label>
                                <textarea name="google_service_account_json" rows="10" placeholder='Tempelkan isi file JSON kredensial Anda di sini. format: { "type": "service_account", "project_id": ... }'
                                          class="w-full font-mono text-xs px-3 py-2.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-y">{{ old('google_service_account_json', $maskedGoogleJson) }}</textarea>
                            </div>

                            @if($hasGoogleCredentials)
                            <div class="p-3.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 flex items-center justify-between">
                                <div class="space-y-0.5">
                                    <span class="text-[10px] font-mono font-bold uppercase text-slate-400 dark:text-zinc-500 block">EMAIL SERVICE ACCOUNT AKTIF:</span>
                                    <span class="text-xs font-mono font-semibold text-slate-700 dark:text-zinc-300">{{ $clientEmail }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400 dark:text-zinc-500 italic max-w-xs text-right">
                                    Bagikan hak akses edit Google Sheets & Google Drive Folder Anda ke alamat email di atas.
                                </div>
                            </div>
                            @endif

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#6366f1] text-white font-mono font-bold text-xs uppercase tracking-wider rounded-none transition-all shadow-sm">
                                    Simpan Kredensial API
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 2: BACKUP SETTINGS -->
            <div id="tab-settings" class="tab-content hidden space-y-6">
                <form action="{{ route('admin.security.backup.settings') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Panel: Schedule & Scope -->
                        <div class="space-y-6">
                            <!-- Toggle Backup -->
                            <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-855 flex items-center justify-between">
                                <div class="space-y-1">
                                    <span class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wide">Backup Otomatis Terjadwal</span>
                                    <p class="text-[11px] text-slate-500 dark:text-zinc-400">Jalankan scheduler untuk backup data secara berkala otomatis.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer select-none" style="display:inline-flex !important; margin-bottom:0 !important;">
                                    <input type="checkbox" name="enabled" value="1" class="sr-only peer" {{ $backupSettings['enabled'] ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-500/20 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>

                            <!-- Scope Components -->
                            <div class="space-y-3">
                                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Komponen Yang Dibackup</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label class="flex items-start gap-3 p-3.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-850 cursor-pointer select-none" style="display:flex !important; margin-bottom:0 !important;">
                                        <input type="checkbox" name="backup_db" value="1" class="mt-0.5 text-indigo-600 focus:ring-indigo-500 border-slate-350 dark:border-zinc-800" {{ $backupSettings['backup_db'] ? 'checked' : '' }}>
                                        <div class="space-y-0.5">
                                            <span class="text-xs font-bold text-slate-850 dark:text-zinc-200 block">Database MySQL</span>
                                            <p class="text-[10px] text-slate-550 dark:text-zinc-400">Skema & seluruh baris data tabel.</p>
                                        </div>
                                    </label>
                                    <label class="flex items-start gap-3 p-3.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-850 cursor-pointer select-none" style="display:flex !important; margin-bottom:0 !important;">
                                        <input type="checkbox" name="backup_storage" id="backup-storage-checkbox" value="1" onchange="toggleStorageFolders()" class="mt-0.5 text-indigo-600 focus:ring-indigo-500 border-slate-350 dark:border-zinc-800" {{ $backupSettings['backup_storage'] ? 'checked' : '' }}>
                                        <div class="space-y-0.5">
                                            <span class="text-xs font-bold text-slate-855 dark:text-zinc-200 block">File Storage Uploads</span>
                                            <p class="text-[10px] text-slate-555 dark:text-zinc-400">File berkas unggahan pendaftar.</p>
                                        </div>
                                    </label>
                                </div>

                                <!-- Sub-panel Selective Storage Folders -->
                                <div id="storage-folders-wrapper" class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-850 space-y-3 transition-all duration-300 {{ $backupSettings['backup_storage'] ? '' : 'hidden' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-700 dark:text-zinc-350">Pilih Folder untuk Dibackup (Opsional)</span>
                                        <button type="button" onclick="scanStorageDirectories()" class="inline-flex items-center gap-1.5 py-1 px-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-800 text-[10px] font-mono font-bold text-slate-600 dark:text-zinc-400 transition-all shadow-sm">
                                            <i class="fa-solid fa-arrows-rotate" id="scan-btn-icon"></i> Scan Folder Realtime
                                        </button>
                                    </div>
                                    <p class="text-[10px] text-slate-505 dark:text-zinc-500">Centang folder tertentu saja yang ingin dicadangkan. Jika tidak ada yang dicentang, sistem otomatis membackup seluruh isi storage public.</p>
                                    
                                    <div id="storage-folders-list" class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1.5">
                                        @forelse($storageDirs as $dir)
                                            <label class="flex items-center justify-between p-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-indigo-500 dark:hover:border-zinc-700 cursor-pointer transition-all duration-200 select-none">
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" name="storage_folders[]" value="{{ $dir['name'] }}" class="text-indigo-600 focus:ring-indigo-500 border-slate-350 dark:border-zinc-800" {{ in_array($dir['name'], $selectedFolders) ? 'checked' : '' }}>
                                                    <span class="text-xs font-mono text-slate-700 dark:text-zinc-300">{{ $dir['name'] }}</span>
                                                </div>
                                                <span class="text-[10px] font-mono font-bold text-slate-400 dark:text-zinc-500">{{ $dir['formatted_size'] }}</span>
                                            </label>
                                        @empty
                                            <div class="col-span-2 py-4 text-center text-slate-450 dark:text-zinc-550 font-mono text-[10px]">
                                                Tidak ada folder yang ditemukan di public storage.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Interval Dropdown & Cron Input -->
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Interval Penjadwalan</label>
                                    <select name="schedule" id="schedule-selector" onchange="toggleCronInput()"
                                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                        <option value="daily" {{ $backupSettings['schedule'] === 'daily' ? 'selected' : '' }}>Harian (Setiap Tengah Malam - 00:00)</option>
                                        <option value="weekly" {{ $backupSettings['schedule'] === 'weekly' ? 'selected' : '' }}>Mingguan (Tiap Hari Minggu Malam - 00:00)</option>
                                        <option value="monthly" {{ $backupSettings['schedule'] === 'monthly' ? 'selected' : '' }}>Bulanan (Tiap Tanggal 1 Tengah Malam - 00:00)</option>
                                        <option value="custom" {{ $backupSettings['schedule'] === 'custom' ? 'selected' : '' }}>Ekspresi Cron Kustom (Kustomisasi Bebas)</option>
                                    </select>
                                </div>

                                <div id="cron-expression-wrapper" class="{{ $backupSettings['schedule'] === 'custom' ? '' : 'hidden' }}">
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Ekspresi Cron Kustom <span class="text-rose-500">*</span></label>
                                    <input type="text" name="cron_expression" value="{{ old('cron_expression', $backupSettings['cron_expression']) }}" placeholder="* * * * *"
                                           class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                                    <span class="text-[10px] text-slate-450 dark:text-zinc-550 block mt-1">Format: Menit | Jam | Hari-bulan | Bulan | Hari-minggu (contoh: <code>*/30 * * * *</code> = Tiap 30 menit)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Encryption & Google Drive Sync -->
                        <div class="space-y-6">
                            <!-- Section Enkripsi -->
                            <div class="p-4 bg-indigo-50/20 dark:bg-zinc-950 border border-indigo-100 dark:border-zinc-800 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wide">Proteksi & Enkripsi AES-256</span>
                                        <p class="text-[10px] text-slate-500 dark:text-zinc-400">Enkripsi berkas menggunakan algoritma sandi enkripsi OpenSSL militer.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer select-none" style="display:inline-flex !important; margin-bottom:0 !important;">
                                        <input type="checkbox" name="encryption_enabled" id="encrypt-toggle" value="1" class="sr-only peer" {{ $backupSettings['encryption_enabled'] ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-500/20 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Kunci Enkripsi Backup</label>
                                    @if($hasPassphrase)
                                        <div class="p-3 bg-emerald-500/10 border border-emerald-500/25 rounded-none space-y-3">
                                            <div class="flex items-center gap-2">
                                                <span class="flex h-2 w-2 relative">
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                </span>
                                                <span class="text-[11px] font-mono font-bold text-emerald-700 dark:text-emerald-400 uppercase">Kunci Enkripsi Terpasang & Aktif</span>
                                            </div>
                                            <p class="text-[10px] text-slate-550 dark:text-zinc-400 leading-relaxed">
                                                Kunci enkripsi acak 64-karakter hex yang sangat kuat telah dibuat secara otomatis oleh sistem. Seluruh cadangan data berikutnya akan diamankan dengan kunci ini.
                                            </p>
                                            <div class="flex flex-wrap gap-2 pt-1">
                                                <button type="button" onclick="confirmKeyDownload()" class="inline-flex items-center gap-2 py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-mono font-bold text-[10px] uppercase tracking-wider rounded-none transition-all shadow-sm">
                                                    <i class="fa-solid fa-download"></i> Download Kunci (.txt)
                                                </button>
                                                <button type="button" onclick="confirmKeyRotation()" class="inline-flex items-center gap-2 py-2 px-4 bg-amber-600 hover:bg-amber-700 text-white font-mono font-bold text-[10px] uppercase tracking-wider rounded-none transition-all shadow-sm">
                                                    <i class="fa-solid fa-rotate"></i> Rotasi Kunci Baru
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-3 bg-rose-500/10 border border-rose-500/25 rounded-none space-y-3">
                                            <div class="flex items-center gap-2">
                                                <span class="flex h-2 w-2 relative">
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                                </span>
                                                <span class="text-[11px] font-mono font-bold text-rose-700 dark:text-rose-400 uppercase">Kunci Enkripsi Belum Dibuat</span>
                                            </div>
                                            <p class="text-[10px] text-slate-550 dark:text-zinc-400 leading-relaxed">
                                                Anda harus membuat kunci enkripsi aman terlebih dahulu untuk dapat menggunakan fitur enkripsi cadangan.
                                            </p>
                                            <div class="pt-1">
                                                <button type="button" onclick="submitGenerateKey()" class="inline-flex items-center gap-2 py-2 px-4 bg-indigo-650 hover:bg-indigo-750 text-white font-mono font-bold text-[10px] uppercase tracking-wider rounded-none transition-all shadow-sm">
                                                    <i class="fa-solid fa-key"></i> Buat Kunci Enkripsi
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                    <span class="text-[10px] text-rose-550/95 block mt-2 font-semibold leading-relaxed">
                                        <i class="fa-solid fa-circle-exclamation"></i> PENTING: Unduh dan simpan kunci enkripsi di tempat yang aman. Berkas backup (.enc) TIDAK dapat dipulihkan tanpa berkas kunci ini.
                                    </span>
                                </div>
                            </div>

                            <!-- Section Google Drive Sync -->
                            <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-850 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wide">Sinkronisasi Ke Google Drive</span>
                                        <p class="text-[10px] text-slate-550 dark:text-zinc-400">Unggah berkas backup secara otomatis ke cloud Google Drive Anda.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer select-none" style="display:inline-flex !important; margin-bottom:0 !important;">
                                        <input type="checkbox" name="google_drive_enabled" id="drive-toggle" value="1" class="sr-only peer" {{ $backupSettings['google_drive_enabled'] ? 'checked' : '' }} {{ !$hasGoogleCredentials ? 'disabled' : '' }}>
                                        <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-500/20 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 peer-disabled:opacity-50 peer-disabled:cursor-not-allowed"></div>
                                    </label>
                                </div>

                                @if(!$hasGoogleCredentials)
                                    <div class="p-3 bg-amber-500/10 border border-amber-500/25 text-[10px] text-amber-700 dark:text-amber-400 leading-relaxed font-semibold">
                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Sinkronisasi Google Drive dinonaktifkan karena Anda belum menyimpan Kredensial Google Service Account di Tab Kredensial.
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Folder ID Google Drive (Opsional)</label>
                                    <input type="text" name="google_drive_folder_id" value="{{ old('google_drive_folder_id', $backupSettings['google_drive_folder_id']) }}" placeholder="Contoh: 1x8vS5c9zQ4ePz3nL... (kosongkan untuk folder root)"
                                           class="w-full font-mono text-xs px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                                    <span class="text-[10px] text-slate-450 dark:text-zinc-555 block mt-1">Kosongkan jika ingin menyimpan di halaman utama (root) Google Drive Anda. Pastikan folder tersebut telah dibagikan hak editnya ke email Service Account Anda.</span>
                                </div>
                            </div>

                            <!-- Retention Days -->
                            <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-850 space-y-3">
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Batas Hari Retensi Berkas Backup Lokal</label>
                                    <div class="relative max-w-[200px]">
                                        <input type="number" name="retention_days" value="{{ old('retention_days', $backupSettings['retention_days']) }}" min="1" max="365"
                                               class="w-full font-mono text-sm px-3 py-2.5 pr-12 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-mono text-slate-400 pointer-events-none">Hari</span>
                                    </div>
                                    <span class="text-[10px] text-slate-455 dark:text-zinc-555 block mt-1.5">Berkas backup lokal yang berusia lebih tua dari jumlah hari ini akan dihapus otomatis dari server demi efisiensi storage.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-zinc-850">
                        <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#6366f1] text-white font-mono font-bold text-xs uppercase tracking-wider rounded-none transition-all shadow-sm">
                            Simpan Konfigurasi Backup
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: BACKUP HISTORY & MANUAL RUN -->
            <div id="tab-history" class="tab-content hidden space-y-6">
                <!-- Manual Trigger & Progress View -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Manual Trigger Panel -->
                    <div class="p-5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-850 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <span class="text-xs font-mono font-bold text-[#4f45b2] dark:text-indigo-400 uppercase tracking-widest block">EKSEKUSI MANUAL</span>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Jalankan Proses Backup Instan</h2>
                            <p class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed">Gunakan tombol di bawah untuk memicu kompresi, enkripsi, dan pengunggahan berkas secara instan di server tanpa menunggu jadwal scheduler.</p>
                        </div>
                        <div>
                            <button type="button" id="manual-backup-btn" onclick="triggerManualBackup()" 
                                    class="w-full py-3 px-4 bg-[#4f45b2] hover:bg-[#6366f1] text-white font-mono font-bold text-xs uppercase tracking-widest rounded-none transition-all shadow-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-play"></i> JALANKAN BACKUP INSTAN
                            </button>
                        </div>
                    </div>

                    <!-- Progress Terminal/Logger View -->
                    <div class="lg:col-span-2 p-4 bg-zinc-950 dark:bg-black border border-zinc-800 font-mono text-[11px] text-zinc-300 flex flex-col min-h-[140px] justify-between relative shadow-inner overflow-hidden">
                        <div class="absolute top-2 right-3 text-[10px] text-zinc-550 uppercase select-none tracking-widest font-mono">Terminal Output Log</div>
                        <div class="flex-1 overflow-y-auto space-y-1.5 scrollbar-thin max-h-[120px]" id="terminal-log-content">
                            <div class="text-zinc-500">&gt; Sistem siap menerima eksekusi manual...</div>
                        </div>
                        <div class="mt-3 pt-2 border-t border-zinc-850/80 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-zinc-650 animate-pulse" id="terminal-indicator"></div>
                                <span class="text-[10px] text-zinc-500" id="terminal-status-text">Status: Menganggur (Idle)</span>
                            </div>
                            <div class="hidden" id="terminal-spinner-wrapper">
                                <svg class="animate-spin h-3.5 w-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Backup History List -->
                <div class="space-y-3">
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Log Riwayat Backup</label>
                    <div class="border border-slate-200 dark:border-zinc-800 rounded-none overflow-hidden shadow-sm bg-white dark:bg-zinc-900">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-zinc-950 text-slate-400 dark:text-zinc-500 border-b border-slate-250/60 dark:border-zinc-850 font-mono text-[10px] tracking-wider uppercase">
                                        <th class="py-3 px-4 font-bold">Tanggal</th>
                                        <th class="py-3 px-4 font-bold">Nama File</th>
                                        <th class="py-3 px-4 font-bold">Ukuran</th>
                                        <th class="py-3 px-4 font-bold">Enkripsi</th>
                                        <th class="py-3 px-4 font-bold">Google Drive Sync</th>
                                        <th class="py-3 px-4 font-bold text-center">Status</th>
                                        <th class="py-3 px-4 font-bold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-zinc-855/80" id="backup-history-tbody">
                                    @forelse($backupHistory as $history)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-900/40 transition-colors">
                                        <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500 dark:text-zinc-400 whitespace-nowrap">
                                            {{ $history->created_at ? $history->created_at->format('d-m-Y H:i:s') : '-' }}
                                        </td>
                                        <td class="py-3.5 px-4 font-medium text-slate-800 dark:text-zinc-200 font-mono text-[11px] break-all max-w-[200px]">
                                            {{ $history->filename }}
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-600 dark:text-zinc-400 whitespace-nowrap">
                                            {{ $history->status === 'success' ? $history->formatted_size : '-' }}
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if($history->encrypted)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-500/10 px-1.5 py-0.5 border border-indigo-500/15">
                                                    <i class="fa-solid fa-lock text-[9px]"></i> AES-256
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-550 bg-slate-500/10 px-1.5 py-0.5 border border-slate-500/10">
                                                    <i class="fa-solid fa-lock-open text-[9px]"></i> Tidak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if($history->drive_uploaded)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 border border-emerald-500/15" title="Drive File ID: {{ $history->drive_file_id }}">
                                                    <i class="fa-brands fa-google-drive"></i> Berhasil
                                                </span>
                                            @elseif(!empty($history->drive_error))
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 dark:text-rose-400 bg-rose-500/10 px-1.5 py-0.5 border border-rose-500/15 cursor-help" title="Error: {{ $history->drive_error }}">
                                                    <i class="fa-solid fa-triangle-exclamation"></i> Gagal
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-400 bg-slate-400/5 px-1.5 py-0.5 border border-slate-450/10">
                                                    -
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                            @if($history->status === 'success')
                                                <span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-emerald-500 text-white dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-500/20">SUKSES</span>
                                            @else
                                                <span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-rose-500 text-white dark:bg-rose-950/40 dark:text-rose-400 border border-rose-500/20">GAGAL</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap text-right space-x-1.5">
                                            <!-- Tombol Info (Selalu muncul untuk detail AJAX) -->
                                            <button type="button" onclick="showBackupLogDetails({{ $history->id }})"
                                                    class="inline-flex items-center justify-center w-7 h-7 bg-blue-50 dark:bg-zinc-800 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-zinc-700 hover:bg-blue-600 hover:text-white transition-colors" title="Detail Info Lengkap">
                                                <i class="fa-solid fa-circle-info text-[11px]"></i>
                                            </button>
 
                                            @if($history->status === 'success')
                                                <a href="{{ route('admin.security.backup.download', ['filename' => $history->filename]) }}"
                                                   class="inline-flex items-center justify-center w-7 h-7 bg-indigo-50 dark:bg-zinc-800 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-zinc-700 hover:bg-indigo-600 hover:text-white transition-colors" title="Unduh Berkas ke Komputer">
                                                    <i class="fa-solid fa-download text-[11px]"></i>
                                                </a>
                                                <button type="button" onclick="prefillVerification('{{ $history->filename }}')"
                                                        class="inline-flex items-center justify-center w-7 h-7 bg-slate-100 dark:bg-zinc-800 text-slate-650 dark:text-zinc-350 border border-slate-250 dark:border-zinc-700 hover:bg-indigo-500 hover:text-white transition-colors" title="Uji Dekripsi Berkas Ini">
                                                    <i class="fa-solid fa-shield-halved text-[11px]"></i>
                                                </button>
                                            @endif
                                            <button type="button" onclick="deleteBackup('{{ $history->filename }}')"
                                                    class="inline-flex items-center justify-center w-7 h-7 bg-rose-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-zinc-700 hover:bg-rose-600 hover:text-white transition-colors" title="Hapus Berkas Backup / Log">
                                                <i class="fa-solid fa-trash-can text-[11px]"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-slate-400 dark:text-zinc-550 font-mono text-[11px]">
                                            <i class="fa-solid fa-inbox text-lg block mb-2 opacity-50"></i> Belum ada riwayat berkas backup yang tercatat.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: DECRYPTION & INTEGRITY VERIFICATION -->
            <div id="tab-verification" class="tab-content hidden space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Column Left: Info & Instructions -->
                    <div class="space-y-4">
                        <div class="p-4 bg-indigo-50/50 dark:bg-zinc-950 border border-indigo-100 dark:border-zinc-800 text-xs space-y-3">
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold font-mono tracking-wider uppercase block text-[10px]">VERIFIKATOR INTEGRITAS</span>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-zinc-200">Bagaimana Cara Uji Mandiri Berkas Enkripsi?</h2>
                            <p class="text-slate-650 dark:text-zinc-400 leading-relaxed">
                                Tool ini akan memicu pemulihan bayangan (Shadow Decryption) di server untuk menguji apakah passphrase enkripsi Anda benar-benar berfungsi dan berkas kompresi ZIP di dalamnya berada dalam kondisi sempurna (tidak korup).
                            </p>
                            <p class="text-slate-650 dark:text-zinc-450 leading-relaxed">
                                Sistem akan mengekstrak berkas bayangan ke memori temporer, membaca daftarnya, lalu membersihkannya kembali secara aman.
                            </p>
                        </div>

                        <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-850 text-[11px] space-y-2">
                            <span class="text-slate-700 dark:text-zinc-350 font-bold uppercase tracking-wider block">Verifikasi via Command Line (CLI):</span>
                            <p class="text-slate-550 dark:text-zinc-400 leading-relaxed">Jika Anda mengunduh berkas backup berekstensi <code>.enc</code> ke PC lokal, Anda dapat mendekripsinya secara manual menggunakan OpenSSL CLI di terminal:</p>
                            <pre class="bg-zinc-950 text-emerald-400 p-2.5 overflow-x-auto text-[10px] font-mono border border-zinc-850 leading-relaxed">openssl enc -d -aes-256-cbc -pbkdf2 -iter 10000 -in [NAMA_FILE].enc -out [NAMA_FILE].zip</pre>
                            <span class="text-[10px] text-slate-455 dark:text-zinc-555 block mt-1">Masukkan kata sandi enkripsi Anda saat dimintai password di terminal CLI.</span>
                        </div>
                    </div>

                    <!-- Column Right: Form Verifier & Live Report Card -->
                    <div class="lg:col-span-2 space-y-5">
                        <div class="bg-slate-50 dark:bg-zinc-950 p-5 border border-slate-200 dark:border-zinc-850 space-y-4">
                            <h2 class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-widest">Validasi Uji Dekripsi</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Pilih File Backup Lokal</label>
                                    <select id="verify-filename"
                                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                        <option value="">-- Pilih Berkas Backup --</option>
                                        @foreach($backupHistory as $history)
                                            @if($history['status'] === 'success')
                                                <option value="{{ $history['filename'] }}">{{ $history['filename'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Masukkan Sandi Enkripsi (Passphrase)</label>
                                    <input type="password" id="verify-passphrase" placeholder="Kata sandi saat berkas backup dibuat"
                                           class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="button" id="verify-submit-btn" onclick="verifyBackupIntegrity()" 
                                        class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#6366f1] text-white font-mono font-bold text-xs uppercase tracking-wider rounded-none transition-all shadow-sm flex items-center gap-2">
                                    <i class="fa-solid fa-shield-check"></i> VERIFIKASI SEKARANG
                                </button>
                            </div>
                        </div>

                        <!-- Decrypt Report Results Card (Hidden by Default) -->
                        <div id="verify-report-card" class="hidden p-5 border shadow-sm rounded-none transition-all duration-300">
                            <!-- Header status -->
                            <div class="flex items-start gap-4" id="verify-report-header">
                                <!-- Dynamic Icon & Status text -->
                            </div>

                            <!-- Content details -->
                            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-zinc-800/80 grid grid-cols-1 sm:grid-cols-3 gap-4" id="verify-report-metrics">
                                <!-- Dynamic indicators -->
                            </div>

                            <!-- Tree Viewer -->
                            <div class="mt-5 space-y-2" id="verify-report-tree-wrapper">
                                <span class="text-[10px] font-mono font-bold uppercase text-slate-400 dark:text-zinc-500 block">Isi Struktur ZIP (Preview 10 Berkas Pertama):</span>
                                <div class="bg-zinc-950 text-emerald-400 p-4 border border-zinc-850 font-mono text-[11px] overflow-x-auto max-h-[220px] scrollbar-thin shadow-inner leading-relaxed" id="verify-report-tree">
                                    <!-- Dynamic File Trees -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching logic
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-indigo-600', 'text-indigo-600', 'dark:text-white');
            btn.classList.add('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        });

        document.getElementById(tabId).classList.remove('hidden');
        
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        activeBtn.classList.add('border-indigo-600', 'text-indigo-600', 'dark:text-white');
    }

    // Toggle Cron Input wrapper
    function toggleCronInput() {
        const schedule = document.getElementById('schedule-selector').value;
        const cronWrapper = document.getElementById('cron-expression-wrapper');
        if (schedule === 'custom') {
            cronWrapper.classList.remove('hidden');
        } else {
            cronWrapper.classList.add('hidden');
        }
    }

    // Toggle password visibility
    function togglePassphraseVisibility() {
        const field = document.getElementById('passphrase-field');
        const icon = document.getElementById('passphrase-eye-icon');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

    // Terminal log logging
    function logToTerminal(message, type = 'info') {
        const terminal = document.getElementById('terminal-log-content');
        const line = document.createElement('div');
        const timestamp = new Date().toLocaleTimeString();
        
        let colorClass = 'text-zinc-300';
        if (type === 'success') colorClass = 'text-emerald-400 font-semibold';
        if (type === 'error') colorClass = 'text-rose-500 font-semibold';
        if (type === 'warn') colorClass = 'text-amber-400';
        if (type === 'system') colorClass = 'text-indigo-400';

        line.className = `${colorClass} leading-relaxed py-0.5`;
        line.innerHTML = `<span class="text-zinc-650">[${timestamp}]</span> ${message}`;
        terminal.appendChild(line);
        terminal.scrollTop = terminal.scrollHeight;
    }

    // Trigger Manual Backup Process
    function triggerManualBackup() {
        const btn = document.getElementById('manual-backup-btn');
        const terminalIndicator = document.getElementById('terminal-indicator');
        const terminalStatusText = document.getElementById('terminal-status-text');
        const terminalSpinner = document.getElementById('terminal-spinner-wrapper');
        const terminalContent = document.getElementById('terminal-log-content');

        // Reset & disable UI
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        terminalSpinner.classList.remove('hidden');
        
        terminalIndicator.className = 'w-2 h-2 rounded-full bg-indigo-500 animate-ping';
        terminalStatusText.innerText = 'Status: Memproses Backup...';
        
        terminalContent.innerHTML = '';
        logToTerminal('Memulai inisialisasi backup manual...', 'system');
        logToTerminal('Mengirim instruksi API ke server...', 'info');

        fetch("{{ route('admin.security.backup.run') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            return response.json().then(data => {
                if (!response.ok) {
                    return Promise.reject({ message: data.message || 'Respons server gagal.', log: data.log });
                }
                return data;
            });
        })
        .then(data => {
            logToTerminal('Inisialisasi berhasil!', 'success');
            logToTerminal(`Kompresi selesai: ${data.log.type}`, 'info');
            if (data.log.encrypted) {
                logToTerminal('Proteksi enkripsi OpenSSL AES-256-CBC: TERAPKAN', 'success');
            } else {
                logToTerminal('Proteksi enkripsi OpenSSL: TIDAK AKTIF', 'warn');
            }

            if (data.log.drive_uploaded) {
                logToTerminal(`Sinkronisasi Google Drive awan: SUKSES (ID: ${data.log.drive_file_id})`, 'success');
            } else if (data.log.drive_error) {
                logToTerminal(`Sinkronisasi Google Drive awan: GAGAL (${data.log.drive_error})`, 'error');
            }

            logToTerminal(`Penghapusan berkas temporer: BERSIH`, 'info');
            logToTerminal(`Ukuran File Akhir: ${data.log.status === 'success' ? data.log.formatted_size : '-'}`, 'system');
            logToTerminal(`Total Durasi Proses: ${data.log.duration} detik`, 'system');
            logToTerminal(`Backup selesai dengan sukses! File disimpan: ${data.log.filename}`, 'success');
            
            terminalIndicator.className = 'w-2 h-2 rounded-full bg-emerald-500';
            terminalStatusText.innerText = 'Status: Eksekusi Sukses!';

            // Dynamic table update without reload
            appendBackupLogToTable(data.log);

            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            terminalSpinner.classList.add('hidden');
        })
        .catch(err => {
            logToTerminal(`PROSES BACKUP GAGAL: ${err.message || err}`, 'error');
            terminalIndicator.className = 'w-2 h-2 rounded-full bg-rose-500';
            terminalStatusText.innerText = 'Status: Eksekusi Eror!';
            
            if (err.log) {
                appendBackupLogToTable(err.log);
            }

            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            terminalSpinner.classList.add('hidden');
        });
    }

    // Dynamic table update helper
    function appendBackupLogToTable(log) {
        const tbody = document.getElementById('backup-history-tbody');
        
        if (tbody.children.length === 1 && tbody.innerHTML.includes('Belum ada riwayat')) {
            tbody.innerHTML = '';
        }
        
        const downloadUrl = "{{ route('admin.security.backup.download', ['filename' => ':filename']) }}".replace(':filename', log.filename);
        
        const row = document.createElement('tr');
        row.className = "hover:bg-slate-50/60 dark:hover:bg-zinc-900/40 transition-colors";
        
        row.innerHTML = `
            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500 dark:text-zinc-400 whitespace-nowrap">
                ${log.formatted_date}
            </td>
            <td class="py-3.5 px-4 font-medium text-slate-800 dark:text-zinc-200 font-mono text-[11px] break-all max-w-[200px]">
                ${log.filename}
            </td>
            <td class="py-3.5 px-4 text-slate-600 dark:text-zinc-400 whitespace-nowrap">
                ${log.status === 'success' ? log.formatted_size : '-'}
            </td>
            <td class="py-3.5 px-4 whitespace-nowrap">
                ${log.encrypted 
                    ? '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-500/10 px-1.5 py-0.5 border border-indigo-500/15"><i class="fa-solid fa-lock text-[9px]"></i> AES-256</span>'
                    : '<span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-550 bg-slate-500/10 px-1.5 py-0.5 border border-slate-500/10"><i class="fa-solid fa-lock-open text-[9px]"></i> Tidak</span>'
                }
            </td>
            <td class="py-3.5 px-4 whitespace-nowrap">
                ${log.drive_uploaded
                    ? `<span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 border border-emerald-500/15" title="Drive File ID: ${log.drive_file_id}"><i class="fa-brands fa-google-drive"></i> Berhasil</span>`
                    : (log.drive_error
                        ? `<span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 dark:text-rose-400 bg-rose-500/10 px-1.5 py-0.5 border border-rose-500/15 cursor-help" title="Error: ${log.drive_error}"><i class="fa-solid fa-triangle-exclamation"></i> Gagal</span>`
                        : '<span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-400 bg-slate-400/5 px-1.5 py-0.5 border border-slate-450/10">-</span>')
                }
            </td>
            <td class="py-3.5 px-4 whitespace-nowrap text-center">
                ${log.status === 'success'
                    ? '<span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-emerald-500 text-white dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-500/20">SUKSES</span>'
                    : '<span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-rose-500 text-white dark:bg-rose-950/40 dark:text-rose-400 border border-rose-500/20">GAGAL</span>'
                }
            </td>
            <td class="py-3.5 px-4 whitespace-nowrap text-right space-x-1.5">
                ${log.status === 'success' 
                    ? `
                    <a href="${downloadUrl}"
                       class="inline-flex items-center justify-center w-7 h-7 bg-indigo-50 dark:bg-zinc-800 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-zinc-700 hover:bg-indigo-600 hover:text-white transition-colors" title="Unduh Berkas ke Komputer">
                        <i class="fa-solid fa-download text-[11px]"></i>
                    </a>
                    <button type="button" onclick="prefillVerification('${log.filename}')"
                            class="inline-flex items-center justify-center w-7 h-7 bg-slate-100 dark:bg-zinc-800 text-slate-650 dark:text-zinc-350 border border-slate-250 dark:border-zinc-700 hover:bg-indigo-500 hover:text-white transition-colors" title="Uji Dekripsi Berkas Ini">
                        <i class="fa-solid fa-shield-halved text-[11px]"></i>
                    </button>
                    ` 
                    : ''
                }
                <button type="button" onclick="deleteBackup('${log.filename}')"
                        class="inline-flex items-center justify-center w-7 h-7 bg-rose-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-zinc-700 hover:bg-rose-600 hover:text-white transition-colors" title="Hapus Berkas Backup / Log">
                    <i class="fa-solid fa-trash-can text-[11px]"></i>
                </button>
            </td>
        `;
        
        tbody.insertBefore(row, tbody.firstChild);
    }

    // Prefill fields for validation tab
    function prefillVerification(filename) {
        document.getElementById('verify-filename').value = filename;
        switchTab('tab-verification');
        document.getElementById('verify-passphrase').focus();
    }

    // Decrypt Verification Logic
    function verifyBackupIntegrity() {
        const filename = document.getElementById('verify-filename').value;
        const passphrase = document.getElementById('verify-passphrase').value;
        const submitBtn = document.getElementById('verify-submit-btn');
        const reportCard = document.getElementById('verify-report-card');
        
        if (!filename) {
            alert('Silakan pilih berkas backup terlebih dahulu.');
            return;
        }
        if (!passphrase) {
            alert('Silakan masukkan sandi enkripsi berkas.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = `<svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> MEMPROSES UJI DEKRIPSI...`;

        fetch("{{ route('admin.security.backup.verify') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                filename: filename,
                passphrase: passphrase
            })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<i class="fa-solid fa-shield-check"></i> VERIFIKASI SEKARANG`;
            
            reportCard.classList.remove('hidden');

            const header = document.getElementById('verify-report-header');
            const metrics = document.getElementById('verify-report-metrics');
            const tree = document.getElementById('verify-report-tree');
            const treeWrapper = document.getElementById('verify-report-tree-wrapper');

            if (res.status === 200 && res.body.success) {
                // SUCCESS REPORT CARD STYLING
                reportCard.className = "p-5 border border-emerald-250 dark:border-emerald-900 bg-emerald-50/15 dark:bg-emerald-950/10 shadow-sm rounded-none transition-all duration-300";
                
                header.innerHTML = `
                    <div class="p-2 bg-emerald-500 text-white rounded-none flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-emerald-800 dark:text-emerald-400">Integrasi & Validitas Berkas Terbukti Sempurna!</h3>
                        <p class="text-[11px] text-emerald-600 dark:text-emerald-500/90 leading-relaxed mt-0.5">${res.body.message}</p>
                    </div>
                `;

                metrics.innerHTML = `
                    <div class="p-3 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 flex flex-col justify-between">
                        <span class="text-[9px] font-mono font-bold text-slate-400 dark:text-zinc-500 uppercase">ISI DATA DATABASE:</span>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            ${res.body.report.has_db_dump 
                                ? '<span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 font-mono"><i class="fa-solid fa-square-check"></i> TERSEDIA (.SQL)</span>' 
                                : '<span class="text-[10px] font-medium text-slate-400 font-mono"><i class="fa-solid fa-square-minus"></i> TIDAK ADA</span>'}
                        </div>
                    </div>
                    <div class="p-3 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 flex flex-col justify-between">
                        <span class="text-[9px] font-mono font-bold text-slate-400 dark:text-zinc-500 uppercase">UPLOAD UPLOADS STORAGE:</span>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            ${res.body.report.has_storage 
                                ? '<span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 font-mono"><i class="fa-solid fa-square-check"></i> TERSEDIA (/UPLOADS)</span>' 
                                : '<span class="text-[10px] font-medium text-slate-400 font-mono"><i class="fa-solid fa-square-minus"></i> TIDAK ADA</span>'}
                        </div>
                    </div>
                    <div class="p-3 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 flex flex-col justify-between">
                        <span class="text-[9px] font-mono font-bold text-slate-400 dark:text-zinc-500 uppercase">TOTAL BERKAS DIKOMPRESI:</span>
                        <div class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 mt-1.5">
                            <i class="fa-regular fa-folder-open text-indigo-500"></i> ${res.body.report.total_files} File
                        </div>
                    </div>
                `;

                // Build styled file list tree snippet
                treeWrapper.classList.remove('hidden');
                let treeHtml = '';
                res.body.report.files.forEach(f => {
                    const ext = f.name.split('.').pop();
                    let icon = '<i class="fa-regular fa-file text-slate-400"></i>';
                    if (f.name === 'database_dump.sql') icon = '<i class="fa-solid fa-database text-indigo-500"></i>';
                    if (f.name.startsWith('storage_uploads/')) icon = '<i class="fa-regular fa-image text-emerald-500"></i>';
                    
                    treeHtml += `<div class="py-0.5 border-b border-zinc-900/60 flex items-center justify-between"><span class="whitespace-nowrap font-mono">${icon} ${f.name}</span><span class="text-[10px] text-zinc-550">${(f.size / 1024).toFixed(1)} KB</span></div>`;
                });
                if (res.body.report.total_files > 10) {
                    treeHtml += `<div class="py-1 text-zinc-500 italic font-mono text-[10px] text-center pt-2">... Dan ${res.body.report.total_files - 10} berkas terkompresi lainnya ...</div>`;
                }
                tree.innerHTML = treeHtml;
            } else {
                // FAILURE REPORT CARD STYLING
                reportCard.className = "p-5 border border-rose-250 dark:border-rose-955 bg-rose-50/15 dark:bg-rose-955/10 shadow-sm rounded-none transition-all duration-300";
                
                header.innerHTML = `
                    <div class="p-2 bg-rose-500 text-white rounded-none flex-shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-rose-800 dark:text-rose-400">Dekripsi Gagal! Sandi Tidak Valid.</h3>
                        <p class="text-[11px] text-rose-600 dark:text-rose-500/90 leading-relaxed mt-0.5">${res.body.message || 'Sandi salah atau berkas backup terenkripsi mengalami kerusakan data.'}</p>
                    </div>
                `;

                metrics.innerHTML = '';
                treeWrapper.classList.add('hidden');
                tree.innerHTML = '';
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<i class="fa-solid fa-shield-check"></i> VERIFIKASI SEKARANG`;
            
            reportCard.classList.remove('hidden');
            reportCard.className = "p-5 border border-rose-250 dark:border-rose-955 bg-rose-50/15 dark:bg-rose-955/10 shadow-sm rounded-none transition-all duration-300";
            
            document.getElementById('verify-report-header').innerHTML = `
                <div class="p-2 bg-rose-500 text-white rounded-none flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-rose-800 dark:text-rose-400">Validasi Eror!</h3>
                    <p class="text-[11px] text-rose-600 dark:text-rose-500/90 leading-relaxed mt-0.5">${err.message || 'Terjadi kesalahan sistem internal.'}</p>
                </div>
            `;
            document.getElementById('verify-report-metrics').innerHTML = '';
            document.getElementById('verify-report-tree-wrapper').classList.add('hidden');
        });
    }

    // Delete Backup using AppPopup.confirm
    function deleteBackup(filename) {
        AppPopup.show({
            type: 'confirm',
            title: 'Hapus Berkas Backup?',
            description: `Apakah Anda yakin ingin menghapus berkas backup <strong>${filename}</strong> secara permanen? Tindakan ini tidak dapat dibatalkan.`,
            confirmText: 'Ya, Hapus',
            cancelText: 'Batal',
            icon: `<svg class="popup-anim-svg" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6"/>
                    <path d="M9 6V4h6v2"/>
                  </svg>`,
            onConfirm: () => {
                const url = "{{ route('admin.security.backup.delete', ['filename' => ':filename']) }}".replace(':filename', filename);

                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        AppPopup.success({
                            title: 'Berhasil!',
                            description: data.message,
                            duration: 2000
                        });
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        AppPopup.error({
                            title: 'Gagal',
                            description: `Gagal menghapus: ${data.message}`
                        });
                    }
                })
                .catch(err => {
                    AppPopup.error({
                        title: 'Eror',
                        description: `Terjadi kesalahan sistem: ${err.message}`
                    });
                });
            }
        });
    }

    function openPasswordConfirmModal(actionType) {
        const form = document.getElementById('password-confirm-form');
        const input = document.getElementById('confirm_password_input');
        input.value = ''; // Reset input
        
        if (actionType === 'download') {
            form.action = "{{ route('admin.security.backup.download-key') }}";
        } else if (actionType === 'rotate') {
            form.action = "{{ route('admin.security.backup.generate-key') }}";
        }
        
        AppModal.open('passwordConfirmModal');
    }

    function confirmKeyRotation() {
        AppPopup.show({
            type: 'warning',
            title: 'Rotasi Kunci Enkripsi?',
            description: 'PERINGATAN: Merotasi kunci akan menghasilkan kunci enkripsi baru. Berkas backup lama yang terenkripsi tidak akan bisa didekripsi dengan kunci baru ini. Apakah Anda yakin ingin melanjutkan?',
            confirmText: 'Ya, Lanjutkan',
            cancelText: 'Batal',
            icon: `<svg class="popup-anim-svg text-amber-500" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                  </svg>`,
            onConfirm: () => {
                openPasswordConfirmModal('rotate');
            }
        });
    }

    function confirmKeyDownload() {
        AppPopup.show({
            type: 'custom',
            title: 'Unduh Kunci Enkripsi?',
            description: 'Apakah Anda yakin ingin mengunduh berkas kunci enkripsi? Kunci ini sangat rahasia dan wajib disimpan dengan aman untuk keperluan dekripsi cadangan data Anda.',
            confirmText: 'Ya, Unduh',
            cancelText: 'Batal',
            icon: `<svg class="popup-anim-svg text-indigo-500" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                  </svg>`,
            onConfirm: () => {
                openPasswordConfirmModal('download');
            }
        });
    }

    function toggleStorageFolders() {
        const checkbox = document.getElementById('backup-storage-checkbox');
        const wrapper = document.getElementById('storage-folders-wrapper');
        if (checkbox && wrapper) {
            if (checkbox.checked) {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
            }
        }
    }

    function scanStorageDirectories() {
        const icon = document.getElementById('scan-btn-icon');
        const listContainer = document.getElementById('storage-folders-list');
        if (!icon || !listContainer) return;
        
        icon.classList.add('fa-spin');
        
        fetch("{{ route('admin.security.backup.storage-directories') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal melakukan scanning folder.');
            }
            return response.json();
        })
        .then(data => {
            icon.classList.remove('fa-spin');
            if (data.success) {
                let html = '';
                const selected = data.selected_folders || [];
                
                if (data.directories.length === 0) {
                    html = '<div class="col-span-2 py-4 text-center text-slate-450 dark:text-zinc-550 font-mono text-[10px]">Tidak ada folder yang ditemukan di public storage.</div>';
                } else {
                    data.directories.forEach(dir => {
                        const isChecked = selected.includes(dir.name) ? 'checked' : '';
                        html += `
                            <label class="flex items-center justify-between p-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-indigo-500 dark:hover:border-zinc-700 cursor-pointer transition-all duration-200 select-none">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="storage_folders[]" value="${dir.name}" ${isChecked} class="text-indigo-600 focus:ring-indigo-500 border-slate-350 dark:border-zinc-800">
                                    <span class="text-xs font-mono text-slate-700 dark:text-zinc-300">${dir.name}</span>
                                </div>
                                <span class="text-[10px] font-mono font-bold text-slate-400 dark:text-zinc-500">${dir.formatted_size}</span>
                            </label>
                        `;
                    });
                }
                listContainer.innerHTML = html;
            } else {
                alert(data.message || 'Gagal memuat daftar folder.');
            }
        })
        .catch(err => {
            icon.classList.remove('fa-spin');
            alert('Gagal melakukan scan folder: ' + err.message);
        });
    }

    // Show Backup Log Details Modal
    function showBackupLogDetails(logId) {
        const modal = document.getElementById('logDetailModal');
        const modalContent = document.getElementById('logDetailModalContent');
        
        // Reset values
        document.getElementById('detail-created_at').innerText = 'Memuat...';
        document.getElementById('detail-status').innerText = '...';
        document.getElementById('detail-status').className = 'inline-block font-bold px-1.5 py-0.5 border border-slate-350 text-slate-500';
        document.getElementById('detail-filename').innerText = 'Memuat...';
        document.getElementById('detail-type').innerText = 'Memuat...';
        document.getElementById('detail-size').innerText = 'Memuat...';
        document.getElementById('detail-encrypted').innerHTML = 'Memuat...';
        document.getElementById('detail-duration').innerText = 'Memuat...';
        document.getElementById('detail-drive_uploaded').innerHTML = 'Memuat...';
        
        document.getElementById('detail-drive_file_id_wrapper').classList.add('hidden');
        document.getElementById('detail-drive_error_wrapper').classList.add('hidden');
        document.getElementById('detail-system_error_wrapper').classList.add('hidden');

        // Show modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 50);

        const url = "{{ route('admin.security.backup.log-details', ['id' => ':id']) }}".replace(':id', logId);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal memuat detail log dari server.');
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Log tidak ditemukan.');
            }

            const log = data.log;

            document.getElementById('detail-created_at').innerText = data.formatted_date;
            document.getElementById('detail-filename').innerText = log.filename;
            document.getElementById('detail-type').innerText = log.type || 'Full Backup';
            document.getElementById('detail-size').innerText = log.status === 'success' ? data.formatted_size : '-';
            document.getElementById('detail-duration').innerText = `${log.duration} detik`;

            // Status Utama
            const statusEl = document.getElementById('detail-status');
            if (log.status === 'success') {
                statusEl.innerText = 'SUKSES';
                statusEl.className = 'inline-block font-bold px-1.5 py-0.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25';
            } else {
                statusEl.innerText = 'GAGAL';
                statusEl.className = 'inline-block font-bold px-1.5 py-0.5 bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/25';
            }

            // Enkripsi
            const encEl = document.getElementById('detail-encrypted');
            if (log.encrypted) {
                encEl.innerHTML = '<span class="text-indigo-600 dark:text-indigo-400 bg-indigo-500/10 px-1.5 py-0.5 border border-indigo-500/15"><i class="fa-solid fa-lock text-[9px]"></i> AES-256</span>';
            } else {
                encEl.innerHTML = '<span class="text-slate-500 bg-slate-500/10 px-1.5 py-0.5 border border-slate-500/10"><i class="fa-solid fa-lock-open text-[9px]"></i> Tidak</span>';
            }

            // Google Drive Uploaded
            const driveEl = document.getElementById('detail-drive_uploaded');
            if (log.drive_uploaded) {
                driveEl.innerHTML = '<span class="text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 border border-emerald-500/15"><i class="fa-brands fa-google-drive"></i> Berhasil</span>';
                document.getElementById('detail-drive_file_id_wrapper').classList.remove('hidden');
                document.getElementById('detail-drive_file_id').innerText = log.drive_file_id || '-';
            } else if (log.drive_error) {
                driveEl.innerHTML = '<span class="text-rose-600 dark:text-rose-400 bg-rose-500/10 px-1.5 py-0.5 border border-rose-500/15"><i class="fa-solid fa-triangle-exclamation"></i> Gagal</span>';
                document.getElementById('detail-drive_error_wrapper').classList.remove('hidden');
                document.getElementById('detail-drive_error').innerText = log.drive_error;
            } else {
                driveEl.innerHTML = '<span class="text-slate-400 bg-slate-400/5 px-1.5 py-0.5 border border-slate-450/10">Tidak Diaktifkan</span>';
            }

            // System Error (if failed)
            if (log.status === 'failed' && log.error_message) {
                document.getElementById('detail-system_error_wrapper').classList.remove('hidden');
                document.getElementById('detail-system_error').innerText = log.error_message;
            }
        })
        .catch(err => {
            alert(err.message);
            closeLogDetailModal();
        });
    }

    function closeLogDetailModal() {
        const modal = document.getElementById('logDetailModal');
        const modalContent = document.getElementById('logDetailModalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }
</script>

<!-- Modal Detail Log Backup -->
<div id="logDetailModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeLogDetailModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 w-full max-w-2xl shadow-xl transform transition-all duration-300 scale-95 opacity-0" id="logDetailModalContent">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-indigo-500"></i>
                <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200">Detail Log Backup</h3>
            </div>
            <button type="button" onclick="closeLogDetailModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-300">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto text-xs">
            <!-- Grid Metadata -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <span class="text-slate-400 dark:text-zinc-500 block font-mono text-[10px] uppercase">WAKTU EKSEKUSI</span>
                    <span id="detail-created_at" class="font-mono text-slate-800 dark:text-zinc-200">-</span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 dark:text-zinc-500 block font-mono text-[10px] uppercase">STATUS UTAMA</span>
                    <span id="detail-status" class="inline-block font-bold px-1.5 py-0.5 border">-</span>
                </div>
                <div class="space-y-1 col-span-2">
                    <span class="text-slate-400 dark:text-zinc-500 block font-mono text-[10px] uppercase">NAMA BERKAS</span>
                    <span id="detail-filename" class="font-mono break-all font-bold text-slate-800 dark:text-zinc-200">-</span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 dark:text-zinc-500 block font-mono text-[10px] uppercase">TIPE BACKUP</span>
                    <span id="detail-type" class="text-slate-800 dark:text-zinc-200">-</span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 dark:text-zinc-500 block font-mono text-[10px] uppercase">UKURAN BERKAS</span>
                    <span id="detail-size" class="font-mono text-slate-800 dark:text-zinc-200">-</span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 dark:text-zinc-500 block font-mono text-[10px] uppercase">ENKRIPSI AES-256</span>
                    <span id="detail-encrypted" class="inline-flex items-center gap-1 font-mono font-bold">-</span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 dark:text-zinc-500 block font-mono text-[10px] uppercase">DURASI PROSES</span>
                    <span id="detail-duration" class="font-mono text-slate-800 dark:text-zinc-200">-</span>
                </div>
            </div>

            <!-- Divider -->
            <div class="h-px bg-slate-200 dark:bg-zinc-800"></div>

            <!-- Google Drive Status Section -->
            <div class="space-y-2">
                <span class="text-slate-400 dark:text-zinc-500 block font-mono text-[10px] uppercase">SINKRONISASI GOOGLE DRIVE</span>
                <div class="p-3 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-550 dark:text-zinc-400">Status Unggahan:</span>
                        <span id="detail-drive_uploaded" class="inline-flex items-center gap-1 font-mono font-bold">-</span>
                    </div>
                    <div id="detail-drive_file_id_wrapper" class="hidden flex items-center justify-between">
                        <span class="text-slate-550 dark:text-zinc-400">ID Berkas Drive:</span>
                        <span id="detail-drive_file_id" class="font-mono text-indigo-600 dark:text-indigo-400 select-all font-semibold break-all text-right max-w-[70%]">-</span>
                    </div>
                    <div id="detail-drive_error_wrapper" class="hidden space-y-1.5 pt-1.5 border-t border-slate-250 dark:border-zinc-850">
                        <span class="text-rose-500 dark:text-rose-400 font-bold block">Pesan Kesalahan Google Drive:</span>
                        <div class="p-2.5 bg-rose-500/5 text-rose-600 dark:text-rose-400/90 font-mono text-[11px] border border-rose-500/10 rounded-none whitespace-pre-wrap leading-relaxed" id="detail-drive_error"></div>
                        <div class="text-[10px] text-amber-600 dark:text-amber-400/80 leading-relaxed font-sans pt-1">
                            <span class="font-bold"><i class="fa-solid fa-lightbulb text-amber-500"></i> Solusi Kuota 0 Bytes:</span> Google Service Account secara default memiliki kuota 0 bytes. Harap gunakan **Shared Drive (Drive Bersama)** atau **Domain-Wide Delegation** di Google Workspace Anda, lalu bagikan akses folder/Shared Drive tersebut ke email Service Account di atas.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Message Section (If failed) -->
            <div id="detail-system_error_wrapper" class="hidden space-y-2">
                <span class="text-slate-400 dark:text-zinc-500 block font-mono text-[10px] uppercase">DETIL EROR SISTEM / PHP EXCEPTION</span>
                <div class="p-3 bg-rose-500/5 text-rose-600 dark:text-rose-400 border border-rose-500/10 rounded-none space-y-1.5">
                    <span class="font-bold block">Exception Message:</span>
                    <div class="p-2.5 bg-zinc-950 text-rose-400 font-mono text-[11px] border border-zinc-850 rounded-none whitespace-pre-wrap leading-relaxed" id="detail-system_error"></div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end p-4 border-t border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950">
            <button type="button" onclick="closeLogDetailModal()" class="py-2 px-5 bg-slate-200 hover:bg-slate-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-800 dark:text-zinc-200 font-mono font-bold text-[10px] uppercase tracking-wider rounded-none transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Password Keamanan -->
<x-app-modal id="passwordConfirmModal" maxWidth="md" title="Konfirmasi Kata Sandi" description="Masukkan kata sandi login Anda untuk memverifikasi tindakan ini demi keamanan tambahan." icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>' iconColor="indigo">
    <form id="password-confirm-form" method="POST" action="">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="confirm_password_input" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-450 dark:text-zinc-500 mb-2">Kata Sandi Akun</label>
                <input type="password" name="confirm_password" id="confirm_password_input" required class="w-full text-sm px-3 py-2.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-none focus:outline-none focus:ring-2 focus:ring-indigo-500/20 text-slate-800 dark:text-slate-100" placeholder="••••••••">
            </div>
        </div>
        <x-slot name="footer">
            <button type="button" onclick="AppModal.close('passwordConfirmModal')" class="modal-btn-cancel">Batal</button>
            <button type="submit" class="modal-btn-primary">Konfirmasi & Lanjutkan</button>
        </x-slot>
    </form>
</x-app-modal>
@endsection
