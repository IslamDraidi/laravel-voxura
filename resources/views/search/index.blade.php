<x-layout title="Search — {{ $q ?: 'All Products' }}">
<style>
.search-page { padding-top: 100px; padding-bottom: 4rem; }

.search-heading {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3rem); font-weight: 800;
    color: var(--gray-900); letter-spacing: -0.03em; margin-bottom: 0.35rem;
}
.search-heading .accent { color: var(--orange); }
.search-sub { color: var(--gray-500); font-size: 0.95rem; margin-bottom: 2rem; }

.search-bar-wrap {
    background: #fff; border: 1.5px solid var(--gray-200); border-radius: var(--radius);
    padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; box-shadow: var(--shadow-md);
}
.search-form { display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end; }
.search-input-wrap { flex: 1; min-width: 220px; position: relative; }
.search-input {
    width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem;
    border: 1.5px solid var(--gray-200); border-radius: 999px;
    font-size: 0.95rem; font-family: 'DM Sans', sans-serif;
    color: var(--gray-900); outline: none; transition: border-color 0.15s;
}
.search-input:focus { border-color: var(--orange); }
.search-icon {
    position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%);
    color: var(--gray-400); pointer-events: none;
}
[dir="rtl"] .search-icon { left: auto; right: 0.9rem; }
[dir="rtl"] .search-input { padding: 0.75rem 2.75rem 0.75rem 1rem; }
.filter-select {
    padding: 0.65rem 0.9rem; border: 1.5px solid var(--gray-200); border-radius: 999px;
    font-size: 0.85rem; font-family: 'DM Sans', sans-serif; color: var(--gray-700);
    outline: none; transition: border-color 0.15s; background: #fff; min-width: 140px;
}
.filter-select:focus { border-color: var(--orange); }
.btn-search {
    background: var(--orange); color: #fff; border: none; padding: 0.7rem 1.75rem;
    border-radius: 999px; font-size: 0.9rem; font-weight: 700; cursor: pointer;
    font-family: 'DM Sans', sans-serif; transition: background 0.15s;
}
.btn-search:hover { background: var(--orange-dark); }

.results-meta {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.5rem;
}
.results-count { font-size: 0.88rem; color: var(--gray-500); }
.results-count strong { color: var(--gray-900); font-weight: 700; }

/* ── Product Grid ── */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.5rem;
}
.product-card {
    background: #fff; border: 1.5px solid var(--gray-200); border-radius: var(--radius);
    overflow: hidden; transition: box-shadow 0.2s, border-color 0.2s; text-decoration: none;
    display: flex; flex-direction: column;
}
.product-card:hover { box-shadow: var(--shadow-md); border-color: var(--orange-muted); }
.card-img { width: 100%; aspect-ratio: 1/1; object-fit: cover; background: var(--gray-100); display: block; }
.card-img-placeholder {
    width: 100%; aspect-ratio: 1/1; background: var(--gray-100);
    display: flex; align-items: center; justify-content: center; color: var(--gray-300);
}
.card-body { padding: 1rem; flex: 1; display: flex; flex-direction: column; gap: 0.35rem; }
.card-category { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--orange); }
.card-name { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--gray-900); line-height: 1.3; }
.card-price { font-size: 1.05rem; font-weight: 800; color: var(--gray-900); margin-top: auto; }
.card-stock { font-size: 0.78rem; color: var(--gray-400); }
.card-stock.out { color: #ef4444; }
.no-results { text-align: center; padding: 5rem 2rem; color: var(--gray-400); }
.no-results svg { margin: 0 auto 1rem; display: block; }
.no-results h3 { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 800; color: var(--gray-600); margin-bottom: 0.5rem; }
.no-results p { font-size: 0.9rem; }

/* ════════════════════════════════════════════════════════════
   FILTER DRAWER
   ════════════════════════════════════════════════════════════ */

/* Trigger bar */
.fd-trigger-wrap {
    background: #ffffff;
    border: 1px solid #f0eeeb;
    border-radius: 14px;
    padding: 12px 16px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.fd-trigger-bar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.5rem; width: 100%;
}
.fd-trigger-left { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.fd-trigger-right { display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap; }

.fd-filter-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 16px;
    background: transparent; color: #3a3a3a;
    border: 1.5px solid #e0ddd9; border-radius: 10px;
    cursor: pointer; font-size: 14px; font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    transition: all 0.2s;
}
.fd-filter-btn svg { color: #E8621A; flex-shrink: 0; }
.fd-filter-btn:hover { border-color: #E8621A; color: #1a1a1a; }
.fd-filter-btn--active { border-color: #E8621A; background: #fff5f0; color: #E8621A; }
.fd-filter-btn--active svg { color: #E8621A; }
.fd-filter-btn--active:hover { border-color: #E8621A; background: #fff5f0; color: #E8621A; }

.fd-active-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #E8621A; flex-shrink: 0;
}

.fd-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 17px; height: 17px; padding: 0 3px;
    background: #E8621A; color: #fff;
    border-radius: 999px; font-size: 0.68rem; font-weight: 800;
}

.fd-result-count { font-size: 13px; color: #999893; }
.fd-result-count strong { color: #1a1a1a; font-weight: 600; }

.fd-sort-select {
    padding: 9px 36px 9px 14px;
    border: 1px solid #e0ddd9; border-radius: 10px;
    font-size: 13px; font-family: 'DM Sans', sans-serif;
    color: #3a3a3a; font-weight: 500;
    background: #f8f7f5 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23E8621A' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 12px center / 12px;
    outline: none; appearance: none; -webkit-appearance: none;
    min-width: 160px; cursor: pointer;
    transition: border-color 0.2s, background-color 0.2s;
}
.fd-sort-select:hover { border-color: #E8621A; background-color: #fff; }
.fd-sort-select:focus { border-color: #E8621A; }

/* Active filter chips */
.fd-active-tags {
    display: flex; align-items: center; flex-wrap: wrap;
    gap: 8px; margin-bottom: 20px;
}
.fd-active-tag {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px;
    background: #fff5f0; color: #E8621A;
    border: 1px solid #fad4c0; border-radius: 20px;
    font-size: 12px; font-weight: 500; text-decoration: none;
    transition: background 0.12s;
}
.fd-active-tag:hover { background: #ffe8dc; }
.fd-active-tag-x { opacity: 0.6; font-size: 14px; }
.fd-active-tag--clear {
    background: transparent; color: #999893;
    border: none; font-size: 12px; padding: 4px 8px; border-radius: 6px;
}
.fd-active-tag--clear:hover { color: #3a3a3a; }

/* Overlay */
.fd-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,0.65);
    z-index: 55; transition: opacity 200ms ease;
}

/* Drawer */
.fd-drawer {
    position: fixed; left: 0; top: 0;
    height: 100vh; width: 320px; max-width: 85vw;
    background: #1a1a1a;
    border-right: 1px solid rgba(255,255,255,0.08);
    z-index: 60;
    transform: translateX(-100%);
    transition: transform 300ms ease-in-out;
    display: flex; flex-direction: column; overflow: hidden;
}
.fd-drawer--open { transform: translateX(0); }
[dir="rtl"] .fd-drawer {
    left: auto; right: 0;
    border-right: none; border-left: 1px solid rgba(255,255,255,0.08);
    transform: translateX(100%);
}
[dir="rtl"] .fd-drawer--open { transform: translateX(0); }

.fd-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.25rem 1.5rem 1rem; flex-shrink: 0;
}
.fd-title { font-size: 1rem; font-weight: 700; color: #fff; }
.fd-close {
    background: none; border: none; cursor: pointer;
    color: rgba(255,255,255,0.4); padding: 4px;
    display: flex; align-items: center; border-radius: 4px;
    transition: color 0.12s;
}
.fd-close:hover { color: #fff; }
.fd-header-divider { height: 2px; background: var(--orange); margin: 0 1.5rem; flex-shrink: 0; }

.fd-body {
    flex: 1; overflow-y: auto; padding: 0 1.5rem;
    scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.1) transparent;
}
.fd-body::-webkit-scrollbar { width: 4px; }
.fd-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

.fd-section { padding: 1rem 0; border-top: 1px solid rgba(255,255,255,0.06); }
.fd-section:first-child { border-top: none; padding-top: 0.9rem; }
.fd-section-label {
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em;
    text-transform: uppercase; color: rgba(255,255,255,0.45); margin-bottom: 0.75rem;
}

/* Price radios */
.fd-price-options { display: flex; flex-direction: column; gap: 0.3rem; }
.fd-radio-label {
    display: flex; align-items: center; gap: 0.55rem;
    cursor: pointer; color: rgba(255,255,255,0.62); font-size: 0.87rem;
    padding: 0.15rem 0; transition: color 0.12s; user-select: none;
}
.fd-radio-label:hover { color: rgba(255,255,255,0.9); }
.fd-radio { position: absolute; opacity: 0; pointer-events: none; width: 0; height: 0; }
.fd-radio-custom {
    width: 15px; height: 15px; border-radius: 50%; flex-shrink: 0;
    border: 2px solid rgba(255,255,255,0.22);
    transition: border-color 0.12s, background 0.12s;
}
.fd-radio:checked ~ .fd-radio-custom {
    border-color: var(--orange); background: var(--orange);
    box-shadow: inset 0 0 0 3px #1a1a1a;
}
.fd-radio-label:has(.fd-radio:checked) { color: #fff; }

/* Size chips */
.fd-chips { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.fd-chip-input { position: absolute; opacity: 0; pointer-events: none; width: 0; height: 0; }
.fd-chip {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 36px; padding: 0.28rem 0.55rem;
    border: 1.5px solid rgba(255,255,255,0.15); border-radius: 6px;
    font-size: 0.8rem; font-weight: 600; color: rgba(255,255,255,0.52);
    cursor: pointer; transition: border-color 0.12s, color 0.12s; user-select: none;
}
.fd-chip:hover { border-color: rgba(255,255,255,0.4); color: rgba(255,255,255,0.9); }
.fd-chip-input:checked + .fd-chip { border-color: var(--orange); color: var(--orange); }

/* Color swatches */
.fd-colors { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.fd-color-wrap { position: relative; }
.fd-color-input { position: absolute; opacity: 0; pointer-events: none; width: 0; height: 0; }
.fd-color-swatch {
    width: 27px; height: 27px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.12); cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    background-color: #6b7280;
    transition: border-color 0.12s, transform 0.12s;
}
.fd-color-swatch:hover { border-color: rgba(255,255,255,0.5); transform: scale(1.12); }
.fd-color-input:checked + .fd-color-swatch {
    border-color: var(--orange);
    outline: 2px solid var(--orange); outline-offset: 2px;
}
.fd-color-check { width: 10px; height: 10px; opacity: 0; transition: opacity 0.12s; pointer-events: none; }
.fd-color-input:checked + .fd-color-swatch .fd-color-check { opacity: 1; }

/* Category checkboxes */
.fd-cats { display: flex; flex-direction: column; gap: 0.3rem; }
.fd-cat-label {
    display: flex; align-items: center; gap: 0.55rem;
    cursor: pointer; color: rgba(255,255,255,0.62); font-size: 0.87rem;
    padding: 0.12rem 0; transition: color 0.12s; user-select: none;
}
.fd-cat-label:hover { color: rgba(255,255,255,0.9); }
.fd-cat-input { position: absolute; opacity: 0; pointer-events: none; width: 0; height: 0; }
.fd-cat-custom {
    width: 15px; height: 15px; border-radius: 3px; flex-shrink: 0;
    border: 2px solid rgba(255,255,255,0.22);
    display: flex; align-items: center; justify-content: center;
    transition: border-color 0.12s, background 0.12s;
}
.fd-cat-input:checked + .fd-cat-custom { border-color: var(--orange); background: var(--orange); }
.fd-cat-input:checked + .fd-cat-custom::after {
    content: '';
    display: block; width: 4px; height: 7px;
    border: 2px solid #fff; border-top: none; border-left: none;
    transform: rotate(45deg) translate(0, -1px);
}
.fd-cat-label:has(.fd-cat-input:checked) { color: #fff; }
.fd-cat-count { margin-left: auto; font-size: 0.74rem; color: rgba(255,255,255,0.28); }
[dir="rtl"] .fd-cat-count { margin-left: 0; margin-right: auto; }

/* Footer */
.fd-footer {
    display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;
    padding: 0.9rem 1.5rem 1.3rem;
    border-top: 1px solid rgba(255,255,255,0.08); flex-shrink: 0;
}
.fd-btn-clear {
    color: rgba(255,255,255,0.38); font-size: 0.83rem; font-weight: 600;
    text-decoration: none; transition: color 0.12s; padding: 0.25rem;
}
.fd-btn-clear:hover { color: #fff; }
.fd-btn-apply {
    background: var(--orange); color: #fff; border: none; border-radius: 999px;
    padding: 0.55rem 1.35rem; font-size: 0.88rem; font-weight: 700;
    font-family: 'DM Sans', sans-serif; cursor: pointer;
    transition: background 0.15s; flex-shrink: 0;
}
.fd-btn-apply:hover { background: var(--orange-dark); }

@media (max-width: 768px) {
    .fd-drawer { width: 85vw; }
    .fd-trigger-right { gap: 0.5rem; }
}
</style>

<div class="search-page">

    <h1 class="search-heading">
        @if($q)
            {{ __('general.search_results_for') }} <span class="accent">"{{ $q }}"</span>
        @else
            <span class="accent">{{ __('general.all') }}</span> {{ __('general.products') }}
        @endif
    </h1>
    <p class="search-sub">{{ __('general.search_subtitle') }}</p>

    {{-- Search Bar --}}
    <div class="search-bar-wrap">
        <form method="GET" action="{{ route('products.search') }}" class="search-form">
            <div class="search-input-wrap">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" name="q" class="search-input"
                       placeholder="{{ __('general.search_placeholder') }}" value="{{ $q }}" autofocus>
            </div>
            <select name="category" class="filter-select">
                <option value="">{{ __('general.all_categories') }}</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ ($categoryId ?? null) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @if(!isset($isShopPage))
            <select name="sort" class="filter-select">
                <option value="relevance" {{ ($sort ?? 'relevance') === 'relevance' ? 'selected' : '' }}>{{ __('general.sort_relevance') }}</option>
                <option value="newest"    {{ ($sort ?? '') === 'newest'    ? 'selected' : '' }}>{{ __('general.sort_newest') }}</option>
                <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>{{ __('general.sort_price_asc') }}</option>
                <option value="price_desc"{{ ($sort ?? '') === 'price_desc'? 'selected' : '' }}>{{ __('general.sort_price_desc') }}</option>
            </select>
            @endif
            <button type="submit" class="btn-search">{{ __('general.search') }}</button>
        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         FILTER DRAWER — only rendered on /products
         ══════════════════════════════════════════════════════════ --}}
    @isset($isShopPage)
    @php
        $sizes       = $sizes       ?? [];
        $colors      = $colors      ?? [];
        $priceRange  = $priceRange  ?? '';
        $categoryIds = $categoryIds ?? [];
        $availableSizes  = $availableSizes  ?? collect(['XS','S','M','L','XL','XXL']);
        $availableColors = $availableColors ?? collect();
        $productCounts   = $productCounts   ?? collect();

        $storeSlug   = $storeSlug ?? '';
        $filterCount = count($sizes) + count($colors) + count($categoryIds) + ($priceRange !== '' ? 1 : 0) + ($storeSlug !== '' ? 1 : 0);
        $hasActive   = $filterCount > 0;

        $currParams = array_filter(
            request()->only(['sort','size','color','price_range','category','store']),
            fn($v) => $v !== '' && $v !== [] && $v !== null
        );

        $rmFilter = function(string $key, ?string $val = null) use ($currParams): string {
            $p = $currParams;
            if ($val === null) {
                unset($p[$key]);
            } else {
                $arr = array_values(array_filter((array) ($p[$key] ?? []), fn($v) => $v !== $val));
                if ($arr) $p[$key] = $arr; else unset($p[$key]);
            }
            $base = route('products.index');
            return $base . ($p ? '?' . http_build_query($p) : '');
        };

        $priceLabel = match($priceRange) {
            'under_50' => __('general.price_under_50'),
            '50_150'   => __('general.price_50_150'),
            '150_300'  => __('general.price_150_300'),
            '300_plus' => __('general.price_300_plus'),
            default    => '',
        };

        $sortSuffix = ($sort ?? 'newest') !== 'newest' ? '?sort=' . ($sort ?? 'newest') : '';
    @endphp

    <div x-data="{ drawerOpen: false, touchStartX: 0 }">

        {{-- Overlay (fixed, sits above everything incl. nav) --}}
        <div
            class="fd-overlay"
            data-dusk="filter-backdrop"
            x-show="drawerOpen"
            x-transition.opacity
            @click="drawerOpen = false"
            style="display:none"
        ></div>

        <form method="GET" action="{{ route('products.index') }}" id="fd-form">
            @if($q)<input type="hidden" name="q" value="{{ $q }}">@endif

            {{-- Trigger Bar --}}
            <div class="fd-trigger-wrap">
            <div class="fd-trigger-bar">
                <div class="fd-trigger-left">
                    <button
                        type="button"
                        class="fd-filter-btn {{ $hasActive ? 'fd-filter-btn--active' : '' }}"
                        data-dusk="filter-btn"
                        @click="drawerOpen = true"
                    >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" y1="6" x2="20" y2="6"/>
                            <line x1="8" y1="12" x2="16" y2="12"/>
                            <line x1="10" y1="18" x2="14" y2="18"/>
                        </svg>
                        @if($hasActive)<span class="fd-active-dot"></span>@endif
                        {{ __('general.filter_title') }}
                        @if($filterCount > 0)
                            <span class="fd-badge">{{ $filterCount }}</span>
                        @endif
                    </button>
                </div>
                <div class="fd-trigger-right">
                    <span class="fd-result-count">
                        <strong>{{ $products->count() }}</strong> {{ __('general.results_count') }}
                    </span>
                    <select name="sort" class="fd-sort-select" onchange="this.form.submit()">
                        <option value="newest"     {{ ($sort ?? 'newest') === 'newest'     ? 'selected' : '' }}>{{ __('general.sort_newest') }}</option>
                        <option value="price_asc"  {{ ($sort ?? '') === 'price_asc'        ? 'selected' : '' }}>{{ __('general.sort_price_asc') }}</option>
                        <option value="price_desc" {{ ($sort ?? '') === 'price_desc'       ? 'selected' : '' }}>{{ __('general.sort_price_desc') }}</option>
                        <option value="popular"    {{ ($sort ?? '') === 'popular'          ? 'selected' : '' }}>{{ __('general.sort_popular') }}</option>
                    </select>
                </div>
            </div>
            </div>{{-- /.fd-trigger-wrap --}}

            {{-- Active Filter Tags --}}
            @if($hasActive)
            <div class="fd-active-tags">
                @foreach($sizes as $s)
                    <a href="{{ $rmFilter('size', $s) }}" class="fd-active-tag" data-dusk="filter-chip">
                        {{ __('general.size') }}: {{ $s }}
                        <span class="fd-active-tag-x" data-dusk="filter-chip-remove">&times;</span>
                    </a>
                @endforeach
                @foreach($colors as $c)
                    <a href="{{ $rmFilter('color', $c) }}" class="fd-active-tag">
                        {{ __('general.color') }}: {{ $c }}
                        <span class="fd-active-tag-x">&times;</span>
                    </a>
                @endforeach
                @if($priceLabel)
                    <a href="{{ $rmFilter('price_range') }}" class="fd-active-tag">
                        {{ $priceLabel }}
                        <span class="fd-active-tag-x">&times;</span>
                    </a>
                @endif
                @foreach($categoryIds as $cid)
                    @php $cName = $categories->firstWhere('id', (int)$cid)?->name ?? $cid; @endphp
                    <a href="{{ $rmFilter('category', (string)$cid) }}" class="fd-active-tag">
                        {{ __('general.category') }}: {{ $cName }}
                        <span class="fd-active-tag-x">&times;</span>
                    </a>
                @endforeach
                @if($storeSlug !== '')
                    @php $activeStoreName = $stores->firstWhere('slug', $storeSlug)?->name ?? $storeSlug; @endphp
                    <a href="{{ $rmFilter('store') }}" class="fd-active-tag" data-dusk="filter-chip">
                        {{ __('general.store') }}: {{ $activeStoreName }}
                        <span class="fd-active-tag-x" data-dusk="filter-chip-remove">&times;</span>
                    </a>
                @endif
                <a href="{{ route('products.index') . $sortSuffix }}" class="fd-active-tag fd-active-tag--clear">
                    {{ __('general.clear_all') }}
                </a>
            </div>
            @endif

            {{-- ══════════════════════════════════════════
                 DRAWER PANEL
                 ══════════════════════════════════════════ --}}
            <div
                class="fd-drawer"
                data-dusk="filter-drawer"
                :class="{ 'fd-drawer--open': drawerOpen }"
                @touchstart.passive="touchStartX = $event.touches[0].clientX"
                @touchmove.passive="
                    const dx = $event.touches[0].clientX - touchStartX;
                    const rtl = document.documentElement.dir === 'rtl';
                    if (rtl ? dx > 60 : dx < -60) drawerOpen = false;
                "
            >
                {{-- Header --}}
                <div class="fd-header">
                    <span class="fd-title">{{ __('general.filter_title') }}</span>
                    <button type="button" class="fd-close" data-dusk="filter-close-btn" @click="drawerOpen = false"
                            aria-label="{{ __('general.close') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.5" stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="fd-header-divider"></div>

                {{-- Scrollable Body --}}
                <div class="fd-body">

                    {{-- § Price Range --}}
                    <div class="fd-section">
                        <div class="fd-section-label">{{ __('general.price_range') }}</div>
                        <div class="fd-price-options">
                            @foreach([
                                [''         , __('general.all_prices')    ],
                                ['under_50' , __('general.price_under_50')],
                                ['50_150'   , __('general.price_50_150')  ],
                                ['150_300'  , __('general.price_150_300') ],
                                ['300_plus' , __('general.price_300_plus')],
                            ] as [$val, $label])
                            <label class="fd-radio-label">
                                <input
                                    type="radio"
                                    name="price_range"
                                    value="{{ $val }}"
                                    class="fd-radio"
                                    {{ $priceRange === $val ? 'checked' : '' }}
                                >
                                <span class="fd-radio-custom"></span>
                                {{ $label }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- § Size --}}
                    <div class="fd-section">
                        <div class="fd-section-label">{{ __('general.size') }}</div>
                        <div class="fd-chips">
                            @foreach($availableSizes as $size)
                            <span style="position:relative">
                                <input
                                    type="checkbox"
                                    name="size[]"
                                    value="{{ $size }}"
                                    id="fd-s-{{ Str::slug($size) }}"
                                    class="fd-chip-input"
                                    {{ in_array($size, $sizes) ? 'checked' : '' }}
                                >
                                <label for="fd-s-{{ Str::slug($size) }}" class="fd-chip">{{ $size }}</label>
                            </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- § Color --}}
                    @if($availableColors->isNotEmpty())
                    <div class="fd-section">
                        <div class="fd-section-label">{{ __('general.color') }}</div>
                        <div class="fd-colors">
                            @foreach($availableColors as $color)
                            <span class="fd-color-wrap">
                                <input
                                    type="checkbox"
                                    name="color[]"
                                    value="{{ $color }}"
                                    id="fd-c-{{ Str::slug($color) }}"
                                    class="fd-color-input"
                                    {{ in_array($color, $colors) ? 'checked' : '' }}
                                >
                                <label
                                    for="fd-c-{{ Str::slug($color) }}"
                                    class="fd-color-swatch"
                                    style="background-color:{{ strtolower($color) }}"
                                    title="{{ $color }}"
                                >
                                    <svg class="fd-color-check" viewBox="0 0 12 12" fill="none"
                                         stroke="white" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="2,6 5,9 10,3"/>
                                    </svg>
                                </label>
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- § Category --}}
                    <div class="fd-section">
                        <div class="fd-section-label">{{ __('general.category') }}</div>
                        <div class="fd-cats">
                            @foreach($categories as $cat)
                            <label class="fd-cat-label">
                                <input
                                    type="checkbox"
                                    name="category[]"
                                    value="{{ $cat->id }}"
                                    class="fd-cat-input"
                                    {{ in_array((string)$cat->id, array_map('strval', $categoryIds)) ? 'checked' : '' }}
                                >
                                <span class="fd-cat-custom"></span>
                                {{ $cat->name }}
                                <span class="fd-cat-count">({{ $productCounts[$cat->id] ?? 0 }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- § Store --}}
                    @if(!empty($stores) && $stores->isNotEmpty())
                    <div class="fd-section">
                        <div class="fd-section-label">{{ __('general.store') }}</div>
                        <div class="fd-price-options">
                            <label class="fd-radio-label">
                                <input type="radio" name="store" value="" class="fd-radio"
                                    {{ $storeSlug === '' ? 'checked' : '' }}>
                                <span class="fd-radio-custom"></span>
                                {{ __('general.all_stores') }}
                            </label>
                            @foreach($stores as $store)
                            <label class="fd-radio-label">
                                <input type="radio" name="store" value="{{ $store->slug }}"
                                    class="fd-radio"
                                    {{ $storeSlug === $store->slug ? 'checked' : '' }}>
                                <span class="fd-radio-custom"></span>
                                {{ $store->name }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>{{-- /.fd-body --}}

                {{-- Sticky Footer --}}
                <div class="fd-footer">
                    <a href="{{ route('products.index') . $sortSuffix }}" class="fd-btn-clear">
                        {{ __('general.clear_all') }}
                    </a>
                    <button type="submit" class="fd-btn-apply" data-dusk="apply-filters-btn">
                        {{ __('general.apply_filters') }}
                    </button>
                </div>

            </div>{{-- /.fd-drawer --}}
        </form>
    </div>{{-- /x-data --}}
    @endisset
    {{-- ══ END FILTER UI ══ --}}

    {{-- Results Meta --}}
    <div class="results-meta">
        <p class="results-count">
            <strong>{{ $products->count() }}</strong>
            {{ __('general.results_count') }}
            @if($q) {{ __('general.search_results_for') }} "{{ $q }}" @endif
        </p>
        @if($q || ($categoryId ?? null))
            <a href="{{ route('products.search') }}"
               style="font-size:0.82rem;color:var(--orange);text-decoration:none;">{{ __('general.clear_filters') }}</a>
        @endif
    </div>

    {{-- Results --}}
    @if($products->isEmpty())
        <div class="no-results">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <h3>{{ __('general.no_products_found') }}</h3>
            <p>{{ __('general.no_products_hint') }}</p>
            <a href="{{ route('products.search') }}"
               style="display:inline-block;margin-top:1rem;color:var(--orange);text-decoration:none;font-weight:700;">
                {{ __('general.view_all_products') }}
            </a>
        </div>
    @else
        <div class="products-grid">
            @foreach($products as $product)
            <a href="{{ route('products.show', $product) }}" class="product-card">
                @if($product->image)
                    <img src="{{ asset('images/' . $product->image) }}"
                         alt="{{ $product->name }}" class="card-img">
                @else
                    <div class="card-img-placeholder">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <rect x="3" y="3" width="18" height="18" rx="3"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
                        </svg>
                    </div>
                @endif
                <div class="card-body">
                    @if($product->category)
                        <span class="card-category">{{ $product->category->name }}</span>
                    @endif
                    <p class="card-name">{{ $product->name }}</p>
                    <p class="card-price">₪{{ number_format($product->price, 2) }}</p>
                    @if($product->store)
                        <p class="card-store" style="font-size:0.72rem;color:var(--gray-400);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ __('general.sold_by', ['store' => $product->store->name]) }}
                        </p>
                    @endif
                    <p class="card-stock {{ $product->stock < 1 ? 'out' : '' }}">
                        {{ $product->stock > 0 ? __('general.in_stock_count', ['count' => $product->stock]) : __('general.out_of_stock') }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    @endif

</div>
</x-layout>
