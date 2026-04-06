<x-layout title="Payment — Order #{{ $order->id }}">
<style>
.payment-page { padding-top: 100px; padding-bottom: 4rem; }

.payment-page-header {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;
}
.payment-page-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.75rem, 4vw, 2.5rem);
    font-weight: 800; color: var(--gray-900); letter-spacing: -0.03em;
}
.payment-page-title span { color: var(--orange); }

.payment-layout {
    display: grid; grid-template-columns: 1fr 320px;
    gap: 2rem; align-items: start;
}
@media (max-width: 800px) { .payment-layout { grid-template-columns: 1fr; } }

/* Gateway cards */
.gateway-list { display: flex; flex-direction: column; gap: 1rem; }

.gateway-card {
    display: flex; align-items: center; gap: 1.25rem;
    padding: 1.25rem 1.5rem;
    background: #fff; border: 2px solid var(--gray-200);
    border-radius: var(--radius); cursor: pointer;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.gateway-card:hover { border-color: var(--orange-muted); }
.gateway-card.selected {
    border-color: var(--orange);
    box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12);
}

.gateway-radio {
    width: 22px; height: 22px; border-radius: 50%;
    border: 2px solid var(--gray-300); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: border-color 0.15s;
}
.gateway-card.selected .gateway-radio {
    border-color: var(--orange);
}
.gateway-radio-dot {
    width: 12px; height: 12px; border-radius: 50%;
    background: var(--orange); transform: scale(0);
    transition: transform 0.15s;
}
.gateway-card.selected .gateway-radio-dot { transform: scale(1); }

.gateway-icon {
    width: 56px; height: 36px; object-fit: contain;
    border-radius: 4px;
}
.gateway-info { flex: 1; }
.gateway-name { font-weight: 700; color: var(--gray-900); font-size: 0.95rem; }
.gateway-desc { font-size: 0.8rem; color: var(--gray-400); margin-top: 0.15rem; }

/* Pay button */
.btn-pay {
    display: block; width: 100%; margin-top: 1.5rem;
    padding: 0.85rem; background: var(--orange); color: #fff;
    font-family: 'DM Sans', sans-serif; font-size: 0.95rem; font-weight: 700;
    border: none; border-radius: 999px; cursor: pointer;
    text-align: center; transition: background 0.15s;
}
.btn-pay:hover { background: var(--orange-dark); }
.btn-pay:disabled { opacity: 0.5; cursor: not-allowed; }

/* Alert box */
.payment-alert {
    padding: 1rem 1.25rem; border-radius: var(--radius);
    font-size: 0.88rem; margin-bottom: 1.5rem;
    display: flex; align-items: flex-start; gap: 0.75rem;
}
.payment-alert.warning {
    background: #fef3c7; color: #92400e; border: 1px solid #fde68a;
}
.payment-alert.error {
    background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;
}
.payment-alert.blocked {
    background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;
}
.payment-alert-icon { font-size: 1.2rem; flex-shrink: 0; }

.attempt-badge {
    display: inline-block; padding: 0.25rem 0.75rem;
    background: #fef3c7; color: #92400e; font-size: 0.78rem;
    font-weight: 700; border-radius: 999px; margin-bottom: 1rem;
}

/* Sidebar — reuse order show pattern */
.payment-sidebar {
    background: #fff; border: 1.5px solid var(--gray-200);
    border-radius: var(--radius); padding: 1.5rem;
    position: sticky; top: 84px;
}
.sidebar-title {
    font-family: 'Playfair Display', serif; font-size: 1.1rem;
    font-weight: 800; color: var(--gray-900);
    margin-bottom: 1.25rem; padding-bottom: 0.75rem;
    border-bottom: 1.5px solid var(--gray-100);
}
.sidebar-row {
    display: flex; justify-content: space-between;
    font-size: 0.88rem; color: var(--gray-500); margin-bottom: 0.6rem;
}
.sidebar-row.total {
    font-size: 1.05rem; font-weight: 800; color: var(--gray-900);
    padding-top: 0.75rem; margin-top: 0.25rem;
    border-top: 1.5px solid var(--gray-100); margin-bottom: 0;
}
.sidebar-row.total span:last-child {
    font-family: 'Playfair Display', serif; font-size: 1.25rem; color: var(--orange);
}
.sidebar-item {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.5rem 0; font-size: 0.85rem;
}
.sidebar-item img {
    width: 40px; height: 40px; object-fit: cover; border-radius: 6px; background: var(--gray-100);
}
.sidebar-item-name { flex: 1; font-weight: 600; color: var(--gray-700); }
.sidebar-item-price { font-weight: 700; color: var(--gray-900); }
</style>

<div class="payment-page">

    <div class="payment-page-header">
        <h1 class="payment-page-title">Choose <span>Payment Method</span></h1>
    </div>

    @if($blocked)
        <div class="payment-alert blocked">
            <span class="payment-alert-icon">🚫</span>
            <div>
                <strong>Payment blocked</strong><br>
                Maximum payment attempts ({{ $maxAttempts }}) reached for this order.
                Please <a href="/pages/contact" style="color:#991b1b;font-weight:700;">contact support</a> for assistance.
            </div>
        </div>
    @else

        @if(session('payment_error'))
            <div class="payment-alert error">
                <span class="payment-alert-icon">⚠️</span>
                <div>{{ session('payment_error') }}</div>
            </div>
        @endif

        @if($attempts > 0 && !session('payment_error'))
            <div class="payment-alert warning">
                <span class="payment-alert-icon">⚠️</span>
                <div>Your previous payment attempt was unsuccessful. Please try again or choose a different method.</div>
            </div>
        @endif

        @if($attempts > 0)
            <span class="attempt-badge">Attempt {{ $attempts + 1 }} of {{ $maxAttempts }}</span>
        @endif

    @if(empty($enabledGateways))
        <div class="payment-alert warning">
            <span class="payment-alert-icon">⚠️</span>
            <div>
                <strong>No payment methods available</strong><br>
                We're currently updating our payment options. Please try again later or
                <a href="/pages/contact" style="color:#92400e;font-weight:700;">contact support</a>.
            </div>
        </div>
    @else
    <div class="payment-layout" x-data="{ gateway: '{{ old('gateway', $enabledGateways[0] ?? '') }}' }">

        {{-- Left: gateway selection --}}
        <div>
            <form method="POST" action="{{ route('payment.process', $order) }}">
                @csrf
                <input type="hidden" name="gateway" x-model="gateway">

                <div class="gateway-list">
                    @if(in_array('paypal', $enabledGateways))
                    <div class="gateway-card" :class="gateway === 'paypal' && 'selected'" @click="gateway = 'paypal'">
                        <div class="gateway-radio"><div class="gateway-radio-dot"></div></div>
                        <svg class="gateway-icon" viewBox="0 0 100 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M38.2 7.2h-6.8c-.5 0-.9.3-1 .8l-2.7 17.4c-.1.4.2.7.6.7h3.2c.5 0 .9-.3 1-.8l.7-4.7c.1-.5.5-.8 1-.8h2.3c4.8 0 7.5-2.3 8.2-6.9.3-2 0-3.6-1-4.7-1-1.3-2.8-2-5.5-2zm.8 6.8c-.4 2.6-2.4 2.6-4.3 2.6h-1.1l.8-4.8c0-.3.3-.5.6-.5h.5c1.3 0 2.5 0 3.2.8.4.4.5 1.1.3 1.9z" fill="#253B80"/>
                            <path d="M60 7.2h-6.8c-.5 0-.9.3-1 .8l-2.7 17.4c-.1.4.2.7.6.7h3.5c.3 0 .6-.2.7-.6l.8-4.9c.1-.5.5-.8 1-.8h2.3c4.8 0 7.5-2.3 8.2-6.9.3-2 0-3.6-1-4.7-1.1-1.3-2.9-2-5.6-2zm.9 6.8c-.4 2.6-2.4 2.6-4.3 2.6h-1.1l.8-4.8c0-.3.3-.5.6-.5h.5c1.3 0 2.5 0 3.2.8.3.4.5 1.1.3 1.9z" fill="#179BD7"/>
                            <path d="M79.5 13.9h-3.3c-.3 0-.5.2-.6.5l-.1.9-.2-.3c-.7-1-2.2-1.3-3.7-1.3-3.5 0-6.4 2.6-7 6.3-.3 1.8.1 3.6 1.2 4.8 1 1.1 2.4 1.6 4.1 1.6 2.9 0 4.5-1.9 4.5-1.9l-.2.9c-.1.4.2.7.6.7h3c.5 0 .9-.3 1-.8l1.8-11.7c0-.3-.3-.7-.7-.7zm-4.5 6.1c-.3 1.8-1.8 3-3.6 3-.9 0-1.6-.3-2.1-.8-.4-.5-.6-1.3-.5-2.1.3-1.8 1.8-3.1 3.6-3.1.9 0 1.6.3 2.1.9.4.5.6 1.3.5 2.1z" fill="#253B80"/>
                        </svg>
                        <div class="gateway-info">
                            <p class="gateway-name">PayPal</p>
                            <p class="gateway-desc">Pay securely with your PayPal account or card</p>
                        </div>
                    </div>
                    @endif

                    @if(in_array('tap', $enabledGateways))
                    <div class="gateway-card" :class="gateway === 'tap' && 'selected'" @click="gateway = 'tap'">
                        <div class="gateway-radio"><div class="gateway-radio-dot"></div></div>
                        <div class="gateway-icon" style="display:flex;align-items:center;justify-content:center;background:#2dd36f;border-radius:6px;color:#fff;font-weight:800;font-size:0.75rem;letter-spacing:0.02em;">TAP</div>
                        <div class="gateway-info">
                            <p class="gateway-name">Tap Payments</p>
                            <p class="gateway-desc">Credit / debit card via Tap Payments</p>
                            <p style="font-size:0.72rem;color:var(--gray-400);margin-top:0.3rem;">Accepts Visa, Mastercard, and local payment methods</p>
                        </div>
                    </div>
                    @endif
                </div>

                <button type="submit" class="btn-pay">
                    Pay ${{ number_format($order->grand_total, 2) }} →
                </button>
            </form>
        </div>

        {{-- Right: order summary --}}
        <div class="payment-sidebar">
            <p class="sidebar-title">Order #{{ $order->id }}</p>

            @foreach($order->items as $item)
                @if($item->product)
                <div class="sidebar-item">
                    <img src="{{ asset('images/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                    <span class="sidebar-item-name">{{ Str::limit($item->product->name, 25) }} × {{ $item->quantity }}</span>
                    <span class="sidebar-item-price">${{ number_format($item->subtotal(), 2) }}</span>
                </div>
                @endif
            @endforeach

            <div style="margin-top:1rem; padding-top:0.75rem; border-top:1.5px solid var(--gray-100);">
                <div class="sidebar-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal ?: $order->total_amount, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="sidebar-row">
                    <span style="color:#16a34a;font-weight:600;">Discount</span>
                    <span style="color:#16a34a;font-weight:700;">−${{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="sidebar-row">
                    <span>Shipping</span>
                    <span>{{ (float)$order->shipping_cost == 0 ? 'Free' : '$'.number_format($order->shipping_cost, 2) }}</span>
                </div>
                @if($order->tax_amount > 0 || $order->shipping_tax_amount > 0)
                <div class="sidebar-row">
                    <span>Tax</span>
                    <span>${{ number_format($order->tax_amount + $order->shipping_tax_amount, 2) }}</span>
                </div>
                @endif
                <div class="sidebar-row total">
                    <span>Total</span>
                    <span>${{ number_format($order->grand_total ?: $order->grandTotal(), 2) }}</span>
                </div>
            </div>
        </div>

    </div>
    @endif {{-- empty gateways --}}
    @endif {{-- blocked --}}
</div>
</x-layout>
