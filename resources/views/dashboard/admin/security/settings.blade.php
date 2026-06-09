@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) breadcrumb.textContent = 'Keamanan — Kredensial';
    });
</script>

<div class="max-w-5xl space-y-6">
    {{-- Header --}}
    <div class="bg-linear-to-r from-[#4f45b2] via-[#6366f1] to-indigo-700 dark:from-zinc-900 dark:to-zinc-950 p-6 border-b-4 border-indigo-500 shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4 text-white">
        <div>
            <h1 class="text-xl font-bold tracking-tight">Pusat Keamanan & Kredensial</h1>
            <p class="text-xs text-indigo-100 dark:text-zinc-400 mt-1">Kelola kredensial Google API dan konfigurasi SMTP email secara terpusat dan terenkripsi.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="flex h-3 w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-mono font-bold tracking-wider uppercase bg-white/10 px-3 py-1.5 border border-white/20">Sistem Aktif</span>
        </div>
    </div>

    {{-- Alerts --}}

    @if($errors->any())
    <div class="bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 p-4 shadow-sm space-y-1">
        <div class="flex items-center gap-3">
            <div class="p-1 bg-rose-500 text-white rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div>
            <p class="text-xs font-bold text-rose-800 dark:text-rose-300">Terjadi Kesalahan!</p>
        </div>
        <ul class="list-disc list-inside text-[11px] text-rose-600 dark:text-rose-400/90 pl-8">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Tabs Card --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        {{-- Tab Navigation --}}
        <div class="flex border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 overflow-x-auto">
            <button type="button" onclick="switchTab('tab-google')" id="btn-tab-google"
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-indigo-600 text-indigo-600 dark:text-white focus:outline-none whitespace-nowrap">
                <i class="fa-brands fa-google mr-2"></i> Kredensial Google API
            </button>
            <button type="button" onclick="switchTab('tab-smtp')" id="btn-tab-smtp"
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-envelope mr-2"></i> Kredensial SMTP Email
            </button>
        </div>

        <div class="p-6">
            {{-- TAB 1: GOOGLE SERVICE ACCOUNT --}}
            <div id="tab-google" class="tab-content space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="space-y-4">
                        <div class="p-4 bg-indigo-50/50 dark:bg-zinc-950 border border-indigo-100 dark:border-zinc-800 text-xs space-y-3">
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold font-mono tracking-wider uppercase block text-[10px]">INTEGRASI KREDENSIAL TERPUSAT</span>
                            <p class="text-slate-600 dark:text-zinc-400 leading-relaxed">Google Service Account JSON tersimpan terenkripsi AES-256. Digunakan oleh seluruh layanan Google di sistem ini.</p>

                            <div class="pt-3 border-t border-slate-200 dark:border-zinc-800">
                                <span class="text-slate-500 dark:text-zinc-400 font-semibold block mb-1">Status Kredensial:</span>
                                @if($hasGoogleCredentials)
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-check"></i> Tersimpan Secara Aman
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-exclamation"></i> Belum Dikonfigurasi
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-[11px] space-y-2">
                            <span class="text-slate-700 dark:text-zinc-350 font-bold uppercase tracking-wider block">Panduan GCP Console:</span>
                            <ol class="list-decimal list-inside space-y-1.5 text-slate-500 dark:text-zinc-400">
                                <li>Buat project baru di GCP Console.</li>
                                <li>Aktifkan Google Sheets API & Drive API.</li>
                                <li>Buat Service Account, unduh kunci <span class="font-mono bg-slate-200 dark:bg-zinc-800 px-1">JSON</span>.</li>
                                <li>Salin isi JSON ke editor di kanan.</li>
                            </ol>
                        </div>
                    </div>
                    <div class="lg:col-span-2 space-y-4">
                        <form action="{{ route('admin.security.credentials.update') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Kunci Service Account (JSON)</label>
                                <textarea name="google_service_account_json" rows="10"
                                    placeholder='Tempelkan isi file JSON kredensial Anda di sini. Format: { "type": "service_account", "project_id": ... }'
                                    class="w-full font-mono text-xs px-3 py-2.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-y">{{ old('google_service_account_json', $maskedGoogleJson) }}</textarea>
                            </div>
                            @if($hasGoogleCredentials)
                            <div class="p-3.5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 flex items-center justify-between">
                                <div class="space-y-0.5">
                                    <span class="text-[10px] font-mono font-bold uppercase text-slate-400 dark:text-zinc-500 block">EMAIL SERVICE ACCOUNT AKTIF:</span>
                                    <span class="text-xs font-mono font-semibold text-slate-700 dark:text-zinc-300">{{ $clientEmail }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400 dark:text-zinc-500 italic max-w-xs text-right">Bagikan hak akses edit Sheets & Drive ke email ini.</div>
                            </div>
                            @endif
                            <div class="flex justify-end pt-2">
                                <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#6366f1] text-white font-mono font-bold text-xs uppercase tracking-wider transition-all shadow-sm">
                                    Simpan Kredensial Google
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- TAB 2: SMTP EMAIL --}}
            <div id="tab-smtp" class="tab-content hidden space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Left: Info --}}
                    <div class="space-y-4">
                        <div class="p-4 bg-indigo-50/50 dark:bg-zinc-950 border border-indigo-100 dark:border-zinc-800 text-xs space-y-3">
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold font-mono tracking-wider uppercase block text-[10px]">KONFIGURASI EMAIL TERPUSAT</span>
                            <p class="text-slate-600 dark:text-zinc-400 leading-relaxed">Konfigurasi SMTP memungkinkan sistem mengirim email notifikasi secara otomatis. Password tersimpan terenkripsi.</p>

                            <div class="pt-3 border-t border-slate-200 dark:border-zinc-800">
                                <span class="text-slate-500 dark:text-zinc-400 font-semibold block mb-1">Status SMTP:</span>
                                @if($hasSmtpCredentials)
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-check"></i> Terkonfigurasi
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-exclamation"></i> Belum Dikonfigurasi
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 text-[11px] space-y-2">
                            <span class="text-slate-700 dark:text-zinc-350 font-bold uppercase tracking-wider block">Contoh SMTP Provider:</span>
                            <ul class="space-y-2 text-slate-500 dark:text-zinc-400">
                                <li class="flex justify-between"><span class="font-mono font-bold">Gmail</span><span>smtp.gmail.com:587</span></li>
                                <li class="flex justify-between"><span class="font-mono font-bold">Outlook</span><span>smtp.office365.com:587</span></li>
                                <li class="flex justify-between"><span class="font-mono font-bold">Mailtrap</span><span>sandbox.smtp.mailtrap.io:2525</span></li>
                            </ul>
                        </div>

                        {{-- Test SMTP --}}
                        @if($hasSmtpCredentials)
                        <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 space-y-3">
                            <span class="text-slate-700 dark:text-zinc-300 font-bold text-xs uppercase tracking-wider block">Uji Koneksi SMTP</span>
                            <input type="email" id="smtp-test-email" value="{{ auth()->user()->email }}"
                                placeholder="Email penerima uji coba"
                                class="w-full font-mono text-xs px-3 py-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <button type="button" onclick="testSmtpConnection()"
                                    id="smtp-test-btn"
                                    class="w-full py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-mono font-bold text-[10px] uppercase tracking-wider transition-all shadow-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane" id="smtp-test-icon"></i> Kirim Email Uji
                            </button>
                            <div id="smtp-test-result" class="hidden text-[11px] font-semibold"></div>
                        </div>
                        @endif
                    </div>

                    {{-- Right: Form --}}
                    <div class="lg:col-span-2">
                        <form action="{{ route('admin.security.smtp.update') }}" method="POST" class="space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">SMTP Host <span class="text-rose-500">*</span></label>
                                    <input type="text" name="host" value="{{ old('host', $smtpCredentials['host']) }}"
                                           placeholder="smtp.gmail.com"
                                           class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Port <span class="text-rose-500">*</span></label>
                                    <input type="number" name="port" value="{{ old('port', $smtpCredentials['port']) }}"
                                           placeholder="587"
                                           class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Username / Email <span class="text-rose-500">*</span></label>
                                    <input type="text" name="username" value="{{ old('username', $smtpCredentials['username']) }}"
                                           placeholder="email@sekolah.ac.id"
                                           class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Password / App Password</label>
                                    <input type="password" name="password"
                                           placeholder="{{ $hasSmtpCredentials ? '(kosongkan jika tidak diubah)' : 'App Password atau Password Email' }}"
                                           class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 mt-1 block">Untuk Gmail: gunakan App Password (bukan password akun biasa).</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Enkripsi Koneksi <span class="text-rose-500">*</span></label>
                                <div class="flex gap-4">
                                    @foreach(['tls' => 'TLS (Disarankan)', 'ssl' => 'SSL', 'none' => 'None (Tidak Aman)'] as $val => $label)
                                    <label class="flex items-center gap-2 cursor-pointer select-none" style="display:flex!important;margin-bottom:0!important;">
                                        <input type="radio" name="encryption" value="{{ $val }}" {{ old('encryption', $smtpCredentials['encryption']) === $val ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-slate-700 dark:text-zinc-300">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="h-px bg-slate-100 dark:bg-zinc-800"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">From Address <span class="text-rose-500">*</span></label>
                                    <input type="email" name="from_address" value="{{ old('from_address', $smtpCredentials['from_address']) }}"
                                           placeholder="no-reply@mamlimpung.sch.id"
                                           class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">From Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="from_name" value="{{ old('from_name', $smtpCredentials['from_name']) }}"
                                           placeholder="MAM Limpung"
                                           class="w-full font-mono text-sm px-3 py-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                </div>
                            </div>
                            <div class="flex justify-end pt-2">
                                <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#6366f1] text-white font-mono font-bold text-xs uppercase tracking-wider transition-all shadow-sm">
                                    Simpan Konfigurasi SMTP
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-indigo-600', 'text-indigo-600', 'dark:text-white');
            btn.classList.add('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        });
        document.getElementById(tabId).classList.remove('hidden');
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('border-transparent', 'text-slate-400', 'dark:text-zinc-500');
        activeBtn.classList.add('border-indigo-600', 'text-indigo-600', 'dark:text-white');
    }

    function testSmtpConnection() {
        const btn = document.getElementById('smtp-test-btn');
        const icon = document.getElementById('smtp-test-icon');
        const result = document.getElementById('smtp-test-result');
        const email = document.getElementById('smtp-test-email').value;

        if (!email) { alert('Masukkan alamat email tujuan uji coba.'); return; }

        btn.disabled = true;
        icon.className = 'fa-solid fa-spinner fa-spin';
        result.className = 'hidden text-[11px] font-semibold';

        fetch("{{ route('admin.security.smtp.test') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ test_email: email })
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            icon.className = 'fa-solid fa-paper-plane';
            result.className = 'text-[11px] font-semibold ' + (data.success ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400');
            result.textContent = (data.success ? '✓ ' : '✗ ') + data.message;
        })
        .catch(err => {
            btn.disabled = false;
            icon.className = 'fa-solid fa-paper-plane';
            result.className = 'text-[11px] font-semibold text-rose-600';
            result.textContent = '✗ Error: ' + err.message;
        });
    }

    // Switch to SMTP tab if there are SMTP errors
    @if($errors->has('host') || $errors->has('port') || $errors->has('username') || $errors->has('encryption') || $errors->has('from_address') || $errors->has('from_name'))
        document.addEventListener('DOMContentLoaded', () => switchTab('tab-smtp'));
    @endif
</script>
@endsection
