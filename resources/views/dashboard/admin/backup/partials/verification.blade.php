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