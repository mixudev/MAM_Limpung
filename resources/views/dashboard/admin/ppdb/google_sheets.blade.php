@extends('dashboard.layouts.main')

@section('content')
<!-- Load Alpine.js CDN for Page-Specific Modern Interactive Tabs -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Google Sheets Integration';
        }
    });
</script>

<div class="space-y-6">

    <!-- Header Panel -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Integrasi Google Sheets</h1>
            <p class="text-sm text-slate-500 dark:text-zinc-400 mt-1">Hubungkan basis data PPDB MAM Limpung secara dinamis dengan Google Sheets API secara aman.</p>
        </div>
        <a href="{{ route('admin.ppdb.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center">
            Kembali ke Pendaftar
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/60 p-4 text-emerald-800 dark:text-emerald-400 text-xs font-semibold flex items-center gap-3 animate-fadeIn">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Settings Form & Operations -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Connection Status Dashboard -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm">
                <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-100 dark:border-zinc-850 pb-3 mb-4">
                    Status Integrasi API
                </h3>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <!-- Icon Badge -->
                        <div id="status-badge-icon" class="p-3 bg-amber-50 dark:bg-amber-950/20 text-amber-500 rounded-none flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-mono font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider block">Status Koneksi</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span id="status-title" class="text-base font-extrabold text-amber-600 dark:text-amber-500">Mengecek Koneksi...</span>
                                <span id="status-dot" class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                            </div>
                            <p id="status-desc" class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Mengautentikasi kredensial Google API...</p>
                        </div>
                    </div>

                    <!-- Instant Action Panel -->
                    <div class="flex flex-wrap gap-2.5 w-full sm:w-auto">
                        <button type="button" id="btn-test-connection" class="flex-1 sm:flex-initial py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all flex items-center justify-center gap-2">
                            <svg id="icon-test-conn" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span id="label-test-conn">Uji Koneksi</span>
                        </button>
                        
                        <button type="button" id="btn-sync-all" class="flex-1 sm:flex-initial py-2 px-4 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs rounded-none transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:pointer-events-none">
                            <svg id="icon-sync-all" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9"/>
                                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z"/>
                            </svg>
                            <span id="label-sync-all">Sinkronisasi Sekarang</span>
                        </button>
                    </div>
                </div>

                <!-- Live Error Message Block -->
                <div id="error-block" class="hidden mt-4 p-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/60 rounded-none text-red-800 dark:text-red-400 text-xs font-mono tracking-tight leading-relaxed animate-fadeIn">
                </div>
            </div>

            <!-- Configuration Form Card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 shadow-sm">
                <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-100 dark:border-zinc-850 pb-3 mb-6">
                    Form Pengaturan Google Sheets
                </h3>

                <form id="google-sheets-form" action="{{ route('admin.ppdb.google-sheets.update') }}" method="POST" class="space-y-6" x-data="{ activeTab: 'connection' }">
                    @csrf

                    <!-- Toggle: Enable Integration -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-850/40 border border-slate-200 dark:border-zinc-800">
                        <div>
                            <label for="is_enabled" class="text-sm font-bold text-slate-800 dark:text-white block cursor-pointer">Aktifkan Integrasi Otomatis</label>
                            <span class="text-xs text-slate-400 dark:text-zinc-500 block mt-0.5">Jika aktif, data calon siswa otomatis dikirim ke Google Sheets secara real-time.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" name="is_enabled" id="is_enabled" value="1" {{ $settings['is_enabled'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 dark:bg-zinc-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#4f45b2]"></div>
                        </label>
                    </div>

                    <!-- Tab Buttons Navigation -->
                    <div class="flex flex-wrap border-b border-slate-100 dark:border-zinc-800 gap-1">
                        <button type="button" @click="activeTab = 'connection'" :class="activeTab === 'connection' ? 'border-[#4f45b2] text-[#4f45b2] border-b-2 font-bold' : 'border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-600 dark:hover:text-zinc-300'" class="px-4 py-2.5 text-xs uppercase tracking-wider font-mono transition-all">
                            1. Koneksi
                        </button>
                        <button type="button" @click="activeTab = 'structure'" :class="activeTab === 'structure' ? 'border-[#4f45b2] text-[#4f45b2] border-b-2 font-bold' : 'border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-600 dark:hover:text-zinc-300'" class="px-4 py-2.5 text-xs uppercase tracking-wider font-mono transition-all">
                            2. Tab Sheet
                        </button>
                        <button type="button" @click="activeTab = 'columns'" :class="activeTab === 'columns' ? 'border-[#4f45b2] text-[#4f45b2] border-b-2 font-bold' : 'border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-600 dark:hover:text-zinc-300'" class="px-4 py-2.5 text-xs uppercase tracking-wider font-mono transition-all">
                            3. Kolom Data
                        </button>
                        <button type="button" @click="activeTab = 'design'" :class="activeTab === 'design' ? 'border-[#4f45b2] text-[#4f45b2] border-b-2 font-bold' : 'border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-600 dark:hover:text-zinc-300'" class="px-4 py-2.5 text-xs uppercase tracking-wider font-mono transition-all">
                            4. Desain
                        </button>
                    </div>

                    <!-- Tab Contents (Modular Partials) -->
                    <div class="space-y-6 mt-4">
                        @include('dashboard.admin.ppdb.partials.google_sheets.connection')
                        @include('dashboard.admin.ppdb.partials.google_sheets.structure')
                        @include('dashboard.admin.ppdb.partials.google_sheets.columns')
                        @include('dashboard.admin.ppdb.partials.google_sheets.design')
                    </div>

                    <!-- Submit Button -->
                    <div class="border-t border-slate-100 dark:border-zinc-850 pt-5 flex justify-end">
                        <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.97] shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <span>Simpan Pengaturan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right 1 Col: GCP Guide & Info -->
        <div class="space-y-6">
            @include('dashboard.admin.ppdb.partials.google_sheets.sidebar_info')
        </div>
    </div>

    @include('dashboard.admin.ppdb.partials.google_sheets.instructions')
</div>

<x-allert />

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            if (window.AppPopup) {
                AppPopup.success({
                    title: 'Berhasil Disalin',
                    description: 'Email Akun Layanan berhasil disalin ke clipboard.'
                });
            } else {
                alert('Email Service Account berhasil disalin ke clipboard!');
            }
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const btnTest = document.getElementById('btn-test-connection');
        const btnSync = document.getElementById('btn-sync-all');
        
        const statusBadgeIcon = document.getElementById('status-badge-icon');
        const statusTitle = document.getElementById('status-title');
        const statusDot = document.getElementById('status-dot');
        const statusDesc = document.getElementById('status-desc');
        const errorBlock = document.getElementById('error-block');

        function updateStatusUI(status, details = '', email = '') {
            if (!statusBadgeIcon || !statusTitle || !statusDot || !statusDesc || !errorBlock) return;
            errorBlock.classList.add('hidden');
            errorBlock.textContent = '';
            
            if (status === 'connected') {
                statusBadgeIcon.className = "p-3 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500 rounded-none flex-shrink-0";
                statusTitle.className = "text-base font-extrabold text-emerald-600 dark:text-emerald-500";
                statusTitle.textContent = "Terhubung (Connected)";
                statusDot.className = "w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse";
                statusDesc.textContent = "Koneksi ke Google Sheets API aktif & siap digunakan.";
                if (email) {
                    const badge = document.getElementById('active-email-badge');
                    if (badge) {
                        badge.textContent = email;
                        badge.title = email;
                    }
                }
                if (btnSync) btnSync.disabled = false;
            } else if (status === 'unconfigured') {
                statusBadgeIcon.className = "p-3 bg-amber-50 dark:bg-amber-950/20 text-amber-500 rounded-none flex-shrink-0";
                statusTitle.className = "text-base font-extrabold text-amber-600 dark:text-amber-500";
                statusTitle.textContent = "Belum Dikonfigurasi";
                statusDot.className = "w-2.5 h-2.5 rounded-full bg-amber-500";
                statusDesc.textContent = "Spreadsheet ID atau file kredensial JSON belum diatur.";
                if (btnSync) btnSync.disabled = true;
            } else {
                statusBadgeIcon.className = "p-3 bg-red-50 dark:bg-red-950/20 text-red-500 rounded-none flex-shrink-0";
                statusTitle.className = "text-base font-extrabold text-red-600 dark:text-red-500";
                statusTitle.textContent = "Koneksi Terputus / Gagal";
                statusDot.className = "w-2.5 h-2.5 rounded-full bg-red-500";
                statusDesc.textContent = "Gagal memverifikasi akses Google API.";
                
                if (details) {
                    errorBlock.textContent = details;
                    errorBlock.classList.remove('hidden');
                }
                if (btnSync) btnSync.disabled = true;
            }
        }

        function runInitialTest() {
            fetch("{{ route('admin.ppdb.google-sheets.test') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateStatusUI('connected', '', data.client_email);
                } else {
                    const spreadsheetInput = document.getElementById('spreadsheet_id');
                    const spreadsheetValue = spreadsheetInput ? spreadsheetInput.value : '';
                    if (!spreadsheetValue || (data.message && data.message.includes('Konfigurasi Google Sheets kosong'))) {
                        updateStatusUI('unconfigured');
                    } else {
                        updateStatusUI('failed', data.message);
                    }
                }
            })
            .catch(err => {
                updateStatusUI('failed', 'Gagal menghubungi server web lokal: ' + err.message);
            });
        }

        runInitialTest();

        if (btnTest) {
            btnTest.addEventListener('click', function() {
                btnTest.disabled = true;
                const icon = document.getElementById('icon-test-conn');
                const label = document.getElementById('label-test-conn');
                if (label) label.textContent = 'Menguji Koneksi...';
                if (icon) icon.classList.add('animate-spin');

                if (statusTitle) statusTitle.textContent = 'Mencoba Menghubungkan...';
                if (statusDot) statusDot.className = 'w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse';

                fetch("{{ route('admin.ppdb.google-sheets.test') }}?force=1", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        updateStatusUI('connected', '', data.client_email);
                        if (window.AppPopup) {
                            AppPopup.success({
                                title: 'Koneksi Sukses',
                                description: 'Koneksi sukses! Konfigurasi API Google Sheets valid dan siap digunakan.'
                            });
                        } else {
                            alert('Koneksi sukses! Konfigurasi API Google Sheets valid.');
                        }
                    } else {
                        updateStatusUI('failed', data.message);
                        if (window.AppPopup) {
                            AppPopup.error({
                                title: 'Koneksi Gagal',
                                description: 'Koneksi gagal! Silakan periksa detail log kesalahan di layar.'
                            });
                        } else {
                            alert('Koneksi gagal! Silakan periksa detail log eror di layar.');
                        }
                    }
                })
                .catch(err => {
                    updateStatusUI('failed', err.message);
                    if (window.AppPopup) {
                        AppPopup.error({
                            title: 'Kesalahan Sistem',
                            description: 'Gagal memproses uji koneksi karena kesalahan jaringan.'
                        });
                    } else {
                        alert('Eror AJAX: Gagal memproses uji koneksi.');
                    }
                })
                .finally(() => {
                    btnTest.disabled = false;
                    if (label) label.textContent = 'Uji Koneksi';
                    if (icon) icon.classList.remove('animate-spin');
                });
            });
        }

        if (btnSync) {
            btnSync.addEventListener('click', function() {
                if (window.AppPopup) {
                    AppPopup.warning({
                        title: 'Konfirmasi Sinkronisasi',
                        description: 'Apakah Anda yakin ingin menyinkronkan total seluruh data pendaftar saat ini ke Google Sheets? Tindakan ini akan mengosongkan sheet dan menulis ulang data terbaru.',
                        confirmText: 'Ya, Lanjutkan',
                        cancelText: 'Batal',
                        onConfirm: function() {
                            btnSync.disabled = true;
                            if (btnTest) btnTest.disabled = true;
                            const icon = document.getElementById('icon-sync-all');
                            const label = document.getElementById('label-sync-all');
                            if (label) label.textContent = 'Sinkronisasi Berjalan...';
                            if (icon) icon.classList.add('animate-spin');

                            fetch("{{ route('admin.ppdb.google-sheets.sync-now') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    AppPopup.success({
                                        title: 'Sinkronisasi Sukses',
                                        description: 'Proses sinkronisasi massal berhasil diselesaikan!'
                                    });
                                } else {
                                    AppPopup.error({
                                        title: 'Sinkronisasi Gagal',
                                        description: 'Gagal menyinkronkan data: ' + data.message
                                    });
                                }
                            })
                            .catch(err => {
                                AppPopup.error({
                                    title: 'Kesalahan Sistem',
                                    description: 'Gagal memproses sinkronisasi massal karena kesalahan jaringan.'
                                });
                                console.error(err);
                            })
                            .finally(() => {
                                btnSync.disabled = false;
                                if (btnTest) btnTest.disabled = false;
                                if (label) label.textContent = 'Sinkronisasi Sekarang';
                                if (icon) icon.classList.remove('animate-spin');
                                runInitialTest();
                            });
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin menyinkronkan data pendaftar saat ini ke Google Sheets?')) {
                        btnSync.disabled = true;
                        if (btnTest) btnTest.disabled = true;
                        const icon = document.getElementById('icon-sync-all');
                        const label = document.getElementById('label-sync-all');
                        if (label) label.textContent = 'Sinkronisasi Berjalan...';
                        if (icon) icon.classList.add('animate-spin');

                        fetch("{{ route('admin.ppdb.google-sheets.sync-now') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert('Sinkronisasi sukses! ' + data.message);
                            } else {
                                alert('Sinkronisasi gagal: ' + data.message);
                            }
                        })
                        .catch(err => {
                            alert('Eror AJAX: Gagal memproses sinkronisasi.');
                            console.error(err);
                        })
                        .finally(() => {
                            btnSync.disabled = false;
                            if (btnTest) btnTest.disabled = false;
                            if (label) label.textContent = 'Sinkronisasi Sekarang';
                            if (icon) icon.classList.remove('animate-spin');
                            runInitialTest();
                        });
                    }
                }
            });
        }
    });
</script>
@endsection
