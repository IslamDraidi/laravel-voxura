<section id="products" class="product-grid-section">
    <div class="pg-container">

        {{-- العنوان --}}
        <div class="pg-header">
            <h2 class="pg-title">
                Our <span>Collection</span>
            </h2>
            <p class="pg-subtitle">
                Discover premium tech designed to elevate every moment
            </p>
        </div>

        {{-- الكاردات --}}
        <div class="pg-grid">
            @foreach($products as $index => $product)
                @include('components.product-card', [
                    'product' => $product,
                    'index'   => $index
                ])
            @endforeach
        </div>

    </div>
</section>