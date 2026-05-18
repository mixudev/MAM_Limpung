@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen pt-12 pb-0 font-sans overflow-x-hidden">
    
    <!-- Hero Section -->
    <section class="py-20 bg-white relative overflow-hidden border-b border-gray-100">
        <!-- Decorative Background -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-50/50 rounded-full blur-[100px] opacity-70 -z-10 translate-x-1/3 -translate-y-1/3"></div>
        
        <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
            <span class="text-blue-700 font-bold text-[10px] tracking-[0.4em] uppercase mb-6 block" data-aos="fade-down">
                Akademik & Pengajaran
            </span>
            <h1 class="text-5xl md:text-7xl font-black text-gray-900 leading-[1.1] tracking-tighter mb-8 uppercase" data-aos="fade-up">
                KURIKULUM <br/>
                <span class="text-blue-700">INTEGRATIF.</span>
            </h1>
            <p class="text-lg text-gray-500 mb-12 leading-relaxed font-regular max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Menggabungkan keunggulan Kurikulum Nasional Merdeka Belajar dengan kedalaman keilmuan khas pesantren. Mempersiapkan generasi yang cerdas secara intelektual dan kokoh secara spiritual.
            </p>
        </div>
    </section>

    <!-- Core Philosophy: Elegant Split -->
    <section class="py-24 bg-white border-b border-gray-100">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="relative h-[500px] overflow-hidden group border border-gray-100 shadow-sm" data-aos="fade-right">
                    <div class="absolute inset-0 bg-blue-900/10 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                    <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000 grayscale group-hover:grayscale-0" alt="Filosofi Kurikulum">
                    <div class="absolute bottom-0 left-0 bg-white p-8 z-20 max-w-xs shadow-xl border-l-4 border-blue-900 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <h4 class="font-black text-2xl text-gray-900 uppercase tracking-tighter mb-2 leading-none">PENDIDIKAN <br/>BERKARAKTER</h4>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mt-2">Keseimbangan Ilmu</p>
                    </div>
                </div>
                <div data-aos="fade-left">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter leading-none mb-8">HARMONI <br/>DUNIA & AKHIRAT.</h2>
                    <p class="text-gray-500 leading-relaxed font-medium mb-6 text-lg">
                        Di MAM Limpung, kami meyakini bahwa kecerdasan akademik harus dibarengi dengan akhlak yang luhur. Oleh karena itu, seluruh mata pelajaran umum diintegrasikan dengan nilai-nilai ketauhidan.
                    </p>
                    <p class="text-gray-500 leading-relaxed font-medium mb-12">
                        Sistem pembelajaran kami dirancang berpusat pada siswa (student-centered), memberikan ruang untuk eksplorasi kritis, kolaborasi, dan pemecahan masalah nyata.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-8 border-t border-gray-100 pt-8">
                        <div>
                            <h4 class="text-4xl font-black text-blue-900 mb-2">100%</h4>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Kurikulum <br/>Merdeka</p>
                        </div>
                        <div>
                            <h4 class="text-4xl font-black text-blue-900 mb-2">24/7</h4>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Pembiasaan <br/>Karakter Islami</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Struktur Kurikulum (Sharp Cards) -->
    <section class="py-32 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-20 border-b border-gray-200 pb-10" data-aos="fade-up">
                <div class="max-w-2xl text-left">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter mb-4 leading-none">KOMPONEN UTAMA <br/>KURIKULUM.</h2>
                    <p class="text-gray-500 font-medium leading-relaxed">Tiga pilar utama pendidikan yang membentuk struktur akademis di MAM Limpung.</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-32 h-1 bg-blue-900"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-0 bg-white shadow-xl border border-gray-100">
                <!-- Kurikulum Nasional -->
                <div class="group border-b md:border-b-0 md:border-r border-gray-100 py-20 px-12 hover:bg-blue-50 transition-colors duration-500 cursor-default" data-aos="fade-up" data-aos-delay="0">
                    <div class="mb-12">
                        <i class="fa-solid fa-book text-5xl text-blue-900/20 group-hover:text-blue-600 transition-colors duration-500"></i>
                    </div>
                    <span class="text-blue-600 font-bold text-[10px] tracking-[0.3em] uppercase mb-6 block">Standar Pendidikan</span>
                    <h3 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-6 leading-none">KURIKULUM <br/>NASIONAL</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-10 font-medium">
                        Mengacu penuh pada standar Kurikulum Merdeka dari Kemdikbud dan Kemenag RI, mempersiapkan siswa untuk Ujian Masuk Perguruan Tinggi (SNBT/SNBP).
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-blue-600 text-[10px] mt-1 mr-4"></i>
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Matematika & Sains Terapan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-blue-600 text-[10px] mt-1 mr-4"></i>
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Sosial & Humaniora</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-blue-600 text-[10px] mt-1 mr-4"></i>
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Pendidikan Pancasila</span>
                        </li>
                    </ul>
                </div>

                <!-- Muatan Keislaman -->
                <div class="group border-b md:border-b-0 md:border-r border-gray-100 py-20 px-12 hover:bg-emerald-50 transition-colors duration-500 cursor-default" data-aos="fade-up" data-aos-delay="100">
                    <div class="mb-12">
                        <i class="fa-solid fa-mosque text-5xl text-emerald-900/20 group-hover:text-emerald-600 transition-colors duration-500"></i>
                    </div>
                    <span class="text-emerald-600 font-bold text-[10px] tracking-[0.3em] uppercase mb-6 block">Kekhasan Madrasah</span>
                    <h3 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-6 leading-none">MUATAN <br/>KEISLAMAN</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-10 font-medium">
                        Pendalaman literatur dan praktek keislaman untuk mencetak generasi ulama dan cendekiawan muslim yang moderat dan toleran.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-emerald-600 text-[10px] mt-1 mr-4"></i>
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Al-Qur'an & Ilmu Hadits</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-emerald-600 text-[10px] mt-1 mr-4"></i>
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Fiqih & Ushul Fiqih</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-emerald-600 text-[10px] mt-1 mr-4"></i>
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Sejarah Peradaban Islam</span>
                        </li>
                    </ul>
                </div>

                <!-- Pengembangan Diri & Bahasa -->
                <div class="group py-20 px-12 hover:bg-amber-50 transition-colors duration-500 cursor-default" data-aos="fade-up" data-aos-delay="200">
                    <div class="mb-12">
                        <i class="fa-solid fa-language text-5xl text-amber-900/20 group-hover:text-amber-600 transition-colors duration-500"></i>
                    </div>
                    <span class="text-amber-600 font-bold text-[10px] tracking-[0.3em] uppercase mb-6 block">Kompetensi Global</span>
                    <h3 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-6 leading-none">KETERAMPILAN <br/>& BAHASA</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-10 font-medium">
                        Pengembangan kecakapan abad 21 untuk membekali siswa dengan kemampuan adaptasi, kewirausahaan, dan komunikasi internasional.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-amber-600 text-[10px] mt-1 mr-4"></i>
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Bahasa Arab Intensif</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-amber-600 text-[10px] mt-1 mr-4"></i>
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Bahasa Inggris Aktif</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fa-solid fa-check text-amber-600 text-[10px] mt-1 mr-4"></i>
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Proyek Kewirausahaan</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
