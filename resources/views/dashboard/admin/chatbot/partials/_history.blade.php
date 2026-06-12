{{-- ═══ RIWAYAT CHAT ═══ --}}
<div class="p-6 space-y-4">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-white">Riwayat Chat Pengguna</h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5 font-mono">{{ $sessions->total() }} total sesi percakapan tercatat.</p>
        </div>
    </div>

    @if($sessions->isEmpty())
    <div class="border border-dashed border-slate-300 dark:border-zinc-700 p-10 text-center">
        <i class="fa-solid fa-comments text-3xl text-slate-300 dark:text-zinc-600 mb-3 block"></i>
        <p class="text-sm font-semibold text-slate-600 dark:text-zinc-400 font-mono">Belum ada riwayat percakapan.</p>
        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Riwayat muncul setelah pengguna mulai menggunakan chatbot.</p>
    </div>
    @else
    <div class="overflow-x-auto border border-slate-100 dark:border-zinc-800">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                    <th class="py-3.5 px-4">Sesi ID</th>
                    <th class="py-3.5 px-4">Topik</th>
                    <th class="py-3.5 px-4">Pengguna / IP</th>
                    <th class="py-3.5 px-4">Pesan</th>
                    <th class="py-3.5 px-4 hidden md:table-cell">Waktu Mulai</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                @foreach($sessions as $sess)
                @php
                    $topicBadge = [
                        'umum'     => 'bg-indigo-50 border-indigo-200 text-[#4f45b2] dark:bg-indigo-950/30 dark:border-indigo-800 dark:text-indigo-400',
                        'ppdb'     => 'bg-sky-50 border-sky-200 text-sky-700 dark:bg-sky-950/30 dark:border-sky-800 dark:text-sky-400',
                        'kegiatan' => 'bg-amber-50 border-amber-200 text-amber-700 dark:bg-amber-950/30 dark:border-amber-800 dark:text-amber-400',
                        'bantuan'  => 'bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-950/30 dark:border-emerald-800 dark:text-emerald-400',
                    ];
                @endphp
                <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30 transition-colors">
                    <td class="py-3.5 px-4">
                        <code class="text-[10px] text-slate-400 font-mono bg-slate-100 dark:bg-zinc-800 px-2 py-0.5">{{ substr($sess->id, 0, 8) }}…</code>
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="px-2 py-0.5 border text-[9px] font-bold font-mono uppercase tracking-wider {{ $topicBadge[$sess->topic] ?? 'bg-slate-50 border-slate-200 text-slate-500' }}">
                            {{ $sess->topic }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4">
                        @if($sess->user)
                        <div class="font-bold text-slate-800 dark:text-zinc-200">{{ $sess->user->name }}</div>
                        <div class="text-[10px] text-slate-400 font-mono">UID: {{ $sess->user_id }}</div>
                        @else
                        <div class="font-semibold text-slate-600 dark:text-zinc-400">Tamu</div>
                        <div class="text-[10px] text-slate-400 font-mono">{{ $sess->user_ip }}</div>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 font-bold font-mono text-slate-700 dark:text-zinc-300">
                        {{ $sess->messages_count }}
                    </td>
                    <td class="py-3.5 px-4 hidden md:table-cell text-[11px] text-slate-400 font-mono">
                        {{ $sess->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <button type="button" onclick="openTranscript('{{ $sess->id }}')"
                            class="inline-flex items-center gap-1 py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors cursor-pointer">
                            <i class="fa-solid fa-eye"></i> Transkrip
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pt-2">
        {{ $sessions->appends(request()->query())->links() }}
    </div>
    @endif
</div>
