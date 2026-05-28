# UI/UX RULES & GUIDELINES

Dokumen ini mendefinisikan standar desain antarmuka (UI) dan pengalaman pengguna (UX) untuk seluruh halaman web (frontend publik dan dashboard admin) di portal MAM Limpung.

---

## 1. Filosofi Desain UI
Desain portal MAM Limpung mengusung tema **Modern, Bersih, dan Profesional** dengan sentuhan estetika premium. Desain harus memberikan kesan tepercaya sekaligus ramah bagi siswa, guru, wali murid, dan publik.
- **Aesthetic Stack / Messy Polaroid**: Untuk bagian galeri foto, gunakan gaya visual Polaroid bertumpuk (messy stack effect) dengan border tipis dan bayangan lembut yang memberikan kesan dinamis dan kreatif.
- **Kontras Tinggi & Ketajaman**: Sudut border tajam (menggunakan default border/tanpa rounded berlebihan di area admin) berpadu dengan kontras teks yang kuat agar mudah dibaca.
- **Micro-Animations**: Transisi hover pada tombol, kartu, dan link harus halus (menggunakan utilitas Tailwind seperti `transition-all duration-300`).

## 2. Sistem Warna (Color Palette)
Aplikasi menggunakan palette warna terkurasi untuk memberikan kesan premium:
- **Warna Utama (Primary)**: Indigo/Purple (`#4f45b2` atau `bg-indigo-700` / `bg-[#4f45b2]`) untuk merepresentasikan akademis, modernitas, dan stabilitas.
- **Warna Aksen (Accent)**: Amber/Gold (`#f59e0b` atau `bg-amber-500` / `text-amber-500`) untuk merepresentasikan prestasi, energi, dan kehangatan Muhammadiyah.
- **Warna Latar Belakang**:
  - Frontend: Abu-abu sangat terang (`#fcfcfc` atau `bg-slate-50`) untuk menjaga kebersihan visual.
  - Dashboard: Putih bersih (`bg-white`) dengan dukungan mode gelap berbasis kelas `dark` menggunakan warna latar zinc (`bg-zinc-950` atau `bg-zinc-900`).
- **Warna Status**:
  - `Success` / `Approved`: Emerald/Hijau (`text-emerald-700`, `bg-emerald-50`, `border-emerald-200`).
  - `Warning` / `Pending`: Amber/Kuning (`text-amber-700`, `bg-amber-50`, `border-amber-200`).
  - `Danger` / `Rejected` / `Inactive`: Rose/Merah (`text-rose-700`, `bg-rose-50`, `border-rose-200`).

## 3. Spacing, Typography & Layout
- **Font**: Menggunakan typeface modern tanpa kaki (Sans-serif) seperti Inter, Roboto, atau Outfit.
- **Spacing**: Terapkan spacing yang konsisten menggunakan skala Tailwind. Jarak antar komponen form minimal `space-y-4` atau `space-y-6`. Padding container luar minimal `p-4` di ponsel dan `p-6` atau `p-8` di layar lebar.
- **Grid Layout**: Gunakan sistem grid yang responsif:
  - Kartu Galeri/Prestasi: `grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6`.
  - Formulir Isian: `grid-cols-1 md:grid-cols-2 gap-6`.

## 4. Prinsip Mobile-First & Responsif
- Semua elemen UI harus dirancang dengan pendekatan mobile-first. Pastikan navigasi (seperti menu mobile) dapat diakses dengan mudah menggunakan satu tangan.
- Tabel data yang lebar wajib dibungkus dalam container overflow (`overflow-x-auto`) agar tidak merusak layout layar ponsel.
- Sembunyikan kolom tabel yang kurang penting pada layar kecil menggunakan utilitas Tailwind seperti `hidden md:table-cell`.

## 5. Komponen UI & Konsistensi
- **Tombol (Buttons)**:
  - Tombol Utama: Menggunakan warna primary (`bg-[#4f45b2] hover:bg-[#4f45b2]/90 text-white`).
  - Tombol Batal/Kembali: Menggunakan warna netral (`bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 text-slate-700`).
  - Tombol Bahaya/Hapus: Menggunakan warna merah (`bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700`).
  - Semua tombol di dashboard harus berbentuk kotak (tanpa rounded penuh/pill-shaped) untuk menjaga kekonsistenan tema visual flat-edge.
- **Form Inputs**: Input teks dan select-box harus memiliki border abu-abu tipis (`border-slate-200`), background putih (`bg-white` atau `dark:bg-zinc-800`), dan transisi warna border saat aktif (`focus:border-[#4f45b2] focus:ring-2 focus:ring-[#4f45b2]/20`).

## 6. Larangan Desain UI (UI Anti-Patterns)
- **DILARANG** menggunakan emoji secara berlebihan pada teks utama sistem atau header menu. Gunakan ikon SVG terstandar (seperti FontAwesome atau Heroicons) untuk representasi visual.
- **DILARANG** menuliskan inline style (`style="..."`) pada tag HTML kecuali untuk kalkulasi dinamis yang mutlak diperlukan (seperti lebar progress bar atau rotasi acak kartu). Semua styling harus menggunakan class Tailwind.
- **DILARANG** mencampur gaya sudut border (seperti menggabungkan tombol bulat/rounded-full dengan kartu kotak tajam/rounded-none di halaman yang sama). Jagalah konsistensi elemen visual.
- **DILARANG** menggunakan warna dasar murni (plain red, plain blue, plain green) yang terlalu mencolok tanpa harmonisasi. Gunakan varian warna yang lembut atau palette Tailwind yang terstandarisasi.
