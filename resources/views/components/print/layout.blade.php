@props([
    'title'       => 'Dokumen MAM Limpung',
    'orientation' => 'portrait',   // 'portrait' | 'landscape'
    'autoPrint'   => true,
    'margin'      => null,         // override @page margin, e.g. '1cm'
])
@php
    $pageMargin  = $margin ?? ($orientation === 'landscape' ? '0.8cm' : '12mm 15mm 12mm 15mm');
    $baseFontPx  = $orientation === 'landscape' ? '9px' : '10.5pt';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        /* ─── Page & Reset ────────────────────────────────────────────── */
        @page {
            size: A4 {{ $orientation }};
            margin: {{ $pageMargin }};
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: {{ $baseFontPx }};
            line-height: 1.35;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .print-page { width: 100%; }

        /* ─── Kop Surat ───────────────────────────────────────────────── */
        .kop-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-bottom: 8px;
            margin-bottom: 14px;
            position: relative;
        }
        .kop-container.bordered { border-bottom: 3px double #000; }
        .kop-logo {
            position: absolute;
            left: 0;
            top: 40%;
            transform: translateY(-50%);
            object-fit: contain;
            flex-shrink: 0;
            max-width: none !important;
            max-height: none !important;
        }
        .kop-body { text-align: center; }
        .kop-line-1 {
            font-family: Arial, sans-serif;
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .kop-line-2 {
            font-family: Arial, sans-serif;
            font-size: 14pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 2px 0 0;
        }
        .kop-line-3 {
            font-size: 8.5pt;
            font-style: italic;
            margin: 2px 0 0;
        }
        .kop-line-4 {
            font-size: 8pt;
            margin: 3px 0 0;
        }

        /* ─── Document Title / Subtitle ───────────────────────────────── */
        .doc-title {
            text-align: center;
            font-size: 11.5pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 10px 0 4px;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            margin: 0 0 16px;
        }

        /* ─── Section Header ──────────────────────────────────────────── */
        .section-header {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1.5px solid #000;
            padding: 2px 0;
            margin-top: 12px;
            margin-bottom: 6px;
            page-break-inside: avoid;
        }

        /* ─── Signature Block ─────────────────────────────────────────── */
        .signature-block {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .signature-col   { width: 45%; display: flex; flex-direction: column; }
        .signature-date  { margin-bottom: 8px; }
        .signature-title { margin-bottom: 6px; font-weight: bold; }
        .signature-name  { font-weight: bold; text-decoration: underline; }
        .signature-space { height: 52px; }

        /* ─── Action Bar — top bar for single-doc print ───────────────── */
        .action-bar {
            background: linear-gradient(90deg, #f1f5f9 0%, #e2e8f0 100%);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-bottom: 2px solid #cbd5e1;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
        }
        .action-bar-label {
            font-size: 12px;
            color: #475569;
            font-weight: bold;
            margin-right: 8px;
        }

        /* ─── Preview Controls — floating for report preview ──────────── */
        .preview-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            background: #fff;
            padding: 12px 16px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 6px;
            display: flex;
            gap: 8px;
        }

        /* ─── Shared Button ───────────────────────────────────────────── */
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 18px;
            font-size: 11px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: filter 0.15s;
            font-family: Arial, sans-serif;
            letter-spacing: 0.3px;
        }
        .action-btn:hover { filter: brightness(0.88); }
        .action-btn.primary   { background: #4f45b2; color: #fff; }
        .action-btn.secondary { background: #64748b; color: #fff; }

        /* ─── Status Badges ───────────────────────────────────────────── */
        .badge           { font-weight: bold; font-size: 7.5px; text-transform: uppercase; }
        .badge-diterima  { color: #059669; }
        .badge-pending   { color: #d97706; }
        .badge-ditolak   { color: #dc2626; }

        /* ─── Utility ─────────────────────────────────────────────────── */
        .center     { text-align: center; }
        .right      { text-align: right; }
        .font-mono  { font-family: monospace; }
        .text-bold  { font-weight: bold; }
        .text-upper { text-transform: uppercase; }
        .page-break { page-break-before: always; }

        /* ─── Print Media ─────────────────────────────────────────────── */
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: #fff; }
        }
    </style>

    {{-- Additional document-specific styles injected via named slot --}}
    {{ $styles ?? '' }}
</head>
<body>
    {{ $slot }}

    @if($autoPrint && ! request()->boolean('embed'))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => { window.print(); }, 500);
        });
    </script>
    @endif
</body>
</html>
