@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) breadcrumb.textContent = 'Manajemen Backup';
        if (typeof toggleStorageFolders === 'function') toggleStorageFolders();
    });
</script>

<div class="max-w-6xl space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 dark:from-zinc-900 dark:to-zinc-950 p-6 border-b-4 border-slate-600 shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4 text-white">
        <div>
            <h1 class="text-xl font-bold tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-database text-slate-300"></i> Manajemen Backup Data
            </h1>
            <p class="text-xs text-slate-300 dark:text-zinc-400 mt-1">Atur jadwal backup, enkripsi AES-256, sinkronisasi Google Drive, dan verifikasi integritas berkas cadangan data Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="flex h-3 w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $backupSettings['enabled'] ? 'bg-emerald-400' : 'bg-amber-400' }} opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 {{ $backupSettings['enabled'] ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
            </span>
            <span class="text-xs font-mono font-bold tracking-wider uppercase bg-white/10 px-3 py-1.5 border border-white/20">
                Scheduler: {{ $backupSettings['enabled'] ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
    </div>

    {{-- Alerts --}}
    {{-- @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-950/20 border-l-4 border-emerald-500 p-4 flex items-center gap-3 shadow-sm">
        <div class="p-1 bg-emerald-500 text-white rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
        <div>
            <p class="text-xs font-bold text-emerald-800 dark:text-emerald-300">Berhasil!</p>
            <p class="text-[11px] text-emerald-600 dark:text-emerald-400/90">{{ session('success') }}</p>
        </div>
    </div>
    @endif --}}
    @if($errors->any())
    <div class="bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 p-4 shadow-sm space-y-1">
        <div class="flex items-center gap-3">
            <div class="p-1 bg-rose-500 text-white rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div>
            <p class="text-xs font-bold text-rose-800 dark:text-rose-300">Kesalahan!</p>
        </div>
        <ul class="list-disc list-inside text-[11px] text-rose-600 dark:text-rose-400/90 pl-8">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Tabs Card --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="flex border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 overflow-x-auto">
            <button type="button" onclick="switchTab('tab-settings')" id="btn-tab-settings"
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-indigo-600 text-indigo-600 dark:text-white focus:outline-none whitespace-nowrap">
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
            {{-- TAB 1: BACKUP SETTINGS --}}
            <div id="tab-settings" class="tab-content space-y-6">
                <form action="{{ route('admin.backup.settings') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Left Panel --}}
                        <div class="space-y-6">
                            <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 flex items-center justify-between">
                                <div class="space-y-1">
                                    <span class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wide">Backup Otomatis Terjadwal</span>
                                    <p class="text-[11px] text-slate-500 dark:text-zinc-400">Jalankan scheduler untuk backup data secara berkala otomatis.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer select-none" style="display:inline-flex!important;margin-bottom:0!important;">
                                    <input type="checkbox" name="enabled" value="1" class="sr-only peer" {{ $backupSettings['enabled'] ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-500/20 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>

                            <div class="space-y-3">
                                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Komponen Yang Dibackup</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label class="flex items-start gap-3 p-3.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 cursor-pointer select-none" style="display:flex!important;margin-bottom:0!important;">
                                        <input type="checkbox" name="backup_db" value="1" class="mt-0.5 text-indigo-600 focus:ring-indigo-500 border-slate-350 dark:border-zinc-800" {{ $backupSettings['backup_db'] ? 'checked' : '' }}>
                                        <div class="space-y-0.5">
                                            <span class="text-xs font-bold text-slate-800 dark:text-zinc-200 block">Database MySQL</span>
                                            <p class="text-[10px] text-slate-550 dark:text-zinc-400">Skema & seluruh baris data tabel.</p>
                                        </div>
                                    </label>
                                    <label class="flex items-start gap-3 p-3.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 cursor-pointer select-none" style="display:flex!important;margin-bottom:0!important;">
                                        <input type="checkbox" name="backup_storage" id="backup-storage-checkbox" value="1" onchange="toggleStorageFolders()" class="mt-0.5 text-indigo-600 focus:ring-indigo-500 border-slate-350 dark:border-zinc-800" {{ $backupSettings['backup_storage'] ? 'checked' : '' }}>
                                        <div class="space-y-0.5">
                                            <span class="text-xs font-bold dark:text-zinc-200 block">File Storage Uploads</span>
                                            <p class="text-[10px] text-slate-555 dark:text-zinc-400">File berkas unggahan pendaftar.</p>
                                        </div>
                                    </label>
                                </div>

                                <div id="storage-folders-wrapper" class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 space-y-3 {{ $backupSettings['backup_storage'] ? '' : 'hidden' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-700 dark:text-zinc-350">Pilih Folder (Opsional)</span>
                                        <button type="button" onclick="scanStorageDirectories()" class="inline-flex items-center gap-1.5 py-1 px-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:bg-slate-50 text-[10px] font-mono font-bold text-slate-600 dark:text-zinc-400 transition-all shadow-sm">
                                            <i class="fa-solid fa-arrows-rotate" id="scan-btn-icon"></i> Scan Folder
                                        </button>
                                    </div>
                                    <p class="text-[10px] text-slate-505 dark:text-zinc-500">Kosong = backup seluruh public storage.</p>
                                    <div id="storage-folders-list" class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1.5">
                                        @forelse($storageDirs as $dir)
                                            <label class="flex items-center justify-between p-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-indigo-500 cursor-pointer transition-all select-none">
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" name="storage_folders[]" value="{{ $dir['name'] }}" class="text-indigo-600 focus:ring-indigo-500" {{ in_array($dir['name'], $selectedFolders) ? 'checked' : '' }}>
                                                    <span class="text-xs font-mono text-slate-700 dark:text-zinc-300">{{ $dir['name'] }}</span>
                                                </div>
                                                <span class="text-[10px] font-mono font-bold text-slate-400 dark:text-zinc-500">{{ $dir['formatted_size'] }}</span>
                                            </label>
                                        @empty
                                            <div class="col-span-2 py-4 text-center text-slate-450 dark:text-zinc-550 font-mono text-[10px]">Tidak ada folder ditemukan.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Interval Penjadwalan</label>
                                    <select name="schedule" id="schedule-selector" onchange="toggleCronInput()"
                                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                        <option value="daily" {{ $backupSettings['schedule'] === 'daily' ? 'selected' : '' }}>Harian (00:00)</option>
                                        <option value="weekly" {{ $backupSettings['schedule'] === 'weekly' ? 'selected' : '' }}>Mingguan (Minggu 00:00)</option>
                                        <option value="monthly" {{ $backupSettings['schedule'] === 'monthly' ? 'selected' : '' }}>Bulanan (Tgl 1 00:00)</option>
                                        <option value="custom" {{ $backupSettings['schedule'] === 'custom' ? 'selected' : '' }}>Ekspresi Cron Kustom</option>
                                    </select>
                                </div>
                                <div id="cron-expression-wrapper" class="{{ $backupSettings['schedule'] === 'custom' ? '' : 'hidden' }}">
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Ekspresi Cron <span class="text-rose-500">*</span></label>
                                    <input type="text" name="cron_expression" value="{{ old('cron_expression', $backupSettings['cron_expression']) }}" placeholder="* * * * *"
                                           class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"/>
                                </div>
                            </div>
                        </div>

                        {{-- Right Panel --}}
                        <div class="space-y-6">
                            <div class="p-4 bg-indigo-50/20 dark:bg-zinc-950 border border-indigo-100 dark:border-zinc-800 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wide">Proteksi Enkripsi AES-256</span>
                                        <p class="text-[10px] text-slate-500 dark:text-zinc-400">Enkripsi berkas menggunakan algoritma OpenSSL militer.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer select-none" style="display:inline-flex!important;margin-bottom:0!important;">
                                        <input type="checkbox" name="encryption_enabled" id="encrypt-toggle" value="1" class="sr-only peer" {{ $backupSettings['encryption_enabled'] ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-500/20 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Kunci Enkripsi Backup</label>
                                    @if($hasPassphrase)
                                        <div class="p-3 bg-emerald-500/10 border border-emerald-500/25 space-y-3">
                                            <div class="flex items-center gap-2">
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                <span class="text-[11px] font-mono font-bold text-emerald-700 dark:text-emerald-400 uppercase">Kunci Aktif & Terpasang</span>
                                            </div>
                                            <p class="text-[10px] text-slate-550 dark:text-zinc-400">Kunci enkripsi 64-karakter hex telah dibuat. Seluruh backup berikutnya akan dienkripsi dengan kunci ini.</p>
                                            <div class="flex flex-wrap gap-2 pt-1">
                                                <button type="button" onclick="confirmKeyDownload()" class="inline-flex items-center gap-2 py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-mono font-bold text-[10px] uppercase tracking-wider transition-all shadow-sm">
                                                    <i class="fa-solid fa-download"></i> Download Kunci
                                                </button>
                                                <button type="button" onclick="confirmKeyRotation()" class="inline-flex items-center gap-2 py-2 px-4 bg-amber-600 hover:bg-amber-700 text-white font-mono font-bold text-[10px] uppercase tracking-wider transition-all shadow-sm">
                                                    <i class="fa-solid fa-rotate"></i> Rotasi Kunci
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-3 bg-rose-500/10 border border-rose-500/25 space-y-3">
                                            <div class="flex items-center gap-2">
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                                <span class="text-[11px] font-mono font-bold text-rose-700 dark:text-rose-400 uppercase">Kunci Belum Dibuat</span>
                                            </div>
                                            <p class="text-[10px] text-slate-550 dark:text-zinc-400">Buat kunci enkripsi terlebih dahulu untuk menggunakan fitur enkripsi backup.</p>
                                            <button type="button" onclick="submitGenerateKey()" class="inline-flex items-center gap-2 py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-mono font-bold text-[10px] uppercase tracking-wider transition-all shadow-sm">
                                                <i class="fa-solid fa-key"></i> Buat Kunci Enkripsi
                                            </button>
                                        </div>
                                    @endif
                                    <span class="text-[10px] text-rose-550/95 block mt-2 font-semibold leading-relaxed">
                                        <i class="fa-solid fa-circle-exclamation"></i> PENTING: Unduh dan simpan kunci. Berkas .enc tidak dapat dipulihkan tanpa kunci ini.
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wide">Sinkronisasi Google Drive</span>
                                        <p class="text-[10px] text-slate-550 dark:text-zinc-400">Unggah backup otomatis ke cloud Google Drive.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer select-none" style="display:inline-flex!important;margin-bottom:0!important;">
                                        <input type="checkbox" name="google_drive_enabled" value="1" class="sr-only peer" {{ $backupSettings['google_drive_enabled'] ? 'checked' : '' }} {{ !$hasGoogleCredentials ? 'disabled' : '' }}>
                                        <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-500/20 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 peer-disabled:opacity-50 peer-disabled:cursor-not-allowed"></div>
                                    </label>
                                </div>
                                @if(!$hasGoogleCredentials)
                                    <div class="p-3 bg-amber-500/10 border border-amber-500/25 text-[10px] text-amber-700 dark:text-amber-400 font-semibold">
                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Google Drive dinonaktifkan. Simpan Kredensial Google Service Account di halaman <a href="{{ route('admin.security.index') }}" class="underline">Keamanan</a> terlebih dahulu.
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Folder ID Google Drive (Opsional)</label>
                                    <input type="text" name="google_drive_folder_id" value="{{ old('google_drive_folder_id', $backupSettings['google_drive_folder_id']) }}"
                                           placeholder="ID Folder Google Drive (kosongkan = root)"
                                           class="w-full font-mono text-xs px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"/>
                                </div>
                            </div>

                            <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 space-y-3">
                                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Retensi Berkas Backup Lokal</label>
                                <div class="relative max-w-[200px]">
                                    <input type="number" name="retention_days" value="{{ old('retention_days', $backupSettings['retention_days']) }}" min="1" max="365"
                                           class="w-full font-mono text-sm px-3 py-2.5 pr-12 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"/>
                                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-mono text-slate-400 pointer-events-none">Hari</span>
                                </div>
                                <span class="text-[10px] text-slate-455 dark:text-zinc-555 block">Berkas backup yang lebih tua dari ini akan dihapus otomatis.</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-zinc-800">
                        <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#6366f1] text-white font-mono font-bold text-xs uppercase tracking-wider transition-all shadow-sm">
                            Simpan Konfigurasi Backup
                        </button>
                    </div>
                </form>
            </div>

            {{-- TAB 2: HISTORY & MANUAL RUN --}}
            <div id="tab-history" class="tab-content hidden space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="p-5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <span class="text-xs font-mono font-bold text-[#4f45b2] dark:text-indigo-400 uppercase tracking-widest block">EKSEKUSI MANUAL</span>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Jalankan Backup Instan</h2>
                            <p class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed">Picu kompresi, enkripsi, dan pengunggahan berkas secara instan tanpa menunggu jadwal.</p>
                        </div>
                        <button type="button" id="manual-backup-btn" onclick="triggerManualBackup()"
                                class="w-full py-3 px-4 bg-[#4f45b2] hover:bg-[#6366f1] text-white font-mono font-bold text-xs uppercase tracking-widest transition-all shadow-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-play"></i> JALANKAN BACKUP INSTAN
                        </button>
                    </div>

                    <div class="lg:col-span-2 p-4 bg-zinc-950 dark:bg-black border border-zinc-800 font-mono text-[11px] text-zinc-300 flex flex-col min-h-[140px] justify-between relative shadow-inner overflow-hidden">
                        <div class="absolute top-2 right-3 text-[10px] text-zinc-550 uppercase select-none tracking-widest font-mono">Terminal Output</div>
                        <div class="flex-1 overflow-y-auto space-y-1.5 max-h-[120px]" id="terminal-log-content">
                            <div class="text-zinc-500">&gt; Sistem siap menerima eksekusi manual...</div>
                        </div>
                        <div class="mt-3 pt-2 border-t border-zinc-800/80 flex items-center justify-between">
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

                <div class="space-y-3">
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Log Riwayat Backup</label>
                    <div class="border border-slate-200 dark:border-zinc-800 overflow-hidden shadow-sm bg-white dark:bg-zinc-900">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-zinc-950 text-slate-400 dark:text-zinc-500 border-b border-slate-250/60 dark:border-zinc-800 font-mono text-[10px] tracking-wider uppercase">
                                        <th class="py-3 px-4 font-bold">Tanggal</th>
                                        <th class="py-3 px-4 font-bold">Nama File</th>
                                        <th class="py-3 px-4 font-bold">Ukuran</th>
                                        <th class="py-3 px-4 font-bold">Enkripsi</th>
                                        <th class="py-3 px-4 font-bold">Google Drive</th>
                                        <th class="py-3 px-4 font-bold text-center">Status</th>
                                        <th class="py-3 px-4 font-bold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-zinc-855/80" id="backup-history-tbody">
                                    @forelse($backupHistory as $history)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-900/40 transition-colors">
                                        <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500 dark:text-zinc-400 whitespace-nowrap">{{ $history->created_at?->format('d-m-Y H:i:s') }}</td>
                                        <td class="py-3.5 px-4 font-medium text-slate-800 dark:text-zinc-200 font-mono text-[11px] break-all max-w-[200px]">{{ $history->filename }}</td>
                                        <td class="py-3.5 px-4 text-slate-600 dark:text-zinc-400 whitespace-nowrap">{{ $history->status === 'success' ? $history->formatted_size : '-' }}</td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if($history->encrypted)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-500/10 px-1.5 py-0.5 border border-indigo-500/15"><i class="fa-solid fa-lock text-[9px]"></i> AES-256</span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-550 bg-slate-500/10 px-1.5 py-0.5 border border-slate-500/10"><i class="fa-solid fa-lock-open text-[9px]"></i> Tidak</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if($history->drive_uploaded)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 border border-emerald-500/15"><i class="fa-brands fa-google-drive"></i> Berhasil</span>
                                            @elseif(!empty($history->drive_error))
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 bg-rose-500/10 px-1.5 py-0.5 border border-rose-500/15 cursor-help" title="{{ $history->drive_error }}"><i class="fa-solid fa-triangle-exclamation"></i> Gagal</span>
                                            @else
                                                <span class="text-[10px] text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                            @if($history->status === 'success')
                                                <span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-emerald-500 text-white border border-emerald-500/20">SUKSES</span>
                                            @else
                                                <span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-rose-500 text-white border border-rose-500/20">GAGAL</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap text-right space-x-1.5">
                                            <button type="button" onclick="showBackupLogDetails({{ $history->id }})"
                                                    class="inline-flex items-center justify-center w-7 h-7 bg-blue-50 dark:bg-zinc-800 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-zinc-700 hover:bg-blue-600 hover:text-white transition-colors" title="Detail">
                                                <i class="fa-solid fa-circle-info text-[11px]"></i>
                                            </button>
                                            @if($history->status === 'success')
                                                <a href="{{ route('admin.backup.download', ['filename' => $history->filename]) }}"
                                                   class="inline-flex items-center justify-center w-7 h-7 bg-indigo-50 dark:bg-zinc-800 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-zinc-700 hover:bg-indigo-600 hover:text-white transition-colors" title="Unduh">
                                                    <i class="fa-solid fa-download text-[11px]"></i>
                                                </a>
                                                <button type="button" onclick="prefillVerification('{{ $history->filename }}')"
                                                        class="inline-flex items-center justify-center w-7 h-7 bg-slate-100 dark:bg-zinc-800 text-slate-650 dark:text-zinc-350 border border-slate-250 dark:border-zinc-700 hover:bg-indigo-500 hover:text-white transition-colors" title="Uji Dekripsi">
                                                    <i class="fa-solid fa-shield-halved text-[11px]"></i>
                                                </button>
                                            @endif
                                            <button type="button" onclick="deleteBackup('{{ $history->filename }}')"
                                                    class="inline-flex items-center justify-center w-7 h-7 bg-rose-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-zinc-700 hover:bg-rose-600 hover:text-white transition-colors" title="Hapus">
                                                <i class="fa-solid fa-trash-can text-[11px]"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-slate-400 dark:text-zinc-550 font-mono text-[11px]">
                                            <i class="fa-solid fa-inbox text-lg block mb-2 opacity-50"></i> Belum ada riwayat backup.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 3: VERIFICATION --}}
            <div id="tab-verification" class="tab-content hidden space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="space-y-4">
                        <div class="p-4 bg-indigo-50/50 dark:bg-zinc-950 border border-indigo-100 dark:border-zinc-800 text-xs space-y-3">
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold font-mono tracking-wider uppercase block text-[10px]">VERIFIKATOR INTEGRITAS</span>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-zinc-200">Uji Mandiri Berkas Enkripsi</h2>
                            <p class="text-slate-650 dark:text-zinc-400 leading-relaxed">Tool ini mendekripsi berkas di memori server sementara untuk memverifikasi integritas dan validitas passphrase Anda.</p>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-[11px] space-y-2">
                            <span class="text-slate-700 dark:text-zinc-350 font-bold uppercase tracking-wider block">Verifikasi via CLI:</span>
                            <pre class="bg-zinc-950 text-emerald-400 p-2.5 overflow-x-auto text-[10px] font-mono border border-zinc-800">openssl enc -d -aes-256-cbc -pbkdf2 -iter 10000 -in [FILE].enc -out [FILE].zip</pre>
                        </div>
                    </div>
                    <div class="lg:col-span-2 space-y-5">
                        <div class="bg-slate-50 dark:bg-zinc-950 p-5 border border-slate-200 dark:border-zinc-800 space-y-4">
                            <h2 class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-widest">Validasi Uji Dekripsi</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Pilih File Backup Lokal</label>
                                    <select id="verify-filename"
                                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                        <option value="">-- Pilih Berkas --</option>
                                        @foreach($backupHistory as $history)
                                            @if($history['status'] === 'success')
                                                <option value="{{ $history['filename'] }}">{{ $history['filename'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Sandi Enkripsi (Passphrase)</label>
                                    <input type="password" id="verify-passphrase" placeholder="Kata sandi saat backup dibuat"
                                           class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"/>
                                </div>
                            </div>
                            <div class="flex justify-end pt-2">
                                <button type="button" id="verify-submit-btn" onclick="verifyBackupIntegrity()"
                                        class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#6366f1] text-white font-mono font-bold text-xs uppercase tracking-wider transition-all shadow-sm flex items-center gap-2">
                                    <i class="fa-solid fa-shield-check"></i> VERIFIKASI SEKARANG
                                </button>
                            </div>
                        </div>
                        <div id="verify-report-card" class="hidden p-5 border shadow-sm transition-all duration-300">
                            <div class="flex items-start gap-4" id="verify-report-header"></div>
                            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-zinc-800/80 grid grid-cols-1 sm:grid-cols-3 gap-4" id="verify-report-metrics"></div>
                            <div class="mt-5 space-y-2" id="verify-report-tree-wrapper">
                                <span class="text-[10px] font-mono font-bold uppercase text-slate-400 dark:text-zinc-500 block">Isi ZIP (Preview 10 Berkas):</span>
                                <div class="bg-zinc-950 text-emerald-400 p-4 border border-zinc-800 font-mono text-[11px] overflow-x-auto max-h-[220px] shadow-inner leading-relaxed" id="verify-report-tree"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Password Confirm Modal (menggunakan x-app-modal) ── --}}
<x-app-modal id="passwordConfirmModal" title="Konfirmasi Identitas" maxWidth="sm"
    :icon="'<svg fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\' stroke-width=\'1.8\' class=\'w-5 h-5\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z\' /></svg>'"
    iconColor="indigo">
    <form id="password-confirm-form" method="POST">
        @csrf
        <div class="space-y-1 mb-1">
            <label class="block text-[11px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Kata Sandi Akun</label>
            <input type="password" name="confirm_password" id="confirm_password_input"
                   placeholder="Masukkan kata sandi akun Anda"
                   class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"/>
        </div>
        <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1.5">Diperlukan untuk memverifikasi identitas Anda sebelum operasi sensitif ini dijalankan.</p>
    </form>
    <x-slot name="footer">
        <button type="button" onclick="AppModal.close('passwordConfirmModal')" class="modal-btn-cancel">Batal</button>
        <button type="button" onclick="document.getElementById('password-confirm-form').submit()" class="modal-btn-primary">
            <i class="fa-solid fa-lock-open"></i> Konfirmasi & Lanjutkan
        </button>
    </x-slot>
</x-app-modal>

{{-- ── Backup Log Detail Modal ── --}}
<x-app-modal id="backupLogDetailModal" title="Detail Log Backup" maxWidth="lg"
    :icon="'<svg fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\' stroke-width=\'1.8\' class=\'w-5 h-5\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\' /></svg>'"
    iconColor="indigo">
    <div id="backup-log-detail-content" class="space-y-4">
        <div class="grid grid-cols-2 gap-3" id="backup-log-detail-grid"></div>
        <div id="backup-log-error-block" class="hidden p-3 bg-rose-50/60 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900 text-[11px] text-rose-700 dark:text-rose-400 font-mono leading-relaxed"></div>
        <div id="backup-log-drive-block" class="hidden p-3 bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900">
            <span class="text-[10px] font-mono font-bold text-emerald-700 dark:text-emerald-400 uppercase block mb-1">Google Drive File ID</span>
            <span id="backup-log-drive-id" class="text-xs font-mono text-slate-700 dark:text-zinc-300 break-all"></span>
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" onclick="AppModal.close('backupLogDetailModal')" class="modal-btn-cancel">Tutup</button>
    </x-slot>
</x-app-modal>

<script>
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

    function toggleCronInput() {
        const schedule = document.getElementById('schedule-selector').value;
        document.getElementById('cron-expression-wrapper').classList.toggle('hidden', schedule !== 'custom');
    }

    function toggleStorageFolders() {
        const checkbox = document.getElementById('backup-storage-checkbox');
        const wrapper = document.getElementById('storage-folders-wrapper');
        if (checkbox && wrapper) wrapper.classList.toggle('hidden', !checkbox.checked);
    }

    function logToTerminal(message, type = 'info') {
        const terminal = document.getElementById('terminal-log-content');
        const line = document.createElement('div');
        const timestamp = new Date().toLocaleTimeString();
        const colorMap = { success: 'text-emerald-400 font-semibold', error: 'text-rose-500 font-semibold', warn: 'text-amber-400', system: 'text-indigo-400', info: 'text-zinc-300' };
        line.className = `${colorMap[type] || 'text-zinc-300'} leading-relaxed py-0.5`;
        line.innerHTML = `<span class="text-zinc-650">[${timestamp}]</span> ${message}`;
        terminal.appendChild(line);
        terminal.scrollTop = terminal.scrollHeight;
    }

    function triggerManualBackup() {
        const btn = document.getElementById('manual-backup-btn');
        const indicator = document.getElementById('terminal-indicator');
        const statusText = document.getElementById('terminal-status-text');
        const spinner = document.getElementById('terminal-spinner-wrapper');
        const content = document.getElementById('terminal-log-content');

        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        spinner.classList.remove('hidden');
        indicator.className = 'w-2 h-2 rounded-full bg-indigo-500 animate-ping';
        statusText.innerText = 'Status: Memproses...';
        content.innerHTML = '';
        logToTerminal('Memulai inisialisasi backup manual...', 'system');

        fetch("{{ route('admin.backup.run') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            if (!ok) return Promise.reject(data);
            logToTerminal('Inisialisasi berhasil!', 'success');
            logToTerminal(`Tipe: ${data.log.type}`, 'info');
            if (data.log.encrypted) logToTerminal('Enkripsi AES-256: AKTIF', 'success');
            if (data.log.drive_uploaded) logToTerminal(`Google Drive: SUKSES (${data.log.drive_file_id})`, 'success');
            else if (data.log.drive_error) logToTerminal(`Google Drive: GAGAL (${data.log.drive_error})`, 'error');
            logToTerminal(`Ukuran: ${data.log.formatted_size} | Durasi: ${data.log.duration}s`, 'system');
            logToTerminal(`Selesai! File: ${data.log.filename}`, 'success');
            indicator.className = 'w-2 h-2 rounded-full bg-emerald-500';
            statusText.innerText = 'Status: Sukses!';
            appendBackupLogToTable(data.log);
            btn.disabled = false; btn.classList.remove('opacity-50', 'cursor-not-allowed'); spinner.classList.add('hidden');
        })
        .catch(err => {
            logToTerminal(`GAGAL: ${err.message || err}`, 'error');
            indicator.className = 'w-2 h-2 rounded-full bg-rose-500';
            statusText.innerText = 'Status: Error!';
            if (err.log) appendBackupLogToTable(err.log);
            btn.disabled = false; btn.classList.remove('opacity-50', 'cursor-not-allowed'); spinner.classList.add('hidden');
        });
    }

    function appendBackupLogToTable(log) {
        const tbody = document.getElementById('backup-history-tbody');
        if (tbody.children.length === 1 && tbody.innerHTML.includes('Belum ada')) tbody.innerHTML = '';
        const downloadUrl = "{{ route('admin.backup.download', ['filename' => ':filename']) }}".replace(':filename', log.filename);
        const row = document.createElement('tr');
        row.className = "hover:bg-slate-50/60 dark:hover:bg-zinc-900/40 transition-colors";
        row.innerHTML = `<td class="py-3.5 px-4 font-mono text-[11px] text-slate-500 whitespace-nowrap">${log.formatted_date}</td>
            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-800 dark:text-zinc-200 break-all max-w-[200px]">${log.filename}</td>
            <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">${log.status === 'success' ? log.formatted_size : '-'}</td>
            <td class="py-3.5 px-4 whitespace-nowrap">${log.encrypted ? '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 bg-indigo-500/10 px-1.5 py-0.5 border border-indigo-500/15"><i class="fa-solid fa-lock text-[9px]"></i> AES-256</span>' : '<span class="text-[10px] text-slate-400">Tidak</span>'}</td>
            <td class="py-3.5 px-4 whitespace-nowrap"><span class="text-[10px] text-slate-400">-</span></td>
            <td class="py-3.5 px-4 text-center">${log.status === 'success' ? '<span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-emerald-500 text-white">SUKSES</span>' : '<span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-rose-500 text-white">GAGAL</span>'}</td>
            <td class="py-3.5 px-4 text-right space-x-1.5">${log.status === 'success' ? `<a href="${downloadUrl}" class="inline-flex items-center justify-center w-7 h-7 bg-indigo-50 dark:bg-zinc-800 text-indigo-600 border border-indigo-200 hover:bg-indigo-600 hover:text-white transition-colors"><i class="fa-solid fa-download text-[11px]"></i></a>` : ''}<button type="button" onclick="deleteBackup('${log.filename}')" class="inline-flex items-center justify-center w-7 h-7 bg-rose-50 dark:bg-zinc-800 text-rose-600 border border-rose-200 hover:bg-rose-600 hover:text-white transition-colors"><i class="fa-solid fa-trash-can text-[11px]"></i></button></td>`;
        tbody.insertBefore(row, tbody.firstChild);
    }

    function prefillVerification(filename) {
        document.getElementById('verify-filename').value = filename;
        switchTab('tab-verification');
        document.getElementById('verify-passphrase').focus();
    }

    function verifyBackupIntegrity() {
        const filename = document.getElementById('verify-filename').value;
        const passphrase = document.getElementById('verify-passphrase').value;
        const btn = document.getElementById('verify-submit-btn');
        if (!filename) { alert('Pilih berkas backup.'); return; }
        if (!passphrase) { alert('Masukkan sandi enkripsi.'); return; }

        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> MEMPROSES...';

        fetch("{{ route('admin.backup.verify') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ filename, passphrase })
        })
        .then(r => r.json().then(data => ({ status: r.status, body: data })))
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-shield-check"></i> VERIFIKASI SEKARANG';
            const reportCard = document.getElementById('verify-report-card');
            reportCard.classList.remove('hidden');
            if (res.status === 200 && res.body.success) {
                reportCard.className = "p-5 border border-emerald-250 dark:border-emerald-900 bg-emerald-50/15 shadow-sm transition-all duration-300";
                document.getElementById('verify-report-header').innerHTML = `<div class="p-2 bg-emerald-500 text-white flex-shrink-0"><i class="fa-solid fa-circle-check text-lg"></i></div><div><h3 class="text-sm font-bold text-emerald-800 dark:text-emerald-400">Integritas Berkas Terbukti Sempurna!</h3><p class="text-[11px] text-emerald-600 leading-relaxed mt-0.5">${res.body.message}</p></div>`;
                let tree = '';
                res.body.report.files.forEach(f => {
                    tree += `<div class="py-0.5 border-b border-zinc-900/60 flex justify-between"><span>${f.name.startsWith('database_dump') ? '<i class="fa-solid fa-database text-indigo-500"></i>' : '<i class="fa-regular fa-file text-slate-400"></i>'} ${f.name}</span><span class="text-zinc-550">${(f.size/1024).toFixed(1)} KB</span></div>`;
                });
                document.getElementById('verify-report-tree').innerHTML = tree;
            } else {
                reportCard.className = "p-5 border border-rose-250 dark:border-rose-955 bg-rose-50/15 shadow-sm transition-all duration-300";
                document.getElementById('verify-report-header').innerHTML = `<div class="p-2 bg-rose-500 text-white flex-shrink-0"><i class="fa-solid fa-triangle-exclamation text-lg"></i></div><div><h3 class="text-sm font-bold text-rose-800 dark:text-rose-400">Dekripsi Gagal!</h3><p class="text-[11px] text-rose-600 leading-relaxed mt-0.5">${res.body.message}</p></div>`;
                document.getElementById('verify-report-tree-wrapper').classList.add('hidden');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-shield-check"></i> VERIFIKASI SEKARANG';
        });
    }

    function deleteBackup(filename) {
        AppPopup.show({
            type: 'confirm',
            title: 'Hapus Berkas Backup?',
            description: `Apakah Anda yakin ingin menghapus <strong>${filename}</strong> secara permanen?`,
            confirmText: 'Ya, Hapus', cancelText: 'Batal',
            onConfirm: () => {
                const url = "{{ route('admin.backup.delete', ['filename' => ':filename']) }}".replace(':filename', filename);
                fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(r => r.json())
                .then(data => {
                    if (data.success) { AppPopup.success({ title: 'Berhasil!', description: data.message, duration: 2000 }); setTimeout(() => window.location.reload(), 2000); }
                    else AppPopup.error({ title: 'Gagal', description: data.message });
                });
            }
        });
    }

    function scanStorageDirectories() {
        const icon = document.getElementById('scan-btn-icon');
        const list = document.getElementById('storage-folders-list');
        icon.classList.add('fa-spin');
        fetch("{{ route('admin.backup.storage-directories') }}", { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            icon.classList.remove('fa-spin');
            if (data.success) {
                list.innerHTML = data.directories.length === 0
                    ? '<div class="col-span-2 py-4 text-center text-zinc-550 font-mono text-[10px]">Tidak ada folder.</div>'
                    : data.directories.map(d => `<label class="flex items-center justify-between p-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 hover:border-indigo-500 cursor-pointer transition-all select-none"><div class="flex items-center gap-2"><input type="checkbox" name="storage_folders[]" value="${d.name}" ${(data.selected_folders||[]).includes(d.name)?'checked':''} class="text-indigo-600"><span class="text-xs font-mono text-slate-700 dark:text-zinc-300">${d.name}</span></div><span class="text-[10px] font-mono font-bold text-slate-400">${d.formatted_size}</span></label>`).join('');
            }
        })
        .catch(() => icon.classList.remove('fa-spin'));
    }

    function showBackupLogDetails(id) {
        const url = "{{ route('admin.backup.log-details', ['id' => ':id']) }}".replace(':id', id);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const log = data.log;
            const statusBadge = log.status === 'success'
                ? '<span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-emerald-500 text-white">SUKSES</span>'
                : '<span class="inline-block text-[10px] font-bold px-2 py-0.5 bg-rose-500 text-white">GAGAL</span>';
            const encBadge = log.encrypted
                ? '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 bg-indigo-500/10 px-1.5 py-0.5 border border-indigo-500/15"><i class="fa-solid fa-lock text-[9px]"></i> AES-256</span>'
                : '<span class="text-[10px] text-slate-400">Tidak Terenkripsi</span>';
            const driveBadge = log.drive_uploaded
                ? '<span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-500/10 px-1.5 py-0.5 border border-emerald-500/15"><i class="fa-brands fa-google-drive"></i> Terunggah</span>'
                : '<span class="text-[10px] text-slate-400">Tidak</span>';

            const rows = [
                ['Nama Berkas', `<span class="font-mono text-[11px] break-all">${log.filename}</span>`],
                ['Status', statusBadge],
                ['Tanggal', log.created_at || '-'],
                ['Tipe Backup', log.type || '-'],
                ['Ukuran', data.formatted_size || '-'],
                ['Durasi Proses', log.duration ? `${log.duration}s` : '-'],
                ['Enkripsi', encBadge],
                ['Google Drive', driveBadge],
            ];

            document.getElementById('backup-log-detail-grid').innerHTML = rows.map(([label, val]) =>
                `<div class="p-3 bg-slate-50 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-800">
                    <span class="block text-[9px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">${label}</span>
                    <div class="text-sm text-slate-800 dark:text-zinc-200">${val}</div>
                </div>`
            ).join('');

            // Error block
            const errBlock = document.getElementById('backup-log-error-block');
            if (log.error_message) {
                errBlock.classList.remove('hidden');
                errBlock.innerHTML = `<span class="font-bold block mb-1 text-rose-700"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Pesan Error:</span>${log.error_message}`;
            } else { errBlock.classList.add('hidden'); }

            // Drive block
            const driveBlock = document.getElementById('backup-log-drive-block');
            if (log.drive_file_id) {
                driveBlock.classList.remove('hidden');
                document.getElementById('backup-log-drive-id').textContent = log.drive_file_id;
            } else { driveBlock.classList.add('hidden'); }

            AppModal.open('backupLogDetailModal');
        })
        .catch(() => AppPopup.show({ type: 'error', title: 'Gagal', description: 'Tidak dapat memuat detail log.' }));
    }

    function submitGenerateKey() { openPasswordConfirmModal('rotate'); }
    function confirmKeyDownload() {
        AppPopup.show({ type: 'custom', title: 'Unduh Kunci Enkripsi?', description: 'Kunci ini sangat rahasia. Simpan dengan aman!', confirmText: 'Unduh', cancelText: 'Batal',
            onConfirm: () => openPasswordConfirmModal('download') });
    }
    function confirmKeyRotation() {
        AppPopup.show({ type: 'warning', title: 'Rotasi Kunci?', description: 'Backup lama yang terenkripsi tidak bisa dibuka dengan kunci baru. Lanjutkan?', confirmText: 'Ya, Lanjutkan', cancelText: 'Batal',
            onConfirm: () => openPasswordConfirmModal('rotate') });
    }
    function openPasswordConfirmModal(type) {
        const form = document.getElementById('password-confirm-form');
        document.getElementById('confirm_password_input').value = '';
        form.action = type === 'download'
            ? "{{ route('admin.backup.download-key') }}"
            : "{{ route('admin.backup.generate-key') }}";
        AppModal.open('passwordConfirmModal');
    }
</script>
@endsection
