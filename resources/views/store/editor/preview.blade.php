<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Preview — {{ $store->name }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root { --orange: #ea580c; --orange-dark: #c2410c; --orange-light: #fff7ed; }
    * { box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #f8f7f5; margin: 0; }

    /* ── Hero Banner ── */
    .store-hero { width: 100%; height: 300px; position: relative; overflow: hidden; background: #1a1a1a; }
    .store-hero-img { width: 100%; height: 100%; object-fit: cover; object-position: center; opacity: 0.75; display: block; }
    .store-hero-overlay { position: absolute; inset: 0; background: linear-gradient(to right, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.3) 60%, rgba(0,0,0,0.1) 100%); }
    .store-hero-content { position: absolute; bottom: 0; left: 0; right: 0; padding: 28px 48px; display: flex; align-items: flex-end; gap: 24px; }
    .store-hero-logo { width: 120px; height: 120px; border-radius: 50%; border: 4px solid #fff; overflow: hidden; background: #1a1a1a; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 32px; flex-shrink: 0; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
    .store-hero-logo img { width: 100%; height: 100%; object-fit: cover; }
    .store-hero-text { flex: 1; color: #fff; }
    .store-hero-name { font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 4px; text-shadow: 0 2px 8px rgba(0,0,0,0.3); }
    .store-hero-tagline { font-size: 15px; color: rgba(255,255,255,0.85); margin-bottom: 14px; }
    .store-hero-badges { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
    .badge-powered { display: inline-flex; align-items: center; gap: 6px; background: rgba(234,88,12,0.15); border: 1.5px solid var(--orange); border-radius: 8px; padding: 7px 14px; color: var(--orange); font-size: 13px; font-weight: 600; }
    .btn-hero-primary { background: var(--orange); color: #fff; border: none; border-radius: 8px; padding: 10px 20px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; cursor: pointer; }
    .btn-hero-ghost { background: rgba(255,255,255,0.1); color: #fff; border: 1.5px solid rgba(255,255,255,0.4); border-radius: 8px; padding: 10px 20px; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; cursor: pointer; }
    .store-hero-btns { display: flex; flex-wrap: wrap; gap: 10px; }

    /* ── Store Info Bar ── */
    .store-info-bar { background: #fff; border: 1px solid #f0ede8; border-radius: 16px; margin: 24px 48px 0; padding: 24px 32px; display: flex; align-items: flex-start; gap: 32px; }
    .store-info-left { display: flex; gap: 20px; align-items: flex-start; flex: 1; }
    .store-info-logo { width: 72px; height: 72px; border-radius: 50%; border: 3px solid #f0ede8; overflow: hidden; background: #1a1a1a; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 20px; flex-shrink: 0; }
    .store-info-logo img { width: 100%; height: 100%; object-fit: cover; }
    .store-info-name { font-size: 20px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
    .store-info-desc { font-size: 13px; color: #666; line-height: 1.6; max-width: 400px; margin-bottom: 12px; }
    .store-info-cats { display: flex; flex-wrap: wrap; gap: 6px; }
    .category-tag { background: #f8f7f5; border: 1px solid #e0ddd9; border-radius: 20px; padding: 4px 14px; font-size: 12px; color: #555; font-weight: 500; }
    .store-info-stats { display: flex; align-items: center; gap: 0; flex-shrink: 0; }
    .stat-col { text-align: center; padding: 0 28px; border-right: 1px solid #f0ede8; }
    .stat-col:last-child { border-right: none; }
    .stat-num { font-size: 22px; font-weight: 800; color: #1a1a1a; display: block; }
    .stat-label { font-size: 12px; color: #888; display: block; }
    .store-info-right { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; flex-shrink: 0; }
    .btn-follow { background: var(--orange); color: #fff; border: none; border-radius: 8px; padding: 10px 22px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; }
    .store-transparency { background: #fdf5ef; border: 1px solid #fad4c0; border-radius: 10px; margin: 14px 48px 24px; padding: 11px 18px; display: flex; align-items: center; gap: 10px; font-size: 13px; color: #666; }

    /* ── Featured + Product Grid ── */
    .section-header { display: flex; align-items: center; justify-content: space-between; padding: 0 48px; margin-bottom: 18px; }
    .section-title { font-size: 20px; font-weight: 800; color: #1a1a1a; }
    .link-view-all { color: var(--orange); font-size: 14px; font-weight: 600; text-decoration: none; }
    .featured-row { display: flex; gap: 14px; padding: 0 48px; overflow-x: auto; margin-bottom: 36px; scrollbar-width: none; }
    .featured-row::-webkit-scrollbar { display: none; }
    .feat-card { flex: 0 0 200px; height: 320px; border-radius: 14px; overflow: hidden; position: relative; cursor: pointer; background: #e8e4df; flex-shrink: 0; text-decoration: none; display: block; }
    .feat-card:first-child { flex: 0 0 250px; }
    .feat-card img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .feat-card-ph { width: 100%; height: 100%; background: linear-gradient(135deg, #e8e4df, #d4cfc9); }
    .feat-badge-3d { position: absolute; bottom: 12px; left: 12px; background: var(--orange); color: #fff; border-radius: 20px; padding: 4px 10px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 4px; }
    .filter-sort-bar { display: flex; align-items: center; justify-content: space-between; padding: 0 48px; border-bottom: 1px solid #f0ede8; background: #fff; }
    .filter-tabs { display: flex; overflow-x: auto; scrollbar-width: none; }
    .filter-tab { padding: 12px 16px 14px; font-size: 14px; font-weight: 500; color: #888; text-decoration: none; white-space: nowrap; border-bottom: 2px solid transparent; display: inline-flex; align-items: center; gap: 5px; }
    .filter-tab.active { color: var(--orange); border-bottom-color: var(--orange); font-weight: 600; }
    .product-grid-wrap { padding: 28px 48px 0; background: #f8f7f5; }
    .product-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 32px; }
    .prod-card { background: #fff; border-radius: 12px; overflow: hidden; }
    .prod-card-img-wrap { position: relative; height: 260px; background: #f5f4f2; overflow: hidden; border-radius: 12px 12px 0 0; }
    .prod-card-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .prod-img-ph { width: 100%; height: 100%; background: linear-gradient(135deg, #e8e4df, #d4cfc9); }
    .prod-badge-3d { position: absolute; bottom: 10px; left: 10px; background: var(--orange); color: #fff; border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 4px; }
    .prod-card-body { padding: 12px; }
    .prod-name { font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 4px; }
    .prod-price { font-size: 15px; font-weight: 700; color: #1a1a1a; margin-bottom: 3px; }
    .prod-sold-by { font-size: 12px; color: #888; margin-bottom: 10px; }
    .prod-btns { display: flex; gap: 6px; }
    .btn-view-prod { flex: 1; background: #fff; border: 1.5px solid #e0ddd9; border-radius: 8px; padding: 8px; font-size: 12px; font-weight: 600; color: #333; cursor: pointer; text-align: center; text-decoration: none; display: block; }
    .btn-add-cart { flex: 1; background: var(--orange); border: none; border-radius: 8px; padding: 8px; font-size: 12px; font-weight: 700; color: #fff; cursor: pointer; text-align: center; display: flex; align-items: center; justify-content: center; gap: 4px; }
    .prod-empty { grid-column: span 4; text-align: center; padding: 60px; color: #aaa; font-size: 15px; }
    .load-more-wrap { text-align: center; padding: 0 48px 40px; background: #f8f7f5; }
    .load-more-count { font-size: 13px; color: #aaa; margin-top: 10px; }

    /* ── About Section ── */
    .section-about { margin: 0 48px 36px; background: #fff; border: 1px solid #f0ede8; border-radius: 16px; padding: 32px 36px; }
    .section-about h2 { font-size: 20px; font-weight: 800; color: #1a1a1a; margin-bottom: 24px; }
    .about-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; align-items: start; }
    .about-col h4 { font-size: 14px; font-weight: 700; color: #1a1a1a; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
    .about-col h4 i { color: var(--orange); }
    .about-col p { font-size: 13px; color: #666; line-height: 1.6; margin-bottom: 14px; }

    /* ── Trust Bar ── */
    .trust-bar { display: grid; grid-template-columns: repeat(3, 1fr); margin: 0 48px 36px; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #f0ede8; }
    .trust-bar-cell { padding: 22px 28px; display: flex; align-items: center; gap: 16px; border-right: 1px solid #f0ede8; }
    .trust-bar-cell:last-child { border-right: none; }
    .trust-icon { font-size: 30px; color: var(--orange); flex-shrink: 0; }
    .trust-label { font-size: 14px; font-weight: 700; color: #1a1a1a; display: block; margin-bottom: 2px; }
    .trust-sub { font-size: 12px; color: #888; display: block; }

    /* ── Similar Stores ── */
    .similar-stores-wrap { padding: 0 48px 36px; background: #f8f7f5; }
    .similar-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
    .sim-card { background: #fff; border: 1px solid #f0ede8; border-radius: 14px; overflow: hidden; }
    .sim-card-banner { position: relative; height: 180px; background: #e8e4df; overflow: hidden; }
    .sim-card-banner img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .sim-card-logo { position: absolute; bottom: -32px; left: 20px; width: 64px; height: 64px; border-radius: 50%; border: 3px solid #fff; overflow: hidden; background: #1a1a1a; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 18px; z-index: 2; }
    .sim-card-logo img { width: 100%; height: 100%; object-fit: cover; }
    .sim-card-body { padding: 44px 16px 16px; }
    .sim-card-name { font-size: 16px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
    .sim-card-tagline { font-size: 12px; color: #888; margin-bottom: 12px; }
    .sim-card-btns { display: flex; gap: 8px; }
    .btn-sim-visit { background: #fff; border: 1.5px solid #e0ddd9; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; color: #333; text-decoration: none; }

    /* ── CTA ── */
    .store-cta { margin: 0 48px 40px; border-radius: 16px; overflow: hidden; position: relative; min-height: 220px; background: #1a1a1a; display: flex; align-items: center; }
    .store-cta-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.55); }
    .store-cta-content { position: relative; z-index: 2; padding: 48px 56px; max-width: 55%; }
    .store-cta-content h2 { font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 12px; }
    .store-cta-content p { font-size: 14px; color: rgba(255,255,255,0.75); line-height: 1.7; margin-bottom: 24px; }
    .btn-cta-apply { background: var(--orange); color: #fff; border: none; border-radius: 8px; padding: 13px 26px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; cursor: pointer; }

    @media (max-width: 768px) {
      .store-hero { height: auto; min-height: 240px; }
      .store-hero-content { padding: 20px; flex-direction: column; align-items: flex-start; }
      .store-hero-logo { width: 80px; height: 80px; font-size: 22px; }
      .store-hero-name { font-size: 22px; }
      .store-info-bar { flex-direction: column; margin: 16px 16px 0; padding: 20px; }
      .section-header { padding: 0 16px; }
      .featured-row { padding: 0 16px; }
      .filter-sort-bar { padding: 0 16px; }
      .product-grid-wrap { padding: 16px 16px 0; }
      .product-grid { grid-template-columns: repeat(2, 1fr); }
      .prod-empty { grid-column: span 2; }
      .section-about { margin: 0 16px 24px; padding: 24px 20px; }
      .about-grid { grid-template-columns: 1fr; }
      .trust-bar { margin: 0 16px 24px; }
      .similar-stores-wrap { padding: 0 16px 28px; }
      .similar-grid { grid-template-columns: 1fr; }
      .store-cta { margin: 0 16px 28px; }
      .store-cta-content { padding: 32px 24px; max-width: 100%; }
      .store-transparency { margin: 12px 16px 16px; }
      .load-more-wrap { padding: 0 16px 28px; }
    }
  </style>
</head>
<body>

{{-- STORE HERO --}}
<div class="store-hero">
  @if($store->banner_path && file_exists(public_path($store->banner_path)))
    <img class="store-hero-img" src="{{ asset($store->banner_path) }}" alt="{{ $store->name }}">
  @endif
  <div class="store-hero-overlay"></div>
  <div class="store-hero-content">
    <div class="store-hero-logo">
      @if($store->logo_path && file_exists(public_path($store->logo_path)))
        <img src="{{ asset($store->logo_path) }}" alt="{{ $store->name }}">
      @else
        {{ strtoupper(substr($store->name, 0, 2)) }}
      @endif
    </div>
    <div class="store-hero-text">
      <div class="store-hero-name store-name-preview">{{ $store->name }}</div>
      <div class="store-hero-tagline store-tagline-preview">{{ $store->tagline }}</div>
      <div class="store-hero-badges">
        <span class="badge-powered"><i class="ti ti-bolt"></i> Powered by Voxura</span>
      </div>
      <div class="store-hero-btns">
        <a href="#product-grid" class="btn-hero-primary"><i class="ti ti-shopping-bag"></i> Shop Products</a>
      </div>
    </div>
  </div>
</div>

{{-- STORE INFO BAR --}}
<div class="store-info-bar">
  <div class="store-info-left">
    <div class="store-info-logo">
      @if($store->logo_path && file_exists(public_path($store->logo_path)))
        <img src="{{ asset($store->logo_path) }}" alt="{{ $store->name }}">
      @else
        {{ strtoupper(substr($store->name, 0, 2)) }}
      @endif
    </div>
    <div>
      <div class="store-info-name store-name-preview">{{ $store->name }}</div>
      <p class="store-info-desc store-description-preview">{{ $store->description }}</p>
      <div class="store-info-cats">
        @foreach($store->category_tags ?? [] as $tag)
          <span class="category-tag">{{ $tag }}</span>
        @endforeach
      </div>
    </div>
  </div>
  <div class="store-info-stats">
    <div class="stat-col">
      <span class="stat-num">{{ $totalProducts }}</span>
      <span class="stat-label">Products</span>
    </div>
    <div class="stat-col">
      <span class="stat-num">{{ $total3DProducts }}</span>
      <span class="stat-label">3D Products</span>
    </div>
  </div>
  <div class="store-info-right">
    <button class="btn-follow"><i class="ti ti-plus"></i> Follow</button>
  </div>
</div>

<div class="store-transparency">
  <i class="ti ti-shield-check" style="color:var(--orange);font-size:17px;flex-shrink:0;"></i>
  {{ $store->name }} manages its own products on Voxura.
</div>

{{-- FEATURED COLLECTION --}}
<div class="section-header" style="margin-top:8px;">
  <span class="section-title">Featured Collection</span>
</div>

@if($featuredProducts->isNotEmpty())
<div class="featured-row">
  @foreach($featuredProducts as $fp)
    @php
      $fpImg = $fp->images->first();
      $fpImgSrc = null;
      if ($fpImg && file_exists(public_path($fpImg->image_path))) {
        $fpImgSrc = asset($fpImg->image_path);
      } elseif ($fp->image && file_exists(public_path('images/' . $fp->image))) {
        $fpImgSrc = asset('images/' . $fp->image);
      }
    @endphp
    <a href="{{ route('products.show', $fp) }}" class="feat-card">
      @if($fpImgSrc)
        <img src="{{ $fpImgSrc }}" alt="{{ $fp->name }}">
      @else
        <div class="feat-card-ph"></div>
      @endif
    </a>
  @endforeach
</div>
@endif

{{-- FILTER / PRODUCT GRID --}}
<div id="product-grid" class="filter-sort-bar">
  <div class="filter-tabs">
    <a href="#" class="filter-tab active">All</a>
  </div>
</div>

<div class="product-grid-wrap">
  <div class="product-grid">
    @forelse($products as $product)
      @php
        $pImg = $product->images->first();
        $pImgSrc = null;
        if ($pImg && file_exists(public_path($pImg->image_path))) {
          $pImgSrc = asset($pImg->image_path);
        } elseif ($product->image && file_exists(public_path('images/' . $product->image))) {
          $pImgSrc = asset('images/' . $product->image);
        }
      @endphp
      <div class="prod-card">
        <div class="prod-card-img-wrap">
          @if($pImgSrc)
            <img src="{{ $pImgSrc }}" alt="{{ $product->name }}">
          @else
            <div class="prod-img-ph"></div>
          @endif
        </div>
        <div class="prod-card-body">
          <div class="prod-name">{{ $product->name }}</div>
          <div class="prod-price">{{ config('shop.currency_symbol', '₪') }}{{ number_format($product->price, 2) }}</div>
          <div class="prod-sold-by">Sold by <span class="store-name-preview">{{ $store->name }}</span></div>
          <div class="prod-btns">
            <a href="{{ route('products.show', $product) }}" class="btn-view-prod">View</a>
            <button class="btn-add-cart"><i class="ti ti-shopping-cart" style="font-size:13px;"></i> Add</button>
          </div>
        </div>
      </div>
    @empty
      <div class="prod-empty">No products yet. Add your first product!</div>
    @endforelse
  </div>
</div>

<div class="load-more-wrap">
  <p class="load-more-count">Showing {{ $products->count() }} of {{ $totalProducts }} products</p>
</div>

{{-- ABOUT --}}
<div class="section-about">
  <h2>About the Store</h2>
  <div class="about-grid">
    <div class="about-col">
      <h4>About <span class="store-name-preview">{{ $store->name }}</span></h4>
      <p class="store-description-preview">{{ $store->description }}</p>
    </div>
    <div class="about-col">
      <h4><i class="ti ti-shield-check"></i> Trust & Safety</h4>
      <p>All products are verified and sellers are authenticated on the Voxura platform.</p>
    </div>
    <div class="about-col">
      <h4><i class="ti ti-truck"></i> Shipping</h4>
      <p>Fast and reliable shipping on all orders. Free shipping on orders above $50.</p>
    </div>
    <div class="about-col">
      <h4><i class="ti ti-refresh"></i> Returns</h4>
      <p>Easy 30-day returns. No questions asked.</p>
    </div>
  </div>
</div>

{{-- TRUST BAR --}}
<div class="trust-bar">
  <div class="trust-bar-cell"><i class="ti ti-truck trust-icon"></i><div><span class="trust-label">Free Shipping</span><span class="trust-sub">On orders above $50</span></div></div>
  <div class="trust-bar-cell"><i class="ti ti-refresh trust-icon"></i><div><span class="trust-label">Easy Returns</span><span class="trust-sub">30-day return policy</span></div></div>
  <div class="trust-bar-cell"><i class="ti ti-shield-check trust-icon"></i><div><span class="trust-label">Secure Checkout</span><span class="trust-sub">Encrypted payments</span></div></div>
</div>

{{-- SIMILAR STORES --}}
@if($similarStores->isNotEmpty())
<div class="similar-stores-wrap">
  <div class="section-header" style="padding:0;margin-bottom:18px;">
    <span class="section-title">More Stores You May Like</span>
    <a href="{{ route('stores.index') }}" class="link-view-all">View All →</a>
  </div>
  <div class="similar-grid">
    @foreach($similarStores as $similar)
    <div class="sim-card">
      <div class="sim-card-banner">
        @if($similar->banner_path && file_exists(public_path($similar->banner_path)))
          <img src="{{ asset($similar->banner_path) }}" alt="{{ $similar->name }}">
        @else
          <div style="width:100%;height:100%;background:linear-gradient(135deg,#2a2a2a,#444);"></div>
        @endif
        <div class="sim-card-logo">
          @if($similar->logo_path && file_exists(public_path($similar->logo_path)))
            <img src="{{ asset($similar->logo_path) }}" alt="{{ $similar->name }}">
          @else
            {{ strtoupper(substr($similar->name, 0, 2)) }}
          @endif
        </div>
      </div>
      <div class="sim-card-body">
        <div class="sim-card-name">{{ $similar->name }}</div>
        <div class="sim-card-tagline">{{ $similar->tagline }}</div>
        <div class="sim-card-btns">
          <a href="{{ route('stores.show', $similar) }}" class="btn-sim-visit">Visit Store</a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endif

{{-- CTA --}}
<div class="store-cta">
  <div class="store-cta-overlay"></div>
  <div class="store-cta-content">
    <h2>Own a Clothing Store?</h2>
    <p>Create your branded store on Voxura and sell with immersive 3D product visualization.</p>
    <a href="{{ route('partner.apply') }}" class="btn-cta-apply">Apply to Join Voxura <i class="ti ti-arrow-right"></i></a>
  </div>
</div>

<script>
window.addEventListener('message', (e) => {
  if (!e.data) return;

  if (e.data.type === 'voxura-update') {
    const { field, value } = e.data;
    switch (field) {
      case 'name':
        document.querySelectorAll('.store-name-preview').forEach(el => el.textContent = value);
        break;
      case 'tagline':
        document.querySelectorAll('.store-tagline-preview').forEach(el => el.textContent = value);
        break;
      case 'description':
        document.querySelectorAll('.store-description-preview').forEach(el => el.textContent = value);
        break;
      case 'accent_color':
        document.documentElement.style.setProperty('--orange', value);
        break;
    }
  }

  if (e.data.type === 'voxura-scroll') {
    const el = document.getElementById(e.data.sectionId);
    if (el) el.scrollIntoView({ behavior: 'smooth' });
  }
});
</script>
</body>
</html>
