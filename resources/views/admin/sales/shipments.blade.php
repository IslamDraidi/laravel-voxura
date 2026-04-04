<x-admin-layout title="Shipments" section="sales" active="shipments">

    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-label">Processing</div>
            <div class="sc-value blue">{{ $counts['processing'] }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Shipped</div>
            <div class="sc-value" style="color:#8b5cf6">{{ $counts['shipped'] }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Delivered</div>
            <div class="sc-value green">{{ $counts['delivered'] }}</div>
        </div>
    </div>

    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search by customer…" value="{{ request('search') }}">
        <select name="status" class="form-select" style="width:auto">
            <option value="">All Statuses</option>
            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped"    {{ request('status') === 'shipped'    ? 'selected' : '' }}>Shipped</option>
            <option value="delivered"  {{ request('status') === 'delivered'  ? 'selected' : '' }}>Delivered</option>
        </select>
        <button type="submit" class="add-btn">Filter</button>
        <a href="{{ route('admin.shipments.index') }}" style="font-size:0.85rem;color:var(--muted);align-self:center;">Reset</a>
    </form>

    @if($shipments->isEmpty())
        <div class="admin-empty">No shipments found.</div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Shipping Address</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shipments as $order)
                    <tr>
                        <td style="font-weight:700;">#{{ $order->id }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $order->user->name }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ $order->user->email }}</div>
                        </td>
                        <td style="max-width:200px;font-size:12px;color:var(--muted);">
                            {{ Str::limit($order->shipping_address, 60) }}
                        </td>
                        <td style="font-size:12px;">{{ $order->shippingMethod?->name ?? '—' }}</td>
                        <td>
                            <span class="badge" style="background:{{ $order->statusBg() }};color:{{ $order->statusColor() }};">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td style="white-space:nowrap;font-size:12px;">{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.orders.status', $order) }}" style="display:flex;gap:4px;align-items:center;">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select" style="padding:4px 8px;font-size:12px;width:auto;">
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped"    {{ $order->status === 'shipped'    ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered"  {{ $order->status === 'delivered'  ? 'selected' : '' }}>Delivered</option>
                                </select>
                                <button type="submit" class="act-btn">Update</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
