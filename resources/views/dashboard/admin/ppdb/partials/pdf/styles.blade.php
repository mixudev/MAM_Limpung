/* Base Setup */
body {
    font-family: Arial, sans-serif;
    color: #1e293b;
    background-color: #fff;
    margin: 0;
    padding: 10px;
    font-size: {{ $orientation === 'portrait' ? '8.5px' : '10px' }};
    line-height: 1.25;
}

/* Kop Surat (School Official Letterhead) */
.kop-surat {
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 3px double #000;
    padding-bottom: 12px;
    margin-bottom: 15px;
    position: relative;
}
.kop-logo {
    width: 55px;
    height: auto;
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
}
.kop-text {
    text-align: center;
    flex-grow: 1;
    padding: 0 10px 0 75px;
}
.kop-text h2 {
    font-family: Arial, sans-serif;
    font-size: {{ $orientation === 'portrait' ? '11px' : '12px' }};
    margin: 0;
    font-weight: bold;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.kop-text h1 {
    font-family: Arial, sans-serif;
    font-size: {{ $orientation === 'portrait' ? '14px' : '17px' }};
    margin: 3px 0;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.kop-text p {
    margin: 2px 0 0 0;
    font-size: {{ $orientation === 'portrait' ? '8px' : '9px' }};
    font-style: italic;
    color: #475569;
}

/* Report Meta Title */
.report-header {
    text-align: center;
    margin-bottom: 15px;
}
.report-header h3 {
    font-size: {{ $orientation === 'portrait' ? '11px' : '13px' }};
    margin: 0 0 5px 0;
    text-transform: uppercase;
    font-weight: bold;
    text-decoration: underline;
}
.report-meta {
    display: flex;
    justify-content: space-between;
    font-family: Arial, sans-serif;
    font-size: {{ $orientation === 'portrait' ? '8px' : '9px' }};
    color: #334155;
    margin-bottom: 10px;
    border-bottom: 1px dashed #cbd5e1;
    padding-bottom: 5px;
}

/* Table Design (Clean Ledger Sheet) */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    table-layout: auto;
}
th, td {
    border: 1px solid #cbd5e1;
    padding: {{ $orientation === 'portrait' ? '4px 5px' : '6px 8px' }};
    text-align: left;
    vertical-align: middle;
    word-wrap: break-word;
}
th {
    background-color: #f8fafc !important;
    font-weight: bold;
    text-transform: uppercase;
    font-size: {{ $orientation === 'portrait' ? '7.5px' : '8.5px' }};
    text-align: center;
    color: #334155;
}
tr:nth-child(even) {
    background-color: #f8fafc;
}
.center {
    text-align: center;
}
.right {
    text-align: right;
}

/* Status Badges for Print */
.status-badge {
    font-weight: bold;
    font-size: {{ $orientation === 'portrait' ? '7.5px' : '8.5px' }};
    text-transform: uppercase;
}
.status-diterima {
    color: #059669;
}
.status-pending {
    color: #d97706;
}
.status-ditolak {
    color: #dc2626;
}

/* Page Layout & Print Optimization */
@page {
    size: A4 {{ $orientation }};
    margin: 0.8cm;
}

@media print {
    body {
        padding: 0;
        background-color: #fff;
        color: #000;
        font-size: {{ $orientation === 'portrait' ? '8px' : '9.5px' }};
    }
    th {
        background-color: #e2e8f0 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    tr:nth-child(even) {
        background-color: #f8fafc !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .no-print {
        display: none;
    }
}

/* Floating back button for screen preview */
.preview-controls {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    background: #fff;
    padding: 10px 15px;
    border: 1px solid #cbd5e1;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}
.preview-controls button {
    background: #4f45b2;
    color: white;
    border: none;
    padding: 6px 12px;
    font-size: 11px;
    font-weight: bold;
    cursor: pointer;
}
