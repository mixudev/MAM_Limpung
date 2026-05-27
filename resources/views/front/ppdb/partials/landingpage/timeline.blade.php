        <section id="alur-pendaftaran" class="ppdb-section scroll-mt-[130px] py-16 bg-gradient-to-b from-gray-50 to-white overflow-hidden">
            <div class="max-w-6xl mx-auto px-6">
                <!-- Title -->
                <div class="text-center max-w-2xl mx-auto mb-16 fade-up-init">
                    <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-blue-200/50">Timeline PPDB</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mt-3 leading-tight tracking-tight">Peta Jalan Pendaftaran</h2>
                    <p class="text-gray-500 mt-2 text-xs md:text-sm leading-relaxed">Ikuti tahapan seleksi PPDB MAS Muhammadiyah Limpung dengan mudah, terencana, dan transparan.</p>
                </div>

                <!-- Roadmap Content Wrapper -->
                <div class="relative mt-8">
                    
                    <!-- Vertical Track Line (Centered on Desktop, Left on Mobile) -->
                    <div class="absolute left-8 md:left-1/2 top-4 bottom-4 -translate-x-1/2 w-[3px] bg-gradient-to-b from-blue-500 via-emerald-400 to-transparent z-0 rounded-full"></div>

                    <!-- Steps -->
                    <div class="space-y-12 relative z-10">
                        
                        @forelse($waves as $index => $wave)
                            @php
                                $isEven = $index % 2 == 0;
                                $number = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                                
                                $today = date('Y-m-d');
                                $startDate = \Carbon\Carbon::parse($wave['start_date']);
                                $endDate = \Carbon\Carbon::parse($wave['end_date']);
                                
                                if ($today < $wave['start_date']) {
                                    $status = 'upcoming';
                                    $statusLabel = 'Akan Datang';
                                    $statusColor = 'amber';
                                    $statusBg = 'bg-amber-50 text-amber-700 border-amber-200/50';
                                    $statusDot = 'bg-amber-500';
                                } elseif ($today > $wave['end_date']) {
                                    $status = 'closed';
                                    $statusLabel = 'Selesai';
                                    $statusColor = 'gray';
                                    $statusBg = 'bg-gray-100 text-gray-600 border-gray-200';
                                    $statusDot = 'bg-gray-400';
                                } else {
                                    $status = 'active';
                                    $statusLabel = 'Pendaftaran Aktif';
                                    $statusColor = 'emerald';
                                    $statusBg = 'bg-emerald-50 text-emerald-700 border-emerald-200/50';
                                    $statusDot = 'bg-emerald-500';
                                }

                                $formattedStart = $startDate->translatedFormat('d M Y');
                                $formattedEnd = $endDate->translatedFormat('d M Y');
                            @endphp
                            
                            <!-- Dynamic Wave Step -->
                            <div class="flex flex-row {{ $isEven ? 'md:flex-row' : 'md:flex-row-reverse' }} items-center justify-between relative pl-16 md:pl-0 group">
                                
                                <!-- Card -->
                                <div class="w-full md:w-[45%] z-20 fade-{{ $isEven ? 'left' : 'right' }}-init">
                                    <div class="relative bg-white p-6 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group">
                                        
                                        <!-- Status Indicator Left Accent -->
                                        <div class="absolute top-0 left-0 w-1.5 h-full {{ $status === 'active' ? 'bg-emerald-500' : ($status === 'closed' ? 'bg-gray-300' : 'bg-amber-400') }}"></div>
                                        
                                        <div class="pl-2">
                                            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                                                <!-- Date Badge -->
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-gray-700 bg-gray-50 border border-gray-100">
                                                    <i class="fa-solid fa-calendar-days text-gray-400"></i>
                                                    {{ $formattedStart }} - {{ $formattedEnd }}
                                                </span>
                                                
                                                <!-- Status Pill -->
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusBg }} uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }} {{ $status === 'active' ? 'animate-pulse' : '' }}"></span>
                                                    {{ $statusLabel }}
                                                </span>
                                            </div>
                                            
                                            <h3 class="text-lg font-bold text-gray-900 mt-2 flex items-center gap-2 group-hover:text-blue-600 transition-colors">
                                                {{ $wave['name'] }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Center Checkpoint Node -->
                                <div id="node-{{ $index + 1 }}" class="absolute left-8 md:left-1/2 -translate-x-1/2 w-8 h-8 rounded-full border-4 border-white shadow-md flex items-center justify-center z-30 transition-all duration-300
                                    {{ $status === 'active' ? 'bg-emerald-500 text-white ring-4 ring-emerald-500/20' : ($status === 'closed' ? 'bg-gray-400 text-white' : 'bg-amber-400 text-white') }}">
                                    @if ($status === 'closed')
                                        <i class="fa-solid fa-check text-xs"></i>
                                    @elseif ($status === 'active')
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                                        </span>
                                    @else
                                        <i class="fa-solid fa-clock text-[10px]"></i>
                                    @endif
                                </div>
                                
                                <!-- Spacer / Info Panel on Desktop -->
                                <div class="hidden md:flex w-[45%] {{ $isEven ? 'justify-start pl-12' : 'justify-end pr-12' }} items-center">
                                    <div class="flex flex-col {{ $isEven ? 'items-start' : 'items-end' }}">
                                        <div class="text-4xl font-extrabold text-gray-200 select-none transition-colors duration-300 group-hover:text-gray-300">{{ $number }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Tahap Pendaftaran</div>
                                        @if ($status === 'active')
                                            <span class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800 animate-pulse uppercase tracking-wider border border-emerald-200/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Berlangsung
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 text-sm">Jadwal gelombang pendaftaran belum ditentukan oleh Admin.</div>
                        @endforelse

                    </div>
                </div>
            </div>
        </section>