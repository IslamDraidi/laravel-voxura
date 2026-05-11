@props([
    'products',
    'activeCategory'  => null,
    'categories'      => null,
    'sort'            => 'newest',
    'sizes'           => [],
    'colors'          => [],
    'priceRange'      => '',
    'categoryIds'     => [],
    'availableSizes'  => null,
    'availableColors' => null,
    'productCounts'   => null,
])

@php
    $categories      = $categories      ?? collect();
    $availableSizes  = $availableSizes  ?? collect(['XS','S','M','L','XL','XXL']);
    $availableColors = $availableColors ?? collect();
    $productCounts   = $productCounts   ?? collect();
    $sizes           = $sizes           ?? [];
    $colors          = $colors          ?? [];
    $priceRange      = $priceRange      ?? '';
    $categoryIds     = $categoryIds     ?? [];

    $filterCount = count($sizes) + count($colors) + count($categoryIds) + ($priceRange !== '' ? 1 : 0);
    $hasActive   = $filterCount > 0;

    $currParams = array_filter(
        request()->only(['sort','size','color','price_range','category']),
        fn($v) => $v !== '' && $v !== [] && $v !== null
    );

    $rmFilter = function(string $key, ?string $val = null) use ($currParams): string {
        $p = $currParams;
        if ($val === null) {
            unset($p[$key]);
        } else {
            $arr = array_values(array_filter((array) ($p[$key] ?? []), fn($x) => $x !== $val));
            if ($arr) $p[$key] = $arr; else unset($p[$key]);
        }
        $base = url('/') . '#products';
        return url('/') . ($p ? '?' . http_build_query($p) : '') . '#products';
    };

    $priceLabel = match($priceRange) {
        'under_50' => __('general.price_under_50'),
        '50_150'   => __('general.price_50_150'),
        '150_300'  => __('general.price_150_300'),
        '300_plus' => __('general.price_300_plus'),
        default    => '',
    };

    $sortSuffix = ($sort !== 'newest') ? '?sort=' . $sort : '';
@endphp

<style>
/* ════════════════════════════════════════════════════════
   PRODUCT GRID SECTION
   ════════════════════════════════════════════════════════ */
.product-grid-section { background: #ffffff; padding: 4rem 0 5rem; }
.pg-container { width: 100%; padding: 0 1.5rem; }
.pg-header { text-align: center; margin-bottom: 2rem; }
.pg-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 800;
    color: var(--gray-900, #111827); letter-spacing: -0.03em; margin-bottom: 0.4rem;
}
.pg-title span { color: var(--orange, #ea580c); }
.pg-subtitle { color: var(--gray-500, #6b7280); font-size: 0.95rem; }
.pg-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}
@media (max-width: 1024px) { .pg-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px)  { .pg-grid { grid-template-columns: repeat(2, 1fr); } }

/* ════════════════════════════════════════════════════════
   FILTER DRAWER
   ════════════════════════════════════════════════════════ */

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
.fd-header-divider { height: 2px; background: var(--orange, #ea580c); margin: 0 1.5rem; flex-shrink: 0; }

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
    border-color: var(--orange, #ea580c); background: var(--orange, #ea580c);
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
.fd-chip-input:checked + .fd-chip { border-color: var(--orange, #ea580c); color: var(--orange, #ea580c); }

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
    border-color: var(--orange, #ea580c);
    outline: 2px solid var(--orange, #ea580c); outline-offset: 2px;
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
.fd-cat-input:checked + .fd-cat-custom { border-color: var(--orange, #ea580c); background: var(--orange, #ea580c); }
.fd-cat-input:checked + .fd-cat-custom::after {
    content: '';
    display: block; width: 4px; height: 7px;
    border: 2px solid #fff; border-top: none; border-left: none;
    transform: rotate(45deg) translate(0, -1px);
}
.fd-cat-label:has(.fd-cat-input:checked) { color: #fff; }
.fd-cat-count { margin-left: auto; font-size: 0.74rem; color: rgba(255,255,255,0.28); }
[dir="rtl"] .fd-cat-count { margin-left: 0; margin-right: auto; }

/* Drawer footer */
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
    background: var(--orange, #ea580c); color: #fff; border: none; border-radius: 999px;
    padding: 0.55rem 1.35rem; font-size: 0.88rem; font-weight: 700;
    font-family: 'DM Sans', sans-serif; cursor: pointer;
    transition: background 0.15s; flex-shrink: 0;
}
.fd-btn-apply:hover { background: var(--orange-dark, #c2410c); }

@media (max-width: 768px) {
    .fd-drawer { width: 85vw; }
    .fd-trigger-right { gap: 0.5rem; }
}
</style>

<section id="products" class="product-grid-section">
<div class="pg-container">

    <div class="pg-header">
        @if($activeCategory)
            <h2 class="pg-title"><span>{{ $activeCategory->name }}</span></h2>
            <p class="pg-subtitle">
                {{ $products->count() }} {{ __('general.results_count') }}
                &nbsp;·&nbsp;
                <a href="/#products" style="color:var(--orange,#ea580c);text-decoration:none;font-weight:600;">{{ __('general.view_all_products') }} ×</a>
            </p>
        @else
            <h2 class="pg-title">{{ __('general.our') }} <span>{{ __('general.collection') }}</span></h2>
            <p class="pg-subtitle">{{ __('general.collection_subtitle') }}</p>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════
         FILTER DRAWER
         ══════════════════════════════════════════════════════ --}}
    <div x-data="{ drawerOpen: false, touchStartX: 0 }">

        {{-- Overlay --}}
        <div
            class="fd-overlay"
            x-show="drawerOpen"
            x-transition.opacity
            @click="drawerOpen = false"
            style="display:none"
        ></div>

        <form method="GET" action="{{ url('/') }}" id="fd-form-home"
              @submit.prevent="$el.action = '/' + (document.getElementById('products') ? '#products' : ''); $el.submit()">

            {{-- Trigger Bar --}}
            <div class="fd-trigger-wrap">
                <div class="fd-trigger-bar">
                    <div class="fd-trigger-left">
                        <button
                            type="button"
                            class="fd-filter-btn {{ $hasActive ? 'fd-filter-btn--active' : '' }}"
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
                            <option value="newest"     {{ $sort === 'newest'     ? 'selected' : '' }}>{{ __('general.sort_newest') }}</option>
                            <option value="price_asc"  {{ $sort === 'price_asc'  ? 'selected' : '' }}>{{ __('general.sort_price_asc') }}</option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>{{ __('general.sort_price_desc') }}</option>
                            <option value="popular"    {{ $sort === 'popular'    ? 'selected' : '' }}>{{ __('general.sort_popular') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Active filter tags --}}
            @if($hasActive)
            <div class="fd-active-tags">
                @foreach($sizes as $s)
                    <a href="{{ $rmFilter('size', $s) }}" class="fd-active-tag">
                        {{ __('general.size') }}: {{ $s }}
                        <span class="fd-active-tag-x">&times;</span>
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
                <a href="{{ url('/') . ($sortSuffix ? '?' . ltrim($sortSuffix, '?') : '') . '#products' }}" class="fd-active-tag fd-active-tag--clear">
                    {{ __('general.clear_all') }}
                </a>
            </div>
            @endif

            {{-- Drawer Panel --}}
            <div
                class="fd-drawer"
                :class="{ 'fd-drawer--open': drawerOpen }"
                @touchstart.passive="touchStartX = $event.touches[0].clientX"
                @touchmove.passive="
                    const dx = $event.touches[0].clientX - touchStartX;
                    const rtl = document.documentElement.dir === 'rtl';
                    if (rtl ? dx > 60 : dx < -60) drawerOpen = false;
                "
            >
                <div class="fd-header">
                    <span class="fd-title">{{ __('general.filter_title') }}</span>
                    <button type="button" class="fd-close" @click="drawerOpen = false"
                            aria-label="{{ __('general.close') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.5" stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="fd-header-divider"></div>

                <div class="fd-body">

                    {{-- Price Range --}}
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
                                <input type="radio" name="price_range" value="{{ $val }}"
                                       class="fd-radio" {{ $priceRange === $val ? 'checked' : '' }}>
                                <span class="fd-radio-custom"></span>
                                {{ $label }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Size --}}
                    <div class="fd-section">
                        <div class="fd-section-label">{{ __('general.size') }}</div>
                        <div class="fd-chips">
                            @foreach($availableSizes as $size)
                            <span style="position:relative">
                                <input type="checkbox" name="size[]" value="{{ $size }}"
                                       id="pgfd-s-{{ Str::slug($size) }}"
                                       class="fd-chip-input"
                                       {{ in_array($size, $sizes) ? 'checked' : '' }}>
                                <label for="pgfd-s-{{ Str::slug($size) }}" class="fd-chip">{{ $size }}</label>
                            </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Color --}}
                    @if($availableColors->isNotEmpty())
                    <div class="fd-section">
                        <div class="fd-section-label">{{ __('general.color') }}</div>
                        <div class="fd-colors">
                            @foreach($availableColors as $color)
                            <span class="fd-color-wrap">
                                <input type="checkbox" name="color[]" value="{{ $color }}"
                                       id="pgfd-c-{{ Str::slug($color) }}"
                                       class="fd-color-input"
                                       {{ in_array($color, $colors) ? 'checked' : '' }}>
                                <label for="pgfd-c-{{ Str::slug($color) }}" class="fd-color-swatch"
                                       style="background-color:{{ strtolower($color) }}" title="{{ $color }}">
                                    <svg class="fd-color-check" viewBox="0 0 12 12" fill="none"
                                         stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="2,6 5,9 10,3"/>
                                    </svg>
                                </label>
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Category --}}
                    <div class="fd-section">
                        <div class="fd-section-label">{{ __('general.category') }}</div>
                        <div class="fd-cats">
                            @foreach($categories as $cat)
                            <label class="fd-cat-label">
                                <input type="checkbox" name="category[]" value="{{ $cat->id }}"
                                       class="fd-cat-input"
                                       {{ in_array((string)$cat->id, array_map('strval', $categoryIds)) ? 'checked' : '' }}>
                                <span class="fd-cat-custom"></span>
                                {{ $cat->name }}
                                <span class="fd-cat-count">({{ $productCounts[$cat->id] ?? 0 }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                </div>{{-- /.fd-body --}}

                <div class="fd-footer">
                    <a href="{{ url('/') . '#products' }}" class="fd-btn-clear">{{ __('general.clear_all') }}</a>
                    <button type="submit" class="fd-btn-apply">{{ __('general.apply_filters') }}</button>
                </div>

            </div>{{-- /.fd-drawer --}}
        </form>
    </div>{{-- /x-data --}}

    {{-- Product Grid --}}
    @if($products->isEmpty())
        <div style="text-align:center;padding:4rem 1rem;color:#6b7280;">
            <p style="font-size:1.1rem;">{{ __('general.no_products_found') }}</p>
            <a href="{{ url('/') . '#products' }}" style="display:inline-block;margin-top:1rem;color:var(--orange,#ea580c);font-weight:600;text-decoration:none;">← {{ __('general.view_all_products') }}</a>
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

<script>
// Scroll to #products after filter form submit so user lands on the grid
(function () {
    var form = document.getElementById('fd-form-home');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var params = new URLSearchParams(new FormData(form));
            // Remove empty values
            for (var key of [...params.keys()]) {
                if (!params.get(key)) params.delete(key);
            }
            window.location.href = '/' + (params.toString() ? '?' + params.toString() : '') + '#products';
        });
    }
    // On load: if filter params present, scroll to products
    if (window.location.search && window.location.hash !== '#products') {
        var el = document.getElementById('products');
        if (el) setTimeout(function() { el.scrollIntoView({ behavior: 'smooth' }); }, 100);
    }
})();
</script>
