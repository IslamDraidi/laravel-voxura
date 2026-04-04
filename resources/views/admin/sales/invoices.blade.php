<x-admin-layout title="Invoices" section="sales" active="invoices">

    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search by customer…" value="{{ request('search') }}">
        <button type="submit" class="add-btn">Search</button>
        <a href="{{ route('admin.invoices.index') }}" style="font-size:0.85rem;color:var(--muted);align-self:center;">Reset</a>
    </form>

    <p class="result-count">{{ $invoices->count() }} invoice{{ $invoices->count() !== 1 ? 's' : '' }}</p>

    @if($invoices->isEmpty())
        <div class="admin-empty">No invoices found.</div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Subtotal</th>
                        <th>Tax</th>
                        <th>Shipping</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $order)
                    <tr>
                        <td style="font-weight:700;">INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td style="white-space:nowrap;font-size:12px;">{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $order->user->name }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ $order->user->email }}</div>
                        </td>
                        <td>{{ $order->items->count() }}</td>
                        <td>${{ number_format($order->total_amount - $order->tax_amount - $order->shipping_cost + $order->discount_amount, 2) }}</td>
                        <td>${{ number_format($order->tax_amount, 2) }}</td>
                        <td>${{ number_format($order->shipping_cost, 2) }}</td>
                        <td style="font-weight:700;">${{ number_format($order->grandTotal(), 2) }}</td>
                        <td>
                            <span class="badge" style="background:{{ $order->statusBg() }};color:{{ $order->statusColor() }};">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
