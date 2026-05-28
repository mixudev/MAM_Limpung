<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $siteSettings->school_name ?? 'MAM Limpung' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js'])  --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        mono: ['DM Mono', 'monospace']
                    }
                }
            }
        }
    </script>
    <script>
        // Inline script to prevent theme FOUC (flicker)
        (function initTheme() {
            const saved = localStorage.getItem('theme');
            if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @include('dashboard.partials.style')
</head>

<body class="font-sans bg-[#f1f5f9] dark:bg-zinc-950 text-zinc-800 dark:text-zinc-200 antialiased">
    
    <!-- Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-zinc-900/50 backdrop-blur-sm z-30 hidden lg:hidden"
        onclick="toggleSidebar()"></div>

    <!-- ════════════ SIDEBAR ════════════ -->
    @include("dashboard.partials.sidebar")

    <!-- ════════════ MAIN ════════════ -->
    <div id="mainWrapper" class="main-wrapper flex flex-col min-h-screen">

        <!-- HEADER -->
        @include("dashboard.partials.header")

        <!-- CONTENT -->
        <main class="flex-1 p-5 md:p-8">

            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-xs font-mono text-zinc-400 mb-1.5">
                    <span>Dashboard</span>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    <span id="breadcrumb">Home</span>
                </div>

            </div>

            @yield('content')

        </main>

        <!-- FOOTER -->
        <footer
            class="px-8 py-4 border-t border-zinc-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex items-center justify-between flex-wrap gap-3">
            <div class="text-xs text-zinc-400 font-mono">MAM Limpung</div>
            <div class="flex items-center gap-4">
                <a href="#"
                    class="text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">Docs</a>
                <a href="#"
                    class="text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">Status</a>
                <a href="#"
                    class="text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">Support</a>
            </div>
        </footer>
    </div>

    <script>
        // ─── TOOLTIP POSITIONING (fixed position needs JS for Y coord)
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('mouseenter', function() {
                const tooltip = this.querySelector('.sidebar-tooltip');
                if (!tooltip) return;
                const rect = this.getBoundingClientRect();
                tooltip.style.top = (rect.top + rect.height / 2) + 'px';
            });
        });

        // ─── DARK MODE
        function isDark() {
            return document.documentElement.classList.contains('dark');
        }

        function toggleDark() {
            document.documentElement.classList.toggle('dark');
            document.getElementById('iconMoon').classList.toggle('hidden');
            document.getElementById('iconSun').classList.toggle('hidden');
            localStorage.setItem('theme', isDark() ? 'dark' : 'light');
            setTimeout(() => {
                if (typeof rebuildCharts === 'function') rebuildCharts();
            }, 50);
        }
        
        // Sync icon state after DOM loads since the head script already applied the class
        document.addEventListener('DOMContentLoaded', () => {
            if (isDark()) {
                const moon = document.getElementById('iconMoon');
                const sun = document.getElementById('iconSun');
                if (moon) moon.classList.add('hidden');
                if (sun) sun.classList.remove('hidden');
            }
        });

        // ─── SIDEBAR (mobile slide)
        function toggleSidebar() {
            const s = document.getElementById('sidebar'),
                o = document.getElementById('sidebarOverlay');
            const isOpen = !s.classList.contains('-translate-x-full');
            s.classList.toggle('-translate-x-full', isOpen);
            o.classList.toggle('hidden', isOpen);
        }
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) document.getElementById('sidebarOverlay').classList.add('hidden');
        });

        // ─── COLLAPSIBLE SIDEBAR (desktop)
        let sidebarCollapsed = false;

        function applyCollapseState(collapsed, animate) {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('mainWrapper');

            if (collapsed) {
                sidebar.classList.add('collapsed');
                mainWrapper.classList.add('sidebar-collapsed');
                document.body.classList.add('sidebar-is-collapsed');
            } else {
                sidebar.classList.remove('collapsed');
                mainWrapper.classList.remove('sidebar-collapsed');
                document.body.classList.remove('sidebar-is-collapsed');
            }
        }

        function toggleCollapse() {
            sidebarCollapsed = !sidebarCollapsed;
            applyCollapseState(sidebarCollapsed, true);
            localStorage.setItem('sidebarCollapsed', sidebarCollapsed ? '1' : '0');
        }



        // ─── NAV
        const pageLabels = {
            dashboard: 'Dashboard',
            applications: 'Applications',
            users: 'Users',
            roles: 'Roles',
            permissions: 'Permissions',
            logs: 'Authentication Logs',
            apikeys: 'API Keys',
            security: 'Security Settings',
            settings: 'System Settings',
            activity_logs: 'Log Aktivitas',
            other_logs: 'Lain-lain'
        };
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.classList.contains('disabled')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }

                if (this.classList.contains('dropdown-trigger')) {
                    e.preventDefault();
                    // If collapsed, expand first
                    if (sidebarCollapsed) {
                        toggleCollapse();
                    }

                    const isOpen = this.classList.contains('dropdown-open');

                    // Close other dropdowns if any (optional, keeping it simple for now)

                    if (!isOpen) {
                        this.classList.add('dropdown-open');
                    } else {
                        this.classList.remove('dropdown-open');
                    }
                    return;
                }

                const href = this.getAttribute('href');
                if (href && href !== '#') {
                    return; // Let standard page navigation run!
                }

                e.preventDefault();
                const page = this.dataset.page;
                if (page) {
                    document.getElementById('pageTitle').textContent = pageLabels[page] || page;
                    document.getElementById('breadcrumb').textContent = pageLabels[page] || page;
                    showToast('Navigasi', 'Halaman ' + (pageLabels[page] || page) + ' dimuat', 'info');
                }
                if (window.innerWidth < 1024) toggleSidebar();
            });
        });

        // ─── DROPDOWNS
        function toggleDropdown() {
            document.getElementById('profileDropdown').classList.toggle('hidden');
        }

        function toggleNotif() {
            const p = document.getElementById('notifPanel');
            p.classList.toggle('open');
            if (p.classList.contains('open')) renderNotifs();
        }
        document.addEventListener('click', e => {
            if (!document.getElementById('profileDropdownWrapper').contains(e.target)) document.getElementById(
                'profileDropdown').classList.add('hidden');
            if (!document.getElementById('notifWrapper').contains(e.target)) document.getElementById('notifPanel')
                .classList.remove('open');
            if (!document.getElementById('searchInput').contains(e.target)) document.getElementById('searchResults')
                .classList.add('hidden');
        });


    </script>
    <x-allert />
</body>

</html>
