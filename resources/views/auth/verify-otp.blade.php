<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verifikasi OTP — {{ config('app.name', 'MAM Limpung') }}</title>
    
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f4f6f8] font-sans text-slate-800 antialiased h-screen w-screen overflow-hidden flex items-center justify-center p-4">

<div class="w-full max-w-4xl bg-white shadow-2xl flex flex-col md:flex-row h-full max-h-[600px] border border-slate-200">
    
    <!-- Left Side: Image Banner -->
    <div class="hidden md:block md:w-1/2 relative overflow-hidden bg-slate-900">
        <img src="{{ asset('assets/img/school.png') }}" alt="Sekolah" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-tr from-slate-950 via-slate-900/80 to-blue-900/30 z-10 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent z-10"></div>
        
        <div class="absolute bottom-10 left-10 z-20">
            <div class="w-8 h-1 bg-amber-500 mb-4 shadow-lg shadow-amber-500/20"></div>
            <h2 class="text-3xl font-black text-white uppercase tracking-tighter leading-tight mb-2 drop-shadow-lg">Verifikasi<br>Keamanan</h2>
            <p class="text-slate-200 text-xs font-bold tracking-widest uppercase drop-shadow-md">Masukkan Kode OTP Anda</p>
        </div>
    </div>

    <!-- Right Side: Verify OTP Form -->
    <div class="w-full md:w-1/2 p-8 md:p-10 flex flex-col justify-center h-full bg-white relative">
        
        <div class="my-4">
            <h3 class="text-2xl font-black uppercase tracking-tighter text-slate-900 mb-1">Verifikasi Kode</h3>
            <p class="text-xs text-slate-500 font-medium">Kami telah mengirimkan 6-digit kode keamanan ke email: <strong class="text-slate-700">{{ $email }}</strong></p>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 p-3 mb-4 text-xs text-emerald-700 flex items-start">
                <svg class="w-4 h-4 mt-0.5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 p-3 mb-4 text-xs text-red-700 flex items-start">
                <svg class="w-4 h-4 mt-0.5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login.otp.verify.post') }}" id="otp-form" class="space-y-6">
            @csrf
            
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="otp_code" id="otp_code">

            <!-- OTP Input Grid -->
            <div class="my-5">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 text-center">Masukkan 6-Digit OTP</label>
                
                <div class="flex gap-2 justify-center items-center" id="otp-inputs">
                    <input type="text" maxlength="1" inputmode="numeric" autocomplete="one-time-code"
                        class="otp-digit w-11 h-12 text-center text-xl font-bold bg-slate-50 border-2 border-slate-200 focus:outline-none focus:border-blue-900 focus:bg-white text-slate-800 transition-all"
                        data-index="0">
                    <input type="text" maxlength="1" inputmode="numeric"
                        class="otp-digit w-11 h-12 text-center text-xl font-bold bg-slate-50 border-2 border-slate-200 focus:outline-none focus:border-blue-900 focus:bg-white text-slate-800 transition-all"
                        data-index="1">
                    <input type="text" maxlength="1" inputmode="numeric"
                        class="otp-digit w-11 h-12 text-center text-xl font-bold bg-slate-50 border-2 border-slate-200 focus:outline-none focus:border-blue-900 focus:bg-white text-slate-800 transition-all"
                        data-index="2">
                    <input type="text" maxlength="1" inputmode="numeric"
                        class="otp-digit w-11 h-12 text-center text-xl font-bold bg-slate-50 border-2 border-slate-200 focus:outline-none focus:border-blue-900 focus:bg-white text-slate-800 transition-all"
                        data-index="3">
                    <input type="text" maxlength="1" inputmode="numeric"
                        class="otp-digit w-11 h-12 text-center text-xl font-bold bg-slate-50 border-2 border-slate-200 focus:outline-none focus:border-blue-900 focus:bg-white text-slate-800 transition-all"
                        data-index="4">
                    <input type="text" maxlength="1" inputmode="numeric"
                        class="otp-digit w-11 h-12 text-center text-xl font-bold bg-slate-50 border-2 border-slate-200 focus:outline-none focus:border-blue-900 focus:bg-white text-slate-800 transition-all"
                        data-index="5">
                </div>
                @error('otp_code')
                    <p class="mt-2 text-center text-[10px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 flex flex-col gap-3 my-4">
                <button type="submit" class="w-full flex justify-center items-center py-3 px-4 text-xs font-bold text-white bg-blue-900 hover:bg-amber-500 hover:text-blue-900 transition-colors uppercase tracking-widest">
                    Verifikasi & Masuk
                </button>
                
                <a href="{{ route('login.otp') }}" class="w-full flex justify-center items-center py-3 px-4 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors uppercase tracking-widest text-center decoration-none">
                    Minta Kode Baru
                </a>
            </div>
        </form>

    </div>
</div>

<script>
(function () {
    const digits = Array.from(document.querySelectorAll('.otp-digit'));
    const hidden  = document.getElementById('otp_code');
    const form    = document.getElementById('otp-form');

    function syncHidden() {
        hidden.value = digits.map(d => d.value).join('');
    }

    function focusNext(index) {
        if (index < digits.length - 1) digits[index + 1].focus();
    }

    function focusPrev(index) {
        if (index > 0) digits[index - 1].focus();
    }

    digits.forEach((input, i) => {

        // Pilih semua teks saat fokus agar mudah diganti
        input.addEventListener('focus', () => input.select());

        input.addEventListener('input', (e) => {
            // Bersihkan semua karakter non-angka
            const val = e.target.value.replace(/\D/g, '');
            input.value = val ? val[val.length - 1] : '';
            syncHidden();
            if (input.value) focusNext(i);
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace') {
                if (input.value) {
                    input.value = '';
                    syncHidden();
                } else {
                    focusPrev(i);
                }
                e.preventDefault();
            } else if (e.key === 'ArrowLeft') {
                focusPrev(i);
                e.preventDefault();
            } else if (e.key === 'ArrowRight') {
                focusNext(i);
                e.preventDefault();
            } else if (e.key === 'Enter') {
                form.requestSubmit();
            }
        });

        // Handle paste — bisa paste dari mana saja (digit pertama maupun tengah)
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData)
                .getData('text')
                .replace(/\D/g, '')
                .slice(0, 6);

            if (!pasted) return;

            // Isi mulai dari digit saat ini atau dari index 0
            const start = i;
            pasted.split('').forEach((char, offset) => {
                const target = digits[start + offset];
                if (target) target.value = char;
            });

            syncHidden();

            // Fokus ke digit setelah paste terakhir atau digit terakhir
            const nextFocus = Math.min(start + pasted.length, digits.length - 1);
            digits[nextFocus].focus();
        });
    });

    // Auto-submit saat 6 digit sudah terisi
    form.addEventListener('input', () => {
        if (hidden.value.length === 6) {
            // Beri jeda singkat agar user bisa lihat sebelum submit
            setTimeout(() => form.requestSubmit(), 300);
        }
    });

    // Fokus otomatis ke digit pertama saat halaman dibuka
    digits[0].focus();
})();
</script>

</body>
</html>