<div>
    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider border-b border-slate-100 dark:border-zinc-800 pb-3 mb-4 flex items-center gap-2">
        <span class="w-2 h-4 bg-[#4f45b2]"></span>
        Berkas & Kolom Kustom
    </h2>

    <!-- Foto Siswa -->
    <div class="mb-4 @error('foto_siswa') border border-red-500 p-3 @enderror">
        <label for="foto_siswa" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">Pas Foto Calon Siswa</label>
        <input type="file" name="foto_siswa" id="foto_siswa" accept="image/*"
            class="admin-ppdb-file-input w-full text-xs text-slate-650 dark:text-zinc-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-none file:border file:border-slate-200 dark:file:border-zinc-700 file:text-xs file:font-bold file:bg-slate-150 file:text-[#4f45b2] hover:file:bg-slate-200 dark:file:bg-zinc-800 dark:file:text-zinc-300">
        <div id="foto_siswa_preview" class="mt-3 hidden"></div>
        <p class="text-[9px] text-slate-400 dark:text-zinc-500 mt-1">Format: JPG, JPEG, PNG (Maks 2MB). Opsional untuk admin.</p>
        @error('foto_siswa')
            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
        @enderror
    </div>

    <!-- Custom Form Fields -->
    @if(count($formFields) > 0)
        <div class="border-t border-slate-100 dark:border-zinc-800 pt-4 mt-4 space-y-4">
            <h3 class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">Informasi Tambahan</h3>
            @foreach($formFields as $field)
                <div>
                    <label for="{{ $field['id'] }}" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">
                        {{ $field['label'] }} @if($field['required']) <span class="text-red-500">*</span> @endif
                    </label>

                    @if($field['type'] === 'select')
                        <select name="{{ $field['id'] }}" id="{{ $field['id'] }}" {{ $field['required'] ? 'required' : '' }}
                            class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                            <option value="">Pilih {{ $field['label'] }}</option>
                            @foreach($field['options'] ?? [] as $opt)
                                <option value="{{ $opt }}" {{ old($field['id']) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    @elseif($field['type'] === 'textarea')
                        <textarea name="{{ $field['id'] }}" id="{{ $field['id'] }}" rows="2" {{ $field['required'] ? 'required' : '' }}
                            class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                            placeholder="Masukkan {{ strtolower($field['label']) }}">{{ old($field['id']) }}</textarea>
                    @elseif($field['type'] === 'date')
                        <input type="date" name="{{ $field['id'] }}" id="{{ $field['id'] }}" value="{{ old($field['id']) }}" {{ $field['required'] ? 'required' : '' }}
                            class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                    @elseif($field['type'] === 'number')
                        <input type="number" name="{{ $field['id'] }}" id="{{ $field['id'] }}" value="{{ old($field['id']) }}" {{ $field['required'] ? 'required' : '' }}
                            class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                            placeholder="Input angka">
                    @else
                        <input type="text" name="{{ $field['id'] }}" id="{{ $field['id'] }}" value="{{ old($field['id']) }}" {{ $field['required'] ? 'required' : '' }}
                            class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 rounded-none text-sm text-slate-700 dark:text-zinc-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]"
                            placeholder="Masukkan {{ strtolower($field['label']) }}">
                    @endif
                    @error($field['id'])
                        <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>
    @endif

    <!-- Dynamic Requirements Documents -->
    @php
        $filteredReqs = collect($requirements)->filter(fn($r) => $r['id'] !== 'foto');
    @endphp

    @if($filteredReqs->count() > 0)
        <div class="border-t border-slate-100 dark:border-zinc-800 pt-4 mt-4 space-y-4">
            <h3 class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">Dokumen Persyaratan</h3>
            @foreach($filteredReqs as $req)
                <div class="@error($req['id']) border border-red-500 p-3 @enderror">
                    <label for="{{ $req['id'] }}" class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1 block">
                        {{ $req['label'] }}
                    </label>
                    <input type="file" name="{{ $req['id'] }}" id="{{ $req['id'] }}" accept=".pdf,image/*"
                        class="admin-ppdb-file-input w-full text-xs text-slate-650 dark:text-zinc-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-none file:border file:border-slate-200 dark:file:border-zinc-700 file:text-xs file:font-bold file:bg-slate-150 file:text-[#4f45b2] hover:file:bg-slate-200 dark:file:bg-zinc-800 dark:file:text-zinc-300">
                    <div id="{{ $req['id'] }}_preview" class="mt-3 hidden"></div>
                    <p class="text-[9px] text-slate-400 dark:text-zinc-500 mt-1">Format: PDF, JPG, JPEG, PNG (Maks 2MB). Opsional untuk admin.</p>
                    @error($req['id'])
                        <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>
    @endif
</div>
