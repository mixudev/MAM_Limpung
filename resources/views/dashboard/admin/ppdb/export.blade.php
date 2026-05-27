@extends('dashboard.layouts.main')

@section('content')
@include('shared.ppdb.print.background-print')

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Export PPDB';
        }
    });
</script>

<div class="space-y-6">

    <!-- Header Panel -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Export Rekapitulasi Data PPDB</h1>
            <p class="text-sm text-slate-500 dark:text-zinc-400 mt-1">Unduh dan cetak seluruh basis data pendaftar calon siswa baru MAM Limpung secara rapi dan profesional.</p>
        </div>
        <a href="{{ route('admin.ppdb.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700/80 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-bold text-xs rounded-none transition-all text-center">
            Kembali ke Pendaftar
        </a>
    </div>

    @include('dashboard.admin.ppdb.partials.export.stats')

    <!-- Export Configurations Panel -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 rounded-none shadow-sm">
        <form id="ppdb-export-form" action="{{ route('admin.ppdb.export.download') }}" method="POST" target="export-target-iframe" class="space-y-8">
            @csrf

            <!-- STEP 1: Format Selection -->
            <div>
                <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-100 dark:border-zinc-850 pb-3 mb-4">
                    1. Pilih Format Laporan Rekap
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Format Excel -->
                    <label class="relative border border-slate-200 dark:border-zinc-800 hover:border-emerald-500 dark:hover:border-emerald-500 p-5 flex items-start gap-4 cursor-pointer transition-all bg-slate-50/30 dark:bg-zinc-900/30 select-none group">
                        <input type="radio" name="format" value="excel" checked class="mt-1 accent-emerald-600 h-4 w-4" onchange="toggleFormatDetails('excel')">
                        <div class="flex-shrink-0 p-3  text-emerald-600 dark:text-emerald-400 ">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-800 dark:text-white block">Microsoft Excel Spreadsheet (.csv)</span>
                            <span class="text-xs text-slate-400 dark:text-zinc-500 block mt-1">Unduh data mentah yang rapi, kompatibel 100% untuk Microsoft Excel, Google Sheets, dan Numbers untuk kebutuhan audit database.</span>
                        </div>
                    </label>

                    <!-- Format PDF -->
                    <label class="relative border border-slate-200 dark:border-zinc-800 hover:border-red-500 dark:hover:border-red-500 p-5 flex items-start gap-4 cursor-pointer transition-all bg-slate-50/30 dark:bg-zinc-900/30 select-none group">
                        <input type="radio" name="format" value="pdf" class="mt-1 accent-red-600 h-4 w-4" onchange="toggleFormatDetails('pdf')">
                        <div class="flex-shrink-0 p-3 text-red-600 dark:text-red-400 ">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-800 dark:text-white block">Dokumen PDF / Cetak Ledger (.pdf)</span>
                            <span class="text-xs text-slate-400 dark:text-zinc-500 block mt-1">Membuat lembar ledger rekapitulasi data pendaftar resmi yang dioptimalkan untuk pencetakan kertas A4 Lanskap (landscape).</span>
                        </div>
                    </label>
                </div>

                <!-- Dynamic PDF Settings (Visible only when PDF is selected) -->
                <div id="pdf-orientation-wrapper" class="hidden mt-4 p-4 bg-slate-50 dark:bg-zinc-800/40 border border-slate-200 dark:border-zinc-800 rounded-none animate-fadeIn">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-sm font-bold text-slate-800 dark:text-white block">Orientasi Halaman PDF</span>
                            <span class="text-xs text-slate-400 dark:text-zinc-500 block mt-0.5">Pilih tata letak cetak dokumen rekap untuk mengoptimalkan kerapian kolom data Anda.</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-5">
                            <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none group">
                                <input type="radio" name="pdf_orientation" value="portrait" checked class="accent-[#4f45b2] h-4 w-4">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-[#4f45b2] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                                </svg>
                                <span class="font-bold">Portrait (Tegak)</span>
                            </label>
                            <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-zinc-300 cursor-pointer select-none group">
                                <input type="radio" name="pdf_orientation" value="landscape" class="accent-[#4f45b2] h-4 w-4">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-[#4f45b2] transition-colors transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                                </svg>
                                <span class="font-bold">Landscape (Mendatar)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Filters -->
            <div>
                <h3 class="text-xs font-mono font-bold uppercase tracking-widest text-[#4f45b2] dark:text-[#8c84c8] border-b border-slate-100 dark:border-zinc-850 pb-3 mb-4">
                    2. Konfigurasi Filter Laporan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tahun Ajaran -->
                    <div>
                        <label for="tahun_ajaran" class="text-[10px] font-mono font-bold  tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Tahun Pelajaran</label>
                        <select name="tahun_ajaran" id="tahun_ajaran" class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ $selectedYear === $yr ? 'selected' : '' }}>
                                    Tahun Pelajaran {{ $yr }}/{{ $yr + 1 }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Kelulusan -->
                    <div>
                        <label for="status" class="text-[10px] font-mono font-bold  tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">Status Kelulusan Calon Siswa</label>
                        <select name="status" id="status" class="w-full py-2 px-3 text-sm bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-[#4f45b2]/20 focus:border-[#4f45b2]">
                            <option value="">Semua Pendaftar (Menunggu, Diterima, Ditolak)</option>
                            <option value="pending">Hanya Menunggu Verifikasi</option>
                            <option value="diterima">Hanya Terverifikasi (Diterima)</option>
                            <option value="ditolak">Hanya Ditolak</option>
                        </select>
                    </div>
                </div>
            </div>

            @include('dashboard.admin.ppdb.partials.export.fields')

            <!-- Download Button -->
            <div class="border-t border-slate-100 dark:border-zinc-850 pt-6 flex justify-end">
                <button type="submit" class="py-3 px-8 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-bold text-xs uppercase tracking-wider rounded-none transition-all active:scale-[.97] shadow-md flex items-center gap-2.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Mulai Export & Unduh Laporan</span>
                </button>
            </div>

        </form>
    </div>

</div>

<!-- Hidden iframe for in-page background processing -->
<iframe name="export-target-iframe" id="export-target-iframe" style="position: absolute; top: -9999px; left: -9999px; width: 1px; height: 1px; visibility: hidden; border: none;"></iframe>

<!-- Beautiful Premium Toast Notification -->
<div id="export-toast" class="fixed top-5 right-5 z-[9999] bg-slate-900 text-white text-xs font-semibold py-3 px-5 shadow-2xl border border-zinc-850 flex items-center gap-3 transition-all duration-300 transform translate-y-[-100px] opacity-0 pointer-events-none">
    <svg class="animate-spin h-4 w-4 text-[#8c84c8]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <span>Sedang menyiapkan dokumen untuk dicetak...</span>
</div>

<script>
    function toggleAllCheckboxes(checkedState) {
        document.querySelectorAll('input[type="checkbox"][name="fields[]"]').forEach(box => {
            box.checked = checkedState;
        });
    }

    function toggleFormatDetails(format) {
        const pdfWrapper = document.getElementById('pdf-orientation-wrapper');
        const form = document.getElementById('ppdb-export-form');
        if (format === 'pdf') {
            if (pdfWrapper) {
                pdfWrapper.classList.remove('hidden');
            }
            if (form) {
                form.removeAttribute('target');
            }
        } else {
            if (pdfWrapper) {
                pdfWrapper.classList.add('hidden');
            }
            if (form) {
                form.setAttribute('target', 'export-target-iframe');
            }
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('ppdb-export-form');
        const toast = document.getElementById('export-toast');

        // Dynamically bind target state on initial page load based on active checkbox selection
        const checkedFormat = document.querySelector('input[name="format"]:checked');
        if (checkedFormat && form) {
            toggleFormatDetails(checkedFormat.value);
        }

        // Add explicit change listeners to format radios to keep state synchronous
        document.querySelectorAll('input[name="format"]').forEach(radio => {
            radio.addEventListener('change', function() {
                toggleFormatDetails(this.value);
            });
        });

        if (form) {
            form.addEventListener('submit', async function (event) {
                const checkedRadio = document.querySelector('input[name="format"]:checked');
                const formatVal = checkedRadio ? checkedRadio.value : 'excel';

                if (formatVal !== 'pdf') {
                    form.setAttribute('target', 'export-target-iframe');
                    return;
                }

                event.preventDefault();

                if (!window.PpdbBackgroundPrint) {
                    return;
                }

                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'text/html',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Export PDF gagal');
                    }

                    const html = await response.text();
                    await PpdbBackgroundPrint.printHtml(html, {
                        loadingLabel: 'Menyiapkan laporan PDF...',
                        errorLabel: 'Gagal mencetak laporan.',
                    });
                } catch (error) {
                    console.error(error);
                    if (toast) {
                        toast.classList.remove('translate-y-[-100px]', 'opacity-0', 'pointer-events-none');
                        setTimeout(function () {
                            toast.classList.add('translate-y-[-100px]', 'opacity-0', 'pointer-events-none');
                        }, 3000);
                    }
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                }
            });
        }
    });
</script>
@endsection
