
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">

    <!-- Top Bar -->
    <div class="bg-blue-900 text-white hidden lg:block mt-4">
        <div class="flex flex-col sm:flex-row justify-between items-center text-sm space-y-2 sm:space-y-0">
            <!-- Social Links -->
            <div class="flex items-center justify-end gap-3 px-5 bg-amber-500 h-full py-2 w-[30%] rounded-none">
                <p class="flex items-center space-x-1 text-xs">
                    Follow Link : 
                </p>
                <a href="#" class="hover:text-blue-200 transition-colors flex items-center space-x-1">
                    <i class="fa-brands fa-facebook"></i>
                </a>
                <a href="#" class="hover:text-blue-200 transition-colors flex items-center space-x-1">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="#" class="hover:text-blue-200 transition-colors flex items-center space-x-1">
                    <i class="fa-brands fa-youtube"></i>
                </a>
            </div>
            
            <!-- Contact Info -->
            <div class="flex flex-row items-center justify-center text-center w-[70%] gap-10 px-5 py-2">
                <span class="flex items-center">
                    <i class="fa-solid fa-location-dot mr-2"></i>
                    <span class="text-xs">Jl. Cokronegoro No.34, Gepor, Limpung</span>
                </span>
                <span class="flex items-center">
                    <i class="fa-solid fa-envelope mr-2"></i>
                    <span class="text-xs">info@mamlimpung.sch</span>
                </span>
                <span class="flex items-center ">
                    <i class="fa-solid fa-phone mr-2"></i>
                    <span class="text-xs">+62 21 1234 5678</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-35 transition-all duration-300" id="navbar">
        <div class="max-w-6xl mx-auto px-5">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('assets/img/logo.png') }}" class="w-12 h-12 object-contain" alt="Logo MAM Limpung">
                    <div class="flex flex-col">
                        <h1 class="text-xl font-bold leading-tight tracking-tight">
                            <span class="text-blue-900">MAM</span>
                            <span class="text-gray-800">Limpung</span>
                        </h1>
                        <p class="text-[10px] text-gray-600 font-light uppercase tracking-widest -mt-0.5">
                            Unggul dan Berprestasi
                        </p>
                    </div>
                </div>

                <!-- Desktop Menu -->
                @include('partials.navbar.desktop-nav')

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button class="hamburger-btn text-gray-700 hover:text-blue-600 focus:outline-none transition-colors" id="mobileMenuBtn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path class="hamburger-line line-1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16"></path>
                            <path class="hamburger-line line-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16"></path>
                            <path class="hamburger-line line-3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- News Ticker (Only on Home Page) -->
    @if(request()->is('/'))
    <div class="bg-gray-100 border-b border-gray-200 overflow-hidden h-10 flex items-center">
        <div class="container mx-auto px-5 flex items-center">
            <!-- Label -->
            <div class="bg-blue-900 text-white px-2 lg:px-4 py-2 lg:py-1 text-[10px] font-bold tracking-widest whitespace-nowrap z-10 flex items-center">
                <i class="fa-solid fa-bullhorn lg:mr-2 text-[8px]"></i> <span class="hidden lg:block">INFO TERKINI</span>
            </div>
            
            <!-- Ticker Content -->
            <div class="relative flex-1 overflow-hidden h-full flex items-center ml-4">
                <div class="animate-ticker whitespace-nowrap flex items-center">
                    <span class="text-xs text-gray-600 font-medium mx-6"> Penerimaan Peserta Didik Baru (PPDB) Tahun Pelajaran 2025/2026 telah dibuka. Daftar Segera!</span>
                    <span class="text-gray-300">|</span>
                    <span class="text-xs text-gray-600 font-medium mx-6"> Selamat kepada Tim Robotik MAM Limpung atas Juara 1 Tingkat Provinsi.</span>
                    <span class="text-gray-300">|</span>
                    <span class="text-xs text-gray-600 font-medium mx-6"> Ujian Akhir Semester Genap akan dilaksanakan mulai tanggal 2 Juni 2025.</span>
                    <span class="text-gray-300">|</span>
                    <!-- Duplicate for seamless loop -->
                    <span class="text-xs text-gray-600 font-medium mx-6"> Penerimaan Peserta Didik Baru (PPDB) Tahun Pelajaran 2025/2026 telah dibuka. Daftar Segera!</span>
                    <span class="text-gray-300">|</span>
                    <span class="text-xs text-gray-600 font-medium mx-6"> Selamat kepada Tim Robotik MAM Limpung atas Juara 1 Tingkat Provinsi.</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Mobile Sidebar Backdrop -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-38" id="sidebarBackdrop"></div>

    <!-- Mobile Sidebar Menu -->
    <div class="fixed top-0 left-0 h-full w-72 bg-white shadow-2xl hidden z-40 flex flex-col transition-all" id="mobileSidebar">

        @include('partials.navbar.mobile-nav')

    </div>

    <script>
        // Get elements
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const hamburgerBtn = document.querySelector('.hamburger-btn svg');
        const dropdownBtns = document.querySelectorAll('.dropdown-btn');

        // Open sidebar
        mobileMenuBtn.addEventListener('click', () => {
            mobileSidebar.classList.remove('hidden');
            sidebarBackdrop.classList.remove('hidden');
            mobileSidebar.classList.add('sidebar-enter');
            sidebarBackdrop.classList.add('backdrop-enter');
            hamburgerBtn.parentElement.classList.add('hamburger-active');
        });

        // Close sidebar
        const closeSidebar = () => {
            mobileSidebar.classList.add('sidebar-exit');
            sidebarBackdrop.classList.add('backdrop-exit');
            hamburgerBtn.parentElement.classList.remove('hamburger-active');
            
            setTimeout(() => {
                mobileSidebar.classList.add('hidden');
                sidebarBackdrop.classList.add('hidden');
                mobileSidebar.classList.remove('sidebar-enter', 'sidebar-exit');
                sidebarBackdrop.classList.remove('backdrop-enter', 'backdrop-exit');
            }, 300);
        };

        closeSidebarBtn.addEventListener('click', closeSidebar);
        sidebarBackdrop.addEventListener('click', closeSidebar);

        // Dropdown toggle
        dropdownBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const dropdownId = btn.getAttribute('data-dropdown');
                const dropdown = document.getElementById(dropdownId);
                const icon = btn.querySelector('.dropdown-icon');
                
                dropdown.classList.toggle('open');
                icon.classList.toggle('rotate');
            });
        });

        // Close sidebar when clicking on a link
        document.querySelectorAll('#sidebarContent a').forEach(link => {
            link.addEventListener('click', () => {
                closeSidebar();
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-white/95', 'backdrop-blur-md');
            } else {
                navbar.classList.remove('bg-white/95', 'backdrop-blur-md');
            }
        });
    </script>