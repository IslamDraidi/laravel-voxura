<x-layout title="Compare Products">
<style>
.cmp-page { padding-top: 100px; padding-bottom: 5rem; }

.cmp-heading {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 800;
    color: var(--gray-900);
    letter-spacing: -0.03em;
    margin-bottom: 0.25rem;
}
.cmp-heading .accent { color: var(--orange); }
.cmp-sub { color: var(--gray-500); font-size: 0.95rem; margin-bottom: 2.5rem; }

/* ── Actions bar ── */
.cmp-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}
.btn-cmp-clear {
    background: none;
    border: 1.5px solid var(--gray-200);
    color: var(--gray-500);
    font-size: 0.82rem;
    font-weight: 600;
    padding: 0.45rem 1.1rem;
    border-radius: 999px;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: border-color 0.15s, color 0.15s;
}
.btn-cmp-clear:hover { border-color: #ef4444; color: #ef4444; }
.cmp-hint { font-size: 0.82rem; color: var(--gray-400); }

/* ── Table ── */
.cmp-scroll { overflow-x: auto; }

.cmp-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 560px;
}

.cmp-table th, .cmp-table td {
    padding: 1rem 1.25rem;
    text-align: left;
    border-bottom: 1.5px solid var(--gray-100);
    vertical-align: top;
}

/* Label column */
.cmp-table th:first-child,
.cmp-table td:first-child {
    width: 130px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--gray-400);
    white-space: nowrap;
    background: var(--gray-50);
    border-right: 1.5px solid var(--gray-100);
}

/* Product columns */
.cmp-table th {
    background: var(--white);
    padding-bottom: 1.25rem;
    border-bottom: 2px solid var(--gray-200);
}

/* Image row */
.cmp-img {
    width: 100%;
    max-width: 180px;
    aspect-ratio: 1/1;
    object-fit: cover;
    border-radius: 0.75rem;
    background: var(--gray-100);
}

.cmp-prod-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
    text-decoration: none;
    display: block;
    transition: color 0.15s;
}
.cmp-prod-name:hover { color: var(--orange); }

.cmp-remove-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--gray-400);
    font-size: 0.78rem;
    font-family: 'DM Sans', sans-serif;
    padding: 0;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    transition: color 0.15s;
}
.cmp-remove-btn:hover { color: #ef4444; }

/* Price */
.cmp-price {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--orange);
}

/* Stock badge */
.cmp-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
}
.cmp-badge.in  { background: #dcfce7; color: #15803d; }
.cmp-badge.out { background: #fee2e2; color: #b91c1c; }

/* Variants */
.cmp-variant-tag {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--gray-100);
    color: var(--gray-600);
    padding: 0.2rem 0.6rem;
    border-radius: 999px;
    margin: 0.15rem 0.15rem 0 0;
}

/* Description */
.cmp-desc {
    font-size: 0.84rem;
    color: var(--gray-500);
    line-height: 1.6;
    max-width: 220px;
}

/* Add to cart */
.btn-cmp-cart {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    background: var(--orange);
    color: #fff;
    border: none;
    border-radius: 999px;
    padding: 0.6rem 1.25rem;
    font-size: 0.85rem;
    font-weight: 700;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: background 0.15s, box-shadow 0.15s;
    text-decoration: none;
    white-space: nowrap;
    box-shadow: 0 2px 10px rgba(234,88,12,0.22);
}
.btn-cmp-cart:hover { background: var(--orange-dark); box-shadow: 0 4px 16px rgba(234,88,12,0.32); }
.btn-cmp-cart:disabled { background: var(--gray-300); box-shadow: none; cursor: not-allowed; }

/* ── Empty ── */
.cmp-empty {
    text-align: center;
    padding: 5rem 1rem;
}
.cmp-empty-icon {
    width: 80px; height: 80px;
    background: var(--orange-light);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--orange);
}
.cmp-empty-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem; font-weight: 800;
    color: var(--gray-900); margin-bottom: 0.5rem;
}
.cmp-empty-sub { color: var(--gray-500); font-size: 0.95rem; margin-bottom: 2rem; }
</style>

<div class="cmp-page">

    <h1 class="cmp-heading">Com<span class="accent">pare</span></h1>
    <p class="cmp-sub">Compare products side by side to find the perfect fit.</p>

    @if($products->isEmpty())
        <div class="cmp-empty">
            <div class="cmp-empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="cmp-empty-title">Nothing to compare yet</p>
            <p class="cmp-empty-sub">Add products using the "Compare" button on any product card.</p>
            <a href="/#products" class="btn btn-primary" style="font-size:0.95rem;padding:0.7rem 2rem;">
                Browse Products
            </a>
        </div>
    @else
        <div class="cmp-actions">
            <span class="cmp-hint">{{ $products->count() }} of 4 products selected</span>
            <form method="POST" action="/compare">
                @csrf @method('DELETE')
                <button type="submit" class="btn-cmp-clear"
                        onclick="return confirm('Clear compare list?')">
                    Clear all
                </button>
            </form>
            <a href="/#products" class="btn btn-primary" style="font-size:0.82rem;padding:0.45rem 1.15rem;">
                + Add more
            </a>
        </div>

        <div class="cmp-scroll">
            <table class="cmp-table">

                {{-- ── Image / Header row ── --}}
                <thead>
                    <tr>
                        <th></th>
                        @foreach($products as $product)
                            <th>
                                <img src="{{ asset('images/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="cmp-img">
                                <a href="/product/{{ $product->id }}" class="cmp-prod-name" style="margin-top:0.75rem;">
                                    {{ $product->name }}
                                </a>
                                <form method="POST" action="/compare/{{ $product->id }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="cmp-remove-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Remove
                                    </button>
                                </form>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    {{-- Price ── --}}
                    <tr>
                        <td>Price</td>
                        @foreach($products as $product)
                            <td><span class="cmp-price">${{ number_format($product->price, 2) }}</span></td>
                        @endforeach
                    </tr>

                    {{-- Category ── --}}
                    <tr>
                        <td>Category</td>
                        @foreach($products as $product)
                            <td style="font-size:0.88rem;color:var(--orange);font-weight:600;">
                                {{ $product->category->name }}
                            </td>
                        @endforeach
                    </tr>

                    {{-- Stock ── --}}
                    <tr>
                        <td>Availability</td>
                        @foreach($products as $product)
                            <td>
                                @if($product->stock > 0)
                                    <span class="cmp-badge in">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                                            <circle cx="12" cy="12" r="6"/>
                                        </svg>
                                        In Stock
                                    </span>
                                @else
                                    <span class="cmp-badge out">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                                            <circle cx="12" cy="12" r="6"/>
                                        </svg>
                                        Out of Stock
                                    </span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    {{-- Stock quantity ── --}}
                    <tr>
                        <td>Stock</td>
                        @foreach($products as $product)
                            <td style="font-size:0.88rem;color:var(--gray-600);">
                                {{ $product->stock }} units
                            </td>
                        @endforeach
                    </tr>

                    {{-- Variants ── --}}
                    <tr>
                        <td>Variants</td>
                        @foreach($products as $product)
                            <td>
                                @if($product->variants->isEmpty())
                                    <span style="font-size:0.82rem;color:var(--gray-400);">None</span>
                                @else
                                    @php $grouped = $product->variants->groupBy('type'); @endphp
                                    @foreach($grouped as $type => $variants)
                                        <div style="margin-bottom:0.4rem;">
                                            <span style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--gray-400);display:block;margin-bottom:0.2rem;">{{ $type }}</span>
                                            @foreach($variants as $v)
                                                <span class="cmp-variant-tag">{{ $v->value }}</span>
                                            @endforeach
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    {{-- Description ── --}}
                    <tr>
                        <td>Description</td>
                        @foreach($products as $product)
                            <td>
                                <p class="cmp-desc">{{ Str::limit($product->description, 120) }}</p>
                            </td>
                        @endforeach
                    </tr>

                    {{-- Add to cart ── --}}
                    <tr>
                        <td>Action</td>
                        @foreach($products as $product)
                            <td>
                                @if($product->stock > 0)
                                    <form method="POST" action="/cart/add">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn-cmp-cart">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <button class="btn-cmp-cart" disabled>Out of Stock</button>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

</div>
</x-layout>
