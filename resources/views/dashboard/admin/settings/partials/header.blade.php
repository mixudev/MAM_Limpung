<!-- Header -->
<div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Pengaturan Website</h1>
        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Kelola informasi sekolah, detail kontak, media sosial, dan metadata SEO untuk frontend.</p>
    </div>
</div>

<!-- Alert Success -->
{{-- @if(session('success'))
<div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/60 p-4 text-emerald-800 dark:text-emerald-400 text-xs font-semibold rounded-none flex items-center gap-2">
    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif --}}

<!-- Alert Errors -->
{{-- @if ($errors->any())
<div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/60 p-4 text-red-800 dark:text-red-400 text-xs font-semibold rounded-none">
    <p class="font-bold mb-2">Terjadi kesalahan input:</p>
    <ul class="list-disc list-inside space-y-1 font-mono">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif --}}
