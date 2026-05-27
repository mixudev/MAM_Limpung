@php
    $activeOverlays = app(\App\Services\Announcement\AnnouncementService::class)->getActiveItems();
    
    // Page state
    $isHome = Route::is('frontend.home');
    $isPpdb = Request::is('ppdb*') || Route::is('frontend.ppdb.*');
    
    // Filter items based on target page
    $filterTarget = function($item) use ($isHome, $isPpdb) {
        $target = $item['target_page'] ?? 'all_pages';
        if ($target === 'all_pages') return true;
        
        // Backward compatibility
        if ($target === 'home_only' && $isHome) return true;
        if ($target === 'ppdb_only' && $isPpdb) return true;
        
        // Match specific front-end route targeting
        if ($target === 'frontend.home' && $isHome) return true;
        if ($target === 'frontend.ppdb.index' && $isPpdb) return true;
        if ($target === 'frontend.article.index' && (Route::is('frontend.article.*') || Request::is('artikel*'))) return true;
        if ($target === 'frontend.jurusan' && Route::is('frontend.jurusan')) return true;
        if ($target === 'frontend.kurikulum' && Route::is('frontend.kurikulum')) return true;
        if ($target === 'frontend.ekstrakurikuler' && Route::is('frontend.ekstrakurikuler')) return true;
        if ($target === 'frontend.prestasi' && Route::is('frontend.prestasi')) return true;
        if ($target === 'frontend.galeri' && Route::is('frontend.galeri')) return true;
        if ($target === 'frontend.profile' && Route::is('frontend.profile')) return true;
        if ($target === 'frontend.contact' && Route::is('frontend.contact')) return true;
        
        return false;
    };

    // Take only the first (newest) matching alert to prevent clashing
    $popupAlerts = $activeOverlays->where('type', 'popup_alert')->filter($filterTarget)->take(1);
    $bannerAds = $activeOverlays->where('type', 'banner_ads')->filter($filterTarget);
@endphp

<!-- 2. Popup Alert (Modal Dialog dengan Image Slider) -->
@if($popupAlerts->isNotEmpty())
    @foreach($popupAlerts as $popup)
        @php
            $modalSize = $popup['popup_size'] ?? 'md';
            $sizeClass = match($modalSize) {
                'sm' => 'max-w-md',
                'lg' => 'max-w-2xl',
                'xl' => 'max-w-4xl',
                default => 'max-w-lg', // md
            };
            $frequency = $popup['display_frequency'] ?? 'once_per_session';
        @endphp
        <div x-data="{ 
                showPopup: false,
                id: 'popup_{{ $popup['id'] }}',
                frequency: '{{ $frequency }}',
                init() {
                    const dismissed = this.frequency === 'once_per_session' 
                        ? sessionStorage.getItem(this.id) 
                        : null;
                    
                    if (!dismissed) {
                        setTimeout(() => { this.showPopup = true; }, 1000);
                    }
                },
                dismiss() {
                    this.showPopup = false;
                    if (this.frequency === 'once_per_session') {
                        sessionStorage.setItem(this.id, 'true');
                    }
                }
             }" 
             x-show="showPopup"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
             
             <!-- Modal Container -->
             <div class="bg-white  w-full {{ $sizeClass }} border border-slate-200  rounded-none shadow-2xl overflow-hidden flex flex-col transform transition-all"
                  @click.away="dismiss()"
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="scale-95"
                  x-transition:enter-end="scale-100">
                  
                  <!-- Close Button Header -->
                  <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 ">
                      <h3 class="text-sm font-bold text-slate-800  uppercase tracking-wider font-mono">{{ $popup['title'] }}</h3>
                      <button @click="dismiss()" class="text-slate-400 hover:text-slate-600  transition-colors">
                          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                      </button>
                  </div>

                  <!-- Modal Content -->
                  <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
                      <!-- Image Slider (Carousel) -->
                      @if(!empty($popup['images']))
                          <div x-data="{ activeSlide: 0, slidesCount: {{ count($popup['images']) }} }" class="relative w-full border border-slate-200  overflow-hidden bg-slate-50 ">
                              <!-- Slides Container -->
                              <div class="relative aspect-video w-full overflow-hidden">
                                  @foreach($popup['images'] as $index => $imgUrl)
                                      <div x-show="activeSlide === {{ $index }}" 
                                           x-transition:enter="transition ease-out duration-300 transform"
                                           x-transition:enter-start="opacity-0 scale-95"
                                           x-transition:enter-end="opacity-100 scale-100"
                                           x-transition:leave="transition ease-in duration-300 transform absolute inset-0"
                                           x-transition:leave-start="opacity-100 scale-100"
                                           x-transition:leave-end="opacity-0 scale-95"
                                           class="w-full h-full">
                                          <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
                                      </div>
                                  @endforeach
                              </div>

                              <!-- Prev/Next Buttons -->
                              <template x-if="slidesCount > 1">
                                  <div>
                                      <button @click="activeSlide = (activeSlide === 0) ? slidesCount - 1 : activeSlide - 1" class="absolute left-2 top-1/2 -translate-y-1/2 p-1.5 bg-black/60 hover:bg-black/85 text-white rounded-none transition-colors focus:outline-none">
                                          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                          </svg>
                                      </button>
                                      <button @click="activeSlide = (activeSlide === slidesCount - 1) ? 0 : activeSlide + 1" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-black/60 hover:bg-black/85 text-white rounded-none transition-colors focus:outline-none">
                                          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                          </svg>
                                      </button>
                                  </div>
                              </template>

                              <!-- Dot Indicators -->
                              <template x-if="slidesCount > 1">
                                  <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
                                      <template x-for="i in slidesCount" :key="i">
                                          <button @click="activeSlide = i - 1" 
                                              :class="activeSlide === i - 1 ? 'bg-white w-4' : 'bg-white/50 hover:bg-white/80 w-1.5'"
                                              class="h-1.5 transition-all duration-300 focus:outline-none"></button>
                                      </template>
                                  </div>
                              </template>
                          </div>
                      @endif
                      
                      @if($popup['content'])
                          <p class="text-sm text-slate-600  leading-relaxed font-sans">{!! nl2br(e($popup['content'])) !!}</p>
                      @endif
                  </div>

                  <!-- Modal Footer -->
                  <div class="px-6 py-4 border-t border-slate-100  flex justify-end gap-3 bg-slate-50/50 /50">
                      <button @click="dismiss()" class="px-4 py-2 text-xs font-bold font-mono text-slate-500 hover:text-slate-700   transition-all rounded-none uppercase">
                          Tutup
                      </button>
                      @if($popup['action_url'])
                          <a href="{{ $popup['action_url'] }}" target="_blank" class="px-4 py-2 text-xs font-bold font-mono bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white transition-all rounded-none uppercase tracking-wider text-center">
                              {{ $popup['action_text'] ?: 'Lihat Detail' }}
                          </a>
                      @endif
                  </div>
             </div>
        </div>
    @endforeach
@endif
