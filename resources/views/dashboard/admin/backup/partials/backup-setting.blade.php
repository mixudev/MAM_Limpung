            <div id="tab-settings" class="tab-content space-y-6">
                <form action="{{ route('admin.backup.settings') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Left Panel --}}
                        <div class="space-y-6">
                            <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-blue-400 dark:border-zinc-800 flex items-center justify-between">
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
                                <div class="grid grid-cols-1 gap-4">
                                    <label class="flex items-start gap-3 p-3.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 cursor-pointer select-none" style="display:flex!important;margin-bottom:0!important;">
                                        <input type="checkbox" name="backup_db" value="1" class="mt-0.5 text-indigo-600 focus:ring-indigo-500 border-slate-350 dark:border-zinc-800" {{ $backupSettings['backup_db'] ? 'checked' : '' }}>
                                        <div class="space-y-0.5">
                                            <span class="text-xs font-bold text-slate-800 dark:text-zinc-200 block">Database MySQL</span>
                                            <p class="text-[10px] text-slate-550 dark:text-zinc-400">Skema & seluruh baris data tabel — diproses cepat & ringan.</p>
                                        </div>
                                    </label>
                                </div>
                                <div class="p-3 bg-indigo-50/20 dark:bg-zinc-950 border border-indigo-100 dark:border-zinc-800">
                                    <div class="flex items-start gap-2">
                                        <i class="fa-solid fa-arrows-rotate text-indigo-500 mt-0.5 shrink-0"></i>
                                        <div>
                                            <span class="text-[11px] font-bold text-indigo-700 dark:text-indigo-400 block">File Storage dipisahkan ke Sinkronisasi Google Drive</span>
                                            <p class="text-[10px] text-slate-500 dark:text-zinc-400 leading-relaxed mt-0.5">File storage (upload) tidak lagi masuk ke ZIP backup. Sebagai gantinya, file-file tersebut akan di-sinkronisasi langsung ke Google Drive secara bertahap per file (chunked jobs), sehingga proses backup database tetap cepat dan tidak timeout meskipun storage besar.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Interval Penjadwalan</label>
                                    <select name="schedule" id="schedule-selector" onchange="updateSchedulePreview()"
                                            class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                        <option value="daily" {{ $backupSettings['schedule'] === 'daily' ? 'selected' : '' }}>Setiap Hari — tengah malam (00:00)</option>
                                        <option value="weekly" {{ $backupSettings['schedule'] === 'weekly' ? 'selected' : '' }}>Setiap Minggu — Minggu 00:00</option>
                                        <option value="monthly" {{ $backupSettings['schedule'] === 'monthly' ? 'selected' : '' }}>Setiap Bulan — Tgl 1 00:00</option>
                                        <option value="custom" {{ $backupSettings['schedule'] === 'custom' ? 'selected' : '' }}>Kustom — Ekspresi Cron</option>
                                    </select>
                                </div>

                                {{-- Cron custom input --}}
                                <div id="cron-expression-wrapper" class="{{ $backupSettings['schedule'] === 'custom' ? '' : 'hidden' }} space-y-3">
                                    <div>
                                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Ekspresi Cron <span class="text-rose-500">*</span></label>
                                        <input type="text" name="cron_expression" id="cron-input"
                                               value="{{ old('cron_expression', $backupSettings['cron_expression']) }}"
                                               placeholder="0 2 * * *"
                                               oninput="updateCronPreview()"
                                               class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"/>
                                    </div>
                                    {{-- Cron quick presets --}}
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="text-[10px] font-mono text-slate-400 dark:text-zinc-500 self-center">Preset:</span>
                                        @foreach([
                                            ['0 2 * * *', 'Tiap hari 02:00'],
                                            ['0 1 * * 0', 'Tiap Minggu 01:00'],
                                            ['0 0 1 * *', 'Tiap Tgl 1'],
                                            ['0 */6 * * *', 'Tiap 6 jam'],
                                            ['0 0 * * 1-5', 'Hari kerja 00:00'],
                                        ] as [$expr, $label])
                                        <button type="button"
                                                onclick="setCronPreset('{{ $expr }}')"
                                                class="px-2 py-1 text-[10px] font-mono bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 border border-slate-200 dark:border-zinc-700 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all">
                                            {{ $label }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Schedule preview --}}
                                <div id="schedule-preview" class="flex items-start gap-2.5 px-3 py-2.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800">
                                    <i class="fa-regular fa-clock text-indigo-500 mt-0.5 shrink-0"></i>
                                    <div>
                                        <span class="text-[10px] font-mono font-bold uppercase text-slate-400 dark:text-zinc-500 block">Jadwal Aktif:</span>
                                        <span id="schedule-preview-text" class="text-[11px] text-slate-700 dark:text-zinc-300 font-mono"></span>
                                    </div>
                                </div>

                                {{-- Local dev note --}}
                                <!-- <div class="flex items-start gap-2.5 px-3 py-2.5 bg-amber-500/10 border border-amber-500/25">
                                    <i class="fa-solid fa-circle-info text-amber-500 mt-0.5 shrink-0 text-xs"></i>
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-mono font-bold uppercase text-amber-700 dark:text-amber-400 block">Cara Mengaktifkan Scheduler</span>
                                        <p class="text-[10px] text-slate-600 dark:text-zinc-400">Scheduler Laravel membutuhkan satu cron entry di server:</p>
                                        <div class="bg-zinc-900 border border-zinc-700 p-2 font-mono text-[10px] text-emerald-400">
                                            * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
                                        </div>
                                        <p class="text-[10px] text-slate-500 dark:text-zinc-500">Di <strong>lokal/Windows</strong>: scheduler tidak berjalan otomatis karena tidak ada cron daemon. Gunakan <span class="font-mono bg-slate-200 dark:bg-zinc-800 px-1">php artisan schedule:run</span> atau jalankan backup manual dari tab <strong>Riwayat & Manual Run</strong>.</p>
                                    </div>
                                </div> -->
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
                                        <input type="checkbox" name="encryption_enabled" id="encrypt-toggle" value="1" class="sr-only peer" {{ $backupSettings['encryption_enabled'] ? 'checked' : '' }} {{ !$hasEncryptionKey ? 'disabled' : '' }}>
                                        <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-500/20 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 peer-disabled:opacity-40 peer-disabled:cursor-not-allowed"></div>
                                    </label>
                                </div>

                                {{-- Encryption key status --}}
                                @if($hasEncryptionKey)
                                    <div class="flex items-center px-3 py-2.5 bg-emerald-500/10 border border-emerald-500/25">
                                        
                                        <div>
                                            <span class="text-[11px] font-mono font-bold text-emerald-700 dark:text-emerald-400 uppercase block">Kunci Aktif di .env</span>
                                            <span class="text-[10px] text-slate-500 dark:text-zinc-500">BACKUP_ENCRYPTION_KEY sudah terpasang di server.</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-start gap-2 px-3 py-2.5 bg-rose-500/10 border border-rose-500/25">
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500 shrink-0 mt-0.5"></span>
                                        <div class="space-y-1.5">
                                            <span class="text-[11px] font-mono font-bold text-rose-700 dark:text-rose-400 uppercase block">Kunci Belum Ada di .env</span>
                                            <p class="text-[10px] text-slate-500 dark:text-zinc-400">Generate kunci lalu isi di <span class="font-mono">.env</span> server:</p>
                                            <div class="bg-zinc-900 border border-zinc-700 p-2 font-mono text-[10px] text-emerald-400 space-y-1">
                                                <div class="text-zinc-500"># 1. Generate kunci (jalankan di server):</div>
                                                <div>php artisan tinker --execute <span class="text-amber-300">"echo base64_encode(random_bytes(32));"</span></div>
                                                <div class="text-zinc-500 mt-1"># 2. Isi hasil di .env:</div>
                                                <div>BACKUP_ENCRYPTION_KEY=<span class="text-amber-300">hasil_dari_command</span></div>
                                                <div class="text-zinc-500 mt-1"># 3. Clear config:</div>
                                                <div>php artisan config:clear</div>
                                            </div>
                                            <p class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold">
                                                <i class="fa-solid fa-triangle-exclamation"></i> Simpan kunci di luar server (password manager). Kehilangan kunci = tidak bisa buka backup.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wide">Backup — Unggah ke Google Drive</span>
                                        <p class="text-[10px] text-slate-550 dark:text-zinc-400">Unggah file backup database ke cloud Google Drive.</p>
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
                                    <div class="flex items-center gap-2 mb-2">
                                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Folder ID Google Drive <span class="text-[9px] font-normal text-slate-500 dark:text-zinc-600 lowercase">(opsional)</span></label>
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-slate-100 dark:bg-zinc-800 text-[9px] font-mono text-slate-500 dark:text-zinc-400 border border-slate-200 dark:border-zinc-700 cursor-help" title="Buat folder baru di Google Drive, set akses ke Restricted, share ke email OAuth2 Anda. Masukkan ID folder di sini.">
                                            <i class="fa-solid fa-shield-halved text-[8px]"></i> Restricted
                                        </span>
                                    </div>
                                    <input type="text" name="google_drive_folder_id" value="{{ old('google_drive_folder_id', $backupSettings['google_drive_folder_id']) }}"
                                           placeholder="Kosongkan = simpan di My Drive / {{ trim(config('app.name')) }} - Backup"
                                           class="w-full font-mono text-xs px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"/>
                                    <p class="text-[9px] text-slate-400 dark:text-zinc-600 mt-1 leading-relaxed">
                                        <i class="fa-solid fa-circle-info text-[8px] mr-0.5"></i>
                                        Buat folder di Drive → set <strong class="text-slate-600 dark:text-zinc-400">General access</strong> ke <strong class="text-emerald-600 dark:text-emerald-400">Restricted</strong> → share ke email OAuth2 Anda. Jika dikosongkan, folder <strong class="text-slate-600 dark:text-zinc-400">{{ trim(config('app.name')) }} - Backup</strong> dibuat otomatis di My Drive root.
                                    </p>
                                </div>
                            </div>

                            {{-- Storage Sync Settings --}}
                            <div class="p-4 bg-emerald-50/20 dark:bg-zinc-950 border border-emerald-200 dark:border-zinc-800 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-mono font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">Sinkronisasi File Storage ke Google Drive</span>
                                        <p class="text-[10px] text-slate-550 dark:text-zinc-400">File storage di-sync langsung per file (bukan ZIP) — aman untuk storage besar.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer select-none" style="display:inline-flex!important;margin-bottom:0!important;">
                                        <input type="checkbox" id="sync-enabled-toggle" value="1" class="sr-only peer" {{ $syncSettings['enabled'] ? 'checked' : '' }} {{ !$hasGoogleCredentials ? 'disabled' : '' }} onchange="toggleSyncSetting(this.checked)">
                                        <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-800 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-500/20 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600 peer-disabled:opacity-50 peer-disabled:cursor-not-allowed"></div>
                                    </label>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-[10px] text-slate-600 dark:text-zinc-400">
                                        <i class="fa-solid fa-clock"></i>
                                        <span>Jadwal otomatis: <strong class="text-emerald-600 dark:text-emerald-400">Setiap hari pukul 02:00</strong></span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-600 dark:text-zinc-400">
                                        <i class="fa-solid fa-layer-group"></i>
                                        <span>Metode: <strong class="text-indigo-600 dark:text-indigo-400">Queue job per file</strong> — diunggah bertahap</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-600 dark:text-zinc-400">
                                        <i class="fa-solid fa-list-check"></i>
                                        <span>Tracking: <strong class="text-indigo-600 dark:text-indigo-400">Log sinkronisasi</strong> — hash file, status, riwayat</span>
                                    </div>
                                </div>
                                @if(!$hasGoogleCredentials)
                                    <div class="p-3 bg-amber-500/10 border border-amber-500/25 text-[10px] text-amber-700 dark:text-amber-400 font-semibold">
                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Aktifkan kredensial Google Drive di halaman <a href="{{ route('admin.security.index') }}" class="underline">Keamanan</a> terlebih dahulu.
                                    </div>
                                @endif
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