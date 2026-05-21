{{--
    Gaya tambahan laporan export PDF (ledger & seragam).
    Dasar halaman (@page, body, kop) sudah di x-print.layout.
--}}
.print-ledger-wrapper {
    width: 100%;
}

.report-header {
    text-align: center;
    margin-bottom: 14px;
}

.report-header h3 {
    font-family: Arial, Helvetica, sans-serif;
    font-size: {{ $orientation === 'landscape' ? '12pt' : '11pt' }};
    margin: 0 0 4px;
    text-transform: uppercase;
    font-weight: bold;
    text-decoration: underline;
}

.report-meta {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 6px;
    font-family: Arial, sans-serif;
    font-size: {{ $orientation === 'landscape' ? '9pt' : '8.5pt' }};
    margin-bottom: 10px;
    border-bottom: 1px dashed #000;
    padding-bottom: 6px;
}

.print-ledger-wrapper table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
    table-layout: auto;
}

.print-ledger-wrapper th,
.print-ledger-wrapper td {
    border: 1px solid #333;
    padding: {{ $orientation === 'landscape' ? '5px 6px' : '4px 5px' }};
    vertical-align: middle;
    word-wrap: break-word;
}

.print-ledger-wrapper th {
    background-color: #f1f5f9 !important;
    font-weight: bold;
    text-transform: uppercase;
    font-size: {{ $orientation === 'landscape' ? '8.5pt' : '7.5pt' }};
    text-align: center;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

.print-ledger-wrapper tr:nth-child(even) td {
    background-color: #f8fafc !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

.status-badge {
    font-weight: bold;
    font-size: {{ $orientation === 'landscape' ? '8.5pt' : '7.5pt' }};
    text-transform: uppercase;
}

.status-diterima { color: #059669; }
.status-pending { color: #d97706; }
.status-ditolak { color: #dc2626; }

@media print {
    .print-ledger-wrapper th {
        background-color: #e2e8f0 !important;
    }
}
