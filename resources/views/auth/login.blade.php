<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- SECURITY: Prevent caching of this sensitive page --}}
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <title>Login Portal Akademik — {{ config('app.name', 'MAM Limpung') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-[#f4f6f8] font-sans text-slate-800 antialiased h-screen w-screen overflow-hidden flex items-center justify-center p-4">

<div class="w-full max-w-4xl bg-white shadow-2xl flex flex-col md:flex-row h-full max-h-[600px] border border-slate-200">
    
    <!-- Left Side: Image Banner -->
    <div class="hidden md:block md:w-1/2 relative overflow-hidden bg-slate-900">
        <!-- Background Image -->
        <img src="{{ asset('assets/img/school.png') }}" alt="Sekolah" class="absolute inset-0 w-full h-full object-cover">
        
        <!-- Soft & Elegant Gradient Overlay -->
        <div class="absolute inset-0 bg-linear-to-tr from-slate-950 via-slate-900/80 to-blue-900/30 z-10 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-linear-to-t from-slate-900 via-slate-900/40 to-transparent z-10"></div>
        
        <div class="absolute bottom-10 left-10 z-20">
            <div class="w-8 h-1 bg-amber-500 mb-4 shadow-lg shadow-amber-500/20"></div>
            <h2 class="text-3xl font-black text-white uppercase tracking-tighter leading-tight mb-2 drop-shadow-lg">Portal<br>Akademik</h2>
            <p class="text-slate-200 text-xs font-bold tracking-widest uppercase drop-shadow-md">Sistem Informasi Terpadu</p>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full md:w-1/2 p-8 md:p-10 flex flex-col justify-center h-full bg-white relative">
        
        <div class="my-6">
            <h3 class="text-2xl font-black uppercase tracking-tighter text-slate-900 mb-1">Masuk Akun</h3>
            <p class="text-xs text-slate-500 font-medium">Silakan login untuk melanjutkan.</p>
        </div>

        @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200 p-3 mb-4 text-xs text-emerald-700 flex items-start">
                <svg class="w-4 h-4 mt-0.5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
            <div class="bg-red-50 border border-red-200 p-3 mb-4 text-xs text-red-700 flex items-start">
                <svg class="w-4 h-4 mt-0.5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('auth.login.post') }}" class="space-y-6">
            @csrf

            <!-- Username/Email -->
            <div class="my-5">
                <label for="email" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Username / Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                        class="block w-full pl-9 pr-3 py-3 bg-slate-50 border border-slate-200 focus:outline-none focus:border-blue-900 focus:bg-white transition-all text-sm font-medium"
                        placeholder="ID Pengguna">
                </div>
                @error('email')
                    <p class="mt-1 text-[10px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div x-data="{ showPassword: false }">
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Kata Sandi</label>
                    <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-blue-600 hover:text-amber-500 transition-colors tracking-widest">LUPA?</a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password"
                        class="block w-full pl-9 pr-10 py-3 bg-slate-50 border border-slate-200 focus:outline-none focus:border-blue-900 focus:bg-white transition-all text-sm font-medium"
                        placeholder="••••••••">
                    
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-900 transition-colors focus:outline-none">
                        <template x-if="!showPassword">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </template>
                        <template x-if="showPassword">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </template>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-[10px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 flex flex-col gap-3 my-4">
                <button type="submit" class="w-full flex justify-center items-center py-3 px-4 text-xs font-bold text-white bg-blue-900 hover:bg-amber-500 hover:text-blue-900 transition-colors uppercase tracking-widest">
                    Masuk
                </button>

                <a href="{{ route('login.otp') }}" class="w-full flex justify-center items-center py-3 px-4 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors uppercase tracking-widest text-center decoration-none">
                    Masuk dengan OTP
                </a>
                
                <button type="button" class="w-full flex justify-center items-center py-3 px-4 text-xs font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition-colors uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.7 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    Google
                </button>
            </div>
        </form>

        <div class=" pt-4 text-center border-t border-slate-100 mt-6">
            <a href="{{ url('/') }}" class="inline-flex items-center text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-blue-900 transition-colors">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>
        </div>

    </div>
</div>

</body>
</html>
