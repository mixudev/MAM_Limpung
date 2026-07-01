<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        return view('front.profile.index');
    }

    public function selayangPandang(): View
    {
        $establishDate = '11 April 1985';
        $charterNumber = 'Wk/5.d./178/Pgm./MA/1988';
        $motto = 'Unggul dalam Ilmu, Kreatif, dan Anggun dalam Perilaku';
        $tagline = 'MUALIM Bersahabat: Berilmu, Santun, dan Hebat';

        $paragraphs = [
            [
                'title' => 'Awal Berdiri',
                'content' => 'Muhammadiyah memiliki perhatian besar terhadap dunia pendidikan sebagai sarana dakwah dan pemberdayaan umat. Sejalan dengan perkembangan Muhammadiyah di Kecamatan Limpung, Kabupaten Batang, berbagai amal usaha pendidikan mulai didirikan untuk memenuhi kebutuhan masyarakat akan pendidikan yang memadukan ilmu pengetahuan umum dan nilai-nilai keislaman. Salah satu wujud nyata dari komitmen tersebut adalah berdirinya Madrasah Aliyah Muhammadiyah Limpung atau MAM Limpung.',
                'icon' => 'fa-solid fa-school',
            ],
            [
                'title' => 'Resmi Berdiri',
                'content' => "Madrasah ini resmi berdiri pada tanggal {$establishDate} dan terdaftar berdasarkan Piagam Madrasah Aliyah Nomor: {$charterNumber} yang dikeluarkan oleh Kantor Wilayah Departemen Agama Provinsi Jawa Tengah.",
                'icon' => 'fa-solid fa-certificate',
            ],
            [
                'title' => 'Semangat Pendidikan',
                'content' => 'Di tengah dinamika zaman yang terus berubah, pendidikan tidak lagi hanya bertugas mengantarkan siswa menuju masa depan, tetapi juga menyiapkan mereka menjadi pribadi yang mampu membentuk dan menghadirkan masa depan itu sendiri. Berangkat dari semangat tersebut, MA Muhammadiyah Limpung (MUALIM) hadir sebagai lembaga pendidikan yang berkomitmen membangun generasi muslim yang berilmu, berkarakter, kreatif, dan siap mengambil peran di tengah masyarakat.',
                'icon' => 'fa-solid fa-lightbulb',
            ],
            [
                'title' => 'Motto & Tagline',
                'content' => "Mengusung motto \"{$motto}\", MUALIM menempatkan penguasaan ilmu pengetahuan, pengembangan potensi diri, serta pembentukan akhlak mulia sebagai fondasi utama dalam proses pendidikan. Madrasah tidak hanya menjadi tempat belajar, tetapi juga ruang tumbuh bagi lahirnya generasi yang percaya diri, adaptif, dan memiliki semangat untuk terus berkarya. Semangat tersebut diwujudkan melalui tagline \"{$tagline}\".",
                'icon' => 'fa-solid fa-quote-left',
            ],
            [
                'title' => 'Makna Tagline',
                'content' => 'Berilmu, karena setiap peserta didik didorong untuk memiliki wawasan yang luas serta kemampuan berpikir yang kritis dan progresif. Santun, karena keberhasilan sejati tidak hanya diukur dari prestasi, tetapi juga dari akhlak dan adab dalam kehidupan sehari-hari. Hebat, karena setiap siswa diyakini memiliki potensi untuk menjadi pribadi yang unggul dan memberi manfaat bagi lingkungan sekitarnya.',
                'icon' => 'fa-solid fa-star',
            ],
            [
                'title' => 'Komitmen ke Depan',
                'content' => 'Sebagai madrasah yang terus bergerak dan berinovasi, MUALIM berkomitmen mencetak generasi yang bukan hanya siap menghadapi masa depan, tetapi juga generasi yang siap menciptakan masa depan—generasi yang mampu menjadi pelopor perubahan, memiliki jiwa kepemimpinan, semangat berkarya, serta tetap berpegang teguh pada nilai-nilai Islam. Dengan dukungan seluruh civitas madrasah, orang tua, dan masyarakat, MA Muhammadiyah Limpung terus melangkah menjadi rumah pendidikan yang menumbuhkan generasi berilmu, berakhlak, berprestasi, serta siap menjadi pencipta peluang dan pemberi manfaat bagi umat, bangsa, dan peradaban.',
                'icon' => 'fa-solid fa-rocket',
            ],
        ];

        return view('front.profile.selayang-pandang', compact('establishDate', 'charterNumber', 'motto', 'tagline', 'paragraphs'));
    }

    public function visiMisi(): View
    {
        $branding = 'Madrasah Calon Entrepreneur Muslim';
        $motto = 'Unggul dalam Ilmu, Kreatif, dan Anggun dalam Perilaku';

        $vision = 'Terwujudnya generasi yang berakhlak mulia, kreatif dalam karya, dan unggul dalam ilmu pengetahuan untuk menghadapi tantangan zaman.';

        $missions = [
            [
                'number' => 1,
                'title' => 'Menanamkan Akhlak Mulia dan Nilai Keislaman',
                'description' => 'Menjadikan nilai-nilai Islam sebagai landasan dalam setiap aspek kehidupan madrasah, membentuk pribadi yang berakhlak karimah, jujur, amanah, dan bertanggung jawab.',
                'icon' => 'fa-solid fa-hand-holding-heart',
                'color' => 'bg-emerald-500',
            ],
            [
                'number' => 2,
                'title' => 'Mengembangkan Kreativitas dan Inovasi Murid',
                'description' => 'Mendorong siswa untuk berpikir kreatif, inovatif, dan berani menciptakan sesuatu yang baru melalui berbagai program pengembangan bakat dan minat.',
                'icon' => 'fa-solid fa-lightbulb',
                'color' => 'bg-amber-500',
            ],
            [
                'number' => 3,
                'title' => 'Meningkatkan Mutu Pembelajaran serta Penguasaan IPTEK',
                'description' => 'Menyelenggarakan pembelajaran berkualitas yang mengintegrasikan ilmu pengetahuan dan teknologi secara optimal untuk membekali siswa menghadapi era global.',
                'icon' => 'fa-solid fa-microchip',
                'color' => 'bg-blue-600',
            ],
            [
                'number' => 4,
                'title' => 'Mewujudkan Lingkungan Belajar yang Aman dan Menyenangkan',
                'description' => 'Menciptakan suasana madrasah yang kondusif, nyaman, dan bebas dari perundungan agar siswa dapat belajar dengan optimal dan penuh semangat.',
                'icon' => 'fa-solid fa-shield-halved',
                'color' => 'bg-teal-600',
            ],
            [
                'number' => 5,
                'title' => 'Menjalin Kemitraan dengan Orang Tua, Masyarakat, dan Lembaga Lain',
                'description' => 'Membangun sinergi dan kolaborasi dengan berbagai pihak untuk mendukung pengembangan madrasah dan peningkatan mutu pendidikan secara berkelanjutan.',
                'icon' => 'fa-solid fa-handshake',
                'color' => 'bg-indigo-600',
            ],
            [
                'number' => 6,
                'title' => 'Menumbuhkan Jiwa Entrepreneur Muslim yang Mandiri dan Berintegritas',
                'description' => 'Membentuk karakter wirausaha yang Islami, mandiri, jujur, dan berintegritas melalui program kewirausahaan dan unit usaha madrasah sebagai bekal kehidupan.',
                'icon' => 'fa-solid fa-store',
                'color' => 'bg-amber-600',
            ],
            [
                'number' => 7,
                'title' => 'Mengembangkan Pembelajaran Berbasis Proyek dan Unit Usaha Madrasah',
                'description' => 'Menerapkan model pembelajaran Project Based Learning (PjBL) yang terintegrasi dengan unit usaha madrasah untuk memberikan pengalaman belajar yang aplikatif dan relevan.',
                'icon' => 'fa-solid fa-diagram-project',
                'color' => 'bg-sky-600',
            ],
        ];

        return view('front.profile.visi-misi', compact('branding', 'motto', 'vision', 'missions'));
    }

    public function periodisasiKepala(): View
    {
        $periods = [
            [
                'name' => 'H. Achmad Badjuri',
                'period' => '1985 — 1988',
                'number' => 1,
            ],
            [
                'name' => 'H. Khuzaeni Munar, B.A.',
                'period' => '1988 — 2008',
                'number' => 2,
            ],
            [
                'name' => 'H. Setiyarso',
                'period' => '2008 — 2010',
                'number' => 3,
            ],
            [
                'name' => 'H.M. Furqon Thohar, S.Ag.',
                'period' => '2010 — 2012',
                'number' => 4,
            ],
            [
                'name' => 'H. Zaenudin, S.Ag.',
                'period' => '2012 — 2017',
                'number' => 5,
            ],
            [
                'name' => 'Ahyaudin, S.Pd.I',
                'period' => '2017 — 2025',
                'number' => 6,
            ],
            [
                'name' => 'Muniroch, M.Pd.',
                'period' => '2025 — Sekarang',
                'number' => 7,
            ],
        ];

        return view('front.profile.periodisasi-kepala', compact('periods'));
    }

    public function strukturOrganisasi(): View
    {
        $teachers = Teacher::with('categories')
            ->where('status', 'aktif')
            ->get();

        $komite = $teachers->filter(fn ($t) => $t->categories->contains(fn ($c) => $c->slug === 'komite-madrasah'))->first();
        $kepala = $teachers->filter(fn ($t) => $t->categories->contains(fn ($c) => $c->slug === 'kepala-madrasah'))->first();
        $wakil = $teachers->filter(fn ($t) => $t->categories->contains(fn ($c) => in_array($c->slug, ['waka-kurikulum', 'waka-kesiswaan', 'waka-sarpras'])))->values();
        $tataUsaha = $teachers->filter(fn ($t) => $t->categories->contains(fn ($c) => in_array($c->slug, ['kepala-tata-usaha', 'staf-tata-usaha'])))->values();
        $bendahara = $teachers->filter(fn ($t) => $t->categories->contains(fn ($c) => in_array($c->slug, ['bendahara-madrasah', 'bendahara-bos'])))->values();
        $operator = $teachers->filter(fn ($t) => $t->categories->contains(fn ($c) => $c->slug === 'operator-madrasah'))->first();
        $unit = $teachers->filter(fn ($t) => $t->categories->contains(fn ($c) => in_array($c->slug, ['kepala-lab-komputer', 'kepala-lab-ipa', 'kepala-perpustakaan', 'satpam-madrasah'])))->values();
        $guru = $teachers->filter(fn ($t) => $t->categories->contains(fn ($c) => $c->slug === 'dewan-guru'))->values();
        $waliKelas = $teachers->filter(fn ($t) => $t->categories->contains(fn ($c) => str_starts_with($c->slug, 'wali-kelas')))->values();
        $guruBk = $teachers->filter(fn ($t) => $t->categories->contains(fn ($c) => $c->slug === 'guru-bk'))->values();

        return view('front.profile.struktur-organisasi', compact(
            'komite', 'kepala', 'wakil', 'tataUsaha', 'bendahara',
            'operator', 'unit', 'guru', 'waliKelas', 'guruBk'
        ));
    }

    public function programMadrasah(): View
    {
        $programs = [
            [
                'title' => 'Kegiatan Religi',
                'category' => 'Keagamaan',
                'description' => 'Program religi yang menjadi budaya keseharian warga madrasah, dirancang untuk menumbuhkan kecintaan terhadap ibadah, membiasakan akhlak mulia, serta membangun keseimbangan antara kecerdasan intelektual dan spiritual.',
                'icon' => 'fa-solid fa-mosque',
                'color' => 'emerald',
                'items' => [
                    'Tadarus Pagi & Dzikir Pagi — Tahsin dan tahfidz Al-Qur\'an setiap Selasa–Jumat, dzikir pagi setiap Sabtu.',
                    'Sholat Dluha — Pembiasaan ibadah sunnah bersama sebagai bentuk kedekatan kepada Allah SWT.',
                    'Sholat Jamaah Dzuhur — Seluruh siswa dan guru melaksanakan sholat Dzuhur berjamaah.',
                    'Kultum & Latihan Khutbah — Ruang bagi siswa belajar berbicara di depan umum dan melatih dakwah.',
                    'Hafalan Hadits Bersama — Penguatan pemahaman nilai-nilai Islam melalui hadits pilihan.',
                ],
            ],
            [
                'title' => 'Display Pra Upacara',
                'category' => 'Kreativitas',
                'description' => 'Panggung kreativitas bagi setiap kelas sebelum pelaksanaan upacara. Setiap kelas mendapatkan kesempatan untuk menampilkan karya dan kemampuan terbaik mereka, seperti tari, puisi, monolog, musik, demonstrasi sains, pertunjukan Tapak Suci, drama singkat, maupun karya kreatif lainnya.',
                'icon' => 'fa-solid fa-palette',
                'color' => 'purple',
                'items' => [
                    'Menampilkan tari, puisi, monolog, dan drama singkat',
                    'Demonstrasi sains dan pertunjukan Tapak Suci',
                    'Melatih keberanian tampil dan meningkatkan kreativitas',
                    'Menumbuhkan budaya saling menghargai antar siswa',
                ],
            ],
            [
                'title' => 'Eduday',
                'category' => 'Pembelajaran',
                'description' => 'Program pembelajaran inspiratif yang menghadirkan pengalaman belajar dari luar kelas melalui kolaborasi dengan berbagai instansi, perguruan tinggi, praktisi, maupun kegiatan edukatif lainnya.',
                'icon' => 'fa-solid fa-chalkboard-user',
                'color' => 'blue',
                'items' => [
                    'Sosialisasi dari BNN',
                    'Materi edukatif bersama UIN Gusdur saat Matsama',
                    'Kajian penentuan awal Ramadhan bersama UNISSULA',
                    'ESQ dan buka bersama',
                    'Pesantren Kilat dengan inspirasi alumni',
                    'Edukasi dari IKM (Ikatan Konselor Menyusui)',
                ],
            ],
            [
                'title' => 'Mualim Open Class',
                'category' => 'Kolaborasi',
                'description' => 'Program kolaborasi pembelajaran bersama siswa SMP/MTs sebagai tindak lanjut kerja sama antar sekolah. Siswa dari sekolah mitra berkunjung dan mengikuti pengalaman belajar secara langsung, baik eksperimen sains, praktik laboratorium, maupun pembelajaran keagamaan.',
                'icon' => 'fa-solid fa-door-open',
                'color' => 'sky',
                'items' => [
                    'Studi Sains bersama 18 siswa MTs Muhammadiyah Batang',
                    'Praktik laboratorium dan eksperimen sains',
                    'Pembelajaran keagamaan tematik',
                    'Berbagi inspirasi budaya belajar aktif',
                ],
            ],
            [
                'title' => 'Parenting',
                'category' => 'Kemitraan',
                'description' => 'Bentuk sinergi antara madrasah dan orang tua dalam mendampingi tumbuh kembang peserta didik. Orang tua memperoleh wawasan mengenai pendidikan remaja, pola komunikasi keluarga, pendampingan belajar, serta pembentukan karakter di era digital.',
                'icon' => 'fa-solid fa-people-arrows',
                'color' => 'indigo',
                'items' => [
                    'Wawasan pendidikan remaja dan pola komunikasi keluarga',
                    'Pendampingan belajar di era digital',
                    'Pembentukan karakter anak',
                    'Sinergi madrasah dan orang tua',
                ],
            ],
            [
                'title' => 'Entrepreneur in Action',
                'category' => 'Kewirausahaan',
                'description' => 'Program unggulan yang mendukung branding MA Muhammadiyah Limpung sebagai Madrasah Calon Entrepreneur Muslim. Memberikan pengalaman nyata kepada siswa untuk mengenal dunia usaha, membangun kreativitas, komunikasi, kepemimpinan, dan keberanian mengambil peluang.',
                'icon' => 'fa-solid fa-store',
                'color' => 'amber',
                'items' => [
                    'Entrepreneur Class',
                    'Bazar Ahad Pagi',
                    'Konsinyasi Produk',
                    'Market Day',
                ],
            ],
            [
                'title' => 'Studi Kampus',
                'category' => 'Wawasan',
                'description' => 'Sarana bagi siswa untuk mengenal dunia perguruan tinggi dan memperluas cita-cita masa depan. Memberikan pengalaman langsung melihat atmosfer akademik, fasilitas kampus, serta peluang studi lanjutan.',
                'icon' => 'fa-solid fa-graduation-cap',
                'color' => 'red',
                'items' => [
                    'Akademi Militer (AKMIL)',
                    'Akademi Kepolisian (AKPOL)',
                    'Universitas Diponegoro (UNDIP)',
                    'Rencana perluasan ke UMS dan kampus unggulan lainnya',
                ],
            ],
            [
                'title' => 'Outing Class',
                'category' => 'Edukatif',
                'description' => 'Pengalaman belajar kontekstual melalui kunjungan ke berbagai tempat edukatif. Pembelajaran tidak hanya terjadi di dalam kelas, tetapi juga melalui observasi langsung, eksplorasi, dan interaksi dengan lingkungan.',
                'icon' => 'fa-solid fa-map-location-dot',
                'color' => 'teal',
                'items' => [
                    'Candi Borobudur',
                    'Desa Menari',
                    'GAMELAB Salatiga',
                    'Destinasi edukatif dan inspiratif lainnya',
                ],
            ],
            [
                'title' => 'Study Tour',
                'category' => 'Pengalaman',
                'description' => 'Pembelajaran berbasis pengalaman yang memperluas wawasan siswa tentang budaya, pendidikan, dan kehidupan masyarakat di berbagai daerah. Membangun kebersamaan serta memperkaya sudut pandang terhadap dunia yang lebih luas.',
                'icon' => 'fa-solid fa-bus',
                'color' => 'cyan',
                'items' => [
                    'Kunjungan ke berbagai daerah',
                    'Pengenalan budaya dan pendidikan lokal',
                    'Mempererat kebersamaan antar siswa',
                    'Memperkaya perspektif kehidupan',
                ],
            ],
            [
                'title' => 'Ujian Sertifikasi Komputer',
                'category' => 'Kompetensi',
                'description' => 'Bentuk kesiapan menghadapi dunia pendidikan tinggi dan dunia kerja. Siswa kelas XII mengikuti ujian sertifikasi komputer bekerja sama dengan LKP Sinar Nusantara Semarang sebagai penguji resmi.',
                'icon' => 'fa-solid fa-laptop-code',
                'color' => 'slate',
                'items' => [
                    'Kompetensi dasar teknologi informasi',
                    'Sertifikat sebagai nilai tambah kelulusan',
                    'Bekerja sama dengan LKP Sinar Nusantara Semarang',
                    'Kesiapan menghadapi dunia kerja dan kampus',
                ],
            ],
            [
                'title' => 'MAPETA & Gamma One',
                'category' => 'Pembekalan',
                'description' => 'MAPETA (Masa Pembekalan Tahap Akhir) membekali siswa dengan keterampilan, nilai-nilai keislaman, dan ketangguhan pribadi menjelang akhir masa studi. Gamma One (Magangnya Murid Mualim – Opportunity for New Experience) adalah program magang sebagai sarana belajar langsung di dunia kerja dan masyarakat.',
                'icon' => 'fa-solid fa-timeline',
                'color' => 'violet',
                'items' => [
                    'MAPETA: ibadah praktis, materi sakinah, kemandirian',
                    'Gamma One: magang di dunia kerja dan masyarakat',
                    'Pembekalan psikologis dan training motivasi',
                    'Pengalaman nyata tanggung jawab profesional',
                ],
            ],
        ];

        return view('front.profile.program-madrasah', compact('programs'));
    }

    public function mmc(): View
    {
        $ekskuls = [
            [
                'title' => 'Hizbul Wathan (HW)',
                'description' => 'Gerakan kepanduan khas Muhammadiyah yang hadir sebagai sarana pembentukan kepemimpinan, kemandirian, dan semangat kebangsaan siswa. Melalui berbagai aktivitas edukatif dan menyenangkan, siswa diajak untuk belajar bekerja sama, disiplin, bertanggung jawab, serta memiliki kepedulian terhadap lingkungan dan masyarakat.',
                'icon' => 'fa-solid fa-tents',
                'color' => 'emerald',
            ],
            [
                'title' => 'Tapak Suci',
                'description' => 'Latihan bela diri rutin yang dipandu oleh pelatih berpengalaman, siswa dibina untuk memiliki mental tangguh, tubuh sehat, serta jiwa yang berakhlak mulia. Juga menjadi sarana pengembangan prestasi melalui kejuaraan dan kompetisi.',
                'icon' => 'fa-solid fa-hand-fist',
                'color' => 'red',
            ],
            [
                'title' => 'Futsal',
                'description' => 'Wadah bagi siswa untuk menyalurkan minat dan bakat di bidang olahraga sekaligus membangun karakter positif melalui kerja sama tim dan semangat sportivitas. Meningkatkan teknik bermain, kebugaran jasmani, strategi permainan, serta mental kompetitif yang sehat.',
                'icon' => 'fa-solid fa-futbol',
                'color' => 'blue',
            ],
            [
                'title' => 'Marching Band',
                'description' => 'Ruang pengembangan kreativitas, musikalitas, dan kekompakan siswa melalui perpaduan seni musik dan keterampilan baris-berbaris. Siswa belajar memainkan alat musik sekaligus melatih konsentrasi, koordinasi, disiplin, dan kerja sama dalam penampilan yang harmonis.',
                'icon' => 'fa-solid fa-drum',
                'color' => 'purple',
            ],
            [
                'title' => 'Tahfidz',
                'description' => 'Ikhtiar madrasah dalam membentuk generasi Qur\'ani melalui bimbingan intensif menghafal Al-Qur\'an. Siswa dibina untuk menghafal dengan baik, memahami adab terhadap Al-Qur\'an, serta mengamalkan nilai-nilainya dalam kehidupan sehari-hari.',
                'icon' => 'fa-solid fa-book-quran',
                'color' => 'amber',
            ],
            [
                'title' => 'Videografi',
                'description' => 'Membekali siswa dengan keterampilan kreatif di bidang produksi media digital, mulai dari teknik pengambilan gambar, penyuntingan video, hingga pembuatan konten yang informatif dan inspiratif. Sarana pengembangan kreativitas dan literasi teknologi.',
                'icon' => 'fa-solid fa-video',
                'color' => 'sky',
            ],
            [
                'title' => 'Kewirausahaan',
                'description' => 'Menumbuhkan jiwa entrepreneur yang kreatif, inovatif, dan mandiri. Siswa belajar mengidentifikasi peluang usaha, mengembangkan produk, melakukan pemasaran, serta memahami dasar-dasar pengelolaan bisnis sebagai bekal menghadapi masa depan.',
                'icon' => 'fa-solid fa-store',
                'color' => 'amber',
            ],
            [
                'title' => 'English Club',
                'description' => 'Wadah bagi siswa untuk meningkatkan kemampuan berbahasa Inggris secara aktif dan komunikatif. Melalui berbagai kegiatan interaktif seperti conversation, discussion, presentation, dan language games, siswa didorong lebih percaya diri menggunakan bahasa Inggris.',
                'icon' => 'fa-solid fa-language',
                'color' => 'indigo',
            ],
            [
                'title' => 'Public Speaking',
                'description' => 'Membentuk generasi yang mampu berkomunikasi secara efektif, percaya diri, dan berwawasan luas. Siswa dilatih dalam berbicara di depan umum, presentasi, pidato, moderasi, hingga kemampuan menyampaikan gagasan dengan jelas dan persuasif.',
                'icon' => 'fa-solid fa-microphone',
                'color' => 'pink',
            ],
        ];

        return view('front.profile.mmc', compact('ekskuls'));
    }
}
