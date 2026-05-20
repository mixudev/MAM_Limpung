@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Tambah Teks Berjalan';
        }
    });
</script>

<div class="max-w-4xl space-y-6">
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Tambah Teks Berjalan Baru</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Buat kalimat pengumuman teks berjalan baru. Teks ini akan ditambahkan ke baris ticker berjalan.</p>
        </div>
        <a href="{{ route('admin.announcements.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center">
            Kembali ke Daftar
        </a>
    </div>

    @if ($errors->any())
    <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/60 p-4 text-red-800 dark:text-red-400 text-xs font-semibold rounded-none">
        <p class="font-bold mb-2">Terjadi kesalahan input:</p>
        <ul class="list-disc list-inside space-y-1 font-mono">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm p-6">
        <form action="{{ route('admin.announcements.texts.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <!-- Title -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Judul Running Text <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Info PPDB 2026 Dibuka"
                        class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]" />
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Isi Pengumuman (Teks yang Berjalan) <span class="text-rose-500">*</span></label>
                    <textarea name="content" rows="4" required placeholder="Masukkan pesan pengumuman singkat..."
                        class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">{{ old('content') }}</textarea>
                </div>

                <!-- Status -->
                <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-zinc-800">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                        class="w-4 h-4 text-[#4f45b2] border-slate-300 focus:ring-[#4f45b2] rounded-none" />
                    <label for="is_active" class="text-sm font-bold text-slate-700 dark:text-zinc-300 select-none">Aktifkan teks berjalan ini</label>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                <a href="{{ route('admin.announcements.index') }}" class="py-2 px-5 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all">
                    Batal
                </a>
                <button type="submit" class="py-2 px-5 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all tracking-wider">
                    Simpan Running Text
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
