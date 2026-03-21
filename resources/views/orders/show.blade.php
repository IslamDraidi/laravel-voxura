<x-layout title="Order #{{ $order->id }}">
<style>
.order-page { padding-top: 100px; padding-bottom: 4rem; }

.order-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}

.order-page-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.75rem, 4vw, 2.5rem);
    font-weight: 800;
    color: var(--gray-900);
    letter-spacing: -0.03em;
}
.order-page-title span { color: var(--orange); }

.btn-back {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.5rem 1.25rem;
    border: 1.5px solid var(--gray-200); border-radius: 999px;
    text-decoration: none; color: var(--gray-600);
    font-size: 0.85rem; font-weight: 600;
    transition: color 0.15s, border-color 0.15s;
}
.btn-back:hover { color: var(--orange); border-color: var(--orange); }

/* ── Layout ── */
.order-layout {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 2rem;
    align-items: start;
}
@media (max-width: 800px) { .order-layout { grid-template-columns: 1fr; } }

/* ── Cards ── */
.order-detail-card {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    overflow: hidden;
    margin-bottom: 1.25rem;
}

.order-detail-card:last-child { margin-bottom: 0; }

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1.5px solid var(--gray-100);
    background: var(--gray-50);
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 800;
    color: var(--gray-900);
}

/* ── Items ── */
.order-item-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--gray-100);
}
.order-item-row:last-child { border-bottom: none; }

.order-item-row img {
    width: 64px; height: 64px;
    object-fit: cover; border-radius: 0.5rem;
    background: var(--gray-100); flex-shrink: 0;
}

.order-item-info { flex: 1; min-width: 0; }

.order-item-category {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--orange);
    margin-bottom: 0.15rem;
}

.order-item-name {
    font-weight: 700;
    color: var(--gray-900);
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.order-item-qty { font-size: 0.78rem; color: var(--gray-400); margin-top: 0.15rem; }

.order-item-subtotal {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--gray-900);
    flex-shrink: 0;
}

/* ── Shipping ── */
.shipping-info {
    padding: 1.25rem 1.5rem;
    font-size: 0.88rem;
    color: var(--gray-600);
    line-height: 1.7;
}

/* ── Sidebar ── */
.order-sidebar-card {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 1.5rem;
    position: sticky;
    top: 84px;
}

.sidebar-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1.5px solid var(--gray-100);
}

.sidebar-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.88rem;
    color: var(--gray-500);
    margin-bottom: 0.6rem;
}

.sidebar-row.total {
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--gray-900);
    padding-top: 0.75rem;
    margin-top: 0.25rem;
    border-top: 1.5px solid var(--gray-100);
    margin-bottom: 0;
}

.sidebar-row.total span:last-child {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem;
    color: var(--orange);
}

.status-block {
    margin-top: 1.25rem;
    padding: 0.85rem 1rem;
    border-radius: 0.5rem;
    text-align: center;
    font-size: 0.85rem;
    font-weight: 700;
}

.order-date-info {
    margin-top: 1rem;
    font-size: 0.78rem;
    color: var(--gray-400);
    text-align: center;
}

.btn-shop-again {
    display: block;
    width: 100%;
    margin-top: 1.25rem;
    padding: 0.7rem;
    background: var(--orange);
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem;
    font-weight: 700;
    border: none;
    border-radius: 999px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: background 0.15s;
}
.btn-shop-again:hover { background: var(--orange-dark); }
</style>

<div class="order-page">

    <div class="order-page-header">
        <h1 class="order-page-title">Order <span>#{{ $order->id }}</span></h1>
        <a href="/orders" class="btn-back">← My Orders</a>
    </div>

    <div class="order-layout">

        {{-- ── Left Column ── --}}
        <div>

            {{-- Items --}}
            <div class="order-detail-card">
                <div class="card-header">🛍️ Items ({{ $order->items->sum('quantity') }})</div>
                @foreach($order->items as $item)
                    @if($item->product)
                    <div class="order-item-row">
                        <img src="{{ asset('images/' . $item->product->image) }}"
                             alt="{{ $item->product->name }}">
                        <div class="order-item-info">
                            <p class="order-item-category">{{ $item->product->category?->name }}</p>
                            <p class="order-item-name">{{ $item->product->name }}</p>
                            <p class="order-item-qty">
                                ${{ number_format($item->price) }} × {{ $item->quantity }}
                            </p>
                        </div>
                        <span class="order-item-subtotal">
                            ${{ number_format($item->subtotal()) }}
                        </span>
                    </div>
                    @endif
                @endforeach
            </div>

            {{-- Shipping --}}
            <div class="order-detail-card">
                <div class="card-header">📦 Shipping Address</div>
                <div class="shipping-info">
                    {{ $order->shipping_address }}
                </div>
            </div>

        </div>

        {{-- ── Sidebar ── --}}
        <div class="order-sidebar-card">
            <p class="sidebar-title">Order Summary</p>

            <div class="sidebar-row">
                <span>Subtotal</span>
                <span>${{ number_format($order->total_amount) }}</span>
            </div>
            <div class="sidebar-row">
                <span>Shipping</span>
                <span style="color:#22c55e;font-weight:600;">Free</span>
            </div>
            <div class="sidebar-row total">
                <span>Total</span>
                <span>${{ number_format($order->total_amount) }}</span>
            </div>

            <div class="status-block"
                 style="background:{{ $order->statusBg() }};color:{{ $order->statusColor() }};">
                Status: {{ ucfirst($order->status) }}
            </div>

            <p class="order-date-info">
                Placed on {{ $order->created_at->format('M d, Y') }}
            </p>

            <a href="/#products" class="btn-shop-again">Shop Again →</a>
        </div>

    </div>
</div>
</x-layout>