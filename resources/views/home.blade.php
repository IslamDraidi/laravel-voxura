<x-layout mainClass="full-width" :navTransparent="true">

<style>
/* ── Home sections ── */
.home-section {
    padding: 48px 0;
}
.home-section-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
}
.home-section-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 4px;
}
.home-section-sub {
    font-size: 14px;
    color: #888;
    margin: 0;
}
.home-section-link {
    font-size: 14px;
    font-weight: 600;
    color: var(--orange);
    text-decoration: none;
    white-space: nowrap;
    margin-top: 4px;
}
.home-section-link:hover { opacity: 0.8; }

/* ── Product grid ── */
.product-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
@media (max-width: 1024px) { .product-grid-4 { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px)  { .product-grid-4 { grid-template-columns: 1fr; } }

/* ── Product card ── */
.product-card {
    background: #ffffff;
    border-radius: 14px;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.product-card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.product-card-footer {
    margin-top: auto;
}
.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.product-card-img-wrap {
    position: relative;
    height: 260px;
    overflow: hidden;
    background: #f5f4f2;
}
.product-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}
.product-card:hover .product-card-img { transform: scale(1.04); }
.product-card-img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ddd;
    font-size: 40px;
}
.product-card-3d-badge {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: var(--orange);
    color: #fff;
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 4px;
}
.product-card-new-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #1D9E75;
    color: #fff;
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 11px;
    font-weight: 700;
}
.product-card-wishlist {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #fff;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: #888;
    transition: all 0.15s;
}
.product-card-wishlist:hover { color: var(--orange); }
.product-card-body { padding: 14px; }
.product-card-category {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--orange);
    display: block;
    margin-bottom: 4px;
}
.product-card-name {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.product-card-store {
    font-size: 12px;
    color: #888;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 10px;
    transition: color 0.15s;
}
.product-card-store:hover { color: var(--orange); }
.product-card-store i { font-size: 13px; }
.product-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.product-card-price {
    font-size: 16px;
    font-weight: 800;
    color: #1a1a1a;
}
.product-card-view {
    font-size: 13px;
    font-weight: 600;
    color: var(--orange);
    text-decoration: none;
}

/* ── Category pills ── */
.category-pills-row {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 8px;
}
.category-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    border: 1.5px solid #e0ddd9;
    border-radius: 50px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 600;
    color: #3a3a3a;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.15s;
}
.category-pill:hover {
    border-color: var(--orange);
    color: var(--orange);
}
.category-pill i { font-size: 18px; }

/* ── Featured stores ── */
.featured-stores-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
@media (max-width: 900px) { .featured-stores-grid { grid-template-columns: 1fr; } }
.featured-store-card {
    background: #fff;
    border: 1px solid #f0ede8;
    border-radius: 16px;
    overflow: visible;
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
    position: relative;
}
.featured-store-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.featured-store-banner {
    position: relative;
    height: 140px;
    overflow: visible;
}
/* inner wrapper clips the image/placeholder to the banner */
.featured-store-banner-inner {
    position: absolute;
    inset: 0;
    background: #e8e4e0;
    overflow: hidden;
    border-radius: 16px 16px 0 0;
}
.featured-store-banner-inner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.featured-store-banner-ph {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #e8e0d8, #d4ccc4);
    display: flex;
    align-items: center;
    justify-content: center;
}
.featured-store-logo {
    position: absolute;
    bottom: -24px;
    left: 16px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 3px solid #fff;
    background: #1a1a1a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    color: #fff;
    overflow: hidden;
    z-index: 2;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.featured-store-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.featured-store-body { padding: 32px 16px 18px; }
.featured-store-name {
    font-size: 16px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 4px;
}
.featured-store-tagline {
    font-size: 12px;
    color: #888;
    margin-bottom: 12px;
}
.featured-store-thumbs {
    display: flex;
    gap: 6px;
    margin-bottom: 14px;
}
.featured-store-thumb {
    width: 60px;
    height: 70px;
    border-radius: 8px;
    object-fit: cover;
    background: #f5f4f2;
}
.featured-store-btn {
    display: block;
    width: 100%;
    background: var(--orange);
    color: #fff;
    border-radius: 8px;
    padding: 10px;
    font-size: 13px;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    transition: opacity 0.15s;
}
.featured-store-btn:hover { opacity: 0.9; }

/* ── Join Voxura CTA ── */
.join-voxura-banner {
    background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
    border-radius: 20px;
    margin: 40px 0;
    overflow: hidden;
    position: relative;
    min-height: 200px;
    display: flex;
    align-items: center;
}
.join-voxura-content {
    padding: 48px 56px;
    position: relative;
    z-index: 2;
}
.join-voxura-text h2 {
    font-size: 28px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
}
.join-voxura-text p {
    font-size: 15px;
    color: rgba(255,255,255,0.55);
    line-height: 1.7;
    max-width: 480px;
    margin-bottom: 24px;
}
.join-voxura-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--orange);
    color: #fff;
    border-radius: 10px;
    padding: 13px 28px;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    transition: opacity 0.15s;
}
.join-voxura-btn:hover { opacity: 0.9; }
.no-products {
    color: #888;
    font-size: 14px;
    grid-column: 1/-1;
    padding: 20px 0;
}

/* ── 3D section header ── */
.home-3d-header {
    text-align: center;
    margin-bottom: 32px;
}
.home-3d-header .home-section-title { margin-bottom: 6px; }
.home-3d-subtitle {
    font-size: 14px;
    color: #888;
}
</style>

    {{-- Hero --}}
    <x-hero />

    <div style="background:#f9f8f6; padding: 0 40px;">

        {{-- Categories --}}
        <div class="home-section">
            <h2 class="home-section-title">{{ __('general.categories') }}</h2>
            <div class="category-pills-row">
                @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat['name']]) }}"
                       class="category-pill">
                        <i class="ti {{ $cat['icon'] }}" aria-hidden="true"></i>
                        {{ $cat['name'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Trending Products --}}
        <div class="home-section">
            <div class="home-section-header">
                <h2 class="home-section-title">{{ __('general.trending_products') }}</h2>
                <a href="{{ route('products.index') }}" class="home-section-link">
                    {{ __('general.see_all') }} →
                </a>
            </div>
            <div class="product-grid-4">
                @forelse($trendingProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @empty
                    <p class="no-products">{{ __('general.no_results') }}</p>
                @endforelse
            </div>
        </div>

        {{-- New Arrivals --}}
        <div class="home-section">
            <div class="home-section-header">
                <h2 class="home-section-title">{{ __('general.new_arrivals') }}</h2>
                <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="home-section-link">
                    {{ __('general.see_all') }} →
                </a>
            </div>
            <div class="product-grid-4">
                @forelse($newArrivals as $product)
                    @include('partials.product-card', ['product' => $product])
                @empty
                    <p class="no-products">{{ __('general.no_results') }}</p>
                @endforelse
            </div>
        </div>

        {{-- 3D Products --}}
        @if($products3D->isNotEmpty())
        <div class="home-section">
            <div class="home-3d-header">
                <h2 class="home-section-title">{{ __('general.shop_in_3d') }}</h2>
                <p class="home-3d-subtitle">{{ __('general.try_before_you_buy') }}</p>
            </div>
            <div class="product-grid-4">
                @foreach($products3D as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
        @endif

        {{-- Featured Stores --}}
        @if($featuredStores->isNotEmpty())
        <div class="home-section featured-stores-section">
            <div class="home-section-header">
                <div>
                    <h2 class="home-section-title">{{ __('general.featured_stores') }}</h2>
                    <p class="home-section-sub">{{ __('general.discover_local_brands') }}</p>
                </div>
                <a href="{{ route('stores.index') }}" class="home-section-link">
                    {{ __('general.see_all_stores') }} →
                </a>
            </div>

            <div class="featured-stores-grid">
                @foreach($featuredStores as $store)
                <div class="featured-store-card" onclick="window.location='{{ route('stores.show', $store) }}'">
                    <div class="featured-store-banner">
                        <div class="featured-store-banner-inner">
                            @if($store->banner_path)
                                <img src="{{ asset($store->banner_path) }}" alt="{{ $store->name }}">
                            @else
                                <div class="featured-store-banner-ph"></div>
                            @endif
                        </div>
                        <div class="featured-store-logo">
                            @if($store->logo_path)
                                <img src="{{ asset($store->logo_path) }}" alt="{{ $store->name }} logo">
                            @else
                                <span>{{ strtoupper(substr($store->name, 0, 2)) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="featured-store-body">
                        <h3 class="featured-store-name">{{ $store->name }}</h3>
                        <p class="featured-store-tagline">{{ $store->tagline }}</p>

                        <div class="featured-store-thumbs">
                            @foreach($store->products->take(3) as $product)
                                @php
                                    $thumbImg = $product->images->first()?->image_path
                                        ?? ($product->image ? 'images/' . $product->image : 'images/placeholder.jpg');
                                @endphp
                                <img src="{{ asset($thumbImg) }}"
                                     alt="{{ $product->name }}"
                                     class="featured-store-thumb">
                            @endforeach
                        </div>

                        <a href="{{ route('stores.show', $store) }}" class="featured-store-btn">
                            {{ __('general.visit_store') }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Join Voxura CTA --}}
        <div class="join-voxura-banner">
            <div class="join-voxura-content">
                <div class="join-voxura-text">
                    <h2>{{ __('general.own_clothing_store') }}</h2>
                    <p>{{ __('general.join_voxura_desc') }}</p>
                    <a href="{{ route('partner.apply') }}" class="join-voxura-btn">
                        {{ __('general.apply_to_join') }}
                        <i class="ti ti-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- About --}}
    <x-about />

    {{-- Contact --}}
    <x-contact />

</x-layout>
