<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lupa Kata Sandi — {{ config('app.name', 'MAM Limpung') }}</title>
    
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
            <h2 class="text-3xl font-black text-white uppercase tracking-tighter leading-tight mb-2 drop-shadow-lg">Keamanan<br>Portal</h2>
            <p class="text-slate-200 text-xs font-bold tracking-widest uppercase drop-shadow-md">Atur Ulang Akses Anda</p>
        </div>
    </div>

    <!-- Right Side: Request Reset Form -->
    <div class="w-full md:w-1/2 p-8 md:p-10 flex flex-col justify-center h-full bg-white relative">
        
        <div class="my-6">
            <h3 class="text-2xl font-black uppercase tracking-tighter text-slate-900 mb-1">Lupa Kata Sandi</h3>
            <p class="text-xs text-slate-500 font-medium">Masukkan email Anda untuk menerima tautan atur ulang kata sandi.</p>
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
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="my-5">
                <label for="email" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                        class="block w-full pl-9 pr-3 py-3 bg-slate-50 border border-slate-200 focus:outline-none focus:border-blue-900 focus:bg-white transition-all text-sm font-medium"
                        placeholder="contoh@domain.com">
                </div>
                @error('email')
                    <p class="mt-1 text-[10px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 flex flex-col gap-3 my-4">
                <button type="submit" class="w-full flex justify-center items-center py-3 px-4 text-xs font-bold text-white bg-blue-900 hover:bg-amber-500 hover:text-blue-900 transition-colors uppercase tracking-widest">
                    Kirim Link Reset
                </button>
                
                <a href="{{ route('login') }}" class="w-full flex justify-center items-center py-3 px-4 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors uppercase tracking-widest text-center decoration-none">
                    Kembali ke Login
                </a>
            </div>
        </form>

        <div class="mt-auto pt-4 text-center border-t border-slate-100 mt-6">
            <a href="{{ route('login.otp') }}" class="inline-flex items-center text-[10px] font-bold uppercase tracking-widest text-blue-600 hover:text-amber-500 transition-colors">
                Gunakan Login OTP Sebagai Alternatif
            </a>
        </div>

    </div>
</div>

</body>
</html>
