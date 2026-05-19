<!-- Signatures Area with Student Manual & Committee Barcode Verification -->
<div class="signature-block">
    <!-- Left Side: Student Manual Signature -->
    <div class="signature-col" style="align-items: flex-start; text-align: left; padding-left: 20px;">
        <div class="signature-date">&nbsp;</div>
        <div class="signature-title">Calon Peserta Didik Baru,</div>
        <div style="height: 52px;"></div> <!-- Space for manual signature -->
        <div class="signature-name">{{ $student->nama_lengkap }}</div>
    </div>
    
    <!-- Right Side: Panitia Digital Signature Barcode -->
    <div class="signature-col" style="align-items: center; text-align: center;">
        <div class="signature-date">Limpung, {{ date('d F Y') }}</div>
        <div class="signature-title">Panitia PPDB MAM Limpung,</div>
        
        <!-- Digital QR Verification QR-Code (Fully functional scanned authenticity) -->
        <div class="barcode-container" style="margin: 4px auto; width: 65px; height: 65px; border: none; padding: 2px; box-sizing: border-box; background-color: #fff; display: flex; align-items: center; justify-content: center;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/ppdb/verify/' . $student->nomor_registrasi)) }}" alt="QR Verifikasi" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        
        <div class="signature-name" style="text-decoration: none; font-size: 7.5pt; font-family: monospace; color: #444; margin-top: 1px;">
            [{{ $student->nomor_registrasi }}]
        </div>
        <div class="signature-verification" style="font-size: 7pt; font-family: sans-serif; font-style: italic; color: #15803d; font-weight: bold; margin-top: 0px; letter-spacing: 0.2px;">
            ✓ TERVERIFIKASI ASLI
        </div>
    </div>
</div>
