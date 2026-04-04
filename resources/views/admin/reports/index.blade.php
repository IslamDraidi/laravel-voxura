<x-admin-layout title="Reports" section="reporting" active="sales">
<style>
.chart-wrap { position: relative; height: 280px; }
.status-bars { display: flex; flex-direction: column; gap: 0.85rem; }
.status-bar-item { display: flex; flex-direction: column; gap: 0.3rem; }
.status-bar-label { display: flex; justify-content: space-between; font-size: 0.83rem; }
.status-bar-label span:first-child { font-weight: 600; color: var(--dark); }
.status-bar-label span:last-child { font-weight: 700; color: var(--dark); }
.bar-track { height: 8px; background: #f3f4f6; border-radius: 999px; overflow: hidden; }
.bar-fill { height: 100%; border-radius: 999px; transition: width 0.6s ease; }
.rank { font-weight: 800; color: var(--orange); font-family: 'Playfair Display', serif; font-size: 1.1rem; }
</style>

{{-- Period Tabs --}}
<div class="sub-nav" style="margin-bottom:1.75rem;">
    @foreach(['7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days', '365' => 'This year'] as $val => $label)
        <a href="{{ route('admin.reports.index', ['period' => $val]) }}"
           class="sub-btn {{ $period == $val ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</div>

{{-- Stats --}}
<div class="stat-grid" style="margin-bottom:2rem;">
    <div class="stat-card">
        <span class="sc-label">Total Revenue</span>
        <span class="sc-value" style="color:var(--orange);">${{ number_format($revenue) }}</span>
        <span class="sc-sub">Excluding cancelled orders</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">Orders</span>
        <span class="sc-value green">{{ number_format($ordersCount) }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">New Customers</span>
        <span class="sc-value blue">{{ number_format($newCustomers) }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">Avg. Order Value</span>
        <span class="sc-value">${{ number_format($avgOrder, 2) }}</span>
    </div>
</div>

{{-- Revenue Chart --}}
<div class="card" style="margin-bottom:2rem;">
    <p class="section-title">Revenue Over Time</p>
    <div class="chart-wrap">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<div class="two-col">

    {{-- Top Products --}}
    <div class="card">
        <p class="section-title">Top Products</p>
        @if($topProducts->isEmpty())
            <p style="color:var(--muted);font-size:0.9rem;">No data for this period.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $i => $product)
                    <tr>
                        <td><span class="rank">{{ $i + 1 }}</span></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ number_format($product->total_qty) }}</td>
                        <td style="font-weight:700;color:var(--orange);">${{ number_format($product->total_revenue) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Order Status Breakdown --}}
    <div class="card">
        <p class="section-title">Order Status Breakdown</p>
        @php
            $total = $statusBreakdown->sum();
            $statusColors = ['pending'=>'#f59e0b','processing'=>'#3b82f6','shipped'=>'#8b5cf6','delivered'=>'#16a34a','cancelled'=>'#ef4444'];
        @endphp
        @if($total === 0)
            <p style="color:var(--muted);font-size:0.9rem;">No orders for this period.</p>
        @else
            <div class="status-bars">
                @foreach(['pending','processing','shipped','delivered','cancelled'] as $status)
                @php $count = $statusBreakdown->get($status, 0); $pct = $total > 0 ? round($count / $total * 100) : 0; @endphp
                <div class="status-bar-item">
                    <div class="status-bar-label">
                        <span style="text-transform:capitalize;">{{ $status }}</span>
                        <span>{{ $count }} ({{ $pct }}%)</span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width:{{ $pct }}%;background:{{ $statusColors[$status] ?? '#6b7280' }};"></div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Revenue ($)',
            data: @json($chartRevenue),
            borderColor: '#ea580c',
            backgroundColor: 'rgba(234,88,12,0.08)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#ea580c',
            pointRadius: @json(count($chartLabels) > 30 ? 0 : 3),
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' $' + Number(ctx.parsed.y).toLocaleString('en-US', {minimumFractionDigits: 2})
                }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { family: 'DM Sans' }, maxRotation: 0 } },
            y: {
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: {
                    font: { family: 'DM Sans' },
                    callback: v => '$' + v.toLocaleString()
                }
            }
        }
    }
});
</script>
</x-admin-layout>
