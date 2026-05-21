{{-- Kop Surat — menggunakan komponen terpusat x-print.kop-surat --}}
<x-print.kop-surat>
    {{-- Judul & sub-judul dokumen di bawah kop --}}
    <h1 class="doc-title">Formulir Pendaftaran Calon Siswa Baru</h1>
    <h3 class="doc-subtitle">
        Penerimaan Peserta Didik Baru (PPDB)
        Tahun Pelajaran {{ $student->tahun_pelajaran ?? date('Y') }}/{{ ($student->tahun_pelajaran ?? date('Y')) + 1 }}
    </h3>
</x-print.kop-surat>
