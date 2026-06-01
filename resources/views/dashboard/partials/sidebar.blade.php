<aside id="sidebar"
    class="fixed top-0 left-0 h-full bg-[#4f45b2] dark:bg-zinc-950 border-r border-[#4f45b2]/20 dark:border-zinc-900 z-40 flex flex-col -translate-x-full lg:translate-x-0"
    style="">

    <!-- Logo -->
    <div class="border-b border-white/10 dark:border-zinc-900 flex-shrink-0">
        <div id="sidebar-logo-area" class="flex items-center gap-3">
            <div class="w-8 h-8">
                <img src="{{ asset('assets/img/logo.png') }}" alt="">
            </div>
            <div id="logo-text" class="overflow-hidden">
                <div class="text-[15px] font-semibold tracking-tight text-white whitespace-nowrap">
                    MAM LIMPUNG</div>
                <div class="text-[10px] font-mono text-white/60 dark:text-zinc-500 uppercase tracking-widest whitespace-nowrap">
                    Admin Panel</div>
            </div>
        </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 py-4 overflow-y-auto space-y-5 px-3" id="sidebarNav">
        <div>
            <p
                class="sidebar-section-title px-3 mb-1.5 text-[10px] font-mono font-bold uppercase tracking-widest text-white/50 dark:text-zinc-500">
                Main</p>
            <ul class="space-y-0.5">
                <li>
                    <a href="{{ route('dashboard') }}" data-page="dashboard" class="sidebar-link {{ Route::is('dashboard') ? 'active' : '' }}" aria-label="Dashboard">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"
                                class="w-4 h-4">
                                <rect x="3" y="3" width="7" height="7" rx="1.5" />
                                <rect x="14" y="3" width="7" height="7" rx="1.5" />
                                <rect x="3" y="14" width="7" height="7" rx="1.5" />
                                <rect x="14" y="14" width="7" height="7" rx="1.5" />
                            </svg>
                        </span>
                        <span class="sidebar-label">Dashboard</span>
                        <span class="sidebar-tooltip">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-page="akademik" class="sidebar-link disabled" aria-label="Akademik">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"
                                class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18" />
                            </svg>
                        </span>
                        <span class="sidebar-label">Akademik</span>
                        <span
                            class="sidebar-badge ml-auto text-[10px] font-mono bg-white dark:bg-zinc-900 text-indigo-700 dark:text-zinc-500 px-1.5 py-0.5 rounded-none border border-indigo-100/80 dark:border-zinc-800">12</span>
                        <span class="sidebar-tooltip">Akademik</span>
                    </a>
                </li>
                @if(Auth::user()->hasAnyPermission(['access-admin-dashboard', 'access-super-admin-dashboard']))
                <li>
                    <a href="#" class="sidebar-link dropdown-trigger {{ Route::is('admin.ppdb.*') ? 'active dropdown-open' : '' }}" aria-label="PPDB">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"
                                class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        <span class="sidebar-label">PPDB</span>
                        <svg class="dropdown-chevron w-3.5 h-3.5 ml-auto text-white/60 dark:text-zinc-500 sidebar-label" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                        <span class="sidebar-tooltip">PPDB</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <div class="sidebar-submenu-inner">
                            <li>
                                <a href="{{ route('admin.ppdb.index') }}" class="sidebar-link text-xs py-1.5"
                                    aria-label="Pendaftar PPDB">
                                    <span class="sidebar-label">Pendaftar</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ppdb.settings.edit') }}" class="sidebar-link text-xs py-1.5"
                                    aria-label="Pengaturan PPDB">
                                    <span class="sidebar-label">Pengaturan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ppdb.google-sheets.edit') }}" class="sidebar-link text-xs py-1.5"
                                    aria-label="Google Sheets PPDB">
                                    <span class="sidebar-label">Google Sheets</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ppdb.export') }}" class="sidebar-link text-xs py-1.5"
                                    aria-label="Export PPDB">
                                    <span class="sidebar-label">Export Data</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
                @endif
            </ul>
        </div>

                <div>
            <p
                class="sidebar-section-title px-3 mb-1.5 text-[10px] font-mono font-bold uppercase tracking-widest text-white/50 dark:text-zinc-500">
                Information</p>
            <ul class="space-y-0.5">
                @can('view-articles')
                <li>
                    <a href="{{ route('admin.articles.index') }}" class="sidebar-link {{ Route::is('admin.articles.*') ? 'active' : '' }}" aria-label="Artikel">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-newspaper" viewBox="0 0 16 16">
                                <path d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5z"/>
                                <path d="M2 3h10v2H2zm0 3h4v3H2zm0 4h4v1H2zm0 2h4v1H2zm5-6h2v1H7zm3 0h2v1h-2zM7 8h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2z"/>
                            </svg>
                        </span>
                        <span class="sidebar-label">Artikel</span>
                        <span class="sidebar-tooltip">Artikel</span>
                    </a>
                </li>
                @endcan
                @can('view-achievements')
                <li>
                    <a href="{{ route('admin.prestasi.index') }}" class="sidebar-link {{ Route::is('admin.prestasi.*') ? 'active' : '' }}" aria-label="Prestasi">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trophy" viewBox="0 0 16 16">
                                <path d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5q0 .807-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33 33 0 0 1 2.5.5m.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935m10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935M3.504 1q.01.775.056 1.469c.13 2.028.457 3.546.87 4.667C5.294 9.48 6.484 10 7 10a.5.5 0 0 1 .5.5v2.61a1 1 0 0 1-.757.97l-1.426.356a.5.5 0 0 0-.179.085L4.5 15h7l-.638-.479a.5.5 0 0 0-.18-.085l-1.425-.356a1 1 0 0 1-.757-.97V10.5A.5.5 0 0 1 9 10c.516 0 1.706-.52 2.57-2.864.413-1.12.74-2.64.87-4.667q.045-.694.056-1.469z"/>
                            </svg>
                        </span>
                        <span class="sidebar-label">Prestasi</span>
                        <span class="sidebar-tooltip">Prestasi</span>
                    </a>
                </li>
                @endcan
                @can('view-galeri')
                <li>
                    <a href="{{ route('admin.galeri.index') }}" class="sidebar-link {{ Route::is('admin.galeri.*') ? 'active' : '' }}" aria-label="Galeri Foto">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera" viewBox="0 0 16 16">
                                <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4z"/>
                                <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5m0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                            </svg>
                        </span>
                        <span class="sidebar-label">Galeri Foto</span>
                        <span class="sidebar-tooltip">Galeri Foto</span>
                    </a>
                </li>
                @endcan
                @if(Auth::user()->hasAnyPermission(['access-admin-dashboard', 'access-super-admin-dashboard']))
                <li>
                    <a href="{{ route('admin.announcements.index') }}" data-page="pengumuman" class="sidebar-link {{ Route::is('admin.announcements.*') ? 'active' : '' }}" aria-label="Pengumuman">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-megaphone" viewBox="0 0 16 16">
                                <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 75 75 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0m-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233q.27.015.537.036c2.568.189 5.093.744 7.463 1.993zm-9 6.215v-4.13a95 95 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A61 61 0 0 1 4 10.065m-.657.975 1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68 68 0 0 0-1.722-.082z"/>
                            </svg>
                        </span>
                        <span class="sidebar-label">Pengumuman</span>
                        <span class="sidebar-tooltip">Pengumuman</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar-link dropdown-trigger {{ Route::is('admin.article-categories.*') ? 'active dropdown-open' : '' }}" aria-label="kategori">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid" viewBox="0 0 16 16">
                                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
                            </svg>
                        </span>
                        <span class="sidebar-label">Kategori</span>
                        <svg class="dropdown-chevron w-3.5 h-3.5 ml-auto text-white/60 dark:text-zinc-500 sidebar-label" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                        <span class="sidebar-tooltip">Kategori</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <div class="sidebar-submenu-inner">
                            <li>
                                <a href="{{ route('admin.article-categories.index') }}" class="sidebar-link text-xs py-1.5 "
                                    aria-label="kategori-artikel">
                                    <span class="sidebar-label">Kategori Artikel</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="sidebar-link disabled text-xs py-1.5"
                                    aria-label="Lain-lain">
                                    <span class="sidebar-label">Lain-lain</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('admin.settings.edit') }}" data-page="settings" class="sidebar-link {{ Route::is('admin.settings.*') ? 'active' : '' }}" aria-label="Pengaturan Website">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        <span class="sidebar-label">Pengaturan Web</span>
                        <span class="sidebar-tooltip">Pengaturan Web</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <div>
            <p
                class="sidebar-section-title px-3 mb-1.5 text-[10px] font-mono font-bold uppercase tracking-widest text-white/50 dark:text-zinc-500">
                Security</p>
            <ul class="space-y-0.5">
                @if(Auth::user()->hasAnyPermission(['access-admin-dashboard', 'access-super-admin-dashboard']))
                <li>
                    <a href="{{ Auth::user()->hasRole('super-admin') ? route('super-admin.logs.index') : route('admin.logs.index') }}" data-page="audit" class="sidebar-link {{ Route::is('*.logs.*') ? 'active' : '' }}" aria-label="Log Sistem">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"
                                class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </span>
                        <span class="sidebar-label">Log Sistem</span>
                        <span class="sidebar-tooltip">Log Sistem</span>
                    </a>
                </li>
                @endif
                @hasrole('super-admin')
                <li>
                    <a href="{{ route('super-admin.roles-permissions.index') }}" data-page="roles-permission" class="sidebar-link {{ Route::is('super-admin.roles-permissions.*') ? 'active' : '' }}" aria-label="Roles-Permission">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"
                                class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </span>
                        <span class="sidebar-label">Roles & Permission</span>
                        <span class="sidebar-tooltip">Roles & Permission</span>
                    </a>
                </li>
                @endhasrole
                @can('view-users')
                <li>
                    <a href="{{ Auth::user()->hasRole('super-admin') ? route('super-admin.users.index') : route('admin.users.index') }}" data-page="users" class="sidebar-link {{ Route::is('*.users.index') ? 'active' : '' }}" aria-label="User Accounts">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"
                                class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        <span class="sidebar-label">User Accounts</span>
                        <span class="sidebar-tooltip">User Accounts</span>
                    </a>
                </li>
                @endcan
                @if(Auth::user()->hasAnyPermission(['access-admin-dashboard', 'access-super-admin-dashboard']))
                <li>
                    <a href="{{ route('admin.security.index') }}" data-page="security" class="sidebar-link {{ Route::is('admin.security.*') ? 'active' : '' }}" aria-label="Security">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"
                                class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <span class="sidebar-label">Keamanan</span>
                        <span class="sidebar-tooltip">Keamanan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.backup.index') }}" data-page="backup" class="sidebar-link {{ Route::is('admin.backup.*') ? 'active' : '' }}" aria-label="Backup">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"
                                class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                        </span>
                        <span class="sidebar-label">Backup Data</span>
                        <span class="sidebar-tooltip">Backup Data</span>
                    </a>
                </li>
                @endif

                <li>
                    <a href="#" data-page="apikeys" class="sidebar-link disabled" aria-label="API Keys">
                        <span class="sidebar-icon w-5 h-5 flex-shrink-0 flex items-center justify-center">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"
                                class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </span>
                        <span class="sidebar-label">API Keys</span>
                        <span class="sidebar-dot-badge ml-auto w-2 h-2 rounded-full bg-amber-400 flex-shrink-0"></span>
                        <span class="sidebar-tooltip">API Keys</span>
                    </a>
                </li>
            </ul>
        </div>

    </nav>


</aside>
