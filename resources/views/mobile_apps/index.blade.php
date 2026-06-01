@extends('mobile_apps.layouts.apps')

@section('content')
            {{-- ── QUICK ACCESS MENU ─────────────────────────── --}}
        <section class="reveal px-5 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-sora font-semibold text-slate-800 text-sm">Akses Cepat</h3>
                {{-- <button class="text-xs text-primary-600 font-semibold">Lihat Semua</button> --}}
            </div>
            <div class="grid grid-cols-4 gap-3">
                <!-- Jadwal -->
                <button id="qa-jadwal" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Jadwal</span>
                </button>
                <!-- Nilai -->
                <button id="qa-nilai" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Nilai</span>
                </button>
                <!-- PPDB -->
                <button id="qa-ppdb" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">PPDB</span>
                </button>
                <!-- Galeri -->
                <button id="qa-galeri" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Galeri</span>
                </button>
                <!-- Artikel -->
                <button id="qa-artikel" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Artikel</span>
                </button>
                <!-- Prestasi -->
                <button id="qa-prestasi" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="6"/>
                            <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Prestasi</span>
                </button>
                <!-- Ekskul -->
                <button id="qa-ekskul" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Ekskul</span>
                </button>
                <!-- Kontak -->
                <button id="qa-kontak" class="flex flex-col items-center gap-2 group">
                    <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-600 font-medium leading-tight text-center">Hubungi</span>
                </button>
            </div>
        </section>

        {{-- ── KEPALA SEKOLAH ────────────────────────────── --}}
        <section class="reveal px-5 mt-7 mb-4">
            <div class="bg-linear-to-br from-primary-700 via-primary-800 to-primary-900 rounded-2xl p-5 relative overflow-hidden">
                <!-- Deco -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full translate-x-10 -translate-y-10"></div>
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-14 h-14 bg-white/15 rounded-2xl flex items-center justify-center shrink-0 border border-white/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="7" r="4"/>
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white/60 text-[10px] uppercase tracking-widest font-semibold">Kepala Sekolah</p>
                        <h4 class="font-sora font-bold text-white text-sm mt-0.5">Drs. H. Ahmad Fauzi, M.Pd.</h4>
                        <p class="text-amber-400 text-[11px] font-medium mt-0.5">"Berilmu, Berkarakter, dan Berprestasi"</p>
                    </div>
                </div>
                <p class="relative z-10 text-white/60 text-[11px] mt-3 leading-relaxed">
                    MAM Limpung berkomitmen mencetak generasi unggul yang beriman, berilmu, dan berakhlak mulia untuk kemajuan bangsa.
                </p>
            </div>
        </section>
@endsection