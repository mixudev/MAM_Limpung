@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Daftar Artikel';
        }
    });
</script>

<div class="space-y-6">

    {{-- ── Header ─────────────────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Kelola Artikel</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Buat, edit, dan publikasikan artikel untuk website sekolah.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}"
           class="py-2.5 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs tracking-wider flex items-center justify-center gap-2 font-mono transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            TAMBAH ARTIKEL
        </a>
    </div>

    {{-- ── Tab Navigasi ────────────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">

        {{-- Tab bar --}}
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin'))
        <div class="flex items-stretch border-b border-slate-200 dark:border-zinc-800 overflow-x-auto">

            @php
                $tabs = [
                    'all'       => ['label' => 'Semua',           'color' => 'slate'],
                    'published' => ['label' => 'Diterbitkan',      'color' => 'emerald'],
                    'pending'   => ['label' => 'Perlu Ditinjau',   'color' => 'amber'],
                    'others'    => ['label' => 'Draft & Arsip',    'color' => 'rose'],
                ];

                $activeClasses = [
                    'all'       => 'border-b-2 border-[#4f45b2] text-[#4f45b2] dark:text-indigo-400 bg-indigo-50/60 dark:bg-indigo-950/20',
                    'published' => 'border-b-2 border-emerald-600 text-emerald-700 dark:text-emerald-400 bg-emerald-50/60 dark:bg-emerald-950/20',
                    'pending'   => 'border-b-2 border-amber-500 text-amber-700 dark:text-amber-400 bg-amber-50/60 dark:bg-amber-950/20',
                    'others'    => 'border-b-2 border-rose-500 text-rose-700 dark:text-rose-400 bg-rose-50/60 dark:bg-rose-950/20',
                ];

                $badgeClasses = [
                    'all'       => 'bg-slate-100 dark:bg-zinc-700 text-slate-600 dark:text-zinc-300',
                    'published' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400',
                    'pending'   => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400',
                    'others'    => 'bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-400',
                ];

                $inactiveClasses = 'text-slate-500 dark:text-zinc-400 hover:text-slate-700 dark:hover:text-zinc-200 hover:bg-slate-50 dark:hover:bg-zinc-800/50';
            @endphp

            @foreach($tabs as $key => $meta)
                @php
                    $isActive  = $tab === $key;
                    $tabUrl    = request()->fullUrlWithQuery(['tab' => $key, 'page' => null, 'search' => null, 'category_id' => null]);
                    $baseClass = 'flex items-center gap-2 px-5 py-3.5 text-xs font-bold font-mono uppercase tracking-wider whitespace-nowrap transition-colors';
                @endphp
                <a href="{{ $tabUrl }}"
                   class="{{ $baseClass }} {{ $isActive ? $activeClasses[$key] : $inactiveClasses }}">

                    {{-- Ikon per tab --}}
                    @if($key === 'all')
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
                        </svg>
                    @elseif($key === 'published')
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
                        </svg>
                    @elseif($key === 'pending')
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                    @endif

                    {{ $meta['label'] }}

                    {{-- Badge count --}}
                    <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-[10px] font-bold
                                 {{ $isActive ? $badgeClasses[$key] : 'bg-slate-100 dark:bg-zinc-700 text-slate-500 dark:text-zinc-400' }}">
                        {{ $counts[$key] }}
                    </span>

                    {{-- Animasi ping khusus tab pending jika ada artikel --}}
                    @if($key === 'pending' && $counts['pending'] > 0 && !$isActive)
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
        @endif

        {{-- Filter dalam tab --}}
        <div class="px-6 py-4 border-b border-slate-100 dark:border-zinc-800/60 bg-slate-50/50 dark:bg-zinc-800/20">
            <form action="{{ route('admin.articles.index') }}" method="GET"
                  class="flex flex-col sm:flex-row items-end gap-3">

                {{-- Pertahankan tab aktif saat filter --}}
                <input type="hidden" name="tab" value="{{ $tab }}">

                <div class="flex-1 min-w-0">
                    <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Cari Judul</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Ketik judul artikel..."
                           class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:border-[#4f45b2]" />
                </div>

                <div class="w-full sm:w-48">
                    <label class="block text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1">Kategori</label>
                    <select name="category_id"
                            class="w-full px-3 py-2 text-xs bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-[#4f45b2]">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-2 bg-slate-900 dark:bg-zinc-700 hover:bg-slate-700 text-white font-bold text-xs font-mono uppercase tracking-wider transition-colors">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'category_id']))
                        <a href="{{ route('admin.articles.index', ['tab' => $tab]) }}"
                           class="px-4 py-2 bg-white dark:bg-zinc-800 hover:bg-slate-100 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-300 font-bold text-xs font-mono uppercase tracking-wider transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="p-6">

            {{-- Banner khusus tab pending --}}
            @if($tab === 'pending' && $counts['pending'] > 0)
                <div class="flex items-center gap-3 mb-4 px-4 py-3 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/40">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <p class="text-xs font-semibold text-amber-800 dark:text-amber-300">
                        {{ $counts['pending'] }} artikel menunggu tinjauan. Klik <strong>Tinjau</strong> untuk memeriksa dan mengambil keputusan.
                    </p>
                </div>
            @endif

            <div class="overflow-x-auto border border-slate-100 dark:border-zinc-800">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700 text-[10px] font-mono uppercase font-bold tracking-wider text-slate-500 dark:text-zinc-400">
                            <th class="py-3.5 px-4 w-14">Thumb</th>
                            <th class="py-3.5 px-4">Judul Artikel</th>
                            <th class="py-3.5 px-4 w-36 hidden md:table-cell">Kategori</th>
                            <th class="py-3.5 px-4 w-32 hidden lg:table-cell">Penulis</th>
                            @if($tab === 'all' || $tab === 'others')
                                <th class="py-3.5 px-4 w-24 text-center">Status</th>
                            @endif
                            <th class="py-3.5 px-4 w-32 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800 text-xs">
                        @forelse($articles as $art)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-zinc-800/30 transition-colors
                                       {{ $art->status === 'pending' ? 'bg-amber-50/30 dark:bg-amber-950/10' : ($art->status === 'revision' ? 'bg-blue-50/30 dark:bg-blue-950/10' : '') }}">

                                {{-- Thumbnail --}}
                                <td class="py-3 px-4">
                                    <div class="w-12 h-9 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 overflow-hidden flex items-center justify-center">
                                        @if($art->thumbnail)
                                            <img src="{{ $art->thumbnailUrl() }}" class="w-full h-full object-cover" alt="">
                                        @else
                                            <svg class="w-4 h-4 text-slate-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                </td>

                                {{-- Judul --}}
                                <td class="py-3 px-4">
                                    <div class="flex items-start gap-2">
                                        @if(in_array($art->status, ['pending', 'revision']))
                                            <span class="relative flex-shrink-0 mt-[5px]">
                                                <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full opacity-75
                                                             {{ $art->status === 'revision' ? 'bg-blue-400' : 'bg-amber-400' }}"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2
                                                             {{ $art->status === 'revision' ? 'bg-blue-500' : 'bg-amber-500' }}"></span>
                                            </span>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-800 dark:text-zinc-200 leading-snug line-clamp-2">
                                                {{ $art->judul }}
                                            </div>
                                            <div class="text-[10px] text-slate-400 dark:text-zinc-500 mt-0.5 font-mono truncate max-w-xs">
                                            @if($art->status === 'published' && $art->published_at)
                                                <span class="text-emerald-600 dark:text-emerald-400">{{ $art->published_at->translatedFormat('d M Y') }}</span>
                                            @else
                                                {{ $art->updated_at->translatedFormat('d M Y') }}
                                            @endif
                                            </div>
                                            @if($art->status === 'pending')
                                                <span class="inline-block mt-1 text-[9px] font-mono font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">⚑ Perlu ditinjau</span>
                                            @elseif($art->status === 'revision')
                                                <span class="inline-block mt-1 text-[9px] font-mono font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">↻ Sedang direvisi</span>
                                            @elseif($art->status === 'rejected')
                                                <span class="inline-block mt-1 text-[9px] font-mono font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">✕ Ditolak (Ke-{{ $art->rejection_count }})</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Kategori --}}
                                <td class="py-3 px-4 hidden md:table-cell text-slate-600 dark:text-zinc-400">
                                    {{ $art->category ? $art->category->name : '—' }}
                                </td>

                                {{-- Penulis --}}
                                <td class="py-3 px-4 hidden lg:table-cell text-slate-600 dark:text-zinc-400 font-medium">
                                    {{ $art->penulis ? $art->penulis->name : '—' }}
                                </td>

                                {{-- Status badge --}}
                                @if($tab === 'all' || $tab === 'others')
                                <td class="py-3 px-4 text-center">
                                    @if($art->status === 'published')
                                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40">TERBIT</span>
                                    @elseif($art->status === 'pending')
                                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/40">PENDING</span>
                                    @elseif($art->status === 'revision')
                                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/40">REVISI</span>
                                    @elseif($art->status === 'rejected')
                                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/40">DITOLAK</span>
                                    @elseif($art->status === 'draft')
                                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400 border border-slate-200 dark:border-zinc-700">DRAFT</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[9px] font-bold font-mono tracking-wider uppercase bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/40">ARSIP</span>
                                    @endif
                                </td>
                                @endif


                                {{-- Aksi --}}
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @php
                                            $userIsAdmin = Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin');
                                        @endphp

                                        @if($art->rejection_count >= 2 && !$userIsAdmin)
                                            <span class="inline-flex items-center gap-1 py-1 px-2 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 text-rose-700 dark:text-rose-400 text-[10px] font-mono font-bold uppercase">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                </svg>
                                                Terkunci
                                            </span>
                                        @elseif($art->status === 'revision' && !$userIsAdmin)
                                            <a href="{{ route('admin.articles.edit', $art->slug) }}"
                                               class="inline-flex items-center gap-1 py-1 px-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[10px] uppercase font-mono tracking-wider transition-colors">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                                </svg>
                                                Revisi Artikel
                                            </a>
                                            <form action="{{ route('admin.articles.destroy', $art->slug) }}" method="POST"
                                                  class="inline" id="delete-form-{{ $art->slug }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="AppPopup.confirm({
                                                            title: 'Hapus Artikel',
                                                            description: 'Artikel akan dipindahkan ke tempat sampah. Lanjutkan?',
                                                            confirmText: 'Ya, Hapus',
                                                            cancelText: 'Batal',
                                                            onConfirm: function() { document.getElementById('delete-form-{{ $art->slug }}').submit(); }
                                                        })"
                                                        class="inline-flex items-center py-1 px-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            @if($art->status === 'pending' && !$userIsAdmin)
                                                <a href="{{ route('admin.articles.show', $art->slug) }}"
                                                   class="inline-flex items-center gap-1 py-1 px-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-400 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.58-3.007-9.964-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    View
                                                </a>
                                            @elseif($art->status === 'pending' && $userIsAdmin)
                                                <a href="{{ route('admin.articles.show', $art->slug) }}"
                                                   class="inline-flex items-center gap-1 py-1 px-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-[10px] uppercase font-mono tracking-wider transition-colors">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.58-3.007-9.964-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Tinjau
                                                </a>
                                            @elseif($art->status === 'revision')
                                                <a href="{{ route('admin.articles.show', $art->slug) }}"
                                                   class="inline-flex items-center gap-1 py-1 px-2.5 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/30 dark:hover:bg-blue-900/40 border border-blue-200 dark:border-blue-700/50 text-blue-700 dark:text-blue-400 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.58-3.007-9.964-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Lihat
                                                </a>
                                            @else
                                                <a href="{{ route('admin.articles.show', $art->slug) }}"
                                                   class="inline-flex items-center gap-1 py-1 px-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-400 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.58-3.007-9.964-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    View
                                                </a>
                                            @endif

                                            <a href="{{ route('admin.articles.edit', $art->slug) }}"
                                               class="inline-flex items-center gap-1 py-1 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                                </svg>
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.articles.destroy', $art->slug) }}" method="POST"
                                                  class="inline" id="delete-form-{{ $art->slug }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="AppPopup.confirm({
                                                            title: 'Hapus Artikel',
                                                            description: 'Artikel akan dipindahkan ke tempat sampah. Lanjutkan?',
                                                            confirmText: 'Ya, Hapus',
                                                            cancelText: 'Batal',
                                                            onConfirm: function() { document.getElementById('delete-form-{{ $art->slug }}').submit(); }
                                                        })"
                                                        class="inline-flex items-center py-1 px-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 font-bold text-[10px] uppercase font-mono tracking-wider transition-colors">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-slate-400 dark:text-zinc-500">
                                        <svg class="w-10 h-10 opacity-40" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        <p class="text-xs font-mono">Tidak ada artikel di kategori ini.</p>
                                        @if(request()->anyFilled(['search', 'category_id']))
                                            <a href="{{ route('admin.articles.index', ['tab' => $tab]) }}"
                                               class="text-xs text-[#4f45b2] hover:underline font-mono">Hapus filter</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($articles->hasPages())
                <div class="pt-4">
                    {{ $articles->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
