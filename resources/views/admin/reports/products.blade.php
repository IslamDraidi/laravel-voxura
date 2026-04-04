<x-admin-layout title="Product Reports" section="reporting" active="products">

    {{-- Period selector --}}
    <div class="sub-nav">
        @foreach(['7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days', '365' => 'Last year'] as $val => $label)
            <a href="{{ route('admin.reports.products', ['period' => $val]) }}"
               class="sub-btn {{ $period == $val ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-label">Revenue (Period)</div>
            <div class="sc-value green" style="font-size:1.5rem;">${{ number_format($totalRevenue, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Units Sold</div>
            <div class="sc-value">{{ number_format($totalUnitsSold) }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Products Sold</div>
            <div class="sc-value blue">{{ $topProducts->count() }}</div>
        </div>
    </div>

    <div class="two-col" style="margin-bottom:0;">
        {{-- Revenue by Category Chart --}}
        <div class="card" style="margin-bottom:16px;">
            <p class="section-title">Revenue by Category</p>
            @if($revenueByCategory->isEmpty())
                <div class="admin-empty" style="padding:1.5rem;">No sales data yet.</div>
            @else
                <canvas id="catChart" height="160"></canvas>
            @endif
        </div>

        {{-- Top 5 Products --}}
        <div class="card" style="margin-bottom:16px;">
            <p class="section-title">Top 5 Products</p>
            @if($topProducts->isEmpty())
                <div class="admin-empty" style="padding:1.5rem;">No sales data yet.</div>
            @else
                @foreach($topProducts->take(5) as $i => $product)
                @php $pct = $totalRevenue > 0 ? ($product->total_revenue / $totalRevenue) * 100 : 0; @endphp
                <div style="margin-bottom:12px;">
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:3px;">
                        <span style="font-weight:600;">{{ $product->name }}</span>
                        <span style="color:var(--green);font-weight:700;">${{ number_format($product->total_revenue, 2) }}</span>
                    </div>
                    <div style="background:var(--gray-100);border-radius:4px;height:6px;">
                        <div style="background:var(--orange);height:6px;border-radius:4px;width:{{ min(100, round($pct)) }}%;"></div>
                    </div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px;">{{ $product->total_qty }} units · {{ round($pct, 1) }}% of revenue</div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Full product table --}}
    @if($topProducts->isNotEmpty())
    <div class="card">
        <p class="section-title">All Products — Sales Breakdown</p>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Units Sold</th>
                    <th>Revenue</th>
                    <th>Revenue Share</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $i => $product)
                @php $pct = $totalRevenue > 0 ? ($product->total_revenue / $totalRevenue) * 100 : 0; @endphp
                <tr>
                    <td style="color:var(--muted);font-size:12px;">{{ $i + 1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            @if($product->image)
                                <img src="{{ asset('images/'.$product->image) }}" style="width:32px;height:32px;object-fit:cover;border-radius:4px;">
                            @endif
                            <span style="font-weight:600;font-size:13px;">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:12px;color:var(--muted);">{{ $product->category }}</td>
                    <td>{{ $product->total_qty }}</td>
                    <td style="font-weight:700;color:var(--green);">${{ number_format($product->total_revenue, 2) }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <div style="background:var(--gray-100);border-radius:4px;height:6px;width:80px;">
                                <div style="background:var(--orange);height:6px;border-radius:4px;width:{{ min(100, round($pct)) }}%;"></div>
                            </div>
                            <span style="font-size:12px;color:var(--muted);">{{ round($pct, 1) }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
    @if($revenueByCategory->isNotEmpty())
    new Chart(document.getElementById('catChart'), {
        type: 'doughnut',
        data: {
            labels: @json($catLabels),
            datasets: [{
                data: @json($catRevenues),
                backgroundColor: ['#ea580c','#f97316','#fb923c','#fdba74','#fed7aa','#fef3c7','#fde68a','#fcd34d'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 12 }, padding: 12 } }
            }
        }
    });
    @endif
    </script>

</x-admin-layout>
