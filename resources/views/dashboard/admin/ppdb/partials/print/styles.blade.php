/* ─────────────────────────────────────────────────────────────────
   Styles khusus dokumen biodata PPDB (cetak 1 halaman A4 portrait).
   Base styles (@page, body, kop, action-bar, signature, dll.)
   sudah ada di komponen x-print.layout.
   ─────────────────────────────────────────────────────────────────*/

/* ─── Profile Section: Biodata + Foto ─────────────────────────── */
.profile-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

/* ─── Tabel Biodata Utama ──────────────────────────────────────── */
.biodata-table {
    width: 78%;
    border-collapse: collapse;
}
.biodata-table td {
    vertical-align: top;
    padding: 3px 0;
}
.biodata-table td.label     { width: 32%; }
.biodata-table td.separator { width: 3%; text-align: center; }
.biodata-table td.value     { font-weight: bold; }

/* ─── Kotak Pas Foto (3×4) ────────────────────────────────────── */
.photo-box {
    width: 2.6cm;
    height: 3.5cm;
    border: 1px solid #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 7.5pt;
    color: #000;
    text-align: center;
    background-color: #fff;
    overflow: hidden;
}
.photo-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

/* ─── Grid Tabel Data (detail informasi) ──────────────────────── */
.grid-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 6px;
    border: none;
    page-break-inside: avoid;
}
.grid-table td {
    padding: 3.5px 0;
    border: none;
    vertical-align: top;
}
.grid-table td.label     { width: 32%; color: #000; }
.grid-table td.separator { width: 3%; text-align: center; color: #000; }
.grid-table td.value     { width: 65%; font-weight: bold; color: #000; }
