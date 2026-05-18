@extends('layouts.app')

@section('content')
<div class="bg-[#f4f6f8] min-h-screen pt-12 pb-0 font-sans" x-data="peminatanData()">
    
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="relative bg-slate-900 overflow-hidden border border-slate-800 p-10 md:p-16 flex flex-col md:flex-row items-center justify-between">
            <!-- Background Image & Overlay -->
            <img src="{{ asset('assets/img/school.png') }}" alt="Jurusan MAM Limpung" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/95 to-slate-900/80 z-10"></div>
            
            <div class="relative z-20 md:w-2/3">
                <div class="w-12 h-1.5 bg-amber-500 mb-6 shadow-lg shadow-amber-500/20"></div>
                <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter leading-tight mb-4 drop-shadow-md">
                    Jurusan & <span class="text-amber-500">Peminatan</span>
                </h1>
                <p class="text-blue-100 text-base md:text-lg font-medium leading-relaxed">
                    MAM Limpung memberikan wadah terbaik untuk setiap potensi siswa melalui pilihan Jurusan Akademik yang terarah dan Program Peminatan khusus untuk membekali siswa dengan <i>life skill</i> dan nilai-nilai Islami.
                </p>
            </div>
            
            
        </div>
    </div>

    <!-- 1. Jurusan Akademik (Cards Layout) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter mb-2">Jurusan Akademik Utama</h2>
            <div class="w-16 h-1 bg-blue-900 mx-auto"></div>
            <p class="text-slate-500 mt-4 max-w-2xl mx-auto text-sm">Program studi formal yang menjadi landasan akademis siswa selama belajar di madrasah untuk persiapan ke jenjang pendidikan tinggi.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- MIPA -->
            <div class="bg-white border border-slate-200 p-8 hover:shadow-xl transition-shadow duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -z-10 group-hover:scale-125 transition-transform duration-500"></div>
                <div class="w-12 h-12 bg-blue-900 text-white flex items-center justify-center mb-5 shadow-md">
                    <i class="fa-solid fa-flask text-xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-1">MIPA</h3>
                <p class="text-[10px] font-bold text-blue-900 tracking-widest uppercase mb-4">Matematika & Ilmu Alam</p>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Program unggulan bagi siswa yang meminati sains, eksperimen, dan penalaran logis. Disiapkan untuk studi Kedokteran, Teknik, dan IT.
                </p>
            </div>

            <!-- IPS -->
            <div class="bg-white border border-slate-200 p-8 hover:shadow-xl transition-shadow duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-full -z-10 group-hover:scale-125 transition-transform duration-500"></div>
                <div class="w-12 h-12 bg-amber-500 text-white flex items-center justify-center mb-5 shadow-md">
                    <i class="fa-solid fa-users-viewfinder text-xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-1">IPS</h3>
                <p class="text-[10px] font-bold text-amber-600 tracking-widest uppercase mb-4">Ilmu Pengetahuan Sosial</p>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Berfokus pada interaksi manusia, ekonomi, dan fenomena sosial. Disiapkan untuk masuk Fakultas Hukum, Ekonomi, dan Ilmu Politik.
                </p>
            </div>

            <!-- KEAGAMAAN -->
            <div class="bg-white border border-slate-200 p-8 hover:shadow-xl transition-shadow duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -z-10 group-hover:scale-125 transition-transform duration-500"></div>
                <div class="w-12 h-12 bg-emerald-600 text-white flex items-center justify-center mb-5 shadow-md">
                    <i class="fa-solid fa-book-quran text-xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-1">AGAMA</h3>
                <p class="text-[10px] font-bold text-emerald-600 tracking-widest uppercase mb-4">Ilmu Keagamaan Islam</p>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Mendalami literatur Islam, tafsir, fiqih, dan Bahasa Arab secara intensif. Mencetak ulama, da'i, dan cendekiawan Muslim masa depan.
                </p>
            </div>
        </div>
    </div>

    <!-- 2. Program Peminatan (Sidebar Layout Interaktif) -->
    <div class="bg-white border-t border-slate-200 py-20 relative">
        <!-- subtle pattern bg -->
        <div class="absolute inset-0 bg-slate-50 opacity-50" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tighter mb-2">Program Peminatan & Skill Khusus</h2>
                <div class="w-16 h-1 bg-amber-500 mx-auto"></div>
                <p class="text-slate-500 mt-4 max-w-2xl mx-auto text-sm">Selain jurusan akademik, siswa dapat memilih kelas peminatan sesuai dengan hobi, <i>passion</i>, dan bakat mereka guna menunjang keterampilan abad ke-21.</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- Sidebar Navigation -->
                <div class="lg:w-1/3 flex flex-col gap-3">
                    <template x-for="(item, index) in peminatanList" :key="index">
                        <button @click="activeTab = index" 
                            class="text-left w-full p-4 transition-all duration-300 border flex items-center justify-between group"
                            :class="activeTab === index ? 'bg-blue-900 border-blue-900 text-white shadow-xl' : 'bg-white border-slate-200 hover:border-blue-300 text-slate-700'">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 flex items-center justify-center transition-colors shadow-sm"
                                    :class="activeTab === index ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-50 group-hover:text-blue-600'">
                                    <i :class="item.icon" class="text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold uppercase text-sm tracking-wide" x-text="item.shortName"></h3>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs transition-transform"
                               :class="activeTab === index ? 'translate-x-1 opacity-100' : 'opacity-0 group-hover:opacity-50'"></i>
                        </button>
                    </template>
                </div>

                <!-- Content Area -->
                <div class="lg:w-2/3">
                    <div class="bg-white border border-slate-200 p-8 md:p-12 min-h-[450px] shadow-lg relative overflow-hidden transition-all duration-500">
                        
                        <!-- Decorative background icon -->
                        <div class="absolute top-0 right-0 w-64 h-64 opacity-5 transition-colors duration-500"
                            :class="peminatanList[activeTab].colorClass">
                            <i :class="peminatanList[activeTab].icon" class="text-[200px] absolute -top-10 -right-10"></i>
                        </div>

                        <div class="relative z-10" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            
                            <div class="inline-flex items-center space-x-2 px-3 py-1 bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-6">
                                <i class="fa-solid fa-star text-amber-500"></i>
                                <span>Detail Peminatan</span>
                            </div>
                            
                            <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tighter mb-4" x-text="peminatanList[activeTab].title"></h2>
                            <p class="text-slate-600 font-medium leading-relaxed mb-10 text-sm md:text-base" x-text="peminatanList[activeTab].description"></p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Target / Kompetensi -->
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4 flex items-center border-b border-slate-100 pb-2">
                                        <i class="fa-solid fa-bullseye mr-2 text-blue-600"></i> Kompetensi Khusus
                                    </h4>
                                    <ul class="space-y-3">
                                        <template x-for="skill in peminatanList[activeTab].skills" :key="skill">
                                            <li class="flex items-start">
                                                <i class="fa-solid fa-check text-[10px] mt-1.5 mr-3 text-emerald-500"></i>
                                                <span class="text-sm font-medium text-slate-700" x-text="skill"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>

                                <!-- Output Kegiatan -->
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4 flex items-center border-b border-slate-100 pb-2">
                                        <i class="fa-solid fa-trophy mr-2 text-amber-500"></i> Output Siswa
                                    </h4>
                                    <ul class="space-y-3">
                                        <template x-for="output in peminatanList[activeTab].outputs" :key="output">
                                            <li class="flex items-start">
                                                <i class="fa-solid fa-arrow-right text-[10px] mt-1.5 mr-3 text-amber-500"></i>
                                                <span class="text-sm font-medium text-slate-700" x-text="output"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('peminatanData', () => ({
            activeTab: 0,
            peminatanList: [
                {
                    shortName: 'Tahfidz Qur\'an',
                    title: 'Kelas Peminatan Tahfidz',
                    icon: 'fa-solid fa-book-open-reader',
                    colorClass: 'text-emerald-600',
                    description: 'Program khusus bagi siswa yang ingin fokus menghafal dan memahami Al-Qur\'an. Didampingi oleh ustadz/ustadzah tersertifikasi dengan metode hafalan yang sistematis dan terukur.',
                    skills: [
                        'Hafalan minimal 3 Juz',
                        'Tahsin dan Tajwid yang benar',
                        'Pemahaman dasar tafsir ayat',
                        'Irama Murattal'
                    ],
                    outputs: [
                        'Sertifikasi Tahfidz tahunan',
                        'Mampu menjadi Imam Sholat',
                        'Siap mengikuti MTQ',
                        'Prioritas beasiswa jalur Tahfidz'
                    ]
                },
                {
                    shortName: 'Karya Ilmiah Remaja (KIR)',
                    title: 'Peminatan Riset & Sains',
                    icon: 'fa-solid fa-microscope',
                    colorClass: 'text-blue-600',
                    description: 'Wadah bagi siswa yang memiliki nalar analitis tinggi untuk meneliti isu-isu saintifik, sosial, dan teknologi. Siswa diajarkan menyusun karya tulis ilmiah dari dasar hingga publikasi.',
                    skills: [
                        'Metodologi Penelitian Dasar',
                        'Penulisan Jurnal Ilmiah',
                        'Eksperimen Laboratorium',
                        'Public Speaking & Presentasi'
                    ],
                    outputs: [
                        'Makalah Ilmiah per semester',
                        'Partisipasi di Olimpiade Sains (OSN)',
                        'Lomba Karya Tulis Ilmiah Nasional',
                        'Proyek Inovasi Terapan'
                    ]
                },
                {
                    shortName: 'Desain & Multimedia',
                    title: 'Peminatan IT & Desain Grafis',
                    icon: 'fa-solid fa-laptop-code',
                    colorClass: 'text-purple-600',
                    description: 'Mempersiapkan siswa menghadapi era digital dengan membekali keterampilan teknologi informasi, desain grafis, editing video, dan dasar-dasar pemrograman web.',
                    skills: [
                        'Penguasaan Software Desain',
                        'Video Editing & Broadcasting',
                        'Digital Marketing & Social Media',
                        'Web & App Design Basics'
                    ],
                    outputs: [
                        'Portofolio Desain Grafis',
                        'Konten Kreatif untuk Madrasah',
                        'Kompetensi Desainer Freelance',
                        'Sertifikat Pelatihan IT'
                    ]
                },
                {
                    shortName: 'Kewirausahaan / Vokasi',
                    title: 'Peminatan Keterampilan & Bisnis',
                    icon: 'fa-solid fa-store',
                    colorClass: 'text-amber-500',
                    description: 'Program life skill yang melatih jiwa entrepreneurship siswa. Melalui program ini, siswa dapat langsung mempraktikkan cara membuat produk, memanajemen keuangan, hingga memasarkan produk.',
                    skills: [
                        'Manajemen Bisnis Dasar',
                        'Tata Boga / Tata Busana',
                        'Manajemen Keuangan Usaha',
                        'Strategi Pemasaran Digital'
                    ],
                    outputs: [
                        'Bazaar Madrasah tahunan',
                        'Mampu memproduksi barang komersil',
                        'Merintis usaha mikro skala pelajar',
                        'Jiwa kemandirian finansial'
                    ]
                }
            ]
        }));
    });
</script>
@endsection
