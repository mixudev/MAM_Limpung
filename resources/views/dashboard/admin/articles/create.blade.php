@extends('dashboard.layouts.main')

@section('content')
    <!-- Quill Rich Text Editor Style -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const breadcrumb = document.getElementById('breadcrumb');
            if (breadcrumb) {
                breadcrumb.textContent = 'Tambah Artikel';
            }

            // Toggle published_at input based on status
            const statusSelect = document.getElementById('status');
            const publishTimeDiv = document.getElementById('publish_time_container');

            function togglePublishTime() {
                if (statusSelect.value === 'publish_custom') {
                    publishTimeDiv.classList.remove('hidden');
                } else {
                    publishTimeDiv.classList.add('hidden');
                }
            }

            statusSelect.addEventListener('change', togglePublishTime);
            togglePublishTime(); // Run once on load
        });
    </script>

    <div class="max-w-5xl space-y-6">
        <!-- Header -->
        <div
            class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">Tambah Artikel Baru</h1>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Tulis dan terbitkan artikel literasi baru untuk
                    dibagikan di portal website sekolah.</p>
            </div>
            <div class="gap-3">
                <button type="button" id="btn-clear-draft"
                    class="py-2 px-4 bg-amber-400 hover:bg-amber-500 dark:bg-blue-800 dark:hover:bg-blue-700 border border-slate-200 dark:border-zinc-700 hover:border-rose-300 text-white dark:text-white font-bold text-xs rounded-none transition-all font-mono hover:cursor-pointer"
                    title="Hapus draft tersimpan di browser">
                    HAPUS DRAFT
                </button>
                <a href="{{ route('admin.articles.index') }}"
                    class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center font-mono">
                    KEMBALI
                </a>
            </div>
        </div>

        <!-- Error Flash -->
        @if ($errors->any())
            <div
                class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/60 p-4 text-red-800 dark:text-red-400 text-xs font-semibold rounded-none">
                <p class="font-bold mb-2">Terjadi kesalahan input:</p>
                <ul class="list-disc list-inside space-y-1 font-mono">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm p-6">
            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm"
                class="space-y-6">
                @csrf

                @include('dashboard.admin.articles.partials.create-form')

                <!-- PANEL SEO & PENCARIAN -->
                @include('dashboard.admin.articles.partials.create-form-seo')

                <!-- Submit Section -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-zinc-800">
                    <!-- Autosave status indicator -->
                    <div id="autosave-status"
                        class="flex items-center gap-1.5 text-[10px] font-mono text-slate-400 dark:text-zinc-500">
                        <i class="fa-regular fa-floppy-disk"></i>
                        <span id="autosave-text">Draft belum disimpan</span>
                    </div>
                    <div class="flex gap-3">

                        <a href="{{ route('admin.articles.index') }}"
                            class="py-2.5 px-5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all font-mono">
                            BATAL
                        </a>
                        <button type="submit"
                            class="py-2.5 px-5 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider font-mono">
                            SIMPAN ARTIKEL
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quill Rich Text Editor Script -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    @include('dashboard.admin.articles.partials.script-create')

@endsection
