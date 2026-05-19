<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MixuAuth — Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <style>
        body {
            transition: background-color 200ms, color 200ms;
        }

        #authChart {
            max-height: 220px;
        }

        .notif-panel {
            transform: translateY(-8px);
            opacity: 0;
            pointer-events: none;
            transition: transform 200ms ease, opacity 200ms ease;
        }

        .notif-panel.open {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .log-row {
            animation: fadeIn 0.35s ease forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #d4d4d8;
            border-radius: 4px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #3f3f46;
        }

        /* ── SIDEBAR LINK ── */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: 0px !important;
            font-size: 14px;
            transition: background 150ms, color 150ms;
            position: relative;
            white-space: nowrap;
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
        }

        .sidebar-link:not(.active) {
            color: #ffffff;
        }

        .dark .sidebar-link:not(.active) {
            color: #a1a1aa;
        }

        .sidebar-link:not(.active):hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .dark .sidebar-link:not(.active):hover {
            background: rgba(255, 255, 255, 0.05);
            color: #f4f4f5;
        }

        .sidebar-link.active {
            background: #8c84c8;
            color: #ffffff;
            font-weight: 700;
            border-left: 4px solid #ffffff;
            border-radius: 0px !important;
        }

        .dark .sidebar-link.active {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            font-weight: 700;
            border-left: 4px solid #3b82f6;
            border-radius: 0px !important;
        }

        /* ── DISABLED SIDEBAR LINK ── */
        .sidebar-link.disabled,
        .sidebar-link.disabled:hover {
            opacity: 0.55;
            cursor: not-allowed;
            background: transparent !important;
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .dark .sidebar-link.disabled,
        .dark .sidebar-link.disabled:hover {
            opacity: 0.4;
            background: transparent !important;
            color: rgba(161, 161, 170, 0.6) !important;
        }

        /* ── SIDEBAR WIDTH TRANSITION ── */
        #sidebar {
            transition: width 300ms ease-in-out, transform 300ms ease;
        }

        #sidebar.collapsed {
            width: 5rem;
            /* w-20 */
        }

        #sidebar:not(.collapsed) {
            width: 16rem;
            /* w-64 */
        }

        /* ── MAIN CONTENT PADDING TRANSITION ── */
        .main-wrapper {
            transition: padding-left 300ms ease-in-out;
        }

        .main-wrapper.sidebar-collapsed {
            padding-left: 5rem;
        }

        .main-wrapper:not(.sidebar-collapsed) {
            padding-left: 16rem;
        }

        /* ── ICON WRAPPER ── */
        .sidebar-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        /* ══ COLLAPSED STATE ══
       Strategy: override sidebar width, then force every link to be
       a 40×40 centered button inside the 80px wide sidebar.
       Nothing else should take horizontal space.
    */
        #sidebar.collapsed .sidebar-link {
            /* 80px sidebar - 2×8px side padding = 64px, center the 40px button */
            width: 40px !important;
            height: 40px !important;
            padding: 0 !important;
            margin: 0 auto !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0 !important;
            overflow: visible !important;
        }

        /* Kill all non-icon content inside collapsed links */
        #sidebar.collapsed .sidebar-label,
        #sidebar.collapsed .sidebar-badge,
        #sidebar.collapsed .sidebar-dot-badge {
            display: none !important;
        }

        /* Nav container: remove horizontal padding */
        #sidebar.collapsed #sidebarNav {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* Group divs: remove any padding */
        #sidebar.collapsed #sidebarNav>div {
            padding: 0 !important;
        }

        /* Section labels: hidden */
        #sidebar.collapsed .sidebar-section-title {
            display: none !important;
        }

        /* List items: full width, no extra margin */
        #sidebar.collapsed #sidebarNav li {
            width: 100%;
            display: flex;
            justify-content: center;
            margin: 0;
            padding: 2px 0;
        }

        /* ── LABELS & BADGES (expanded) ── */
        .sidebar-label,
        .sidebar-section-title,
        .sidebar-badge,
        .sidebar-dot-badge,
        .sidebar-user-info,
        .sidebar-user-chevron {
            overflow: hidden;
            white-space: nowrap;
        }

        /* ── COLLAPSED LOGO ── */
        #sidebar-logo-area {
            transition: padding 300ms ease-in-out;
        }

        #sidebar.collapsed #sidebar-logo-area {
            justify-content: center !important;
            padding: 20px 0 !important;
            gap: 0 !important;
        }

        #sidebar:not(.collapsed) #sidebar-logo-area {
            padding: 20px 16px;
        }

        #sidebar.collapsed #logo-text {
            display: none;
        }

        /* ── USER CARD IN COLLAPSED ── */
        #sidebar.collapsed #sidebar-footer-area {
            padding: 12px 0 !important;
            justify-content: center;
        }

        #sidebar:not(.collapsed) #sidebar-footer-area {
            padding: 12px;
        }

        #sidebar.collapsed #user-card {
            width: 40px !important;
            height: 40px !important;
            padding: 0 !important;
            margin: 0 auto !important;
            justify-content: center !important;
            gap: 0 !important;
        }

        #sidebar.collapsed .sidebar-user-info,
        #sidebar.collapsed .sidebar-user-chevron {
            display: none !important;
        }

        #sidebar:not(.collapsed) #user-card {
            padding: 10px 12px;
        }

        /* ── TOOLTIP ── */
        .sidebar-tooltip {
            position: fixed;
            left: 5rem;
            /* exactly at collapsed sidebar edge */
            top: auto;
            transform: translateY(-50%);
            margin-left: 8px;
            background: #1e293b;
            color: #fff;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 0px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 150ms ease;
            z-index: 9999;
            border: 1px solid #cbd5e1;
        }

        .dark .sidebar-tooltip {
            background: #27272a;
            border: 1px solid #3f3f46;
            border-radius: 0px !important;
        }

        .sidebar-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #1e293b;
        }

        .dark .sidebar-tooltip::before {
            border-right-color: #27272a;
        }

        #sidebar.collapsed .sidebar-link:hover .sidebar-tooltip {
            opacity: 1;
        }

        /* Hide tooltip when expanded */
        #sidebar:not(.collapsed) .sidebar-tooltip {
            display: none;
        }

        /* ── COLLAPSE TOGGLE BTN (desktop only) ── */
        #collapseBtn {
            display: none;
        }

        @media (min-width: 1024px) {
            #collapseBtn {
                display: flex;
            }
        }

        /* ── MOBILE: sidebar always w-64 ── */
        @media (max-width: 1023px) {
            #sidebar {
                width: 16rem !important;
            }

            .main-wrapper {
                padding-left: 0 !important;
            }
        }

        /* ── CHEVRON ICON rotation ── */
        #collapseBtnIcon {
            transition: transform 300ms ease-in-out;
        }

        body.sidebar-is-collapsed #collapseBtnIcon {
            transform: rotate(180deg);
        }

        /* ── SIDEBAR DROPDOWN ── */
        .sidebar-submenu {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 260ms cubic-bezier(.16, 1, .3, 1);
            padding-left: 20px;
            margin-left: 14px;
            border-left: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .dark .sidebar-submenu {
            border-left-color: #27272a;
        }

        .sidebar-link.dropdown-open+.sidebar-submenu {
            grid-template-rows: 1fr;
            margin-top: 4px;
            margin-bottom: 8px;
        }

        .sidebar-submenu-inner {
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 2px;
            /* Advanced entry animation for the container */
            opacity: 0;
            transform: translateY(-4px) scaleY(0.98);
            transform-origin: top;
            transition: opacity 220ms ease, transform 220ms cubic-bezier(.16, 1, .3, 1);
        }

        .sidebar-link.dropdown-open+.sidebar-submenu .sidebar-submenu-inner {
            opacity: 1;
            transform: translateY(0) scaleY(1);
        }

        .dropdown-chevron {
            transition: transform 200ms cubic-bezier(.16, 1, .3, 1);
        }

        .sidebar-link.dropdown-open .dropdown-chevron {
            transform: rotate(180deg);
        }

        /* Parent open state */
        .sidebar-link.dropdown-open {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .dark .sidebar-link.dropdown-open {
            background: #18181b;
            color: #f4f4f5;
        }

        .sidebar-link.dropdown-open .sidebar-icon svg {
            color: #ffffff;
        }

        .dark .sidebar-link.dropdown-open .sidebar-icon svg {
            color: #60a5fa;
        }

        /* Staggered submenu item animation */
        .sidebar-submenu li {
            opacity: 0;
            transform: translateY(-4px);
            transition: opacity 200ms ease, transform 200ms cubic-bezier(.16, 1, .3, 1), color 150ms;
        }

        .sidebar-link.dropdown-open+.sidebar-submenu li {
            opacity: 1;
            transform: translateY(0);
        }

        .sidebar-link.dropdown-open+.sidebar-submenu li:nth-child(1) {
            transition-delay: 40ms;
        }

        .sidebar-link.dropdown-open+.sidebar-submenu li:nth-child(2) {
            transition-delay: 80ms;
        }

        .sidebar-link.dropdown-open+.sidebar-submenu li:nth-child(3) {
            transition-delay: 120ms;
        }

        /* Sub-menu item hover: smooth horizontal shift */
        .sidebar-submenu .sidebar-link {
            transition: transform 150ms ease, color 150ms;
            background: transparent !important;
        }

        .sidebar-submenu .sidebar-link:hover {
            transform: translateX(2px);
            color: #ffffff;
        }

        .dark .sidebar-submenu .sidebar-link:hover {
            color: #f4f4f5;
        }

        /* Collapsed state for dropdowns */
        #sidebar.collapsed .sidebar-submenu {
            display: none !important;
        }
    </style>
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
                rebuildCharts();
            }, 50);
        }
        (function initTheme() {
            const saved = localStorage.getItem('theme');
            if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                document.getElementById('iconMoon').classList.add('hidden');
                document.getElementById('iconSun').classList.remove('hidden');
            }
        })();

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
