<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
    <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-800 flex items-center gap-2">
        <span
            class="text-xs font-bold font-mono uppercase tracking-wider text-slate-700 dark:text-zinc-300">Pilih
            Berkas Excel</span>
    </div>
    <div class="p-5">
        {{-- File Input --}}
        <input type="file" id="file_excel" accept=".xlsx,.xls" class="hidden" x-ref="fileInput" @change="handleFileSelect($event)">

        {{-- Dropzone Label (Native browser click behavior) --}}
        <label for="file_excel"
            @dragover.prevent="dragOver = true"
            @dragleave.prevent="dragOver = false"
            @drop.prevent="dragOver = false; handleFileDrop($event)"
            :class="dragOver ? 'border-[#4f45b2] dark:border-indigo-500 bg-indigo-50/20' : (hasFile ? 'border-emerald-400 dark:border-emerald-700 bg-emerald-50/30 dark:bg-emerald-950/10' : 'border-slate-300 dark:border-zinc-600 bg-slate-50 dark:bg-zinc-800/40 hover:border-[#4f45b2] dark:hover:border-indigo-500')"
            class="flex items-center justify-center min-h-[150px] w-full border-2 border-dashed rounded-none cursor-pointer transition-all duration-200">
            
            {{-- Empty Dropzone State --}}
            <div x-show="!hasFile" class="text-center space-y-3 px-6 py-6">
                <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-400 dark:text-zinc-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700 dark:text-zinc-300">Seret &amp; Lepas file di sini</p>
                    <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">atau <span
                            class="text-[#4f45b2] dark:text-indigo-400 font-bold underline underline-offset-2">klik untuk memilih file</span></p>
                </div>
                <p class="text-[10px] text-slate-400 dark:text-zinc-600 font-mono">Format: .XLSX atau .XLS &nbsp;|&nbsp; Maks. 5 MB</p>
            </div>

            {{-- File Info Dropzone State --}}
            <div x-show="hasFile" x-cloak class="w-full px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-14 shrink-0 bg-emerald-600 flex flex-col items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                        <path fill="rgba(255,255,255,0.25)" d="M14 2l6 6h-6V2z" />
                    </svg>
                    <span class="text-[9px] font-bold font-mono mt-1" x-text="fileExt">XLSX</span>
                </div>
                <div class="flex-1 min-w-0 text-left">
                    <p class="text-sm font-bold text-slate-800 dark:text-zinc-200 truncate" x-text="fileName"></p>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5" x-text="fileSize"></p>
                    <span
                        class="inline-flex items-center gap-1 mt-2 text-[10px] font-mono font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 px-2 py-0.5">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        FILE DIPILIH
                    </span>
                </div>
                {{-- Stops click event propagation & default action from opening the picker again --}}
                <button type="button" @click.stop.prevent="clearAll()"
                    class="shrink-0 p-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 text-rose-600 dark:text-rose-400 transition-all rounded-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </label>
    </div>
</div>
