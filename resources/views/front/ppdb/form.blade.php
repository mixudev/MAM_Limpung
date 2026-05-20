@extends('layouts.app')

@section('content')

<!-- Form Design Style Customizations (Premium, Soft, Eye-Friendly) -->
<style>
    .form-input-premium {
        width: 100% !important;
        padding: 0.625rem 1rem !important; /* px-4 py-2.5 */
        font-size: 0.75rem !important; /* text-xs */
        color: #334155 !important; /* text-slate-700 */
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important; /* border-slate-200 */
        border-radius: 0px !important; /* rounded-none */
        outline: none !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    .form-input-premium::placeholder {
        color: #94a3b8 !important; /* text-slate-400 */
        font-size: 0.75rem !important; /* text-xs */
        opacity: 0.85 !important;
    }
    .form-input-premium:focus {
        border-color: #059669 !important; /* focus:border-emerald-600 */
        box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.05) !important; /* focus:ring-4 focus:ring-emerald-600/5 */
    }
    /* Semibold, elegant, eye-friendly labels for premium visual comfort */
    .form-label-premium {
        display: block !important;
        font-size: 0.725rem !important; /* text-[11.5px] */
        font-weight: 600 !important; /* Semibold (clean, not harsh) */
        color: #475569 !important; /* Muted slate-600 (soft on eyes) */
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        margin-bottom: 0.4rem !important;
        font-family: system-ui, -apple-system, sans-serif !important;
    }
</style>

<section class="max-w-6xl mx-auto px-4 py-8 sm:py-12" x-data="{
    step: 1,
    form: {
        nama_lengkap: '',
        nisn: '',
        nomor_hp: '',
        email: '',
        jenis_kelamin: '',
        tanggal_lahir: '',
        tempat_lahir: '',
        nama_ayah: '',
        nama_ibu: '',
        alamat_lengkap: '',
        sekolah_asal: '',
        ukuran_baju: '',
        @foreach($formFields as $field)
            {{ $field['id'] }}: '',
        @endforeach
    },
    init() {
        const saved = localStorage.getItem('ppdb_form_draft');
        if (saved) {
            try {
                const parsed = JSON.parse(saved);
                Object.keys(this.form).forEach(key => {
                    if (parsed[key] !== undefined) {
                        this.form[key] = parsed[key];
                    }
                });
            } catch(e) {
                console.error('Gagal memuat draf formulir:', e);
            }
        }
        
        // Watch for changes and save to localStorage
        this.$watch('form', value => {
            localStorage.setItem('ppdb_form_draft', JSON.stringify(value));
        }, { deep: true });
    },
    clearDraft() {
        localStorage.removeItem('ppdb_form_draft');
    },
    nextStep() {
        // Validate inputs inside current step container
        const currentContainer = document.getElementById('step-' + this.step);
        const inputs = currentContainer.querySelectorAll('input, select, textarea');
        let isValid = true;
        
        for (let input of inputs) {
            if (!input.checkValidity()) {
                input.reportValidity();
                isValid = false;
                break;
            }
        }
        
        if (isValid) {
            this.step++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },
    prevStep() {
        this.step--;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}">
    <!-- Header Section -->
    <div class="text-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-2">Formulir Pendaftaran Siswa Baru</h1>
        <span class="inline-block bg-cyan-100 text-cyan-800 text-[10px] font-bold px-3 py-1 rounded-sm uppercase tracking-widest font-mono mb-2">
            Tahun Pelajaran {{ $general['tahun_ajaran'] }}/{{ $general['tahun_ajaran'] + 1 }}
        </span>
        <p class="text-gray-600 text-sm sm:text-base">Silakan lengkapi data diri Anda secara jujur, benar, dan lengkap</p>
    </div>

    <!-- Error Alert - Display All Errors -->
    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-sm shadow-md">
        <div class="flex items-start">
            <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <p class="font-bold mb-2">Terjadi kesalahan pada formulir:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Container -->
    <div class="bg-white rounded-none p-6 sm:p-10 border border-gray-300 shadow-sm relative">
        
        <!-- Progress Stepper (Interactive & Elegant) -->
        <div class="mb-12 border-b border-slate-100 pb-8">
            <div class="flex items-center justify-between max-w-md mx-auto relative">
                <!-- Progress Line Background (Dashed Slate) -->
                <div class="absolute left-6 right-6 top-[18px] h-0.5 border-t-2 border-dashed border-slate-200 z-0"></div>
                <!-- Interactive Active Progress Line (Dashed Emerald) -->
                <div class="absolute left-6 top-[18px] h-0.5 border-t-2 border-dashed border-emerald-800 transition-all duration-500 ease-out z-0" 
                     :style="'width: ' + ((step - 1) * 50) + '%'"></div>
                
                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center gap-2 cursor-pointer group" @click="if(step > 1) step = 1">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300 border-2 z-10"
                        :class="step > 1 ? 'bg-emerald-800 border-emerald-800 text-white shadow-md group-hover:scale-105 group-hover:bg-emerald-900' : (step === 1 ? 'bg-emerald-800 border-emerald-800 text-white shadow-lg ring-4 ring-emerald-800/10 scale-110 font-bold' : 'bg-white border-slate-300 text-slate-400 group-hover:border-slate-400')">
                        <span x-show="step > 1"><i class="fa-solid fa-check text-[10px]"></i></span>
                        <span x-show="step <= 1">1</span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider font-mono transition-colors duration-200"
                        :class="step >= 1 ? 'text-emerald-800' : 'text-slate-400'">Profil</span>
                </div>
                
                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center gap-2 cursor-pointer group" @click="if(step > 2) step = 2">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300 border-2 z-10"
                        :class="step > 2 ? 'bg-emerald-800 border-emerald-800 text-white shadow-md group-hover:scale-105 group-hover:bg-emerald-900' : (step === 2 ? 'bg-emerald-800 border-emerald-800 text-white shadow-lg ring-4 ring-emerald-800/10 scale-110 font-bold' : 'bg-white border-slate-300 text-slate-400 group-hover:border-slate-400')">
                        <span x-show="step > 2"><i class="fa-solid fa-check text-[10px]"></i></span>
                        <span x-show="step <= 2">2</span>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider font-mono transition-colors duration-200"
                        :class="step >= 2 ? 'text-emerald-800' : 'text-slate-400'">Kontak & Wali</span>
                </div>
                
                <!-- Step 3 -->
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

        <form method="POST" action="{{ route('frontend.ppdb.store') }}" enctype="multipart/form-data" @submit="clearDraft()">
            @csrf

            <!-- ════════════ LANGKAH 1: DATA PRIBADI ════════════ -->
            <div id="step-1" x-show="step === 1" class="space-y-6">
                <div class="border-b border-slate-100 pb-3 mb-6">
                    <h2 class="text-base font-bold text-slate-800 uppercase tracking-wider font-mono">Langkah 1: Profil Calon Siswa</h2>
                    <p class="text-xs text-slate-400 mt-1">Lengkapi biodata diri utama Anda sesuai dokumen resmi keluarga.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Biodata Fields (2 cols) -->
                    <div class="lg:col-span-2 space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label class="form-label-premium">Nama Lengkap *</label>
                                <input type="text" name="nama_lengkap" x-model="form.nama_lengkap" class="form-input-premium" placeholder="Masukkan nama lengkap sesuai ijazah/akta" required>
                                @error('nama_lengkap')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="form-label-premium">NISN *</label>
                                <input type="text" name="nisn" x-model="form.nisn" class="form-input-premium" placeholder="10 digit nomor NISN" required>
                                @error('nisn')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label-premium">Jenis Kelamin *</label>
                                <select name="jenis_kelamin" x-model="form.jenis_kelamin" class="form-input-premium" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label-premium">Tempat Lahir *</label>
                                <input type="text" name="tempat_lahir" x-model="form.tempat_lahir" class="form-input-premium" placeholder="Kota kelahiran" required>
                                @error('tempat_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label-premium">Tanggal Lahir *</label>
                                <input type="date" name="tanggal_lahir" x-model="form.tanggal_lahir" class="form-input-premium" required>
                                @error('tanggal_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="form-label-premium">Ukuran Seragam *</label>
                                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                                    @foreach(['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                    <label class="flex items-center justify-center rounded-none cursor-pointer">
                                        <input type="radio" name="ukuran_baju" value="{{ $size }}" x-model="form.ukuran_baju" class="hidden peer" required>
                                        <div class="border border-slate-200 peer-checked:border-emerald-800 peer-checked:bg-emerald-50 peer-checked:text-emerald-800 w-full text-center font-bold p-2 text-xs uppercase tracking-wider transition-all">
                                            {{ $size }}
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @error('ukuran_baju')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="form-label-premium">Alamat Lengkap *</label>
                                <textarea name="alamat_lengkap" x-model="form.alamat_lengkap" rows="3" class="form-input-premium resize-none" placeholder="Masukkan alamat RT/RW, Dusun, Kelurahan, Kecamatan" required></textarea>
                                @error('alamat_lengkap')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right: Photo Upload (1 col) -->
                    <div class="lg:col-span-1">
                        <div class="border border-slate-200 p-5 bg-slate-50 sticky top-4">
                            <label class="block text-xs font-bold text-slate-800 uppercase tracking-widest font-mono mb-3">Pas Foto Resmi *</label>
                            
                            <div id="dropZone" class="min-h-[220px] border-2 border-dashed border-slate-300 flex flex-col items-center justify-center cursor-pointer bg-white p-4 hover:border-emerald-800 transition-colors">
                                <div id="dropZoneContent" class="text-center">
                                    <svg class="w-10 h-10 text-slate-400 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-slate-700 text-xs font-bold mb-1">Unggah Pas Foto</p>
                                    <p class="text-slate-400 text-[10px] mb-3">Seret & lepas berkas di sini</p>
                                    <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border border-slate-355 text-slate-700 text-[10px] font-bold uppercase tracking-wider transition-colors">Pilih File</button>
                                </div>
                            </div>
                            <input type="file" name="foto_siswa" id="fileInput" class="hidden" accept="image/*" {{ empty(old('foto_siswa')) ? 'required' : '' }}>
                            
                            <div class="mt-4 space-y-2">
                                <p class="text-[10px] text-slate-455 leading-normal flex items-start gap-1">
                                    <span>⚠️</span>
                                    <span>Gunakan pas foto resmi berseragam dengan latar belakang merah atau biru.</span>
                                </p>
                                <p class="text-[10px] text-slate-455 leading-normal flex items-start gap-1">
                                    <span>📁</span>
                                    <span>Maksimal 2MB, format file .jpg, .jpeg, .png</span>
                                </p>
                            </div>
                            @error('foto_siswa')
                            <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- ════════════ LANGKAH 2: KONTAK & ORANG TUA ════════════ -->
            <div id="step-2" x-show="step === 2" class="space-y-6" style="display: none;">
                <div class="border-b border-slate-100 pb-3 mb-6">
                    <h2 class="text-base font-bold text-slate-800 uppercase tracking-wider font-mono">Langkah 2: Kontak & Wali Orang Tua</h2>
                    <p class="text-xs text-slate-400 mt-1">Lengkapi data komunikasi utama dan nama orang tua kandung pendaftar.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label-premium">Nomor HP / WhatsApp *</label>
                        <input type="tel" name="nomor_hp" x-model="form.nomor_hp" class="form-input-premium" placeholder="Contoh: 08123456789" required>
                        @error('nomor_hp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label-premium">Email Aktif *</label>
                        <input type="email" name="email" x-model="form.email" class="form-input-premium" placeholder="contoh@email.com" required>
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label-premium">Nama Sekolah Asal (SMP/MTs) *</label>
                        <input type="text" name="sekolah_asal" x-model="form.sekolah_asal" class="form-input-premium" placeholder="Masukkan nama sekolah asal lengkap" required>
                        @error('sekolah_asal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label-premium">Nama Ayah Kandung *</label>
                        <input type="text" name="nama_ayah" x-model="form.nama_ayah" class="form-input-premium" placeholder="Nama lengkap ayah sesuai berkas" required>
                        @error('nama_ayah')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label-premium">Nama Ibu Kandung *</label>
                        <input type="text" name="nama_ibu" x-model="form.nama_ibu" class="form-input-premium" placeholder="Nama lengkap ibu sesuai berkas" required>
                        @error('nama_ibu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ════════════ LANGKAH 3: DOKUMEN & LAINNYA ════════════ -->
            <div id="step-3" x-show="step === 3" class="space-y-6" style="display: none;">
                <div class="border-b border-slate-100 pb-3 mb-6">
                    <h2 class="text-base font-bold text-slate-800 uppercase tracking-wider font-mono">Langkah 3: Berkas Persyaratan & Informasi Kustom</h2>
                    <p class="text-xs text-slate-400 mt-1">Unggah scan kelengkapan dokumen pendukung dan lengkapi kolom kustom PPDB.</p>
                </div>

                <!-- Auto-Save Alert Message (Tegas & Tenang) -->
                <div class="bg-slate-50 border border-slate-200 p-4 text-xs text-slate-500 leading-normal flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-emerald-800 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>
                        <strong>Draf Tersimpan Otomatis:</strong> Isian teks Anda telah tersimpan secara otomatis di browser lokal. Apabila Anda tidak sengaja keluar, data teks akan tetap ada. Namun, demi alasan keamanan browser, <strong>berkas dokumen dan foto</strong> di bawah harus diunggah ulang apabila Anda memuat ulang halaman ini.
                    </span>
                </div>

                <!-- Dynamic Requirement Document Scans -->
                @if(!empty($requirements))
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest font-mono mb-2">Dokumen Persyaratan Mandiri</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($requirements as $req)
                        @if($req['id'] !== 'foto') {{-- Already handled by foto_siswa dropzone in Step 1 --}}
                        <div class="bg-slate-50 border border-slate-200 p-4 rounded-sm hover:border-amber-500 transition-all">
                            <label class="form-label-premium">
                                {{ $req['label'] }}{{ $req['required'] ? ' *' : '' }}
                            </label>
                            <input type="file" name="{{ $req['id'] }}" 
                                class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-sm file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-slate-650 hover:file:bg-slate-50 cursor-pointer"
                                accept=".pdf,.jpg,.jpeg,.png"
                                {{ $req['required'] ? 'required' : '' }}>
                            @error($req['id'])
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Dynamic Custom Fields (Informasi Tambahan) -->
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
                                    class="form-input-premium resize-none" 
                                    placeholder="Masukkan {{ strtolower($field['label']) }}" 
                                    {{ $field['required'] ? 'required' : '' }}></textarea>
                            @elseif($field['type'] === 'select')
                                <select name="{{ $field['id'] }}" x-model="form.{{ $field['id'] }}" 
                                    class="form-input-premium"
                                    {{ $field['required'] ? 'required' : '' }}>
                                    <option value="">Pilih {{ $field['label'] }}</option>
                                    @foreach($field['options'] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="{{ $field['type'] }}" name="{{ $field['id'] }}" x-model="form.{{ $field['id'] }}" 
                                    class="form-input-premium" 
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

            <!-- ════════════ NAVIGASI WIZARD BUTTONS (Tegas & Profesional) ════════════ -->
            <div class="flex items-center justify-between mt-10 pt-6 border-t border-slate-100">
                <!-- Back Button -->
                <div>
                    <button type="button" @click="prevStep()" x-show="step > 1" 
                        class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-none hover:bg-slate-50 transition-colors"
                        style="display: none;">
                        Sebelumnya
                    </button>
                </div>

                <!-- Next or Submit Button -->
                <div>
                    <!-- Next Button -->
                    <button type="button" @click="nextStep()" x-show="step < 3" 
                        class="px-6 py-2.5 bg-slate-900 hover:bg-black text-white font-bold text-xs uppercase tracking-wider rounded-none transition-colors">
                        Selanjutnya
                    </button>

                    <!-- Submit Button -->
                    <button type="submit" x-show="step === 3" 
                        class="px-7 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-colors"
                        style="display: none;">
                        Kirim Pendaftaran
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    // ════════════ Pas Foto Dropzone Scripts ════════════
    const dropZone = document.getElementById('dropZone');
    const dropZoneContent = document.getElementById('dropZoneContent');
    const fileInput = document.getElementById('fileInput');

    if (dropZone && fileInput) {
        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-emerald-800', 'bg-slate-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-emerald-800', 'bg-slate-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-emerald-800', 'bg-slate-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                displayFileName(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                displayFileName(e.target.files[0]);
            }
        });

        function displayFileName(file) {
            dropZoneContent.innerHTML = `
                <svg class="w-10 h-10 text-emerald-800 mb-2 mx-auto animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-slate-800 font-bold text-xs text-center truncate max-w-[200px] mx-auto">${file.name}</p>
                <p class="text-slate-400 text-[10px] mt-1 text-center font-mono">Klik dropzone untuk mengubah</p>
            `;
        }
    }
</script>

@endsection