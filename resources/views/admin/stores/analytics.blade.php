<x-admin-layout title="Store Analytics" section="stores" active="stores-analytics">

<style>
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
.stat-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:16px 18px;}
.stat-num{font-size:22px;font-weight:800;color:var(--dark);display:block;}
.stat-label{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-top:2px;}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;}
.card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:20px;margin-bottom:20px;}
.card-title{font-size:13px;font-weight:700;color:var(--dark);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--border);}
.analytics-table{width:100%;border-collapse:collapse;}
.analytics-table th{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);padding:8px 12px;text-align:left;border-bottom:1px solid var(--border);background:#fafafa;}
.analytics-table td{padding:10px 12px;font-size:13px;border-bottom:1px solid var(--gray-100);}
.analytics-table tr:hover td{background:var(--gray-50);}
.rank-num{width:28px;height:28px;border-radius:50%;background:var(--orange);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;}
.plan-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px;}
.plan-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:20px;text-align:center;}
.plan-name{font-size:13px;font-weight:700;color:var(--dark);margin-bottom:8px;}
.plan-count{font-size:32px;font-weight:800;}
.cat-bar{display:flex;align-items:center;gap:10px;margin-bottom:8px;}
.cat-name{font-size:13px;color:var(--dark);min-width:120px;}
.cat-track{flex:1;height:6px;background:var(--gray-100);border-radius:3px;overflow:hidden;}
.cat-fill{height:100%;background:var(--orange);border-radius:3px;}
.cat-count{font-size:12px;color:var(--muted);min-width:30px;text-align:right;}
.badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-approved{background:#dcfce7;color:#15803d;}
.expiry-warn{color:var(--amber);font-weight:600;}
.expiry-expired{color:var(--red);font-weight:600;}
.back-link{display:inline-flex;align-items:center;gap:6px;color:var(--muted);font-size:13px;text-decoration:none;margin-bottom:16px;}
.back-link:hover{color:var(--dark);}
</style>

<a href="{{ route('admin.stores.index') }}" class="back-link">← All Stores</a>

{{-- Overview Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-num">{{ $analytics['new_this_month'] }}</span>
        <span class="stat-label">New This Month</span>
    </div>
    <div class="stat-card">
        <span class="stat-num">{{ $analytics['new_this_week'] }}</span>
        <span class="stat-label">New This Week</span>
    </div>
    <div class="stat-card">
        <span class="stat-num" style="{{ $analytics['never_visited'] > 0 ? 'color:var(--amber)' : '' }}">{{ $analytics['never_visited'] }}</span>
        <span class="stat-label">Never Visited</span>
    </div>
    <div class="stat-card">
        <span class="stat-num">{{ $analytics['stores_no_products'] }}</span>
        <span class="stat-label">No Products</span>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-num" style="color:var(--green)">{{ $analytics['active_subscriptions'] }}</span>
        <span class="stat-label">Active Subscriptions</span>
    </div>
    <div class="stat-card">
        <span class="stat-num" style="{{ $analytics['expiring_soon']->count() > 0 ? 'color:var(--amber)' : '' }}">{{ $analytics['expiring_soon']->count() }}</span>
        <span class="stat-label">Expiring Soon (7d)</span>
    </div>
    <div class="stat-card">
        <span class="stat-num" style="{{ $analytics['expired'] > 0 ? 'color:var(--red)' : '' }}">{{ $analytics['expired'] }}</span>
        <span class="stat-label">Expired Subscriptions</span>
    </div>
    <div class="stat-card">
        <span class="stat-num">{{ $analytics['avg_products_per_store'] }}</span>
        <span class="stat-label">Avg Products / Store</span>
    </div>
</div>

{{-- Visit Analytics --}}
<div class="two-col">
    <div class="card" style="margin-bottom:0;">
        <div class="card-title">Most Visited Stores</div>
        <table class="analytics-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Store</th>
                    <th>Visits</th>
                    <th>Last Visit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($analytics['most_visited'] as $i => $store)
                <tr>
                    <td><span class="rank-num">{{ $i + 1 }}</span></td>
                    <td>
                        <a href="{{ route('admin.stores.show', $store) }}" style="font-weight:600;color:var(--dark);text-decoration:none;">{{ $store->name }}</a>
                        <div style="font-size:11px;color:var(--muted);">{{ $store->products_count }} products</div>
                    </td>
                    <td style="font-weight:600;">{{ number_format($store->visit_count) }}</td>
                    <td style="font-size:12px;color:var(--muted);">{{ $store->last_visited_at ? $store->last_visited_at->diffForHumans() : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:20px;">No data yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card" style="margin-bottom:0;">
        <div class="card-title">Least Visited Stores</div>
        <table class="analytics-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Store</th>
                    <th>Visits</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($analytics['least_visited'] as $i => $store)
                <tr>
                    <td><span class="rank-num" style="background:var(--gray-400);">{{ $i + 1 }}</span></td>
                    <td>
                        <a href="{{ route('admin.stores.show', $store) }}" style="font-weight:600;color:var(--dark);text-decoration:none;">{{ $store->name }}</a>
                    </td>
                    <td>{{ number_format($store->visit_count) }}</td>
                    <td><span class="badge badge-approved">Active</span></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:20px;">No data yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Plan Breakdown --}}
<div class="plan-cards">
    @php
        $planData = $analytics['plan_breakdown']->keyBy('plan_type');
    @endphp
    <div class="plan-card">
        <div class="plan-name">Basic Plan</div>
        <div class="plan-count" style="color:var(--gray-500);">{{ $planData->get('basic')->count ?? 0 }}</div>
        <div style="font-size:12px;color:var(--muted);">stores</div>
    </div>
    <div class="plan-card">
        <div class="plan-name">Pro Plan</div>
        <div class="plan-count" style="color:var(--blue);">{{ $planData->get('pro')->count ?? 0 }}</div>
        <div style="font-size:12px;color:var(--muted);">stores</div>
    </div>
    <div class="plan-card">
        <div class="plan-name">Premium Plan</div>
        <div class="plan-count" style="color:var(--orange);">{{ $planData->get('premium')->count ?? 0 }}</div>
        <div style="font-size:12px;color:var(--muted);">stores</div>
    </div>
</div>

{{-- Expiring Subscriptions --}}
@if($analytics['expiring_soon']->isNotEmpty())
<div class="card">
    <div class="card-title">Expiring Within 7 Days</div>
    <table class="analytics-table">
        <thead>
            <tr>
                <th>Store</th>
                <th>Plan</th>
                <th>Expiry Date</th>
                <th>Days Left</th>
            </tr>
        </thead>
        <tbody>
            @foreach($analytics['expiring_soon'] as $store)
            <tr>
                <td>
                    <a href="{{ route('admin.stores.show', $store) }}" style="font-weight:600;color:var(--dark);text-decoration:none;">{{ $store->name }}</a>
                </td>
                <td>{{ ucfirst($store->plan_type) }}</td>
                <td>{{ $store->subscription_expires_at->format('d M Y') }}</td>
                <td class="{{ $store->days_until_expiry <= 3 ? 'expiry-expired' : 'expiry-warn' }}">
                    {{ $store->days_until_expiry }} days
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Two Col: Category Breakdown + Recently Active --}}
<div class="two-col">
    <div class="card" style="margin-bottom:0;">
        <div class="card-title">Top Categories Across Stores</div>
        @if($analytics['top_categories']->isEmpty())
            <p style="color:var(--muted);font-size:13px;">No category data yet.</p>
        @else
            @php $maxCat = $analytics['top_categories']->first(); @endphp
            @foreach($analytics['top_categories'] as $cat => $count)
            <div class="cat-bar">
                <div class="cat-name">{{ $cat }}</div>
                <div class="cat-track">
                    <div class="cat-fill" style="width:{{ $maxCat > 0 ? round($count / $maxCat * 100) : 0 }}%;"></div>
                </div>
                <div class="cat-count">{{ $count }}</div>
            </div>
            @endforeach
        @endif
    </div>
    <div class="card" style="margin-bottom:0;">
        <div class="card-title">Recently Active Stores</div>
        <table class="analytics-table">
            <thead>
                <tr>
                    <th>Store</th>
                    <th>Last Visit</th>
                    <th>Total Visits</th>
                </tr>
            </thead>
            <tbody>
                @forelse($analytics['recently_active'] as $store)
                <tr>
                    <td>
                        <a href="{{ route('admin.stores.show', $store) }}" style="font-weight:600;color:var(--dark);text-decoration:none;">{{ $store->name }}</a>
                    </td>
                    <td style="font-size:12px;color:var(--muted);">{{ $store->last_visited_at->diffForHumans() }}</td>
                    <td style="font-weight:600;">{{ number_format($store->visit_count) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:var(--muted);padding:20px;">No recent visits</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</x-admin-layout>
