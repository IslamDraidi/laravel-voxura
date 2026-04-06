<x-layout title="Checkout">
<style>
.checkout-page { padding-top: 100px; padding-bottom: 4rem; }

.checkout-heading {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 800;
    color: var(--gray-900);
    letter-spacing: -0.03em;
    margin-bottom: 0.25rem;
}
.checkout-heading .accent { color: var(--orange); }
.checkout-sub { color: var(--gray-500); font-size: 0.95rem; margin-bottom: 2.5rem; }

.checkout-layout {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 2rem;
    align-items: start;
}
@media (max-width: 900px) { .checkout-layout { grid-template-columns: 1fr; } }

.checkout-card {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 2rem;
    box-shadow: var(--shadow-md);
}

.checkout-section-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1.5px solid var(--gray-100);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.75rem;
}
@media (max-width: 500px) { .form-grid { grid-template-columns: 1fr; } }

.form-group { display: flex; flex-direction: column; gap: 0.35rem; }
.form-group.full { grid-column: 1 / -1; }

.form-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--gray-500);
}

.form-input, .form-select {
    padding: 0.65rem 0.9rem;
    border: 1.5px solid var(--gray-200);
    border-radius: 0.5rem;
    font-size: 0.9rem;
    font-family: 'DM Sans', sans-serif;
    color: var(--gray-900);
    outline: none;
    transition: border-color 0.15s;
    width: 100%;
    background: #fff;
}
.form-input:focus, .form-select:focus { border-color: var(--orange); }
.form-error { font-size: 0.75rem; color: #ef4444; }

.payment-placeholder {
    background: var(--gray-50);
    border: 1.5px dashed var(--gray-300);
    border-radius: 0.5rem;
    padding: 1.5rem;
    text-align: center;
    color: var(--gray-400);
    font-size: 0.85rem;
    margin-top: 0.5rem;
}
.payment-placeholder p { margin-top: 0.4rem; font-size: 0.78rem; }

.btn-place-order {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    margin-top: 1.75rem;
    padding: 0.9rem;
    background: var(--orange);
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    border: none;
    border-radius: 999px;
    cursor: pointer;
    box-shadow: 0 4px 18px rgba(234,88,12,0.28);
    transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
}
.btn-place-order:hover { background: var(--orange-dark); box-shadow: 0 6px 24px rgba(234,88,12,0.38); }
.btn-place-order:active { transform: scale(0.98); }

.summary-card {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 1.75rem;
    position: sticky;
    top: 84px;
}

.summary-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 1.25rem;
    padding-bottom: 1rem;
    border-bottom: 1.5px solid var(--gray-100);
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.85rem;
}

.summary-item img {
    width: 52px; height: 52px;
    object-fit: cover;
    border-radius: 0.4rem;
    flex-shrink: 0;
    background: var(--gray-100);
}

.summary-item-info { flex: 1; min-width: 0; }

.summary-item-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--gray-900);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.summary-item-qty { font-size: 0.75rem; color: var(--gray-400); }

.summary-item-price {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--gray-900);
    flex-shrink: 0;
}

.summary-divider {
    border: none;
    border-top: 1.5px solid var(--gray-100);
    margin: 1rem 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.88rem;
    color: var(--gray-500);
    margin-bottom: 0.5rem;
}

.summary-row.total {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--gray-900);
    margin-top: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1.5px solid var(--gray-100);
    margin-bottom: 0;
}

.summary-row.total span:last-child {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    color: var(--orange);
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 1rem;
    font-size: 0.82rem;
    color: var(--gray-400);
    text-decoration: none;
    transition: color 0.15s;
}
.back-link:hover { color: var(--orange); }

.tax-breakdown-tooltip {
    position: relative;
    cursor: help;
}
.tax-breakdown-detail {
    font-size: 0.72rem;
    color: var(--gray-400);
    margin-top: 2px;
}

.delivery-estimate {
    font-size: 0.72rem;
    color: var(--gray-400);
    font-style: italic;
}
</style>

<div class="checkout-page">

    <h1 class="checkout-heading">Check<span class="accent">out</span></h1>
    <p class="checkout-sub">Almost there — fill in your details to place your order.</p>

    <div class="checkout-layout">

        {{-- ── Form ── --}}
        <div class="checkout-card">
            <form method="POST" action="/checkout">
                @csrf

                {{-- Shipping Info ── --}}
                <p class="checkout-section-title">📦 Shipping Information</p>
                <div class="form-grid">

                    <div class="form-group full">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-input"
                               placeholder="John Doe"
                               value="{{ old('full_name', auth()->user()->name) }}" required>
                        @error('full_name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input"
                               placeholder="john@example.com"
                               value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-input"
                               placeholder="+1 234 567 890"
                               value="{{ old('phone') }}" required>
                        @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-input"
                               placeholder="123 Main Street, Apt 4B"
                               value="{{ old('address') }}" required>
                        @error('address')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-input"
                               placeholder="New York"
                               value="{{ old('city') }}" required>
                        @error('city')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" class="form-input"
                               placeholder="10001"
                               value="{{ old('postal_code') }}" required>
                        @error('postal_code')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-input" id="countryInput"
                               placeholder="United States"
                               value="{{ old('country') }}" required>
                        @error('country')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                </div>

                {{-- Shipping Method ── --}}
                @if($shippingMethods->isNotEmpty())
                <p class="checkout-section-title" style="margin-top:1.5rem;">🚚 Shipping Method</p>
                <div style="display:flex;flex-direction:column;gap:0.6rem;margin-bottom:1rem;" id="shippingOptions">
                    @foreach($shippingMethods as $sm)
                    @php
                        $rate = $sm->calculated_rate ?? (float) $sm->price;
                        $isFree = ($sm->calculated_free ?? false) || $rate == 0;
                        $delivery = $sm->estimated_delivery_text ?? $sm->estimated_delivery;
                    @endphp
                    <label style="display:flex;align-items:center;gap:0.85rem;padding:0.85rem 1rem;border:1.5px solid var(--gray-200);border-radius:0.5rem;cursor:pointer;transition:border-color 0.15s;"
                           onmouseover="this.style.borderColor='var(--orange-muted)'" onmouseout="highlightSelected()">
                        <input type="radio" name="shipping_method_id" value="{{ $sm->id }}"
                               data-price="{{ $rate }}"
                               {{ $loop->first ? 'checked' : '' }}
                               onchange="selectShipping({{ $sm->id }}, {{ $rate }})"
                               style="accent-color:var(--orange);width:16px;height:16px;flex-shrink:0;">
                        <div style="flex:1;">
                            <p style="font-weight:700;font-size:0.9rem;color:var(--gray-900);">{{ $sm->name }}</p>
                            @if($delivery)
                                <p style="font-size:0.78rem;color:var(--gray-400);">{{ $delivery }}</p>
                            @endif
                        </div>
                        <span style="font-weight:700;font-size:0.95rem;color:{{ $isFree ? '#16a34a' : 'var(--gray-900)' }};">
                            {{ $isFree ? 'Free' : '₪' . number_format($rate, 2) }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @endif

                {{-- Payment ── --}}
                <p class="checkout-section-title">💳 Payment</p>
                <div class="payment-placeholder">
                    <span style="font-size:2rem;">🔒</span>
                    <p style="font-weight:700;color:var(--gray-600);margin-top:0.5rem;">
                        Secure payment on the next step
                    </p>
                    <p>You'll choose your payment method after confirming your order details.</p>
                </div>

                {{-- Coupon ── --}}
                @unless(\App\Http\Middleware\AdminPreviewMode::isActive())
                <p class="checkout-section-title" style="margin-top:1.5rem;">🏷️ Coupon Code</p>
                <div id="couponSection">
                    <div style="display:flex;gap:0.6rem;margin-bottom:0.5rem;">
                        <input type="text" id="couponInput" placeholder="Enter coupon code"
                               style="flex:1;padding:0.65rem 0.9rem;border:1.5px solid var(--gray-200);border-radius:0.5rem;font-size:0.9rem;font-family:'DM Sans',sans-serif;outline:none;text-transform:uppercase;"
                               oninput="this.value=this.value.toUpperCase()">
                        <button type="button" onclick="applyCoupon()"
                                style="background:var(--orange);color:#fff;border:none;padding:0.65rem 1.25rem;border-radius:0.5rem;font-size:0.85rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background 0.15s;"
                                onmouseover="this.style.background='#c2410c'" onmouseout="this.style.background='#ea580c'">Apply</button>
                    </div>
                    <div id="couponFeedback" style="font-size:0.82rem;min-height:1.2rem;"></div>
                    <input type="hidden" name="coupon_code" id="couponCode">
                </div>
                @endunless

                <button type="submit" class="btn-place-order">
                    Place Order →
                </button>

            </form>
        </div>

        {{-- ── Summary ── --}}
        <div class="summary-card">
            <p class="summary-title">Order Summary</p>

            @foreach($cart->items as $item)
                <div class="summary-item">
                    <img src="{{ asset('images/' . $item->product->image) }}"
                         alt="{{ $item->product->name }}">
                    <div class="summary-item-info">
                        <p class="summary-item-name">{{ $item->product->name }}</p>
                        @if($item->variant)
                            <p style="font-size:0.75rem;color:var(--orange);font-weight:600;">{{ $item->variant->label() }}</p>
                        @endif
                        <p class="summary-item-qty">Qty: {{ $item->quantity }}</p>
                    </div>
                    <span class="summary-item-price">₪{{ number_format($item->subtotal()) }}</span>
                </div>
            @endforeach

            <hr class="summary-divider">

            <div class="summary-row">
                <span>Subtotal</span>
                <span id="summarySubtotal">₪{{ number_format($cart->total(), 2) }}</span>
            </div>
            <div class="summary-row" id="discountRow" style="display:none;">
                <span id="discountLabel" style="color:#16a34a;font-weight:600;">Discount</span>
                <span id="discountAmount" style="color:#16a34a;font-weight:700;"></span>
            </div>
            <div class="summary-row" id="shippingRow">
                <span>Shipping</span>
                @php
                    $firstRate = $shippingMethods->isNotEmpty() ? ($shippingMethods->first()->calculated_rate ?? (float)$shippingMethods->first()->price) : 0;
                    $firstFree = $firstRate == 0;
                @endphp
                <span id="shippingCost" style="font-weight:600;color:{{ $firstFree ? '#16a34a' : 'var(--gray-900)' }};">
                    {{ $firstFree ? 'Free' : '₪' . number_format($firstRate, 2) }}
                </span>
            </div>
            <div id="deliveryEstimate" class="delivery-estimate" style="text-align:right;margin-bottom:0.5rem;">
                @if($shippingMethods->isNotEmpty() && ($shippingMethods->first()->estimated_delivery_text ?? $shippingMethods->first()->estimated_delivery))
                    Est. {{ $shippingMethods->first()->estimated_delivery_text ?? $shippingMethods->first()->estimated_delivery }}
                @endif
            </div>
            @if($taxRate > 0)
            <div class="summary-row" id="taxRow">
                <span class="tax-breakdown-tooltip">
                    Tax ({{ number_format($taxRate, $taxRate == floor($taxRate) ? 0 : 2) }}%)
                </span>
                <span id="taxAmount">₪{{ number_format($cart->total() * $taxRate / 100, 2) }}</span>
            </div>
            <div id="taxBreakdownDetail" class="tax-breakdown-detail" style="text-align:right;"></div>
            @endif
            <div class="summary-row total">
                <span>Total</span>
                @php
                    $initialTotal = $cart->total() * (1 + $taxRate / 100) + $firstRate;
                @endphp
                <span id="summaryTotal">₪{{ number_format($initialTotal, 2) }}</span>
            </div>

            <a href="/cart" class="back-link">← Edit cart</a>
        </div>

    </div>
</div>
<script>
const TAX_RATE = {{ $taxRate }};
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
let currentShipping = {{ $firstRate }};
let currentDiscount = 0;
let currentMethodId = {{ $shippingMethods->isNotEmpty() ? $shippingMethods->first()->id : 'null' }};

function getGrandTotal(subtotal, discount, shipping) {
    const afterDiscount = subtotal - discount;
    const tax = afterDiscount * TAX_RATE / 100;
    return afterDiscount + tax + shipping;
}

function selectShipping(methodId, fallbackPrice) {
    currentMethodId = methodId;
    currentShipping = fallbackPrice;

    // Update display immediately with fallback
    const el = document.getElementById('shippingCost');
    if (el) {
        el.textContent = fallbackPrice === 0 ? 'Free' : '₪' + fallbackPrice.toFixed(2);
        el.style.color = fallbackPrice === 0 ? '#16a34a' : 'var(--gray-900)';
    }
    recalcTotal();
    highlightSelected();

    // AJAX call for accurate server-side calculation
    fetchTotals();
}

async function fetchTotals() {
    if (!currentMethodId) return;

    try {
        const res = await fetch('{{ route("checkout.calculate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({
                shipping_method_id: currentMethodId,
                country: document.getElementById('countryInput')?.value || '',
                coupon_code: document.getElementById('couponCode')?.value || ''
            })
        });

        if (!res.ok) return;
        const data = await res.json();

        // Update shipping
        const el = document.getElementById('shippingCost');
        if (el) {
            el.textContent = data.shipping_free ? 'Free' : '₪' + data.shipping_amount.toFixed(2);
            el.style.color = data.shipping_free ? '#16a34a' : 'var(--gray-900)';
        }
        currentShipping = data.shipping_amount;

        // Update delivery estimate
        const deliveryEl = document.getElementById('deliveryEstimate');
        if (deliveryEl) {
            deliveryEl.textContent = data.estimated_delivery ? 'Est. ' + data.estimated_delivery : '';
        }

        // Update tax
        const taxEl = document.getElementById('taxAmount');
        if (taxEl) {
            const totalTax = data.tax_amount + (data.shipping_tax_amount || 0);
            taxEl.textContent = '₪' + totalTax.toFixed(2);
        }

        // Show tax breakdown
        if (data.tax_breakdown && data.tax_breakdown.length > 0) {
            const breakdownEl = document.getElementById('taxBreakdownDetail');
            if (breakdownEl) {
                breakdownEl.innerHTML = data.tax_breakdown.map(t =>
                    `<div>${t.name}: $${t.amount.toFixed(2)}</div>`
                ).join('');
            }
        }

        // Update total
        document.getElementById('summaryTotal').textContent = '₪' + data.grand_total.toFixed(2);

    } catch(e) {
        // Fallback to client-side calculation
        recalcTotal();
    }
}

function recalcTotal() {
    const subtotal = parseFloat('{{ $cart->total() }}');
    const grand = getGrandTotal(subtotal, currentDiscount, currentShipping);

    const tax = (subtotal - currentDiscount) * TAX_RATE / 100;
    if (document.getElementById('taxAmount')) {
        document.getElementById('taxAmount').textContent = '₪' + tax.toFixed(2);
    }
    document.getElementById('summaryTotal').textContent = '₪' + grand.toFixed(2);
}

function highlightSelected() {
    document.querySelectorAll('#shippingOptions label').forEach(label => {
        const radio = label.querySelector('input[type=radio]');
        label.style.borderColor = radio && radio.checked ? 'var(--orange)' : 'var(--gray-200)';
        label.style.background  = radio && radio.checked ? 'rgba(234,88,12,0.04)' : '';
    });
}

document.addEventListener('DOMContentLoaded', highlightSelected);

async function applyCoupon() {
    const code = document.getElementById('couponInput').value.trim();
    const fb   = document.getElementById('couponFeedback');
    if (!code) { fb.innerHTML = '<span style="color:#ef4444;">Enter a coupon code first.</span>'; return; }

    fb.innerHTML = '<span style="color:var(--gray-400);">Checking…</span>';

    try {
        const res = await fetch('{{ route("coupon.apply") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({ code })
        });

        const data = await res.json();

        if (!res.ok) {
            fb.innerHTML = `<span style="color:#ef4444;">✕ ${data.error}</span>`;
            document.getElementById('couponCode').value = '';
            document.getElementById('discountRow').style.display = 'none';
            currentDiscount = 0;
            fetchTotals();
        } else {
            fb.innerHTML = `<span style="color:#16a34a;">✓ ${data.discount_label} applied!</span>`;
            document.getElementById('couponCode').value = data.coupon_code;
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('discountLabel').textContent = 'Discount (' + data.coupon_code + ')';
            document.getElementById('discountAmount').textContent = '-$' + data.discount.toFixed(2);

            currentDiscount = data.discount;
            fetchTotals();
        }
    } catch (e) {
        fb.innerHTML = '<span style="color:#ef4444;">Something went wrong. Please try again.</span>';
    }
}
</script>
</x-layout>
