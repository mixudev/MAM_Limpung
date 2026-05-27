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
    
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#f4f6f8] font-sans text-slate-800 antialiased h-screen w-screen overflow-hidden flex items-center justify-center p-4">

<div class="w-full max-w-md bg-white shadow-2xl border border-slate-200 p-8 flex flex-col justify-center relative">
    
    <div class="mb-6 text-center">
        <div class="w-12 h-12 bg-indigo-50 text-[#4f45b2] rounded-full flex items-center justify-center mx-auto mb-3 border border-indigo-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        <h3 class="text-xl font-black uppercase tracking-tighter text-slate-900 mb-1">Reset Kata Sandi</h3>
        <p class="text-xs text-slate-500 font-medium">Buat kata sandi baru untuk <strong>{{ $email }}</strong></p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ request()->fullUrl() }}" class="space-y-5">
        @csrf

        <!-- New Password -->
        <div x-data="{ showPassword: false }">
            <label for="password" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Kata Sandi Baru</label>
            <div class="relative">
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required autofocus
                    class="block w-full px-3 py-3 bg-slate-50 border border-slate-200 focus:outline-none focus:border-[#4f45b2] focus:bg-white transition-all text-sm font-medium"
                    placeholder="Minimal 8 karakter">
                
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-[#4f45b2] transition-colors focus:outline-none">
                    <template x-if="!showPassword">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </template>
                    <template x-if="showPassword">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </template>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-[10px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div x-data="{ showConfirmPassword: false }">
            <label for="password_confirmation" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Konfirmasi Kata Sandi Baru</label>
            <div class="relative">
                <input :type="showConfirmPassword ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                    class="block w-full px-3 py-3 bg-slate-50 border border-slate-200 focus:outline-none focus:border-[#4f45b2] focus:bg-white transition-all text-sm font-medium"
                    placeholder="Ulangi kata sandi baru">
                
                <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-[#4f45b2] transition-colors focus:outline-none">
                    <template x-if="!showConfirmPassword">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </template>
                    <template x-if="showConfirmPassword">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </template>
                </button>
            </div>
        </div>

        <div class="pt-2 flex flex-col gap-3">
            <button type="submit" class="w-full flex justify-center items-center py-3 px-4 text-xs font-bold text-white bg-[#4f45b2] hover:bg-[#6366f1] transition-colors uppercase tracking-widest">
                Simpan Kata Sandi Baru
            </button>
        </div>
    </form>

    <div class="mt-6 pt-4 text-center border-t border-slate-100">
        <a href="{{ route('login') }}" class="inline-flex items-center text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Batal & Login
        </a>
    </div>

</div>

</body>
</html>
