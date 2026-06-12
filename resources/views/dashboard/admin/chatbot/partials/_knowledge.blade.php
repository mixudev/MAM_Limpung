{{-- ═══ KNOWLEDGE BASE ═══ --}}
<div class="p-6 space-y-4">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-white">Basis Pengetahuan Sekolah</h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5 font-mono">Informasi ini dijadikan konteks AI saat menjawab pertanyaan pengguna.</p>
        </div>
        <button type="button" onclick="openKnowledgeModal(false, null)"
            class="py-2.5 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs tracking-wider font-mono transition-colors flex items-center gap-2 cursor-pointer shrink-0">
            <i class="fa-solid fa-plus"></i> TAMBAH PENGETAHUAN
        </button>
    </div>

    @if($knowledgeBases->isEmpty())
    <div class="border border-dashed border-slate-300 dark:border-zinc-700 p-10 text-center">
        <i class="fa-solid fa-book-open text-3xl text-slate-300 dark:text-zinc-600 mb-3 block"></i>
        <p class="text-sm font-semibold text-slate-600 dark:text-zinc-400 font-mono">Basis pengetahuan masih kosong.</p>
        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1 mb-4">Tambahkan informasi sekolah agar AI dapat menjawab pertanyaan dengan akurat.</p>
        <button type="button" onclick="openKnowledgeModal(false, null)"
            class="py-2 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs font-mono tracking-wider transition-colors inline-flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-plus"></i> TAMBAH PERTAMA
        </button>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($knowledgeBases as $kb)
        @php
            $topicBadge = [
                'umum'     => 'bg-indigo-50 border-indigo-200 text-[#4f45b2] dark:bg-indigo-950/30 dark:border-indigo-800 dark:text-indigo-400',
                'ppdb'     => 'bg-sky-50 border-sky-200 text-sky-700 dark:bg-sky-950/30 dark:border-sky-800 dark:text-sky-400',
                'kegiatan' => 'bg-amber-50 border-amber-200 text-amber-700 dark:bg-amber-950/30 dark:border-amber-800 dark:text-amber-400',
                'bantuan'  => 'bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-950/30 dark:border-emerald-800 dark:text-emerald-400',
            ];
        @endphp
        <div class="border border-slate-200 dark:border-zinc-700 p-5 flex flex-col justify-between hover:border-[#4f45b2]/40 transition-colors">
            <div>
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h4 class="font-bold text-slate-800 dark:text-zinc-200 text-sm leading-snug">{{ $kb->title }}</h4>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="px-2 py-0.5 border text-[9px] font-bold font-mono uppercase tracking-wider {{ $topicBadge[$kb->topic] ?? '' }}">
                            {{ $kb->topic }}
                        </span>
                        @if(!$kb->is_active)
                        <span class="px-2 py-0.5 border border-slate-200 dark:border-zinc-700 text-[9px] font-bold font-mono uppercase bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400">OFF</span>
                        @endif
                    </div>
                </div>
                <p class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed overflow-hidden" style="-webkit-line-clamp:3;display:-webkit-box;-webkit-box-orient:vertical">{{ $kb->content }}</p>
                <p class="text-[10px] text-slate-300 dark:text-zinc-600 font-mono mt-2">{{ $kb->updated_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</p>
            </div>
            <div class="flex justify-end gap-1.5 mt-4 pt-3 border-t border-slate-100 dark:border-zinc-800">
                <button type="button"
                    onclick="openKnowledgeModal(true, {{ json_encode(['id'=>$kb->id,'topic'=>$kb->topic,'title'=>$kb->title,'content'=>$kb->content,'is_active'=>(int)$kb->is_active]) }})"
                    class="inline-flex items-center gap-1 py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors cursor-pointer">
                    <i class="fa-solid fa-pen"></i> Edit
                </button>
                <form action="{{ route('admin.chatbot.knowledge.destroy', $kb) }}" method="POST"
                    onsubmit="return confirm('Hapus pengetahuan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center py-1 px-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors cursor-pointer">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
