<x-store-layout title="Inventory" active="inventory">

<div class="time-filter-bar">
    <div class="time-filter-left">
        <h1 class="page-title">Inventory</h1>
        <p class="page-subtitle">Stock levels and product performance</p>
    </div>
    <div class="time-filter-right">
        <a href="{{ route('store.dashboard.print', ['inventory']) }}" target="_blank" class="btn-print">🖨️ Print</a>
    </div>
</div>

{{-- ── STAT CARDS ── --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr)">

    <div class="stat-card">
        <div class="sc-icon">📦</div>
        <div class="sc-label">Total Products</div>
        <div class="sc-value">{{ $totalProducts }}</div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">💰</div>
        <div class="sc-label">Inventory Value</div>
        <div class="sc-value green">${{ number_format($inventoryValue, 2) }}</div>
        <div class="sc-sub">Stock × Price</div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">⚠️</div>
        <div class="sc-label">Low Stock</div>
        <div class="sc-value amber">{{ $lowStock->count() }}</div>
        <div class="sc-sub">Less than 5 units</div>
    </div>

    <div class="stat-card">
        <div class="sc-icon">❌</div>
        <div class="sc-label">Out of Stock</div>
        <div class="sc-value red">{{ $outOfStock }}</div>
    </div>

</div>

{{-- ── LOW STOCK ALERT ── --}}
@if($lowStock->count() > 0)
<div class="alert-banner">
    <span>⚠️</span>
    <span>{{ $lowStock->count() }} product(s) are running low on stock — restock soon to avoid missed sales.</span>
</div>

<div class="card" style="margin-bottom:16px">
    <div class="section-title">Low Stock Products</div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Stock Left</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStock as $product)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        @if($product->images->first())
                        <img src="{{ asset('images/' . basename($product->images->first()->image_path)) }}"
                             style="width:32px;height:32px;border-radius:6px;object-fit:cover" alt="">
                        @endif
                        <span style="font-weight:600">{{ Str::limit($product->name, 30) }}</span>
                    </div>
                </td>
                <td><span class="badge badge-amber">{{ $product->stock }} left</span></td>
                <td>{{ config('shop.currency_symbol', '₪') }}{{ number_format($product->price, 2) }}</td>
                <td>
                    <a href="{{ route('store.editor.products.edit', $product->id) }}" class="act-btn green">Edit Stock</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- ── BEST SELLERS ── --}}
@if($bestSellers->count() > 0)
<div class="card" style="margin-bottom:16px">
    <div class="section-title">Best Sellers</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Units Sold</th>
                <th>Stock Left</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bestSellers as $i => $p)
            @php
                $stockColor = $p->stock > 10 ? 'var(--green)' : ($p->stock >= 5 ? 'var(--amber)' : 'var(--red)');
            @endphp
            <tr>
                <td style="color:var(--muted);font-weight:600">{{ $i + 1 }}</td>
                <td style="font-weight:600">{{ Str::limit($p->name, 30) }}</td>
                <td>{{ $p->units_sold }}</td>
                <td style="color:{{ $stockColor }};font-weight:600">{{ $p->stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- ── FULL INVENTORY TABLE ── --}}
<div class="card">
    <div class="section-title">All Products ({{ $products->total() }})</div>
    @if($products->isEmpty())
        <div class="admin-empty">No products in your store yet.</div>
    @else
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            @php
                $stockBadge = '';
                $stockColor = 'var(--dark)';
                if ($product->stock === 0) {
                    $stockBadge = '<span class="badge badge-red">Out of Stock</span>';
                } elseif ($product->stock < 5) {
                    $stockColor = 'var(--red)';
                    $stockBadge = ' <span class="badge badge-red" style="font-size:10px">Low</span>';
                } elseif ($product->stock <= 10) {
                    $stockColor = 'var(--amber)';
                }
            @endphp
            <tr>
                <td>
                    @if($product->images->first())
                    <img src="{{ asset('images/' . basename($product->images->first()->image_path)) }}"
                         style="width:36px;height:36px;border-radius:6px;object-fit:cover" alt="">
                    @else
                    <div style="width:36px;height:36px;border-radius:6px;background:var(--gray-100)"></div>
                    @endif
                </td>
                <td style="font-weight:600">{{ Str::limit($product->name, 30) }}</td>
                <td>{{ config('shop.currency_symbol', '₪') }}{{ number_format($product->price, 2) }}</td>
                <td>
                    @if($product->stock === 0)
                        {!! $stockBadge !!}
                    @else
                        <span style="color:{{ $stockColor }};font-weight:600">{{ $product->stock }}</span>{!! $stockBadge !!}
                    @endif
                </td>
                <td>
                    <span class="badge {{ $product->status === 'approved' ? 'badge-green' : ($product->status === 'pending' ? 'badge-amber' : 'badge-gray') }}">
                        {{ ucfirst($product->status ?? 'active') }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('store.editor.products.edit', $product->id) }}" class="act-btn">Edit</a>
                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="act-btn" style="margin-left:4px">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:16px">{{ $products->links() }}</div>
    @endif
</div>

</x-store-layout>
