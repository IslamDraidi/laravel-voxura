@props(['title' => 'Admin', 'section' => '', 'active' => '', 'pageTitle' => null])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — Voxura Admin</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        width:200px;
        min-height:100vh;
        background:var(--dark);
        display:flex;
        flex-direction:column;
        position:fixed;
        top:0;left:0;
        z-index:100;
    }
    .sidebar-brand{
        padding:20px 18px 16px;
        border-bottom:1px solid #2d2d2d;
    }
    .sidebar-brand h1{
        font-size:20px;font-weight:700;
        color:#fff;letter-spacing:.5px;
        font-family:'DM Sans',sans-serif;
    }
    .sidebar-brand h1 span{color:var(--orange)}
    .sidebar-brand .sidebar-sub{
        font-size:11px;color:#666;margin-top:2px;
    }
    .sidebar-nav{
        flex:1;padding:10px 0;overflow-y:auto;
    }
    .nav-item{
        display:flex;align-items:center;gap:10px;
        padding:12px 18px;
        color:#9ca3af;font-size:13.5px;
        text-decoration:none;
        border-left:3px solid transparent;
        transition:all .15s;
        cursor:pointer;
    }
    /* Override app.css a:not(.btn) specificity */
    .sidebar .nav-item{color:#9ca3af}
    .sidebar .nav-item:hover{color:#fff;background:#252525}
    .sidebar .nav-item.active{color:#fff;background:#252525;border-left-color:var(--orange)}
    .nav-icon{font-size:15px;width:20px;text-align:center;flex-shrink:0}
    .sidebar-footer{
        padding:14px 18px;
        font-size:11px;color:#555;
        border-top:1px solid #2d2d2d;
    }

    /* ── MAIN ── */
    .admin-main{
        margin-left:200px;
        flex:1;
        min-height:100vh;
        display:flex;
        flex-direction:column;
    }

    /* ── TOPBAR ── */
    .admin-topbar{
        background:var(--white);
        border-bottom:1px solid var(--border);
        padding:14px 28px;
        display:flex;align-items:center;
        justify-content:space-between;
        position:sticky;top:0;z-index:50;
    }
    .topbar-title{
        font-size:18px;font-weight:700;
        color:var(--dark);
    }
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
        color:#fff;font-weight:700;font-size:13px;
        flex-shrink:0;
    }

    /* ── SUB-NAV TABS ── */
    .admin-subnav{
        background:var(--white);
        border-bottom:1px solid var(--border);
        padding:10px 28px;
        display:flex;gap:8px;flex-wrap:wrap;
    }
    .subnav-tab{
        display:inline-flex;align-items:center;gap:6px;
        padding:7px 18px;
        border-radius:40px;
        font-size:13px;font-weight:600;
        text-decoration:none;
        border:2px solid var(--border);
        background:var(--white);
        color:var(--muted);
        transition:all .15s;
        white-space:nowrap;
        font-family:'DM Sans',sans-serif;
    }
    .admin-subnav .subnav-tab{color:var(--muted)}
    .admin-subnav .subnav-tab:hover{
        border-color:var(--orange);
        color:var(--orange);
        background:var(--orange-pale);
    }
    .admin-subnav .subnav-tab.active{
        background:var(--orange);
        border-color:var(--orange);
        color:#fff;
    }
    .tab-icon{font-size:14px}

    /* ── ALERTS ── */
    .admin-alert{
        margin:16px 28px 0;
        padding:10px 16px;border-radius:8px;
        font-size:13px;font-weight:600;
    }
    .admin-alert-success{background:#dcfce7;color:#16a34a}
    .admin-alert-error{background:#fee2e2;color:#dc2626}

    /* ── CONTENT ── */
    .admin-content{
        padding:24px 28px;
        flex:1;
    }

    /* ── COMPONENTS ── */
    .section-title{
        font-size:16px;font-weight:700;
        color:var(--dark);margin-bottom:16px;
    }

    .card{
        background:var(--white);
        border-radius:12px;
        border:1px solid var(--border);
        padding:20px;
    }
    .card + .card{margin-top:16px}

    .stat-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
        gap:12px;margin-bottom:20px;
    }
    .stat-card{
        background:var(--white);border-radius:12px;
        padding:18px;border:1px solid var(--border);
    }
    .stat-card .sc-icon{font-size:20px;margin-bottom:6px}
    .sc-label{
        font-size:11px;font-weight:600;color:var(--muted);
        text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;
    }
    .sc-value{
        font-size:26px;font-weight:700;color:var(--orange);line-height:1.1;
    }
    .sc-value.green{color:var(--green)}
    .sc-value.blue{color:var(--blue)}
    .sc-value.red{color:var(--red)}
    .sc-sub{font-size:11px;color:var(--muted);margin-top:3px}

    /* Tables */
    table{width:100%;border-collapse:collapse}
    table th{
        font-size:11px;font-weight:600;color:var(--muted);
        text-transform:uppercase;letter-spacing:.5px;
        padding:8px 12px;border-bottom:1px solid var(--border);
        text-align:left;white-space:nowrap;
    }
    table td{
        font-size:13px;color:var(--dark);
        padding:10px 12px;border-bottom:1px solid #f3f4f6;
    }
    table tr:last-child td{border-bottom:none}
    table tr:hover td{background:#fafafa}

    /* Badges */
    .badge{
        display:inline-block;padding:2px 8px;
        border-radius:20px;font-size:11px;font-weight:600;
        white-space:nowrap;
    }
    .badge-green{background:#dcfce7;color:#166534}
    .badge-red{background:#fee2e2;color:#991b1b}
    .badge-amber{background:#fef3c7;color:#92400e}
    .badge-blue{background:#dbeafe;color:#1e40af}
    .badge-gray{background:#f1f5f9;color:#475569}
    .badge-orange{background:#fff7ed;color:#c2410c}

    /* Action buttons */
    .act-btn{
        background:none;border:1px solid var(--border);
        border-radius:6px;padding:4px 10px;
        font-size:12px;cursor:pointer;color:var(--muted);
        font-family:'DM Sans',sans-serif;
        text-decoration:none;transition:all .15s;
        display:inline-flex;align-items:center;gap:4px;
    }
    .act-btn:hover{border-color:var(--orange);color:var(--orange)}
    .act-btn.red{border-color:#fecaca;color:#ef4444}
    .act-btn.red:hover{background:#fee2e2}
    .act-btn.green{border-color:#bbf7d0;color:#16a34a}
    .act-btn.green:hover{background:#dcfce7}

    .add-btn{
        background:var(--orange);color:#fff;
        border:none;border-radius:8px;
        padding:8px 16px;font-size:13px;
        font-weight:600;cursor:pointer;
        font-family:'DM Sans',sans-serif;
        text-decoration:none;transition:background .15s;
        display:inline-flex;align-items:center;gap:6px;
        white-space:nowrap;
    }
    .add-btn:hover{background:var(--orange-dark)}

    /* Sub nav (legacy, within page content) */
    .sub-nav{
        display:flex;gap:8px;
        margin-bottom:20px;flex-wrap:wrap;
    }
    .sub-btn{
        padding:7px 14px;border-radius:8px;
        font-size:13px;font-weight:500;cursor:pointer;
        border:1px solid var(--border);
        background:var(--white);color:var(--muted);
        transition:all .15s;text-decoration:none;
        font-family:'DM Sans',sans-serif;
    }
    .sub-btn:hover,.sub-btn.active{
        background:var(--orange);color:#fff;
        border-color:var(--orange);
    }

    /* Search bar */
    .search-bar{
        display:flex;gap:8px;margin-bottom:16px;
    }
    .search-bar input,.search-bar select{
        border:1px solid var(--border);border-radius:8px;
        padding:8px 12px;font-size:13px;
        color:var(--dark);background:var(--white);
        outline:none;font-family:'DM Sans',sans-serif;
    }
    .search-bar input{flex:1}
    .search-bar input:focus,.search-bar select:focus{border-color:var(--orange)}

    /* Form elements */
    .form-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:14px;
    }
    .form-group{display:flex;flex-direction:column;gap:5px}
    .form-label{
        font-size:11px;font-weight:600;
        text-transform:uppercase;letter-spacing:.06em;
        color:var(--muted);
    }
    .form-input,.form-select{
        padding:8px 10px;border:1px solid var(--border);
        border-radius:8px;font-size:13px;
        font-family:'DM Sans',sans-serif;
        color:var(--dark);outline:none;
        transition:border-color .15s;width:100%;
        background:var(--white);
    }
    .form-input:focus,.form-select:focus{border-color:var(--orange)}
    .form-textarea{
        padding:8px 10px;border:1px solid var(--border);
        border-radius:8px;font-size:13px;
        font-family:'Courier New',monospace;
        color:var(--dark);outline:none;
        transition:border-color .15s;width:100%;
        background:var(--white);resize:vertical;min-height:120px;
    }
    .form-textarea:focus{border-color:var(--orange)}
    .form-error{font-size:12px;color:#ef4444;margin-top:2px}

    /* Info banner */
    .info-banner{
        background:var(--orange-pale);border:1px solid var(--orange-muted);
        border-radius:10px;padding:14px 16px;
        display:flex;gap:10px;align-items:flex-start;
        font-size:13px;color:var(--dark);margin-bottom:16px;
        line-height:1.6;
    }

    /* Two-col grid */
    .two-col{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
    @media(max-width:900px){.two-col{grid-template-columns:1fr}}

    /* Empty state */
    .admin-empty{
        text-align:center;padding:3rem 1rem;
        color:var(--muted);font-size:14px;
    }

    /* Result count */
    .result-count{font-size:12px;color:var(--muted);margin-bottom:12px}

    /* Scrollbar */
    .sidebar-nav::-webkit-scrollbar{width:4px}
    .sidebar-nav::-webkit-scrollbar-track{background:#1a1a1a}
    .sidebar-nav::-webkit-scrollbar-thumb{background:#333}
    </style>
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <h1>Vox<span>ura</span></h1>
        <div class="sidebar-sub">Admin Panel</div>
    </div>

    <nav class="sidebar-nav">
        <a href="/admin"           class="nav-item {{ $section === 'dashboard' ? 'active' : '' }}"><span class="nav-icon">⊞</span> Dashboard</a>
        <a href="/admin/orders"    class="nav-item {{ $section === 'sales' ? 'active' : '' }}"><span class="nav-icon">🛒</span> Sales</a>
        <a href="/admin/customers" class="nav-item {{ $section === 'customers' ? 'active' : '' }}"><span class="nav-icon">👥</span> Customers</a>
        <a href="/admin/products"  class="nav-item {{ $section === 'catalog' ? 'active' : '' }}"><span class="nav-icon">📦</span> Catalog</a>
        <a href="/admin/cms/pages"   class="nav-item {{ $section === 'cms' ? 'active' : '' }}"><span class="nav-icon">🖼️</span> CMS</a>
        <a href="/admin/coupons"   class="nav-item {{ $section === 'marketing' ? 'active' : '' }}"><span class="nav-icon">🏷️</span> Marketing</a>
        <a href="/admin/reports"   class="nav-item {{ $section === 'reporting' ? 'active' : '' }}"><span class="nav-icon">📈</span> Reporting</a>
        <a href="/admin/shipping"  class="nav-item {{ $section === 'configure' ? 'active' : '' }}"><span class="nav-icon">⚙️</span> Configure</a>
    </nav>

    <div class="sidebar-footer">Voxura v2.0</div>
</aside>

{{-- ── MAIN ── --}}
<div class="admin-main">

    {{-- Topbar --}}
    <div class="admin-topbar">
        <div class="topbar-title">{{ $pageTitle ?? $title }}</div>
        <div class="topbar-right">
<a href="{{ route('admin.preview.enable') }}" target="_blank" class="topbar-ghost" title="Browse the storefront as a customer with interactive features hidden">👁 View as Customer</a>
            <a href="/" class="topbar-ghost">← Store</a>
            <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
        </div>
    </div>

    {{-- Sub-nav tabs --}}
    @if($section === 'sales')
    <div class="admin-subnav">
        <a href="/admin/orders"       class="subnav-tab {{ $active === 'orders'       ? 'active' : '' }}"><span class="tab-icon">🛒</span> Orders</a>
        <a href="/admin/shipments"    class="subnav-tab {{ $active === 'shipments'    ? 'active' : '' }}"><span class="tab-icon">📬</span> Shipments</a>
        <a href="/admin/invoices"     class="subnav-tab {{ $active === 'invoices'     ? 'active' : '' }}"><span class="tab-icon">🧾</span> Invoices</a>
        <a href="/admin/refunds"      class="subnav-tab {{ $active === 'refunds'      ? 'active' : '' }}"><span class="tab-icon">🔄</span> Refunds</a>
        <a href="/admin/transactions" class="subnav-tab {{ $active === 'transactions' ? 'active' : '' }}"><span class="tab-icon">💳</span> Transactions</a>
        <a href="/admin/rma"          class="subnav-tab {{ $active === 'rma'          ? 'active' : '' }}"><span class="tab-icon">↩️</span> RMA</a>
    </div>
    @elseif($section === 'catalog')
    <div class="admin-subnav">
        <a href="/admin/products"            class="subnav-tab {{ $active === 'products'    ? 'active' : '' }}"><span class="tab-icon">📦</span> Products</a>
        <a href="/admin/categories"         class="subnav-tab {{ $active === 'categories'  ? 'active' : '' }}"><span class="tab-icon">🗂️</span> Categories</a>
        <a href="/admin/attributes"         class="subnav-tab {{ $active === 'attributes'  ? 'active' : '' }}"><span class="tab-icon">✏️</span> Attributes</a>
        <a href="/admin/attribute-families" class="subnav-tab {{ $active === 'attr-families' ? 'active' : '' }}"><span class="tab-icon">🗃️</span> Attribute Families</a>
    </div>
    @elseif($section === 'customers')
    <div class="admin-subnav">
        <a href="/admin/customers"     class="subnav-tab {{ $active === 'customers' ? 'active' : '' }}"><span class="tab-icon">👥</span> Customers</a>
        <a href="/admin/customer-groups" class="subnav-tab {{ $active === 'groups'  ? 'active' : '' }}"><span class="tab-icon">👪</span> Groups</a>
        <a href="/admin/reviews"       class="subnav-tab {{ $active === 'reviews'   ? 'active' : '' }}"><span class="tab-icon">⭐</span> Reviews</a>
        <a href="/admin/gdpr"          class="subnav-tab {{ $active === 'gdpr'      ? 'active' : '' }}"><span class="tab-icon">🔒</span> GDPR Data Requests</a>
    </div>
    @elseif($section === 'marketing')
    <div class="admin-subnav">
        <a href="/admin/coupons"         class="subnav-tab {{ $active === 'promotions'     ? 'active' : '' }}"><span class="tab-icon">🏷️</span> Promotions</a>
        <a href="/admin/seo"             class="subnav-tab {{ $active === 'seo'            ? 'active' : '' }}"><span class="tab-icon">🔍</span> Search &amp; SEO</a>
    </div>
    @elseif($section === 'cms')
    <div class="admin-subnav">
        <a href="/admin/cms/pages"        class="subnav-tab {{ $active === 'pages'   ? 'active' : '' }}"><span class="tab-icon">📄</span> Pages</a>
        <a href="/admin/banners"          class="subnav-tab {{ $active === 'banners' ? 'active' : '' }}"><span class="tab-icon">🖼️</span> Banners</a>
        <a href="/admin/email-templates"  class="subnav-tab {{ $active === 'emails'  ? 'active' : '' }}"><span class="tab-icon">📧</span> Email Templates</a>
    </div>
    @elseif($section === 'reporting')
    <div class="admin-subnav">
        <a href="/admin/reports"           class="subnav-tab {{ $active === 'sales'     ? 'active' : '' }}"><span class="tab-icon">📈</span> Sales</a>
        <a href="/admin/reports/customers" class="subnav-tab {{ $active === 'customers' ? 'active' : '' }}"><span class="tab-icon">👥</span> Customers</a>
        <a href="/admin/reports/products"  class="subnav-tab {{ $active === 'products'  ? 'active' : '' }}"><span class="tab-icon">📦</span> Products</a>
    </div>
    @elseif($section === 'configure')
    <div class="admin-subnav">
        <a href="/admin/shipping/methods" class="subnav-tab {{ $active === 'shipping' ? 'active' : '' }}"><span class="tab-icon">🚚</span> Shipping Methods</a>
        <a href="/admin/shipping/zones"   class="subnav-tab {{ $active === 'zones'    ? 'active' : '' }}"><span class="tab-icon">🌍</span> Shipping Zones</a>
        <a href="/admin/tax"              class="subnav-tab {{ $active === 'tax'      ? 'active' : '' }}"><span class="tab-icon">🧾</span> Tax Rates</a>
    </div>
    @endif

    {{-- Alerts --}}
    @if(session('success'))
        <div class="admin-alert admin-alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="admin-alert admin-alert-error">✕ {{ session('error') }}</div>
    @endif

    {{-- Page Content --}}
    <div class="admin-content">
        {{ $slot }}
    </div>

</div>

</body>
</html>
