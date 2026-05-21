{{-- Tanda tangan kartu bukti pendaftaran (orang tua + panitia + QR) --}}
@php
    $verifyUrl = route('frontend.ppdb.verify', ['nomor_registrasi' => $student->nomor_registrasi]);
@endphp
<div class="signature-block">
    <div class="signature-col" style="align-items: center; text-align: center;">
        <div class="signature-title">Mengetahui,<br>Orang Tua / Wali Siswa</div>
        <div class="signature-space"></div>
        <div class="signature-name" style="text-decoration: none;">( ..................................... )</div>
    </div>

    <div class="signature-col" style="align-items: center; text-align: center;">
        <div class="signature-date">Limpung, {{ now()->translatedFormat('d F Y') }}</div>
        <div class="signature-title">Panitia PPDB MAM Limpung</div>
        <div class="barcode-container" style="margin: 4px auto; width: 65px; height: 65px; display: flex; align-items: center; justify-content: center;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($verifyUrl) }}" alt="QR Verifikasi" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <div style="font-size: 7pt; font-family: monospace; color: #444;">{{ $student->nomor_registrasi }}</div>
        <div style="font-size: 7pt; font-style: italic; color: #15803d; font-weight: bold;">Dokumen Sah Digital</div>
    </div>
</div>

