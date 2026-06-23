<div class="product-card" onclick="window.location='{{ route('products.show', $product) }}'" style="cursor:pointer;display:flex;flex-direction:column;height:100%;">
    <div class="product-card-img-wrap">
        @php
            $cardImg = $product->images->first()?->image_path
                ?? ($product->image ? 'images/' . $product->image : null);
        @endphp
        @if($cardImg)
            <img src="{{ asset($cardImg) }}"
                 alt="{{ $product->name }}"
                 class="product-card-img">
        @else
            <div class="product-card-img-placeholder">
                <i class="ti ti-photo" aria-hidden="true"></i>
            </div>
        @endif

        @if($product->is3DReady())
            <span class="product-card-3d-badge">
                <i class="ti ti-cube-3d-sphere" aria-hidden="true"></i>
                3D
            </span>
        @endif

        @if($product->created_at->diffInDays() <= 7)
            <span class="product-card-new-badge">{{ __('general.new_badge') }}</span>
        @endif

        <button class="product-card-wishlist" aria-label="{{ __('general.add_to_wishlist') }}">
            <i class="ti ti-heart" aria-hidden="true"></i>
        </button>
    </div>

    <div class="product-card-body" style="flex:1;display:flex;flex-direction:column;">
        @if($product->category)
            <span class="product-card-category">{{ $product->category->name ?? $product->category }}</span>
        @endif

        <h3 class="product-card-name">{{ $product->name }}</h3>

        @if($product->store)
            <a href="{{ route('stores.show', $product->store) }}" class="product-card-store">
                <i class="ti ti-building-store" aria-hidden="true"></i>
                {{ __('general.sold_by', ['store' => $product->store->name]) }}
            </a>
        @endif

        <div class="product-card-footer" style="margin-top:auto;">
            <span class="product-card-price">
                {{ config('shop.currency_symbol', '₪') }}
                {{ number_format($product->price, 2) }}
            </span>
            <a href="{{ route('products.show', $product) }}" class="product-card-view">
                {{ __('general.view') }} →
            </a>
        </div>
    </div>
</div>
