<header
            class="sticky top-0 z-20 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-sm border-b border-slate-200 dark:border-zinc-800 h-[70px] flex items-center px-4 md:px-6 gap-4">

            <!-- Mobile menu toggle -->
            <button onclick="toggleSidebar()"
                class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800"
                style="transition:background 150ms" aria-label="Open sidebar">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Desktop collapse toggle — left of search -->
            <button id="collapseBtn" onclick="toggleCollapse()"
                class="w-9 h-9 items-center justify-center rounded-lg text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"
                style="transition:background 150ms" aria-label="Toggle sidebar">
                <svg id="collapseBtnIcon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <!-- Search -->
            <div class="flex-1 max-w-md relative">
                <input id="searchInput" type="text" placeholder="Search users, apps, logs..."
                    oninput="handleSearch(this.value)"
                    class="w-full pl-11 pr-4 py-2 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-400/30 focus:border-blue-500"
                    style="transition:border 150ms, box-shadow 150ms" />
                    
                <div id="searchResults"
                    class="hidden absolute top-full left-0 right-0 mt-1 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none shadow-2xl z-50 overflow-hidden py-1">
                </div>
            </div>

            <div class="flex items-center gap-1.5 ml-auto">

                <!-- Dark mode toggle -->
                <button onclick="toggleDark()"
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"
                    style="transition:background 150ms" title="Toggle dark mode">
                    <svg id="iconMoon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg id="iconSun" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="5" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                    </svg>
                </button>

                <!-- Notifications -->
                <div class="relative" id="notifWrapper">
                    <button onclick="toggleNotif()"
                        class="relative w-9 h-9 flex items-center justify-center rounded-lg text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"
                        style="transition:background 150ms">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span id="notifBadge"
                            class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-zinc-900"></span>
                    </button>
                    <div id="notifPanel"
                        class="notif-panel absolute right-0 top-full mt-2 w-80 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none shadow-2xl z-50">
                        <div
                            class="px-4 py-3 border-b border-slate-100 dark:border-zinc-700 flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-800 dark:text-zinc-200">Notifications</span>
                            <button onclick="clearNotifs()"
                                class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Mark all
                                read</button>
                        </div>                        <div id="notifList"
                            class="max-h-72 overflow-y-auto divide-y divide-zinc-50 dark:divide-zinc-700/50">
                            <!-- Notification Item 1 -->
                            <div class="notif-item px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 cursor-pointer flex gap-3 items-start"
                                data-read="false">
                                <span
                                    class="w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center mt-0.5 bg-amber-100 dark:bg-amber-900/40 text-amber-500">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Suspicious
                                         login attempt</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">IP 185.220.101.4
                                        blocked after 5 failures</div>
                                    <div class="text-[10px] text-zinc-400 mt-0.5">2 min ago</div>
                                </div>
                                <span
                                    class="unread-dot w-1.5 h-1.5 rounded-full bg-blue-600 dark:bg-blue-400 flex-shrink-0 mt-1.5"></span>
                            </div>
                            <!-- Notification Item 2 -->
                            <div class="notif-item px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 cursor-pointer flex gap-3 items-start"
                                data-read="false">
                                <span
                                    class="w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center mt-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-500">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">New
                                        application registered</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">"Analytics Portal"
                                        added by admin</div>
                                    <div class="text-[10px] text-zinc-400 mt-0.5">14 min ago</div>
                                </div>
                                <span
                                    class="unread-dot w-1.5 h-1.5 rounded-full bg-blue-600 dark:bg-blue-400 flex-shrink-0 mt-1.5"></span>
                            </div>
                            <!-- Notification Item 3 -->
                            <div class="notif-item px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 cursor-pointer flex gap-3 items-start"
                                data-read="false">
                                <span
                                    class="w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center mt-0.5 bg-blue-100 dark:bg-blue-900/40 text-blue-500">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">API key
                                        expiring soon</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">Key sk_prod_x8k2…
                                        expires in 3 days</div>
                                    <div class="text-[10px] text-zinc-400 mt-0.5">1 hr ago</div>
                                </div>
                                <span
                                    class="unread-dot w-1.5 h-1.5 rounded-full bg-blue-600 dark:bg-blue-400 flex-shrink-0 mt-1.5"></span>
                            </div>
                            <!-- Notification Item 4 -->
                            <div class="notif-item px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 cursor-pointer flex gap-3 items-start opacity-60"
                                data-read="true">
                                <span
                                    class="w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center mt-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-500">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">User bulk
                                         import complete</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">248 users imported
                                        successfully</div>
                                    <div class="text-[10px] text-zinc-400 mt-0.5">3 hr ago</div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="px-4 py-2.5 border-t border-slate-200 dark:border-zinc-700 text-xs text-blue-600 dark:text-blue-400 hover:underline cursor-pointer">
                            View all →</div>
                    </div>
                </div>

                <!-- Activity -->
                <button
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"
                    style="transition:background 150ms">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </button>

                <div class="w-px h-6 bg-zinc-200 dark:bg-zinc-700 mx-1"></div>

                <!-- Profile -->
                <div class="relative" id="profileDropdownWrapper">
                    <button onclick="toggleDropdown()"
                        class="flex items-center gap-2.5 px-2 py-1.5 rounded-none hover:bg-zinc-100 dark:hover:bg-zinc-800"
                        style="transition:background 150ms">
                        <div
                            class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-[11px] font-semibold flex-shrink-0">
                            JD</div>
                        <span class="hidden sm:block text-sm font-medium text-zinc-700 dark:text-zinc-300">James
                            Dawson</span>
                        <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="profileDropdown"
                        class="hidden absolute right-0 top-full mt-2 w-52 bg-white dark:bg-zinc-800 rounded-none shadow-xl border border-slate-200 dark:border-zinc-700 py-1.5 z-50">
                        <div class="px-4 py-2.5 border-b border-slate-100 dark:border-zinc-700 mb-1">
                            <div class="text-sm font-semibold text-slate-800 dark:text-zinc-200">James Dawson</div>
                            <div class="text-xs text-slate-400">james@company.io</div>
                        </div>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-700 hover:text-blue-600 dark:hover:text-white"
                            style="transition:background 100ms"><svg class="w-4 h-4 text-slate-400 hover:text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>Profile</a>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-700 hover:text-blue-600 dark:hover:text-white"
                            style="transition:background 100ms"><svg class="w-4 h-4 text-slate-400 hover:text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>Account Settings</a>
                        <div class="border-t border-slate-100 dark:border-zinc-700 mt-1 pt-1">
                            <a href="#"
                                onclick="event.preventDefault(); AppPopup.confirm({
                                    title: 'Akhiri sesi sekarang?',
                                    description: 'Aktivitas Anda telah tersimpan dengan aman.',
                                    confirmText: 'Ya, Keluar',
                                    cancelText: 'Batal',
                                    onConfirm: () => document.getElementById('logout-form').submit()
                                });"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20"
                                style="transition:background 100ms"><svg class="w-4 h-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>Logout</a>
                            <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>