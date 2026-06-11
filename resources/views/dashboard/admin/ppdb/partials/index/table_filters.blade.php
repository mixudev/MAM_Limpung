<!-- Table Filters -->
<div class="p-6 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50">
    <form action="{{ route('admin.ppdb.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        
        <!-- Search Input (Col-span 2) -->
        <div class="relative md:col-span-2">
            
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NISN, registrasi, sekolah..."
                class="w-full pl-9 pr-4 py-2 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
        </div>

        <!-- Tahun Pelajaran Filter (Col-span 1) -->
        <div>
            <select name="tahun_ajaran" onchange="this.form.submit()"
                class="w-full py-2 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                @foreach($years as $yr)
                    <option value="{{ $yr }}" {{ (int) $selectedYear === (int) $yr ? 'selected' : '' }}>
                        Tahun: {{ $yr }}/{{ (int) $yr + 1 }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter (Col-span 1) -->
        <div>
            <select name="status" onchange="this.form.submit()"
                class="w-full py-2 px-3 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima (Terverifikasi)</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <!-- Filter Actions (Col-span 1) -->
        <div class="flex items-center gap-2">
            <button type="submit" class="flex-1 py-2 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-sm rounded-none tracking-wide transition-all active:scale-[.98]">
                Terapkan
            </button>
            @if(request('search') || request('status') || request('tahun_ajaran'))
                <a href="{{ route('admin.ppdb.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-600 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700 font-bold text-sm rounded-none text-center">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>
