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
    <div class="bg-gradient-to-br from-indigo-50/30 to-slate-50/50 dark:from-zinc-900 dark:to-zinc-850 border-2 border-dashed border-indigo-200 dark:border-zinc-700/60 p-5 flex flex-col justify-between items-center text-center space-y-4 hover:border-indigo-400 dark:hover:border-zinc-500 transition-colors my-4">
        <div class="space-y-1">
            <span class="inline-block px-2 py-0.5 text-[8px] font-bold font-mono tracking-widest uppercase bg-indigo-100 dark:bg-zinc-800 text-indigo-700 dark:text-indigo-300">INFO BERSAMA</span>
            <h4 class="text-sm font-bold text-slate-800 dark:text-zinc-200 leading-snug line-clamp-2 mt-1">{{ $resolvedAd->title }}</h4>
            @if($resolvedAd->description)
                <p class="text-[11px] text-slate-500 dark:text-zinc-400 line-clamp-2 font-mono leading-relaxed mt-1">{{ $resolvedAd->description }}</p>
            @endif
        </div>

        @if($resolvedAd->image)
            <div class="w-full aspect-video max-h-36 overflow-hidden border border-slate-200 dark:border-zinc-850 shadow-sm bg-white dark:bg-zinc-950">
                <img src="{{ asset('storage/' . $resolvedAd->image) }}" alt="{{ $resolvedAd->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        @if($resolvedAd->action_url)
            <a href="{{ $resolvedAd->action_url }}" target="_blank" class="w-full py-2 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-mono font-bold text-[10px] tracking-wider uppercase transition-colors">
                {{ $resolvedAd->action_text ?: 'Cari Tahu' }}
            </a>
        @endif
    </div>
@endif
