<div class="space-y-6">
    <div class="bg-indigo-50/50 dark:bg-zinc-950 p-4 border border-indigo-100 dark:border-zinc-800 text-xs text-indigo-700 dark:text-zinc-400">
        <p class="font-bold flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Mode Lihat Saja (Read-Only)
        </p>
        <p class="mt-1">Daftar permission di bawah ini dikelola dan terdaftar langsung melalui database seeder (`PermissionSeeder.php`) untuk menjaga konsistensi kode fitur sistem. Perubahan pada hak akses/permission harus didasari perubahan kode pada migration/seeder.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($permissionsGrouped as $groupName => $permissions)
        <div class="border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
            <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 border-b border-slate-100 dark:border-zinc-800 pb-2 mb-3">
                Kategori: {{ $groupName }}
            </h3>
            
            <div class="space-y-3">
                @foreach ($permissions as $permission)
                <div class="text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-bold text-slate-800 dark:text-zinc-200">{{ $permission->display_name ?: $permission->name }}</span>
                        <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 font-mono text-[9px] text-slate-500 dark:text-zinc-400 rounded-none">
                            {{ $permission->name }}
                        </span>
                    </div>
                    @if($permission->description)
                        <p class="text-[10px] text-slate-500 dark:text-zinc-500 mt-1">{{ $permission->description }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
