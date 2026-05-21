<div class="mb-12 border-b border-slate-100 pb-8">
    <div class="flex items-center justify-between max-w-md mx-auto relative">
        <div class="absolute left-6 right-6 top-[18px] h-0.5 border-t-2 border-dashed border-slate-200 z-0"></div>
        <div class="absolute left-6 top-[18px] h-0.5 border-t-2 border-dashed border-emerald-800 transition-all duration-500 ease-out z-0"
             :style="'width: ' + ((step - 1) * 50) + '%'"></div>

        <div class="relative z-10 flex flex-col items-center gap-2 cursor-pointer group" @click="if(step > 1) step = 1">
            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300 border-2 z-10"
                :class="step > 1 ? 'bg-emerald-800 border-emerald-800 text-white shadow-md group-hover:scale-105 group-hover:bg-emerald-900' : (step === 1 ? 'bg-emerald-800 border-emerald-800 text-white shadow-lg ring-4 ring-emerald-800/10 scale-110 font-bold' : 'bg-white border-slate-300 text-slate-400 group-hover:border-slate-400')">
                <span x-show="step > 1"><i class="fa-solid fa-check text-[10px]"></i></span>
                <span x-show="step <= 1">1</span>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider font-mono transition-colors duration-200"
                :class="step >= 1 ? 'text-emerald-800' : 'text-slate-400'">Profil</span>
        </div>

        <div class="relative z-10 flex flex-col items-center gap-2 cursor-pointer group" @click="if(step > 2) step = 2">
            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300 border-2 z-10"
                :class="step > 2 ? 'bg-emerald-800 border-emerald-800 text-white shadow-md group-hover:scale-105 group-hover:bg-emerald-900' : (step === 2 ? 'bg-emerald-800 border-emerald-800 text-white shadow-lg ring-4 ring-emerald-800/10 scale-110 font-bold' : 'bg-white border-slate-300 text-slate-400 group-hover:border-slate-400')">
                <span x-show="step > 2"><i class="fa-solid fa-check text-[10px]"></i></span>
                <span x-show="step <= 2">2</span>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider font-mono transition-colors duration-200"
                :class="step >= 2 ? 'text-emerald-800' : 'text-slate-400'">Kontak & Wali</span>
        </div>

        <div class="relative z-10 flex flex-col items-center gap-2 group">
            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300 border-2 z-10"
                :class="step === 3 ? 'bg-emerald-800 border-emerald-800 text-white shadow-lg ring-4 ring-emerald-800/10 scale-110 font-bold' : 'bg-white border-slate-300 text-slate-400'">
                <span>3</span>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider font-mono transition-colors duration-200"
                :class="step === 3 ? 'text-emerald-800' : 'text-slate-400'">Dokumen</span>
        </div>
    </div>
</div>
