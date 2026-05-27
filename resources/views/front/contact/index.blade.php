@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen pt-12 pb-0 font-sans overflow-x-hidden">
    
    <!-- Hero Section -->
    <section class="py-20 bg-white relative overflow-hidden border-b border-gray-100">
        <!-- Decorative Background Blur -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-50/50 rounded-full blur-[100px] opacity-70 -z-10 translate-x-1/3 -translate-y-1/3"></div>
        
        <div class="container mx-auto px-6 relative z-10 text-center max-w-4xl">
            <span class="text-blue-700 font-bold text-[10px] tracking-[0.4em] uppercase mb-6 block" data-aos="fade-down">
                Hubungi Kami
            </span>
            <h1 class="text-5xl md:text-7xl font-black text-gray-900 leading-[1.1] tracking-tighter mb-8 uppercase" data-aos="fade-up">
                KONTAK <br/>
                <span class="text-blue-700">SEKOLAH.</span>
            </h1>
            <p class="text-lg text-gray-500 mb-0 leading-relaxed font-regular max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Ada pertanyaan mengenai pendaftaran PPDB, program akademis, atau ingin berkunjung ke sekolah kami? Silakan hubungi kami melalui saluran resmi di bawah ini.
            </p>
        </div>
    </section>

    <!-- Core Contact Info: 3 Column Premium Grid -->
    <section class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-0 bg-white shadow-2xl border border-gray-200 rounded-none overflow-hidden">
                
                <!-- Telepon & WA Card -->
                <div class="group border-b md:border-b-0 md:border-r border-gray-100 py-16 px-10 hover:bg-blue-50 transition-colors duration-500 cursor-default" data-aos="fade-up" data-aos-delay="0">
                    <div class="mb-10 text-blue-900/20 group-hover:text-blue-600 transition-colors duration-500">
                        <i class="fa-solid fa-phone text-5xl"></i>
                    </div>
                    <span class="text-blue-600 font-bold text-[10px] tracking-[0.3em] uppercase mb-4 block">Respons Cepat</span>
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter mb-6 leading-none">TELEPON & WA</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8 font-medium">
                        Hubungi nomor telepon kantor sekolah atau nomor layanan WhatsApp Center PPDB kami untuk mendapatkan tanggapan langsung.
                    </p>
                    <div class="space-y-3 mb-8">
                        <a href="tel:+62285123456" class="block text-sm font-bold text-gray-800 hover:text-blue-900 transition-colors">
                            <i class="fa-solid fa-phone text-[10px] mr-2 text-blue-600"></i> {{ $siteSettings->phone ?? '' }}
                        </a>
                        <a href="https://wa.me/{{ $siteSettings->whatsapp ?? '' }}" target="_blank" class="block text-sm font-bold text-gray-800 hover:text-blue-900 transition-colors">
                            <i class="fa-brands fa-whatsapp text-[12px] mr-2 text-emerald-500 font-bold"></i>+ {{ preg_replace('/^(\d{2})(\d{3})(\d{4})(\d{4})$/', '$1 $2 $3 $4', $siteSettings->whatsapp ?? '') }}
                        </a> 
                    </div>
                    <a href="https://wa.me/{{ $siteSettings->whatsapp ?? '' }}" target="_blank" class="inline-flex items-center gap-3 bg-blue-900 text-white px-6 py-3 font-semibold hover:bg-black transition-colors uppercase tracking-widest text-[10px] rounded-none">
                        Hubungi WhatsApp <i class="fa-brands fa-whatsapp text-xs"></i>
                    </a>
                </div>

                <!-- Email Card -->
                <div class="group border-b md:border-b-0 md:border-r border-gray-100 py-16 px-10 hover:bg-emerald-50 transition-colors duration-500 cursor-default" data-aos="fade-up" data-aos-delay="100">
                    <div class="mb-10 text-emerald-900/20 group-hover:text-emerald-600 transition-colors duration-500">
                        <i class="fa-solid fa-envelope text-5xl"></i>
                    </div>
                    <span class="text-emerald-600 font-bold text-[10px] tracking-[0.3em] uppercase mb-4 block">Persuratan Resmi</span>
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter mb-6 leading-none">EMAIL RESMI</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8 font-medium">
                        Kirimkan surat resmi kedinasan, berkas administrasi, proposal kerjasama akademis, atau pertanyaan umum ke email resmi kami.
                    </p>
                    <div class="space-y-3 mb-8">
                        <a href="mailto:{{ $siteSettings->email ?? '' }}" class="block text-sm font-bold text-gray-800 hover:text-emerald-700 transition-colors">
                            <i class="fa-solid fa-envelope-open text-[10px] mr-2 text-emerald-600"></i> {{ $siteSettings->email ?? '' }}
                        </a>
                        <!-- <a href="mailto:{{ $siteSettings->email ?? '' }}" class="block text-sm font-bold text-gray-800 hover:text-emerald-700 transition-colors">
                            <i class="fa-solid fa-inbox text-[10px] mr-2 text-emerald-600"></i> {{ $siteSettings->email ?? '' }}
                        </a> -->
                    </div>
                    <a href="mailto:{{ $siteSettings->email ?? '' }}" class="inline-flex items-center gap-3 bg-emerald-700 text-white px-6 py-3 font-semibold hover:bg-emerald-900 transition-colors uppercase tracking-widest text-[10px] rounded-none">
                        Kirim Email <i class="fa-solid fa-paper-plane text-xs"></i>
                    </a>
                </div>

                <!-- Jam Operasional Card -->
                <div class="group py-16 px-10 hover:bg-amber-50 transition-colors duration-500 cursor-default" data-aos="fade-up" data-aos-delay="200">
                    <div class="mb-10 text-amber-900/20 group-hover:text-amber-600 transition-colors duration-500">
                        <i class="fa-solid fa-clock text-5xl"></i>
                    </div>
                    <span class="text-amber-600 font-bold text-[10px] tracking-[0.3em] uppercase mb-4 block">Jam Kerja</span>
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter mb-6 leading-none">OPERASIONAL</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8 font-medium">
                        Jam operasional pelayanan administrasi kantor sekretariat sekolah dan penerimaan kunjungan wali murid/tamu.
                    </p>
                    <div class="space-y-4 mb-8 text-sm font-bold text-gray-800">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="font-bold uppercase tracking-wider text-xs">Senin - Kamis</span>
                            <span class="text-amber-700">07:00 - 14:00 WIB</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="font-bold uppercase tracking-wider text-xs">Jumat</span>
                            <span class="text-amber-700">07:00 - 11:00 WIB</span>
                        </div>
                        <div class="flex justify-between pb-2">
                            <span class="font-bold uppercase tracking-wider text-xs">Sabtu</span>
                            <span class="text-amber-700">07:00 - 13:00 WIB</span>
                        </div>
                    </div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        *Hari Ahad dan Hari Besar Nasional Libur
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Map & Social Media: Split Layout -->
    <section class="py-24 bg-white border-t border-gray-100">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-stretch">
                
                <!-- Sisi Kiri: Alamat & Media Sosial -->
                <div class="flex flex-col justify-between" data-aos="fade-right">
                    <div> 
                        <span class="text-blue-700 font-bold text-[10px] tracking-[0.4em] uppercase mb-6 block border-l-4 border-blue-700 pl-3">
                            Lokasi Kami
                        </span>
                        <h2 class="text-4xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter mb-8 leading-none">SEKOLAH <br/>MAM LIMPUNG.</h2>
                        
                        <div class="bg-gray-50 p-8 border-l-4 border-blue-900 shadow-sm mb-10">
                            <h4 class="font-bold text-stone-900 uppercase tracking-wider text-sm mb-3">Alamat Resmi Sekolah</h4>
                            <p class="text-gray-600 font-medium leading-relaxed mb-4 text-base">
                                {{ $siteSettings->address ?? 'Jl. Cokronegoro No.34, Gepor, Limpung, Kec. Limpung, Kabupaten Batang, Jawa Tengah 51271' }}
                            </p>
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">
                                {{ $siteSettings->school_name ?? 'MAM Limpung'}} — NPSN: 20364936
                            </span>
                        </div>
                    </div>

                    <!-- Media Sosial Grid -->
                    <div>
                        <h4 class="font-bold text-stone-900 uppercase tracking-wider text-xs mb-6">Media Sosial Resmi</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <!-- Instagram -->
                            <a href="{{ $siteSettings->instagram_url ?? '#' }}" target="_blank" class="group bg-white p-5 text-center border border-gray-200 hover:border-blue-900 hover:shadow-xl transition-all">
                                <i class="fa-brands fa-instagram text-2xl text-gray-300 group-hover:text-pink-600 transition-colors mb-3 block"></i>
                                <span class="text-[9px] font-bold text-gray-800 uppercase tracking-widest block">Instagram</span>
                            </a>
                            <!-- YouTube -->
                            <a href="{{ $siteSettings->youtube_url ?? '#' }}" target="_blank" class="group bg-white p-5 text-center border border-gray-200 hover:border-blue-900 hover:shadow-xl transition-all">
                                <i class="fa-brands fa-youtube text-2xl text-gray-300 group-hover:text-red-600 transition-colors mb-3 block"></i>
                                <span class="text-[9px] font-bold text-gray-800 uppercase tracking-widest block">YouTube</span>
                            </a>
                            <!-- Tikktok -->
                            <a href="{{ $siteSettings->tiktok_url ?? '#' }}" target="_blank" class="group bg-white p-5 text-center border border-gray-200 hover:border-blue-900 hover:shadow-xl transition-all">
                                <i class="fa-brands fa-tiktok text-2xl text-gray-300 group-hover:text-blue-700 transition-colors mb-3 block"></i>
                                <span class="text-[9px] font-bold text-gray-800 uppercase tracking-widest block">TikTok</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Embedded Maps -->
                <div class="relative min-h-[450px] shadow-2xl border border-gray-200 group overflow-hidden" data-aos="fade-left">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.923915235983!2d109.91647967363026!3d-7.0182295687473255!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e701514018d6bed%3A0x4fc0ab1092d70606!2sMadrasah%20Aliyah%20Muhammadiyah%20Limpung!5e0!3m2!1sen!2sid!4v1779090722448!5m2!1sen!2sid" 
                        class="w-full h-full border-0 absolute inset-0 grayscale contrast-110 group-hover:grayscale-0 transition-all duration-1000" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>

                </div>

            </div>
        </div>
    </section>

</div>
@endsection
