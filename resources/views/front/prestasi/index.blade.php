@extends('layouts.app')

@section('content')
<div x-data="prestasiData()" class="bg-[#f4f6f8] min-h-screen pt-12 pb-20 font-sans relative">
    
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="relative bg-slate-900 overflow-hidden border border-slate-800">
            <!-- Background Image -->
            <img src="{{ asset('assets/img/school.png') }}" alt="Prestasi" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-40">
            <!-- Gradient -->
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-slate-900/50 z-10"></div>
            
            <div class="relative z-20 p-10 md:p-16 lg:p-20">
                <div class="w-12 h-1.5 bg-amber-500 mb-6 shadow-lg shadow-amber-500/20"></div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white uppercase tracking-tighter leading-tight mb-4 drop-shadow-md">
                    Panggung <span class="text-amber-500">Kehormatan</span>
                </h1>
                <p class="text-blue-100 text-lg md:text-xl font-medium max-w-2xl leading-relaxed">
                    Setiap peluh adalah doa, setiap juara adalah bukti nyata. Inilah rekam jejak dedikasi dan semangat pantang menyerah siswa-siswi MAM Limpung dalam mengukir sejarah.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content: Filters & Table -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-slate-200 shadow-sm overflow-hidden">
            
            <!-- Toolbar / Filters -->
            <div class="p-6 border-b border-slate-200 bg-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                <!-- Search -->
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input x-model="searchQuery" type="text" placeholder="Cari nama siswa atau prestasi..." 
                        class="block w-full pl-11 pr-4 py-3 border border-slate-300 focus:outline-none focus:border-blue-900 focus:ring-1 focus:ring-blue-900 bg-white transition-colors text-sm font-medium">
                </div>

                <!-- Dropdowns -->
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <!-- Kategori -->
                    <div class="relative w-full sm:w-48">
                        <select x-model="selectedCategory" class="appearance-none block w-full pl-4 pr-10 py-3 border border-slate-300 focus:outline-none focus:border-blue-900 bg-white text-sm font-medium cursor-pointer rounded-none">
                            <template x-for="category in categories" :key="category">
                                <option x-text="category"></option>
                            </template>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <!-- Tingkat -->
                    <div class="relative w-full sm:w-48">
                        <select x-model="selectedLevel" class="appearance-none block w-full pl-4 pr-10 py-3 border border-slate-300 focus:outline-none focus:border-blue-900 bg-white text-sm font-medium cursor-pointer rounded-none">
                            <template x-for="level in levels" :key="level">
                                <option x-text="level"></option>
                            </template>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-blue-900 text-white text-xs uppercase tracking-widest">
                            <th class="px-6 py-4 font-bold border-b border-blue-800">Tanggal</th>
                            <th class="px-6 py-4 font-bold border-b border-blue-800">Siswa / Tim</th>
                            <th class="px-6 py-4 font-bold border-b border-blue-800">Prestasi</th>
                            <th class="px-6 py-4 font-bold border-b border-blue-800">Tingkat</th>
                            <th class="px-6 py-4 font-bold border-b border-blue-800">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-100">
                        <template x-for="item in filteredAchievements" :key="item.title + item.student">
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-5 whitespace-nowrap text-slate-500 font-mono text-xs" x-text="item.date"></td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <!-- Thumbnail Foto -->
                                        <div class="w-12 h-8 shrink-0 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-800 overflow-hidden flex items-center justify-center shadow-sm">
                                            <template x-if="item.foto">
                                                <img :src="item.foto" class="w-full h-full object-cover cursor-zoom-in hover:scale-105 transition-transform" @click="lightboxImg = item.foto; showLightbox = true">
                                            </template>
                                            <template x-if="!item.foto">
                                                <svg class="w-4 h-4 text-slate-350" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </template>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 group-hover:text-blue-900 transition-colors" x-text="item.student"></div>
                                            <div class="text-[10px] uppercase tracking-widest text-slate-400 mt-1" x-text="item.category"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-bold text-amber-600 flex items-center">
                                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                        <span x-text="item.title"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex px-3 py-1 text-[10px] font-bold uppercase tracking-widest border"
                                        :class="{
                                            'bg-purple-50 text-purple-700 border-purple-200': item.level === 'Internasional',
                                            'bg-blue-50 text-blue-700 border-blue-200': item.level === 'Nasional',
                                            'bg-slate-100 text-slate-700 border-slate-200': item.level === 'Provinsi',
                                            'bg-emerald-50 text-emerald-700 border-emerald-200': item.level === 'Kabupaten/Kota' || item.level === 'Kabupaten',
                                            'bg-slate-50 text-slate-505 border-slate-200': item.level === 'Sekolah'
                                        }" x-text="item.level">
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-slate-505 max-w-xs truncate" :title="item.description" x-text="item.description"></td>
                            </tr>
                        </template>
                        
                        <!-- Empty State -->
                        <tr x-show="filteredAchievements.length === 0" style="display: none;">
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-100 mb-4 border border-slate-200">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Data Tidak Ditemukan</h3>
                                <p class="text-sm text-slate-505">Coba gunakan kata kunci atau filter lain.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Footer -->
            <div class="p-4 bg-slate-50 border-t border-slate-200 text-xs font-bold text-slate-505 text-center uppercase tracking-widest">
                Menampilkan <span class="text-slate-900" x-text="filteredAchievements.length"></span> Prestasi
            </div>

        </div>
    </div>

    <!-- Lightbox Modal -->
    <div x-show="showLightbox" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 animate-fadeIn" x-transition.opacity style="display: none;" @click="showLightbox = false">
        <div class="relative max-w-4xl max-h-[85vh] bg-white p-2 border border-white/20 shadow-2xl" @click.stop>
            <button class="absolute -top-10 -right-2 text-white hover:text-gray-300 font-bold text-3xl drop-shadow-md z-50" @click="showLightbox = false">
                &times;
            </button>
            <img :src="lightboxImg" class="max-w-full max-h-[80vh] object-contain">
        </div>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('prestasiData', () => ({
            searchQuery: '',
            selectedCategory: 'Semua',
            selectedLevel: 'Semua',
            showLightbox: false,
            lightboxImg: '',
            
            achievements: [
                @foreach($prestasis as $pres)
                {
                    date: {!! json_encode($pres->tanggal_prestasi?->translatedFormat('d F Y') ?? (string) $pres->tahun) !!},
                    student: {!! json_encode($pres->peraih) !!},
                    title: {!! json_encode(($pres->juara ? $pres->juara . ' - ' : '') . $pres->judul) !!},
                    level: {!! json_encode($pres->tingkatLabel()) !!},
                    category: {!! json_encode($pres->jenis === 'akademik' ? 'Akademik' : 'Non-Akademik') !!},
                    description: {!! json_encode(strip_tags($pres->deskripsi ?? '')) !!},
                    foto: {!! json_encode($pres->foto ? asset('storage/' . $pres->foto) : '') !!}
                },
                @endforeach
            ],

            get categories() {
                return ['Semua', ...new Set(this.achievements.map(a => a.category))];
            },

            get levels() {
                return ['Semua', ...new Set(this.achievements.map(a => a.level))];
            },

            get filteredAchievements() {
                return this.achievements.filter(a => {
                    const matchesSearch = a.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                          a.student.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                          a.description.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const matchesCategory = this.selectedCategory === 'Semua' || a.category === this.selectedCategory;
                    const matchesLevel = this.selectedLevel === 'Semua' || a.level === this.selectedLevel;
                    return matchesSearch && matchesCategory && matchesLevel;
                });
            }
        }));
    });
</script>
@endsection