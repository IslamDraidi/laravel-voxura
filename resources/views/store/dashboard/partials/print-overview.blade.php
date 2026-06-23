<h1>Overview Report</h1>

<div class="stat-grid">
    <div class="stat-box">
        <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-sub">{{ $revenueChange >= 0 ? '+' : '' }}{{ round($revenueChange, 1) }}% vs previous period</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">${{ number_format($netProfit, 2) }}</div>
        <div class="stat-label">Net Profit</div>
        <div class="stat-sub">After {{ $store->commission_rate ?? 0 }}% commission</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">{{ $totalOrders }}</div>
        <div class="stat-label">Total Orders</div>
        <div class="stat-sub">AOV: ${{ number_format($aov, 2) }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">{{ round($conversionRate, 1) }}%</div>
        <div class="stat-label">Conversion Rate</div>
    </div>
</div>

<h2>Top Products</h2>
<table>
    <thead>
        <tr><th>#</th><th>Product</th><th>Units Sold</th><th>Revenue</th></tr>
    </thead>
    <tbody>
        @forelse($topProducts as $i => $p)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $p->name }}</td>
            <td>{{ $p->units_sold }}</td>
            <td>${{ number_format($p->revenue, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="color:#aaa">No sales data</td></tr>
        @endforelse
    </tbody>
</table>

<h2>Order Status Breakdown</h2>
<table>
    <thead>
        <tr><th>Status</th><th>Count</th></tr>
    </thead>
    <tbody>
        @foreach($orderStatuses as $status => $count)
        <tr>
            <td>{{ ucfirst($status) }}</td>
            <td>{{ $count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
