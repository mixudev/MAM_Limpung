<!-- Kop Surat -->
<div class="kop-container">
    <!-- Local asset logo.png -->
    <img class="kop-logo" src="{{ asset('assets/img/logo.png') }}" alt="Logo Muhammadiyah">
    
    <div class="kop-text">
        <h4 class="kop-yayasan">Pimpinan Cabang Muhammadiyah Limpung</h4>
        <h2 class="kop-sekolah">Madrasah Aliyah Muhammadiyah Limpung</h2>
        <p class="kop-akreditasi">TERAKREDITASI B | NPSN: 20363023 | Status: Swasta</p>
        <p class="kop-alamat">Jl. Raya Limpung No. 12, Limpung, Batang, Jawa Tengah 51271 - Telp: (0285) 446889</p>
    </div>
</div>

<!-- Document Titles -->
<h1 class="doc-title">Formulir Pendaftaran Calon Siswa Baru</h1>
<h3 class="doc-subtitle">Penerimaan Peserta Didik Baru (PPDB) Tahun Pelajaran {{ $student->tahun_pelajaran ?? date('Y') }}/{{ ($student->tahun_pelajaran ?? date('Y')) + 1 }}</h3>
