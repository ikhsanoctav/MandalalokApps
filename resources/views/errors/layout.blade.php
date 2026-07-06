<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error') - Mandalaloka</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo_Mandalaloka.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0f2e5c;
            --primary-light: #1a498b;
            --gold: #d4af37;
            --gold-dark: #b8902d;
            --accent: @yield('accent', '#dc2626');
            --accent-bg: @yield('accent_bg', '#fef2f2');
            --accent-border: @yield('accent_border', '#fecaca');
            --bg: #f0f2f5;
            --card-bg: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            min-height: 100vh;
            padding: 24px 16px;
            color: var(--text-dark);
        }

        .page-wrapper {
            max-width: 900px;
            margin: 0 auto;
        }

        /* ─── Top Brand Bar ─── */
        .brand-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding: 0 4px;
        }
        .brand-bar img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }
        .brand-bar .brand-text {
            display: flex;
            flex-direction: column;
        }
        .brand-bar .brand-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 0.3px;
        }
        .brand-bar .brand-sub {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ─── Status Card ─── */
        .status-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.04);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .status-header {
            padding: 24px 32px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .status-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-light);
        }
        .status-time {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
            font-variant-numeric: tabular-nums;
        }

        .status-body {
            padding: 20px 32px 28px;
            display: flex;
            gap: 40px;
        }

        /* Left Column: Error Code */
        .status-left {
            flex-shrink: 0;
            min-width: 160px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .error-code {
            font-size: 88px;
            font-weight: 900;
            line-height: 1;
            color: var(--text-dark);
            letter-spacing: -3px;
            font-variant-numeric: tabular-nums;
        }
        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            background: var(--accent-bg);
            color: var(--accent);
            border: 1px solid var(--accent-border);
            width: fit-content;
        }
        .error-badge i {
            font-size: 10px;
        }

        .meta-list {
            margin-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .meta-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-light);
        }
        .meta-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            word-break: break-all;
        }
        .meta-value.mono {
            font-family: 'SF Mono', 'Fira Code', 'Cascadia Code', monospace;
            font-size: 12px;
        }
        .meta-value code {
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-family: 'SF Mono', 'Fira Code', 'Cascadia Code', monospace;
            color: var(--accent);
            border: 1px solid var(--border);
        }

        /* Divider */
        .status-divider {
            width: 1px;
            background: var(--border);
            flex-shrink: 0;
        }

        /* Right Column: Description */
        .status-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 12px;
        }
        .status-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.3;
        }
        .status-desc {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* Actions */
        .status-actions {
            display: flex;
            gap: 10px;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            font-family: inherit;
        }
        .btn-accent {
            background: var(--accent);
            color: white;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
        }
        .btn-accent:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 2px 8px rgba(15, 46, 92, 0.25);
        }
        .btn-primary:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }
        .btn-ghost {
            background: #f1f5f9;
            color: var(--primary);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover {
            background: #e2e8f0;
        }
        .action-hint {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: -4px;
        }

        /* ─── Advisory Card ─── */
        .advisory-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.04);
            border: 1px solid var(--border);
            padding: 24px 32px;
            margin-bottom: 16px;
        }
        .advisory-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        .advisory-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .advisory-icon.amber {
            background: #fef3c7;
            color: #d97706;
        }
        .advisory-icon.blue {
            background: #dbeafe;
            color: var(--primary);
        }
        .advisory-title {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-light);
        }
        .advisory-body {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.7;
        }
        .advisory-body strong {
            color: var(--text-dark);
        }
        .advisory-highlight {
            margin-top: 12px;
            padding: 12px 16px;
            background: #f8fafc;
            border-radius: 10px;
            border-left: 3px solid var(--gold);
            font-size: 13px;
            color: #475569;
            line-height: 1.7;
        }

        /* ─── Debug Detail ─── */
        .debug-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid var(--border);
            padding: 20px 32px;
            margin-bottom: 16px;
        }
        .debug-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-light);
            margin-bottom: 8px;
        }
        .debug-text {
            font-size: 12px;
            color: #475569;
            font-family: 'SF Mono', 'Fira Code', 'Cascadia Code', monospace;
            line-height: 1.7;
            word-break: break-all;
            background: #f8fafc;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        /* ─── Footer ─── */
        .page-footer {
            text-align: center;
            font-size: 11px;
            color: var(--text-light);
            padding: 8px 0 16px;
        }

        /* ─── Responsive ─── */
        @media (max-width: 640px) {
            body { padding: 12px 8px; }
            .status-body { flex-direction: column; gap: 20px; }
            .status-divider { width: 100%; height: 1px; }
            .status-left { min-width: auto; }
            .error-code { font-size: 64px; }
            .status-header { padding: 16px 20px 0; }
            .status-body { padding: 16px 20px 20px; }
            .advisory-card { padding: 16px 20px; }
            .debug-card { padding: 16px 20px; }
            .status-actions { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        {{-- Brand Bar --}}
        <div class="brand-bar">
            <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo" onerror="this.style.display='none'">
            <div class="brand-text">
                <span class="brand-name">MANDALALOKA</span>
                <span class="brand-sub">Sistem Pendataan UMKM &mdash; Kecamatan Mandalajati</span>
            </div>
        </div>

        @yield('content')

        <div class="page-footer">
            &copy; {{ date('Y') }} Sistem Pendataan UMKM &mdash; Kecamatan Mandalajati, Kota Bandung
        </div>
    </div>
</body>
</html>
