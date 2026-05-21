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
    @if ($errors->any())
    <div id="admin-ppdb-errors" class="bg-red-50 dark:bg-red-950/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-none shadow-sm relative" role="alert">
        <button type="button" onclick="this.closest('#admin-ppdb-errors').remove()" class="absolute top-3 right-3 text-red-500 hover:text-red-800 dark:hover:text-red-200 p-1" aria-label="Tutup notifikasi">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <p class="font-bold mb-2 pr-8">Terjadi kesalahan. Periksa kolom yang ditandai:</p>
        <ul class="list-disc list-inside space-y-1 text-sm pr-8">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

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

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const firstErrorKey = @json($errors->keys()->first());
        const el = document.querySelector('[name="' + firstErrorKey + '"]');
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof el.focus === 'function') {
                el.focus({ preventScroll: true });
            }
        }
    });
</script>
@endif

<script>
    function renderAdminFilePreview(file, previewEl) {
        previewEl.classList.remove('hidden');
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewEl.innerHTML = `
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-2">Pratinjau</p>
                    <img src="${e.target.result}" alt="Pratinjau" class="max-h-36 w-auto border border-slate-200 dark:border-zinc-700 object-contain">
                    <p class="text-xs text-slate-600 dark:text-zinc-400 mt-2 truncate">${file.name}</p>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            previewEl.innerHTML = `
                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-1">Berkas terpilih</p>
                <p class="text-xs font-mono truncate">${file.name}</p>
            `;
        }
    }

    document.querySelectorAll('.admin-ppdb-file-input').forEach((input) => {
        const preview = document.getElementById(input.id + '_preview');
        if (!preview) {
            return;
        }
        input.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                renderAdminFilePreview(e.target.files[0], preview);
            }
        });
    });
</script>
@endsection
