@extends('dashboard.layouts.main')

@section('content')
<style>
    .dropzone-highlight { border-color: #4f45b2 !important; background: rgba(79,69,178,0.04); }
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const bc = document.getElementById('breadcrumb');
    if (bc) bc.textContent = '{{ $teacher ? "Edit Guru" : "Tambah Guru" }}';

    var dz = document.getElementById('photoDropzone');
    var fi = document.getElementById('photoInput');
    var pr = document.getElementById('photoPreview');
    var pl = document.getElementById('photoPlaceholder');
    if (dz && fi) {
        fi.addEventListener('change', function(e) {
            if (e.target.files.length) {
                var r = new FileReader();
                r.onload = function(ev) { if (pr) { pr.src=ev.target.result; pr.classList.remove('hidden'); } if (pl) pl.classList.add('hidden'); };
                r.readAsDataURL(e.target.files[0]);
            }
        });
        ['dragenter','dragover'].forEach(function(t) { dz.addEventListener(t, function(e) { e.preventDefault(); dz.classList.add('dropzone-highlight'); }); });
        ['dragleave','drop'].forEach(function(t) { dz.addEventListener(t, function(e) { e.preventDefault(); dz.classList.remove('dropzone-highlight'); }); });
        dz.addEventListener('drop', function(e) {
            e.preventDefault();
            if (e.dataTransfer.files.length) { fi.files = e.dataTransfer.files; fi.dispatchEvent(new Event('change')); }
        });
        dz.addEventListener('click', function() { fi.click(); });
    }
});
</script>

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4 bg-white dark:bg-zinc-900 p-6 border border-slate-300 dark:border-zinc-800 border-l-4 border-l-[#4f45b2] shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
        <a href="{{ route('admin.teachers.index') }}" class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-400 transition-colors shrink-0">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white leading-tight">{{ $teacher ? 'Edit Guru' : 'Tambah Guru Baru' }}</h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 font-mono">{{ $teacher ? 'Perbarui data guru dan staf.' : 'Isi data guru dan staf dengan lengkap.' }}</p>
        </div>
    </div>

    <form method="POST" action="{{ $teacher ? route('admin.teachers.update', $teacher) : route('admin.teachers.store') }}" enctype="multipart/form-data">
        @csrf
        @if($teacher) @method('PUT') @endif

        {{-- Profile + Akun Login --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 border-t-4 border-t-[#4f45b2] shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 p-6">
                {{-- Left: Photo Dropzone --}}
                <div class="lg:col-span-1">
                    <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Foto Guru</label>
                    <div id="photoDropzone"
                        class="border-2 border-dashed border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-950/50 p-4 flex flex-col items-center justify-center text-center cursor-pointer transition-colors aspect-square max-w-[200px] mx-auto lg:mx-0">
                        <input type="file" id="photoInput" name="foto" accept="image/jpg,image/jpeg,image/png" class="hidden">
                        @if($teacher && $teacher->foto)
                            <img id="photoPreview" src="{{ asset('storage/'.$teacher->foto) }}" alt="{{ $teacher->nama }}" class="w-full h-full object-cover">
                            <div id="photoPlaceholder" class="hidden"></div>
                        @else
                            <img id="photoPreview" class="hidden w-full h-full object-cover">
                            <div id="photoPlaceholder" class="flex flex-col items-center gap-2">
                                <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-300 dark:text-zinc-600"></i>
                                <p class="text-[10px] font-mono text-slate-400 dark:text-zinc-500">Seret foto ke sini</p>
                                <p class="text-[9px] text-slate-300 dark:text-zinc-600 font-mono">atau klik untuk memilih</p>
                            </div>
                        @endif
                    </div>
                    @error('foto') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                    <p class="text-[9px] text-slate-300 dark:text-zinc-600 font-mono mt-1 text-center lg:text-left">Format: JPG/PNG. Maks 2MB.</p>
                </div>

                {{-- Right: Identity Fields --}}
                <div class="lg:col-span-4 space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 flex items-center gap-2">
                        <i class="fa-solid fa-user-circle text-[#4f45b2]"></i> Identitas & Akun Login
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama', $teacher->nama ?? '') }}"
                                   placeholder="Masukkan nama lengkap"
                                   class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                            @error('nama') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip', $teacher->nip ?? '') }}"
                                   placeholder="Nomor Induk Pegawai"
                                   class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                            @error('nip') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Email Login <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $teacher->user->email ?? '') }}"
                                   placeholder="contoh@email.com"
                                   class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                            @error('email') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Password {{ !$teacher ? '<span class="text-rose-500">*</span>' : '' }}</label>
                            <input type="password" name="password" value=""
                                   placeholder="{{ $teacher ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
                                   class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                            @error('password') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Pribadi --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 border-t-4 border-t-emerald-600 shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
            <div class="p-6 border-b border-slate-100 dark:border-zinc-800">
                <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-emerald-600"></i> Data Pribadi
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin"
                                class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                            <option value="">Pilih</option>
                            <option value="L" {{ old('jenis_kelamin', $teacher->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $teacher->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $teacher->tempat_lahir ?? '') }}"
                               placeholder="Kota lahir"
                               class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                    </div>
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $teacher?->tanggal_lahir?->format('Y-m-d') ?? '') }}"
                               class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Alamat</label>
                        <textarea name="alamat" rows="2"
                                  placeholder="Alamat lengkap"
                                  class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">{{ old('alamat', $teacher->alamat ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">No. Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $teacher->no_telepon ?? '') }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                    </div>

                </div>
            </div>
        </div>

        {{-- Data Profesi --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 border-t-4 border-t-amber-600 shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
            <div class="p-6 border-b border-slate-100 dark:border-zinc-800">
                <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 flex items-center gap-2">
                    <i class="fa-solid fa-briefcase text-amber-600"></i> Data Profesi & Kepegawaian
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $teacher->pendidikan_terakhir ?? '') }}"
                               placeholder="S1, S2, S3"
                               class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                    </div>
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Jurusan</label>
                        <input type="text" name="jurusan" value="{{ old('jurusan', $teacher->jurusan ?? '') }}"
                               placeholder="Pendidikan Matematika"
                               class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                    </div>
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Kategori</label>
                        <select name="teacher_category_id"
                                class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('teacher_category_id', $teacher->teacher_category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $teacher?->tanggal_masuk?->format('Y-m-d') ?? '') }}"
                               class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]"/>
                    </div>
                    <div>
                        <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1.5">Status <span class="text-rose-500">*</span></label>
                        <select name="status"
                                class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                            <option value="aktif" {{ old('status', $teacher->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $teacher->status ?? 'aktif') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quote --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 border-t-4 border-t-rose-600 shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
            <div class="p-6 border-b border-slate-100 dark:border-zinc-800">
                <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 flex items-center gap-2">
                    <i class="fa-solid fa-quote-right text-rose-600"></i> Quote / Kata Motivasi
                </h3>
            </div>
            <div class="p-6">
                <textarea name="quote" rows="3" placeholder="Tuliskan kata-kata motivasi atau quote untuk guru ini..."
                          class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">{{ old('quote', $teacher->quote ?? '') }}</textarea>
            </div>
        </div>

        {{-- Action --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 shadow-[1px_1px_3px_rgba(0,0,0,0.05)] p-6 flex items-center justify-end gap-3">
            <a href="{{ route('admin.teachers.index') }}"
               class="py-2.5 px-6 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs font-mono tracking-wider transition-all">BATAL</a>
            <button type="submit"
               class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs font-mono tracking-wider transition-all inline-flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> {{ $teacher ? 'SIMPAN PERUBAHAN' : 'SIMPAN DATA GURU' }}
            </button>
        </div>

    </form>
</div>
@endsection
