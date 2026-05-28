# FILE RESPONSIBILITY MAP

Dokumen ini memetakan pembagian tanggung jawab folder di dalam proyek MAM Limpung untuk mencegah terjadinya tumpang tindih logika dan menjaga modularitas aplikasi.

---

## 1. Peta Tanggung Jawab Folder Utama

| Path Folder | Tanggung Jawab Utama | Jenis File yang Boleh Ada |
| :--- | :--- | :--- |
| `app/Models/` | Definisi skema data objek, relasi Eloquent, scope query database, and getter/helper representatif entitas. | File Eloquent Model PHP (PascalCase) |
| `app/Http/Controllers/` | Menangani HTTP request, memvalidasi otorisasi (Policy/Gate), mengambil data dari DB/Service, dan merender view/response. | File Controller PHP (PascalCase) |
| `app/Http/Requests/` | Memvalidasi parameter masukan dari HTTP request (form input, file uploads) beserta otorisasi awal form. | File Form Request PHP (PascalCase) |
| `app/Policies/` | Logika otorisasi granular per entitas model berdasarkan peran/role atau permission user. | File Policy PHP (PascalCase) |
| `app/Services/` | Logika bisnis yang kompleks, interaksi file sistem, pencatatan log sistem, integrasi API, atau manipulasi data berat. | File Service PHP (PascalCase) |
| `app/Traits/` | Fungsi-fungsi bersama yang dapat disisipkan ke dalam beberapa Model atau Controller secara lintas sektoral. | PHP Trait File (PascalCase) |
| `database/migrations/` | Definisi perubahan dan struktur tabel database secara kronologis. | Migration PHP class file (snake_case diawali stempel waktu) |
| `database/seeders/` | Memasukkan data awal atau dummy data ke database untuk pengujian dan instalasi awal sistem. | Seeder PHP class file (PascalCase) |
| `routes/` | Pendaftaran URL rute aplikasi, pemetaan ke Controller, dan pemasangan middleware pelindung. | File rute PHP modular (huruf kecil) |
| `resources/views/` | Kode antarmuka visual (tampilan HTML/Blade, directive Alpine, dan pemanggilan asset CSS/JS). | View Blade File (kebab-case) |
| `tests/` | Skenario pengujian otomatis untuk menguji kebenaran fitur (Unit, Feature, API). | Pest PHP test file (PascalCase diakhiri Test) |

## 2. Batasan & Aturan Ketat (Larangan Campur Tanggung Jawab)

### ⚠️ Larangan Model
- **DILARANG** melakukan operasi upload file fisik atau manipulasi sistem file (`Storage::disk(...)`) langsung di dalam kelas Model. Delegasikan ini ke Controller atau Service.
- **DILARANG** mengakses data request (`request()`, `$_POST`, dll.) secara langsung dari dalam Model. Model harus menerima data mentah dari parameter fungsi.

### ⚠️ Larangan Controller
- **DILARANG** menulis aturan validasi manual (`$request->validate([...])`) di dalam controller. Semua validasi masukan wajib dialihkan ke folder `app/Http/Requests/`.
- **DILARANG** melakukan pengecekan role kasar secara inline seperti `if ($user->name === 'admin')`. Gunakan Laravel Policy di `app/Policies/` dan panggil via `$this->authorize()` atau `Gate::authorize()`.

### ⚠️ Larangan Views (Blade)
- **DILARANG** menulis kode query SQL, logika manipulasi string yang rumit, atau pemanggilan model secara langsung. Blade hanya bertugas menampilkan variabel data yang disiapkan oleh Controller.
- **DILARANG** menggunakan tag `<script>` berisi ratusan baris Javascript vanilla mentah. Gunakan Alpine.js secara deklaratif atau tempatkan skrip di file JS terpisah.

### ⚠️ Larangan Rute (Routes)
- **DILARANG** menulis closure fungsi yang berisi logika query database langsung di dalam file `routes/*.php` (misalnya: `Route::get('/path', fn() => Model::all())`). Semua rute wajib menunjuk ke method spesifik di kelas Controller.
