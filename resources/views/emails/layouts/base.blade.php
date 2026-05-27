<!DOCTYPE html>
<html lang="id" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'MAM Limpung')</title>
    <style>
        /* Reset */
        *, *::before, *::after { box-sizing: border-box; }
        body, html { margin: 0; padding: 0; width: 100% !important; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f1f5f9;
        }
        img { border: 0; max-width: 100%; display: block; }
        a { color: #4f45b2; text-decoration: none; }
        a:hover { text-decoration: underline; }

        /* Wrapper */
        .email-wrapper {
            max-width: 600px;
            margin: 32px auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #4f45b2 0%, #6366f1 60%, #818cf8 100%);
            padding: 32px 40px;
            text-align: left;
        }
        .email-header .school-name {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin: 0 0 2px 0;
        }
        .email-header .school-subtitle {
            font-size: 11px;
            color: rgba(255,255,255,0.7);
            font-family: 'Courier New', monospace;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }
        .email-header-divider {
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin: 20px 0 16px;
        }
        .email-header .email-badge {
            display: inline-block;
            font-size: 10px;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.9);
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 3px 10px;
        }

        /* Body */
        .email-body {
            padding: 36px 40px;
        }
        .email-title {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }
        .email-subtitle {
            font-size: 13px;
            color: #64748b;
            margin: 0 0 28px 0;
        }
        .email-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 24px 0;
        }

        /* Info Card */
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #4f45b2;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .info-card-label {
            font-size: 10px;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            display: block;
            margin-bottom: 4px;
        }
        .info-card-value {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }

        /* Data Grid */
        .data-grid {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .data-grid td {
            padding: 10px 14px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        .data-grid td:first-child {
            color: #64748b;
            font-weight: 500;
            width: 42%;
            white-space: nowrap;
        }
        .data-grid td:last-child {
            color: #0f172a;
            font-weight: 600;
        }
        .data-grid tr:last-child td { border-bottom: none; }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 2px;
        }
        .status-diterima { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .status-pending  { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
        .status-ditolak  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* CTA Button */
        .cta-btn {
            display: inline-block;
            padding: 13px 28px;
            background: #4f45b2;
            color: #ffffff !important;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none !important;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }
        .cta-btn:hover { background: #6366f1; }

        /* Alert */
        .alert-box {
            padding: 14px 18px;
            border-radius: 2px;
            margin: 20px 0;
            font-size: 13px;
        }
        .alert-info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }

        /* Footer */
        .email-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px 40px;
            text-align: center;
        }
        .email-footer p {
            font-size: 11px;
            color: #94a3b8;
            margin: 0 0 4px 0;
            line-height: 1.6;
        }
        .email-footer .footer-brand {
            font-size: 12px;
            font-weight: 700;
            color: #4f45b2;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <div class="school-name">MAM Limpung</div>
            <div class="school-subtitle">Madrasah Aliyah Muhammadiyah Limpung</div>
            <div class="email-header-divider"></div>
            <span class="email-badge">@yield('badge', 'Notifikasi Sistem')</span>
        </div>

        <!-- Body -->
        <div class="email-body">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p class="footer-brand">MAM LIMPUNG</p>
            <p>Madrasah Aliyah Muhammadiyah Limpung, Batang, Jawa Tengah</p>
            <p>Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} MAM Limpung. Seluruh Hak Dilindungi.</p>
        </div>
    </div>
</body>
</html>
