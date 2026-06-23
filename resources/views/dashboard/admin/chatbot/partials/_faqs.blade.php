{{-- ═══ FAQ CEPAT ═══ --}}
<div class="p-6 space-y-4">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-white">FAQ Cepat</h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5 font-mono">Pertanyaan pintasan di awal chatbot. Dijawab instan tanpa memanggil AI.</p>
        </div>
        <button type="button" onclick="openFaqModal(false, null)"
            class="py-2.5 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs tracking-wider font-mono transition-colors flex items-center gap-2 cursor-pointer shrink-0">
            <i class="fa-solid fa-plus"></i> TAMBAH FAQ
        </button>
    </div>

    @if($faqs->isEmpty())
    <div class="border border-dashed border-slate-300 dark:border-zinc-700 p-10 text-center">
        <i class="fa-solid fa-circle-question text-3xl text-slate-300 dark:text-zinc-600 mb-3 block"></i>
        <p class="text-sm font-semibold text-slate-600 dark:text-zinc-400 font-mono">Belum ada FAQ terdaftar.</p>
        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1 mb-4">Tambahkan pertanyaan agar chatbot bisa menjawab dengan cepat.</p>
        <button type="button" onclick="openFaqModal(false, null)"
            class="py-2 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs font-mono tracking-wider transition-colors inline-flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-plus"></i> TAMBAH FAQ PERTAMA
        </button>
    </div>
    @else
    <div class="overflow-x-auto border border-slate-100 dark:border-zinc-800">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                    <th class="py-3.5 px-4 w-16">Urut</th>
                    <th class="py-3.5 px-4">Pertanyaan &amp; Jawaban</th>
                    <th class="py-3.5 px-4 text-center w-20">Status</th>
                    <th class="py-3.5 px-4 text-right w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                @foreach($faqs as $faq)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30 transition-colors">
                    <td class="py-3.5 px-4">
                        <span class="w-7 h-7 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-400 font-bold font-mono text-xs flex items-center justify-center">
                            {{ $faq->order }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4">
                        <div class="font-bold text-slate-800 dark:text-zinc-200 leading-snug">{{ $faq->question }}</div>
                        <div class="text-[11px] text-slate-400 dark:text-zinc-500 mt-1 leading-relaxed overflow-hidden" style="-webkit-line-clamp:2;display:-webkit-box;-webkit-box-orient:vertical">{{ $faq->answer }}</div>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <span class="px-2 py-0.5 border text-[9px] font-bold font-mono uppercase tracking-wider
                            {{ $faq->is_active
                                ? 'bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-950/20 dark:border-emerald-800 dark:text-emerald-400'
                                : 'bg-slate-100 border-slate-200 text-slate-500 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400' }}">
                            {{ $faq->is_active ? 'AKTIF' : 'OFF' }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <button type="button"
                                onclick="openFaqModal(true, {{ json_encode(['id'=>$faq->id,'question'=>$faq->question,'answer'=>$faq->answer,'order'=>$faq->order,'is_active'=>(int)$faq->is_active]) }})"
                                class="inline-flex items-center gap-1 py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors cursor-pointer">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                            <form action="{{ route('admin.chatbot.faqs.destroy', $faq) }}" method="POST"
                                onsubmit="return confirm('Hapus FAQ ini?')">
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
