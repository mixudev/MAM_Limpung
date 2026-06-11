@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) breadcrumb.textContent = 'Manajemen Backup';
        if (typeof toggleStorageFolders === 'function') toggleStorageFolders();
    });
</script>

<div class="max-w-6xl space-y-6">
    {{-- Header --}}
    <div class="bg-linear-to-r from-slate-800 via-slate-700 to-slate-800 dark:from-zinc-900 dark:to-zinc-950 p-6 border-b-4 border-cyan-500 dark:border-slate-600 shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4 text-slate-700 dark:text-white">
        <div>
            <h1 class="text-xl font-bold tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-database text-cyan-500 dark:text-slate-300"></i> Manajemen Backup Data
            </h1>
            <p class="text-xs text-slate-400 dark:text-zinc-400 mt-1">Atur jadwal backup, enkripsi AES-256, sinkronisasi Google Drive, dan verifikasi integritas berkas cadangan data Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="flex h-3 w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $backupSettings['enabled'] ? 'bg-emerald-400' : 'bg-amber-400' }} opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 {{ $backupSettings['enabled'] ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
            </span>
            <span class="text-xs font-mono font-bold tracking-wider uppercase bg-white/10 px-3 py-1.5 border border-white/20">
                Scheduler: {{ $backupSettings['enabled'] ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 p-4 shadow-sm space-y-1">
        <div class="flex items-center gap-3">
            <div class="p-1 bg-rose-500 text-white rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div>
            <p class="text-xs font-bold text-rose-800 dark:text-rose-300">Kesalahan!</p>
        </div>
        <ul class="list-disc list-inside text-[11px] text-rose-600 dark:text-rose-400/90 pl-8">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Tabs Card --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="flex border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 overflow-x-auto">
            <button type="button" onclick="switchTab('tab-settings')" id="btn-tab-settings"
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-indigo-600 text-indigo-600 dark:text-white focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-sliders mr-2"></i> Pengaturan Backup
            </button>
            <button type="button" onclick="switchTab('tab-history')" id="btn-tab-history"
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i> Riwayat & Manual Run
            </button>
            <button type="button" onclick="switchTab('tab-verification')" id="btn-tab-verification"
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-shield-halved mr-2"></i> Validasi & Dekripsi
            </button>
        </div>

        <div class="p-6">
            {{-- TAB 1: BACKUP SETTINGS --}}
            @include('dashboard.admin.backup.partials.backup-setting')

            {{-- TAB 2: HISTORY & MANUAL RUN --}}
            @include('dashboard.admin.backup.partials.backup-run')
            

            {{-- TAB 3: VERIFICATION --}}
            @include('dashboard.admin.backup.partials.verification')
        </div>
    </div>
</div>

{{-- ── Password Confirm Modal (menggunakan x-app-modal) ── --}}
<x-app-modal id="passwordConfirmModal" title="Konfirmasi Identitas" maxWidth="sm"
    :icon="'<svg fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\' stroke-width=\'1.8\' class=\'w-5 h-5\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z\' /></svg>'"
    iconColor="indigo">
    <form id="password-confirm-form" method="POST">
        @csrf
        <div class="space-y-1 mb-1">
            <label class="block text-[11px] font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">Kata Sandi Akun</label>
            <input type="password" name="confirm_password" id="confirm_password_input"
                   placeholder="Masukkan kata sandi akun Anda"
                   class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"/>
        </div>
        <p class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1.5">Diperlukan untuk memverifikasi identitas Anda sebelum operasi sensitif ini dijalankan.</p>
    </form>
    <x-slot name="footer">
        <button type="button" onclick="AppModal.close('passwordConfirmModal')" class="modal-btn-cancel">Batal</button>
        <button type="button" onclick="document.getElementById('password-confirm-form').submit()" class="modal-btn-primary">
            <i class="fa-solid fa-lock-open"></i> Konfirmasi & Lanjutkan
        </button>
    </x-slot>
</x-app-modal>

{{-- ── Backup Log Detail Modal ── --}}
<x-app-modal id="backupLogDetailModal" title="Detail Log Backup" maxWidth="lg"
    :icon="'<svg fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\' stroke-width=\'1.8\' class=\'w-5 h-5\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\' /></svg>'"
    iconColor="indigo">
    <div id="backup-log-detail-content" class="space-y-4">
        <div class="grid grid-cols-2 gap-3" id="backup-log-detail-grid"></div>
        <div id="backup-log-error-block" class="hidden p-3 bg-rose-50/60 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900 text-[11px] text-rose-700 dark:text-rose-400 font-mono leading-relaxed"></div>
        <div id="backup-log-drive-block" class="hidden p-3 bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900">
            <span class="text-[10px] font-mono font-bold text-emerald-700 dark:text-emerald-400 uppercase block mb-1">Google Drive File ID</span>
            <span id="backup-log-drive-id" class="text-xs font-mono text-slate-700 dark:text-zinc-300 break-all"></span>
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" onclick="AppModal.close('backupLogDetailModal')" class="modal-btn-cancel">Tutup</button>
    </x-slot>
</x-app-modal>

@include('dashboard.admin.backup.partials.script')


@endsection
