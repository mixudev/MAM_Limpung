<div id="step-3" x-show="step === 3" class="space-y-6" style="display: none;">
    <div class="border-b border-slate-100 pb-3 mb-6">
        <h2 class="text-base font-bold text-slate-800 uppercase tracking-wider font-mono">Langkah 3: Berkas Persyaratan & Informasi Kustom</h2>
        <p class="text-xs text-slate-400 mt-1">Unggah scan kelengkapan dokumen pendukung dan lengkapi kolom kustom PPDB.</p>
    </div>

    <div class="bg-slate-50 border border-slate-200 p-4 text-xs text-slate-500 leading-normal flex items-start gap-2.5">
        <svg class="w-4 h-4 text-emerald-800 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>
            <strong>Draf Tersimpan Otomatis:</strong> Isian teks Anda telah tersimpan secara otomatis di browser lokal. Apabila Anda tidak sengaja keluar, data teks akan tetap ada. Namun, demi alasan keamanan browser, <strong>berkas dokumen dan foto</strong> di bawah harus diunggah ulang apabila Anda memuat ulang halaman ini.
        </span>
    </div>

    @if(!empty($requirements))
    <div class="space-y-4">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest font-mono mb-2">Dokumen Persyaratan Mandiri</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($requirements as $req)
            @if($req['id'] !== 'foto')
            @php $docTemp = $ppdbTempUploads[$req['id']] ?? null; @endphp
            <div class="bg-slate-50 border border-slate-200 p-4 rounded-sm hover:border-amber-500 transition-all @error($req['id']) !border-red-500 @enderror">
                <label class="form-label-premium">
                    {{ $req['label'] }}{{ $req['required'] ? ' *' : '' }}
                </label>
                <input type="file" name="{{ $req['id'] }}"
                    class="ppdb-doc-input w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-sm file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-slate-650 hover:file:bg-slate-50 cursor-pointer"
                    accept=".pdf,.jpg,.jpeg,.png"
                    {{ ($req['required'] && ! $docTemp) ? 'required' : '' }}>
                <div class="ppdb-doc-preview mt-3 {{ $docTemp ? '' : 'hidden' }}"
                    @if($docTemp)
                        data-restored-url="{{ $docTemp['url'] }}"
                        data-restored-name="{{ $docTemp['original_name'] }}"
                        data-restored-is-image="{{ $docTemp['is_image'] ? '1' : '0' }}"
                    @endif></div>
                @if($docTemp)
                <p class="text-[10px] text-emerald-800 font-bold mt-2">Berkas tersimpan dari unggahan sebelumnya.</p>
                @endif
                @error($req['id'])
                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($formFields))
    <div class="space-y-6 border-t border-slate-100 pt-6">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest font-mono">Informasi Pelengkap Lainnya</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @foreach($formFields as $field)
            <div class="{{ $field['type'] === 'textarea' ? 'sm:col-span-2' : '' }}">
                <label class="form-label-premium">
                    {{ $field['label'] }}{{ $field['required'] ? ' *' : '' }}
                </label>
                @if($field['type'] === 'textarea')
                    <textarea name="{{ $field['id'] }}" x-model="form.{{ $field['id'] }}" rows="3"
                        class="form-input-premium resize-none @error($field['id']) !border-red-500 @enderror"
                        placeholder="Masukkan {{ strtolower($field['label']) }}"
                        {{ $field['required'] ? 'required' : '' }}></textarea>
                @elseif($field['type'] === 'select')
                    <select name="{{ $field['id'] }}" x-model="form.{{ $field['id'] }}"
                        class="form-input-premium @error($field['id']) !border-red-500 @enderror"
                        {{ $field['required'] ? 'required' : '' }}>
                        <option value="">Pilih {{ $field['label'] }}</option>
                        @foreach($field['options'] as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="{{ $field['type'] }}" name="{{ $field['id'] }}" x-model="form.{{ $field['id'] }}"
                        class="form-input-premium @error($field['id']) !border-red-500 @enderror"
                        placeholder="Masukkan {{ strtolower($field['label']) }}"
                        {{ $field['required'] ? 'required' : '' }}>
                @endif
                @error($field['id'])
                <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
