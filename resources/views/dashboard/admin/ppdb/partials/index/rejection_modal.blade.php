<!-- 6. Custom Modal Rejection Reason -->
<div id="rejectionModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs transition-opacity" onclick="closeRejectionModal()"></div>
        
        <div class="inline-block w-full max-w-md bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-2xl p-6 text-left transform transition-all rounded-none relative z-10">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2" id="rejectModalTitle">Tolak Pendaftaran</h3>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mb-4">Berikan catatan alasan yang valid mengapa berkas calon siswa ini ditolak. Alasan ini akan dibaca oleh siswa di laman cek status.</p>
            
            <form id="rejectionForm" action="" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="catatan_admin" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-1">Alasan Penolakan</label>
                    <textarea name="catatan_admin" id="catatan_admin" rows="4" placeholder="Contoh: NISN tidak valid / Dokumen pas foto tidak buram dan jelas..." required
                        class="w-full p-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-red-400/20 focus:border-red-500"></textarea>
                </div>

                <div class="flex items-center gap-2.5 pt-2">
                    <button type="button" onclick="closeRejectionModal()" class="flex-1 py-2.5 px-4 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-300 font-bold text-xs rounded-none transition-all active:scale-[.98]">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white font-bold text-xs rounded-none transition-all active:scale-[.98]">
                        Tolak & Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
