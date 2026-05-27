<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ArticleCategory::all();
        $admin = User::role('admin')->first() ?? User::role('super-admin')->first() ?? User::first();

        if ($categories->isEmpty() || ! $admin) {
            return;
        }

        $articlesData = [
            [
                'judul' => 'MAM Limpung Siap Sambut PPDB Tahun Pelajaran 2026/2027',
                'ringkasan' => 'MA Muhammadiyah Limpung secara resmi membuka pendaftaran peserta didik baru untuk tahun ajaran 2026/2027 dengan berbagai pilihan jurusan unggulan.',
                'konten' => '<p>MA Muhammadiyah Limpung secara resmi telah membuka Penerimaan Peserta Didik Baru (PPDB) untuk Tahun Ajaran 2026/2027. Pada tahun ini, sekolah menawarkan berbagai program unggulan dan fasilitas penunjang belajar yang representatif.</p><h2>Program Unggulan</h2><p>Kami memiliki tiga jurusan spesialisasi utama yaitu Matematika & Ilmu Pengetahuan Alam (MIPA), Ilmu Pengetahuan Sosial (IPS), serta program Keagamaan yang terintegrasi dengan pondok pesantren.</p><p>Pendaftaran dapat dilakukan secara daring melalui website resmi kami atau dengan datang langsung ke sekretariat pendaftaran di kampus MA Muhammadiyah Limpung.</p>',
                'status' => 'published',
                'focus_keyword' => 'PPDB MAM Limpung',
                'meta_title' => 'Pendaftaran PPDB MA Muhammadiyah Limpung 2026/2027',
                'meta_description' => 'Ayo daftar di MA Muhammadiyah Limpung untuk tahun ajaran 2026/2027. Tersedia pilihan jurusan MIPA, IPS, dan Keagamaan dengan fasilitas modern.',
            ],
            [
                'judul' => 'Siswa MAM Limpung Raih Juara 1 Olimpiade Fisika Tingkat Kabupaten',
                'ringkasan' => 'Prestasi membanggakan kembali diukir oleh siswa MA Muhammadiyah Limpung dalam ajang Olimpiade Fisika tingkat Kabupaten Batang.',
                'konten' => '<p>Prestasi luar biasa diraih oleh siswa MA Muhammadiyah Limpung dalam Olimpiade Sains Nasional (OSN) tingkat Kabupaten Batang bidang Fisika. Siswa kami berhasil membawa pulang medali emas setelah bersaing dengan puluhan sekolah lainnya.</p><h2>Komitmen Sekolah terhadap Prestasi Akademik</h2><p>Kepala Sekolah menyampaikan rasa syukur dan bangganya atas pencapaian ini. Pihak sekolah berkomitmen untuk terus membimbing dan memfasilitasi minat bakat siswa baik di bidang akademik maupun non-akademik agar terus bersinar.</p>',
                'status' => 'published',
                'focus_keyword' => 'Prestasi Siswa MAM Limpung',
                'meta_title' => 'Siswa MA Muhammadiyah Limpung Juara Olimpiade Fisika',
                'meta_description' => 'Siswa MAM Limpung sukses meraih juara pertama dalam ajang Olimpiade Fisika tingkat Kabupaten Batang. Baca selengkapnya tentang prestasi membanggakan ini.',
            ],
            [
                'judul' => 'Kajian Rutin Keputrian IPM MAM Limpung Membentuk Karakter Muslimah',
                'ringkasan' => 'Pimpinan Ranting Ikatan Pelajar Muhammadiyah (PR IPM) MAM Limpung rutin mengadakan kajian keputrian guna membina karakter siswi.',
                'konten' => '<p>Kajian keputrian merupakan agenda rutin mingguan yang diselenggarakan oleh bidang Immawati PR IPM MA Muhammadiyah Limpung. Kegiatan ini bertujuan untuk membekali para siswi dengan ilmu agama praktis dan pembentukan karakter muslimah teladan.</p><h2>Materi Kajian</h2><p>Materi yang disampaikan berkisar tentang fiqih wanita, etika pergaulan Islami, serta peran wanita dalam pembangunan peradaban modern yang berkemajuan.</p>',
                'status' => 'published',
                'focus_keyword' => 'IPM MAM Limpung',
                'meta_title' => 'Kajian Keputrian IPM MA Muhammadiyah Limpung',
                'meta_description' => 'Kajian keputrian rutin dari PR IPM MAM Limpung membina karakter akhlak mulia dan pemahaman keislaman bagi para siswi. Baca artikelnya.',
            ],
        ];

        foreach ($articlesData as $index => $data) {
            $category = $categories->random();

            $article = Article::create([
                'user_id' => $admin->id,
                'category_id' => $category->id,
                'judul' => $data['judul'],
                'ringkasan' => $data['ringkasan'],
                'konten' => $data['konten'],
                'status' => $data['status'],
                'published_at' => now()->subDays($index * 2),
            ]);

            $article->seo()->create([
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
                'meta_keywords' => 'mam limpung, sekolah islam limpung, '.Str::slug($data['focus_keyword'], ', '),
                'focus_keyword' => $data['focus_keyword'],
                'is_indexed' => true,
                'is_followed' => true,
            ]);
        }
    }
}
