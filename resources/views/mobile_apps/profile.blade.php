@extends('mobile_apps.layouts.apps')

@section('content')
    <div class="px-5 pt-4">
        <!-- Header & Back Button -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('apps.home') }}" class="w-8 h-8 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-600 shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-sora font-bold text-slate-800 text-base">Pengaturan Profil</h2>
        </div>



        <!-- General Profile Form -->
        <div class="bg-white border border-slate-100/80 shadow-xs rounded-2xl p-5 mb-6">
            <form id="profile-update-form" action="{{ route('apps.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-5">
                    
                    <!-- Avatar Selection -->
                    <div class="flex flex-col items-center">
                        <div class="relative group cursor-pointer" onclick="document.getElementById('avatar-input').click()">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 flex items-center justify-center shadow-xs">
                                <img id="avatar-preview" src="{{ $user->avatarUrl() }}" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center shadow-xs border border-white">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316A2.192 2.192 0 0015.402 4.5h-6.8a2.191 2.191 0 00-1.78.98L6.827 6.175zM12 10.025a3.75 3.75 0 110 7.5 3.75 3.75 0 010-7.5z" />
                                </svg>
                            </div>
                        </div>
                        <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*">
                        <span class="text-[10px] text-slate-400 font-bold mt-2">Ketuk untuk ubah foto</span>
                        @error('avatar')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name Field -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">
                        @error('name')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 transition-all font-semibold">
                        @error('email')
                            <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>



                    <!-- Submit Button -->
                    <button type="submit" id="profile-submit-btn"
                            class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-md active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Security & Reset Password Section -->
        <div class="bg-white border border-slate-100/80 shadow-xs rounded-2xl p-5 mb-6">
            <h3 class="font-sora font-bold text-slate-800 text-xs mb-3 uppercase tracking-wider text-slate-400">Keamanan Akun</h3>
            
            <p class="text-[11px] text-slate-500 leading-relaxed font-semibold mb-4">Untuk mengganti kata sandi login, kirim permintaan reset password melalui link sekali pakai yang akan dikirimkan ke email Anda.</p>
            
            <form id="reset-password-form" action="{{ route('apps.profile.password') }}" method="POST" onsubmit="confirmResetPassword(event)">
                @csrf
                <button type="submit"
                        class="w-full py-2.5 bg-slate-50 border border-slate-100 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold shadow-xs active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Kirim Link Ganti Password
                </button>
            </form>
        </div>

        <!-- Academic Info Details Card -->
        <div class="bg-white border border-slate-100/80 shadow-xs rounded-2xl p-5 space-y-4 mb-6">
            <h3 class="font-sora font-bold text-slate-800 text-xs uppercase tracking-wider text-slate-400">Informasi Akademik</h3>
            
            <div class="flex justify-between text-xs pt-1">
                <span class="text-slate-400 font-semibold">Tahun Pelajaran</span>
                <span class="font-bold text-slate-700">2025/2026 (Genap)</span>
            </div>
            <div class="flex justify-between text-xs pt-2 border-t border-slate-50">
                <span class="text-slate-400 font-semibold">Status Kelas</span>
                <span class="font-bold text-slate-700">Kelas XI - IPA</span>
            </div>
            <div class="flex justify-between text-xs pt-2 border-t border-slate-50">
                <span class="text-slate-400 font-semibold">Role Pengguna</span>
                <span class="font-bold text-slate-700">Siswa (MAM Limpung)</span>
            </div>
            <div class="flex justify-between text-[10px] pt-2 border-t border-slate-50 flex-col gap-1">
                <span class="text-slate-400 font-semibold">UUID Pengguna</span>
                <span class="font-mono text-slate-500 select-all break-all">{{ $user->uuid }}</span>
            </div>
        </div>

        <!-- Log Out Button -->
        <div class="px-2 pb-6">
            <form id="profile-logout-form" action="{{ route('auth.logout') }}" method="POST">
                @csrf
                <button type="button" onclick="confirmMobileLogoutForm()"
                        class="w-full py-3 bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 rounded-xl text-xs font-bold shadow-xs active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar dari Akun
                </button>
            </form>
        </div>

        <script>
            function confirmMobileLogoutForm() {
                if (window.MobilePopup) {
                    window.MobilePopup.confirm({
                        title: 'Keluar Aplikasi?',
                        description: 'Apakah Anda yakin ingin keluar dari akun Anda?',
                        confirmText: 'Ya, Keluar',
                        cancelText: 'Batal',
                        onConfirm: () => {
                            document.getElementById('profile-logout-form').submit();
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin keluar dari akun Anda?')) {
                        document.getElementById('profile-logout-form').submit();
                    }
                }
            }
        </script>
    </div>

    <!-- Image preview script -->
    <script>
        const avatarInput = document.getElementById('avatar-input');
        const avatarPreview = document.getElementById('avatar-preview');
        
        if (avatarInput && avatarPreview) {
            avatarInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Check file size (max 1MB)
                    if (file.size > 1024 * 1024) {
                        if (window.MobilePopup) {
                            window.MobilePopup.error({
                                title: 'File Terlalu Besar',
                                description: 'Ukuran foto profil maksimal adalah 1MB. Silakan pilih file yang lebih kecil.',
                                confirmText: 'Tutup'
                            });
                        } else {
                            alert('Ukuran foto profil maksimal adalah 1MB.');
                        }
                        this.value = ''; // Reset input value
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // Show uploading loader overlay on profile update form submit
        const profileForm = document.getElementById('profile-update-form');
        if (profileForm) {
            profileForm.addEventListener('submit', function() {
                if (window.showGlobalLoader) {
                    window.showGlobalLoader('Menyimpan Profil...', 'Sedang memperbarui informasi profil Anda');
                }
            });
        }

        // Alert info confirmation for sending reset password link
        function confirmResetPassword(event) {
            event.preventDefault();
            if (window.MobilePopup) {
                window.MobilePopup.confirm({
                    title: 'Kirim Link Ganti Sandi?',
                    description: 'Tautan untuk menyetel ulang kata sandi akan dikirim ke email terdaftar Anda (<strong>{{ $user->email }}</strong>). Lanjutkan?',
                    confirmText: 'Kirim Tautan',
                    cancelText: 'Batal',
                    onConfirm: () => {
                        if (window.showGlobalLoader) {
                            window.showGlobalLoader('Mengirim Link...', 'Sedang mengirim email pemulihan sandi');
                        }
                        document.getElementById('reset-password-form').submit();
                    }
                });
            } else {
                if (confirm('Kirim tautan reset kata sandi ke email {{ $user->email }}?')) {
                    if (window.showGlobalLoader) {
                        window.showGlobalLoader('Mengirim Link...', 'Sedang mengirim email pemulihan sandi');
                    }
                    document.getElementById('reset-password-form').submit();
                }
            }
        }
    </script>
@endsection
