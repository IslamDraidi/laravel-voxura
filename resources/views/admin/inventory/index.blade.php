<x-admin-layout title="Inventory" section="reporting" active="inventory">
<style>
.stock-badge { display:inline-flex; align-items:center; gap:0.3rem; padding:0.25rem 0.7rem; border-radius:999px; font-size:0.75rem; font-weight:700; }
.stock-badge.ok  { background:#dcfce7; color:#16a34a; }
.stock-badge.low { background:#fef9c3; color:#a16207; }
.stock-badge.out { background:#fee2e2; color:#dc2626; }
.stock-input { width:90px; padding:0.35rem 0.65rem; border:1.5px solid var(--border); border-radius:0.5rem; font-size:0.88rem; font-family:'DM Sans',sans-serif; color:var(--dark); outline:none; text-align:center; transition:border-color 0.15s; }
.stock-input:focus { border-color:var(--orange); }
</style>

{{-- Low Stock Alert --}}
@if($outCount > 0 || $lowCount > 0)
<div class="info-banner" style="border-color:#fde047;color:#a16207;background:#fef9c3;margin-bottom:1.5rem;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
    </svg>
    @if($outCount > 0)
        <span>{{ $outCount }} product{{ $outCount > 1 ? 's' : '' }} <strong>out of stock</strong></span>
    @endif
    @if($outCount > 0 && $lowCount > 0) <span style="opacity:0.5;">·</span> @endif
    @if($lowCount > 0)
        <span>{{ $lowCount }} product{{ $lowCount > 1 ? 's' : '' }} <strong>low on stock</strong> (≤10)</span>
    @endif
</div>
@endif

{{-- Stats --}}
<div class="stat-grid" style="margin-bottom:2rem;">
    <div class="stat-card">
        <span class="sc-label">Total Products</span>
        <span class="sc-value">{{ $products->count() }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">Low Stock</span>
        <span class="sc-value" style="color:#d97706;">{{ $lowCount }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">Out of Stock</span>
        <span class="sc-value red">{{ $outCount }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">Total Units</span>
        <span class="sc-value">{{ number_format($products->sum('stock')) }}</span>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="/admin/inventory">
    <div class="search-bar" style="margin-bottom:1.25rem;">
        <input type="text" name="search" placeholder="Search products…" value="{{ request('search') }}">
        <select name="category">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="stock">
            <option value="">All Stock Levels</option>
            <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Low Stock (≤10)</option>
            <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Out of Stock</option>
        </select>
        <button type="submit" class="add-btn">Filter</button>
        @if(request()->hasAny(['search','category','stock']))
            <a href="/admin/inventory" class="act-btn" style="text-decoration:none;">Clear</a>
        @endif
    </div>
</form>

{{-- Bulk stock form --}}
<form method="POST" action="/admin/inventory/stock" id="bulkForm">
    @csrf
    <div class="card" style="padding:0;overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            @if($p->image)
                                <img src="{{ asset('images/'.$p->image) }}" alt="{{ $p->name }}" style="width:40px;height:40px;border-radius:0.5rem;object-fit:cover;flex-shrink:0;">
                            @else
                                <div style="width:40px;height:40px;border-radius:0.5rem;background:#f3f4f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#d1d5db;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                </div>
                            @endif
                            <div>
                                <p style="font-weight:700;color:var(--dark);margin:0 0 0.1rem;">{{ $p->name }}</p>
                                <a href="/admin/products/{{ $p->id }}/edit" style="font-size:0.75rem;color:var(--orange);text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Edit product →</a>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--muted);">{{ $p->category?->name ?? '—' }}</td>
                    <td style="font-weight:700;">₪{{ number_format($p->price, 2) }}</td>
                    <td>
                        @if($p->stock === 0)
                            <span class="stock-badge out">Out of stock</span>
                        @elseif($p->stock <= 10)
                            <span class="stock-badge low">Low stock</span>
                        @else
                            <span class="stock-badge ok">In stock</span>
                        @endif
                    </td>
                    <td>
                        <input type="number" name="stock[{{ $p->id }}]" value="{{ $p->stock }}" min="0" class="stock-input">
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--muted);padding:2.5rem;">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->isNotEmpty())
    <div style="display:flex;justify-content:flex-end;margin-top:1rem;">
        <button type="submit" class="add-btn">Save Stock Changes</button>
    </div>
    @endif
</form>
</x-admin-layout>
