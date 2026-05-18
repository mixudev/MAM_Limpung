@extends('layouts.app')

@section('content')

<section class="max-w-6xl mx-auto px-4 py-8 sm:py-12">
    <!-- Header Section -->
    <div class="text-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-3">Formulir Pendaftaran Siswa Baru</h1>
        <p class="text-gray-600 text-sm sm:text-base">Silakan lengkapi data diri Anda dengan benar dan lengkap</p>
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

    <!-- Success Alert -->
    @if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-sm shadow-md">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Form Section -->
    <div class="bg-white rounded-sm p-6 sm:p-8 border border-gray-300 shadow-lg">
        <form method="POST" action="{{ route('frontend.ppdb.store') }}" enctype="multipart/form-data">
            @csrf
            <!-- Row 1: Personal Data & Photo -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Left Column: Personal Inputs -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full px-4 py-2 border @error('nama_lengkap') border-red-500 @else border-gray-300 @enderror rounded-sm focus:ring-2 placeholder:text-sm focus:ring-amber-500 focus:border-transparent outline-none" placeholder="Masukkan nama lengkap" required>
                        @error('nama_lengkap')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2">NISN *</label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" class="w-full px-4 py-2 border @error('nisn') border-red-500 @else border-gray-300 @enderror rounded-sm focus:ring-2 placeholder:text-sm focus:ring-amber-500 focus:border-transparent outline-none" placeholder="Masukkan NISN" required>
                        @error('nisn')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2">Nomor HP *</label>
                        <input type="tel" name="nomor_hp" value="{{ old('nomor_hp') }}" class="w-full px-4 py-2 border @error('nomor_hp') border-red-500 @else border-gray-300 @enderror rounded-sm focus:ring-2 placeholder:text-sm focus:ring-amber-500 focus:border-transparent outline-none" placeholder="Contoh: 08123456789" required>
                        @error('nomor_hp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-sm focus:ring-2 placeholder:text-sm focus:ring-amber-500 focus:border-transparent outline-none" placeholder="contoh@email.com" required>
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column: Photo Upload -->
                <div class="lg:p-8 mb-5">
                    <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2">Foto Siswa *</label>
                    <div id="dropZone" class="h-full min-h-[280px] border-2 border-dashed @error('foto_siswa') border-red-500 @else border-gray-300 @enderror rounded-sm flex flex-col items-center justify-center cursor-pointer hover:border-amber-500 transition-colors bg-gray-50">
                        <div id="dropZoneContent">
                            <svg class="w-12 h-12 text-gray-400 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-600 text-sm mb-1 text-center">Seret & lepas foto di sini</p>
                            <p class="text-gray-400 text-xs mb-3 text-center">atau</p>
                            <div class="text-center">
                                <button type="button" class="px-6 py-2 rounded text-sm text-gray-500 hover:border-amber-500 hover:text-amber-500 border border-gray-500">Pilih File</button>
                            </div>
                        </div>
                    </div>
                    <!-- Hidden Input File - Tetap ada di form -->
                    <input type="file" name="foto_siswa" id="fileInput" class="hidden" accept="image/*" required>
                    @error('foto_siswa')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Select Jenis Kelamin -->
            <div class="flex flex-col lg:flex-row items-center w-full gap-4 mb-6">
                <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2 w-full lg:w-48">Jenis Kelamin *</label>
                <div class="w-full flex-1">
                    <div class="grid grid-cols-2 gap-3 lg:max-w-md w-full">
                        <label class="flex items-center justify-center rounded-sm cursor-pointer hover:border-amber-500 transition-colors">
                            <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} class="hidden peer" required>
                            <div class="border-2 border-gray-300 peer-checked:border-amber-500 peer-checked:text-amber-500 w-full text-center font-medium rounded p-2">
                                Laki-laki
                            </div>
                        </label>
                        <label class="flex items-center justify-center rounded-sm cursor-pointer hover:border-amber-500 transition-colors">
                            <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} class="hidden peer" required>
                            <div class="border-2 border-gray-300 peer-checked:border-amber-500 peer-checked:text-amber-500 w-full text-center font-medium rounded p-2">
                                Perempuan
                            </div>
                        </label>
                    </div>
                    @error('jenis_kelamin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tanggal Lahir -->
            <div class="flex flex-col lg:flex-row items-center w-full gap-4 mb-6">
                <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2 w-full lg:w-48">Tanggal Lahir *</label>
                <div class="w-full lg:max-w-md flex-1">
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full px-4 py-2 border @error('tanggal_lahir') border-red-500 @else border-gray-300 @enderror rounded-sm focus:ring-2 focus:ring-amber-500 placeholder:text-sm focus:border-transparent outline-none" required>
                    @error('tanggal_lahir')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tempat Lahir -->
            <div class="flex flex-col lg:flex-row items-center w-full gap-4 mb-6">
                <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2 w-full lg:w-48">Tempat Lahir *</label>
                <div class="w-full flex-1">
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full px-4 py-2 border @error('tempat_lahir') border-red-500 @else border-gray-300 @enderror rounded-sm focus:ring-2 focus:ring-amber-500 placeholder:text-sm focus:border-transparent outline-none" placeholder="Kota kelahiran" required>
                    @error('tempat_lahir')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Nama Ayah -->
            <div class="mb-6 flex flex-col lg:flex-row items-center w-full gap-4">
                <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2 w-full lg:w-48">Nama Ayah Kandung *</label>
                <div class="w-full flex-1">
                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" class="w-full px-4 py-2 border @error('nama_ayah') border-red-500 @else border-gray-300 @enderror rounded-sm focus:ring-2 focus:ring-amber-500 placeholder:text-sm focus:border-transparent outline-none" placeholder="Nama lengkap ayah" required>
                    @error('nama_ayah')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Nama Ibu -->
            <div class="mb-6 flex flex-col lg:flex-row items-center w-full gap-4">
                <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2 w-full lg:w-48">Nama Ibu Kandung *</label>
                <div class="w-full flex-1">
                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" class="w-full px-4 py-2 border @error('nama_ibu') border-red-500 @else border-gray-300 @enderror rounded-sm focus:ring-2 focus:ring-amber-500 placeholder:text-sm focus:border-transparent outline-none" placeholder="Nama lengkap ibu" required>
                    @error('nama_ibu')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Alamat -->
            <div class="mb-6 flex flex-col lg:flex-row items-center w-full gap-4">
                <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2 w-full lg:w-48">Alamat Lengkap *</label>
                <div class="w-full flex-1">
                    <textarea name="alamat_lengkap" rows="3" class="w-full px-4 py-2 border @error('alamat_lengkap') border-red-500 @else border-gray-300 @enderror rounded-sm focus:ring-2 focus:ring-amber-500 placeholder:text-sm focus:border-transparent outline-none resize-none" placeholder="Masukkan alamat lengkap" required>{{ old('alamat_lengkap') }}</textarea>
                    @error('alamat_lengkap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Nama Sekolah Asal -->
            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 mb-6">
                <div class="flex flex-col lg:flex-row items-center w-full gap-4">
                    <label class="block text-sm font-medium text-gray-700 w-full lg:w-48">Nama Sekolah Asal *</label>
                    <div class="flex-1 w-full">
                        <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal') }}" class="w-full px-4 py-2 border @error('sekolah_asal') border-red-500 @else border-gray-300 @enderror rounded-sm focus:ring-2 focus:ring-amber-500 placeholder:text-sm focus:border-transparent outline-none" placeholder="Nama sekolah asal" required>
                        @error('sekolah_asal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Ukuran Baju -->
            <div class="mb-8 flex flex-col lg:flex-row items-center w-full gap-4">
                <label class="block text-sm font-medium text-gray-700 mt-2 mb-1 lg:mb-2 w-full lg:w-48">Ukuran Baju Seragam *</label>
                <div class="w-full flex-1">
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                        @foreach(['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                        <label class="flex items-center justify-center rounded-sm cursor-pointer hover:border-amber-500 transition-colors">
                            <input type="radio" name="ukuran_baju" value="{{ $size }}" {{ old('ukuran_baju') == $size ? 'checked' : '' }} class="hidden peer" required>
                            <div class="border-2 border-gray-300 peer-checked:border-amber-500 peer-checked:text-amber-500 w-full text-center font-medium rounded p-2">{{ $size }}</div>
                        </label>
                        @endforeach
                    </div>
                    @error('ukuran_baju')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center lg:justify-end mt-5">
                <button type="submit" class="px-8 py-3 bg-cyan-600 text-white font-medium rounded-sm hover:bg-cyan-700 transition-colors">
                    Daftar Sekarang
                </button>
            </div>
        </form>
    </div>
</section>

<script>
    const dropZone = document.getElementById('dropZone');
    const dropZoneContent = document.getElementById('dropZoneContent');
    const fileInput = document.getElementById('fileInput');

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-amber-500', 'bg-amber-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-amber-500', 'bg-amber-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-amber-500', 'bg-amber-50');
        
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
        // Update hanya konten visual, bukan input file
        dropZoneContent.innerHTML = `
            <svg class="w-12 h-12 text-green-500 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="text-gray-700 font-medium text-center">${file.name}</p>
            <p class="text-gray-400 text-sm mt-2 text-center">Klik untuk mengubah foto</p>
        `;
    }
</script>

@endsection