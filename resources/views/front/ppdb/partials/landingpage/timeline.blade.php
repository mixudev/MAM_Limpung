<section id="alur-pendaftaran" class="ppdb-section scroll-mt-[130px] py-16 bg-gray-50 overflow-hidden">
    <div class="max-w-5xl mx-auto px-6">

        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-16 fade-up-init">
            <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Timeline PPDB</span>
            <h2 class="text-2xl font-bold text-gray-900 mt-3 leading-tight">Peta Jalan Pendaftaran</h2>
            <p class="text-gray-400 mt-2 text-xs md:text-sm">Ikuti tahapan seleksi PPDB MAS Muhammadiyah Limpung dengan mudah dan terencana.</p>
        </div>

        @php
            $today       = date('Y-m-d');
            $totalWaves  = $waves->count();
            $activeIndex = null;
            $allDone     = true;

            foreach ($waves as $i => $w) {
                $wStart = $w->start_date->format('Y-m-d');
                $wEnd = $w->end_date?->format('Y-m-d');
                if ($wEnd) {
                    if ($today >= $wStart && $today <= $wEnd) {
                        $activeIndex = $i;
                    }
                    if ($today <= $wEnd) {
                        $allDone = false;
                    }
                } else {
                    if ($today >= $wStart) {
                        $activeIndex = $i;
                    } else {
                        $allDone = false;
                    }
                }
            }

            if ($activeIndex !== null) {
                $progressPct = $totalWaves === 1 ? 100 : round($activeIndex / ($totalWaves - 1) * 100);
            } elseif ($allDone) {
                $progressPct = 100;
            } else {
                $progressPct = 0;
            }

            $nodeSize  = 28;
        @endphp

        @if($totalWaves > 0)

        {{-- ===== DESKTOP ===== --}}
        <div class="hidden md:block fade-up-init">

            {{--
                Layout rows (top-to-bottom):
                - Top label area  : 64px
                - Node row        : 28px  (nodeSize)
                - Status label    : 20px
                - Bottom label    : 64px
                Total             : 176px
            --}}
            <div class="relative" style="height: 176px;">

                {{--
                    Track sits exactly at vertical midpoint of node row.
                    Node row starts at top=64px, node half = 14px
                    So track top = 64 + 14 - 2 = 76px  (2 = half of 4px track height)
                --}}
                <div class="absolute z-0 rounded-full bg-gray-200"
                     style="top: 76px; height: 4px; left: 0; right: 0;">
                    <div class="h-full rounded-full bg-gray-800 transition-all duration-700"
                         style="width: {{ $progressPct }}%"></div>
                </div>

                @foreach($waves as $index => $wave)
                    @php
                        $startDate = $wave->start_date;
                        $hasEndDate = $wave->end_date !== null;
                        $waveStartStr = $startDate->format('Y-m-d');

                        if ($today < $waveStartStr) {
                            $status      = 'upcoming';
                            $statusLabel = 'Segera';
                            $nodeCls     = 'bg-white border-2 border-gray-300';
                            $innerCls    = 'w-2.5 h-2.5 rounded-full bg-gray-300';
                            $labelCls    = 'text-gray-400';
                            $showCheck   = false;
                        } elseif ($hasEndDate && $today > $wave->end_date->format('Y-m-d')) {
                            $status      = 'closed';
                            $statusLabel = 'Selesai';
                            $nodeCls     = 'bg-gray-800 border-2 border-gray-800';
                            $innerCls    = '';
                            $labelCls    = 'text-gray-400';
                            $showCheck   = true;
                        } else {
                            $status      = 'active';
                            $statusLabel = 'Berlangsung';
                            $nodeCls     = 'bg-white border-2 border-blue-600';
                            $innerCls    = 'w-2.5 h-2.5 rounded-full bg-blue-600';
                            $labelCls    = 'text-blue-600';
                            $showCheck   = false;
                        }

                        $isEven = $index % 2 === 0;
                        $leftPct = $totalWaves === 1 ? 50 : round($index / ($totalWaves - 1) * 100);
                        $formattedStart = $startDate->translatedFormat('d M');
                        $formattedStartWithYear = $startDate->translatedFormat('d M Y');
                        $formattedEnd   = $hasEndDate ? $wave->end_date->translatedFormat('d M Y') : null;
                    @endphp

                    <div class="absolute group"
                         style="left: {{ $leftPct }}%; top: 0; bottom: 0; transform: translateX(-50%);">

                        <div class="flex flex-col items-center justify-end text-center"
                             style="height: 60px; padding-bottom: 8px; min-width: 100px;">
                            @if($isEven)
                                <p class="text-xs font-bold text-gray-800 leading-snug whitespace-nowrap">{{ $wave->name }}</p>
                                @if($hasEndDate)
                                    <p class="text-[11px] text-gray-400 mt-0.5 whitespace-nowrap">{{ $formattedStart }} – {{ $formattedEnd }}</p>
                                @else
                                    <p class="text-[11px] text-gray-400 mt-0.5 whitespace-nowrap">{{ $formattedStartWithYear }}</p>
                                @endif
                            @endif
                        </div>

                        <div class="flex items-center justify-center relative" style="height: {{ $nodeSize }}px;">
                            @if($status === 'active')
                                <span class="absolute rounded-full bg-blue-100 animate-ping opacity-40"
                                      style="width: 44px; height: 44px;"></span>
                            @endif
                            <div class="rounded-full {{ $nodeCls }} flex items-center justify-center shadow z-10 relative transition-transform duration-200 group-hover:scale-110"
                                 style="width: {{ $nodeSize }}px; height: {{ $nodeSize }}px;">
                                @if($showCheck)
                                    <svg style="width:13px;height:13px;" class="text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif($innerCls)
                                    <span class="{{ $innerCls }} {{ $status === 'active' ? 'animate-pulse' : '' }}"></span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-center" style="height: 20px;">
                            <p class="text-[10px] font-bold uppercase tracking-widest {{ $labelCls }} whitespace-nowrap">{{ $statusLabel }}</p>
                        </div>

                        <div class="flex flex-col items-center justify-start text-center"
                             style="height: 68px; padding-top: 8px; min-width: 100px;">
                            @if(!$isEven)
                                <p class="text-xs font-bold text-gray-800 leading-snug whitespace-nowrap">{{ $wave->name }}</p>
                                @if($hasEndDate)
                                    <p class="text-[11px] text-gray-400 mt-0.5 whitespace-nowrap">{{ $formattedStart }} – {{ $formattedEnd }}</p>
                                @else
                                    <p class="text-[11px] text-gray-400 mt-0.5 whitespace-nowrap">{{ $formattedStartWithYear }}</p>
                                @endif
                            @endif
                        </div>

                    </div>
                @endforeach

            </div>
        </div>

        {{-- ===== MOBILE: vertical list ===== --}}
        <div class="md:hidden relative fade-up-init">

            {{-- Track: left offset = half of node (14px) --}}
            <div class="absolute rounded-full bg-gray-200 z-0"
                 style="left: 13px; top: 14px; bottom: 14px; width: 3px;">
                <div class="w-full rounded-full bg-gray-800 transition-all duration-700"
                     style="height: {{ $progressPct }}%"></div>
            </div>

            <div class="relative z-10 space-y-8">
                @foreach($waves as $index => $wave)
                    @php
                        $startDate = $wave->start_date;
                        $hasEndDate = $wave->end_date !== null;
                        $waveStartStr = $startDate->format('Y-m-d');

                        if ($today < $waveStartStr) {
                            $status      = 'upcoming';
                            $statusLabel = 'Segera';
                            $nodeCls     = 'bg-white border-2 border-gray-300';
                            $innerCls    = 'w-2.5 h-2.5 rounded-full bg-gray-300';
                            $labelCls    = 'text-gray-400';
                            $showCheck   = false;
                        } elseif ($hasEndDate && $today > $wave->end_date->format('Y-m-d')) {
                            $status      = 'closed';
                            $statusLabel = 'Selesai';
                            $nodeCls     = 'bg-gray-800 border-2 border-gray-800';
                            $innerCls    = '';
                            $labelCls    = 'text-gray-400';
                            $showCheck   = true;
                        } else {
                            $status      = 'active';
                            $statusLabel = 'Berlangsung';
                            $nodeCls     = 'bg-white border-2 border-blue-600';
                            $innerCls    = 'w-2.5 h-2.5 rounded-full bg-blue-600';
                            $labelCls    = 'text-blue-600';
                            $showCheck   = false;
                        }

                        $formattedStart = $startDate->translatedFormat('d M');
                        $formattedStartWithYear = $startDate->translatedFormat('d M Y');
                        $formattedEnd   = $hasEndDate ? $wave->end_date->translatedFormat('d M Y') : null;
                    @endphp

                    <div class="flex items-center gap-5 group">

                        <div class="relative flex-shrink-0 flex items-center justify-center"
                             style="width: {{ $nodeSize }}px; height: {{ $nodeSize }}px;">
                            @if($status === 'active')
                                <span class="absolute rounded-full bg-blue-100 animate-ping opacity-40"
                                      style="width: 44px; height: 44px;"></span>
                            @endif
                            <div class="rounded-full {{ $nodeCls }} flex items-center justify-center shadow z-10 relative"
                                 style="width: {{ $nodeSize }}px; height: {{ $nodeSize }}px;">
                                @if($showCheck)
                                    <svg style="width:13px;height:13px;" class="text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif($innerCls)
                                    <span class="{{ $innerCls }}"></span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest {{ $labelCls }}">{{ $statusLabel }}</p>
                            <p class="text-sm font-bold text-gray-800 leading-snug mt-0.5">{{ $wave->name }}</p>
                            @if($hasEndDate)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $formattedStart }} – {{ $formattedEnd }}</p>
                            @else
                                <p class="text-xs text-gray-400 mt-0.5">{{ $formattedStartWithYear }}</p>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        </div>

        @else
            <p class="text-center py-8 text-gray-400 text-sm">Jadwal gelombang pendaftaran belum dikonfigurasi.</p>
        @endif

    </div>
</section>