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
    <div class="bg-linear-to-r from-[#4f45b2] via-[#6366f1] to-indigo-700 dark:from-zinc-900 dark:to-zinc-950 p-6 border-b-4 border-indigo-500 shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4 text-slate-600 dark:text-white">
        <div>
            <h1 class="text-xl font-bold tracking-tight">Pusat Keamanan & Kredensial</h1>
            <p class="text-xs text-slate-400 dark:text-zinc-400 mt-1">Kelola kredensial Google API dan konfigurasi SMTP email secara terpusat dan terenkripsi.</p>
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
            <button type="button" onclick="switchTab('tab-google-drive')" id="btn-tab-google-drive"
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 focus:outline-none whitespace-nowrap">
                <i class="fa-brands fa-google-drive mr-2"></i> Google Drive Backup
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

            {{-- TAB 2: GOOGLE DRIVE OAUTH2 (Personal Account) --}}
            <div id="tab-google-drive" class="tab-content hidden space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Left: Status & Tutorial --}}
                    <div class="space-y-4">
                        {{-- Status .env credentials --}}
                        <div class="p-4 bg-indigo-50/50 dark:bg-zinc-950 border border-indigo-100 dark:border-zinc-800 text-xs space-y-3">
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold font-mono tracking-wider uppercase block text-[10px]">BACKUP KE GOOGLE DRIVE PERSONAL</span>
                            <p class="text-slate-600 dark:text-zinc-400 leading-relaxed">Metode ini menggunakan OAuth2 dan berfungsi dengan akun Gmail biasa — tidak memerlukan Google Workspace.</p>

                            <div class="pt-3 border-t border-slate-200 dark:border-zinc-800 space-y-2">
                                <span class="text-slate-500 dark:text-zinc-400 font-semibold block">Kredensial di <span class="font-mono">.env</span>:</span>
                                @if($hasOAuth2EnvCredentials)
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-check"></i> Client ID & Secret Tersedia
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-xmark"></i> Belum Dikonfigurasi di .env
                                    </div>
                                    <p class="text-[10px] text-slate-500 dark:text-zinc-400 mt-1">Isi <span class="font-mono">GOOGLE_DRIVE_OAUTH2_CLIENT_ID</span> dan <span class="font-mono">GOOGLE_DRIVE_OAUTH2_CLIENT_SECRET</span> di file <span class="font-mono">.env</span>.</p>
                                @endif

                                <span class="text-slate-500 dark:text-zinc-400 font-semibold block mt-3">Status Otorisasi:</span>
                                @if($hasOAuth2Credentials)
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-check"></i> Sudah Diotorisasi
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-exclamation"></i> Belum Diotorisasi
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Revoke --}}
                        @if($hasOAuth2Credentials)
                        <div class="p-4 bg-rose-50/50 dark:bg-zinc-950 border border-rose-200 dark:border-zinc-800 space-y-3">
                            <span class="text-rose-700 dark:text-rose-400 font-bold text-xs uppercase tracking-wider block">Cabut Otorisasi</span>
                            <p class="text-[11px] text-slate-500 dark:text-zinc-400">Menghapus refresh token dan mencabut akses ke Google Drive.</p>
                            <form action="{{ route('admin.security.google-drive.revoke') }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Yakin cabut otorisasi Google Drive?')"
                                        class="w-full py-2 px-4 bg-rose-600 hover:bg-rose-700 text-white font-mono font-bold text-[10px] uppercase tracking-wider transition-all shadow-sm flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-link-slash"></i> Cabut Otorisasi
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    {{-- Right: Tutorial & Action --}}
                    <div class="lg:col-span-2 space-y-6">
                        @if($errors->has('google_oauth2'))
                        <div class="p-4 bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 text-xs text-rose-700 dark:text-rose-400">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ $errors->first('google_oauth2') }}
                        </div>
                        @endif

                        {{-- Tutorial --}}
                        <div class="p-5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-600 flex items-center justify-center shrink-0">
                                    <i class="fa-brands fa-google text-white text-sm"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 block">Langkah 1: Konfigurasi Google Cloud Console</span>
                                    <span class="text-[10px] text-slate-500 dark:text-zinc-400">Lakukan sekali saja, hasilnya diisi ke <span class="font-mono">.env</span> server.</span>
                                </div>
                            </div>
                            <ol class="list-decimal list-inside space-y-2 text-[11px] text-slate-500 dark:text-zinc-400 pl-1">
                                <li>Buka <a href="https://console.cloud.google.com/" target="_blank" class="underline text-indigo-500">Google Cloud Console</a>.</li>
                                <li>Aktifkan <span class="font-mono bg-slate-200 dark:bg-zinc-800 px-1">Google Drive API</span>.</li>
                                <li>Buat Credentials → <span class="font-mono bg-slate-200 dark:bg-zinc-800 px-1">OAuth 2.0 Client IDs</span>, pilih tipe <span class="font-mono bg-slate-200 dark:bg-zinc-800 px-1">Web application</span>.</li>
                                <li>Tambahkan Authorized redirect URI:
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="font-mono bg-slate-200 dark:bg-zinc-800 px-2 py-1 text-[10px] break-all text-slate-700 dark:text-zinc-300">{{ route('admin.security.google-drive.callback') }}</span>
                                    </div>
                                </li>
                                <li>Salin Client ID & Secret, lalu isi di file <span class="font-mono bg-slate-200 dark:bg-zinc-800 px-1">.env</span> server:
                                    <div class="mt-2 p-3 bg-zinc-900 border border-zinc-700 font-mono text-[10px] text-emerald-400 space-y-1">
                                        <div>GOOGLE_DRIVE_OAUTH2_CLIENT_ID=<span class="text-zinc-400">your-client-id.apps.googleusercontent.com</span></div>
                                        <div>GOOGLE_DRIVE_OAUTH2_CLIENT_SECRET=<span class="text-zinc-400">your-client-secret</span></div>
                                    </div>
                                </li>
                                <li>Jalankan <span class="font-mono bg-slate-200 dark:bg-zinc-800 px-1">php artisan config:clear</span> setelah mengisi <span class="font-mono">.env</span>.</li>
                            </ol>

                            <div class="border-t border-slate-200 dark:border-zinc-800 pt-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-8 h-8 bg-emerald-600 flex items-center justify-center shrink-0">
                                        <i class="fa-brands fa-google-drive text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 block">Langkah 2: Otorisasi Akses ke Google Drive</span>
                                        <span class="text-[10px] text-slate-500 dark:text-zinc-400">Klik tombol di bawah setelah <span class="font-mono">.env</span> terisi. Anda akan diarahkan ke halaman izin Google.</span>
                                    </div>
                                </div>
                                @if($hasOAuth2EnvCredentials)
                                <form action="{{ route('admin.security.google-drive.authorize') }}" method="POST" data-turbo="false">
                                    @csrf
                                    <button type="submit"
                                            class="w-full py-2.5 px-6 bg-[#4f45b2] hover:bg-[#6366f1] text-white font-mono font-bold text-xs uppercase tracking-wider transition-all shadow-sm flex items-center justify-center gap-2">
                                        <i class="fa-brands fa-google"></i>
                                        {{ $hasOAuth2Credentials ? 'Otorisasi Ulang Google Drive' : 'Otorisasi Google Drive' }}
                                    </button>
                                </form>
                                @else
                                <div class="w-full py-2.5 px-6 bg-slate-300 dark:bg-zinc-800 text-slate-500 dark:text-zinc-500 font-mono font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 cursor-not-allowed select-none">
                                    <i class="fa-solid fa-lock"></i> Isi .env Terlebih Dahulu
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($hasOAuth2Credentials)
                        <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/10 border border-emerald-200 dark:border-emerald-900 space-y-2">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400"></i>
                                <span class="text-xs font-mono font-bold text-emerald-700 dark:text-emerald-400 uppercase">Google Drive Siap Digunakan untuk Backup</span>
                            </div>
                            <p class="text-[11px] text-slate-600 dark:text-zinc-400">Refresh token tersimpan. Aktifkan "Sinkronisasi Google Drive" di halaman <a href="{{ route('admin.backup.index') }}" class="underline text-indigo-500">Backup</a> untuk mulai menggunakan.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TAB 3: SMTP EMAIL --}}
            <div id="tab-smtp" class="tab-content hidden space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Left: Status --}}
                    <div class="space-y-4">
                        <div class="p-4 bg-indigo-50/50 dark:bg-zinc-950 border border-indigo-100 dark:border-zinc-800 text-xs space-y-3">
                            <span class="text-indigo-600 dark:text-indigo-400 font-bold font-mono tracking-wider uppercase block text-[10px]">KONFIGURASI EMAIL TERPUSAT</span>
                            <p class="text-slate-600 dark:text-zinc-400 leading-relaxed">Konfigurasi SMTP dikelola melalui file <span class="font-mono">.env</span> server. Tidak ada data sensitif yang disimpan di database.</p>

                            <div class="pt-3 border-t border-slate-200 dark:border-zinc-800 space-y-2">
                                <span class="text-slate-500 dark:text-zinc-400 font-semibold block">Status SMTP:</span>
                                @if($hasSmtpCredentials)
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-check"></i> Terkonfigurasi
                                    </div>
                                    <div class="mt-2 space-y-1 text-[10px] font-mono text-slate-500 dark:text-zinc-500">
                                        <div>Host: <span class="text-slate-700 dark:text-zinc-300">{{ $smtpConfig['host'] }}:{{ $smtpConfig['port'] }}</span></div>
                                        <div>User: <span class="text-slate-700 dark:text-zinc-300">{{ $smtpConfig['username'] }}</span></div>
                                        <div>From: <span class="text-slate-700 dark:text-zinc-300">{{ $smtpConfig['from_address'] }}</span></div>
                                        <div>Enkripsi: <span class="text-slate-700 dark:text-zinc-300 uppercase">{{ $smtpConfig['encryption'] }}</span></div>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/25 text-[10px] font-mono font-bold uppercase">
                                        <i class="fa-solid fa-circle-xmark"></i> Belum Dikonfigurasi
                                    </div>
                                    <p class="text-[10px] text-slate-500 dark:text-zinc-400 mt-1">Isi variabel <span class="font-mono">MAIL_*</span> di file <span class="font-mono">.env</span>.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Test SMTP --}}
                        @if($hasSmtpCredentials)
                        <div class="p-4 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 space-y-3">
                            <span class="text-slate-700 dark:text-zinc-300 font-bold text-xs uppercase tracking-wider block">Uji Koneksi SMTP</span>
                            <input type="email" id="smtp-test-email" value="{{ auth()->user()->email }}"
                                placeholder="Email penerima uji coba"
                                class="w-full font-mono text-xs px-3 py-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <p class="text-[10px] text-slate-400 dark:text-zinc-500">Isi email tujuan. Default email akun Anda sendiri.</p>
                            <button type="button" onclick="testSmtpConnection()"
                                    id="smtp-test-btn"
                                    class="w-full py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-mono font-bold text-[10px] uppercase tracking-wider transition-all shadow-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane" id="smtp-test-icon"></i> Kirim Email Uji
                            </button>
                            <div id="smtp-test-result" class="hidden text-[11px] font-semibold"></div>
                        </div>
                        @endif
                    </div>

                    {{-- Right: Tutorial --}}
                    <div class="lg:col-span-2 space-y-5">
                        <div class="p-5 bg-slate-50 dark:bg-zinc-950 border border-slate-200 dark:border-zinc-800 space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-envelope text-white text-sm"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-mono font-bold text-slate-800 dark:text-zinc-200 block">Cara Mengkonfigurasi SMTP</span>
                                    <span class="text-[10px] text-slate-500 dark:text-zinc-400">Isi variabel berikut di file <span class="font-mono">.env</span> server, lalu jalankan <span class="font-mono">php artisan config:clear</span>.</span>
                                </div>
                            </div>

                            <div class="p-4 bg-zinc-900 border border-zinc-700 font-mono text-[11px] space-y-1 text-emerald-400">
                                <div class="text-zinc-500 mb-2"># Contoh konfigurasi Gmail dengan App Password</div>
                                <div>MAIL_MAILER=<span class="text-amber-300">smtp</span></div>
                                <div>MAIL_SCHEME=<span class="text-zinc-400">null</span></div>
                                <div>MAIL_HOST=<span class="text-amber-300">smtp.gmail.com</span></div>
                                <div>MAIL_PORT=<span class="text-amber-300">587</span></div>
                                <div>MAIL_USERNAME=<span class="text-amber-300">email@sekolah.ac.id</span></div>
                                <div>MAIL_PASSWORD=<span class="text-amber-300">xxxx-xxxx-xxxx-xxxx</span></div>
                                <div>MAIL_FROM_ADDRESS=<span class="text-amber-300">"no-reply@mamlimpung.sch.id"</span></div>
                                <div>MAIL_FROM_NAME=<span class="text-amber-300">"MAM Limpung"</span></div>
                            </div>

                            <ol class="list-decimal list-inside space-y-2 text-[11px] text-slate-500 dark:text-zinc-400 pl-1">
                                <li>Isi semua variabel <span class="font-mono">MAIL_*</span> di file <span class="font-mono">.env</span> server.</li>
                                <li>Untuk Gmail, aktifkan <strong>2-Step Verification</strong> lalu buat <strong>App Password</strong> di <a href="https://myaccount.google.com/apppasswords" target="_blank" class="underline text-indigo-500">myaccount.google.com/apppasswords</a>. Gunakan App Password sebagai <span class="font-mono">MAIL_PASSWORD</span>.</li>
                                <li>Jalankan <span class="font-mono bg-slate-200 dark:bg-zinc-800 px-1">php artisan config:clear</span> setelah mengubah <span class="font-mono">.env</span>.</li>
                                <li>Gunakan tombol <strong>Uji Koneksi SMTP</strong> di panel kiri untuk memverifikasi.</li>
                            </ol>

                            <div class="border-t border-slate-200 dark:border-zinc-800 pt-4 text-[11px] space-y-2">
                                <span class="text-slate-700 dark:text-zinc-300 font-bold uppercase tracking-wider block text-[10px]">Contoh SMTP Provider Lain:</span>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach(['Gmail' => 'smtp.gmail.com:587', 'Outlook' => 'smtp.office365.com:587', 'Mailtrap' => 'sandbox.smtp.mailtrap.io:2525'] as $provider => $server)
                                    <div class="p-2.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-center">
                                        <span class="font-mono font-bold text-slate-700 dark:text-zinc-300 block text-[11px]">{{ $provider }}</span>
                                        <span class="text-[10px] text-slate-400 dark:text-zinc-500">{{ $server }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
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
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ test_email: email })
        })
        .then(r => {
            if (!r.ok && r.headers.get('content-type')?.includes('text/html')) {
                throw new Error('Sesi habis atau tidak diizinkan. Silakan refresh halaman dan coba lagi.');
            }
            return r.json();
        })
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

    @if($errors->has('google_oauth2'))
        document.addEventListener('DOMContentLoaded', () => switchTab('tab-google-drive'));
    @endif
</script>
@endsection