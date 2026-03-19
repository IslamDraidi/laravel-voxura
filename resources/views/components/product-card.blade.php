<div class="product-card" data-index="{{ $index }}">
    <div class="pc-card">

        {{-- Image --}}
        <div class="pc-image-wrap">

            <a href="/product/{{ $product->id }}">
                <img src="{{ asset('images/' . $product->image) }}"
                     alt="{{ $product->name }}" />
            </a>

            {{-- Overlay --}}
            <div class="pc-overlay"></div>

            {{-- Out of Stock Badge --}}
            @if($product->stock <= 0)
                <div class="pc-out-badge">Out of Stock</div>
            @elseif($product->stock <= 5)
                <div class="pc-low-badge">Only {{ $product->stock }} left</div>
            @endif

            {{-- Like button --}}
            <form method="POST" action="/likes/{{ $product->id }}/toggle"
                  class="pc-like-form">
                @csrf
                <button type="submit"
                        class="pc-like-btn {{ in_array($product->id, $likedIds ?? []) ? 'liked' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </button>
            </form>

            {{-- Add to Cart hover --}}
            <div class="pc-cart-hover">
                @if($product->stock > 0)
                    <form method="POST" action="/cart/add">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn-pc-cart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="21" r="1"/>
                                <circle cx="19" cy="21" r="1"/>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                            </svg>
                            Add to Cart
                        </button>
                    </form>
                @else
                    <div class="btn-pc-cart btn-pc-cart-disabled">
                        Out of Stock
                    </div>
                @endif
            </div>

        </div>

        {{-- Info --}}
        <div class="pc-info">
            <p class="pc-category">{{ $product->category->name }}</p>
            <h3 class="pc-name">
                <a href="/product/{{ $product->id }}">{{ $product->name }}</a>
            </h3>
            <p class="pc-description">{{ $product->description }}</p>
            <div class="pc-footer">
                <span class="pc-price">${{ number_format($product->price) }}</span>
                <a href="/product/{{ $product->id }}" class="pc-learn">
                    Learn more →
                </a>
            </div>
        </div>

    </div>
</div>

<style>
.pc-out-badge {
    position: absolute;
    top: 0.75rem; left: 0.75rem;
    background: rgba(0,0,0,0.75);
    color: #fff;
    font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.07em;
    padding: 0.2rem 0.65rem; border-radius: 999px;
    backdrop-filter: blur(4px);
}

.pc-low-badge {
    position: absolute;
    top: 0.75rem; left: 0.75rem;
    background: rgba(234,88,12,0.9);
    color: #fff;
    font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.07em;
    padding: 0.2rem 0.65rem; border-radius: 999px;
    backdrop-filter: blur(4px);
}

.btn-pc-cart-disabled {
    display: inline-flex; align-items: center; justify-content: center;
    gap: 0.4rem; padding: 0.5rem 1.1rem; border-radius: 999px;
    background: rgba(100,100,100,0.7); color: #fff;
    font-size: 0.82rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: not-allowed; opacity: 0.8;
}
</style>