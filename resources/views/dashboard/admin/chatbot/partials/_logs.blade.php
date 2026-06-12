{{-- ═══ LOG AKTIFITAS CHATBOT ═══ --}}
<div class="p-6 space-y-4">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-white">Log Aktifitas AI Chatbot</h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5 font-mono">{{ $logs->total() }} total log aktifitas tercatat.</p>
        </div>
        @if(!$logs->isEmpty())
        <div>
            <form action="{{ route('admin.chatbot.logs.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua log aktifitas chatbot? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 py-1.5 px-3 bg-rose-500 hover:bg-rose-600 text-white font-bold text-[10px] uppercase font-mono tracking-wider transition-colors cursor-pointer shadow-sm">
                    <i class="fa-solid fa-trash-can"></i> Hapus Semua Log
                </button>
            </form>
        </div>
        @endif
    </div>

    @if($logs->isEmpty())
    <div class="border border-dashed border-slate-300 dark:border-zinc-700 p-10 text-center">
        <i class="fa-solid fa-list-check text-3xl text-slate-300 dark:text-zinc-600 mb-3 block"></i>
        <p class="text-sm font-semibold text-slate-600 dark:text-zinc-400 font-mono">Belum ada log aktifitas.</p>
        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Log akan terisi otomatis ketika ada aktivitas chatbot (pesan terkirim, faq dipicu, atau error).</p>
    </div>
    @else
    <div class="overflow-x-auto border border-slate-100 dark:border-zinc-800">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                    <th class="py-3.5 px-4">Level</th>
                    <th class="py-3.5 px-4">Pesan</th>
                    <th class="py-3.5 px-4">Sesi ID</th>
                    <th class="py-3.5 px-4">API Key ID</th>
                    <th class="py-3.5 px-4 hidden md:table-cell">Waktu Kejadian</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                @foreach($logs as $log)
                @php
                    $levelBadge = [
                        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-950/30 dark:border-emerald-800 dark:text-emerald-400',
                        'info'    => 'bg-blue-50 border-blue-200 text-blue-700 dark:bg-blue-950/30 dark:border-blue-800 dark:text-blue-400',
                        'warning' => 'bg-amber-50 border-amber-200 text-amber-700 dark:bg-amber-950/30 dark:border-amber-800 dark:text-amber-400',
                        'error'   => 'bg-rose-50 border-rose-200 text-rose-700 dark:bg-rose-950/30 dark:border-rose-800 dark:text-rose-400',
                    ];
                @endphp
                <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30 transition-colors">
                    <td class="py-3.5 px-4">
                        <span class="px-2 py-0.5 border text-[9px] font-bold font-mono uppercase tracking-wider {{ $levelBadge[$log->level] ?? 'bg-slate-50 border-slate-200 text-slate-500' }}">
                            {{ $log->level }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 font-mono font-medium max-w-xs truncate md:max-w-md" title="{{ $log->message }}">
                        {{ $log->message }}
                    </td>
                    <td class="py-3.5 px-4">
                        @if($log->session_id)
                        <button type="button" onclick="openTranscript('{{ $log->session_id }}')" class="text-[10px] text-indigo-600 dark:text-indigo-400 hover:underline font-mono cursor-pointer">
                            {{ substr($log->session_id, 0, 8) }}…
                        </button>
                        @else
                        <span class="text-slate-400 font-mono">-</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 font-mono">
                        @if($log->api_key_id)
                        <span class="text-slate-700 dark:text-zinc-300">ID: {{ $log->api_key_id }}</span>
                        @if($log->apiKey)
                        <span class="text-[10px] text-slate-400">({{ $log->apiKey->model_name }})</span>
                        @endif
                        @else
                        <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 hidden md:table-cell text-[11px] text-slate-400 font-mono">
                        {{ $log->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s') }}
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <a href="{{ route('admin.chatbot.logs.show', $log) }}"
                            class="inline-flex items-center gap-1 py-1 px-2 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pt-2">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>
