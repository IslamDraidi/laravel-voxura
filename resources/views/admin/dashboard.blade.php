<x-admin-layout title="Dashboard" section="dashboard">

<style>
/* ── Overall stat cards ─────────────────────────────── */
.overview-grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:14px;
    margin-bottom:2rem;
}
.ov-card{
    background:#fff;
    border:1px solid var(--border);
    border-radius:10px;
    padding:18px 20px;
}
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
.ov-sub{font-size:12px;color:var(--muted);}

/* ── Today's cards ──────────────────────────────────── */
.today-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
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

{{-- ═══════════════ OVERALL DETAILS ═══════════════ --}}
<p class="section-heading">Overall Details</p>
<div class="overview-grid">

    <div class="ov-card">
        <div class="ov-icon">💰</div>
        <div class="ov-label">Total Sales</div>
        <div class="ov-value orange">${{ number_format($totalSales, 0) }}</div>
        <div class="ov-sub">All time revenue</div>
    </div>

    <div class="ov-card">
        <div class="ov-icon">🛍️</div>
        <div class="ov-label">Total Orders</div>
        <div class="ov-value blue">{{ number_format($totalOrders) }}</div>
        <div class="ov-sub">All time orders</div>
    </div>

    <div class="ov-card">
        <div class="ov-icon">👤</div>
        <div class="ov-label">Total Customers</div>
        <div class="ov-value green">{{ number_format($totalCustomers) }}</div>
        <div class="ov-sub">Registered users</div>
    </div>

    <div class="ov-card">
        <div class="ov-icon">📈</div>
        <div class="ov-label">Avg Order Sale</div>
        <div class="ov-value orange">${{ number_format($avgOrderSale, 2) }}</div>
        <div class="ov-sub">Per transaction</div>
    </div>

    <div class="ov-card">
        <div class="ov-icon">📋</div>
        <div class="ov-label">Unpaid Invoices</div>
        <div class="ov-value red">{{ number_format($unpaidInvoices) }}</div>
        <div class="ov-sub">Pending payment</div>
    </div>

</div>

{{-- ═══════════════ TODAY'S DETAILS ════════════════ --}}
<p class="section-heading">Today's Details</p>
<div class="today-grid">

    <div class="td-card orange">
        <div class="td-label">Today's Sales</div>
        <div class="td-value">${{ number_format($todaySales, 0) }}</div>
    </div>

    <div class="td-card dark">
        <div class="td-label">Today's Orders</div>
        <div class="td-value">{{ $todayOrders }}</div>
    </div>

    <div class="td-card green">
        <div class="td-label">Today's Customers</div>
        <div class="td-value">{{ $todayCustomers }}</div>
    </div>

</div>

{{-- ═══════════════ TABLES ══════════════════════════ --}}
<div class="dash-two-col">

    {{-- Top Selling Products --}}
    <div class="card" style="padding:20px 22px;">
        <p class="section-heading" style="margin-bottom:.75rem;">🏆 Top Selling Products</p>
        @if($topProducts->isEmpty())
            <p style="color:var(--muted);font-size:13px;">No sales data yet.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align:right;">Sales</th>
                        <th style="text-align:right;">Revenue</th>
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
                        <td style="text-align:right;color:var(--dark);">{{ number_format($p->total_sold) }}</td>
                        <td style="text-align:right;color:var(--dark);">${{ number_format($p->revenue) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Customers with Most Sales --}}
    <div class="card" style="padding:20px 22px;">
        <p class="section-heading" style="margin-bottom:.75rem;">⭐ Customers with Most Sales</p>
        @if($topCustomers->isEmpty())
            <p style="color:var(--muted);font-size:13px;">No customer data yet.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th style="text-align:right;">Orders</th>
                        <th style="text-align:right;">Total Spent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topCustomers as $c)
                    <tr>
                        <td style="font-weight:500;">{{ $c->name }}</td>
                        <td style="text-align:right;color:var(--dark);">{{ $c->order_count }}</td>
                        <td style="text-align:right;color:var(--dark);">${{ number_format($c->total_spent) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

</x-admin-layout>
