<x-store-layout title="Orders" active="orders">

@include('store.dashboard.partials.time-filter', [
    'title'    => 'Orders',
    'subtitle' => 'All orders containing your products',
    'range'    => $range,
    'section'  => 'orders',
])

{{-- ── STATUS QUEUE BAR ── --}}
<div class="sub-nav" style="margin-bottom:16px">
    @php
        $allCount = $statusCounts->sum();
    @endphp
    <a href="{{ route('store.dashboard.orders', ['range' => $range, 'status' => 'all']) }}"
       class="sub-btn {{ $status === 'all' ? 'active' : '' }}">All ({{ $allCount }})</a>
    @foreach(['pending' => '⏳', 'processing' => '⚙️', 'shipped' => '📬', 'delivered' => '✅', 'cancelled' => '✕'] as $s => $icon)
    <a href="{{ route('store.dashboard.orders', ['range' => $range, 'status' => $s]) }}"
       class="sub-btn {{ $status === $s ? 'active' : '' }}">{{ $icon }} {{ ucfirst($s) }} ({{ $statusCounts[$s] ?? 0 }})</a>
    @endforeach
</div>

{{-- ── ORDERS TABLE ── --}}
<div class="card">
    <div class="result-count">{{ $orders->total() }} order(s)</div>
    @if($orders->isEmpty())
        <div class="admin-empty">No orders found for this period.</div>
    @else
    <table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            @php
                $badgeClass = match($order->status) {
                    'paid', 'delivered', 'completed' => 'badge-green',
                    'pending'                         => 'badge-amber',
                    'processing'                      => 'badge-blue',
                    'shipped'                         => 'badge-teal',
                    'cancelled', 'returned'           => 'badge-red',
                    'refunded'                        => 'badge-purple',
                    default                           => 'badge-gray',
                };
            @endphp
            <tr>
                <td style="font-weight:600">#{{ $order->id }}</td>
                <td>
                    <div style="font-weight:600;font-size:13px">{{ $order->recipientName() }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ $order->recipientEmail() }}</div>
                </td>
                <td>{{ $order->items->count() }}</td>
                <td style="font-weight:700">${{ number_format($order->grand_total, 2) }}</td>
                <td><span class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span></td>
                <td style="color:var(--muted)">{{ $order->created_at->format('M d, Y') }}</td>
                <td>
                    <a href="{{ route('orders.show', $order->id) }}" class="act-btn" target="_blank">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:16px">
        {{ $orders->links() }}
    </div>
    @endif
</div>

</x-store-layout>
