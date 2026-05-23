@php
    $tahunAjaran = $tahunAjaran ?? (int) date('Y');
@endphp
<x-print.kop-surat
    line1="MAJELIS PENDIDIKAN DASAR DAN MENENGAH MUHAMMADIYAH"
    line2="Madrasah Aliyah Muhammadiyah Limpung"
    line3="Terakreditasi B | NPSN: 20363023 | NSM: 131233250001"
    line4="Jalan Cokronegoro No.34 Limpung Kabupaten Batang | Telp: (0285) 4468835 | 51271"
    :logo-size="$logoSize ?? '100px'"
>
    @if(! empty($docTitle))
        <h1 class="doc-title">{{ $docTitle }}</h1>
    @endif
    @if(! empty($docSubtitle))
        <h3 class="doc-subtitle">{{ $docSubtitle }}</h3>
    @endif
</x-print.kop-surat>
