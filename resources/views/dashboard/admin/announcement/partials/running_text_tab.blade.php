<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider font-mono">Daftar Kalimat Running Text</h3>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Anda dapat menambahkan banyak pengumuman berjalan. Semuanya akan digabungkan menjadi satu baris berjalan di halaman depan.</p>
        </div>
        <a href="{{ route('admin.announcements.texts.create') }}" class="py-2 px-3 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider flex items-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Teks
        </a>
    </div>

    <div class="overflow-x-auto border border-slate-100 dark:border-zinc-800">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-zinc-850 border-b border-slate-200 dark:border-zinc-800 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                    <th class="py-3.5 px-4">Judul & Isi Teks</th>
                    <th class="py-3.5 px-4 w-28 text-center">Status</th>
                    <th class="py-3.5 px-4 w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                @forelse($runningTexts as $text)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-850/30 transition-colors">
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-800 dark:text-zinc-300">{{ $text->title }}</div>
                            <div class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1 line-clamp-2 italic">"{{ $text->content }}"</div>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <form action="{{ route('admin.announcements.texts.toggle-active', $text) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="focus:outline-none">
                                    @if($text->is_active)
                                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/40">
                                            Non-Aktif
                                        </span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-1.5 whitespace-nowrap">
                            <a href="{{ route('admin.announcements.texts.edit', $text) }}" class="inline-block py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider">
                                Edit
                            </a>
                            <form action="{{ route('admin.announcements.texts.destroy', $text) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus running text ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="py-1 px-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] uppercase font-mono tracking-wider">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-slate-400 dark:text-zinc-500 italic">
                            Belum ada kalimat running text berjalan yang dibuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
