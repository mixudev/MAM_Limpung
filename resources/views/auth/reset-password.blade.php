<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Atur Ulang Kata Sandi — {{ config('app.name', 'MAM Limpung') }}</title>
    
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
            <h2 class="text-3xl font-black text-white uppercase tracking-tighter leading-tight mb-2 drop-shadow-lg">Kata<br>Sandi Baru</h2>
            <p class="text-slate-200 text-xs font-bold tracking-widest uppercase drop-shadow-md">Amankan Akun Anda Kembali</p>
        </div>
    </div>

    <!-- Right Side: Reset Form -->
    <div class="w-full md:w-1/2 p-8 md:p-10 flex flex-col justify-center h-full bg-white relative">
        
        <div class="my-4">
            <h3 class="text-2xl font-black uppercase tracking-tighter text-slate-900 mb-1">Atur Ulang Sandi</h3>
            <p class="text-xs text-slate-500 font-medium">Buat kata sandi baru untuk akun Anda.</p>
        </div>

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 p-3 mb-4 text-xs text-red-700 flex items-start">
                <svg class="w-4 h-4 mt-0.5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required readonly
                        class="block w-full pl-9 pr-3 py-2.5 bg-slate-100 border border-slate-200 text-slate-500 focus:outline-none text-sm font-medium">
                </div>
                @error('email')
                    <p class="mt-1 text-[10px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div x-data="{ showPassword: false }">
                <label for="password" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Kata Sandi Baru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required autofocus
                        class="block w-full pl-9 pr-10 py-2.5 bg-slate-50 border border-slate-200 focus:outline-none focus:border-blue-900 focus:bg-white transition-all text-sm font-medium"
                        placeholder="Minimal 8 karakter">
                    
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

            <!-- Confirm Password -->
            <div x-data="{ showPassword: false }">
                <label for="password_confirmation" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                        class="block w-full pl-9 pr-10 py-2.5 bg-slate-50 border border-slate-200 focus:outline-none focus:border-blue-900 focus:bg-white transition-all text-sm font-medium"
                        placeholder="Ulangi kata sandi baru">
                    
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-900 transition-colors focus:outline-none">
                        <template x-if="!showPassword">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </template>
                        <template x-if="showPassword">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </template>
                    </button>
                </div>
            </div>

            <div class="pt-2 flex flex-col gap-3 my-4">
                <button type="submit" class="w-full flex justify-center items-center py-3 px-4 text-xs font-bold text-white bg-blue-900 hover:bg-amber-500 hover:text-blue-900 transition-colors uppercase tracking-widest">
                    Simpan Sandi Baru
                </button>
            </div>
        </form>

    </div>
</div>

</body>
</html>
