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
    <div class="max-w-screen-lg mx-auto bg-gradient-to-r from-slate-50 to-slate-100 border border-blue-300 p-4 rounded shadow-lg hover:shadow-md transition-shadow flex flex-col sm:flex-row items-center gap-4 my-6">
        @if($resolvedAd->image)
            <div class="w-full sm:w-48 h-28 flex-shrink-0 overflow-hidden border border-slate-200/60 bg-white ">
                <img src="{{ asset('storage/' . $resolvedAd->image) }}" alt="{{ $resolvedAd->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
            </div>
        @endif
        
        <div class="flex-1 text-center sm:text-left space-y-1.5 py-1">
            <span class="inline-block px-2 py-0.5 text-[9px] font-bold font-mono tracking-widest uppercase bg-[#4f45b2]/10 text-[#4f45b2] ">Info</span>
            <h4 class="text-sm font-bold text-slate-800 eading-tight line-clamp-2">{{ $resolvedAd->title }}</h4>
            @if($resolvedAd->description)
                <p class="text-xs text-slate-500 ine-clamp-2 font-mono leading-relaxed">{{ $resolvedAd->description }}</p>
            @endif
        </div>

        @if($resolvedAd->action_url)
            <div class="flex-shrink-0 w-full sm:w-auto">
                <a href="{{ $resolvedAd->action_url }}" target="_blank" class="block w-full sm:w-auto text-center py-2.5 px-5 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-mono font-bold text-xs uppercase tracking-wider transition-colors rounded-none">
                    {{ $resolvedAd->action_text ?: 'Selengkapnya' }}
                </a>
            </div>
        @endif
    </div>
@endif
