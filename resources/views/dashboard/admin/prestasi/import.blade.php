@extends('dashboard.layouts.main')

@section('content')
    <!-- Load Alpine.js CDN for dynamic client-side interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const breadcrumb = document.getElementById('breadcrumb');
            if (breadcrumb) breadcrumb.textContent = 'Import Data Prestasi';
        });
    </script>

    <div x-data="importManager()" class="max-w-screen space-y-6">

        {{-- Header Section --}}
        @include('dashboard.admin.prestasi.partials.import-header')

        {{-- Alerts & Response Messages Section --}}
        @include('dashboard.admin.prestasi.partials.import-alerts')

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            {{-- Left column (3/4 width) --}}
            <div class="lg:col-span-3 space-y-5">
                {{-- Step 1: Download Template Excel --}}
                @include('dashboard.admin.prestasi.partials.import-template-card')

                {{-- Step 2: Drag & Drop Area --}}
                @include('dashboard.admin.prestasi.partials.import-dropzone')

                {{-- Step 4: Import/Submit Button Area --}}
                @include('dashboard.admin.prestasi.partials.import-submit-card')
            </div>

            {{-- Right column (1/4 width) --}}
            <div class="space-y-4">
                {{-- Rules and valid fields side panel --}}
                @include('dashboard.admin.prestasi.partials.import-sidebar')
            </div>
        </div>

        {{-- Step 3: Interactive Preview & Correction Table --}}
        @include('dashboard.admin.prestasi.partials.import-table')
    </div>



    {{-- Script Manager --}}
    @include('dashboard.admin.prestasi.partials.import-script')
@endsection
        