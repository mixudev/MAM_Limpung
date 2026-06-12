{{-- ═══ API KEYS ═══ --}}
<div class="p-6 space-y-4">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-white">Daftar Kunci API AI</h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5 font-mono">API Key dienkripsi. Minimal 1 key aktif agar chatbot merespons.</p>
        </div>
        <button type="button" onclick="openKeyModal(false, null)"
            class="py-2.5 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs tracking-wider font-mono transition-colors flex items-center gap-2 cursor-pointer shrink-0">
            <i class="fa-solid fa-plus"></i> TAMBAH API KEY
        </button>
    </div>

    <div class="flex items-start gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/50 text-xs text-amber-700 dark:text-amber-400 font-mono">
        <i class="fa-solid fa-lock shrink-0 mt-0.5"></i>
        <span>API Key dienkripsi sebelum disimpan. Nilai asli tidak dapat dilihat kembali. Klik status untuk aktifkan/nonaktifkan.</span>
    </div>

    @if($apiKeys->isEmpty())
    <div class="border border-dashed border-slate-300 dark:border-zinc-700 p-10 text-center">
        <i class="fa-solid fa-key text-3xl text-slate-300 dark:text-zinc-600 mb-3 block"></i>
        <p class="text-sm font-semibold text-slate-600 dark:text-zinc-400 font-mono">Belum ada API Key terdaftar.</p>
        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1 mb-4">Chatbot tidak akan merespons sebelum API Key ditambahkan.</p>
        <button type="button" onclick="openKeyModal(false, null)"
            class="py-2 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs font-mono tracking-wider transition-colors inline-flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-plus"></i> TAMBAH API KEY PERTAMA
        </button>
    </div>
    @else
    <div class="overflow-x-auto border border-slate-100 dark:border-zinc-800">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                    <th class="py-3.5 px-4">Provider / Model</th>
                    <th class="py-3.5 px-4">API Key</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4">Error</th>
                    <th class="py-3.5 px-4 hidden md:table-cell">Limit Terakhir</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                @foreach($apiKeys as $key)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30 transition-colors">
                    <td class="py-3.5 px-4">
                        <div class="font-bold text-slate-800 dark:text-zinc-200">{{ strtoupper($key->provider) }}</div>
                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $key->model_name }}</div>
                    </td>
                    <td class="py-3.5 px-4">
                        <code class="text-[11px] text-slate-400 font-mono bg-slate-100 dark:bg-zinc-800 px-2 py-0.5">••••••••••••••••</code>
                    </td>
                    <td class="py-3.5 px-4">
                        <form action="{{ route('admin.chatbot.apikeys.toggle', $key) }}" method="POST">
                            @csrf @method('PUT')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 py-1 px-2.5 border font-bold text-[10px] font-mono uppercase tracking-wider cursor-pointer transition-colors
                                    {{ $key->is_active
                                        ? 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/20 dark:border-emerald-800 dark:text-emerald-400'
                                        : 'bg-slate-100 border-slate-200 text-slate-500 hover:bg-slate-200 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400' }}">
                                <span class="w-1.5 h-1.5 {{ $key->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $key->is_active ? 'AKTIF' : 'OFF' }}
                            </button>
                        </form>
                    </td>
                    <td class="py-3.5 px-4">
                        @if($key->error_count > 0)
                        <span class="px-2 py-0.5 bg-red-50 border border-red-200 text-red-600 dark:bg-red-950/20 dark:border-red-800 dark:text-red-400 text-[10px] font-bold font-mono uppercase">
                            {{ $key->error_count }} ERR
                        </span>
                        @else
                        <span class="text-[11px] text-slate-400 font-mono">—</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 hidden md:table-cell text-[11px] text-slate-400 font-mono">
                        {{ $key->limit_reached_at ? $key->limit_reached_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') : '—' }}
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <button type="button"
                                onclick="openKeyModal(true, {{ json_encode(['id'=>$key->id,'provider'=>$key->provider,'model_name'=>$key->model_name]) }})"
                                class="inline-flex items-center gap-1 py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors cursor-pointer">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                            <form action="{{ route('admin.chatbot.apikeys.destroy', $key) }}" method="POST"
                                onsubmit="return confirm('Hapus API Key {{ $key->model_name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center py-1 px-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors cursor-pointer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
