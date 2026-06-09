<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider font-mono">Popup Alert (Modal Dialog)</h3>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Buat popup promosi atau informasi penting yang muncul di tengah layar ketika halaman diakses.</p>
        </div>
        <a href="{{ route('admin.announcements.alerts.create') }}" class="py-2 px-3 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider flex items-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Popup Alert
        </a>
    </div>

    <!-- Alert Items List (Left Image, Right Detail) -->
    <div class="space-y-6">
        @forelse($popupAlerts as $alert)
            <div class="flex flex-col md:flex-row border border-slate-200 dark:border-zinc-800 p-5 hover:border-slate-300 dark:hover:border-zinc-700 transition-colors gap-6 bg-slate-50/20 dark:bg-zinc-900/30">
                <!-- Left: Image Thumbnails / Mini Slider -->
                <div class="w-full md:w-64 shrink-0">
                    @if(is_array($alert->image) && count($alert->image) > 0)
                        <div class="relative w-full aspect-video border border-slate-200 dark:border-zinc-800 overflow-hidden bg-slate-100 dark:bg-zinc-950 flex items-center justify-center">
                            <!-- Display the first image -->
                            <img src="{{ asset('storage/' . $alert->image[0]) }}" class="w-full h-full object-cover">
                            @if(count($alert->image) > 1)
                                <div class="absolute bottom-2 right-2 bg-black/70 text-white text-[9px] font-mono font-bold px-2 py-0.5 uppercase tracking-wider">
                                    +{{ count($alert->image) - 1 }} Gambar Lain
                                </div>
                            @endif
                        </div>
                        <!-- Extra thumbnails list -->
                        <div class="flex gap-1.5 mt-2 overflow-x-auto py-1">
                            @foreach($alert->image as $index => $imgPath)
                                <div class="w-10 h-10 border border-slate-200 dark:border-zinc-800 overflow-hidden shrink-0 bg-white">
                                    <img src="{{ asset('storage/' . $imgPath) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="w-full aspect-video border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 dark:text-zinc-500 text-xs italic font-mono">
                            Tanpa Gambar Banner
                        </div>
                    @endif
                </div>

                <!-- Right: Details -->
                <div class="flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h4 class="text-sm font-bold text-slate-800 dark:text-zinc-200">{{ $alert->title }}</h4>
                            <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 border border-slate-200/50 dark:border-zinc-700/50">
                                Size: {{ strtoupper($alert->popup_size) }}
                            </span>
                            <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 border border-slate-200/50 dark:border-zinc-700/50">
                                Freq: {{ str_replace('_', ' ', $alert->display_frequency) }}
                            </span>
                            <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 border border-slate-200/50 dark:border-zinc-700/50">
                                Target: {{ str_replace('_', ' ', $alert->target_page) }}
                            </span>
                        </div>
                        @if($alert->content)
                            <p class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed font-sans line-clamp-3 italic">
                                "{!! nl2br(e($alert->content)) !!}"
                            </p>
                        @endif

                        @if($alert->action_url)
                            <div class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono">
                                <strong>URL Tombol:</strong> <a href="{{ $alert->action_url }}" target="_blank" class="text-[#4f45b2] hover:underline">{{ $alert->action_url }}</a>
                                ({{ $alert->action_text ?: 'Lihat Detail' }})
                            </div>
                        @endif

                        @if($alert->start_date || $alert->end_date)
                            <div class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono">
                                <strong>Periode Tayang:</strong> 
                                {{ $alert->start_date ? $alert->start_date->format('d/m/Y H:i') : 'Kapan saja' }} s.d. 
                                {{ $alert->end_date ? $alert->end_date->format('d/m/Y H:i') : 'Selamanya' }}
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between border-t border-slate-100 dark:border-zinc-800 pt-3">
                        <form action="{{ route('admin.announcements.alerts.toggle-active', $alert) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-xs font-mono font-bold focus:outline-none">
                                @if($alert->is_active)
                                    <span class="px-2.5 py-1 text-[9px] font-bold font-mono tracking-wider uppercase bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[9px] font-bold font-mono tracking-wider uppercase bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/40">
                                        Non-Aktif
                                    </span>
                                @endif
                                <span class="text-[10px] text-slate-400 hover:text-slate-600 dark:hover:text-zinc-300">Klik untuk ubah status</span>
                            </button>
                        </form>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.announcements.alerts.edit', $alert) }}" class="py-1.5 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider">
                                Edit Popup
                            </a>
                            <form action="{{ route('admin.announcements.alerts.destroy', $alert) }}" method="POST" class="inline" id="delete-form-{{ $alert->id }}">
                                @csrf
                                @method('DELETE')
                                <button 
                                type="button" 
                                onclick="AppPopup.confirm({
                                                    title: 'Peringatan',
                                                    description: 'Apakah Anda yakin ingin menghapus popup alert ini beserta semua gambarnya?',
                                                    confirmText: 'Ya, Hapus',
                                                    cancelText: 'Batal',
                                                    onConfirm: function() {
                                                        document.getElementById('delete-form-{{ $alert->id }}').submit();
                                                    }
                                                })" 
                                class="py-1.5 px-3 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] uppercase font-mono tracking-wider">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-slate-400 dark:text-zinc-500 italic border border-dashed border-slate-200 dark:border-zinc-800">
                Belum ada popup alert modal yang dibuat.
            </div>
        @endforelse
    </div>
</div>
