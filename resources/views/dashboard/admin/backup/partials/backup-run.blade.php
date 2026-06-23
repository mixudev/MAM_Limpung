<div id="tab-history" class="tab-content hidden space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                    {{-- Left: Action Buttons --}}
                    <div class="lg:col-span-2 space-y-3">
                        <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 flex flex-col space-y-3">
                            <div class="space-y-1">
                                <span class="text-xs font-mono font-bold text-[#4f45b2] dark:text-indigo-400 uppercase tracking-widest block">BACKUP DATABASE</span>
                                <p class="text-[10px] text-slate-500 dark:text-zinc-400 leading-relaxed">Dump database MySQL + upload ke Google Drive.</p>
                            </div>
                            <button type="button" id="manual-backup-btn" onclick="triggerManualBackup()"
                                    class="w-full py-2.5 px-4 bg-[#4f45b2] hover:bg-[#6366f1] text-white font-mono font-bold text-xs uppercase tracking-widest transition-all shadow-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-play"></i> TEST BACKUP DATABASE
                            </button>
                        </div>

                        <div class="p-4 bg-emerald-50/20 dark:bg-zinc-950 border border-emerald-200 dark:border-zinc-800 flex flex-col space-y-3">
                            <div class="space-y-1">
                                <span class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest block">SINKRONISASI STORAGE</span>
                                <p class="text-[10px] text-slate-500 dark:text-zinc-400 leading-relaxed">Sync file storage ke Google Drive per file (queue job).</p>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] text-slate-500 dark:text-zinc-400">
                                <span>Status:</span>
                                <span id="sync-status-badge" class="inline-block text-[10px] font-bold px-2 py-0.5 {{ $syncSettings['enabled'] ? 'bg-emerald-500 text-white' : 'bg-slate-400 text-white' }}">{{ $syncSettings['enabled'] ? 'AKTIF' : 'NONAKTIF' }}</span>
                            </div>
                            <button type="button" id="storage-sync-btn" onclick="triggerStorageSync()" {{ $syncSettings['enabled'] ? '' : 'disabled' }}
                                    class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-mono font-bold text-xs uppercase tracking-widest transition-all shadow-sm flex items-center justify-center gap-2 {{ $syncSettings['enabled'] ? '' : 'opacity-50 cursor-not-allowed' }}">
                                <i class="fa-solid fa-cloud-arrow-up"></i> TEST SYNC STORAGE
                            </button>
                        </div>
                    </div>

                    {{-- Right: Terminal --}}
                    <div class="lg:col-span-3 p-4 bg-zinc-950 dark:bg-black border border-zinc-800 font-mono text-[11px] text-zinc-300 flex flex-col min-h-[260px] justify-between relative shadow-inner overflow-hidden">
                        <div class="absolute top-2 right-3 text-[10px] text-zinc-550 uppercase select-none tracking-widest font-mono">Terminal Output</div>
                        <div class="flex-1 overflow-y-auto space-y-1.5 max-h-[220px]" id="terminal-log-content">
                            <div class="text-zinc-500">&gt; Sistem siap menerima eksekusi manual...</div>
                        </div>

                        {{-- Progress Bar --}}
                        <div id="progress-bar-wrapper" class="hidden mt-2 mb-1">
                            <div class="flex items-center justify-between mb-1">
                                <span id="progress-step-label" class="text-[10px] text-zinc-400">Memulai...</span>
                                <span id="progress-pct-label" class="text-[10px] text-zinc-500">0%</span>
                            </div>
                            <div class="w-full h-1.5 bg-zinc-800 rounded-full overflow-hidden">
                                <div id="progress-bar-fill" class="h-full bg-gradient-to-r from-indigo-500 to-emerald-500 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="mt-2 pt-2 border-t border-zinc-800/80 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-zinc-650" id="terminal-indicator"></div>
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
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Log Riwayat Backup Database</label>
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

                {{-- Storage Sync Log Table (minimal + paginated) --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Log Sinkronisasi Storage</label>
                        <div class="flex items-center gap-2">
                            <span id="sync-log-summary" class="text-[10px] font-mono text-slate-400 dark:text-zinc-500"></span>
                            <button type="button" onclick="confirmClearSyncLogs()" class="inline-flex items-center gap-1 py-1 px-2 text-[10px] font-mono text-rose-500 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition-colors">
                                <i class="fa-solid fa-trash-can text-[9px]"></i> Bersihkan
                            </button>
                            <button type="button" onclick="loadSyncLogs(1)" class="inline-flex items-center gap-1 py-1 px-2 text-[10px] font-mono text-slate-500 dark:text-zinc-400 hover:text-indigo-600 transition-colors">
                                <i class="fa-solid fa-arrows-rotate text-[9px]" id="sync-log-refresh-icon"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="border border-slate-200 dark:border-zinc-800 overflow-hidden shadow-sm bg-white dark:bg-zinc-900">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-zinc-950 text-slate-400 dark:text-zinc-500 border-b border-slate-200 dark:border-zinc-800 font-mono text-[10px] tracking-wider uppercase">
                                        <th class="py-2.5 px-3 w-8 text-center">#</th>
                                        <th class="py-2.5 px-3">File</th>
                                        <th class="py-2.5 px-3 w-20 text-right">Ukuran</th>
                                        <th class="py-2.5 px-3 w-16 text-center">Status</th>
                                        <th class="py-2.5 px-3 w-32">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800" id="sync-log-tbody">
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400 dark:text-zinc-500 font-mono text-[11px]">
                                            <i class="fa-solid fa-inbox text-lg block mb-2 opacity-50"></i> Muat log sinkronisasi...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Pagination --}}
                    <div id="sync-log-pagination" class="flex items-center justify-between py-2 hidden">
                        <button type="button" id="sync-log-prev" onclick="loadSyncLogs(currentSyncPage - 1)"
                                class="py-1.5 px-3 text-[10px] font-mono font-bold uppercase tracking-wider bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700 transition-colors disabled:opacity-30 disabled:cursor-not-allowed border border-slate-200 dark:border-zinc-700">
                            <i class="fa-solid fa-chevron-left mr-1"></i> Sebelumnya
                        </button>
                        <div id="sync-log-page-numbers" class="flex items-center gap-1"></div>
                        <button type="button" id="sync-log-next" onclick="loadSyncLogs(currentSyncPage + 1)"
                                class="py-1.5 px-3 text-[10px] font-mono font-bold uppercase tracking-wider bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700 transition-colors disabled:opacity-30 disabled:cursor-not-allowed border border-slate-200 dark:border-zinc-700">
                            Selanjutnya <i class="fa-solid fa-chevron-right ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>