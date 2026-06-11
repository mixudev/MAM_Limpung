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

                {{-- web toggle --}}
                <button
                    onclick="window.open('{{ route('frontend.home') }}', '_blank')"
                    class="flex px-3 w-auto gap-2 h-9 items-center justify-center bg-[#4f45b2] rounded-sm text-white dark:text-white"
                    style="transition:background 150ms"
                    title="View website">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M9.752 6.193c.599.6 1.73.437 2.528-.362s.96-1.932.362-2.531c-.599-.6-1.73-.438-2.528.361-.798.8-.96 1.933-.362 2.532"/>
                        <path d="M15.811 3.312c-.363 1.534-1.334 3.626-3.64 6.218l-.24 2.408a2.56 2.56 0 0 1-.732 1.526L8.817 15.85a.51.51 0 0 1-.867-.434l.27-1.899c.04-.28-.013-.593-.131-.956a9 9 0 0 0-.249-.657l-.082-.202c-.815-.197-1.578-.662-2.191-1.277-.614-.615-1.079-1.379-1.275-2.195l-.203-.083a10 10 0 0 0-.655-.248c-.363-.119-.675-.172-.955-.132l-1.896.27A.51.51 0 0 1 .15 7.17l2.382-2.386c.41-.41.947-.67 1.524-.734h.006l2.4-.238C9.005 1.55 11.087.582 12.623.208c.89-.217 1.59-.232 2.08-.188.244.023.435.06.57.093q.1.026.16.045c.184.06.279.13.351.295l.029.073a3.5 3.5 0 0 1 .157.721c.055.485.051 1.178-.159 2.065m-4.828 7.475.04-.04-.107 1.081a1.54 1.54 0 0 1-.44.913l-1.298 1.3.054-.38c.072-.506-.034-.993-.172-1.418a9 9 0 0 0-.164-.45c.738-.065 1.462-.38 2.087-1.006M5.205 5c-.625.626-.94 1.351-1.004 2.09a9 9 0 0 0-.45-.164c-.424-.138-.91-.244-1.416-.172l-.38.054 1.3-1.3c.245-.246.566-.401.91-.44l1.08-.107zm9.406-3.961c-.38-.034-.967-.027-1.746.163-1.558.38-3.917 1.496-6.937 4.521-.62.62-.799 1.34-.687 2.051.107.676.483 1.362 1.048 1.928.564.565 1.25.941 1.924 1.049.71.112 1.429-.067 2.048-.688 3.079-3.083 4.192-5.444 4.556-6.987.183-.771.18-1.345.138-1.713a3 3 0 0 0-.045-.283 3 3 0 0 0-.3-.041Z"/>
                        <path d="M7.009 12.139a7.6 7.6 0 0 1-1.804-1.352A7.6 7.6 0 0 1 3.794 8.86c-1.102.992-1.965 5.054-1.839 5.18.125.126 3.936-.896 5.054-1.902Z"/>
                    </svg>
                    <span class="text-xs">Website</span>
                </button>

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
                                    class="w-7 h-7 rounded-full shrink-0 flex items-center justify-center mt-0.5 bg-amber-100 dark:bg-amber-900/40 text-amber-500">
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
                                    class="unread-dot w-1.5 h-1.5 rounded-full bg-blue-600 dark:bg-blue-400 shrink-0 mt-1.5"></span>
                            </div>
                            <!-- Notification Item 2 -->
                            <div class="notif-item px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 cursor-pointer flex gap-3 items-start"
                                data-read="false">
                                <span
                                    class="w-7 h-7 rounded-full shrink-0 flex items-center justify-center mt-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-500">
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
                                    class="unread-dot w-1.5 h-1.5 rounded-full bg-blue-600 dark:bg-blue-400 shrink-0 mt-1.5"></span>
                            </div>
                            <!-- Notification Item 3 -->
                            <div class="notif-item px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 cursor-pointer flex gap-3 items-start"
                                data-read="false">
                                <span
                                    class="w-7 h-7 rounded-full shrink-0 flex items-center justify-center mt-0.5 bg-blue-100 dark:bg-blue-900/40 text-blue-500">
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
                                    class="unread-dot w-1.5 h-1.5 rounded-full bg-blue-600 dark:bg-blue-400 shrink-0 mt-1.5"></span>
                            </div>
                            <!-- Notification Item 4 -->
                            <div class="notif-item px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 cursor-pointer flex gap-3 items-start opacity-60"
                                data-read="true">
                                <span
                                    class="w-7 h-7 rounded-full shrink-0 flex items-center justify-center mt-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-500">
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
                            class="w-7 h-7 rounded-full bg-linear-to-br shadow-sm shadow-blue-900/20 border border-slate-500/50 from-blue-500 to-indigo-600 flex items-center justify-center text-black dark:text-white text-[11px] font-semibold shrink-0">
                            @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . auth()->user()->name }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                            @else
                            <svg class="w-4 h-4" fill="currentColor"  viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                            </svg>
                            @endif
                        </div>
                        {{-- maksimal 18 karakter --}}
                        <span class="hidden sm:block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            {{ Str::limit(auth()->user()->name, 18) }}
                        </span>
                        <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="profileDropdown"
                        class="hidden absolute right-0 top-full mt-2 w-52 bg-white dark:bg-zinc-800 rounded-none shadow-xl border border-slate-200 dark:border-zinc-700 py-1.5 z-50">
                        <div class="px-4 py-2.5 border-b border-slate-100 dark:border-zinc-700 mb-1">
                            <div class="text-sm font-semibold text-slate-800 dark:text-zinc-200">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="text-xs text-slate-400">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                        <a href="{{ route('user.profile.edit') }}"
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