{{--
    Komponen Kop Surat MAM Limpung yang bisa dipakai di semua dokumen cetak.

    Props:
      bordered  (bool)   — tampilkan garis bawah kop, default true
      logoSize  (string) — ukuran logo, default '72px'
      line1     (string) — baris 1: nama yayasan/induk
      line2     (string) — baris 2: nama sekolah (BESAR)
      line3     (string) — baris 3: akreditasi/NPSN/NSM
      line4     (string) — baris 4: alamat & kontak
      logoSrc   (string) — override URL logo (opsional)

    Slot default ($slot):
      Konten opsional di bawah kop, misalnya judul & sub-judul dokumen.
      Gunakan <h1 class="doc-title"> dan <h3 class="doc-subtitle">.
--}}
@props([
    'bordered' => true,
    'logoSize' => '110px',
    'line1'    => 'MAJELIS PENDIDIKAN DASAR DAN MENENGAH MUHAMMADIYAH',
    'line2'    => 'Madrasah Aliyah Muhammadiyah Limpung',
    'line3'    => 'Terakreditasi B | NPSN: 20363023 | NSM: 131233250001',
    'line4'    => 'Jalan Cokronegoro No.34 Limpung Kabupaten Batang | Telp: (0285) 4468835 | 51271',
    'logoSrc'  => null,
])
@php
    $logoNum     = (int) filter_var($logoSize, FILTER_SANITIZE_NUMBER_INT);
    $bodyPadLeft = ($logoNum + 10) . 'px';
@endphp

<div class="kop-container {{ $bordered ? 'bordered' : '' }}">
    <img
        class="kop-logo"
        src="{{ $logoSrc ?? asset('assets/img/logo.png') }}"
        alt="Logo MAM Limpung"
        style="width: {{ $logoSize }}; height: {{ $logoSize }};"
    >
    <div class="kop-body" style="padding-left: {{ $bodyPadLeft }}; padding-right: 10px;">
        @if($line1)
            <p class="kop-line-1">{{ $line1 }}</p>
        @endif
        <h1 class="kop-line-2">{{ $line2 }}</h1>
        @if($line3)
            <p class="kop-line-3">{{ $line3 }}</p>
        @endif
        @if($line4)
            <p class="kop-line-4">{{ $line4 }}</p>
        @endif
    </div>
</div>

{{-- Slot opsional: judul dokumen di bawah kop --}}
{{ $slot }}
