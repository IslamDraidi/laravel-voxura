<x-admin-layout title="{{ __('admin.customer_reports_title') }}" section="reporting" active="customers">

    {{-- Period selector --}}
    <div class="sub-nav">
        @foreach(['7' => __('admin.period_7'), '30' => __('admin.period_30'), '90' => __('admin.period_90'), '365' => __('admin.period_365_alt')] as $val => $label)
            <a href="{{ route('admin.reports.customers', ['period' => $val]) }}"
               class="sub-btn {{ $period == $val ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-label">{{ __('admin.total_customers_stat') }}</div>
            <div class="sc-value">{{ $totalCustomers }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">{{ __('admin.new_this_period') }}</div>
            <div class="sc-value green">{{ $newCustomers }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">{{ __('admin.active_buyers') }}</div>
            <div class="sc-value blue">{{ $activeCustomers }}</div>
            <div class="sc-sub">{{ __('admin.ordered_in_period') }}</div>
        </div>
    </div>

    <div class="two-col">
        {{-- Signups Chart --}}
        <div class="card">
            <p class="section-title">{{ __('admin.new_signups') }}</p>
            <canvas id="signupChart" height="140"></canvas>
        </div>

        {{-- Top Customers --}}
        <div class="card">
            <p class="section-title">{{ __('admin.top_customers_by_spend') }}</p>
            @if($topCustomers->isEmpty())
                <div class="admin-empty" style="padding:1.5rem;">{{ __('admin.no_orders_yet') }}</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCustomers as $i => $customer)
                        <tr>
                            <td style="color:var(--muted);font-size:12px;">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-weight:600;font-size:13px;">{{ $customer->name }}</div>
                                <div style="font-size:11px;color:var(--muted);">{{ $customer->email }}</div>
                            </td>
                            <td>{{ $customer->orders_count }}</td>
                            <td style="font-weight:700;color:var(--green);">₪{{ number_format($customer->orders_sum_total_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
    new Chart(document.getElementById('signupChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'New Customers',
                data: @json($chartSignups),
                backgroundColor: 'rgba(234,88,12,0.15)',
                borderColor: '#ea580c',
                borderWidth: 2,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
                x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } }
            }
        }
    });
    </script>

</x-admin-layout>
