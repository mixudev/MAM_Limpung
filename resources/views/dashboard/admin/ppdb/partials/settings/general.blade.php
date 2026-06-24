<div id="tab-umum" class="tab-content space-y-6">

    <!-- Academic Years Table -->
    <div class="bg-white dark:bg-zinc-900 border-t-4 border-t-[#4f45b2] border-x border-b border-slate-200 dark:border-zinc-800 shadow-sm">
        <div class="p-6 pb-0">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <h3 class="text-sm font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8]">
                        Tahun Ajaran
                    </h3>
                    <span class="text-xs text-slate-400 dark:text-zinc-500">Kelola tahun ajaran, gelombang, dan alur PPDB.</span>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Professional Toggle Switch PPDB Status -->
                    <form action="{{ route('admin.ppdb.settings.general') }}" method="POST" id="ppdbToggleForm">
                        @csrf
                        <input type="hidden" name="is_open" id="is_open_input" value="{{ $general['is_open'] ? '1' : '0' }}">
                        <label class="relative inline-flex items-center cursor-pointer select-none gap-2 py-1.5 px-3 bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-none" style="display:inline-flex!important;margin-bottom:0!important;">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider select-none min-w-[36px] {{ $general['is_open'] ? 'text-emerald-600' : 'text-slate-400 dark:text-zinc-500' }}" id="ppdb_status_label">{{ $general['is_open'] ? 'BUKA' : 'TUTUP' }}</span>
                            <input type="checkbox" id="ppdb_toggle" {{ $general['is_open'] ? 'checked' : '' }} onchange="document.getElementById('is_open_input').value = this.checked ? '1' : '0'; document.getElementById('ppdbToggleForm').submit();" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-700 rounded-full peer peer-focus:ring-2 peer-focus:ring-[#4f45b2]/20 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </form>

                    <span class="text-slate-200 dark:border-zinc-700 select-none">|</span>

                    <!-- Add Year -->
                    <form action="{{ route('admin.ppdb.settings.years.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="year" value="{{ $academicYears->count() > 0 ? $academicYears->max('year') + 1 : date('Y') + 1 }}">
                        <button type="submit" class="py-2 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98] whitespace-nowrap flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Tahun
                            <span class="text-[10px] font-normal opacity-70">({{ $academicYears->count() > 0 ? $academicYears->max('year') + 1 : date('Y') + 1 }})</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto mt-4">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#4f45b2]/5 dark:bg-[#4f45b2]/[0.04] border-y border-slate-100 dark:border-zinc-800/80">
                        <th class="px-6 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Tahun</th>
                        <th class="px-6 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Nama</th>
                        <th class="px-6 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Status</th>
                        <th class="px-6 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Gelombang</th>
                        <th class="px-6 py-3 text-xs font-mono font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/50">
                    @forelse($academicYears as $ay)
                    <tr class="hover:bg-slate-50/20 transition-all group">
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm font-medium">{{ $ay->year }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $ay->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($ay->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-none uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold bg-slate-100 text-slate-400 border border-slate-200 rounded-none uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs text-slate-500 dark:text-zinc-400">{{ $ay->waves->count() }} gelombang</span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.ppdb.settings.years.show', $ay->id) }}"
                                   class="inline-flex items-center gap-1.5 py-1.5 px-3 text-[10px] font-bold bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white border border-[#4f45b2] rounded-none uppercase tracking-wider transition-all active:scale-[.97]">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Kelola
                                </a>

                                @if(!$ay->is_active)
                                <form action="{{ route('admin.ppdb.settings.years.activate', $ay->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="py-1.5 px-2.5 text-[10px] font-bold bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200 rounded-none uppercase tracking-wider transition-all">
                                        Aktifkan
                                    </button>
                                </form>
                                @endif

                                @if($ay->waves->isEmpty())
                                <form action="{{ route('admin.ppdb.settings.years.destroy', $ay->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tahun ajaran {{ $ay->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="py-1.5 px-2.5 text-[10px] font-bold bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-none uppercase tracking-wider transition-all">
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-400 dark:text-zinc-500">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <span class="text-xs">Belum ada tahun ajaran. Tambahkan tahun baru di atas.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
