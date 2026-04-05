<x-layout title="Your Wishlist">

<style>
/* ── Wishlist Page ── */
.wishlist-page {
    padding-top: 100px;
    padding-bottom: 4rem;
}

.wishlist-heading {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 800;
    color: var(--gray-900);
    letter-spacing: -0.03em;
    margin-bottom: 0.25rem;
}

.wishlist-heading .accent { color: var(--orange); }

.wishlist-subheading {
    color: var(--gray-500);
    font-size: 0.95rem;
    margin-bottom: 2.5rem;
}

/* ── Grid ── */
.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.5rem;
}

/* ── Wish Card ── */
.wish-card {
    background: var(--white);
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    overflow: hidden;
    transition: box-shadow 0.25s, border-color 0.25s, transform 0.25s;
}

.wish-card:hover {
    box-shadow: var(--shadow-card);
    border-color: var(--orange-muted);
    transform: translateY(-4px);
}

.wish-card-img-wrap {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    background: var(--gray-100);
}

.wish-card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.wish-card:hover .wish-card-img-wrap img {
    transform: scale(1.06);
}

/* Unlike button */
.wish-unlike-btn {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.92);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ef4444;
    transition: background 0.15s, transform 0.15s;
    backdrop-filter: blur(4px);
}

.wish-unlike-btn:hover {
    background: #ef4444;
    color: #fff;
    transform: scale(1.1);
}

.wish-unlike-btn svg { width: 17px; height: 17px; }

/* Add to cart hover */
.wish-add-hover {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(4px);
    display: flex;
    justify-content: center;
    padding: 0.85rem;
    transform: translateY(100%);
    transition: transform 0.25s ease;
}

.wish-card:hover .wish-add-hover {
    transform: translateY(0);
}

.btn-wish-cart {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--orange);
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 0.55rem 1.3rem;
    border-radius: 999px;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
    width: 100%;
    justify-content: center;
}

.btn-wish-cart:hover { background: var(--orange-dark); }

/* Info */
.wish-card-info {
    padding: 1.1rem;
}

.wish-card-category {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--orange);
    margin-bottom: 0.3rem;
}

.wish-card-name {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.wish-card-name a {
    color: inherit;
    text-decoration: none;
    transition: color 0.15s;
}

.wish-card-name a:hover { color: var(--orange); }

.wish-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.wish-card-price {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--gray-900);
    font-family: 'Playfair Display', serif;
}

.wish-card-learn {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--gray-400);
    text-decoration: none;
    transition: color 0.15s;
}

.wish-card-learn:hover { color: var(--orange); }

/* ── Empty State ── */
.wishlist-empty {
    text-align: center;
    padding: 5rem 1rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--orange-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--orange);
}

.empty-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.empty-sub {
    color: var(--gray-500);
    margin-bottom: 2rem;
    font-size: 0.95rem;
}
</style>

<div class="wishlist-page">

    <h1 class="wishlist-heading">Your <span class="accent">Wishlist</span></h1>

    @if($likedProducts->isEmpty())
        {{-- ── Empty State ── --}}
        <div class="wishlist-empty">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                </svg>
            </div>
            <p class="empty-title">Your wishlist is empty</p>
            <p class="empty-sub">Hit the ♡ on any product to save it here.</p>
            <a href="/#products" class="btn btn-primary" style="font-size:0.95rem;padding:0.7rem 2rem;">
                Browse Products
            </a>
        </div>

    @else
        <p class="wishlist-subheading">
            {{ $likedProducts->count() }} {{ Str::plural('item', $likedProducts->count()) }} saved
        </p>

        <div class="wishlist-grid">
            @foreach($likedProducts as $product)
                <div class="wish-card">

                    <div class="wish-card-img-wrap">

                        {{-- Product image --}}
                        <a href="/product/{{ $product->id }}">
                            <img src="{{ asset('images/' . $product->image) }}"
                                 alt="{{ $product->name }}">
                        </a>

                        {{-- Remove from wishlist --}}
                        <form method="POST" action="/likes/{{ $product->id }}/toggle">
                            @csrf
                            <button class="wish-unlike-btn" type="submit" title="Remove from wishlist">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                     fill="currentColor">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </form>

                        {{-- Add to cart hover --}}
                        <div class="wish-add-hover">
                            <form method="POST" action="/cart/add" style="width:100%">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn-wish-cart">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="8" cy="21" r="1"/>
                                        <circle cx="19" cy="21" r="1"/>
                                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                    </svg>
                                    Add to Cart
                                </button>
                            </form>
                        </div>

                    </div>

                    <div class="wish-card-info">
                        <p class="wish-card-category">{{ $product->category->name }}</p>
                        <h3 class="wish-card-name">
                            <a href="/product/{{ $product->id }}">{{ $product->name }}</a>
                        </h3>
                        <div class="wish-card-footer">
                            <span class="wish-card-price">₪{{ number_format($product->price) }}</span>
                            <a href="/product/{{ $product->id }}" class="wish-card-learn">View →</a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>

</x-layout>