<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm flex flex-col space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider font-mono">Iklan Banner & Seksi Promosi</h3>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Buat iklan banner untuk ditempatkan secara dinamis di berbagai bagian halaman website menggunakan komponen Blade.</p>
        </div>
        <a href="{{ route('admin.announcements.ads.create') }}" class="py-2 px-3 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider flex items-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Iklan Banner
        </a>
    </div>

    <!-- Ad Items List (Left Image, Right Detail) -->
    <div class="space-y-6">
        @forelse($bannerAds as $ad)
            <div class="flex flex-col md:flex-row border border-slate-200 dark:border-zinc-800 p-5 hover:border-slate-300 dark:hover:border-zinc-700 transition-colors gap-6 bg-slate-50/20 dark:bg-zinc-900/30">
                <!-- Left: Image Preview -->
                <div class="w-full md:w-56 flex-shrink-0">
                    @if($ad->image)
                        <div class="relative w-full aspect-video border border-slate-200 dark:border-zinc-800 overflow-hidden bg-slate-100 dark:bg-zinc-950 flex items-center justify-center">
                            <img src="{{ asset('storage/' . $ad->image) }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-full aspect-video border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-800 flex items-center justify-center text-slate-400 dark:text-zinc-500 text-xs italic font-mono">
                            Tanpa Gambar Iklan
                        </div>
                    @endif
                </div>

                <!-- Right: Details -->
                <div class="flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h4 class="text-sm font-bold text-slate-800 dark:text-zinc-200">{{ $ad->title }}</h4>
                        </div>
                        
                        @if($ad->description)
                            <p class="text-xs text-slate-500 dark:text-zinc-400 italic">"{{ $ad->description }}"</p>
                        @endif

                        @if($ad->action_url)
                            <div class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono">
                                <strong>Tautan URL Promosi:</strong> <a href="{{ $ad->action_url }}" target="_blank" class="text-[#4f45b2] hover:underline">{{ $ad->action_url }}</a>
                                ({{ $ad->action_text ?: 'Kunjungi' }})
                            </div>
                        @endif

                        @if($ad->start_date || $ad->end_date)
                            <div class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono">
                                <strong>Periode Tayang:</strong> 
                                {{ $ad->start_date ? $ad->start_date->format('d/m/Y H:i') : 'Kapan saja' }} s.d. 
                                {{ $ad->end_date ? $ad->end_date->format('d/m/Y H:i') : 'Selamanya' }}
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between border-t border-slate-100 dark:border-zinc-800 pt-3">
                        <form action="{{ route('admin.announcements.ads.toggle-active', $ad) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-xs font-mono font-bold focus:outline-none">
                                @if($ad->is_active)
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
                            <a href="{{ route('admin.announcements.ads.edit', $ad) }}" class="py-1.5 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider">
                                Edit Iklan
                            </a>
                            <form action="{{ route('admin.announcements.ads.destroy', $ad) }}" method="POST" class="inline" id="delete-form-{{ $ad->id }}">
                                @csrf
                                @method('DELETE')
                                <button 
                                type="button" 
                                onclick="AppPopup.confirm({
                                                    title: 'Peringatan',
                                                    description: 'Apakah Anda yakin ingin menghapus banner iklan ini?',
                                                    confirmText: 'Ya, Hapus',
                                                    cancelText: 'Batal',
                                                    onConfirm: function() {
                                                        document.getElementById('delete-form-{{ $ad->id }}').submit();
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
                Belum ada iklan banner melayang yang dibuat.
            </div>
        @endforelse
    </div>
</div>
