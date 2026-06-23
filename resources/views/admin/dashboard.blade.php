<x-admin-layout title="Dashboard" section="dashboard">

<style>
/* ── Overall stat cards ─────────────────────────────── */
.overview-grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:14px;
    margin-bottom:2rem;
}
.overview-grid-4{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:14px;
    margin-bottom:2rem;
}
.ov-card{
    background:#fff;
    border:1px solid var(--border);
    border-radius:10px;
    padding:18px 20px;
}
.ov-card-link{
    display:block;
    text-decoration:none;
    background:#fff;
    border:1px solid var(--border);
    border-radius:10px;
    padding:18px 20px;
    transition:box-shadow .15s;
    cursor:pointer;
}
.ov-card-link:hover{box-shadow:0 2px 8px rgba(0,0,0,0.08);}
.ov-icon{font-size:1.75rem;margin-bottom:10px;}
.ov-label{
    font-size:10.5px;font-weight:700;letter-spacing:.07em;
    color:var(--muted);text-transform:uppercase;margin-bottom:6px;
}
.ov-value{font-size:1.55rem;font-weight:700;line-height:1;margin-bottom:6px;}
.ov-value.orange{color:var(--orange);}
.ov-value.blue   {color:#4f46e5;}
.ov-value.green  {color:var(--green);}
.ov-value.red    {color:var(--red);}
.ov-value.amber  {color:var(--amber);}
.ov-sub{font-size:12px;color:var(--muted);}

/* ── Today's cards ──────────────────────────────────── */
.today-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:14px;
    margin-bottom:2rem;
}
.td-card{
    border-radius:10px;
    padding:22px 24px;
    color:#fff;
}
.td-card.orange {background:var(--orange);}
.td-card.dark   {background:#1a1a2e;}
.td-card.green  {background:#16a34a;}
.td-card.blue   {background:#2563eb;}
.td-label{
    font-size:10.5px;font-weight:700;letter-spacing:.07em;
    text-transform:uppercase;opacity:.85;margin-bottom:10px;
}
.td-value{font-size:2rem;font-weight:700;line-height:1;}

/* ── Two-col tables ─────────────────────────────────── */
.dash-two-col{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}
.section-heading{
    font-size:15px;font-weight:700;color:var(--dark);
    margin-bottom:1rem;
}
</style>

{{-- ═══════════════ PLATFORM OVERVIEW ═══════════════ --}}
<p class="section-heading">Platform Overview</p>
<div class="overview-grid-4">

    <div class="ov-card">
        <div class="ov-icon"><i class="ti ti-building-store" style="color:var(--orange);"></i></div>
        <div class="ov-label">Total Stores</div>
        <div class="ov-value orange">{{ number_format($totalStores) }}</div>
        <div class="ov-sub">{{ $activeStores }} active</div>
    </div>

    <a class="ov-card-link" href="/admin/stores?tab=pending" onclick="adminNavigate('/admin/stores?tab=pending');return false;">
        <div class="ov-icon"><i class="ti ti-clock" style="color:var(--amber);"></i></div>
        <div class="ov-label">Pending Approvals</div>
        <div class="ov-value amber">{{ number_format($pendingStores) }}</div>
        <div class="ov-sub">Stores awaiting review</div>
    </a>

    <div class="ov-card">
        <div class="ov-icon"><i class="ti ti-coin" style="color:var(--green);"></i></div>
        <div class="ov-label">Commission Earned</div>
        <div class="ov-value green">{{ config('shop.currency_symbol', '₪') }}{{ number_format($totalCommission, 2) }}</div>
        <div class="ov-sub">From all store sales</div>
    </div>

    <a class="ov-card-link" href="/admin/stores?tab=expiring" onclick="adminNavigate('/admin/stores?tab=expiring');return false;">
        <div class="ov-icon"><i class="ti ti-alert-triangle" style="color:var(--red);"></i></div>
        <div class="ov-label">Expiring Soon</div>
        <div class="ov-value red">{{ number_format($expiringStores) }}</div>
        <div class="ov-sub">Subscriptions within 7 days</div>
    </a>

</div>

{{-- ═══════════════ SALES OVERVIEW ═══════════════ --}}
<p class="section-heading">{{ __('admin.overall_details') }}</p>
<div class="overview-grid">

    <div class="ov-card">
        <div class="ov-icon">💰</div>
        <div class="ov-label">{{ __('admin.total_sales') }}</div>
        <div class="ov-value orange">₪{{ number_format($totalSales, 0) }}</div>
        <div class="ov-sub">{{ __('admin.all_time_revenue') }}</div>
    </div>

    <div class="ov-card">
        <div class="ov-icon">🛍️</div>
        <div class="ov-label">{{ __('admin.total_orders') }}</div>
        <div class="ov-value blue">{{ number_format($totalOrders) }}</div>
        <div class="ov-sub">{{ __('admin.all_time_orders') }}</div>
    </div>

    <div class="ov-card">
        <div class="ov-icon">👤</div>
        <div class="ov-label">{{ __('admin.total_customers') }}</div>
        <div class="ov-value green">{{ number_format($totalCustomers) }}</div>
        <div class="ov-sub">{{ __('admin.registered_users') }}</div>
    </div>

    <div class="ov-card">
        <div class="ov-icon">📈</div>
        <div class="ov-label">{{ __('admin.avg_order_sale') }}</div>
        <div class="ov-value orange">₪{{ number_format($avgOrderSale, 2) }}</div>
        <div class="ov-sub">{{ __('admin.per_transaction') }}</div>
    </div>

    <div class="ov-card">
        <div class="ov-icon">📋</div>
        <div class="ov-label">{{ __('admin.unpaid_invoices') }}</div>
        <div class="ov-value red">{{ number_format($unpaidInvoices) }}</div>
        <div class="ov-sub">{{ __('admin.pending_payment') }}</div>
    </div>

</div>

{{-- ═══════════════ TODAY'S DETAILS ════════════════ --}}
<p class="section-heading">{{ __('admin.todays_details') }}</p>
<div class="today-grid">

    <div class="td-card orange">
        <div class="td-label">{{ __('admin.todays_sales') }}</div>
        <div class="td-value">₪{{ number_format($todaySales, 0) }}</div>
    </div>

    <div class="td-card dark">
        <div class="td-label">{{ __('admin.todays_orders') }}</div>
        <div class="td-value">{{ $todayOrders }}</div>
    </div>

    <div class="td-card green">
        <div class="td-label">{{ __('admin.todays_customers') }}</div>
        <div class="td-value">{{ $todayCustomers }}</div>
    </div>

    <div class="td-card blue">
        <div class="td-label">New Applications</div>
        <div class="td-value">{{ $todayApplications }}</div>
    </div>

</div>

{{-- ═══════════════ TABLES ══════════════════════════ --}}
<div class="dash-two-col">

    {{-- Top Selling Products --}}
    <div class="card" style="padding:20px 22px;">
        <p class="section-heading" style="margin-bottom:.75rem;">{{ __('admin.top_selling_products') }}</p>
        @if($topProducts->isEmpty())
            <p style="color:var(--muted);font-size:13px;">{{ __('admin.no_sales_data') }}</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>{{ __('admin.product_col') }}</th>
                        <th>Store</th>
                        <th style="text-align:right;">{{ __('admin.sales_col') }}</th>
                        <th style="text-align:right;">{{ __('admin.revenue_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $p)
                    <tr>
                        <td>
                            <a href="/admin/products/{{ $p->id }}/edit"
                               style="color:var(--orange);text-decoration:none;font-weight:500;">
                                {{ $p->name }}
                            </a>
                        </td>
                        <td>
                            @if($p->store_name)
                                <a href="/admin/stores/{{ $p->store_slug }}"
                                   onclick="adminNavigate('/admin/stores/{{ $p->store_slug }}');return false;"
                                   style="color:var(--orange);text-decoration:none;font-size:12px;">
                                    {{ $p->store_name }}
                                </a>
                            @else
                                <span style="color:var(--muted);font-size:12px;">—</span>
                            @endif
                        </td>
                        <td style="text-align:right;color:var(--dark);">{{ number_format($p->total_sold) }}</td>
                        <td style="text-align:right;color:var(--dark);">₪{{ number_format($p->revenue) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Customers with Most Sales --}}
    <div class="card" style="padding:20px 22px;">
        <p class="section-heading" style="margin-bottom:.75rem;">{{ __('admin.customers_most_sales') }}</p>
        @if($topCustomers->isEmpty())
            <p style="color:var(--muted);font-size:13px;">{{ __('admin.no_customer_data') }}</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>{{ __('admin.customer_col') }}</th>
                        <th>Top Store</th>
                        <th style="text-align:right;">{{ __('admin.orders_col') }}</th>
                        <th style="text-align:right;">{{ __('admin.total_spent_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topCustomers as $c)
                    <tr>
                        <td style="font-weight:500;">{{ $c->name }}</td>
                        <td style="font-size:12px;color:var(--muted);">{{ $c->top_store ?? '—' }}</td>
                        <td style="text-align:right;color:var(--dark);">{{ $c->order_count }}</td>
                        <td style="text-align:right;color:var(--dark);">₪{{ number_format($c->total_spent) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

</x-admin-layout>
