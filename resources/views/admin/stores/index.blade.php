<x-admin-layout title="Stores" section="stores" active="stores">

<style>
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:16px;}
.stat-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:18px 20px;}
.stat-num{font-size:26px;font-weight:800;color:var(--dark);display:block;}
.stat-label{font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-top:2px;}
.highlights-row{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px;}
.highlight-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:16px 18px;display:flex;flex-direction:column;gap:4px;}
.highlight-title{font-size:11px;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);font-weight:600;}
.highlight-value{font-size:18px;font-weight:700;color:var(--dark);}
.highlight-sub{font-size:12px;color:var(--muted);}
.highlight-link{font-size:12px;color:var(--orange);text-decoration:none;margin-top:4px;}
.search-bar{margin-bottom:16px;}
.search-bar input{width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;font-size:13px;font-family:inherit;color:var(--dark);background:#fff;}
.search-bar input:focus{outline:none;border-color:var(--orange);}
.tab-bar{display:flex;gap:0;margin-bottom:0;border-bottom:1px solid var(--border);}
.tab-btn{padding:10px 16px;font-size:13px;font-weight:600;border:none;background:none;color:var(--muted);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-1px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;}
.tab-btn.active{color:var(--orange);border-bottom-color:var(--orange);}
.tab-dot{width:6px;height:6px;border-radius:50%;background:var(--orange);display:inline-block;}
.stores-table{width:100%;border-collapse:collapse;margin-top:0;}
.stores-table th{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);padding:10px 12px;text-align:left;border-bottom:1px solid var(--border);background:#fafafa;}
.stores-table td{padding:12px 12px;font-size:13px;border-bottom:1px solid var(--gray-100);vertical-align:middle;}
.stores-table tr:hover td{background:var(--gray-50);}
.store-avatar{width:40px;height:40px;border-radius:10px;background:var(--orange);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;flex-shrink:0;overflow:hidden;}
.store-avatar img{width:100%;height:100%;object-fit:cover;}
.store-cell{display:flex;align-items:center;gap:10px;}
.store-name{font-weight:600;color:var(--dark);}
.store-slug{font-size:11px;color:var(--muted);}
.badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-pending{background:#fef3c7;color:#92400e;}
.badge-approved{background:#dcfce7;color:#15803d;}
.badge-rejected{background:#fee2e2;color:#991b1b;}
.badge-suspended{background:#f3f4f6;color:#374151;}
.plan-basic{background:#f3f4f6;color:#374151;}
.plan-pro{background:#dbeafe;color:#1d4ed8;}
.plan-premium{background:#fff7ed;color:#c2410c;}
.expiry-ok{color:#15803d;font-weight:600;}
.expiry-warn{color:#d97706;font-weight:600;}
.expiry-expired{color:#dc2626;font-weight:600;}
.btn-sm{display:inline-flex;align-items:center;gap:3px;padding:5px 10px;border-radius:6px;font-size:12px;font-weight:600;border:none;cursor:pointer;text-decoration:none;transition:background .15s;}
.btn-view{background:var(--gray-100);color:var(--gray-600);}
.btn-view:hover{background:var(--gray-200);}
.btn-edit{background:#dbeafe;color:#1d4ed8;}
.btn-edit:hover{background:#bfdbfe;}
.dropdown-wrap{position:relative;display:inline-block;}
.dropdown-btn{background:var(--gray-100);color:var(--gray-600);padding:5px 10px;border-radius:6px;border:none;cursor:pointer;font-size:12px;font-weight:600;}
.dropdown-btn:hover{background:var(--gray-200);}
.dropdown-menu{display:none;position:absolute;right:0;top:100%;margin-top:4px;background:#fff;border:1px solid var(--border);border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,.1);min-width:160px;z-index:100;}
.dropdown-menu.open{display:block;}
.dropdown-item{display:block;padding:9px 14px;font-size:12px;color:var(--dark);text-decoration:none;border:none;background:none;cursor:pointer;width:100%;text-align:left;}
.dropdown-item:hover{background:var(--gray-50);}
.dropdown-item.danger{color:var(--red);}
.dropdown-item.success{color:var(--green);}
.action-form{display:inline;}
.featured-badge{background:#fef9c3;color:#854d0e;font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;margin-left:4px;}
.bulk-bar{background:var(--orange-pale);border:1px solid var(--orange-muted);border-radius:8px;padding:10px 14px;margin-bottom:14px;display:none;align-items:center;gap:12px;}
.bulk-bar.visible{display:flex;}
.bulk-count{font-size:13px;font-weight:600;color:var(--dark);}
.table-wrap{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;}
.pagination-wrap{padding:14px 16px;border-top:1px solid var(--border);}
</style>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-num">{{ $stats['total'] }}</span>
        <span class="stat-label">Total Stores</span>
    </div>
    <div class="stat-card">
        <span class="stat-num" style="{{ $stats['pending'] > 0 ? 'color:var(--amber)' : '' }}">{{ $stats['pending'] }}</span>
        <span class="stat-label">Pending Review</span>
    </div>
    <div class="stat-card">
        <span class="stat-num" style="color:var(--green)">{{ $stats['active'] }}</span>
        <span class="stat-label">Active</span>
    </div>
    <div class="stat-card">
        <span class="stat-num" style="{{ $stats['suspended'] > 0 ? 'color:var(--red)' : '' }}">{{ $stats['suspended'] }}</span>
        <span class="stat-label">Suspended</span>
    </div>
</div>

{{-- Quick Highlights --}}
<div class="highlights-row">
    <div class="highlight-card">
        <span class="highlight-title">Most Visited Store</span>
        @if($stats['most_visited'])
            <span class="highlight-value">{{ $stats['most_visited']->name }}</span>
            <span class="highlight-sub">{{ number_format($stats['most_visited']->visit_count) }} visits</span>
            <a href="{{ route('admin.stores.show', $stats['most_visited']) }}" class="highlight-link">View →</a>
        @else
            <span class="highlight-sub">No data yet</span>
        @endif
    </div>
    <div class="highlight-card">
        <span class="highlight-title">Expiring Soon</span>
        <span class="highlight-value" style="{{ $stats['expiring_soon'] > 0 ? 'color:var(--amber)' : '' }}">{{ $stats['expiring_soon'] }}</span>
        <span class="highlight-sub">stores expire within 7 days</span>
        <a href="{{ route('admin.stores.analytics') }}" class="highlight-link">View →</a>
    </div>
    <div class="highlight-card">
        <span class="highlight-title">New This Week</span>
        <span class="highlight-value">{{ $stats['new_this_week'] }}</span>
        <span class="highlight-sub">stores approved this week</span>
        <a href="{{ route('admin.stores.index', ['tab' => 'approved']) }}" class="highlight-link">View →</a>
    </div>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.stores.index') }}" class="search-bar">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search stores by name or slug...">
</form>

{{-- Tabs --}}
<div class="table-wrap">
    <div class="tab-bar" style="padding:0 16px;">
        <a href="{{ route('admin.stores.index', ['tab' => 'pending']) }}" class="tab-btn {{ $tab === 'pending' ? 'active' : '' }}">
            @if($counts['pending'] > 0)<span class="tab-dot"></span>@endif
            Pending ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.stores.index', ['tab' => 'approved']) }}" class="tab-btn {{ $tab === 'approved' ? 'active' : '' }}">
            Approved ({{ $counts['approved'] }})
        </a>
        <a href="{{ route('admin.stores.index', ['tab' => 'rejected']) }}" class="tab-btn {{ $tab === 'rejected' ? 'active' : '' }}">
            Rejected ({{ $counts['rejected'] }})
        </a>
        <a href="{{ route('admin.stores.index', ['tab' => 'suspended']) }}" class="tab-btn {{ $tab === 'suspended' ? 'active' : '' }}">
            Suspended ({{ $counts['suspended'] }})
        </a>
        <a href="{{ route('admin.stores.index', ['tab' => 'all']) }}" class="tab-btn {{ $tab === 'all' ? 'active' : '' }}">
            All ({{ $counts['all'] }})
        </a>
    </div>

    {{-- Stores Table --}}
    @if($stores->isEmpty())
    <div style="padding:40px;text-align:center;color:var(--muted);">
        <div style="font-size:32px;margin-bottom:8px;">🏪</div>
        No stores found in this tab.
    </div>
    @else
    <div style="overflow-x:auto;">
        <table class="stores-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" style="cursor:pointer;"></th>
                    <th>Store</th>
                    <th>Owner</th>
                    <th>Plan</th>
                    <th>Products</th>
                    <th>Visits</th>
                    <th>Status</th>
                    <th>Subscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stores as $store)
                <tr>
                    <td><input type="checkbox" class="store-check" value="{{ $store->id }}" style="cursor:pointer;"></td>
                    <td>
                        <div class="store-cell">
                            <div class="store-avatar">
                                @if($store->logo_path)
                                    <img src="{{ asset($store->logo_path) }}" alt="">
                                @else
                                    {{ strtoupper(substr($store->name, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <div class="store-name">
                                    {{ $store->name }}
                                    @if($store->is_featured)
                                        <span class="featured-badge">⭐ Featured</span>
                                    @endif
                                </div>
                                <div class="store-slug">{{ $store->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($store->owner)
                            <div style="font-weight:500;">{{ $store->owner->name }}</div>
                            <div class="store-slug">{{ $store->owner->email }}</div>
                        @else
                            <span style="color:var(--muted);">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ 'plan-' . $store->plan_type }}">{{ ucfirst($store->plan_type) }}</span>
                    </td>
                    <td>
                        <div style="font-size:12px;">
                            <span style="color:var(--green);font-weight:600;">{{ $store->products_approved }}</span> approved
                            <br>
                            <span style="{{ $store->products_pending > 0 ? 'color:var(--amber);font-weight:600' : '' }}">{{ $store->products_pending }}</span> pending
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:4px;font-weight:600;">
                            👁 {{ number_format($store->visit_count) }}
                        </div>
                        @if($store->last_visited_at)
                            <div class="store-slug">{{ $store->last_visited_at->diffForHumans() }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $store->status }}">{{ ucfirst($store->status) }}</span>
                    </td>
                    <td>
                        @if($store->subscription_expires_at)
                            @php
                                $days = $store->days_until_expiry;
                                $cls = $store->is_expired ? 'expiry-expired' : ($days <= 7 ? 'expiry-warn' : 'expiry-ok');
                            @endphp
                            <div class="{{ $cls }}">{{ $store->subscription_expires_at->format('d M Y') }}</div>
                            <div class="store-slug">
                                @if($store->is_expired) Expired @else {{ $days }} days left @endif
                            </div>
                        @else
                            <span style="color:var(--muted);font-size:12px;">No subscription</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <a href="{{ route('admin.stores.show', $store) }}" class="btn-sm btn-view">View</a>
                            <a href="{{ route('admin.stores.edit', $store) }}" class="btn-sm btn-edit">Edit</a>
                            <div class="dropdown-wrap">
                                <button class="dropdown-btn" onclick="toggleDropdown(this)">Actions ▾</button>
                                <div class="dropdown-menu">
                                    @if($store->status === 'pending')
                                        <form method="POST" action="{{ route('admin.stores.approve', $store) }}" class="action-form">
                                            @csrf
                                            <button type="submit" class="dropdown-item success">✓ Approve</button>
                                        </form>
                                        <a href="{{ route('admin.stores.show', $store) }}#reject" class="dropdown-item danger">✕ Reject</a>
                                    @elseif($store->status === 'approved')
                                        <a href="{{ route('admin.stores.show', $store) }}#suspend" class="dropdown-item danger">⊘ Suspend</a>
                                        <form method="POST" action="{{ route('admin.stores.feature', $store) }}" class="action-form">
                                            @csrf
                                            <button type="submit" class="dropdown-item">⭐ Set Featured</button>
                                        </form>
                                    @elseif($store->status === 'suspended')
                                        <form method="POST" action="{{ route('admin.stores.reactivate', $store) }}" class="action-form">
                                            @csrf
                                            <button type="submit" class="dropdown-item success">↺ Reactivate</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.stores.show', $store) }}#subscription" class="dropdown-item">💳 Edit Subscription</a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $stores->links() }}
    </div>
    @endif
</div>

<script>
function toggleDropdown(btn) {
    var menu = btn.nextElementSibling;
    document.querySelectorAll('.dropdown-menu.open').forEach(function(m) {
        if (m !== menu) m.classList.remove('open');
    });
    menu.classList.toggle('open');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown-wrap')) {
        document.querySelectorAll('.dropdown-menu.open').forEach(function(m) { m.classList.remove('open'); });
    }
});
var selectAll = document.getElementById('selectAll');
if (selectAll) {
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.store-check').forEach(function(cb) { cb.checked = selectAll.checked; });
    });
}
</script>

</x-admin-layout>
