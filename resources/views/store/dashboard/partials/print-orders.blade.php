<h1>Orders Report</h1>

<div class="stat-grid" style="grid-template-columns:repeat(3,1fr)">
    <div class="stat-box">
        <div class="stat-value">{{ $orders->total() }}</div>
        <div class="stat-label">Total Orders</div>
    </div>
    @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
    <div class="stat-box">
        <div class="stat-value">{{ $statusCounts[$s] ?? 0 }}</div>
        <div class="stat-label">{{ ucfirst($s) }}</div>
    </div>
    @endforeach
</div>

<h2>Orders</h2>
<table>
    <thead>
        <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>#{{ $order->id }}</td>
            <td>{{ $order->recipientName() }}</td>
            <td>{{ $order->items->count() }}</td>
            <td>${{ number_format($order->grand_total, 2) }}</td>
            <td>{{ ucfirst($order->status) }}</td>
            <td>{{ $order->created_at->format('M d, Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="color:#aaa">No orders</td></tr>
        @endforelse
    </tbody>
</table>
