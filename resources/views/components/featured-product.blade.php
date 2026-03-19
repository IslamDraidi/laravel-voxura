<div class="featured-product {{ $index % 2 === 0 ? 'even' : 'odd' }}"
     data-index="{{ $index }}">

    {{-- Image --}}
    <div class="fp-image">
        <div class="fp-image-wrap">
            <img src="{{ asset('images/' . $product->image) }}"
                 alt="{{ $product->name }}" />
        </div>
    </div>

    {{-- Content --}}
    <div class="fp-content">

        <p class="fp-category">{{ $product->category->name }}</p>
        <h2 class="fp-name">{{ $product->name }}</h2>
        <p class="fp-description">{{ $product->description }}</p>

        <div class="fp-actions">
            <span class="fp-price">${{ number_format($product->price) }}</span>

            @if($product->stock > 0)
                <form method="POST" action="/cart/add">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn-add-cart">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
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
                <div class="btn-add-cart"
                     style="opacity:0.5;cursor:not-allowed;background:#6b7280;">
                    Out of Stock
                </div>
            @endif

            @if($product->stock > 0 && $product->stock <= 5)
                <span style="font-size:0.78rem;color:#ea580c;font-weight:600;margin-left:0.5rem;">
                    Only {{ $product->stock }} left!
                </span>
            @endif
        </div>

    </div>

</div>