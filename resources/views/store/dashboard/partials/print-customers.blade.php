<h1>Customers Report</h1>

<div class="stat-grid">
    <div class="stat-box">
        <div class="stat-value">{{ $totalCustomers }}</div>
        <div class="stat-label">Total Customers</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">{{ $newCustomers }}</div>
        <div class="stat-label">New Customers</div>
        <div class="stat-sub">In selected period</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">{{ $returningCustomers }}</div>
        <div class="stat-label">Returning Customers</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">${{ number_format($clv, 2) }}</div>
        <div class="stat-label">Avg. Lifetime Value</div>
    </div>
</div>

<h2>Top Customers</h2>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Orders</th>
            <th>Total Spent</th>
            <th>Last Order</th>
        </tr>
    </thead>
    <tbody>
        @forelse($topCustomers as $i => $customer)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->order_count }}</td>
            <td>${{ number_format($customer->total_spent, 2) }}</td>
            <td>{{ \Carbon\Carbon::parse($customer->last_order_at)->format('M d, Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="color:#aaa">No customers</td></tr>
        @endforelse
    </tbody>
</table>
