@php
    $tahunAjaran = $tahunAjaran ?? (int) date('Y');
@endphp
<x-print.kop-surat
    line1="Pimpinan Cabang Muhammadiyah Limpung"
    line2="Madrasah Aliyah Muhammadiyah Limpung"
    line3="Terakreditasi B | NPSN: 20363023 | NSM: 131233250001"
    line4="Jl. Raya Limpung No. 12, Limpung, Batang, Jawa Tengah 51271 | Telp: (0285) 446889 | WA: +62 823-2495-2365 | Email: mamlimpung@gmail.com"
    :logo-size="$logoSize ?? '72px'"
>
    @if(! empty($docTitle))
        <h1 class="doc-title">{{ $docTitle }}</h1>
    @endif
    @if(! empty($docSubtitle))
        <h3 class="doc-subtitle">{{ $docSubtitle }}</h3>
    @endif
</x-print.kop-surat>
