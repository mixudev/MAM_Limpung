@extends('dashboard.layouts.main')

@section('content')
<!-- Custom Breadcrumb Override -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.innerHTML = '<a href="{{ route("admin.ppdb.index") }}" class="hover:underline">PPDB Siswa</a> <span class="mx-2">/</span> <span>Tambah Pendaftar</span>';
        }
    });
</script>

<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white dark:bg-zinc-900 p-6 border border-slate-300 dark:border-zinc-800 border-l-4 border-l-[#4f45b2] rounded-none shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white leading-tight">Tambah Pendaftar Baru</h1>
            <p class="text-sm text-slate-500 dark:text-zinc-400 mt-1">Formulir khusus admin untuk mengentri calon siswa yang mendaftar secara langsung.</p>
        </div>
        <a href="{{ route('admin.ppdb.index') }}" class="inline-flex items-center gap-2 py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-750 dark:text-zinc-250 font-bold text-xs uppercase tracking-wider rounded-none transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Form Submit -->
    <form action="{{ route('admin.ppdb.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Grid Layout for Modular Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left & Middle: Input Data Siswa & Wali (Col-span 2) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- 1. Data Diri -->
                <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-300 dark:border-zinc-800 border-t-4 border-t-[#4f45b2] rounded-none shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
                    @include('dashboard.admin.ppdb.partials.create.personal_data')
                </div>

                <!-- 2. Kontak & Alamat -->
                <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-300 dark:border-zinc-800 border-t-4 border-t-[#4f45b2] rounded-none shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
                    @include('dashboard.admin.ppdb.partials.create.contact_address')
                </div>

                <!-- 3. Orang Tua / Wali -->
                <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-300 dark:border-zinc-800 border-t-4 border-t-[#4f45b2] rounded-none shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
                    @include('dashboard.admin.ppdb.partials.create.parent_data')
                </div>
            </div>

            <!-- Right Column: Uploads & Custom Fields (Col-span 1) -->
            <div class="space-y-6">
                <!-- 4. Data Tambahan & Berkas -->
                <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-300 dark:border-zinc-800 border-t-4 border-t-[#4f45b2] rounded-none shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
                    @include('dashboard.admin.ppdb.partials.create.custom_data')
                </div>
            </div>
        </div>

        <!-- Full Width Bottom Section: Status & Catatan Admin -->
        <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-300 dark:border-zinc-800 border-t-4 border-t-emerald-600 rounded-none shadow-[1px_1px_3px_rgba(0,0,0,0.05)]">
            @include('dashboard.admin.ppdb.partials.create.admin_status')
        </div>

        <!-- Full Width Bottom Actions -->
        <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-300 dark:border-zinc-800 rounded-none shadow-[1px_1px_3px_rgba(0,0,0,0.05)] flex items-center justify-end gap-3">
            <button type="reset" class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-750 dark:text-zinc-250 font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98]">
                Reset Form
            </button>
            <button type="submit" class="py-2.5 px-6 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.98]">
                Simpan Pendaftar
            </button>
        </div>
    </form>
</div>
@endsection
