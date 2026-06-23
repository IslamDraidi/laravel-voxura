<h1>Traffic Report</h1>

<div class="stat-grid" style="grid-template-columns:repeat(3,1fr)">
    <div class="stat-box">
        <div class="stat-value">{{ number_format($totalVisits) }}</div>
        <div class="stat-label">Total Store Visits</div>
        <div class="stat-sub">All time</div>
    </div>
    <div class="stat-box">
        <div class="stat-value" style="font-size:15px">
            {{ $lastVisited ? $lastVisited->format('M d, Y') : 'No visits yet' }}
        </div>
        <div class="stat-label">Last Visit</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">{{ $store->products()->count() }}</div>
        <div class="stat-label">Products Listed</div>
    </div>
</div>

<h2>Estimated Traffic Sources</h2>
<p style="font-size:12px;color:#888;margin-bottom:16px">
    Note: Per-source tracking is coming soon. These are estimated platform averages.
</p>
@foreach($trafficSources as $source => $pct)
<div class="progress-row">
    <div class="pb-label">
        <span>{{ $source }}</span>
        <span><strong>{{ $pct }}%</strong></span>
    </div>
    <div class="pb-track">
        <div class="pb-fill" style="width:{{ $pct }}%"></div>
    </div>
</div>
@endforeach

<h2>Page Views</h2>
<table>
    <thead>
        <tr><th>Page</th><th>Estimated Views</th></tr>
    </thead>
    <tbody>
        @foreach($pageViews as $page => $views)
        <tr>
            <td>{{ $page }}</td>
            <td>{{ number_format($views) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
