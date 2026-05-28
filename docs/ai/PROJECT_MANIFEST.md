# PROJECT MANIFEST

Dokumen ini adalah otoritas tertinggi dan acuan utama bagi seluruh sistem kecerdasan buatan (AI) yang bekerja pada repositori ini.

---

## 1. Tujuan Proyek
Proyek ini bertujuan untuk mengembangkan portal sistem informasi sekolah modern untuk Madrasah Aliyah Muhammadiyah (MAM) Limpung. Portal ini mencakup:
- Landing page informatif, modern, dan interaktif (profil sekolah, artikel/berita, galeri foto kegiatan, prestasi sekolah).
- Sistem Penerimaan Peserta Didik Baru (PPDB) online terintegrasi (pendaftaran, manajemen status kelulusan, ekspor data, sinkronisasi Google Sheets).
- Dashboard portal administrasi untuk berbagai peran (Super Administrator, Administrator, Guru, dan Siswa).

## 2. Scope Sistem
Sistem ini terdiri atas:
- **Frontend Publik**: Menampilkan informasi umum sekolah, artikel, prestasi, galeri foto, dan formulir pendaftaran PPDB.
- **Backend Portal / Dashboard**: Panel manajemen konten, manajemen akun user, konfigurasi keamanan, sistem pencatatan aktivitas (audit logging), dan sistem persetujuan (approval workflow) untuk unggahan siswa.
- **Integrasi Pihak Ketiga**: Sinkronisasi Google Sheets untuk data PPDB dan notifikasi/konfirmasi berbasis chat/WhatsApp (jika dikonfigurasi).

## 3. Prinsip Desain UI/UX
- **Visual yang Premium**: Menggunakan palette warna harmonis berbasis CSS Variables (misal: Indigo `#4f45b2` dan Amber `#f59e0b`), efek glassmorphism, visual shadow yang halus, dan sudut border tajam/konsisten (tidak serampangan).
- **Interaksi Hidup (Dynamic UI)**: Menggunakan Alpine.js untuk micro-interactions (modal, dropdown, transisi tab, filter pencarian instan, lightbox foto) demi pengalaman pengguna yang responsif.
- **Responsif & Mobile-First**: Semua layout wajib responsif mulai dari layar ponsel (320px) hingga layar desktop ultra-wide (1440px+).

## 4. Prinsip Clean Code & Arsitektur
- **Mengikuti Pola Laravel Modern**: Mematuhi kaidah Laravel v13 dan PHP 8.5. Menggunakan model, controller, Form Request khusus untuk validasi, Policy untuk otorisasi, dan Service Layer untuk logika kompleks.
- **Modularisasi Rute**: Membagi rute ke dalam file-file modular berdasarkan fungsionalitas (misal: `routes/dashboard/announcement.php`, `routes/dashboard/galeri.php`).
- **DRY (Don't Repeat Yourself)**: Menggunakan helper global, trait (seperti `LogsActivity`), dan blade layout/components yang dapat digunakan kembali.

## 5. Prinsip Keamanan
- **Proteksi Data (UUID)**: Menggunakan UUID sebagai Route Model Binding Key pada database (misalnya untuk entitas `User`, `PpdbSiswa`, `Galeri`) untuk menghindari enumerasi ID oleh pihak yang tidak bertanggung jawab.
- **Otorisasi Granular**: Seluruh aksi dashboard dilindungi oleh Spatie Laravel Permission dan Policy. Tidak ada pengecekan akses kasar di controller tanpa otorisasi Policy.
- **Sanitasi Masukan**: Validasi ketat di tingkat Form Request. Penanganan unggahan berkas dengan sanitasi nama berkas dan pembatasan tipe berkas (MIME types).

## 6. Batasan Eksplisit AI (Larangan & Aturan Bekerja)
- **DILARANG** melakukan perubahan skema database tanpa menulis file migrasi resmi.
- **DILARANG** mengabaikan gaya penulisan kode yang sudah ada (cek file sejenis sebelum membuat kode baru).
- **DILARANG** mengedit pustaka eksternal (di folder `vendor` atau `node_modules`).
- **DILARANG** menggunakan CSS inline untuk styling baru; gunakan framework CSS yang tersedia (Tailwind CSS v4) dan class utility-nya secara konsisten.
- **WAJIB** memformat kode PHP menggunakan Laravel Pint (`vendor/bin/pint --format agent`) sebelum menyelesaikan tugas modifikasi kode.
- **WAJIB** membuat dan menjalankan pengujian (Pest PHP) untuk setiap fitur baru yang ditambahkan.
