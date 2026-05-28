    <style>
        /* ── GLOBAL PREMIUM DASHBOARD LABELS ── */
        label {
            display: block !important;
            font-size: 0.725rem !important; /* text-[11.5px] */
            font-weight: 600 !important; /* Semibold (clean, not harsh) */
            color: #475569 !important; /* Muted slate-600 (soft on eyes) */
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            margin-bottom: 0.4rem !important;
            font-family: system-ui, -apple-system, sans-serif !important;
        }
        .dark label {
            color: #cbd5e1 !important; /* White / light slate in Dark Mode */
        }

        /* ── GLOBAL PREMIUM DASHBOARD INPUTS ── */
        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        input[type="date"],
        input[type="file"],
        select,
        textarea {
            width: 100% !important;
            padding: 0.55rem 0.85rem !important;
            font-size: 0.8rem !important; /* text-[13px] */
            color: #334155 !important; /* text-slate-700 */
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important; /* border-slate-300 - clear but not distracting */
            border-radius: 0.25rem !important; /* rounded-sm */
            outline: none !important;
            transition: all 200ms cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        /* Dark mode compatibility */
        .dark input[type="text"],
        .dark input[type="number"],
        .dark input[type="email"],
        .dark input[type="password"],
        .dark input[type="tel"],
        .dark input[type="date"],
        .dark input[type="file"],
        .dark select,
        .dark textarea {
            color: #f4f4f5 !important;
            background-color: #18181b !important;
            border: 1px solid #3f3f46 !important;
        }

        /* Muted placeholders */
        input::placeholder,
        textarea::placeholder {
            color: #94a3b8 !important; /* slate-400 */
            opacity: 0.85 !important;
            font-size: 0.775rem !important;
        }
        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #71717a !important; /* zinc-500 */
        }

        /* Focus states - smooth, soft shadow ring */
        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="tel"]:focus,
        input[type="date"]:focus,
        input[type="file"]:focus,
        select:focus,
        textarea:focus {
            border-color: #6366f1 !important; /* focus:border-indigo-500 */
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.08) !important; /* focus:ring-4 focus:ring-indigo-500/8 */
        }
        .dark input[type="text"]:focus,
        .dark input[type="number"]:focus,
        .dark input[type="email"]:focus,
        .dark input[type="password"]:focus,
        .dark input[type="tel"]:focus,
        .dark input[type="date"]:focus,
        .dark input[type="file"]:focus,
        .dark select:focus,
        .dark textarea:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12) !important;
        }

        /* Styled select options */
        select option {
            font-size: 0.8rem !important;
            background-color: #ffffff !important;
            color: #334155 !important;
        }
        .dark select option {
            background-color: #18181b !important;
            color: #f4f4f5 !important;
        }

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