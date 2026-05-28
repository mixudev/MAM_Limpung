# AI BEHAVIOR RULES

Dokumen ini mendefinisikan aturan perilaku, protokol interaksi, dan batasan operasional bagi seluruh asisten AI yang beroperasi dalam pengembangan repositori MAM Limpung.

---

## 1. Protokol Membaca File (Selective Reading)
- **Hindari Pembacaan Massal**: AI tidak boleh membaca seluruh file dalam repositori secara membabi buta. Lakukan pencarian spesifik menggunakan grep atau list direktori terlebih dahulu.
- **Batasi Jangkauan Baca**: Buka file hanya jika file tersebut:
  - Berhubungan langsung dengan fitur yang sedang dikerjakan.
  - Merupakan file konfigurasi sistem (seperti `composer.json` atau rute) yang krusial.
  - Merupakan file contoh/sibling untuk memahami konvensi penulisan kode setempat.

## 2. Kapan AI Boleh Membuka File
AI diizinkan membuka file dalam skenario berikut:
- Membaca file migrasi atau skema model untuk memverifikasi tipe kolom sebelum menulis operasi query.
- Membaca file controller atau view yang akan dimodifikasi untuk mendapatkan konteks layout dan struktur HTML.
- Membaca panduan internal proyek (`AGENTS.md` atau `docs/ai/*`) untuk memastikan kepatuhan standar.

## 3. Kapan AI Harus Bertanya & Meminta Klarifikasi
AI wajib menghentikan tindakan mandiri dan bertanya kepada pengguna ketika:
- Menemukan persyaratan fitur yang tidak spesifik, ambigu, atau kontradiktif dengan fungsionalitas sistem yang sudah ada.
- Terjadi kegagalan fatal pada proses kompilasi aset atau pengujian unit yang tidak dapat diselesaikan dengan perbaikan kode standar.
- Pengguna meminta implementasi pustaka eksternal baru yang belum terpasang di `composer.json` atau `package.json`.

## 4. Kapan AI Dilarang Bertindak
AI dilarang keras melakukan hal-hal berikut tanpa instruksi tertulis yang eksplisit dari pengguna:
- **DILARANG** menghapus atau mengganti pustaka inti aplikasi (framework core dependencies).
- **DILARANG** menghapus file pengujian unit/fitur (`tests/*`) yang sudah ada demi meloloskan build yang error.
- **DILARANG** mengubah kredensial rahasia di dalam berkas `.env` (misal: kunci enkripsi aplikasi, password database).

## 5. Konsistensi Lintas Sesi & Otoritas Tertinggi
- **Otoritas Dokumentasi**: Seluruh file di dalam direktori `/docs/ai/` adalah hukum tertinggi proyek. Jika ada pertentangan antara asumsi bawaan AI (default model knowledge) dengan dokumentasi ini, AI **wajib** mendahulukan aturan di dokumentasi ini.
- **Sinkronisasi Progress**: AI harus selalu memperbarui berkas `task.md` secara berkala selama fase eksekusi untuk melaporkan status pengerjaan tugas kepada pengguna secara transparan.

---

## KONTRAK PERILAKU AI (WAJIB)

SETIAP AI yang bekerja di proyek ini WAJIB:
1. Membaca seluruh isi `/docs/ai/` sebelum melakukan perubahan kode apa pun.
2. Mengikuti aturan dalam dokumen-dokumen tersebut tanpa pengecualian.
3. Menganggap dokumentasi di `/docs/ai/` lebih tinggi otoritasnya daripada asumsi pribadi maupun basis data internal AI.
4. Bertanya kepada pengguna jika menemukan konflik aturan atau ketidakjelasan arsitektur.
