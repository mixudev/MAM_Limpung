@props(['ad' => null, 'id' => null])

@php
    $resolvedAd = $ad;
    if (!$resolvedAd) {
        if ($id) {
            $resolvedAd = \App\Models\AnnounceAd::active()->find($id);
        } else {
            $resolvedAd = \App\Models\AnnounceAd::active()->inRandomOrder()->first();
        }
    }
@endphp

@if($resolvedAd)
    <div class="w-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col gap-4 my-4">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-2">
            <span class="text-[10px] font-bold font-mono tracking-widest uppercase bg-slate-100 dark:bg-zinc-850 text-slate-500 dark:text-zinc-400 px-2 py-0.5">SPONSOR</span>
            <span class="w-1.5 h-1.5 rounded-full bg-[#4f45b2]"></span>
        </div>

        @if($resolvedAd->image)
            <div class="w-full aspect-[4/3] overflow-hidden border border-slate-200/60 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 flex-shrink-0">
                <img src="{{ asset('storage/' . $resolvedAd->image) }}" alt="{{ $resolvedAd->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
            </div>
        @endif
        
        <div class="space-y-2 flex-1 flex flex-col justify-between">
            <div>
                <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-200 leading-snug line-clamp-2">{{ $resolvedAd->title }}</h4>
                @if($resolvedAd->description)
                    <p class="text-[10px] text-slate-500 dark:text-zinc-400 mt-1 line-clamp-3 font-mono leading-relaxed">{{ $resolvedAd->description }}</p>
                @endif
            </div>
            
            @if($resolvedAd->action_url)
                <a href="{{ $resolvedAd->action_url }}" target="_blank" class="block w-full text-center py-2 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 text-[#4f45b2] dark:text-indigo-400 font-mono font-bold text-[10px] uppercase tracking-wider transition-colors border border-slate-200 dark:border-zinc-700">
                    {{ $resolvedAd->action_text ?: 'Kunjungi' }}
                </a>
            @endif
        </div>
    </div>
@endif
