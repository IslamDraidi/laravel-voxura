<x-store-layout title="Traffic" active="traffic">

@include('store.dashboard.partials.time-filter', [
    'title'    => 'Traffic',
    'subtitle' => 'Store visits and page performance',
    'range'    => $range,
    'section'  => 'traffic',
])

{{-- ── STAT CARDS ── --}}
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr)">

    <div class="stat-card">
        <div class="sc-icon">👁️</div>
        <div class="sc-label">Total Store Visits</div>
        <div class="sc-value">{{ number_format($totalVisits) }}</div>
        <div class="sc-sub">All time</div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">🕐</div>
        <div class="sc-label">Last Visit</div>
        <div class="sc-value" style="font-size:16px;line-height:1.4">
            {{ $lastVisited ? $lastVisited->diffForHumans() : 'No visits yet' }}
        </div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">📦</div>
        <div class="sc-label">Products Listed</div>
        <div class="sc-value blue">{{ $store->products()->count() }}</div>
    </div>

</div>

{{-- ── TRAFFIC SOURCES ── --}}
<div class="card" style="margin-bottom:16px">
    <div class="section-title">Estimated Traffic Sources</div>
    <div class="info-card" style="margin-bottom:16px">
        <h4>ℹ️ Estimated Breakdown</h4>
        <p>Detailed traffic source tracking is coming soon. Showing estimated breakdown based on platform averages.</p>
    </div>
    @foreach($trafficSources as $source => $pct)
    <div class="progress-bar-wrap">
        <div class="pb-header">
            <span style="font-size:13px;font-weight:500;color:var(--dark)">{{ $source }}</span>
            <span style="font-size:13px;font-weight:600;color:var(--orange)">{{ $pct }}%</span>
        </div>
        <div class="pb-track">
            <div class="pb-fill" style="width:{{ $pct }}%"></div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── PAGE VIEWS ── --}}
<div class="card" style="margin-bottom:16px">
    <div class="section-title">Page Views Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Page</th>
                <th>Estimated Views</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pageViews as $page => $views)
            <tr>
                <td>{{ $page }}</td>
                <td style="font-weight:600">{{ number_format($views) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ── COMING SOON NOTE ── --}}
<div class="info-card">
    <h4>📊 Advanced Analytics Coming Soon</h4>
    <p>
        Per-page visit tracking, traffic source breakdown, and ROAS reporting will be available
        in the next platform update. All visit data is being collected now and will be retroactively
        analyzed when the feature launches.
    </p>
</div>

</x-store-layout>
