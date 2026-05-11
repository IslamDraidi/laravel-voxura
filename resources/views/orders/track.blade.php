<x-layout title="{{ __('general.track_order') }} #{{ $order->id }}">
<style>
.track-page { padding-top: 100px; padding-bottom: 4rem; max-width: 760px; margin: 0 auto; padding-left: 1rem; padding-right: 1rem; }

.track-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2.5rem;
}

.track-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.6rem, 4vw, 2.2rem);
    font-weight: 800;
    color: var(--gray-900);
    letter-spacing: -0.03em;
    margin: 0;
}
.track-title span { color: var(--orange); }

.btn-back {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.5rem 1.25rem;
    border: 1.5px solid var(--gray-200); border-radius: 999px;
    text-decoration: none; color: var(--gray-600);
    font-size: 0.85rem; font-weight: 600;
    transition: color 0.15s, border-color 0.15s;
    white-space: nowrap;
}
.btn-back:hover { color: var(--orange); border-color: var(--orange); }

/* Status badge */
.status-badge {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.35rem 1rem;
    border-radius: 999px;
    font-size: 0.8rem; font-weight: 700; letter-spacing: 0.02em;
    margin-bottom: 2rem;
}

/* Timeline */
.timeline {
    position: relative;
    padding: 0;
    list-style: none;
    margin: 0 0 2.5rem 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 19px;
    top: 0; bottom: 0;
    width: 2px;
    background: var(--gray-200);
}

.tl-step {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 1.25rem;
    padding: 0 0 2rem 0;
}
.tl-step:last-child { padding-bottom: 0; }

.tl-icon {
    flex-shrink: 0;
    width: 40px; height: 40px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    position: relative; z-index: 1;
    font-size: 1rem;
    border: 2px solid transparent;
    transition: all 0.2s;
}

.tl-icon.done {
    background: var(--orange);
    border-color: var(--orange);
    color: #fff;
    box-shadow: 0 2px 12px rgba(234,88,12,0.25);
}

.tl-icon.active {
    background: var(--orange-light);
    border-color: var(--orange);
    color: var(--orange);
    animation: pulse-ring 2s ease infinite;
}

.tl-icon.pending {
    background: #f9fafb;
    border-color: var(--gray-200);
    color: var(--gray-400);
}

.tl-icon.terminal-cancelled {
    background: #fef2f2;
    border-color: #fca5a5;
    color: #dc2626;
}

@keyframes pulse-ring {
    0%, 100% { box-shadow: 0 0 0 0 rgba(234,88,12,0.35); }
    50% { box-shadow: 0 0 0 6px rgba(234,88,12,0); }
}

.tl-body { padding-top: 7px; }

.tl-label {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.2rem;
}
.tl-label.pending-label { color: var(--gray-400); font-weight: 600; }

.tl-desc {
    font-size: 0.85rem;
    color: var(--gray-500);
    line-height: 1.5;
}

/* Info card */
.info-card {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.info-card-title {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--gray-400);
    margin: 0 0 1rem 0;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 1rem;
    padding: 0.45rem 0;
    border-bottom: 1px solid var(--gray-100);
    font-size: 0.9rem;
}
.info-row:last-child { border-bottom: none; }
.info-row .label { color: var(--gray-500); }
.info-row .value { font-weight: 600; color: var(--gray-900); text-align: right; }

@media (max-width: 500px) {
    .timeline::before { left: 15px; }
    .tl-icon { width: 32px; height: 32px; font-size: 0.85rem; }
    .tl-step { gap: 1rem; }
}
</style>

<div class="track-page">

    <div class="track-header">
        <h1 class="track-title">{{ __('general.track_order') }} <span>#{{ $order->id }}</span></h1>
        <a href="{{ route('orders.show', $order) }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            {{ __('general.order_details') }}
        </a>
    </div>

    {{-- Status badge --}}
    <div class="status-badge" style="background:{{ $order->statusBg() }};color:{{ $order->statusColor() }};">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg>
        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
    </div>

    {{-- Timeline --}}
    @php
        $isCancelled = $order->status === 'cancelled';
        $isRefunded  = in_array($order->status, ['refunded', 'partially_refunded']);

        $steps = [
            [
                'key'    => 'pending',
                'label'  => __('general.order_status_pending'),
                'desc'   => __('general.track_desc_pending'),
                'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
            ],
            [
                'key'    => 'paid',
                'label'  => __('general.order_status_paid'),
                'desc'   => __('general.track_desc_paid'),
                'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>',
            ],
            [
                'key'    => 'processing',
                'label'  => __('general.order_status_processing'),
                'desc'   => __('general.track_desc_processing'),
                'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
            ],
            [
                'key'    => 'shipped',
                'label'  => __('general.order_status_shipped'),
                'desc'   => __('general.track_desc_shipped'),
                'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>',
            ],
            [
                'key'    => 'delivered',
                'label'  => __('general.order_status_delivered'),
                'desc'   => __('general.track_desc_delivered'),
                'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>',
            ],
        ];

        $statusOrder = ['pending', 'paid', 'processing', 'shipped', 'delivered'];
        $currentIdx  = array_search($order->status, $statusOrder);
        if ($currentIdx === false) $currentIdx = -1;
    @endphp

    @if($isCancelled || $isRefunded)
    {{-- Terminal cancelled / refunded state --}}
    <ul class="timeline">
        <li class="tl-step">
            <div class="tl-icon terminal-cancelled">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div class="tl-body">
                <div class="tl-label">{{ $isCancelled ? __('general.order_status_cancelled') : __('general.order_status_refunded') }}</div>
                <div class="tl-desc">
                    @if($isCancelled)
                        {{ __('general.track_desc_cancelled') }}
                    @else
                        {{ __('general.track_desc_refunded') }}
                    @endif
                </div>
            </div>
        </li>
    </ul>
    @else
    <ul class="timeline">
        @foreach($steps as $idx => $step)
        @php
            $stepIdx = array_search($step['key'], $statusOrder);
            if ($stepIdx < $currentIdx) $state = 'done';
            elseif ($stepIdx === $currentIdx) $state = 'active';
            else $state = 'pending';
        @endphp
        <li class="tl-step">
            <div class="tl-icon {{ $state }}">
                {!! $step['icon'] !!}
            </div>
            <div class="tl-body">
                <div class="tl-label {{ $state === 'pending' ? 'pending-label' : '' }}">{{ $step['label'] }}</div>
                @if($state !== 'pending')
                <div class="tl-desc">{{ $step['desc'] }}</div>
                @endif
            </div>
        </li>
        @endforeach
    </ul>
    @endif

    {{-- Order info card --}}
    <div class="info-card">
        <p class="info-card-title">{{ __('general.order_information') }}</p>
        <div class="info-row">
            <span class="label">{{ __('general.order_number') }}</span>
            <span class="value">#{{ $order->id }}</span>
        </div>
        <div class="info-row">
            <span class="label">{{ __('general.order_placed_label') }}</span>
            <span class="value">{{ $order->created_at->format('M d, Y') }}</span>
        </div>
        <div class="info-row">
            <span class="label">{{ __('general.total') }}</span>
            <span class="value">₪{{ number_format($order->grand_total ?: $order->grandTotal(), 2) }}</span>
        </div>
        @if($order->shippingMethod)
        <div class="info-row">
            <span class="label">{{ __('general.shipping_method') }}</span>
            <span class="value">{{ $order->shippingMethod->name }}</span>
        </div>
        @endif
        @if($order->shipping_address)
        <div class="info-row">
            <span class="label">{{ __('general.shipping_address') }}</span>
            <span class="value" style="max-width:60%;">{{ is_array($order->shipping_address) ? implode(', ', array_filter($order->shipping_address)) : $order->shipping_address }}</span>
        </div>
        @endif
    </div>

</div>
</x-layout>
