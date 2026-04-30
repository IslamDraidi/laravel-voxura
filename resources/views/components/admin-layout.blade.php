@props(['title' => 'Admin', 'section' => '', 'active' => '', 'pageTitle' => null])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    .form-input:-webkit-autofill,
    .form-input:-webkit-autofill:hover,
    .form-input:-webkit-autofill:focus{
        -webkit-box-shadow:0 0 0px 1000px #fff inset !important;
        -webkit-text-fill-color:#1a1a1a !important;
        caret-color:#1a1a1a;
    }
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

    /* ── TOAST ── */
    #toast-container{
        position:fixed;top:20px;right:20px;z-index:9999;
        display:flex;flex-direction:column;gap:8px;pointer-events:none;
    }
    @keyframes slideInToast{
        from{transform:translateX(20px);opacity:0}
        to{transform:translateX(0);opacity:1}
    }

    /* ── DRAWER ── */
    #admin-drawer-overlay{
        display:none;position:fixed;inset:0;
        background:rgba(0,0,0,0.4);z-index:200;
    }
    #admin-drawer{
        display:none;position:fixed;top:0;right:0;
        height:100vh;width:520px;max-width:100vw;
        background:#fff;z-index:201;overflow-y:auto;
        box-shadow:-4px 0 24px rgba(0,0,0,0.12);
        transform:translateX(100%);
        transition:transform 0.25s ease;
    }
    @media(max-width:600px){#admin-drawer{width:100vw}}
    #admin-drawer-header{
        display:flex;align-items:center;justify-content:space-between;
        padding:16px 20px;border-bottom:1px solid var(--border);
        position:sticky;top:0;background:#fff;z-index:5;
    }
    #admin-drawer-title{font-size:15px;font-weight:700;color:var(--dark)}
    #admin-drawer-close{
        background:none;border:none;cursor:pointer;
        font-size:20px;color:var(--muted);line-height:1;padding:4px;
    }
    #admin-drawer-close:hover{color:var(--dark)}
    #admin-drawer-body{padding:20px}

    /* ── CONFIRM MODAL ── */
    #confirm-modal{
        display:none;position:fixed;inset:0;
        background:rgba(0,0,0,0.5);z-index:8000;
        align-items:center;justify-content:center;
    }
    .confirm-card{
        background:#fff;border-radius:14px;padding:28px 28px 24px;
        max-width:400px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);
    }
    .confirm-icon{font-size:28px;margin-bottom:10px}
    .confirm-title{font-size:16px;font-weight:700;color:var(--dark);margin-bottom:8px}
    .confirm-body{font-size:13px;color:var(--muted);margin-bottom:24px;line-height:1.5}
    .confirm-actions{display:flex;gap:10px;justify-content:flex-end}
    .confirm-cancel{
        background:transparent;color:var(--muted);
        border:1px solid var(--border);border-radius:8px;
        padding:8px 18px;font-size:13px;font-weight:600;
        cursor:pointer;font-family:'DM Sans',sans-serif;
        transition:all .15s;
    }
    .confirm-cancel:hover{border-color:var(--dark);color:var(--dark)}
    .confirm-ok{
        background:#DC2626;color:#fff;
        border:none;border-radius:8px;
        padding:8px 18px;font-size:13px;font-weight:600;
        cursor:pointer;font-family:'DM Sans',sans-serif;
        transition:background .15s;
    }
    .confirm-ok:hover{background:#b91c1c}

    /* ── PREVIEW MODAL ── */
    #preview-modal{
        display:none;position:fixed;inset:0;
        background:rgba(0,0,0,0.7);z-index:8000;
        align-items:center;justify-content:center;
        padding:20px;
    }
    #preview-modal .pm-inner{
        background:#fff;border-radius:14px;
        width:100%;max-width:960px;height:80vh;
        display:flex;flex-direction:column;overflow:hidden;
        box-shadow:0 20px 60px rgba(0,0,0,0.3);
    }
    #preview-modal .pm-header{
        display:flex;align-items:center;justify-content:space-between;
        padding:12px 16px;border-bottom:1px solid var(--border);
        background:#fff;
    }
    #preview-modal .pm-title{font-size:13px;font-weight:600;color:var(--muted)}
    #preview-modal .pm-close{
        background:none;border:none;cursor:pointer;
        font-size:20px;color:var(--muted);line-height:1;
    }
    #preview-iframe{flex:1;border:none;width:100%}
    </style>
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <h1>Vox<span>ura</span></h1>
        <div class="sidebar-sub">{{ auth()->user()->name ?? 'Admin' }}</div>
    </div>

    <nav class="sidebar-nav">
        <a href="/admin" class="nav-item {{ $section === 'dashboard' ? 'active' : '' }}"><span class="nav-icon">⊞</span> Dashboard</a>
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
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="topbar-ghost" style="cursor:pointer;border:1px solid var(--border);background:transparent;font-family:'DM Sans',sans-serif;">⎋ Logout</button>
            </form>
            <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
        </div>
    </div>

    {{-- Dynamic page body (replaced on AJAX nav) --}}
    <div id="admin-page-body">

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

    </div>{{-- #admin-page-body --}}

</div>

{{-- ── TOAST CONTAINER ── --}}
<div id="toast-container"></div>

{{-- ── DRAWER ── --}}
<div id="admin-drawer-overlay" onclick="closeDrawer()"></div>
<div id="admin-drawer">
    <div id="admin-drawer-header">
        <span id="admin-drawer-title">Edit</span>
        <button id="admin-drawer-close" onclick="closeDrawer()">×</button>
    </div>
    <div id="admin-drawer-body"></div>
</div>

{{-- ── CONFIRM MODAL ── --}}
<div id="confirm-modal">
    <div class="confirm-card">
        <div class="confirm-icon">⚠️</div>
        <div class="confirm-title" id="confirm-title">Are you sure?</div>
        <div class="confirm-body" id="confirm-body"></div>
        <div class="confirm-actions">
            <button class="confirm-cancel" onclick="closeConfirm()">Cancel</button>
            <button class="confirm-ok" id="confirm-ok">Confirm</button>
        </div>
    </div>
</div>

{{-- ── PREVIEW MODAL ── --}}
<div id="preview-modal">
    <div class="pm-inner">
        <div class="pm-header">
            <span class="pm-title">Product Preview</span>
            <button class="pm-close" onclick="closePreview()">×</button>
        </div>
        <iframe id="preview-iframe" src="about:blank"></iframe>
    </div>
</div>

<script>
(function () {
    'use strict';

    // ── CSRF ──
    function csrfToken() {
        var m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.content : '';
    }

    // ── TOAST ──
    window.showToast = function (message, type) {
        type = type || 'success';
        var map = {
            success: { border: '#16A34A', bg: '#F0FDF4', icon: '✓' },
            error:   { border: '#DC2626', bg: '#FEF2F2', icon: '✕' },
            warning: { border: '#D97706', bg: '#FFFBEB', icon: '⚠' }
        };
        var c = map[type] || map.success;
        var t = document.createElement('div');
        t.style.cssText = 'background:' + c.bg + ';border-left:3px solid ' + c.border + ';border-radius:8px;padding:12px 16px;font-size:13px;color:#1A1A1A;box-shadow:0 4px 12px rgba(0,0,0,0.1);display:flex;align-items:center;gap:10px;min-width:260px;max-width:360px;pointer-events:auto;animation:slideInToast 0.2s ease;';
        t.innerHTML = '<span style="color:' + c.border + ';font-weight:700;flex-shrink:0">' + c.icon + '</span><span>' + message + '</span>';
        document.getElementById('toast-container').appendChild(t);
        setTimeout(function () {
            t.style.opacity = '0';
            t.style.transition = 'opacity 0.3s';
            setTimeout(function () { if (t.parentNode) t.remove(); }, 300);
        }, 3500);
    };

    // ── CONFIRM MODAL ──
    var _confirmCb = null;
    window.confirmAction = function (title, body, cb) {
        document.getElementById('confirm-title').textContent = title || 'Are you sure?';
        document.getElementById('confirm-body').textContent  = body  || '';
        _confirmCb = cb;
        var m = document.getElementById('confirm-modal');
        m.style.display = 'flex';
    };
    window.closeConfirm = function () {
        document.getElementById('confirm-modal').style.display = 'none';
        _confirmCb = null;
    };
    document.getElementById('confirm-ok').addEventListener('click', function () {
        if (_confirmCb) _confirmCb();
        closeConfirm();
    });
    document.getElementById('confirm-modal').addEventListener('click', function (e) {
        if (e.target === this) closeConfirm();
    });

    // ── DRAWER ──
    window.openDrawer = function (html, title) {
        var drawer  = document.getElementById('admin-drawer');
        var overlay = document.getElementById('admin-drawer-overlay');
        var body    = document.getElementById('admin-drawer-body');
        if (title) document.getElementById('admin-drawer-title').textContent = title;
        body.innerHTML = html;
        overlay.style.display = 'block';
        drawer.style.display  = 'block';
        // Execute any inline scripts
        body.querySelectorAll('script').forEach(function (sc) {
            var s = document.createElement('script');
            s.textContent = sc.textContent;
            document.body.appendChild(s);
            document.body.removeChild(s);
        });
        setTimeout(function () { drawer.style.transform = 'translateX(0)'; }, 10);
    };
    window.closeDrawer = function () {
        var drawer = document.getElementById('admin-drawer');
        drawer.style.transform = 'translateX(100%)';
        setTimeout(function () {
            drawer.style.display = 'none';
            document.getElementById('admin-drawer-overlay').style.display = 'none';
            document.getElementById('admin-drawer-body').innerHTML = '';
        }, 260);
    };

    // ── PREVIEW MODAL ──
    window.previewProduct = function (slug) {
        document.getElementById('preview-iframe').src = '/product/' + slug;
        var m = document.getElementById('preview-modal');
        m.style.display = 'flex';
    };
    window.closePreview = function () {
        document.getElementById('preview-modal').style.display = 'none';
        document.getElementById('preview-iframe').src = 'about:blank';
    };
    document.getElementById('preview-modal').addEventListener('click', function (e) {
        if (e.target === this) closePreview();
    });

    // ── AJAX FORM HELPER ──
    window.showInlineErrors = function (formEl, errors) {
        formEl.querySelectorAll('.form-error-ajax').forEach(function (el) { el.remove(); });
        Object.keys(errors).forEach(function (field) {
            var input = formEl.querySelector('[name="' + field + '"]') ||
                        formEl.querySelector('[name="' + field + '[]"]');
            if (input) {
                var err = document.createElement('p');
                err.className = 'form-error form-error-ajax';
                err.textContent = errors[field][0];
                input.parentNode.insertBefore(err, input.nextSibling);
            }
        });
        showToast('Please correct the highlighted errors.', 'error');
    };

    window.submitForm = async function (formEl, successCb) {
        var btn = formEl.querySelector('[type="submit"]');
        if (btn) { btn.disabled = true; btn.style.opacity = '0.65'; }
        formEl.querySelectorAll('.form-error-ajax').forEach(function (el) { el.remove(); });
        var fd = new FormData(formEl);
        try {
            var res = await fetch(formEl.action || formEl.getAttribute('action'), {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken()
                }
            });
            var data = await res.json().catch(function () { return {}; });
            if (res.ok) {
                showToast(data.message || 'Saved successfully', 'success');
                if (successCb) successCb(data);
            } else if (res.status === 422 && data.errors) {
                showInlineErrors(formEl, data.errors);
            } else {
                showToast(data.message || 'Something went wrong.', 'error');
            }
        } catch (e) {
            showToast('Request failed. Please try again.', 'error');
        }
        if (btn) { btn.disabled = false; btn.style.opacity = ''; }
    };

    // ── PRODUCT ACTIONS (used by products/index) ──
    window.editProduct = async function (productId) {
        openDrawer('<div style="padding:2rem;text-align:center;color:var(--muted);font-size:13px;">Loading…</div>', 'Edit Product');
        try {
            var res = await fetch('/admin/products/' + productId + '/edit', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error();
            var html = await res.text();
            var parser = new DOMParser();
            var doc    = parser.parseFromString(html, 'text/html');
            var inner  = doc.querySelector('.admin-content');
            openDrawer(inner ? inner.innerHTML : html, 'Edit Product');

            // Intercept form submit inside drawer
            var form = document.getElementById('admin-drawer-body').querySelector('#cp-form');
            if (form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    await submitForm(form, function () {
                        closeDrawer();
                        setTimeout(function () { location.reload(); }, 320);
                    });
                });
            }
        } catch (e) {
            document.getElementById('admin-drawer-body').innerHTML =
                '<div style="padding:2rem;color:#dc2626;font-size:13px;">Failed to load. <a href="/admin/products/' + productId + '/edit" style="color:var(--orange);">Open full page →</a></div>';
        }
    };

    window.deleteProduct = function (productId, name) {
        confirmAction(
            'Delete product?',
            'Move "' + name + '" to the archive. You can restore it later.',
            async function () {
                try {
                    var fd = new FormData();
                    fd.append('_method', 'DELETE');
                    fd.append('_token', csrfToken());
                    var res = await fetch('/admin/products/' + productId, {
                        method: 'POST', body: fd,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken() }
                    });
                    if (!res.ok) throw new Error();
                    var row = document.querySelector('tr[data-product-id="' + productId + '"]');
                    if (row) {
                        row.style.transition = 'opacity 0.3s';
                        row.style.opacity = '0';
                        setTimeout(function () { if (row.parentNode) row.remove(); }, 330);
                    }
                    showToast('Product moved to archive.', 'success');
                } catch (e) {
                    showToast('Delete failed. Please try again.', 'error');
                }
            }
        );
    };

    // ── ORDER ACTIONS (used by orders/index) ──
    window.openOrderDrawer = async function (orderId) {
        openDrawer('<div style="padding:2rem;text-align:center;color:var(--muted);font-size:13px;">Loading…</div>', 'Order #' + orderId);
        try {
            var res = await fetch('/admin/orders/' + orderId, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error();
            var html = await res.text();
            var parser = new DOMParser();
            var doc    = parser.parseFromString(html, 'text/html');
            var inner  = doc.querySelector('.admin-content');
            openDrawer(inner ? inner.innerHTML : html, 'Order #' + orderId);
        } catch (e) {
            document.getElementById('admin-drawer-body').innerHTML =
                '<div style="padding:2rem;color:#dc2626;font-size:13px;">Failed to load. <a href="/admin/orders/' + orderId + '" style="color:var(--orange);">Open full page →</a></div>';
        }
    };

    // ── CMS PAGE PREVIEW ──
    window.previewCmsPage = function (slug) {
        document.getElementById('preview-iframe').src = '/pages/' + slug;
        document.getElementById('preview-modal').style.display = 'flex';
    };

    // ── KEYBOARD ──
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDrawer();
            closeConfirm();
            closePreview();
        }
    });

    // ── AJAX SIDEBAR NAVIGATION ──
    var _navBusy = false;
    var _loadedExtScripts = new Set();

    window.adminNavigate = async function (url) {
        if (_navBusy) return;
        _navBusy = true;
        updateSidebarActive(url);
        try {
            var res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) { _navBusy = false; window.location.href = url; return; }
            var html   = await res.text();
            var parser = new DOMParser();
            var doc    = parser.parseFromString(html, 'text/html');
            var newPb  = doc.getElementById('admin-page-body');
            if (!newPb) { _navBusy = false; window.location.href = url; return; }
            var pb = document.getElementById('admin-page-body');
            pb.innerHTML = newPb.innerHTML;
            // Re-run inline scripts; load external ones once
            pb.querySelectorAll('script').forEach(function (sc) {
                if (sc.src) {
                    if (!_loadedExtScripts.has(sc.src)) {
                        _loadedExtScripts.add(sc.src);
                        var s = document.createElement('script');
                        s.src = sc.src;
                        s.addEventListener('load', function () {
                            if (window.Alpine) try { Alpine.initTree(pb); } catch (e) {}
                        });
                        document.body.appendChild(s);
                    }
                } else {
                    var s = document.createElement('script');
                    s.textContent = sc.textContent;
                    document.body.appendChild(s);
                    document.body.removeChild(s);
                }
            });
            // Initialize Alpine.js components in new content if already loaded
            if (window.Alpine) try { Alpine.initTree(pb); } catch (e) {}
            history.pushState({ url: url }, '', url);
            window.scrollTo(0, 0);
        } catch (e) {
            window.location.href = url;
        }
        _navBusy = false;
    };

    function updateSidebarActive(url) {
        var u;
        try { u = new URL(url, window.location.origin).pathname; } catch (e) { u = url; }
        var map = [
            ['/admin/orders','/admin/orders'],['/admin/shipments','/admin/orders'],
            ['/admin/invoices','/admin/orders'],['/admin/refunds','/admin/orders'],
            ['/admin/transactions','/admin/orders'],['/admin/rma','/admin/orders'],
            ['/admin/customers','/admin/customers'],['/admin/customer-groups','/admin/customers'],
            ['/admin/reviews','/admin/customers'],['/admin/gdpr','/admin/customers'],
            ['/admin/products','/admin/products'],['/admin/categories','/admin/products'],
            ['/admin/attributes','/admin/products'],['/admin/attribute-families','/admin/products'],
            ['/admin/archive','/admin/products'],
            ['/admin/cms','/admin/cms/pages'],['/admin/banners','/admin/cms/pages'],
            ['/admin/email-templates','/admin/cms/pages'],
            ['/admin/coupons','/admin/coupons'],['/admin/seo','/admin/coupons'],
            ['/admin/communications','/admin/coupons'],
            ['/admin/reports','/admin/reports'],
            ['/admin/shipping','/admin/shipping'],['/admin/tax','/admin/shipping'],
        ];
        var navHref = '/admin';
        for (var i = 0; i < map.length; i++) {
            if (u.startsWith(map[i][0])) { navHref = map[i][1]; break; }
        }
        document.querySelectorAll('.sidebar .nav-item').forEach(function (item) {
            item.classList.remove('active');
            if (item.getAttribute('href') === navHref) item.classList.add('active');
        });
    }

    // Sidebar link intercept
    document.querySelectorAll('.sidebar .nav-item').forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (e.ctrlKey || e.metaKey || e.shiftKey) return;
            e.preventDefault();
            adminNavigate(this.getAttribute('href'));
        });
    });

    // Subnav/sub-btn intercept (capture phase so it works on dynamically loaded content)
    document.addEventListener('click', function (e) {
        var el = e.target.closest('.subnav-tab, .sub-btn');
        if (el && el.href && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
            e.preventDefault();
            adminNavigate(el.href);
        }
    }, true);

    window.addEventListener('popstate', function (e) {
        if (e.state && e.state.url) adminNavigate(e.state.url);
    });

}());
</script>

</body>
</html>
