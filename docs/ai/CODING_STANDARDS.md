# CODING STANDARDS

Dokumen ini mendefinisikan standar penulisan kode (coding standards) untuk file PHP, Javascript, Blade, dan skema database pada repositori MAM Limpung.

---

## 1. Aturan Penamaan File & Class
- **PHP Classes & Models**: Menggunakan format **PascalCase** (misal: `GaleriController`, `GaleriPhoto`).
- **PHP Files**: Nama file harus sama persis dengan nama class yang dideklarasikan di dalamnya (misal: `GaleriPhoto.php`).
- **Blade View Files**: Menggunakan format **kebab-case** seluruhnya dalam huruf kecil (misal: `index.blade.php`, `create.blade.php`, `mobile-nav.blade.php`).
- **Database Tables**: Menggunakan nama jamak dalam bahasa Inggris, berformat **snake_case** (misal: `galeris`, `galeri_photos`).
- **Database Columns**: Menggunakan huruf kecil berformat **snake_case** (misal: `user_id`, `file_path`, `is_cover`, `rejected_reason`).
- **Routes Name**: Menggunakan format dot notation berbasis tingkat akses/domain (misal: `admin.galeri.index`, `frontend.galeri`).

## 2. Aturan Komentar & Dokumentasi Kode
- **Bahasa**: Semua komentar kode dan blok dokumentasi wajib ditulis dalam **Bahasa Indonesia** yang jelas dan baku.
- **PHPDoc**: Gunakan PHPDoc block di atas deklarasi class, method penting, dan properti untuk menjelaskan fungsionalitas dan tipe data kembalian (return types):
  ```php
  /**
   * Mengambil URL dari foto sampul utama galeri.
   * 
   * @return string
   */
  public function coverUrl(): string
  {
      // ...
  }
  ```
- **Komentar Inline**: Hindari menulis komentar inline untuk logika yang sudah jelas. Tulis komentar inline HANYA jika baris kode tersebut mengandung logika bisnis yang kompleks atau tidak biasa.

## 3. Struktur Fungsi & Method Ideal
- **Pendek & Fokus**: Setiap method/fungsi harus berukuran pendek (ideal di bawah 30 baris) dan hanya melakukan satu hal saja (Single Responsibility Principle).
- **Type Hinting**: Gunakan type hinting pada parameter method dan deklarasi return type secara eksplisit untuk meningkatkan kejelasan kode:
  ```php
  public function update(UpdateGaleriRequest $request, Galeri $galeri): RedirectResponse
  ```
- **Constructor Promotion**: Gunakan PHP 8 constructor property promotion untuk dependency injection:
  ```php
  public function __construct(protected SystemLogService $logService) {}
  ```
  Hindari membiarkan constructor kosong tanpa parameter.

## 4. Penanganan Kesalahan & Validasi (Error Handling)
- **Validasi Terpusat**: Semua validasi request wajib ditempatkan di kelas Form Request khusus di bawah `app/Http/Requests/`. Jangan menuliskan aturan validasi di dalam controller.
- **Database Transactions**: Gunakan database transaction (`DB::transaction(function() { ... })`) jika melakukan operasi tulis ke beberapa tabel database sekaligus (seperti menyimpan `Galeri` bersamaan dengan banyak `GaleriPhoto`) untuk menjaga konsistensi data.
- **Fail-Fast Principle**: Periksa kondisi error atau kegagalan otorisasi di awal fungsi (guard clauses), lalu segera kembalikan respon (return early). Ini akan menghindari struktur kontrol bersarang (nested loops/ifs) yang rumit.
  ```php
  // Pendekatan Bagus: Early Return
  public function edit(Galeri $galeri): View
  {
      Gate::authorize('update', $galeri);
      
      return view('dashboard.admin.galeri.edit', compact('galeri'));
  }
  ```

## 5. Prinsip Readable & Maintainable Code
- **Laravel Pint Formatter**: Jalankan perintah penyeragaman format kode `vendor/bin/pint --dirty --format agent` (atau `vendor/bin/pint --format agent` untuk file yang dimodifikasi) secara rutin sebelum melakukan commit.
- **Gunakan Helper & Named Routes**: Gunakan fungsi `route('nama.rute')` alih-alih menulis URL keras (`/admin/galeri`). Gunakan helper `asset()` atau `Storage::url()` untuk file statis dan berkas unggahan.
- **Bungkus Logika Kompleks ke Service**: Jika suatu fitur memerlukan operasi file sistem yang rumit, parsing eksternal, atau interaksi API, buat kelas Service khusus di bawah `app/Services/` untuk menanganinya. Keep controllers thin!
