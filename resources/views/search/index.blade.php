<x-layout title="Search — {{ $q ?: 'All Products' }}">
<style>
.search-page { padding-top: 100px; padding-bottom: 4rem; }

.search-heading {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3rem); font-weight: 800;
    color: var(--gray-900); letter-spacing: -0.03em; margin-bottom: 0.35rem;
}
.search-heading .accent { color: var(--orange); }
.search-sub { color: var(--gray-500); font-size: 0.95rem; margin-bottom: 2rem; }

.search-bar-wrap {
    background: #fff; border: 1.5px solid var(--gray-200); border-radius: var(--radius);
    padding: 1.25rem 1.5rem; margin-bottom: 1.75rem; box-shadow: var(--shadow-md);
}
.search-form { display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end; }
.search-input-wrap { flex: 1; min-width: 220px; position: relative; }
.search-input {
    width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem;
    border: 1.5px solid var(--gray-200); border-radius: 999px;
    font-size: 0.95rem; font-family: 'DM Sans', sans-serif;
    color: var(--gray-900); outline: none; transition: border-color 0.15s;
}
.search-input:focus { border-color: var(--orange); }
.search-icon {
    position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%);
    color: var(--gray-400); pointer-events: none;
}
.filter-select {
    padding: 0.65rem 0.9rem; border: 1.5px solid var(--gray-200); border-radius: 999px;
    font-size: 0.85rem; font-family: 'DM Sans', sans-serif; color: var(--gray-700);
    outline: none; transition: border-color 0.15s; background: #fff; min-width: 140px;
}
.filter-select:focus { border-color: var(--orange); }
.btn-search {
    background: var(--orange); color: #fff; border: none; padding: 0.7rem 1.75rem;
    border-radius: 999px; font-size: 0.9rem; font-weight: 700; cursor: pointer;
    font-family: 'DM Sans', sans-serif; transition: background 0.15s;
}
.btn-search:hover { background: var(--orange-dark); }

.results-meta {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.5rem;
}
.results-count { font-size: 0.88rem; color: var(--gray-500); }
.results-count strong { color: var(--gray-900); font-weight: 700; }

/* ── Product Grid ── */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.5rem;
}

.product-card {
    background: #fff; border: 1.5px solid var(--gray-200); border-radius: var(--radius);
    overflow: hidden; transition: box-shadow 0.2s, border-color 0.2s; text-decoration: none;
    display: flex; flex-direction: column;
}
.product-card:hover { box-shadow: var(--shadow-md); border-color: var(--orange-muted); }

.card-img {
    width: 100%; aspect-ratio: 1/1; object-fit: cover;
    background: var(--gray-100); display: block;
}
.card-img-placeholder {
    width: 100%; aspect-ratio: 1/1; background: var(--gray-100);
    display: flex; align-items: center; justify-content: center; color: var(--gray-300);
}
.card-body { padding: 1rem; flex: 1; display: flex; flex-direction: column; gap: 0.35rem; }
.card-category { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--orange); }
.card-name { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--gray-900); line-height: 1.3; }
.card-price { font-size: 1.05rem; font-weight: 800; color: var(--gray-900); margin-top: auto; }
.card-stock { font-size: 0.78rem; color: var(--gray-400); }
.card-stock.out { color: #ef4444; }

.no-results {
    text-align: center; padding: 5rem 2rem; color: var(--gray-400);
}
.no-results svg { margin: 0 auto 1rem; display: block; }
.no-results h3 { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 800; color: var(--gray-600); margin-bottom: 0.5rem; }
.no-results p { font-size: 0.9rem; }
</style>

<div class="search-page">

    <h1 class="search-heading">
        @if($q)
            Results for <span class="accent">"{{ $q }}"</span>
        @else
            <span class="accent">All</span> Products
        @endif
    </h1>
    <p class="search-sub">Discover our full range of premium tech products.</p>

    {{-- Search Bar --}}
    <div class="search-bar-wrap">
        <form method="GET" action="{{ route('products.search') }}" class="search-form">
            <div class="search-input-wrap">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" name="q" class="search-input"
                       placeholder="Search products…" value="{{ $q }}" autofocus>
            </div>
            <select name="category" class="filter-select">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <select name="sort" class="filter-select">
                <option value="relevance" {{ $sort === 'relevance' ? 'selected' : '' }}>Most Relevant</option>
                <option value="newest"    {{ $sort === 'newest'    ? 'selected' : '' }}>Newest</option>
                <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
                <option value="price_desc"{{ $sort === 'price_desc'? 'selected' : '' }}>Price: High → Low</option>
            </select>
            <button type="submit" class="btn-search">Search</button>
        </form>
    </div>

    {{-- Results Meta --}}
    <div class="results-meta">
        <p class="results-count">
            <strong>{{ $products->count() }}</strong>
            {{ Str::plural('product', $products->count()) }} found
            @if($q) for "{{ $q }}" @endif
        </p>
        @if($q || $categoryId)
            <a href="{{ route('products.search') }}"
               style="font-size:0.82rem;color:var(--orange);text-decoration:none;">Clear filters</a>
        @endif
    </div>

    {{-- Results --}}
    @if($products->isEmpty())
        <div class="no-results">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <h3>No products found</h3>
            <p>Try different keywords or browse all categories.</p>
            <a href="{{ route('products.search') }}"
               style="display:inline-block;margin-top:1rem;color:var(--orange);text-decoration:none;font-weight:700;">
                View all products →
            </a>
        </div>
    @else
        <div class="products-grid">
            @foreach($products as $product)
            <a href="{{ route('products.show', $product) }}" class="product-card">
                @if($product->image)
                    <img src="{{ asset('images/' . $product->image) }}"
                         alt="{{ $product->name }}" class="card-img">
                @else
                    <div class="card-img-placeholder">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <rect x="3" y="3" width="18" height="18" rx="3"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
                        </svg>
                    </div>
                @endif
                <div class="card-body">
                    @if($product->category)
                        <span class="card-category">{{ $product->category->name }}</span>
                    @endif
                    <p class="card-name">{{ $product->name }}</p>
                    <p class="card-price">₪{{ number_format($product->price, 2) }}</p>
                    <p class="card-stock {{ $product->stock < 1 ? 'out' : '' }}">
                        {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    @endif

</div>
</x-layout>
