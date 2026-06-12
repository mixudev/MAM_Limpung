{{-- ════════════════════════════════════════════════════════════
     CHATBOT SECTION NAV — shared across all chatbot pages
════════════════════════════════════════════════════════════ --}}
@php
    $currentRoute = Route::currentRouteName();
    $navItems = [
        ['route' => 'admin.chatbot.analytics',  'icon' => 'fa-chart-line',     'label' => 'Analitik',    'color' => 'text-[#4f45b2]'],
        ['route' => 'admin.chatbot.apikeys',     'icon' => 'fa-key',            'label' => 'Kunci API',   'color' => 'text-amber-500'],
        ['route' => 'admin.chatbot.knowledge',   'icon' => 'fa-book-open',      'label' => 'Pengetahuan', 'color' => 'text-indigo-500'],
        ['route' => 'admin.chatbot.faqs',        'icon' => 'fa-circle-question','label' => 'FAQ Cepat',   'color' => 'text-cyan-500'],
        ['route' => 'admin.chatbot.history',     'icon' => 'fa-comments',       'label' => 'Riwayat',     'color' => 'text-emerald-500'],
        ['route' => 'admin.chatbot.logs',        'icon' => 'fa-list-check',     'label' => 'Log',         'color' => 'text-rose-500'],
        ['route' => 'admin.chatbot.guide',       'icon' => 'fa-book',           'label' => 'Panduan',     'color' => 'text-violet-500'],
    ];
@endphp

<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm overflow-x-auto">
    <div class="flex items-stretch min-w-max">
        @foreach($navItems as $item)
        @php $active = $currentRoute === $item['route']; @endphp
        <a href="{{ route($item['route']) }}"
            class="flex items-center gap-2 px-5 py-3.5 text-xs font-bold font-mono uppercase tracking-wider whitespace-nowrap transition-colors border-b-2
                {{ $active
                    ? 'border-[#4f45b2] text-[#4f45b2] dark:text-indigo-400 bg-indigo-50/60 dark:bg-indigo-950/20'
                    : 'border-transparent text-slate-500 dark:text-zinc-400 hover:text-slate-700 dark:hover:text-zinc-200 hover:bg-slate-50 dark:hover:bg-zinc-800/50' }}">
            <i class="fa-solid {{ $item['icon'] }} {{ $active ? 'text-[#4f45b2] dark:text-indigo-400' : $item['color'] }}"></i>
            {{ $item['label'] }}
        </a>
        @endforeach
    </div>
</div>
