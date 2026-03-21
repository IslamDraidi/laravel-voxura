<x-layout title="Admin — Orders">
<style>
.admin-page { padding-top: 90px; padding-bottom: 4rem; }

.admin-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 1rem; margin-bottom: 1.5rem;
}

.admin-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem; font-weight: 800;
    color: var(--gray-900); letter-spacing: -0.03em;
}
.admin-title span { color: var(--orange); }

.btn-admin {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: var(--orange); color: #fff;
    padding: 0.55rem 1.1rem; border-radius: 999px;
    text-decoration: none; font-size: 0.83rem; font-weight: 700;
    border: none; cursor: pointer; transition: background 0.15s;
    font-family: 'DM Sans', sans-serif;
}
.btn-admin:hover { background: var(--orange-dark); }
.btn-admin-ghost {
    background: transparent; color: var(--gray-600);
    border: 1.5px solid var(--gray-300);
}
.btn-admin-ghost:hover { color: var(--orange); border-color: var(--orange); background: rgba(234,88,12,0.05); }

.alert-success {
    background: #dcfce7; color: #16a34a;
    padding: 0.85rem 1.25rem; border-radius: 0.5rem;
    margin-bottom: 1.25rem; font-weight: 600; font-size: 0.9rem;
}

/* ── Status Tabs ── */
.status-tabs {
    display: flex; gap: 0.5rem; flex-wrap: wrap;
    margin-bottom: 1.25rem;
}

.status-tab {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.4rem 1rem; border-radius: 999px;
    font-size: 0.8rem; font-weight: 700; text-decoration: none;
    border: 1.5px solid var(--gray-200); color: var(--gray-500);
    transition: all 0.15s;
}
.status-tab:hover { border-color: var(--orange); color: var(--orange); }
.status-tab.active { background: var(--orange); color: #fff; border-color: var(--orange); }

.tab-count {
    background: rgba(255,255,255,0.25);
    padding: 0.05rem 0.45rem;
    border-radius: 999px; font-size: 0.72rem;
}
.status-tab:not(.active) .tab-count {
    background: var(--gray-100); color: var(--gray-500);
}

/* ── Search bar ── */
.filter-bar {
    display: flex; gap: 0.75rem; flex-wrap: wrap;
    margin-bottom: 1.25rem;
    background: #fff; border: 1.5px solid var(--gray-200);
    border-radius: var(--radius); padding: 1rem 1.25rem;
}
.filter-bar input {
    flex: 1; min-width: 180px;
    padding: 0.5rem 0.9rem; border: 1.5px solid var(--gray-200);
    border-radius: 999px; font-size: 0.85rem;
    font-family: 'DM Sans', sans-serif; outline: none;
    transition: border-color 0.15s;
}
.filter-bar input:focus { border-color: var(--orange); }
.btn-filter {
    background: var(--orange); color: #fff; border: none;
    padding: 0.5rem 1.2rem; border-radius: 999px;
    font-size: 0.85rem; font-weight: 700; cursor: pointer;
    font-family: 'DM Sans', sans-serif; transition: background 0.15s;
}
.btn-filter:hover { background: var(--orange-dark); }
.btn-reset {
    background: transparent; color: var(--gray-500);
    border: 1.5px solid var(--gray-200); padding: 0.5rem 1rem;
    border-radius: 999px; font-size: 0.85rem; font-weight: 600;
    cursor: pointer; text-decoration: none;
    font-family: 'DM Sans', sans-serif;
    transition: color 0.15s, border-color 0.15s;
}
.btn-reset:hover { color: var(--orange); border-color: var(--orange); }

/* ── Result count ── */
.result-count { font-size: 0.82rem; color: var(--gray-400); margin-bottom: 0.75rem; }

/* ── Order Rows ── */
.order-rows { display: flex; flex-direction: column; gap: 0.75rem; }

.order-row {
    background: #fff; border: 1.5px solid var(--gray-200);
    border-radius: var(--radius); overflow: hidden;
    transition: box-shadow 0.2s, border-color 0.2s;
}
.order-row:hover { box-shadow: var(--shadow-md); border-color: var(--orange-muted); }

.order-row-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 0.75rem; padding: 0.85rem 1.25rem;
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-100);
}

.order-row-id {
    font-family: 'Playfair Display', serif;
    font-size: 0.95rem; font-weight: 800; color: var(--gray-900);
}
.order-row-id span { color: var(--orange); }

.order-row-meta {
    display: flex; align-items: center; gap: 1rem;
    font-size: 0.78rem; color: var(--gray-400); flex-wrap: wrap;
}

.status-badge {
    font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em;
    padding: 0.2rem 0.65rem; border-radius: 999px;
}

.order-row-body {
    display: flex; align-items: center;
    gap: 1.25rem; padding: 1rem 1.25rem; flex-wrap: wrap;
}

.order-thumbs { display: flex; gap: 0.35rem; }
.order-thumb {
    width: 44px; height: 44px; object-fit: cover;
    border-radius: 0.35rem; border: 1.5px solid var(--gray-100);
    background: var(--gray-100);
}
.order-more {
    width: 44px; height: 44px; border-radius: 0.35rem;
    background: var(--gray-100); display: flex;
    align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 700; color: var(--gray-500);
    border: 1.5px solid var(--gray-200);
}

.order-info { flex: 1; min-width: 0; }
.order-customer { font-weight: 700; font-size: 0.9rem; color: var(--gray-900); }
.order-customer-email { font-size: 0.75rem; color: var(--gray-400); }
.order-amount {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem; font-weight: 800; color: var(--gray-900);
    flex-shrink: 0;
}

/* ── Status Update Form ── */
.status-form {
    display: flex; align-items: center; gap: 0.5rem;
    flex-shrink: 0; flex-wrap: wrap;
}

.status-select {
    padding: 0.4rem 0.75rem;
    border: 1.5px solid var(--gray-200); border-radius: 0.5rem;
    font-size: 0.82rem; font-family: 'DM Sans', sans-serif;
    color: var(--gray-900); outline: none; background: #fff;
    transition: border-color 0.15s; cursor: pointer;
}
.status-select:focus { border-color: var(--orange); }

.btn-update-status {
    background: var(--orange); color: #fff; border: none;
    padding: 0.4rem 0.9rem; border-radius: 0.5rem;
    font-size: 0.8rem; font-weight: 700; cursor: pointer;
    font-family: 'DM Sans', sans-serif; transition: background 0.15s;
    white-space: nowrap;
}
.btn-update-status:hover { background: var(--orange-dark); }

/* ── Empty ── */
.admin-empty {
    text-align: center; padding: 3rem;
    color: var(--gray-400); font-size: 0.95rem;
}

@media (max-width: 640px) {
    .order-row-body { flex-direction: column; align-items: flex-start; }
    .status-form { width: 100%; }
    .status-select { flex: 1; }
}
</style>

<div class="admin-page">

    <div class="admin-header">
        <h1 class="admin-title">📋 <span>Orders</span></h1>
        <a href="/admin" class="btn-admin btn-admin-ghost">← Back to Admin</a>
    </div>

    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    {{-- Status Tabs --}}
    <div class="status-tabs">
        @php $currentStatus = request('status', ''); @endphp

        @foreach([
            ''           => ['label' => 'All',        'count' => $statusCounts['all']],
            'pending'    => ['label' => 'Pending',     'count' => $statusCounts['pending']],
            'processing' => ['label' => 'Processing',  'count' => $statusCounts['processing']],
            'shipped'    => ['label' => 'Shipped',     'count' => $statusCounts['shipped']],
            'delivered'  => ['label' => 'Delivered',   'count' => $statusCounts['delivered']],
            'cancelled'  => ['label' => 'Cancelled',   'count' => $statusCounts['cancelled']],
        ] as $val => $tab)
            <a href="/admin/orders?status={{ $val }}{{ request('search') ? '&search='.request('search') : '' }}"
               class="status-tab {{ $currentStatus === $val ? 'active' : '' }}">
                {{ $tab['label'] }}
                <span class="tab-count">{{ $tab['count'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Search --}}
    <form method="GET" action="/admin/orders" class="filter-bar">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="search" placeholder="Search by customer name or email…"
               value="{{ request('search') }}">
        <button type="submit" class="btn-filter">Search</button>
        <a href="/admin/orders{{ request('status') ? '?status='.request('status') : '' }}"
           class="btn-reset">Reset</a>
    </form>

    <p class="result-count">
        Showing {{ $orders->count() }} {{ Str::plural('order', $orders->count()) }}
    </p>

    {{-- Orders --}}
    @if($orders->isEmpty())
        <div class="admin-empty">
            <p style="font-size:2rem;margin-bottom:0.5rem;">📭</p>
            <p>No orders found.</p>
        </div>
    @else
        <div class="order-rows">
            @foreach($orders as $order)
            <div class="order-row">

                {{-- Header --}}
                <div class="order-row-header">
                    <p class="order-row-id">Order <span>#{{ $order->id }}</span></p>
                    <div class="order-row-meta">
                        <span>{{ $order->created_at->format('M d, Y · g:i A') }}</span>
                        <span class="status-badge"
                              style="background:{{ $order->statusBg() }};color:{{ $order->statusColor() }};">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                {{-- Body --}}
                <div class="order-row-body">

                    {{-- Thumbnails --}}
                    <div class="order-thumbs">
                        @foreach($order->items->take(3) as $item)
                            @if($item->product)
                                <img src="{{ asset('images/' . $item->product->image) }}"
                                     alt="{{ $item->product->name }}" class="order-thumb">
                            @endif
                        @endforeach
                        @if($order->items->count() > 3)
                            <div class="order-more">+{{ $order->items->count() - 3 }}</div>
                        @endif
                    </div>

                    {{-- Customer --}}
                    <div class="order-info">
                        <p class="order-customer">{{ $order->user->name }}</p>
                        <p class="order-customer-email">{{ $order->user->email }}</p>
                        <p style="font-size:0.75rem;color:var(--gray-400);margin-top:0.15rem;">
                            {{ $order->items->sum('quantity') }} {{ Str::plural('item', $order->items->sum('quantity')) }}
                        </p>
                    </div>

                    {{-- Amount --}}
                    <span class="order-amount">${{ number_format($order->total_amount) }}</span>

                    {{-- Status Update --}}
                    <form method="POST" action="/admin/orders/{{ $order->id }}/status"
                          class="status-form">
                        @csrf @method('PATCH')
                        <select name="status" class="status-select">
                            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-update-status">Update</button>
                    </form>

                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
</x-layout>