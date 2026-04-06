<x-admin-layout title="Orders" section="sales" active="orders">

    {{-- Status Tabs --}}
    <div class="sub-nav">
        @php $currentStatus = request('status', ''); @endphp

        @foreach([
            ''                    => ['label' => 'All',                'count' => $statusCounts['all']],
            'pending'             => ['label' => 'Pending',            'count' => $statusCounts['pending']],
            'paid'                => ['label' => 'Paid',               'count' => $statusCounts['paid']],
            'processing'          => ['label' => 'Processing',         'count' => $statusCounts['processing']],
            'shipped'             => ['label' => 'Shipped',            'count' => $statusCounts['shipped']],
            'delivered'           => ['label' => 'Delivered',          'count' => $statusCounts['delivered']],
            'cancelled'           => ['label' => 'Cancelled',          'count' => $statusCounts['cancelled']],
            'payment_blocked'     => ['label' => 'Blocked',            'count' => $statusCounts['payment_blocked']],
            'refunded'            => ['label' => 'Refunded',           'count' => $statusCounts['refunded']],
        ] as $val => $tab)
            <a href="/admin/orders?status={{ $val }}{{ request('search') ? '&search='.request('search') : '' }}"
               class="sub-btn {{ $currentStatus === $val ? 'active' : '' }}">
                {{ $tab['label'] }}
                <span style="background:rgba(0,0,0,0.08);padding:0.05rem 0.45rem;border-radius:999px;font-size:0.72rem;margin-left:0.25rem;">{{ $tab['count'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Search --}}
    <form method="GET" action="/admin/orders" class="search-bar" style="margin-bottom:1.25rem;">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="search" placeholder="Search by customer name or email…"
               value="{{ request('search') }}">
        <button type="submit" class="add-btn">Search</button>
        <a href="/admin/orders{{ request('status') ? '?status='.request('status') : '' }}"
           style="font-size:0.85rem;color:var(--muted);text-decoration:none;align-self:center;">Reset</a>
    </form>

    <p class="result-count">Showing {{ $orders->count() }} {{ Str::plural('order', $orders->count()) }}</p>

    {{-- Orders --}}
    @if($orders->isEmpty())
        <div class="admin-empty">
            <p style="font-size:2rem;margin-bottom:0.5rem;">📭</p>
            <p>No orders found.</p>
        </div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    @php
                        $statusBadge = match($order->status) {
                            'pending', 'processing'       => 'badge-amber',
                            'shipped'                     => 'badge-blue',
                            'delivered', 'paid'           => 'badge-green',
                            'cancelled', 'payment_blocked' => 'badge-red',
                            'refunded'                    => 'badge-blue',
                            'partially_refunded'          => 'badge-orange',
                            default                       => 'badge-gray',
                        };
                    @endphp
                    <tr>
                        <td style="font-weight:700;">#{{ $order->id }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $order->user->name }}</div>
                            <div style="font-size:0.75rem;color:var(--muted);">{{ $order->user->email }}</div>
                        </td>
                        <td style="white-space:nowrap;">{{ $order->created_at->format('M d, Y · g:i A') }}</td>
                        <td>
                            <div style="display:flex;gap:4px;align-items:center;flex-wrap:wrap;">
                                @foreach($order->items->take(3) as $item)
                                    @if($item->product)
                                        <img src="{{ asset('images/' . $item->product->image) }}"
                                             alt="{{ $item->product->name }}"
                                             style="width:32px;height:32px;object-fit:cover;border-radius:4px;">
                                    @endif
                                @endforeach
                                @if($order->items->count() > 3)
                                    <span style="font-size:0.72rem;color:var(--muted);">+{{ $order->items->count() - 3 }}</span>
                                @endif
                            </div>
                            <div style="font-size:0.75rem;color:var(--muted);margin-top:2px;">
                                {{ $order->items->sum('quantity') }} {{ Str::plural('item', $order->items->sum('quantity')) }}
                            </div>
                        </td>
                        <td style="font-weight:700;">${{ number_format($order->total_amount) }}</td>
                        <td><span class="badge {{ $statusBadge }}">{{ ucfirst($order->status) }}</span></td>
                        <td>
                            <form method="POST" action="/admin/orders/{{ $order->id }}/status"
                                  style="display:flex;gap:6px;align-items:center;">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select" style="padding:0.3rem 0.5rem;font-size:0.82rem;">
                                    @foreach(['pending','paid','processing','shipped','delivered','cancelled','payment_blocked','refunded','partially_refunded'] as $s)
                                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="act-btn">Update</button>
                                <a href="/admin/orders/{{ $order->id }}" class="act-btn" style="text-decoration:none;">View</a>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
