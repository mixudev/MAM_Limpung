@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bc = document.getElementById('breadcrumb');
        if (bc) bc.textContent = 'Chatbot · Panduan';
    });
</script>

<div class="space-y-5">

    {{-- Header --}}
    @include('dashboard.admin.chatbot.partials._header', [
        'title' => 'Panduan',
        'subtitle' => 'Panduan lengkap konfigurasi dan penggunaan AI Chatbot MAM Limpung.'
    ])

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 text-sm text-emerald-700 dark:text-emerald-400 font-mono">
        <i class="fa-solid fa-circle-check shrink-0"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Content --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
        @include('dashboard.admin.chatbot.partials._guide')
    </div>

</div>
@endsection
