@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Pengaturan Website';
        }
    });
</script>

<div class="max-w-6xl space-y-6">
    <!-- Header & Alerts -->
    @include('dashboard.admin.settings.partials.header')

    <!-- Form & Tabs Card -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm overflow-hidden">
        
        <!-- Tab Navigation -->
        @include('dashboard.admin.settings.partials.tabs-nav')

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Tab Contents -->
            @include('dashboard.admin.settings.partials.tabs.general')
            @include('dashboard.admin.settings.partials.tabs.contact')
            @include('dashboard.admin.settings.partials.tabs.social')
            @include('dashboard.admin.settings.partials.tabs.seo')

            <!-- Footer Actions -->
            @include('dashboard.admin.settings.partials.footer')
        </form>
    </div>
</div>

<!-- Scripts -->
@include('dashboard.admin.settings.partials.scripts')
@endsection
