<x-store-layout title="Customers" active="customers">

@include('store.dashboard.partials.time-filter', [
    'title'    => 'Customers',
    'subtitle' => 'Customers who ordered your products',
    'range'    => $range,
    'section'  => 'customers',
])

{{-- ── STAT CARDS ── --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr)">

    <div class="stat-card">
        <div class="sc-icon">👥</div>
        <div class="sc-label">Total Customers</div>
        <div class="sc-value">{{ $totalCustomers }}</div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">🆕</div>
        <div class="sc-label">New Customers</div>
        <div class="sc-value green">{{ $newCustomers }}</div>
        <div class="sc-sub">In selected period</div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">🔄</div>
        <div class="sc-label">Returning</div>
        <div class="sc-value blue">{{ $returningCustomers }}</div>
        <div class="sc-sub">Bought more than once</div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">💜</div>
        <div class="sc-label">Avg. Lifetime Value</div>
        <div class="sc-value" style="color:#7c3aed">${{ number_format($clv, 2) }}</div>
        <div class="sc-sub">Per customer total spend</div>
    </div>

</div>

{{-- ── TOP CUSTOMERS ── --}}
<div class="card">
    <div class="section-title">Top Customers</div>
    @if($topCustomers->isEmpty())
        <div class="admin-empty">No customer data for this period.</div>
    @else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Orders</th>
                <th>Total Spent</th>
                <th>Last Order</th>
                <th>Loyalty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topCustomers as $i => $customer)
            @php
                $loyaltyBadge = match(true) {
                    $customer->order_count >= 5 => ['Loyal', 'badge-orange'],
                    $customer->order_count >= 2 => ['Regular', 'badge-blue'],
                    default                     => ['New', 'badge-gray'],
                };
                $lastOrder = \Carbon\Carbon::parse($customer->last_order_at);
            @endphp
            <tr>
                <td style="color:var(--muted);font-weight:600">{{ $i + 1 }}</td>
                <td>
                    <div style="font-weight:600">{{ $customer->name }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ $customer->email }}</div>
                </td>
                <td>{{ $customer->order_count }}</td>
                <td style="font-weight:700">${{ number_format($customer->total_spent, 2) }}</td>
                <td style="color:var(--muted);font-size:12px">{{ $lastOrder->diffForHumans() }}</td>
                <td><span class="badge {{ $loyaltyBadge[1] }}">{{ $loyaltyBadge[0] }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

</x-store-layout>
