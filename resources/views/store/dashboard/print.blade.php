<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($section) }} Report — {{ $store->name }}</title>
    <style>
        body{font-family:Arial,sans-serif;color:#1a1a1a;font-size:13px;margin:0;padding:20px}
        .print-header{
            display:flex;justify-content:space-between;align-items:flex-start;
            margin-bottom:32px;padding-bottom:16px;border-bottom:2px solid #ea580c;
        }
        .print-logo{font-size:20px;font-weight:800;letter-spacing:-0.5px}
        .print-logo span{color:#ea580c}
        .print-meta{text-align:right;font-size:12px;color:#666}
        h1{font-size:22px;font-weight:800;margin:0 0 4px;color:#1a1a1a}
        h2{font-size:15px;font-weight:700;margin:24px 0 12px;color:#1a1a1a;border-bottom:1px solid #eee;padding-bottom:6px}
        .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:32px}
        .stat-box{border:1px solid #e0ddd9;border-radius:10px;padding:16px}
        .stat-value{font-size:22px;font-weight:800;color:#1a1a1a;margin-bottom:2px}
        .stat-label{font-size:12px;color:#888}
        .stat-sub{font-size:11px;color:#aaa;margin-top:2px}
        table{width:100%;border-collapse:collapse;margin-bottom:24px}
        th{background:#f8f7f5;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#888;padding:10px 12px;text-align:left}
        td{padding:10px 12px;border-bottom:1px solid #f0ede8;font-size:13px}
        .badge{display:inline-block;padding:3px 8px;border-radius:20px;font-size:11px;font-weight:600}
        .badge-orange{background:#fff0e8;color:#ea580c}
        .badge-green{background:#dcfce7;color:#166534}
        .badge-amber{background:#fef3c7;color:#92400e}
        .badge-red{background:#fee2e2;color:#991b1b}
        .badge-blue{background:#dbeafe;color:#1e40af}
        .badge-gray{background:#f1f5f9;color:#475569}
        .progress-row{margin-bottom:10px}
        .pb-label{display:flex;justify-content:space-between;font-size:12px;margin-bottom:3px}
        .pb-track{background:#f0ede8;border-radius:20px;height:7px;overflow:hidden}
        .pb-fill{height:100%;background:#ea580c;border-radius:20px}
        .print-footer{
            margin-top:40px;padding-top:16px;border-top:1px solid #e0ddd9;
            display:flex;justify-content:space-between;font-size:11px;color:#aaa;
        }
        .no-print{margin-bottom:20px;display:flex;gap:8px}
        .btn-primary{background:#ea580c;color:#fff;border:none;border-radius:8px;padding:10px 20px;font-size:14px;font-weight:700;cursor:pointer}
        .btn-secondary{background:#f0ede8;color:#555;border:none;border-radius:8px;padding:10px 20px;font-size:14px;cursor:pointer}
        @media print{.no-print{display:none!important}}
    </style>
</head>
<body>

<div class="no-print">
    <button class="btn-primary" onclick="window.print()">🖨️ Print / Save as PDF</button>
    <button class="btn-secondary" onclick="window.close()">Close</button>
</div>

<div class="print-header">
    <div>
        <div class="print-logo"><span>VOX</span>URA</div>
        <div style="font-size:12px;color:#888;margin-top:2px">Powered by Voxura</div>
    </div>
    <div class="print-meta">
        <strong>{{ $store->name }}</strong><br>
        {{ ucfirst($section) }} Report<br>
        Period: {{ ucfirst($range) }}<br>
        Generated: {{ now()->format('M d, Y H:i') }}
    </div>
</div>

@include('store.dashboard.partials.print-' . $section)

<div class="print-footer">
    <span>{{ $store->name }} — Voxura Store Report</span>
    <span>Confidential · Do not distribute</span>
    <span>© {{ date('Y') }} Voxura</span>
</div>

</body>
</html>
