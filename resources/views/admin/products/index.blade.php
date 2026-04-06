<x-admin-layout title="Products" section="catalog" active="products">

{{-- Page header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
    <h2 style="font-size:16px;font-weight:700;color:var(--dark);margin:0;">All Products</h2>
    <a href="/admin/products/create" class="add-btn">+ Add Product</a>
</div>

{{-- Filters --}}
<form method="GET" action="/admin/products">
    <div class="search-bar" style="margin-bottom:1.25rem;">
        <input type="text" name="search" placeholder="Search products…" value="{{ request('search') }}">
        <select name="category">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>In Stock</option>
            <option value="out"    {{ request('status') === 'out'    ? 'selected' : '' }}>Out of Stock</option>
        </select>
        <button type="submit" class="add-btn">Filter</button>
        @if(request()->hasAny(['search','category','status']))
            <a href="/admin/products" class="act-btn" style="text-decoration:none;">Clear</a>
        @endif
    </div>
</form>

{{-- Stats --}}
<div class="stat-grid" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <span class="sc-label">Total Products</span>
        <span class="sc-value">{{ $products->count() }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">In Stock</span>
        <span class="sc-value green">{{ $products->where('stock', '>', 0)->count() }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">Out of Stock</span>
        <span class="sc-value red">{{ $products->where('stock', 0)->count() }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">Categories</span>
        <span class="sc-value">{{ $categories->count() }}</span>
    </div>
</div>

{{-- Table --}}
<div class="card" style="padding:0;overflow-x:auto;">
    @if($products->isEmpty())
        <div class="admin-empty">No products found.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            @if($product->image)
                                <img src="{{ asset('images/'.$product->image) }}"
                                     alt="{{ $product->name }}"
                                     style="width:44px;height:44px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                            @else
                                <div style="width:44px;height:44px;border-radius:8px;background:var(--gray-100);flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:18px;">📦</div>
                            @endif
                            <div>
                                <div style="font-weight:600;color:var(--dark);font-size:13px;">{{ $product->name }}</div>
                                @if($product->description)
                                    <div style="font-size:11px;color:var(--muted);margin-top:1px;max-width:220px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">{{ $product->description }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($product->category)
                            <span class="badge badge-orange">{{ $product->category->name }}</span>
                        @else
                            <span style="color:var(--muted);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td style="font-weight:600;color:var(--dark);">
                        {{ number_format($product->price, 2) }} SAR
                    </td>
                    <td>
                        @if($product->stock > 10)
                            <span class="badge badge-green">{{ $product->stock }}</span>
                        @elseif($product->stock > 0)
                            <span class="badge badge-amber">{{ $product->stock }} low</span>
                        @else
                            <span class="badge badge-red">Out of stock</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="/product/{{ $product->slug }}" target="_blank" class="act-btn" style="text-decoration:none;">View</a>
                            <a href="/admin/products/{{ $product->id }}/edit" class="act-btn" style="text-decoration:none;">Edit</a>
                            <form method="POST" action="/admin/products/{{ $product->id }}" style="display:inline;"
                                  onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

</x-admin-layout>
