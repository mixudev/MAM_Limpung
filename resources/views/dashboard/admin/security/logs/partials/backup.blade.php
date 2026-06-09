<!-- Modal: Backup Log Details (NEW) -->
<div id="backupDetailsModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 max-w-3xl w-full flex flex-col max-h-[85vh] shadow-xl">
        <div class="p-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center bg-slate-50 dark:bg-zinc-950/30">
            <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-100 uppercase tracking-wider font-mono">
                Detail Log Backup Sistem
            </h3>
            <button type="button" onclick="closeBackupModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 text-xs space-y-4">
            <!-- Summary Information -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 rounded-lg font-mono">
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px]">Status</span>
                    <span id="bak_status" class="font-bold text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px]">Jenis</span>
                    <span id="bak_type" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px]">Ukuran File</span>
                    <span id="bak_size" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px]">Waktu Backup</span>
                    <span id="bak_time" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
            </div>

            <!-- Filename Block -->
            <div class="p-3 bg-white dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 rounded-lg">
                <span class="text-slate-400 dark:text-zinc-500 font-mono text-[9px] uppercase tracking-wider block mb-1">Nama Berkas (Filename)</span>
                <span id="bak_filename" class="font-mono font-bold text-slate-800 dark:text-zinc-200 text-xs select-all"></span>
            </div>

            <!-- Google Drive Cloud Upload details -->
            <div class="p-3 bg-indigo-50/50 dark:bg-indigo-950/10 border border-indigo-100 dark:border-indigo-900/30 rounded-lg">
                <span class="text-indigo-500 dark:text-indigo-400 font-mono text-[9px] uppercase tracking-wider block mb-2 font-bold">Laporan Unggah Awan (Google Drive Sync):</span>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[9px] font-mono">TUNGGAH BERHASIL?</span>
                        <span id="bak_drive_uploaded" class="font-bold font-mono"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[9px] font-mono">ID FILE GOOGLE DRIVE</span>
                        <span id="bak_drive_id" class="font-mono font-bold text-slate-800 dark:text-zinc-200 select-all block truncate"></span>
                    </div>
                    <div class="col-span-1 md:col-span-2 hidden" id="bak_drive_error_area">
                        <span class="text-rose-500 block text-[9px] font-mono font-bold">ERROR UPLOAD DRIVE</span>
                        <span id="bak_drive_error" class="font-mono text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 p-2 block border border-rose-200 dark:border-rose-900/50 select-all"></span>
                    </div>
                </div>
            </div>

            <!-- Error message (Visible only if backup failed) -->
            <div id="bak_error_card" class="p-4 bg-rose-50 dark:bg-rose-950/10 border border-rose-200 dark:border-rose-900/50 rounded-lg hidden">
                <h4 class="text-xs font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wider mb-2 font-mono flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Pesan Kesalahan Sistem (System Error Message):
                </h4>
                <p id="bak_error_msg" class="font-mono text-rose-700 dark:text-rose-450 leading-relaxed bg-white dark:bg-zinc-950 p-3 border border-rose-200 dark:border-rose-900/40 select-all whitespace-pre-wrap overflow-x-auto max-h-40"></p>
            </div>

            <!-- Details metadata JSON -->
            <div>
                <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider mb-2 font-mono">
                    Laporan Rincian Log (Metadata JSON):
                </h4>
                <pre id="bak_details" class="p-4 bg-slate-50 dark:bg-zinc-950/60 border border-slate-200 dark:border-zinc-800 text-slate-600 dark:text-zinc-400 font-mono text-[10px] overflow-x-auto max-h-48 leading-relaxed rounded-lg"></pre>
            </div>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/30 flex justify-end">
            <button type="button" onclick="closeBackupModal()" 
                    class="py-2 px-6 bg-slate-200 hover:bg-slate-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 font-mono font-bold text-xs uppercase tracking-wider transition-colors">
                Tutup Detail
            </button>
        </div>
    </div>
</div>