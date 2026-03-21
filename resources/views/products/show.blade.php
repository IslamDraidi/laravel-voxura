<x-layout :title="$product->name">

<style>
.pd-page { max-width: 1100px; margin: 0 auto; padding: 2.5rem 1.5rem 5rem; animation: pdFadeIn 0.45s ease both; }
@keyframes pdFadeIn { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
.pd-back { display: inline-flex; align-items: center; gap: 0.4rem; color: var(--gray-500); text-decoration: none; font-size: 0.875rem; font-weight: 600; margin-bottom: 2.5rem; transition: color 0.18s; }
.pd-back svg { width: 16px; height: 16px; transition: transform 0.18s; }
.pd-back:hover { color: var(--orange); }
.pd-back:hover svg { transform: translateX(-3px); }
.pd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4.5rem; align-items: start; }
.pd-image-wrap { position: relative; border-radius: 1.25rem; overflow: hidden; background: var(--gray-100); aspect-ratio: 1/1; box-shadow: var(--shadow-card); }
.pd-image-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s ease; }
.pd-image-wrap:hover img { transform: scale(1.03); }
.pd-image-placeholder { width: 100%; height: 100%; min-height: 380px; display: flex; align-items: center; justify-content: center; color: var(--gray-300); }
.pd-wishlist { position: absolute; top: 14px; right: 14px; width: 42px; height: 42px; background: var(--white); border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 12px rgba(0,0,0,0.13); color: var(--gray-400); transition: color 0.2s, transform 0.2s; z-index: 2; }
.pd-wishlist:hover { color: #ef4444; transform: scale(1.12); }
.pd-wishlist svg { width: 18px; height: 18px; }
.pd-info { display: flex; flex-direction: column; }
.pd-category { font-size: 0.7rem; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: var(--orange); margin-bottom: 0.6rem; }
.pd-name { font-family: "Playfair Display", serif; font-size: 2.5rem; font-weight: 800; color: var(--gray-900); line-height: 1.15; margin-bottom: 0.8rem; }
.pd-price { font-size: 2.1rem; font-weight: 800; color: var(--gray-900); margin-bottom: 1.1rem; }
.pd-stock { display: inline-flex; align-items: center; gap: 0.45rem; font-size: 0.84rem; font-weight: 600; margin-bottom: 1.75rem; }
.pd-stock svg { width: 16px; height: 16px; }
.pd-stock.in { color: #16a34a; }
.pd-stock.out { color: #dc2626; }
.pd-divider { border: none; border-top: 1px solid var(--gray-200); margin: 0 0 1.5rem; }
.pd-desc-label { font-size: 1rem; font-weight: 700; color: var(--gray-900); margin-bottom: 0.55rem; }
.pd-desc-text { font-size: 0.92rem; color: var(--gray-500); line-height: 1.8; margin-bottom: 1.75rem; }
.pd-cart-btn { width: 100%; padding: 1rem 1.5rem; background: var(--orange); color: var(--white); border: none; border-radius: 999px; font-family: "DM Sans", sans-serif; font-size: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.6rem; box-shadow: 0 4px 20px rgba(234,88,12,0.28); transition: background 0.18s, box-shadow 0.18s, transform 0.1s; }
.pd-cart-btn:hover { background: var(--orange-dark); box-shadow: 0 6px 28px rgba(234,88,12,0.38); }
.pd-cart-btn:active { transform: scale(0.98); }
.pd-cart-btn:disabled { background: var(--gray-300); box-shadow: none; cursor: not-allowed; }
.pd-cart-btn svg { width: 18px; height: 18px; }
@media (max-width: 768px) { .pd-grid { grid-template-columns: 1fr; gap: 2rem; } .pd-name { font-size: 1.9rem; } .pd-price { font-size: 1.7rem; } }
</style>

<div class="pd-page">
    <a href="{{ url()->previous() }}" class="pd-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Back
    </a>
    <div class="pd-grid">
        <div class="pd-image-wrap">
            @if($product->image)
                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}">
            @else
                <div class="pd-image-placeholder">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                </div>
            @endif
            <button class="pd-wishlist" title="Add to Wishlist">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </button>
        </div>
        <div class="pd-info">
            @if($product->category)
                <span class="pd-category">{{ $product->category->name }}</span>
            @endif
            <h1 class="pd-name">{{ $product->name }}</h1>
            <p class="pd-price">${{ number_format($product->price, 2) }}</p>
            <p class="pd-stock {{ $product->stock > 0 ? 'in' : 'out' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}
            </p>
            <hr class="pd-divider">
            <h3 class="pd-desc-label">Description</h3>
            <p class="pd-desc-text">{{ $product->description ?? 'No description available.' }}</p>
            <hr class="pd-divider">
            <form method="POST" action="/cart/add">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="pd-cart-btn" {{ $product->stock < 1 ? 'disabled' : '' }}>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                </button>
            </form>
        </div>
    </div>
</div>

</x-layout>