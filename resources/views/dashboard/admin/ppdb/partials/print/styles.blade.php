/* CSS resets & A4 Print Styles to guarantee exactly 1 page layout */
@page {
    size: A4;
    margin: 12mm 15mm 12mm 15mm;
}
body {
    font-family: 'Times New Roman', Times, serif;
    font-size: 10.5pt;
    line-height: 1.35;
    color: #000;
    background-color: #fff;
    margin: 0;
    padding: 0;
}

.print-wrapper {
    width: 100%;
    box-sizing: border-box;
}

/* Kop Surat (School Letterhead) */
.kop-container {
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 3px double #000;
    padding-bottom: 6px;
    margin-bottom: 12px;
    position: relative;
}
.kop-logo {
    position: absolute;
    left: 5px;
    width: 75px;
    height: 75px;
    object-fit: contain;
}
.kop-text {
    text-align: center;
    padding-left: 85px;
    padding-right: 15px;
}
.kop-yayasan {
    font-size: 10pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}
.kop-sekolah {
    font-size: 13.5pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin: 2px 0 0 0;
}
.kop-akreditasi {
    font-size: 8.5pt;
    font-style: italic;
    margin: 1px 0 0 0;
}
.kop-alamat {
    font-size: 8.5pt;
    margin: 3px 0 0 0;
}

/* Document Title */
.doc-title {
    text-align: center;
    font-size: 11.5pt;
    font-weight: bold;
    text-decoration: underline;
    text-transform: uppercase;
    margin: 0 0 3px 0;
}
.doc-subtitle {
    text-align: center;
    font-size: 10pt;
    font-weight: bold;
    margin: 0 0 15px 0;
}

/* Content Layout */
.profile-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}
.biodata-table {
    width: 78%;
    border-collapse: collapse;
}
.biodata-table td {
    vertical-align: top;
    padding: 3px 0;
}
.biodata-table td.label {
    width: 32%;
}
.biodata-table td.separator {
    width: 3%;
    text-align: center;
}
.biodata-table td.value {
    font-weight: bold;
}

/* Photo Area */
.photo-box {
    width: 2.6cm;
    height: 3.5cm;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 7.5pt;
    color: #555;
    text-align: center;
    box-sizing: border-box;
    background-color: #fcfcfc;
    overflow: hidden;
}
.photo-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

/* Table Grid standard for data grouping */
.section-header {
    font-size: 9.5pt;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    text-transform: uppercase;
    border-bottom: 1.5px solid #000;
    padding: 2px 0;
    margin-top: 12px;
    margin-bottom: 6px;
    page-break-inside: avoid;
}

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
.grid-table td.label {
    width: 32%;
    background-color: transparent;
    font-weight: normal;
    color: #111;
}
.grid-table td.separator {
    width: 3%;
    text-align: center;
    color: #111;
}
.grid-table td.value {
    width: 65%;
    font-weight: bold;
    color: #000;
}

/* Signatures block */
.signature-block {
    margin-top: 25px;
    display: flex;
    justify-content: space-between;
    page-break-inside: avoid;
}
.signature-col {
    width: 45%;
    display: flex;
    flex-direction: column;
}
.signature-date {
    margin-bottom: 8px;
}
.signature-title {
    margin-bottom: 6px;
    font-weight: bold;
}
.signature-name {
    font-weight: bold;
    text-decoration: underline;
}

/* Hide print triggers */
@media print {
    .no-print {
        display: none !important;
    }
}

.print-btn-bar {
    background-color: #f5f5f5;
    padding: 8px;
    text-align: center;
    border-bottom: 1px solid #ddd;
    margin-bottom: 15px;
}
.print-btn {
    background-color: #4f45b2;
    color: white;
    border: none;
    padding: 6px 12px;
    font-weight: bold;
    cursor: pointer;
    font-family: Arial, sans-serif;
    font-size: 9.5pt;
}
.print-btn:hover {
    background-color: #3b3394;
}
