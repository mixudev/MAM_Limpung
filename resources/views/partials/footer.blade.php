 <!-- Footer -->
    <footer class="bg-gradient-to-br from-blue-900 to-blue-800 text-white mt-12">
        <!-- Main Footer Content -->
        <div class="max-w-6xl mx-auto px-5 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- School Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        @if(!empty($siteSettings->logo_path))
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center p-1 overflow-hidden">
                                <img src="{{ asset('storage/' . $siteSettings->logo_path) }}" class="w-full h-full object-contain" alt="Logo {{ $siteSettings->school_name ?? 'MAM Limpung' }}">
                            </div>
                        @else
                            <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-xl text-white"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-2xl font-bold">
                                <span class="text-amber-400">{{ $siteSettings->school_name ?? 'MAM Limpung' }}</span>
                            </h3>
                            <p class="text-blue-200 text-sm">Madrasah Aliyah Muhammadiyah</p>
                        </div>
                    </div>
                    <p class="text-blue-100 mb-6 leading-relaxed">
                        {{ $siteSettings->about_short ?: 'MAM Limpung adalah lembaga pendidikan Islam yang berkomitmen untuk membentuk generasi yang berakhlak mulia, cerdas, dan siap menghadapi tantangan masa depan dengan landasan nilai-nilai Islam.' }}
                    </p>
                    
                    <!-- Contact Info -->
                    <div class="space-y-3">
                        @if($siteSettings->address)
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-amber-400 mt-1"></i>
                            <div>
                                <p class="text-blue-100 text-sm">
                                    {{ $siteSettings->address }}
                                </p>
                            </div>
                        </div>
                        @endif
                        @if($siteSettings->phone)
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-amber-400"></i>
                            <p class="text-blue-100 text-sm">{{ $siteSettings->phone }}</p>
                        </div>
                        @endif
                        @if($siteSettings->email)
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-amber-400"></i>
                            <p class="text-blue-100 text-sm">{{ $siteSettings->email }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-xl font-semibold mb-6 text-amber-400">Menu Utama</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('frontend.home') }}" class="text-blue-100 hover:text-amber-400 transition-colors text-sm flex items-center space-x-2"><i class="fas fa-chevron-right text-xs"></i><span>Beranda</span></a></li>
                        <li><a href="{{ route('frontend.profile') }}" class="text-blue-100 hover:text-amber-400 transition-colors text-sm flex items-center space-x-2"><i class="fas fa-chevron-right text-xs"></i><span>Profil Sekolah</span></a></li>
                        <li><a href="{{ route('frontend.jurusan') }}" class="text-blue-100 hover:text-amber-400 transition-colors text-sm flex items-center space-x-2"><i class="fas fa-chevron-right text-xs"></i><span>Program Akademik</span></a></li>
                        <li><a href="{{ route('frontend.kurikulum') }}" class="text-blue-100 hover:text-amber-400 transition-colors text-sm flex items-center space-x-2"><i class="fas fa-chevron-right text-xs"></i><span>Kurikulum & Fasilitas</span></a></li>
                        <li><a href="{{ route('frontend.article.index') }}" class="text-blue-100 hover:text-amber-400 transition-colors text-sm flex items-center space-x-2"><i class="fas fa-chevron-right text-xs"></i><span>Berita & Artikel</span></a></li>
                        <li><a href="{{ route('frontend.galeri') }}" class="text-blue-100 hover:text-amber-400 transition-colors text-sm flex items-center space-x-2"><i class="fas fa-chevron-right text-xs"></i><span>Galeri</span></a></li>
                        <li><a href="{{ route('frontend.contact') }}" class="text-blue-100 hover:text-amber-400 transition-colors text-sm flex items-center space-x-2"><i class="fas fa-chevron-right text-xs"></i><span>Kontak</span></a></li>
                    </ul>
                </div>

                <!-- Academic & Services -->
                <div>
                    <h4 class="text-xl font-semibold mb-6 text-amber-400">Layanan</h4>
                    <ul class="space-y-3 mb-6">
                        <li><a href="{{ route('frontend.ppdb.index') }}" class="text-blue-100 hover:text-amber-400 transition-colors text-sm flex items-center space-x-2"><i class="fas fa-chevron-right text-xs"></i><span>PPDB Online</span></a></li>
                        <li><a href="{{ route('login') }}" class="text-blue-100 hover:text-amber-400 transition-colors text-sm flex items-center space-x-2"><i class="fas fa-chevron-right text-xs"></i><span>Portal Siswa (Login)</span></a></li>
                        <li><a href="{{ route('frontend.ppdb.status') }}" class="text-blue-100 hover:text-amber-400 transition-colors text-sm flex items-center space-x-2"><i class="fas fa-chevron-right text-xs"></i><span>Cek Status PPDB</span></a></li>
                    </ul>

                    <!-- Social Media -->
                    <div>
                        <h5 class="font-semibold mb-4 text-amber-400">Ikuti Kami</h5>
                        <div class="flex space-x-3">
                            @if($siteSettings->facebook_url)
                            <a href="{{ $siteSettings->facebook_url }}" target="_blank" class="w-10 h-10 bg-blue-700 hover:bg-amber-500 rounded-full flex items-center justify-center transition-colors group">
                                <i class="fab fa-facebook text-white group-hover:scale-110 transition-transform"></i>
                            </a>
                            @endif
                            @if($siteSettings->instagram_url)
                            <a href="{{ $siteSettings->instagram_url }}" target="_blank" class="w-10 h-10 bg-blue-700 hover:bg-amber-500 rounded-full flex items-center justify-center transition-colors group">
                                <i class="fab fa-instagram text-white group-hover:scale-110 transition-transform"></i>
                            </a>
                            @endif
                            @if($siteSettings->youtube_url)
                            <a href="{{ $siteSettings->youtube_url }}" target="_blank" class="w-10 h-10 bg-blue-700 hover:bg-amber-500 rounded-full flex items-center justify-center transition-colors group">
                                <i class="fab fa-youtube text-white group-hover:scale-110 transition-transform"></i>
                            </a>
                            @endif
                            @if($siteSettings->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings->whatsapp) }}" target="_blank" class="w-10 h-10 bg-blue-700 hover:bg-amber-500 rounded-full flex items-center justify-center transition-colors group">
                                <i class="fab fa-whatsapp text-white group-hover:scale-110 transition-transform"></i>
                            </a>
                            @endif
                            @if($siteSettings->twitter_url)
                            <a href="{{ $siteSettings->twitter_url }}" target="_blank" class="w-10 h-10 bg-blue-700 hover:bg-amber-500 rounded-full flex items-center justify-center transition-colors group">
                                <i class="fa-brands fa-x-twitter text-white group-hover:scale-110 transition-transform"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="border-t border-blue-700 pt-8 mt-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <h4 class="text-xl font-semibold mb-3 text-amber-400">
                            <i class="fas fa-newspaper mr-2"></i>
                            Newsletter Sekolah
                        </h4>
                        <p class="text-blue-100 text-sm">
                            Dapatkan informasi terbaru tentang kegiatan sekolah, pengumuman penting, dan berita pendidikan langsung di email Anda.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input 
                            type="email" 
                            placeholder="Masukkan email Anda"
                            class="flex-1 px-4 py-3 rounded-lg bg-blue-800 border border-blue-600 text-white placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                        >
                        <button class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors whitespace-nowrap">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Berlangganan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="bg-blue-950 border-t border-blue-800">
            <div class="max-w-6xl mx-auto px-5 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="text-center md:text-left">
                        <p class="text-blue-200 text-sm">
                            &copy; {{ date('Y') }} {{ $siteSettings->school_name }}. Hak Cipta Dilindungi Undang-Undang.
                        </p>
                        <p class="text-blue-300 text-xs mt-1">
                            Dikembangkan oleh Tim IT {{ $siteSettings->school_name }}
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap gap-6 text-blue-200 text-sm">
                        <a href="#" class="hover:text-amber-400 transition-colors">Kebijakan Privasi</a>
                        <a href="#" class="hover:text-amber-400 transition-colors">Syarat & Ketentuan</a>
                        <a href="/login" class="hover:text-amber-400 transition-colors">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

