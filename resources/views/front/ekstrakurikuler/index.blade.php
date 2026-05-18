@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen pt-8 pb-0 font-sans" x-data="ekstraData()">
    
    <!-- Hero Section -->
    <section class="py-20 relative overflow-hidden border-b border-gray-100">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <span class="inline-block py-1 px-3 border border-gray-200 bg-gray-50 text-gray-500 font-bold text-[10px] tracking-[0.3em] uppercase mb-6" data-aos="fade-down">
                Pengembangan Diri
            </span>
            <h1 class="text-5xl md:text-7xl font-black text-gray-900 leading-[1] tracking-tighter mb-8 uppercase" data-aos="fade-up">
                BEYOND THE <br/>
                <span class="text-blue-700">CLASSROOM.</span>
            </h1>
            <p class="text-lg text-gray-500 mb-0 leading-relaxed font-regular max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Temukan passion-mu. Di MAM Limpung, kami tidak hanya mendidik di dalam kelas, tetapi juga memfasilitasi setiap siswa untuk aktif, berkreasi, dan memimpin melalui beragam ekstrakurikuler.
            </p>
        </div>
    </section>

    <!-- Organisasi Utama (Zig-Zag Layout) -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tighter mb-2 leading-none">ORGANISASI <br/>INTI MADRASAH.</h2>
                <div class="w-16 h-1 bg-blue-900 mt-6"></div>
            </div>

            <!-- IPM -->
            <div class="flex flex-col lg:flex-row gap-12 items-center mb-24" data-aos="fade-up">
                <div class="lg:w-1/2">
                    <div class="relative w-full aspect-[4/3] bg-gray-100 overflow-hidden group border border-gray-200">
                        <div class="absolute inset-0 bg-blue-900/20 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                        <img src="https://images.unsplash.com/photo-1529070538774-1843cb3265df?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700" alt="IPM">
                        
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <span class="text-blue-600 font-bold text-[10px] tracking-[0.3em] uppercase mb-4 block">Kepemimpinan Pelajar</span>
                    <h3 class="text-4xl font-black text-gray-900 uppercase tracking-tighter mb-6 leading-none">Ikatan Pelajar Muhammadiyah (IPM)</h3>
                    <p class="text-gray-500 mb-6 font-medium leading-relaxed">
                        Organisasi otonom ini adalah jantung kegiatan kesiswaan. Di sini, siswa dilatih menjadi organisatoris yang tangguh, pemikir yang kritis, dan pemimpin yang berintegritas.
                    </p>
                    {{-- <div class="bg-gray-50 p-6 border-l-4 border-blue-600 shadow-sm">
                        <h4 class="font-bold text-gray-900 text-sm uppercase tracking-wider mb-3">Apa yang dilakukan?</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start text-sm text-gray-600">
                                <i class="fa-solid fa-check text-blue-600 mt-1 mr-3"></i> 
                                <span><strong>Event Organizing:</strong> Mengelola acara besar madrasah seperti Class Meeting, Porseni, dan Seminar Pelajar.</span>
                            </li>
                            <li class="flex items-start text-sm text-gray-600">
                                <i class="fa-solid fa-check text-blue-600 mt-1 mr-3"></i> 
                                <span><strong>Pelatihan Kepemimpinan:</strong> Mengikuti Pelatihan Kader Dasar (Taruna Melati) untuk upgrade skill manajerial.</span>
                            </li>
                            <li class="flex items-start text-sm text-gray-600">
                                <i class="fa-solid fa-check text-blue-600 mt-1 mr-3"></i> 
                                <span><strong>Aspirasi Siswa:</strong> Menjadi perwakilan dan jembatan resmi antara siswa dan pihak sekolah.</span>
                            </li>
                        </ul>
                    </div> --}}
                </div>
            </div>

            <!-- HW -->
            <div class="flex flex-col lg:flex-row-reverse gap-12 items-center" data-aos="fade-up">
                <div class="lg:w-1/2">
                    <div class="relative w-full aspect-[4/3] bg-gray-100 overflow-hidden group border border-gray-200">
                        <div class="absolute inset-0 bg-emerald-900/20 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                        <img src="https://images.unsplash.com/photo-1523987355523-c7b5b0dd90a7?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700" alt="HW">
                        
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <span class="text-emerald-600 font-bold text-[10px] tracking-[0.3em] uppercase mb-4 block">Kepanduan Islam</span>
                    <h3 class="text-4xl font-black text-gray-900 uppercase tracking-tighter mb-6 leading-none">Gerakan Kepanduan Hizbul Wathan (HW)</h3>
                    <p class="text-gray-500 mb-6 font-medium leading-relaxed">
                        Pendidikan non-formal di luar kelas yang menitikberatkan pada keterampilan fisik, mental, dan kedisiplinan tinggi, berbasis cinta alam dan kasih sayang sesama.
                    </p>
                    {{-- <div class="bg-gray-50 p-6 border-l-4 border-emerald-600 shadow-sm">
                        <h4 class="font-bold text-gray-900 text-sm uppercase tracking-wider mb-3">Apa yang dilakukan?</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start text-sm text-gray-600">
                                <i class="fa-solid fa-check text-emerald-600 mt-1 mr-3"></i> 
                                <span><strong>Survival & Kemah:</strong> Pelatihan bertahan hidup di alam bebas, navigasi kompas, dan mendirikan tenda.</span>
                            </li>
                            <li class="flex items-start text-sm text-gray-600">
                                <i class="fa-solid fa-check text-emerald-600 mt-1 mr-3"></i> 
                                <span><strong>Ketangkasan Fisik:</strong> Baris-berbaris (PBB), tali-temali (pionering), dan sandi kepanduan.</span>
                            </li>
                            <li class="flex items-start text-sm text-gray-600">
                                <i class="fa-solid fa-check text-emerald-600 mt-1 mr-3"></i> 
                                <span><strong>Bakti Sosial:</strong> Kegiatan peduli lingkungan, penanaman pohon, dan siaga kebencanaan.</span>
                            </li>
                        </ul>
                    </div> --}}
                </div>
            </div>

        </div>
    </section>

    <!-- Interactive Extracurricular Catalog -->
    <section class="py-24 bg-gray-900 text-white overflow-hidden border-t-8 border-gray-800">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 border-b border-gray-800 pb-10">
                <div class="max-w-2xl text-left" data-aos="fade-right">
                    <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter mb-4 leading-none">EKSPLORASI <br/>EKSTRAKURIKULER.</h2>
                    <p class="text-gray-400 font-medium leading-relaxed">Pilih ekskul yang sesuai dengan minat dan bakatmu. Klik pada daftar di bawah ini untuk melihat detail kegiatannya.</p>
                </div>
                <div class="hidden md:block" data-aos="fade-left">
                    <div class="w-24 h-1 bg-amber-500"></div>
                </div>
            </div>

            <!-- Layout: Sidebar Menu (Left) + Content (Right) -->
            <div class="flex flex-col lg:flex-row gap-0 border border-gray-800 shadow-2xl" data-aos="fade-up">
                
                <!-- Sidebar Nav -->
                <div class="lg:w-1/3 bg-gray-800/30 flex flex-col border-b lg:border-b-0 lg:border-r border-gray-800">
                    <template x-for="(item, index) in ekskulList" :key="index">
                        <button @click="activeTab = index" 
                            class="text-left px-8 py-6 flex items-center justify-between border-b border-gray-800/50 transition-all duration-300 group"
                            :class="activeTab === index ? 'bg-amber-500 text-gray-900 shadow-xl z-10' : 'hover:bg-gray-800 text-gray-400 hover:text-white'">
                            <div class="flex items-center space-x-4">
                                <i :class="item.icon" class="text-xl" :class="activeTab === index ? 'text-gray-900' : 'text-gray-500 group-hover:text-white'"></i>
                                <span class="font-bold uppercase tracking-widest text-sm" x-text="item.name"></span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs transition-transform" :class="activeTab === index ? 'translate-x-1' : 'opacity-0 group-hover:opacity-100 group-hover:translate-x-1'"></i>
                        </button>
                    </template>
                </div>

                <!-- Main Display area -->
                <div class="lg:w-2/3 relative min-h-[500px] overflow-hidden bg-gray-900">
                    <!-- Dynamic Background Image -->
                    <img :src="ekskulList[activeTab].image" class="absolute inset-0 w-full h-full object-cover opacity-20 grayscale mix-blend-luminosity transition-all duration-1000 scale-105" alt="Background">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/80 to-transparent"></div>

                    <!-- Dynamic Content -->
                    <div class="relative z-10 p-10 md:p-16 h-full flex flex-col justify-end"
                         x-transition:enter="transition ease-out duration-500" 
                         x-transition:enter-start="opacity-0 translate-y-8" 
                         x-transition:enter-end="opacity-100 translate-y-0">
                        
                        <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-widest mb-6 border border-white/20 w-max">
                            <i :class="ekskulList[activeTab].icon"></i>
                            <span x-text="ekskulList[activeTab].category"></span>
                        </div>

                        <h3 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter mb-4 leading-none" x-text="ekskulList[activeTab].name"></h3>
                        <p class="text-gray-300 text-lg leading-relaxed mb-8 max-w-xl" x-text="ekskulList[activeTab].desc"></p>

                        <!-- Activities Grid -->
                        <div class="bg-gray-900/80 backdrop-blur-md border border-gray-700 p-6 md:p-8">
                            <h4 class="text-xs font-bold text-amber-500 uppercase tracking-widest mb-4 border-b border-gray-700 pb-3">Daftar Kegiatan Rutin:</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <template x-for="activity in ekskulList[activeTab].activities" :key="activity">
                                    <div class="flex items-start">
                                        <i class="fa-solid fa-arrow-turn-up rotate-90 text-gray-500 mt-1 mr-3 text-xs"></i>
                                        <span class="text-sm text-gray-300 font-medium" x-text="activity"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ekstraData', () => ({
            activeTab: 0,
            ekskulList: [
                {
                    name: 'Tapak Suci',
                    category: 'Bela Diri & Fisik',
                    icon: 'fa-solid fa-hand-fist',
                    image: 'https://images.unsplash.com/photo-1555597673-b21d5c935865?q=80&w=2070&auto=format&fit=crop',
                    desc: 'Perguruan seni bela diri Indonesia pencak silat yang melatih kekuatan fisik, jurus-jurus ketangkasan, serta membentuk mental pantang menyerah.',
                    activities: [
                        'Latihan fisik & stamina secara berkala',
                        'Pematangan Jurus Dasar & Ganda',
                        'Sparing & Ujian Kenaikan Sabuk',
                        'Persiapan Kejuaraan Daerah/Nasional'
                    ]
                },
                {
                    name: 'P M R',
                    category: 'Kesehatan & Sosial',
                    icon: 'fa-solid fa-kit-medical',
                    image: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070&auto=format&fit=crop',
                    desc: 'Palang Merah Remaja mendidik siswa menjadi relawan kesehatan pertama di lingkungan sekolah, menanamkan jiwa kemanusiaan yang tinggi.',
                    activities: [
                        'Pelatihan Pertolongan Pertama (PPPK)',
                        'Evakuasi Medis Dasar & Pembalutan',
                        'Pendidikan Remaja Sebaya (PRS)',
                        'Praktek Siaga UKS Harian'
                    ]
                },
                {
                    name: 'Olahraga Prestasi',
                    category: 'Atletik & Tim',
                    icon: 'fa-solid fa-volleyball',
                    image: 'https://images.unsplash.com/photo-1518659754515-560416b713bc?q=80&w=2070&auto=format&fit=crop',
                    desc: 'Wadah bagi para siswa yang memiliki hobi olahraga seperti Bola Voli, Futsal, dan Tenis Meja untuk mencetak atlet pelajar yang berprestasi.',
                    activities: [
                        'Latihan taktik & strategi permainan',
                        'Pertandingan persahabatan (Sparring)',
                        'Menggelar Turnamen Classmeeting',
                        'Latihan penguatan stamina atlet'
                    ]
                },
                {
                    name: 'English & Arabic Club',
                    category: 'Bahasa & Akademik',
                    icon: 'fa-solid fa-comments',
                    image: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070&auto=format&fit=crop',
                    desc: 'Klub bahasa yang seru untuk meningkatkan kepercayaan diri berbicara menggunakan bahasa asing. Sangat cocok bagi yang bersiap ke kampus internasional.',
                    activities: [
                        'Latihan Public Speaking & Pidato',
                        'Simulasi English Debate & Story Telling',
                        'Menulis Essay Berbahasa Asing',
                        'Bedah Film & Latihan Listening'
                    ]
                },
                {
                    name: 'Seni Islami & Rebana',
                    category: 'Seni Budaya',
                    icon: 'fa-solid fa-masks-theater',
                    image: 'https://images.unsplash.com/photo-1519671282429-b44660ead0a7?q=80&w=2069&auto=format&fit=crop',
                    desc: 'Mengekspresikan nilai-nilai keindahan Islam melalui seni musik Hadroh, Rebana, Paduan Suara, serta seni Tilawatil Qur\'an.',
                    activities: [
                        'Latihan Ketukan & Vokal Rebana',
                        'Latihan Qiroah / Tilawatil Qur\'an',
                        'Latihan Tarian Islami / Paduan Suara',
                        'Tampil live di acara besar Madrasah'
                    ]
                },
                {
                    name: 'Jurnalistik & IT',
                    category: 'Kreativitas Digital',
                    icon: 'fa-solid fa-camera',
                    image: 'https://images.unsplash.com/photo-1452421822248-d4c2b47f0c81?q=80&w=1974&auto=format&fit=crop',
                    desc: 'Klub untuk anak-anak kreatif yang suka menulis, memotret, merekam video, dan membuat konten. Menjadi ujung tombak publikasi media madrasah.',
                    activities: [
                        'Dokumentasi (Foto/Video) Acara Sekolah',
                        'Pelatihan Desain Grafis Profesional',
                        'Menulis Berita / Mengisi Mading',
                        'Pengelolaan Konten Media Sosial'
                    ]
                }
            ]
        }));
    });
</script>
@endsection
