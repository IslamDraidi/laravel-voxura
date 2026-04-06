<x-layout title="My Orders">
<style>
.orders-page { padding-top: 100px; padding-bottom: 4rem; }

.orders-heading {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 800;
    color: var(--gray-900);
    letter-spacing: -0.03em;
    margin-bottom: 0.25rem;
}
.orders-heading .accent { color: var(--orange); }
.orders-sub { color: var(--gray-500); font-size: 0.95rem; margin-bottom: 2.5rem; }

.orders-list { display: flex; flex-direction: column; gap: 1.25rem; }

.order-card {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    overflow: hidden;
    transition: box-shadow 0.2s, border-color 0.2s;
}
.order-card:hover { box-shadow: var(--shadow-md); border-color: var(--orange-muted); }

/* Header row */
.order-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1.5px solid var(--gray-100);
    background: var(--gray-50);
}

.order-id {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 800;
    color: var(--gray-900);
}
.order-id span { color: var(--orange); }

.order-date { font-size: 0.78rem; color: var(--gray-400); }

.status-badge {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
}

/* Body */
.order-card-body {
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex-wrap: wrap;
}

.order-images {
    display: flex;
    gap: 0.4rem;
}

.order-thumb {
    width: 52px; height: 52px;
    object-fit: cover;
    border-radius: 0.4rem;
    border: 1.5px solid var(--gray-100);
    background: var(--gray-100);
}

.order-more-imgs {
    width: 52px; height: 52px;
    border-radius: 0.4rem;
    background: var(--gray-100);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; font-weight: 700; color: var(--gray-500);
    border: 1.5px solid var(--gray-200);
}

.order-meta { flex: 1; min-width: 0; }

.order-items-count {
    font-size: 0.82rem;
    color: var(--gray-500);
    margin-bottom: 0.2rem;
}

.order-total {
    font-family: 'Playfair Display', serif;
    font-size: 1.35rem;
    font-weight: 800;
    color: var(--gray-900);
}

.btn-view-order {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1.25rem;
    border: 1.5px solid var(--orange);
    border-radius: 999px;
    color: var(--orange);
    font-size: 0.83rem;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
    flex-shrink: 0;
}
.btn-view-order:hover { background: var(--orange); color: #fff; }

/* Empty */
.orders-empty {
    text-align: center;
    padding: 5rem 1rem;
}
.empty-icon {
    width: 80px; height: 80px;
    background: var(--orange-light);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--orange);
}
.empty-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem; font-weight: 800; color: var(--gray-900);
    margin-bottom: 0.5rem;
}
.empty-sub { color: var(--gray-500); margin-bottom: 2rem; font-size: 0.95rem; }
</style>

<div class="orders-page">

    <h1 class="orders-heading">My <span class="accent">Orders</span></h1>

    @if($orders->isEmpty())
        <div class="orders-empty">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="empty-title">No orders yet</p>
            <p class="empty-sub">Once you place an order, it'll show up here.</p>
            <a href="/#products" class="btn btn-primary" style="font-size:0.95rem;padding:0.7rem 2rem;">
                Start Shopping
            </a>
        </div>

    @else
        <p class="orders-sub">{{ $orders->count() }} {{ Str::plural('order', $orders->count()) }} placed</p>

        <div class="orders-list">
            @foreach($orders as $order)
                <div class="order-card">

                    <div class="order-card-header">
                        <div>
                            <p class="order-id">Order <span>#{{ $order->id }}</span></p>
                            <p class="order-date">{{ $order->created_at->format('M d, Y · g:i A') }}</p>
                        </div>
                        <span class="status-badge"
                              style="background:{{ $order->statusBg() }};color:{{ $order->statusColor() }};">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="order-card-body">

                        {{-- Product thumbnails --}}
                        <div class="order-images">
                            @foreach($order->items->take(3) as $item)
                                @if($item->product)
                                    <img src="{{ asset('images/' . $item->product->image) }}"
                                         alt="{{ $item->product->name }}"
                                         class="order-thumb">
                                @endif
                            @endforeach
                            @if($order->items->count() > 3)
                                <div class="order-more-imgs">+{{ $order->items->count() - 3 }}</div>
                            @endif
                        </div>

                        <div class="order-meta">
                            <p class="order-items-count">
                                {{ $order->items->sum('quantity') }} {{ Str::plural('item', $order->items->sum('quantity')) }}
                            </p>
                            <p class="order-total">₪{{ number_format($order->total_amount) }}</p>
                        </div>

                        <a href="/orders/{{ $order->id }}" class="btn-view-order">
                            View Details →
                        </a>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
</x-layout>