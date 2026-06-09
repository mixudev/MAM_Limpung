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