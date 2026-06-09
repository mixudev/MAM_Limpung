<!-- Modal: Activity Details (Data Changes Comparison) - FULLY RESPONSIVE -->
<div id="activityDetailsModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 max-w-4xl w-full flex flex-col max-h-[85vh] shadow-xl">
        <div class="p-5 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center bg-slate-50 dark:bg-zinc-950/30">
            <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-100 uppercase tracking-wider font-mono">
                Log Perubahan: <span id="act_event" class="text-[#4f45b2] dark:text-indigo-400"></span> - <span id="act_model"></span>
            </h3>
            <button type="button" onclick="closeActivityModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 text-xs space-y-5">
            <!-- Metadata Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 rounded-lg">
                <div class="space-y-1">
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase font-mono text-[9px] tracking-wider">Dilakukan Oleh</span>
                    <span id="act_causer" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase font-mono text-[9px] tracking-wider">Waktu Kejadian</span>
                    <span id="act_time" class="font-bold text-slate-800 dark:text-zinc-200 text-xs"></span>
                </div>
                <div class="col-span-1 md:col-span-2 space-y-1 pt-2 border-t border-slate-200/60 dark:border-zinc-800/80">
                    <span class="text-slate-400 dark:text-zinc-500 block uppercase font-mono text-[9px] tracking-wider">Kredensial Jaringan & Perangkat</span>
                    <span id="act_ip" class="font-mono font-semibold text-[#4f45b2] dark:text-indigo-400 block"></span>
                    <div id="act_ua" class="text-[10px] text-slate-500 dark:text-zinc-400 font-mono mt-1 leading-relaxed bg-white dark:bg-zinc-950 p-2 border border-slate-200 dark:border-zinc-800 overflow-x-auto whitespace-pre-wrap break-all"></div>
                </div>
            </div>

            <!-- Diff Table -->
            <div>
                <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider mb-2 font-mono">
                    Perbandingan Perubahan Data (Data Diff):
                </h4>
                <div class="border border-slate-200 dark:border-zinc-800 rounded-lg overflow-hidden">
                    <table class="w-full text-left border-collapse diff-responsive-table">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-zinc-950 font-mono font-bold uppercase tracking-wider text-[9px] text-slate-500 dark:text-zinc-400 border-b border-slate-200 dark:border-zinc-800">
                                <th class="py-2.5 px-4 min-w-32">Kolom / Atribut</th>
                                <th class="py-2.5 px-4 w-96 bg-rose-500/5 text-rose-700 dark:text-rose-450">Sebelum (Lama)</th>
                                <th class="py-2.5 px-4 w-96 bg-emerald-500/5 text-emerald-700 dark:text-emerald-450">Sesudah (Baru)</th>
                            </tr>
                        </thead>
                        <tbody id="diff_body" class="divide-y divide-slate-100 dark:divide-zinc-800 font-mono text-[11px] leading-relaxed">
                            <!-- Diff rows will go here via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/30 flex justify-end">
            <button type="button" onclick="closeActivityModal()" 
                    class="py-2 px-6 bg-slate-200 hover:bg-slate-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 font-mono font-bold text-xs uppercase tracking-wider transition-all">
                Tutup Perbandingan
            </button>
        </div>
    </div>
</div>