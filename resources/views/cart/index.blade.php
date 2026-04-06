<x-layout title="Your Cart">

<style>
/* ── Cart Page ── */
.cart-page {
    padding-top: 100px;
    padding-bottom: 4rem;
}

.cart-heading {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 800;
    color: var(--gray-900);
    letter-spacing: -0.03em;
    margin-bottom: 0.25rem;
}

.cart-heading .accent { color: var(--orange); }

.cart-subheading {
    color: var(--gray-500);
    font-size: 0.95rem;
    margin-bottom: 2.5rem;
}

/* ── Layout ── */
.cart-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 2rem;
    align-items: start;
}

@media (max-width: 860px) {
    .cart-layout { grid-template-columns: 1fr; }
}

/* ── Items ── */
.cart-items-wrap {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.cart-item {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 1.25rem;
    display: grid;
    grid-template-columns: 90px 1fr auto;
    gap: 1.25rem;
    align-items: center;
    transition: box-shadow 0.2s, border-color 0.2s;
}

.cart-item:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--orange-muted);
}

.cart-item-img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 0.5rem;
    background: var(--gray-100);
}

.cart-item-info { min-width: 0; }

.cart-item-category {
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--orange);
    margin-bottom: 0.2rem;
}

.cart-item-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.35rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cart-item-price {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-600);
}

.cart-item-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.6rem;
}

.cart-item-subtotal {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--gray-900);
}

/* ── Qty Controls ── */
.qty-wrap {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    background: var(--gray-100);
    border-radius: 999px;
    padding: 0.2rem 0.4rem;
}

.qty-btn {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: none;
    background: var(--white);
    color: var(--gray-900);
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s, color 0.15s;
    line-height: 1;
}

.qty-btn:hover { background: var(--orange); color: #fff; }

.qty-val {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-900);
    min-width: 1.5rem;
    text-align: center;
}

/* ── Remove ── */
.btn-remove {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--gray-400);
    padding: 0.2rem;
    border-radius: 0.3rem;
    transition: color 0.15s;
    display: flex;
    align-items: center;
}

.btn-remove:hover { color: #ef4444; }

/* ── Clear Row ── */
.cart-clear-row {
    display: flex;
    justify-content: flex-end;
    margin-top: 0.5rem;
}

.btn-clear {
    background: none;
    border: 1.5px solid var(--gray-200);
    color: var(--gray-500);
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.4rem 1rem;
    border-radius: 999px;
    cursor: pointer;
    transition: border-color 0.15s, color 0.15s;
}

.btn-clear:hover { border-color: #ef4444; color: #ef4444; }

/* ── Summary Card ── */
.summary-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 1.75rem;
    position: sticky;
    top: 84px;
}

.summary-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1.5px solid var(--gray-100);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
    color: var(--gray-600);
    margin-bottom: 0.75rem;
}

.summary-row.total {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1.5px solid var(--gray-100);
    margin-bottom: 0;
}

.summary-row.total span:last-child {
    font-family: 'Playfair Display', serif;
    font-size: 1.35rem;
    color: var(--orange);
}

.btn-checkout {
    display: block;
    width: 100%;
    margin-top: 1.5rem;
    padding: 0.85rem;
    background: var(--orange);
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 700;
    border: none;
    border-radius: 999px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: background 0.18s, box-shadow 0.18s, transform 0.1s;
    box-shadow: 0 4px 18px rgba(234,88,12,0.28);
}

.btn-checkout:hover {
    background: var(--orange-dark);
    box-shadow: 0 6px 24px rgba(234,88,12,0.38);
}

.btn-checkout:active { transform: scale(0.98); }

.continue-link {
    display: block;
    text-align: center;
    margin-top: 0.85rem;
    font-size: 0.82rem;
    color: var(--gray-500);
    text-decoration: none;
    transition: color 0.15s;
}

.continue-link:hover { color: var(--orange); }

/* ── Empty State ── */
.cart-empty {
    text-align: center;
    padding: 5rem 1rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--orange-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--orange);
}

.empty-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.empty-sub {
    color: var(--gray-500);
    margin-bottom: 2rem;
    font-size: 0.95rem;
}

@media (max-width: 600px) {
    .cart-item {
        grid-template-columns: 72px 1fr;
        grid-template-rows: auto auto;
    }

    .cart-item-actions {
        grid-column: 1 / -1;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}
</style>

<div class="cart-page">

    <h1 class="cart-heading">Your <span class="accent">Cart</span></h1>

    @if($cart->items->isEmpty())
        {{-- ── Empty State ── --}}
        <div class="cart-empty">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <p class="empty-title">Your cart is empty</p>
            <p class="empty-sub">Looks like you haven't added anything yet.</p>
            <a href="/#products" class="btn btn-primary" style="font-size:0.95rem;padding:0.7rem 2rem;">
                Browse Products
            </a>
        </div>

    @else
        <p class="cart-subheading">{{ $cart->itemCount() }} {{ Str::plural('item', $cart->itemCount()) }} in your cart</p>

        <div class="cart-layout">

            {{-- ── Items Column ── --}}
            <div>
                <div class="cart-items-wrap">
                    @foreach($cart->items as $item)
                        <div class="cart-item">

                            {{-- Image --}}
                            <img src="{{ asset('images/' . $item->product->image) }}"
                                 alt="{{ $item->product->name }}"
                                 class="cart-item-img">

                            {{-- Info --}}
                            <div class="cart-item-info">
                                <p class="cart-item-category">{{ $item->product->category->name }}</p>
                                <p class="cart-item-name">{{ $item->product->name }}</p>
                                @if($item->variant)
                                    <p style="font-size:0.78rem;color:var(--orange);font-weight:600;margin-bottom:0.25rem;">
                                        {{ $item->variant->label() }}
                                    </p>
                                @endif
                                <p class="cart-item-price">₪{{ number_format($item->unitPrice(), 2) }} each</p>
                            </div>

                            {{-- Actions --}}
                            <div class="cart-item-actions">

                                {{-- Subtotal --}}
                                <span class="cart-item-subtotal">
                                    ₪{{ number_format($item->subtotal()) }}
                                </span>

                                {{-- Qty --}}
                                <div class="qty-wrap">
                                    {{-- Decrease --}}
                                    <form method="POST" action="/cart/items/{{ $item->id }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantity"
                                               value="{{ max(1, $item->quantity - 1) }}">
                                        <button class="qty-btn" type="submit"
                                                @if($item->quantity <= 1) disabled style="opacity:0.4;cursor:not-allowed;" @endif>
                                            −
                                        </button>
                                    </form>

                                    <span class="qty-val">{{ $item->quantity }}</span>

                                    {{-- Increase --}}
                                    <form method="POST" action="/cart/items/{{ $item->id }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantity"
                                               value="{{ $item->quantity + 1 }}">
                                        <button class="qty-btn" type="submit">+</button>
                                    </form>
                                </div>

                                {{-- Remove --}}
                                <form method="POST" action="/cart/items/{{ $item->id }}">
                                    @csrf @method('DELETE')
                                    <button class="btn-remove" type="submit" title="Remove">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Clear cart --}}
                <div class="cart-clear-row">
                    <form method="POST" action="/cart/clear">
                        @csrf @method('DELETE')
                        <button class="btn-clear" type="submit"
                                onclick="return confirm('Clear your entire cart?')">
                            Clear cart
                        </button>
                    </form>
                </div>
            </div>

            {{-- ── Summary ── --}}
            <div class="summary-card">
                <p class="summary-title">Order Summary</p>

                <div class="summary-row">
                    <span>Subtotal ({{ $cart->itemCount() }} items)</span>
                    <span>₪{{ number_format($cart->total()) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span style="color:#22c55e;font-weight:600;">Free</span>
                </div>
                <div class="summary-row">
                    <span>Tax (est.)</span>
                    <span>₪{{ number_format($cart->total() * 0.08) }}</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>₪{{ number_format($cart->total() * 1.08) }}</span>
                </div>

                @if(\App\Http\Middleware\AdminPreviewMode::isActive())
                <span class="btn-checkout" style="opacity:0.45;cursor:not-allowed;" title="Checkout disabled in preview mode">Proceed to Checkout →</span>
                @else
                <a href="/checkout" class="btn-checkout">Proceed to Checkout →</a>
                @endif
                <a href="/#products" class="continue-link">← Continue shopping</a>
            </div>

        </div>
    @endif

</div>

</x-layout>