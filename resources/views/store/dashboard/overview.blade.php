<x-store-layout title="Overview" active="overview">

@include('store.dashboard.partials.time-filter', [
    'title'    => 'Store Overview',
    'subtitle' => 'Your store performance at a glance',
    'range'    => $range,
    'section'  => 'overview',
])

{{-- ── PUBLISH BANNER ── --}}
@if ($store->onboarding_status !== 'live' && $store->status !== 'approved')
<div class="publish-banner">
  <div class="publish-banner-body">
    <div class="publish-banner-icon">🚀</div>
    <div>
      <h3>{{ __('general.store_not_live') }}</h3>
      <p>{{ __('general.complete_to_publish') }}</p>
      <ul class="publish-checklist">
        <li class="{{ $store->logo_path   ? 'done' : '' }}">
          {{ $store->logo_path   ? '✓' : '○' }} {{ __('general.upload_logo') }}
        </li>
        <li class="{{ $store->banner_path ? 'done' : '' }}">
          {{ $store->banner_path ? '✓' : '○' }} {{ __('general.upload_banner') }}
        </li>
        @php $approvedCount = $store->products()->where('status','approved')->count(); @endphp
        <li class="{{ $approvedCount >= 5 ? 'done' : '' }}">
          {{ $approvedCount >= 5 ? '✓' : '○' }} {{ __('general.add_5_products') }} ({{ $approvedCount }}/5)
        </li>
      </ul>
    </div>
  </div>
  @if ($store->logo_path && $store->banner_path && $approvedCount >= 5)
    <form method="POST" action="{{ route('store.publish') }}">
      @csrf
      <button type="submit" class="btn-publish">{{ __('general.publish_store') }}</button>
    </form>
  @endif
</div>
@endif

{{-- ── STAT CARDS ── --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr)">

    <div class="stat-card">
        <div class="sc-icon">💰</div>
        <div class="sc-label">Total Revenue</div>
        <div class="sc-value">${{ number_format($totalRevenue, 2) }}</div>
        <div class="sc-sub">{{ $range === 'today' ? 'Today' : 'This ' . ucfirst($range) }}</div>
        <div class="sc-trend {{ $revenueChange >= 0 ? 'up' : 'down' }}">
            {{ $revenueChange >= 0 ? '▲' : '▼' }}
            {{ abs(round($revenueChange, 1)) }}% vs previous period
        </div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">📈</div>
        <div class="sc-label">Net Profit</div>
        <div class="sc-value green">${{ number_format($netProfit, 2) }}</div>
        <div class="sc-sub">After {{ $store->commission_rate ?? 0 }}% Voxura commission</div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">🛍️</div>
        <div class="sc-label">Total Orders</div>
        <div class="sc-value blue">{{ $totalOrders }}</div>
        <div class="sc-sub">Avg. order: ${{ number_format($aov, 2) }}</div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">🎯</div>
        <div class="sc-label">Conversion Rate</div>
        <div class="sc-value" style="color:#7c3aed">{{ round($conversionRate, 1) }}%</div>
        <div class="sc-sub">Store visits → purchases</div>
    </div>

</div>

@if($has3DAccess)
<div class="stat-grid" style="grid-template-columns:repeat(2,1fr);margin-top:1rem">

    <div class="stat-card">
        <div class="sc-icon">🎲</div>
        <div class="sc-label">3D Credits</div>
        <div class="sc-value purple">{{ $creditBalance }}</div>
        <div class="sc-sub">{{ $creditMonthly }}/mo · {{ $creditUsedTotal }} used total</div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">📦</div>
        <div class="sc-label">3D Products</div>
        <div class="sc-value purple">{{ $products3DCount }}</div>
        <div class="sc-sub">{{ $products3DPending }} generating now</div>
    </div>

</div>
@endif

{{-- ── REVENUE CHART ── --}}
<div class="chart-card">
    <div class="chart-card-header">
        <h3>Revenue Over Time</h3>
        <span class="chart-subtitle">{{ ucfirst($range) }} performance</span>
    </div>
    <canvas id="revenue-chart" height="80"></canvas>
</div>

{{-- ── ORDER STATUS + TOP PRODUCTS ── --}}
<div class="two-col">

    {{-- Order Status Queue --}}
    <div class="card">
        <div class="section-title">Order Status</div>
        <div class="status-queue">
            <a href="{{ route('store.dashboard.orders', ['range' => $range, 'status' => 'pending']) }}"
               class="sq-card amber">
                <div class="sq-icon">⏳</div>
                <div class="sq-count">{{ $orderStatuses['pending'] ?? 0 }}</div>
                <div class="sq-label">Pending</div>
            </a>
            <a href="{{ route('store.dashboard.orders', ['range' => $range, 'status' => 'processing']) }}"
               class="sq-card blue">
                <div class="sq-icon">⚙️</div>
                <div class="sq-count">{{ $orderStatuses['processing'] ?? 0 }}</div>
                <div class="sq-label">Processing</div>
            </a>
            <a href="{{ route('store.dashboard.orders', ['range' => $range, 'status' => 'shipped']) }}"
               class="sq-card green">
                <div class="sq-icon">📬</div>
                <div class="sq-count">{{ $orderStatuses['shipped'] ?? 0 }}</div>
                <div class="sq-label">Shipped</div>
            </a>
            <a href="{{ route('store.dashboard.orders', ['range' => $range, 'status' => 'cancelled']) }}"
               class="sq-card red">
                <div class="sq-icon">✕</div>
                <div class="sq-count">{{ $orderStatuses['cancelled'] ?? 0 }}</div>
                <div class="sq-label">Cancelled</div>
            </a>
        </div>
    </div>

    {{-- Top 5 Products --}}
    <div class="card">
        <div class="section-title">Top Products</div>
        @if($topProducts->isEmpty())
            <div class="admin-empty">No sales data yet</div>
        @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Units Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $i => $p)
                <tr>
                    <td style="color:var(--muted);font-weight:600">{{ $i + 1 }}</td>
                    <td>{{ Str::limit($p->name, 25) }}</td>
                    <td>{{ $p->units_sold }}</td>
                    <td style="font-weight:600">${{ number_format($p->revenue, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const chartData = @json($chartData);
    const labels   = chartData.map(d => d.date);
    const revenues = chartData.map(d => parseFloat(d.revenue));

    if (!document.getElementById('revenue-chart')) return;

    new Chart(document.getElementById('revenue-chart').getContext('2d'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Revenue ($)',
                data: revenues,
                borderColor: '#ea580c',
                backgroundColor: 'rgba(234,88,12,0.07)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#ea580c',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => '₪' + ctx.parsed.y.toFixed(2)
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { color: '#9ca3af', font: { size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        color: '#9ca3af', font: { size: 11 },
                        callback: (val) => '₪' + val
                    }
                }
            }
        }
    });
})();
</script>

</x-store-layout>
