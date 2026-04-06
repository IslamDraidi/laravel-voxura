<x-layout title="Payment Failed — Order #{{ $order->id }}">
<style>
.failed-page { padding-top: 100px; padding-bottom: 4rem; max-width: 640px; margin: 0 auto; }

.failed-card {
    background: #fff; border: 1.5px solid var(--gray-200);
    border-radius: var(--radius); padding: 2.5rem;
    text-align: center;
}

.failed-icon {
    width: 72px; height: 72px; margin: 0 auto 1.5rem;
    background: #fee2e2; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem;
}
.failed-icon.blocked-icon { background: #fef3c7; }

.failed-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem; font-weight: 800;
    color: var(--gray-900); margin-bottom: 0.75rem;
}

.failed-message {
    font-size: 0.95rem; color: var(--gray-500);
    line-height: 1.6; margin-bottom: 1.5rem;
}

.failed-details {
    background: var(--gray-50); border-radius: 0.5rem;
    padding: 1rem 1.25rem; margin-bottom: 1.5rem;
    font-size: 0.85rem; text-align: left;
}
.failed-details-row {
    display: flex; justify-content: space-between;
    padding: 0.35rem 0; color: var(--gray-600);
}
.failed-details-row span:first-child { color: var(--gray-400); }
.failed-details-row span:last-child { font-weight: 600; }

.attempt-indicator {
    display: inline-block; padding: 0.35rem 1rem;
    background: #fef3c7; color: #92400e;
    font-size: 0.82rem; font-weight: 700;
    border-radius: 999px; margin-bottom: 1.5rem;
}

.failed-actions {
    display: flex; flex-direction: column; gap: 0.75rem;
    align-items: center;
}

.btn-retry {
    display: inline-block; width: 100%; padding: 0.8rem;
    background: var(--orange); color: #fff;
    font-family: 'DM Sans', sans-serif; font-size: 0.9rem; font-weight: 700;
    border: none; border-radius: 999px; cursor: pointer;
    text-decoration: none; text-align: center; transition: background 0.15s;
}
.btn-retry:hover { background: var(--orange-dark); }

.btn-secondary {
    display: inline-block; width: 100%; padding: 0.8rem;
    background: transparent; color: var(--gray-600);
    border: 1.5px solid var(--gray-200); border-radius: 999px;
    font-family: 'DM Sans', sans-serif; font-size: 0.9rem; font-weight: 600;
    text-decoration: none; text-align: center; cursor: pointer;
    transition: color 0.15s, border-color 0.15s;
}
.btn-secondary:hover { color: var(--orange); border-color: var(--orange); }

.support-link {
    font-size: 0.82rem; color: var(--gray-400);
    text-decoration: none; margin-top: 0.5rem;
}
.support-link:hover { color: var(--orange); }
</style>

<div class="failed-page">
    <div class="failed-card">

        @if($blocked)
            <div class="failed-icon blocked-icon">🚫</div>
            <h1 class="failed-title">Payment Blocked</h1>
            <p class="failed-message">
                You've reached the maximum number of payment attempts ({{ $maxAttempts }}) for this order.
                Our team has been notified and will reach out to help.
            </p>
        @else
            <div class="failed-icon">✕</div>
            <h1 class="failed-title">Payment Failed</h1>
            <p class="failed-message">{{ $errorMessage }}</p>
        @endif

        @if($payment)
        <div class="failed-details">
            <div class="failed-details-row">
                <span>Order</span>
                <span>#{{ $order->id }}</span>
            </div>
            <div class="failed-details-row">
                <span>Amount</span>
                <span>${{ number_format($order->grand_total, 2) }}</span>
            </div>
            <div class="failed-details-row">
                <span>Gateway</span>
                <span>{{ ucfirst($payment->gateway) }}</span>
            </div>
            @if($payment->last_attempted_at)
            <div class="failed-details-row">
                <span>Last Attempt</span>
                <span>{{ $payment->last_attempted_at->format('M d, Y · g:i A') }}</span>
            </div>
            @endif
        </div>

        <div class="attempt-indicator">
            Attempt {{ $attempts }} of {{ $maxAttempts }}
        </div>
        @endif

        <div class="failed-actions">
            @if(!$blocked)
                <a href="{{ route('payment.retry', $order) }}" class="btn-retry">
                    Try Again →
                </a>
                <a href="{{ route('payment.methods', $order) }}" class="btn-secondary">
                    Choose Different Method
                </a>
            @endif
            <a href="{{ route('orders.index') }}" class="btn-secondary">
                View My Orders
            </a>
            <a href="/pages/contact" class="support-link">Need help? Contact Support</a>
        </div>

    </div>
</div>
</x-layout>
