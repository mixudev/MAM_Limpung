@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const breadcrumb = document.getElementById('breadcrumb');
        if (breadcrumb) {
            breadcrumb.textContent = 'Pengaturan Website';
        }
    });
</script>

<div class="max-w-6xl space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-900 p-6 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Pengaturan Website</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Kelola informasi sekolah, detail kontak, media sosial, dan metadata SEO untuk frontend.</p>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/60 p-4 text-emerald-800 dark:text-emerald-400 text-xs font-semibold rounded-none flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Alert Errors -->
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

    <!-- Form & Tabs Card -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-none shadow-sm overflow-hidden">
        
        <!-- Tab Headers -->
        <div class="flex border-b border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 overflow-x-auto">
            <button type="button" onclick="switchTab('tab-general'); return false;" id="btn-tab-general" 
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-indigo-600 text-indigo-600 dark:text-white focus:outline-none whitespace-nowrap">
                Profil & SEO
            </button>
            <button type="button" onclick="switchTab('tab-contact'); return false;" id="btn-tab-contact" 
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 focus:outline-none whitespace-nowrap">
                Kontak & Lokasi
            </button>
            <button type="button" onclick="switchTab('tab-social'); return false;" id="btn-tab-social" 
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 focus:outline-none whitespace-nowrap">
                Media Sosial
            </button>
            <button type="button" onclick="switchTab('tab-headmaster'); return false;" id="btn-tab-headmaster" 
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 focus:outline-none whitespace-nowrap">
                Kepala Sekolah
            </button>
            <button type="button" onclick="switchTab('tab-school-data'); return false;" id="btn-tab-school-data" 
                    class="tab-btn cursor-pointer px-6 py-3.5 text-xs font-mono font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-300 focus:outline-none whitespace-nowrap">
                Data Sekolah
            </button>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. TAB: GENERAL -->
            <div id="tab-general" class="tab-content space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Column 1: Logo Drag & Drop -->
                    <div class="flex flex-col space-y-4">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                            Logo Sekolah
                        </label>
                        
                        <div id="dropzone" 
                             class="relative border-2 border-dashed border-slate-300 dark:border-zinc-700 hover:border-indigo-400 dark:hover:border-zinc-500 bg-slate-50 dark:bg-zinc-950 p-6 flex flex-col items-center justify-center min-h-[220px] text-center cursor-pointer transition-all group">
                            
                            <input type="file" name="logo" id="fileInput" class="hidden" accept="image/*" />
                            
                            <!-- State Empty/Upload -->
                            <div id="upload-state" class="{{ $siteSetting->logo_path ? 'hidden' : '' }} space-y-3 py-4">
                                <div class="p-3 bg-indigo-50 dark:bg-zinc-900 rounded-full inline-block group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="text-xs">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-bold">Klik untuk unggah</span> atau seret dan lepas gambar ke sini
                                </div>
                                <p class="text-[10px] text-slate-400 dark:text-zinc-650">PNG, JPG, JPEG, atau WEBP hingga 2MB</p>
                            </div>

                            <!-- State Preview -->
                            <div id="preview-state" class="{{ $siteSetting->logo_path ? '' : 'hidden' }} w-full flex flex-col items-center justify-center py-2 relative">
                                <div class="w-32 h-32 relative border border-slate-200 dark:border-zinc-800 bg-white p-2">
                                    <img id="logo-preview" 
                                         src="{{ $siteSetting->logo_path ? asset('storage/' . $siteSetting->logo_path) : '' }}" 
                                         class="w-full h-full object-contain" />
                                </div>
                                <button type="button" onclick="resetFileSelection()" class="mt-4 py-1.5 px-3 bg-rose-600 hover:bg-rose-700 text-white font-mono text-[10px] font-bold uppercase tracking-wider transition-colors">
                                    Ganti Logo
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 & 3: Info & SEO -->
                    <div class="lg:col-span-2 space-y-4">
                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nama Sekolah / Website <span class="text-rose-500">*</span></label>
                            <input type="text" name="school_name" value="{{ old('school_name', $siteSetting->school_name) }}" required placeholder="Contoh: MAM Limpung"
                                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                        </div>

                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Profil Singkat Sekolah</label>
                            <textarea name="about_short" rows="4" placeholder="Teks singkat tentang sekolah yang tampil di footer..."
                                      class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-y">{{ old('about_short', $siteSetting->about_short) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Meta Title (SEO)</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title', $siteSetting->meta_title) }}" placeholder="MAM Limpung - Unggul dan Berprestasi"
                                       class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                            </div>

                            <div>
                                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Meta Description (SEO)</label>
                                <input type="text" name="meta_description" value="{{ old('meta_description', $siteSetting->meta_description) }}" placeholder="Deskripsi pencarian Google..."
                                       class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. TAB: CONTACT -->
            <div id="tab-contact" class="tab-content hidden space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Email Sekolah</label>
                        <input type="email" name="email" value="{{ old('email', $siteSetting->email) }}" placeholder="info@mamlimpung.sch.id"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nomor Telepon Kantor</label>
                        <input type="text" name="phone" value="{{ old('phone', $siteSetting->phone) }}" placeholder="+62 21 1234 5678"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">WhatsApp Official</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $siteSetting->whatsapp) }}" placeholder="628123456789"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Alamat Lengkap Sekolah</label>
                    <textarea name="address" rows="3" placeholder="Jl. Cokronegoro No.34, Gepor, Limpung..."
                              class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-y">{{ old('address', $siteSetting->address) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Google Maps Embed Iframe Code</label>
                    <textarea name="google_maps_iframe" rows="4" placeholder="Masukkan tag <iframe> dari Google Maps..."
                              class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-y">{{ old('google_maps_iframe', $siteSetting->google_maps_iframe) }}</textarea>
                    <p class="text-[10px] text-slate-400 dark:text-zinc-600 mt-1">Petunjuk: Cari lokasi di Google Maps -> Bagikan -> Sematkan Peta -> Salin HTML.</p>
                </div>
            </div>

            <!-- 3. TAB: SOCIAL MEDIA -->
            <div id="tab-social" class="tab-content hidden space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Link Facebook</label>
                        <input type="url" name="facebook_url" value="{{ old('facebook_url', $siteSetting->facebook_url) }}" placeholder="https://facebook.com/nama-sekolah"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Link Instagram</label>
                        <input type="url" name="instagram_url" value="{{ old('instagram_url', $siteSetting->instagram_url) }}" placeholder="https://instagram.com/nama-sekolah"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Link YouTube Channel</label>
                        <input type="url" name="youtube_url" value="{{ old('youtube_url', $siteSetting->youtube_url) }}" placeholder="https://youtube.com/c/nama-channel"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Link Twitter / X</label>
                        <input type="url" name="twitter_url" value="{{ old('twitter_url', $siteSetting->twitter_url) }}" placeholder="https://x.com/nama-sekolah"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                    </div>
                </div>
            </div>

            <!-- 4. TAB: KEPALA SEKOLAH -->
            <div id="tab-headmaster" class="tab-content hidden space-y-6">
                <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800 rounded-md p-4 text-xs text-blue-800 dark:text-blue-300 flex items-start gap-2">
                    <i class="fa-solid fa-info-circle flex-shrink-0 mt-0.5"></i>
                    <span>Lengkapi data kepala sekolah untuk dokumen resmi sekolah dan surat-surat penting.</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Column 1: Signature Drag & Drop -->
                    <div class="flex flex-col space-y-4">
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                            Tanda Tangan Kepala Sekolah
                        </label>
                        
                        <div id="signature-dropzone" 
                             class="relative border-2 border-dashed border-slate-300 dark:border-zinc-700 hover:border-indigo-400 dark:hover:border-zinc-500 bg-slate-50 dark:bg-zinc-950 p-6 flex flex-col items-center justify-center min-h-[220px] text-center cursor-pointer transition-all group">
                            
                            <input type="file" name="headmaster_signature" id="signatureFileInput" class="hidden" accept="image/*" />
                            
                            <!-- State Empty/Upload -->
                            <div id="signature-upload-state" class="{{ $siteSetting->headmaster_signature ? 'hidden' : '' }} space-y-3 py-4">
                                <div class="p-3 bg-indigo-50 dark:bg-zinc-900 rounded-full inline-block group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="text-xs">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-bold">Klik untuk unggah</span> atau seret dan lepas gambar ke sini
                                </div>
                                <p class="text-[10px] text-slate-400 dark:text-zinc-650">PNG, JPG, JPEG, atau WEBP hingga 1MB</p>
                            </div>

                            <!-- State Preview -->
                            <div id="signature-preview-state" class="{{ $siteSetting->headmaster_signature ? '' : 'hidden' }} w-full flex flex-col items-center justify-center py-2 relative">
                                <div class="w-32 h-32 relative border border-slate-200 dark:border-zinc-800 bg-white p-2">
                                    <img id="signature-preview" 
                                         src="{{ $siteSetting->headmaster_signature ? asset('storage/' . $siteSetting->headmaster_signature) : '' }}" 
                                         class="w-full h-full object-contain" />
                                </div>
                                <button type="button" onclick="resetSignatureFileSelection()" class="mt-4 py-1.5 px-3 bg-rose-600 hover:bg-rose-700 text-white font-mono text-[10px] font-bold uppercase tracking-wider transition-colors">
                                    Ganti TTD
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 & 3: Headmaster Info -->
                    <div class="lg:col-span-2 space-y-4">
                        <div>
                            <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nama Kepala Sekolah</label>
                            <input type="text" name="headmaster_name" value="{{ old('headmaster_name', $siteSetting->headmaster_name) }}" placeholder="Contoh: Drs. Ahmad Wijaya, M.Pd"
                                   class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">NIP Kepala Sekolah</label>
                                <input type="text" name="headmaster_nip" value="{{ old('headmaster_nip', $siteSetting->headmaster_nip) }}" placeholder="Contoh: 195805011980031003"
                                       class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                            </div>

                            <div>
                                <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Nomor Telepon Kepala Sekolah</label>
                                <input type="text" name="headmaster_phone" value="{{ old('headmaster_phone', $siteSetting->headmaster_phone) }}" placeholder="+62 812 3456 789"
                                       class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. TAB: DATA SEKOLAH -->
            <div id="tab-school-data" class="tab-content hidden space-y-6">
                <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 rounded-md p-4 text-xs text-emerald-800 dark:text-emerald-300 flex items-start gap-2">
                    <i class="fa-solid fa-graduation-cap flex-shrink-0 mt-0.5"></i>
                    <span>Informasi lengkap tentang data dan profil sekolah untuk keperluan administrasi resmi.</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Kode Sekolah (NPSN/NSSS)</label>
                        <input type="text" name="school_code" value="{{ old('school_code', $siteSetting->school_code) }}" placeholder="Contoh: 20401152"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Tahun Berdiri Sekolah</label>
                        <input type="number" name="school_founding_year" value="{{ old('school_founding_year', $siteSetting->school_founding_year) }}" placeholder="Contoh: 1980" min="1900" max="{{ date('Y') }}"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Status Sekolah</label>
                        <select name="school_status"
                                class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <option value="">-- Pilih Status --</option>
                            <option value="Negeri" {{ old('school_status', $siteSetting->school_status) === 'Negeri' ? 'selected' : '' }}>Negeri</option>
                            <option value="Swasta" {{ old('school_status', $siteSetting->school_status) === 'Swasta' ? 'selected' : '' }}>Swasta</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Akreditasi</label>
                        <select name="school_accreditation"
                                class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <option value="">-- Pilih Akreditasi --</option>
                            <option value="A" {{ old('school_accreditation', $siteSetting->school_accreditation) === 'A' ? 'selected' : '' }}>A (Sangat Baik)</option>
                            <option value="B" {{ old('school_accreditation', $siteSetting->school_accreditation) === 'B' ? 'selected' : '' }}>B (Baik)</option>
                            <option value="C" {{ old('school_accreditation', $siteSetting->school_accreditation) === 'C' ? 'selected' : '' }}>C (Cukup)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Motto / Semangat Sekolah</label>
                    <textarea name="school_motto" rows="3" placeholder="Contoh: Unggul dalam Akademik, Utama dalam Akhlak, Unikan dalam Inovasi"
                              class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-y">{{ old('school_motto', $siteSetting->school_motto) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Website Sekolah</label>
                        <input type="url" name="school_website" value="{{ old('school_website', $siteSetting->school_website) }}" placeholder="https://mamlimpung.sch.id"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-2">Email Resmi Sekolah</label>
                        <input type="email" name="school_email_official" value="{{ old('school_email_official', $siteSetting->school_email_official) }}" placeholder="sekolah@mamlimpung.sch.id"
                               class="w-full px-3 py-2.5 text-sm bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-none text-slate-700 dark:text-zinc-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" />
                    </div>
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-zinc-800">
                <button type="submit" class="py-2.5 px-6 bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white font-mono font-bold text-xs uppercase tracking-wider rounded-none transition-all">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab switching logic
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

    // Drag and Drop Zone Logic (Vanilla JS)
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const uploadState = document.getElementById('upload-state');
    const previewState = document.getElementById('preview-state');
    const logoPreview = document.getElementById('logo-preview');

    dropzone.addEventListener('click', () => {
        // If file input has files or already configured logo path, do not trigger dialog by clicking the entire area unless user reset
        if (uploadState.classList.contains('hidden')) return;
        fileInput.click();
    });

    fileInput.addEventListener('change', handleFileSelect);

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        if (uploadState.classList.contains('hidden')) return;
        dropzone.classList.add('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
        
        if (uploadState.classList.contains('hidden')) return;

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect();
        }
    });

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            logoPreview.src = e.target.result;
            uploadState.classList.add('hidden');
            previewState.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function resetFileSelection() {
        fileInput.value = '';
        logoPreview.src = '';
        uploadState.classList.remove('hidden');
        previewState.classList.add('hidden');
    }

    // Signature File Upload Logic
    const signatureDropzone = document.getElementById('signature-dropzone');
    const signatureFileInput = document.getElementById('signatureFileInput');
    const signatureUploadState = document.getElementById('signature-upload-state');
    const signaturePreviewState = document.getElementById('signature-preview-state');
    const signaturePreview = document.getElementById('signature-preview');

    signatureDropzone.addEventListener('click', () => {
        if (signatureUploadState.classList.contains('hidden')) return;
        signatureFileInput.click();
    });

    signatureFileInput.addEventListener('change', handleSignatureFileSelect);

    signatureDropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        if (signatureUploadState.classList.contains('hidden')) return;
        signatureDropzone.classList.add('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
    });

    signatureDropzone.addEventListener('dragleave', () => {
        signatureDropzone.classList.remove('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
    });

    signatureDropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        signatureDropzone.classList.remove('border-indigo-500', 'bg-indigo-50/20', 'dark:bg-zinc-900/30');
        
        if (signatureUploadState.classList.contains('hidden')) return;

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            signatureFileInput.files = files;
            handleSignatureFileSelect();
        }
    });

    function handleSignatureFileSelect() {
        const file = signatureFileInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            signaturePreview.src = e.target.result;
            signatureUploadState.classList.add('hidden');
            signaturePreviewState.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function resetSignatureFileSelection() {
        signatureFileInput.value = '';
        signaturePreview.src = '';
        signatureUploadState.classList.remove('hidden');
        signaturePreviewState.classList.add('hidden');
    }
@endsection
