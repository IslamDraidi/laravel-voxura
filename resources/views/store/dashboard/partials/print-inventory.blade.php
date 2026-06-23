<h1>Inventory Report</h1>

<div class="stat-grid">
    <div class="stat-box">
        <div class="stat-value">{{ $totalProducts }}</div>
        <div class="stat-label">Total Products</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">${{ number_format($inventoryValue, 2) }}</div>
        <div class="stat-label">Inventory Value</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">{{ $lowStock->count() }}</div>
        <div class="stat-label">Low Stock (&lt; 5 units)</div>
    </div>
    <div class="stat-box">
        <div class="stat-value">{{ $outOfStock }}</div>
        <div class="stat-label">Out of Stock</div>
    </div>
</div>

@if($lowStock->count() > 0)
<h2>Low Stock Products</h2>
<table>
    <thead>
        <tr><th>Product</th><th>Stock Left</th><th>Price</th></tr>
    </thead>
    <tbody>
        @foreach($lowStock as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td style="color:#dc2626;font-weight:600">{{ $product->stock }}</td>
            <td>₪{{ number_format($product->price, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<h2>Full Inventory</h2>
<table>
    <thead>
        <tr><th>Product</th><th>Price</th><th>Stock</th></tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>₪{{ number_format($product->price, 2) }}</td>
            <td>{{ $product->stock === 0 ? 'Out of Stock' : $product->stock }}</td>
        </tr>
        @empty
        <tr><td colspan="3" style="color:#aaa">No products</td></tr>
        @endforelse
    </tbody>
</table>
