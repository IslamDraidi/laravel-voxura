<x-admin-layout title="Refunds" section="sales" active="refunds">

    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-label">Total Refund Orders</div>
            <div class="sc-value red">{{ $refunds->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Total Refunded Value</div>
            <div class="sc-value red" style="font-size:1.4rem;">${{ number_format($totalRefunded, 2) }}</div>
        </div>
    </div>

    @if($refunds->isEmpty())
        <div class="admin-empty">
            <p style="font-size:2rem;margin-bottom:0.5rem;">✓</p>
            <p>No cancelled orders — nothing to refund!</p>
        </div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Cancelled Date</th>
                        <th>Amount</th>
                        <th>Coupon Used</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($refunds as $order)
                    <tr>
                        <td style="font-weight:700;">#{{ $order->id }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $order->user->name }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ $order->user->email }}</div>
                        </td>
                        <td style="white-space:nowrap;font-size:12px;">{{ $order->created_at->format('M d, Y') }}</td>
                        <td style="white-space:nowrap;font-size:12px;">{{ $order->updated_at->format('M d, Y') }}</td>
                        <td style="font-weight:700;color:var(--red);">${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            @if($order->coupon_code)
                                <span class="badge badge-blue">{{ $order->coupon_code }}</span>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>
                        <td><span class="badge badge-red">Cancelled</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
