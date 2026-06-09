@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Pengumuman & Iklan';
        }
    });
</script>

<div class="space-y-10">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Pengaturan Pengumuman & Iklan</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Kelola popup alert informasi penting, running text berjalan, dan banner promosi secara terpisah dan profesional.</p>
        </div>
    </div>


    <!-- SECTION 1: Popup Alert (Modal Dialog) -->
    <div class="space-y-3">
        <div class="flex items-center gap-2 px-1">
            <span class="text-xs font-mono font-bold text-slate-400 dark:text-zinc-500">SECTION 01</span>
            <div class="h-px bg-slate-200 dark:bg-zinc-800 flex-1"></div>
        </div>
        @include('dashboard.admin.announcement.partials.popup_alert_tab')
    </div>

    <!-- SECTION 2: Teks Berjalan (Running Text) -->
    <div class="space-y-3">
        <div class="flex items-center gap-2 px-1">
            <span class="text-xs font-mono font-bold text-slate-400 dark:text-zinc-500">SECTION 02</span>
            <div class="h-px bg-slate-200 dark:bg-zinc-800 flex-1"></div>
        </div>
        @include('dashboard.admin.announcement.partials.running_text_tab')
    </div>

    <!-- SECTION 3: Iklan Banner (Promosi Melayang) -->
    <div class="space-y-3">
        <div class="flex items-center gap-2 px-1">
            <span class="text-xs font-mono font-bold text-slate-400 dark:text-zinc-500">SECTION 03</span>
            <div class="h-px bg-slate-200 dark:bg-zinc-800 flex-1"></div>
        </div>
        @include('dashboard.admin.announcement.partials.banner_ads_tab')
    </div>
</div>
@endsection
