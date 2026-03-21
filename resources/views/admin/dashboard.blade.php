<x-layout title="Admin Panel">
<style>
.admin-page { padding-top: 90px; padding-bottom: 4rem; }

/* ── Stats ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 1.25rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    transition: box-shadow 0.2s;
}

.stat-card:hover { box-shadow: var(--shadow-md); }

.stat-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--gray-400);
}

.stat-value {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 800;
    color: var(--gray-900);
    line-height: 1;
}

.stat-card.orange  { border-color: var(--orange-muted); }
.stat-card.orange .stat-value { color: var(--orange); }
.stat-card.red     { border-color: #fecaca; }
.stat-card.red .stat-value { color: #ef4444; }
.stat-card.green   { border-color: #bbf7d0; }
.stat-card.green .stat-value { color: #16a34a; }

/* ── Header ── */
.admin-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.admin-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--gray-900);
    letter-spacing: -0.03em;
}

.admin-title span { color: var(--orange); }

.admin-actions { display: flex; gap: 0.6rem; flex-wrap: wrap; }

.btn-admin {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: var(--orange);
    color: #fff;
    padding: 0.55rem 1.1rem;
    border-radius: 999px;
    text-decoration: none;
    font-size: 0.83rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
    font-family: 'DM Sans', sans-serif;
}

.btn-admin:hover { background: var(--orange-dark); }

.btn-admin-ghost {
    background: transparent;
    color: var(--gray-600);
    border: 1.5px solid var(--gray-300);
}

.btn-admin-ghost:hover {
    color: var(--orange);
    border-color: var(--orange);
    background: rgba(234,88,12,0.05);
}

/* ── Search / Filter Bar ── */
.filter-bar {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-bottom: 1.25rem;
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
}

.filter-bar input,
.filter-bar select {
    padding: 0.5rem 0.9rem;
    border: 1.5px solid var(--gray-200);
    border-radius: 999px;
    font-size: 0.85rem;
    font-family: 'DM Sans', sans-serif;
    color: var(--gray-900);
    outline: none;
    transition: border-color 0.15s;
}

.filter-bar input:focus,
.filter-bar select:focus { border-color: var(--orange); }

.filter-bar input { flex: 1; min-width: 180px; }

.btn-filter {
    background: var(--orange);
    color: #fff;
    border: none;
    padding: 0.5rem 1.2rem;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.15s;
}

.btn-filter:hover { background: var(--orange-dark); }

.btn-reset {
    background: transparent;
    color: var(--gray-500);
    border: 1.5px solid var(--gray-200);
    padding: 0.5rem 1rem;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    text-decoration: none;
    transition: color 0.15s, border-color 0.15s;
}

.btn-reset:hover { color: var(--orange); border-color: var(--orange); }

/* ── Result count ── */
.result-count {
    font-size: 0.82rem;
    color: var(--gray-400);
    margin-bottom: 0.75rem;
}

/* ── Product Rows ── */
.product-rows { display: flex; flex-direction: column; gap: 0.75rem; }

.product-row {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: box-shadow 0.2s, border-color 0.2s;
}

.product-row:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--orange-muted);
}

.product-row img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 0.5rem;
    flex-shrink: 0;
    background: var(--gray-100);
}

.product-row-info { flex: 1; min-width: 0; }

.product-row-name {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-900);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-row-meta {
    display: flex;
    gap: 1rem;
    margin-top: 0.3rem;
    font-size: 0.82rem;
    color: var(--gray-400);
    flex-wrap: wrap;
}

.stock-badge {
    font-weight: 700;
    font-size: 0.78rem;
    padding: 0.15rem 0.6rem;
    border-radius: 999px;
}

.stock-ok   { background: #dcfce7; color: #16a34a; }
.stock-low  { background: #fff7ed; color: var(--orange); }
.stock-out  { background: #fee2e2; color: #ef4444; }

.product-row-actions { display: flex; gap: 0.5rem; flex-shrink: 0; }

.btn-edit, .btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.4rem 0.9rem;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    border: 1.5px solid;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.15s, color 0.15s;
}

.btn-edit {
    color: var(--gray-600);
    border-color: var(--gray-200);
    background: transparent;
}

.btn-edit:hover { color: var(--orange); border-color: var(--orange); background: rgba(234,88,12,0.05); }

.btn-delete {
    color: #ef4444;
    border-color: #fecaca;
    background: transparent;
}

.btn-delete:hover { background: #fee2e2; }

/* ── Success / Error ── */
.alert-success {
    background: #dcfce7;
    color: #16a34a;
    padding: 0.85rem 1.25rem;
    border-radius: 0.5rem;
    margin-bottom: 1.25rem;
    font-weight: 600;
    font-size: 0.9rem;
}

.alert-error {
    background: #fee2e2;
    color: #ef4444;
    padding: 0.85rem 1.25rem;
    border-radius: 0.5rem;
    margin-bottom: 1.25rem;
    font-weight: 600;
    font-size: 0.9rem;
}

/* ── Empty ── */
.admin-empty {
    text-align: center;
    padding: 3rem;
    color: var(--gray-400);
    font-size: 0.95rem;
}

@media (max-width: 640px) {
    .product-row { flex-wrap: wrap; }
    .product-row-actions { width: 100%; justify-content: flex-end; }
}
</style>

<div class="admin-page">

    {{-- Header --}}
    <div class="admin-header">
        <h1 class="admin-title">Admin <span>Panel</span></h1>
        <div class="admin-actions">
            <a href="/" class="btn-admin btn-admin-ghost">← Home</a>
            <a href="/admin/categories" class="btn-admin btn-admin-ghost">🏷️ Categories</a>
            <a href="/admin/archive" class="btn-admin btn-admin-ghost">📦 Archive</a>
            <a href="/admin/products/create" class="btn-admin">+ Add Product</a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">✕ {{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card orange">
            <span class="stat-label">Total Products</span>
            <span class="stat-value">{{ $stats['total'] }}</span>
        </div>
        <div class="stat-card {{ $stats['low_stock'] > 0 ? 'red' : 'green' }}">
            <span class="stat-label">Low Stock</span>
            <span class="stat-value">{{ $stats['low_stock'] }}</span>
        </div>
        <div class="stat-card {{ $stats['out'] > 0 ? 'red' : 'green' }}">
            <span class="stat-label">Out of Stock</span>
            <span class="stat-value">{{ $stats['out'] }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-label">Archived</span>
            <span class="stat-value">{{ $stats['archived'] }}</span>
        </div>
        <div class="stat-card green">
            <span class="stat-label">Total Users</span>
            <span class="stat-value">{{ $stats['users'] }}</span>
        </div>
        <div class="stat-card orange">
            <span class="stat-label">Categories</span>
            <span class="stat-value">{{ $categories->count() }}</span>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="/admin" class="filter-bar">
        <input type="text" name="search" placeholder="Search products…"
               value="{{ request('search') }}">

        <select name="category">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <select name="stock">
            <option value="">All Stock</option>
            <option value="low"  {{ request('stock') === 'low'  ? 'selected' : '' }}>Low Stock (≤10)</option>
            <option value="out"  {{ request('stock') === 'out'  ? 'selected' : '' }}>Out of Stock</option>
        </select>

        <button type="submit" class="btn-filter">Filter</button>
        <a href="/admin" class="btn-reset">Reset</a>
    </form>

    {{-- Result count --}}
    <p class="result-count">
        Showing {{ $products->count() }} {{ Str::plural('product', $products->count()) }}
        @if(request()->hasAny(['search','category','stock']))
            — <a href="/admin" style="color:var(--orange);text-decoration:none;">clear filters</a>
        @endif
    </p>

    {{-- Products --}}
    @if($products->isEmpty())
        <div class="admin-empty">No products found.</div>
    @else
        <div class="product-rows">
            @foreach($products as $product)
            <div class="product-row">

                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}">

                <div class="product-row-info">
                    <p class="product-row-name">{{ $product->name }}</p>
                    <div class="product-row-meta">
                        <span>{{ $product->category->name }}</span>
                        <span style="font-weight:700;color:var(--gray-900);">${{ number_format($product->price) }}</span>
                        <span class="stock-badge {{ $product->stock === 0 ? 'stock-out' : ($product->stock <= 10 ? 'stock-low' : 'stock-ok') }}">
                            Stock: {{ $product->stock }}
                        </span>
                    </div>
                </div>

                <div class="product-row-actions">
                    <a href="/admin/products/{{ $product->id }}/edit" class="btn-edit">
                        ✏️ Edit
                    </a>
                    <form method="POST" action="/admin/products/{{ $product->id }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete"
                                onclick="return confirm('Move \"{{ $product->name }}\" to archive?')">
                            🗑️ Delete
                        </button>
                    </form>
                </div>

            </div>
            @endforeach
        </div>
    @endif

</div>
</x-layout>