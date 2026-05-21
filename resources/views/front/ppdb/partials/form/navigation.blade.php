<div class="flex items-center justify-between mt-10 pt-6 border-t border-slate-100">
    <div>
        <button type="button" @click="prevStep()" x-show="step > 1"
            class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-none hover:bg-slate-50 transition-colors"
            style="display: none;">
            Sebelumnya
        </button>
    </div>

    <div>
        <button type="button" @click="nextStep()" x-show="step < 3"
            class="px-6 py-2.5 bg-slate-900 hover:bg-black text-white font-bold text-xs uppercase tracking-wider rounded-none transition-colors">
            Selanjutnya
        </button>

        <button type="submit" x-show="step === 3"
            class="px-7 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-colors"
            style="display: none;">
            Kirim Pendaftaran
        </button>
    </div>
</div>
