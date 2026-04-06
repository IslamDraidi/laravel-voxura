<x-admin-layout title="Order #{{ $order->id }}" section="sales" active="orders">

<style>
.order-detail { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start; }
@media (max-width: 900px) { .order-detail { grid-template-columns: 1fr; } }

.detail-card {
    background: #fff; border: 1px solid var(--border);
    border-radius: 10px; margin-bottom: 1.25rem; overflow: hidden;
}
.detail-card-header {
    padding: 0.85rem 1.25rem; font-weight: 700; font-size: 0.88rem;
    color: var(--dark); background: #fafafa; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 0.5rem;
}
.detail-card-body { padding: 1rem 1.25rem; }

.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem 1.5rem; }
.info-label { font-size: 0.75rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; }
.info-value { font-size: 0.88rem; color: var(--dark); font-weight: 600; margin-bottom: 0.5rem; }

.item-row {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.6rem 0; border-bottom: 1px solid #f3f4f6;
}
.item-row:last-child { border-bottom: none; }
.item-row img { width: 40px; height: 40px; object-fit: cover; border-radius: 6px; }
.item-name { flex: 1; font-size: 0.85rem; font-weight: 600; }
.item-qty { font-size: 0.78rem; color: var(--muted); }
.item-total { font-weight: 700; font-size: 0.88rem; }

/* Payment badge */
.gateway-badge {
    display: inline-block; padding: 0.2rem 0.65rem;
    border-radius: 999px; font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.04em;
}
.gateway-paypal { background: #fef3c7; color: #92400e; }
.gateway-tap { background: #d1fae5; color: #065f46; }

/* Failure table */
.failure-table { width: 100%; font-size: 0.8rem; border-collapse: collapse; }
.failure-table th { text-align: left; color: var(--muted); font-weight: 600; padding: 0.4rem 0.5rem; font-size: 0.72rem; text-transform: uppercase; }
.failure-table td { padding: 0.4rem 0.5rem; border-top: 1px solid #f3f4f6; }

/* Refund form */
.refund-form { margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); }
.refund-form label { font-size: 0.78rem; font-weight: 600; color: var(--dark); display: block; margin-bottom: 0.25rem; }
.refund-form input, .refund-form textarea {
    width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--border);
    border-radius: 6px; font-size: 0.85rem; font-family: inherit;
}
.refund-form input:focus, .refund-form textarea:focus { border-color: var(--orange); outline: none; }
.refund-form textarea { min-height: 70px; resize: vertical; }
.refund-row { display: flex; gap: 0.75rem; align-items: end; margin-bottom: 0.75rem; }
.refund-row > div { flex: 1; }

.btn-refund {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.55rem 1.25rem; background: #dc2626; color: #fff;
    border: none; border-radius: 6px; font-size: 0.82rem;
    font-weight: 700; cursor: pointer; transition: background 0.15s;
}
.btn-refund:hover { background: #b91c1c; }

.refund-table { width: 100%; font-size: 0.8rem; border-collapse: collapse; }
.refund-table th { text-align: left; color: var(--muted); font-weight: 600; padding: 0.4rem 0.5rem; font-size: 0.72rem; text-transform: uppercase; }
.refund-table td { padding: 0.5rem 0.5rem; border-top: 1px solid #f3f4f6; }

/* Sidebar totals */
.totals-row { display: flex; justify-content: space-between; font-size: 0.85rem; padding: 0.35rem 0; }
.totals-row.grand { font-weight: 800; font-size: 1rem; padding-top: 0.6rem; margin-top: 0.3rem; border-top: 2px solid var(--border); }
.totals-row.grand span:last-child { color: var(--orange); }

.status-pill {
    display: inline-block; padding: 0.5rem 1rem; border-radius: 8px;
    font-weight: 700; font-size: 0.85rem; text-align: center; width: 100%;
}
</style>

<a href="/admin/orders" style="font-size:0.82rem;color:var(--muted);text-decoration:none;margin-bottom:1rem;display:inline-block;">← Back to Orders</a>

<div class="order-detail">

    {{-- ── Left Column ── --}}
    <div>

        {{-- Customer --}}
        <div class="detail-card">
            <div class="detail-card-header">👤 Customer</div>
            <div class="detail-card-body">
                <div class="info-grid">
                    <div>
                        <p class="info-label">Name</p>
                        <p class="info-value">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="info-label">Email</p>
                        <p class="info-value">{{ $order->user->email }}</p>
                    </div>
                    <div style="grid-column: span 2;">
                        <p class="info-label">Shipping Address</p>
                        <p class="info-value">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="detail-card">
            <div class="detail-card-header">🛍️ Items ({{ $order->items->sum('quantity') }})</div>
            <div class="detail-card-body">
                @foreach($order->items as $item)
                    <div class="item-row">
                        @if($item->product)
                            <img src="{{ asset('images/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                        @endif
                        <span class="item-name">{{ $item->product?->name ?? 'Deleted' }}</span>
                        <span class="item-qty">× {{ $item->quantity }}</span>
                        <span class="item-total">${{ number_format($item->subtotal(), 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Payment Info --}}
        <div class="detail-card">
            <div class="detail-card-header">💳 Payment</div>
            <div class="detail-card-body">
                @php $payment = $order->payments->where('status', 'completed')->first() ?? $order->payments->first(); @endphp
                @if($payment)
                    <div class="info-grid">
                        <div>
                            <p class="info-label">Gateway</p>
                            <p class="info-value">
                                <span class="gateway-badge {{ $payment->gateway === 'paypal' ? 'gateway-paypal' : 'gateway-tap' }}">
                                    {{ ucfirst($payment->gateway ?? 'N/A') }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="info-label">Status</p>
                            <p class="info-value">
                                @php
                                    $payBadge = match($payment->status) {
                                        'completed' => 'badge-green',
                                        'failed' => 'badge-red',
                                        'pending' => 'badge-amber',
                                        'refunded' => 'badge-blue',
                                        default => 'badge-gray',
                                    };
                                @endphp
                                <span class="badge {{ $payBadge }}">{{ ucfirst($payment->status) }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="info-label">Transaction ID</p>
                            <p class="info-value" style="font-size:0.8rem;word-break:break-all;">{{ $payment->transaction_id ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="info-label">Date</p>
                            <p class="info-value">{{ $payment->created_at->format('M d, Y · g:i A') }}</p>
                        </div>
                        <div>
                            <p class="info-label">Attempts</p>
                            <p class="info-value">{{ $payment->attempts }}</p>
                        </div>
                        <div>
                            <p class="info-label">Amount</p>
                            <p class="info-value">${{ number_format($payment->amount, 2) }}</p>
                        </div>
                    </div>

                    {{-- Failure history --}}
                    @if($order->payments->where('failure_code', '!=', null)->count())
                    <div style="margin-top:1rem;">
                        <p style="font-size:0.78rem;font-weight:700;color:var(--muted);text-transform:uppercase;margin-bottom:0.5rem;">Failure History</p>
                        <table class="failure-table">
                            <thead>
                                <tr><th>#</th><th>Gateway</th><th>Date</th><th>Reason</th></tr>
                            </thead>
                            <tbody>
                                @foreach($order->payments->where('failure_code', '!=', null) as $fp)
                                <tr>
                                    <td>{{ $fp->attempts }}</td>
                                    <td>{{ ucfirst($fp->gateway) }}</td>
                                    <td>{{ $fp->last_attempted_at?->format('M d, g:i A') ?? '—' }}</td>
                                    <td>{{ $fp->failure_reason }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                @else
                    <p style="color:var(--muted);font-size:0.85rem;">No payment recorded yet.</p>
                @endif
            </div>
        </div>

        {{-- Refunds --}}
        @if(in_array($order->status, ['paid', 'partially_refunded', 'refunded', 'processing', 'shipped', 'delivered']))
        <div class="detail-card">
            <div class="detail-card-header">
                💸 Refunds
                @if($order->totalRefunded() > 0)
                    <span style="margin-left:auto;font-size:0.78rem;color:var(--muted);">
                        Total refunded: <strong style="color:#dc2626;">${{ number_format($order->totalRefunded(), 2) }}</strong>
                    </span>
                @endif
            </div>
            <div class="detail-card-body">

                {{-- Existing refunds --}}
                @if($order->refunds->isNotEmpty())
                <table class="refund-table">
                    <thead>
                        <tr><th>Date</th><th>Amount</th><th>Reason</th><th>Status</th><th>By</th><th>Refund ID</th></tr>
                    </thead>
                    <tbody>
                        @foreach($order->refunds as $refund)
                        <tr>
                            <td>{{ $refund->created_at->format('M d, Y') }}</td>
                            <td style="font-weight:700;">${{ number_format($refund->amount, 2) }}</td>
                            <td>{{ Str::limit($refund->reason, 40) }}</td>
                            <td>
                                @php
                                    $rBadge = match($refund->status) {
                                        'completed' => 'badge-green',
                                        'failed' => 'badge-red',
                                        default => 'badge-amber',
                                    };
                                @endphp
                                <span class="badge {{ $rBadge }}">{{ ucfirst($refund->status) }}</span>
                            </td>
                            <td>{{ $refund->admin?->name ?? '—' }}</td>
                            <td style="font-size:0.75rem;">{{ $refund->gateway_refund_id ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                {{-- Refund form --}}
                @if($order->isFullyRefunded())
                    <div style="text-align:center;padding:1rem 0;">
                        <span class="badge badge-green" style="font-size:0.85rem;padding:0.4rem 1rem;">✓ Fully Refunded</span>
                    </div>
                @elseif($refundableAmount > 0 && $order->payments->where('status', 'completed')->count())
                    <div class="refund-form" x-data="{ fullRefund: false, amount: {{ $refundableAmount }} }">
                        <form method="POST" action="{{ route('admin.orders.refund', $order) }}"
                              onsubmit="return confirm('Issue refund of $' + this.amount.value + '? This action cannot be undone.');">
                            @csrf
                            <div class="refund-row">
                                <div>
                                    <label>Amount ($)</label>
                                    <input type="number" name="amount" step="0.01" min="0.01"
                                           max="{{ $refundableAmount }}"
                                           x-model="amount" :readonly="fullRefund" required>
                                </div>
                                <div style="flex:0 0 auto;padding-bottom:0.25rem;">
                                    <label style="display:flex;align-items:center;gap:0.4rem;cursor:pointer;white-space:nowrap;">
                                        <input type="checkbox" x-model="fullRefund"
                                               @change="if(fullRefund) amount = {{ $refundableAmount }}">
                                        Full Refund
                                    </label>
                                </div>
                            </div>
                            <div style="margin-bottom:0.75rem;">
                                <label>Reason (min 10 characters)</label>
                                <textarea name="reason" required minlength="10" maxlength="500"
                                          placeholder="Describe the reason for this refund…"></textarea>
                            </div>
                            <button type="submit" class="btn-refund">💸 Issue Refund</button>
                            <span style="font-size:0.75rem;color:var(--muted);margin-left:0.75rem;">Max: ${{ number_format($refundableAmount, 2) }}</span>
                        </form>
                    </div>
                @endif

            </div>
        </div>
        @endif

    </div>

    {{-- ── Right Sidebar ── --}}
    <div>
        {{-- Order Summary --}}
        <div class="detail-card">
            <div class="detail-card-header">📋 Summary</div>
            <div class="detail-card-body">
                <div class="totals-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal ?: $order->total_amount, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="totals-row" style="color:#16a34a;">
                    <span>Discount {{ $order->coupon_code ? '('.$order->coupon_code.')' : '' }}</span>
                    <span>−${{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="totals-row">
                    <span>Shipping</span>
                    <span>{{ (float)$order->shipping_cost == 0 ? 'Free' : '$'.number_format($order->shipping_cost, 2) }}</span>
                </div>
                @if($order->tax_amount > 0 || $order->shipping_tax_amount > 0)
                <div class="totals-row">
                    <span>Tax</span>
                    <span>${{ number_format($order->tax_amount + $order->shipping_tax_amount, 2) }}</span>
                </div>
                @endif
                <div class="totals-row grand">
                    <span>Total</span>
                    <span>${{ number_format($order->grand_total ?: $order->grandTotal(), 2) }}</span>
                </div>

                @if($order->totalRefunded() > 0)
                <div class="totals-row" style="color:#dc2626;margin-top:0.5rem;">
                    <span>Refunded</span>
                    <span>−${{ number_format($order->totalRefunded(), 2) }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Status --}}
        <div class="detail-card">
            <div class="detail-card-header">📌 Status</div>
            <div class="detail-card-body" style="text-align:center;">
                <div class="status-pill" style="background:{{ $order->statusBg() }};color:{{ $order->statusColor() }};">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </div>
                <p style="font-size:0.78rem;color:var(--muted);margin-top:0.75rem;">
                    Placed {{ $order->created_at->format('M d, Y · g:i A') }}
                </p>

                {{-- Inline status update --}}
                <form method="POST" action="{{ route('admin.orders.status', $order) }}"
                      style="margin-top:0.75rem;display:flex;gap:6px;">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select" style="flex:1;padding:0.35rem 0.5rem;font-size:0.82rem;">
                        @foreach(['pending','paid','processing','shipped','delivered','cancelled','payment_blocked','refunded','partially_refunded'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $s)) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="act-btn">Update</button>
                </form>
            </div>
        </div>

        {{-- Shipping --}}
        @if($order->shippingMethod)
        <div class="detail-card">
            <div class="detail-card-header">📦 Shipping</div>
            <div class="detail-card-body">
                <p style="font-weight:700;color:var(--dark);font-size:0.88rem;">{{ $order->shippingMethod->name }}</p>
                @if($order->shippingMethod->estimated_days_min && $order->shippingMethod->estimated_days_max)
                    <p style="font-size:0.8rem;color:var(--muted);margin-top:0.25rem;">
                        {{ $order->shippingMethod->estimated_days_min }}–{{ $order->shippingMethod->estimated_days_max }} business days
                    </p>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>

</x-admin-layout>
