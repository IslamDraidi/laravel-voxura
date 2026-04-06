@props(['products', 'activeCategory' => null])

<section id="products" class="product-grid-section">
    <div class="pg-container">

        <div class="pg-header">
            @if($activeCategory)
                <h2 class="pg-title"><span>{{ $activeCategory->name }}</span></h2>
                <p class="pg-subtitle">
                    {{ $products->count() }} product{{ $products->count() !== 1 ? 's' : '' }} in this category
                    &nbsp;·&nbsp;
                    <a href="/#products" style="color:#ea580c;text-decoration:none;font-weight:600;">View all ×</a>
                </p>
            @else
                <h2 class="pg-title">Our <span>Collection</span></h2>
                <p class="pg-subtitle">Discover premium pieces designed to elevate every moment</p>
            @endif
        </div>

        @if($products->isEmpty())
            <div style="text-align:center;padding:4rem 1rem;color:#6b7280;">
                <p style="font-size:1.1rem;">No products found in this category.</p>
                <a href="/#products" style="display:inline-block;margin-top:1rem;color:#ea580c;font-weight:600;text-decoration:none;">← View all products</a>
            </div>
        @else
            <div class="pg-grid">
                @foreach($products as $index => $product)
                    @include('components.product-card', [
                        'product' => $product,
                        'index'   => $index
                    ])
                @endforeach
            </div>
        @endif

    </div>
</section>
