<!-- Modal: Failed Job Exception Details -->
<div id="failedJobModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 max-w-4xl w-full flex flex-col max-h-[85vh] shadow-xl">
        <div class="p-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center bg-slate-50 dark:bg-zinc-950/30">
            <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-100 uppercase tracking-wider font-mono">
                Log Detail Pekerjaan Gagal (UUID: <span id="job_uuid" class="text-rose-500"></span>)
            </h3>
            <button type="button" onclick="closeFailedJobModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 text-xs space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 rounded-lg font-mono">
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px] tracking-wider">Koneksi</span>
                    <span id="job_connection" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px] tracking-wider">Queue</span>
                    <span id="job_queue" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div>
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase text-[9px] tracking-wider">Gagal Pada</span>
                    <span id="job_time" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider mb-2 font-mono">Stack Trace / Exception:</h4>
                <pre id="job_exception" class="p-4 bg-rose-50 dark:bg-rose-950/10 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-450 font-mono text-[10px] overflow-x-auto overflow-y-auto max-h-96 leading-relaxed whitespace-pre-wrap select-all rounded-lg"></pre>
            </div>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/30 flex justify-end gap-2">
            <form id="job_retry_form" method="POST" class="inline-block">
                @csrf
                <button type="submit" 
                        class="py-2 px-6 bg-emerald-600 hover:bg-emerald-700 text-white font-mono font-bold text-xs uppercase tracking-wider transition-colors shadow-sm">
                    Retry Job
                </button>
            </form>
            <button type="button" onclick="closeFailedJobModal()" 
                    class="py-2 px-6 bg-slate-200 hover:bg-slate-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 font-mono font-bold text-xs uppercase tracking-wider transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>