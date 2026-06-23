@props(['title' => 'Dashboard', 'active' => ''])
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ auth()->user()->store->name ?? 'Store Dashboard' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @if(app()->getLocale() === 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%}

    :root{
        --orange:#ea580c;
        --orange-dark:#c2410c;
        --orange-pale:#fff7ed;
        --orange-muted:#fed7aa;
        --dark:#1a1a1a;
        --muted:#6b7280;
        --border:#e5e7eb;
        --bg:#fff8f4;
        --white:#fff;
        --green:#16a34a;
        --red:#dc2626;
        --blue:#2563eb;
        --amber:#d97706;
        --gray-50:#f9fafb;
        --gray-100:#f3f4f6;
        --gray-200:#e5e7eb;
        --gray-300:#d1d5db;
        --gray-400:#9ca3af;
        --gray-500:#6b7280;
        --gray-600:#4b5563;
        --gray-900:#111827;
        --shadow-sm:0 1px 3px rgba(0,0,0,0.08);
        --shadow-md:0 4px 16px rgba(0,0,0,0.08);
        --radius:0.75rem;
    }

    body{
        font-family:'DM Sans',sans-serif;
        background:var(--bg);
        color:var(--gray-900);
        display:flex;
        min-height:100vh;
    }

    /* ── SIDEBAR ── */
    .sidebar{
        width:200px;min-height:100vh;
        background:var(--dark);
        display:flex;flex-direction:column;
        position:fixed;top:0;left:0;z-index:100;
    }
    .sidebar-brand{
        padding:20px 18px 16px;
        border-bottom:1px solid #2d2d2d;
    }
    .sidebar-brand h1{
        font-size:18px;font-weight:700;
        color:#fff;letter-spacing:.3px;
        font-family:'DM Sans',sans-serif;
        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    }
    .sidebar-brand h1 span{color:var(--orange)}
    .sidebar-brand .sidebar-sub{
        font-size:10px;color:#666;margin-top:2px;
        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    }
    .sidebar-brand .store-logo-wrap{
        width:36px;height:36px;border-radius:8px;
        background:var(--orange);
        display:flex;align-items:center;justify-content:center;
        color:#fff;font-weight:700;font-size:14px;
        margin-bottom:8px;overflow:hidden;flex-shrink:0;
    }
    .sidebar-brand .store-logo-wrap img{
        width:100%;height:100%;object-fit:cover;
    }
    .sidebar-nav{flex:1;padding:10px 0;overflow-y:auto}
    .nav-section{
        padding:8px 18px 4px;
        font-size:9px;font-weight:700;
        color:#444;text-transform:uppercase;letter-spacing:.8px;
    }
    .nav-item{
        display:flex;align-items:center;gap:10px;
        padding:11px 18px;
        color:#9ca3af;font-size:13.5px;
        text-decoration:none;
        border-left:3px solid transparent;
        transition:all .15s;cursor:pointer;
    }
    .sidebar .nav-item{color:#9ca3af}
    .sidebar .nav-item:hover{color:#fff;background:#252525}
    .sidebar .nav-item.active{color:#fff;background:#252525;border-left-color:var(--orange)}
    .nav-icon{font-size:14px;width:20px;text-align:center;flex-shrink:0}
    .sidebar-divider{
        height:1px;background:#2d2d2d;
        margin:6px 18px;
    }
    .sidebar-footer{
        padding:14px 18px;
        font-size:11px;color:#555;
        border-top:1px solid #2d2d2d;
    }

    /* ── MAIN ── */
    .admin-main{
        margin-left:200px;flex:1;
        min-height:100vh;
        display:flex;flex-direction:column;
    }

    /* ── TOPBAR ── */
    .admin-topbar{
        background:var(--white);
        border-bottom:1px solid var(--border);
        padding:14px 28px;
        display:flex;align-items:center;justify-content:space-between;
        position:sticky;top:0;z-index:50;
    }
    .topbar-title{font-size:18px;font-weight:700;color:var(--dark)}
    .topbar-title span{color:var(--orange)}
    .topbar-right{display:flex;align-items:center;gap:10px}
    .topbar-btn{
        background:var(--orange);color:#fff;
        border:none;border-radius:8px;
        padding:7px 16px;font-size:13px;font-weight:600;
        cursor:pointer;text-decoration:none;
        font-family:'DM Sans',sans-serif;
        transition:background .15s;white-space:nowrap;
    }
    .topbar-btn:hover{background:var(--orange-dark)}
    .topbar-ghost{
        background:transparent;color:var(--muted);
        border:1px solid var(--border);border-radius:8px;
        padding:7px 14px;font-size:13px;font-weight:500;
        text-decoration:none;transition:all .15s;white-space:nowrap;
    }
    .topbar-ghost:hover{border-color:var(--orange);color:var(--orange)}
    .topbar-avatar{
        width:34px;height:34px;border-radius:50%;
        background:var(--orange);
        display:flex;align-items:center;justify-content:center;
        color:#fff;font-weight:700;font-size:13px;flex-shrink:0;
    }

    /* ── ALERTS ── */
    .admin-alert{margin:16px 28px 0;padding:10px 16px;border-radius:8px;font-size:13px;font-weight:600}
    .admin-alert-success{background:#dcfce7;color:#16a34a}
    .admin-alert-error{background:#fee2e2;color:#dc2626}

    /* ── CONTENT ── */
    .admin-content{padding:24px 28px;flex:1}

    /* ── COMPONENTS ── */
    .section-title{font-size:16px;font-weight:700;color:var(--dark);margin-bottom:16px}
    .card{background:var(--white);border-radius:12px;border:1px solid var(--border);padding:20px}
    .card+.card{margin-top:16px}

    .stat-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
        gap:12px;margin-bottom:20px;
    }
    .stat-card{background:var(--white);border-radius:12px;padding:18px;border:1px solid var(--border)}
    .stat-card .sc-icon{font-size:20px;margin-bottom:6px}
    .sc-label{font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px}
    .sc-value{font-size:26px;font-weight:700;color:var(--orange);line-height:1.1}
    .sc-value.green{color:var(--green)}
    .sc-value.blue{color:var(--blue)}
    .sc-value.red{color:var(--red)}
    .sc-value.amber{color:var(--amber)}
    .sc-value.purple{color:#7c3aed}
    .sc-sub{font-size:11px;color:var(--muted);margin-top:3px}
    .sc-trend{font-size:11px;font-weight:600;margin-top:4px}
    .sc-trend.up{color:var(--green)}
    .sc-trend.down{color:var(--red)}

    /* Tables */
    table{width:100%;border-collapse:collapse}
    table th{font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;padding:8px 12px;border-bottom:1px solid var(--border);text-align:left;white-space:nowrap}
    table td{font-size:13px;color:var(--dark);padding:10px 12px;border-bottom:1px solid #f3f4f6}
    table tr:last-child td{border-bottom:none}
    table tr:hover td{background:#fafafa}

    /* Badges */
    .badge{display:inline-block;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap}
    .badge-green{background:#dcfce7;color:#166534}
    .badge-red{background:#fee2e2;color:#991b1b}
    .badge-amber{background:#fef3c7;color:#92400e}
    .badge-blue{background:#dbeafe;color:#1e40af}
    .badge-gray{background:#f1f5f9;color:#475569}
    .badge-orange{background:#fff7ed;color:#c2410c}
    .badge-teal{background:#ccfbf1;color:#0f766e}
    .badge-purple{background:#f3e8ff;color:#6b21a8}

    /* Action buttons */
    .act-btn{
        background:none;border:1px solid var(--border);border-radius:6px;
        padding:4px 10px;font-size:12px;cursor:pointer;color:var(--muted);
        font-family:'DM Sans',sans-serif;text-decoration:none;
        transition:all .15s;display:inline-flex;align-items:center;gap:4px;
    }
    .act-btn:hover{border-color:var(--orange);color:var(--orange)}
    .act-btn.red{border-color:#fecaca;color:#ef4444}
    .act-btn.red:hover{background:#fee2e2}
    .act-btn.green{border-color:#bbf7d0;color:#16a34a}
    .act-btn.green:hover{background:#dcfce7}

    .add-btn{
        background:var(--orange);color:#fff;border:none;border-radius:8px;
        padding:8px 16px;font-size:13px;font-weight:600;cursor:pointer;
        font-family:'DM Sans',sans-serif;text-decoration:none;
        transition:background .15s;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;
    }
    .add-btn:hover{background:var(--orange-dark)}

    /* Sub nav */
    .sub-nav{display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap}
    .sub-btn{
        padding:7px 14px;border-radius:8px;font-size:13px;font-weight:500;
        cursor:pointer;border:1px solid var(--border);
        background:var(--white);color:var(--muted);
        transition:all .15s;text-decoration:none;font-family:'DM Sans',sans-serif;
    }
    .sub-btn:hover,.sub-btn.active{background:var(--orange);color:#fff;border-color:var(--orange)}

    /* Two-col grid */
    .two-col{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
    @media(max-width:900px){.two-col{grid-template-columns:1fr}}

    /* Empty state */
    .admin-empty{text-align:center;padding:3rem 1rem;color:var(--muted);font-size:14px}

    /* Result count */
    .result-count{font-size:12px;color:var(--muted);margin-bottom:12px}

    /* Time filter bar */
    .time-filter-bar{
        display:flex;justify-content:space-between;align-items:flex-start;
        margin-bottom:24px;flex-wrap:wrap;gap:12px;
    }
    .time-filter-left{}
    .page-title{font-size:20px;font-weight:700;color:var(--dark);margin-bottom:2px}
    .page-subtitle{font-size:13px;color:var(--muted)}
    .time-filter-right{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
    .time-filter-tabs{
        display:flex;background:var(--white);
        border:1px solid var(--border);border-radius:10px;overflow:hidden;
    }
    .time-tab{
        padding:7px 14px;font-size:12px;font-weight:500;
        color:var(--muted);text-decoration:none;transition:all .15s;
        border-right:1px solid var(--border);white-space:nowrap;
    }
    .time-tab:last-child{border-right:none}
    .time-tab:hover{color:var(--dark)}
    .time-tab-active{background:var(--orange);color:#fff !important;font-weight:600}
    .btn-print{
        display:flex;align-items:center;gap:6px;
        padding:7px 14px;font-size:12px;font-weight:500;
        border-radius:8px;text-decoration:none;
        border:1px solid var(--border);color:var(--muted);
        background:var(--white);transition:all .15s;white-space:nowrap;
    }
    .btn-print:hover{border-color:var(--orange);color:var(--orange)}

    /* Status queue */
    .status-queue{display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:10px;margin-bottom:16px}
    .sq-card{
        background:var(--white);border-radius:10px;padding:14px;
        border:2px solid var(--border);text-align:center;cursor:pointer;
        text-decoration:none;transition:all .15s;display:block;
    }
    .sq-card:hover{border-color:var(--orange)}
    .sq-card.amber{border-color:#fde68a}
    .sq-card.blue{border-color:#bfdbfe}
    .sq-card.green{border-color:#bbf7d0}
    .sq-card.red{border-color:#fecaca}
    .sq-card.teal{border-color:#99f6e4}
    .sq-icon{font-size:18px;margin-bottom:4px}
    .sq-count{font-size:22px;font-weight:700;color:var(--dark);line-height:1}
    .sq-label{font-size:11px;color:var(--muted);margin-top:2px}

    /* Progress bars */
    .progress-bar-wrap{margin-bottom:12px}
    .pb-header{display:flex;justify-content:space-between;margin-bottom:4px;font-size:13px}
    .pb-track{background:var(--gray-100);border-radius:20px;height:8px;overflow:hidden}
    .pb-fill{height:100%;background:var(--orange);border-radius:20px}

    /* Chart card */
    .chart-card{background:var(--white);border-radius:12px;border:1px solid var(--border);padding:20px;margin-bottom:16px}
    .chart-card-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
    .chart-card-header h3{font-size:14px;font-weight:700;color:var(--dark)}
    .chart-subtitle{font-size:12px;color:var(--muted)}

    /* Info card */
    .info-card{
        background:var(--orange-pale);border:1px solid var(--orange-muted);
        border-radius:10px;padding:16px;margin-top:16px;
    }
    .info-card h4{font-size:13px;font-weight:700;color:var(--orange-dark);margin-bottom:6px}
    .info-card p{font-size:12px;color:var(--muted);line-height:1.6;margin:0}

    /* Alert banner */
    .alert-banner{
        background:#fef3c7;border:1px solid #fde68a;
        border-radius:10px;padding:12px 16px;
        display:flex;align-items:center;gap:10px;
        font-size:13px;font-weight:600;color:#92400e;margin-bottom:16px;
    }

    /* Nav badge */
    .nav-badge{
        min-width:18px;height:18px;
        background:var(--orange);color:#fff;
        border-radius:10px;font-size:10px;font-weight:700;
        display:flex;align-items:center;justify-content:center;
        padding:0 5px;flex-shrink:0;
    }

    /* Scrollbar */
    .sidebar-nav::-webkit-scrollbar{width:4px}
    .sidebar-nav::-webkit-scrollbar-track{background:#1a1a1a}
    .sidebar-nav::-webkit-scrollbar-thumb{background:#333}

    /* ── TOAST ── */
    #toast-container{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none}

    /* ── LANG SWITCHER ── */
    .admin-lang-switcher{display:flex;align-items:center;gap:3px}
    .admin-lang-btn{
        background:none;border:1px solid var(--border);border-radius:6px;
        padding:4px 8px;font-size:11px;font-weight:700;cursor:pointer;
        color:var(--muted);transition:all .15s;
        font-family:'Tajawal','DM Sans',sans-serif;line-height:1;
    }
    .admin-lang-btn:hover{border-color:var(--orange);color:var(--orange)}
    .admin-lang-btn.lang-active{background:var(--orange);border-color:var(--orange);color:#fff}

    /* ── RTL ── */
    [dir="rtl"] body{font-family:'Tajawal',sans-serif;text-align:right}
    [dir="rtl"] .sidebar{left:auto;right:0}
    [dir="rtl"] .admin-main{margin-left:0;margin-right:200px}
    [dir="rtl"] .nav-item{border-left:none;border-right:3px solid transparent}
    [dir="rtl"] .sidebar .nav-item.active{border-right-color:var(--orange)}
    [dir="rtl"] .admin-topbar{flex-direction:row-reverse}
    [dir="rtl"] .topbar-right{flex-direction:row-reverse}
    [dir="rtl"] table th{text-align:right}
    [dir="rtl"] .stat-card{text-align:right}
    [dir="rtl"] .two-col{direction:rtl}
    .publish-banner{background:#fff7ed;border:1.5px solid #fed7aa;border-radius:14px;padding:20px 24px;margin-bottom:24px;display:flex;align-items:flex-start;justify-content:space-between;gap:20px;flex-wrap:wrap}
    .publish-banner-body{display:flex;gap:16px;align-items:flex-start;flex:1}
    .publish-banner-icon{font-size:36px;flex-shrink:0}
    .publish-banner h3{font-size:16px;font-weight:700;color:var(--gray-900,#111827);margin:0 0 4px}
    .publish-banner p{font-size:13px;color:#6b7280;margin:0 0 10px}
    .publish-checklist{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:4px}
    .publish-checklist li{font-size:13px;color:#6b7280}
    .publish-checklist li.done{color:#059669;font-weight:600}
    .btn-publish{background:var(--orange,#ea580c);color:#fff;border:none;border-radius:8px;padding:10px 22px;font-size:14px;font-weight:700;cursor:pointer;white-space:nowrap}
    .btn-publish:hover{background:var(--orange-dark,#c2410c)}
    </style>
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        @php $store = auth()->user()->store; @endphp
        @if($store && $store->logo_path)
            <div class="store-logo-wrap"><img src="{{ asset($store->logo_path) }}" alt="{{ $store->name }}"></div>
        @else
            <div class="store-logo-wrap">{{ strtoupper(substr($store->name ?? 'S', 0, 1)) }}</div>
        @endif
        <h1>{{ Str::limit($store->name ?? 'My Store', 20) }}</h1>
        <div class="sidebar-sub">Store Dashboard</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Analytics</div>
        <a href="{{ route('store.dashboard') }}"           class="nav-item {{ $active === 'overview'   ? 'active' : '' }}"><span class="nav-icon">📊</span> Overview</a>
        @php
            $pendingOrderCount = 0;
            if (isset($currentStore) && $currentStore->id) {
                $pendingOrderCount = \App\Models\Order::where('store_id', $currentStore->id)->where('status', 'pending')->count();
            }
        @endphp
        <a href="{{ route('store.dashboard.orders') }}" class="nav-item {{ $active === 'orders' ? 'active' : '' }}" style="justify-content:space-between;">
            <span style="display:flex;align-items:center;gap:10px;"><span class="nav-icon">📦</span> Orders</span>
            @if($pendingOrderCount > 0)<span class="nav-badge">{{ $pendingOrderCount }}</span>@endif
        </a>
        <a href="{{ route('store.dashboard.inventory') }}" class="nav-item {{ $active === 'inventory'  ? 'active' : '' }}"><span class="nav-icon">📋</span> Inventory</a>
        <a href="{{ route('store.dashboard.customers') }}" class="nav-item {{ $active === 'customers'  ? 'active' : '' }}"><span class="nav-icon">👥</span> Customers</a>
        <a href="{{ route('store.dashboard.traffic') }}"   class="nav-item {{ $active === 'traffic'    ? 'active' : '' }}"><span class="nav-icon">📈</span> Traffic</a>
        <div class="sidebar-divider"></div>
        <div class="nav-section">Manage</div>
        <a href="{{ route('store.editor') }}"              class="nav-item {{ $active === 'editor'     ? 'active' : '' }}"><span class="nav-icon">🎨</span> Edit Store</a>
        <a href="{{ route('store.messages.index') }}"      class="nav-item {{ $active === 'messages'   ? 'active' : '' }}"><span class="nav-icon">💬</span> Messages</a>
        <div class="sidebar-divider"></div>
        @if($store)
        <a href="{{ route('stores.show', $store->slug) }}" target="_blank" class="nav-item"><span class="nav-icon">🔗</span> View Live Store</a>
        @endif
        <a href="{{ route('profile.edit') }}" class="nav-item"><span class="nav-icon">⚙️</span> Account Settings</a>
    </nav>

    <div class="sidebar-footer">Voxura v2.0</div>
</aside>

{{-- ── MAIN ── --}}
<div class="admin-main">

    {{-- Topbar --}}
    <div class="admin-topbar">
        <div class="topbar-title">
            {{ auth()->user()->store->name ?? 'My Store' }}
            <span style="font-size:13px;font-weight:400;color:var(--muted);margin-left:8px;">{{ $title }}</span>
        </div>
        <div class="topbar-right">
            {{-- Language Switcher --}}
            <form method="POST" action="{{ route('language.switch') }}" id="lang-form-store" style="display:none;">
                @csrf
                <input type="hidden" name="locale" id="lang-input-store" value="">
            </form>
            <div class="admin-lang-switcher">
                <button type="button" onclick="switchLangStore('en')" class="admin-lang-btn {{ app()->getLocale() === 'en' ? 'lang-active' : '' }}">EN</button>
                <button type="button" onclick="switchLangStore('ar')" class="admin-lang-btn {{ app()->getLocale() === 'ar' ? 'lang-active' : '' }}">ع</button>
            </div>
            @if(auth()->user()->store)
            <a href="{{ route('stores.show', auth()->user()->store->slug) }}" target="_blank" class="topbar-btn" style="font-size:12px;">View Store →</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="topbar-ghost" style="cursor:pointer;border:1px solid var(--border);background:transparent;font-family:'DM Sans',sans-serif;">⎋ Logout</button>
            </form>
            <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}</div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="admin-alert admin-alert-success" style="margin:16px 28px 0;">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="admin-alert admin-alert-error" style="margin:16px 28px 0;">✕ {{ session('error') }}</div>
    @endif

    {{-- Page Content --}}
    <div class="admin-content">
        {{ $slot }}
    </div>

</div>

{{-- Toast --}}
<div id="toast-container"></div>

<script>
(function () {
    'use strict';

    window.switchLangStore = function(locale) {
        document.getElementById('lang-input-store').value = locale;
        document.getElementById('lang-form-store').submit();
    };

    window.showToast = function (message, type) {
        type = type || 'success';
        var map = {
            success: { border:'#16A34A', bg:'#F0FDF4', icon:'✓' },
            error:   { border:'#DC2626', bg:'#FEF2F2', icon:'✕' },
            warning: { border:'#D97706', bg:'#FFFBEB', icon:'⚠' },
        };
        var s = map[type] || map.success;
        var t = document.createElement('div');
        t.style.cssText = 'background:'+s.bg+';border-left:3px solid '+s.border+';border-radius:8px;padding:10px 16px;font-size:13px;font-weight:500;color:#1a1a1a;box-shadow:0 2px 12px rgba(0,0,0,0.10);animation:slideInToast .2s ease;pointer-events:auto;display:flex;align-items:center;gap:8px;max-width:300px;';
        t.innerHTML = '<span>'+s.icon+'</span><span>'+message+'</span>';
        document.getElementById('toast-container').appendChild(t);
        setTimeout(function(){ t.remove(); }, 3500);
    };
})();
</script>

</body>
</html>
