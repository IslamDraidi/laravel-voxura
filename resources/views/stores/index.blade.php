<x-layout title="{{ __('general.browse_stores') }}" mainClass="full-width">

<style>
/* ═══════════════════════════════════════
   STORES PAGE — Custom Styles
═══════════════════════════════════════ */

/* ── Hero ─────────────────────────────────────────── */

/* Hide admin preview banner on stores page and reset nav position */
#previewBanner { display: none !important; visibility: hidden !important; height: 0 !important; overflow: hidden !important; }
.preview-active nav { top: 0 !important; }
html, body { background: #ffffff; }

/* Force navbar visible (white) before scrolling on cream hero */
nav.nav-top { background: #ffffff !important; box-shadow: 0 1px 0 rgba(0,0,0,0.06) !important; }
nav.nav-top .nav-logo      { color: var(--orange) !important; }
nav.nav-top .nav-links a   { color: #374151 !important; }
nav.nav-top .nav-links a:hover { color: var(--orange) !important; }
nav.nav-top .nav-icon-btn  { color: #374151 !important; }
nav.nav-top .nav-icon-btn:hover { color: var(--orange) !important; }
nav.nav-top .nav-user      { color: #374151 !important; }
nav.nav-top .btn-ghost      { color: #374151 !important; border-color: #374151 !important; }
nav.nav-top .hamburger      { color: #374151 !important; }
nav.nav-top .lang-btn       { color: #374151 !important; }
nav.nav-top .nav-cat-btn    { color: #374151 !important; }
nav.nav-top .nav-search-toggle { color: #374151 !important; }
nav.nav-top .nav-badge      { background: var(--orange) !important; color: #fff !important; }

.stores-hero {
    display: flex;
    align-items: stretch;
    min-height: 360px;
    width: 100%;
    overflow: hidden;
    margin-top: 64px;
}
.stores-hero-left {
    width: 44%;
    flex-shrink: 0;
    background: #faf8f4;
    padding: 56px 48px 56px 56px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.stores-hero-left h1 {
    font-family: 'Playfair Display', serif;
    font-size: 38px;
    font-weight: 800;
    color: #1a1a1a;
    line-height: 1.15;
    letter-spacing: -0.5px;
    margin-bottom: 16px;
}
.stores-hero-left p {
    font-size: 15px;
    font-weight: 400;
    color: #555555;
    line-height: 1.7;
    margin-bottom: 32px;
}
.stores-hero-btns {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.stores-hero-right {
    width: 56%;
    overflow: hidden;
    position: relative;
    min-height: 360px;
}
.stores-hero-right::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 220px;
    height: 100%;
    background: linear-gradient(
        to right,
        #faf8f4 0%,
        rgba(250,248,244,0.85) 30%,
        rgba(250,248,244,0.3) 70%,
        transparent 100%
    );
    z-index: 2;
    pointer-events: none;
}
.stores-hero-right img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
}
.stores-hero-right .hero-fallback {
    width: 100%;
    height: 100%;
    min-height: 360px;
    background: linear-gradient(135deg, #2a2a2a 0%, #4a4a4a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ── Shared buttons ───────────────────────────────── */
.btn-orange {
    background: var(--orange);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 13px 28px;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    display: inline-block;
    transition: background .18s;
    cursor: pointer;
}
.btn-orange:hover { background: var(--orange-dark); color: #fff; }

.btn-outline-dark {
    background: transparent;
    color: #1a1a1a;
    border: 2px solid #1a1a1a;
    border-radius: 8px;
    padding: 11px 28px;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: background .18s, color .18s;
}
.btn-outline-dark:hover {
    background: #1a1a1a;
    color: #fff;
}

/* ── Filter Pills ─────────────────────────────────── */
.stores-filters-wrapper {
    background: #ffffff;
    border-bottom: 1px solid #f0ede8;
}
.stores-filters-inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px 32px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
}
.pill, .pill-active {
    border-radius: 50px;
    padding: 8px 18px;
    font-size: 13px;
    white-space: nowrap;
    text-decoration: none;
    display: inline-block;
    transition: border-color .15s, color .15s;
}
.pill {
    background: #ffffff;
    border: 1.5px solid #e0ddd9;
    color: #3a3a3a;
    font-weight: 500;
}
.pill:hover {
    border-color: var(--orange);
    color: var(--orange);
}
.pill-active {
    background: #ffffff;
    border: 1.5px solid var(--orange);
    color: var(--orange);
    font-weight: 600;
}

/* ── Featured Store ───────────────────────────────── */
.stores-featured-wrapper {
    max-width: 1400px;
    margin: 32px auto;
    padding: 0 32px;
}
.stores-featured-card {
    border: 1.5px solid #f0ede8;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    background: #ffffff;
    box-shadow: 0 4px 24px rgba(0,0,0,0.04);
}
.stores-featured-left {
    width: 40%;
    flex-shrink: 0;
    position: relative;
    min-height: 320px;
    background: #e8e4df;
    overflow: hidden;
}
.stores-featured-left img.banner-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    position: absolute;
    inset: 0;
}
.stores-featured-logo {
    position: absolute;
    bottom: 20px;
    left: 20px;
    width: 64px;
    height: 64px;
    border-radius: 50%;
    border: 3px solid #ffffff;
    overflow: hidden;
    background: #1a1a1a;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 20px;
    flex-shrink: 0;
    z-index: 2;
}
.stores-featured-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.stores-featured-right {
    flex: 1;
    padding: 36px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.featured-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--orange);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: block;
}
.stores-featured-right h2 {
    font-size: 32px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 8px;
    font-family: 'Playfair Display', serif;
}
.stores-featured-right > p {
    font-size: 14px;
    color: #888888;
    margin-bottom: 20px;
    line-height: 1.6;
}
.featured-thumbs {
    display: flex;
    gap: 10px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.featured-thumb {
    width: 100px;
    height: 120px;
    border-radius: 10px;
    object-fit: cover;
    background: #f5f5f5;
    display: block;
}
.featured-thumb-placeholder {
    width: 100px;
    height: 120px;
    border-radius: 10px;
    background: #f0ede8;
}

/* ── Store Cards Grid ─────────────────────────────── */
.stores-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    padding: 32px 80px;
    background: #f8f7f5;
    width: 100%;
    box-sizing: border-box;
    align-items: stretch;
}
.store-card {
    background: #ffffff;
    border: 1px solid #f0ede8;
    border-radius: 16px;
    overflow: visible;
    display: flex;
    flex-direction: column;
    position: relative;
    transition: transform 0.2s, box-shadow 0.2s;
}
.store-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.store-card-banner {
    position: relative;
    height: 150px;
    overflow: visible;
}
/* inner wrapper clips the image to the banner area with rounded top corners */
.store-card-banner-inner {
    position: absolute;
    inset: 0;
    background: #e8e4e0;
    overflow: hidden;
    border-radius: 16px 16px 0 0;
}
.store-card-banner-inner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}
.store-card-banner-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.store-card-banner-placeholder svg {
    opacity: .35;
}
.store-card-logo {
    position: absolute;
    bottom: -24px;
    left: 16px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 3px solid #ffffff;
    overflow: hidden;
    background: #1a1a1a;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: 800;
    font-size: 15px;
    font-family: inherit;
    letter-spacing: -0.5px;
    flex-shrink: 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.18);
    z-index: 3;
}
.store-card-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.store-card-body {
    padding: 32px 16px 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
    background: #ffffff;
    border-radius: 0 0 16px 16px;
}
.store-card-body h3 {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 4px;
}
.store-card-tagline {
    font-size: 13px;
    color: #888888;
    margin-bottom: 16px;
    line-height: 1.5;
}
.store-card-thumbs {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
}
.store-card-thumbs img,
.store-card-thumb-ph {
    height: 88px;
    border-radius: 10px;
    object-fit: cover;
    background: #f5f5f5;
    display: block;
    width: 100%;
}
.store-card-thumb-ph {
    background: #f0ede8;
}
.store-card-stats {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 14px;
    margin-bottom: 6px;
}
.store-card-stats-count {
    font-size: 13px;
    font-weight: 600;
    color: #1a1a1a;
}
.badge-3d {
    font-size: 11px;
    font-weight: 600;
    color: var(--orange);
    border: 1.5px solid var(--orange);
    border-radius: 20px;
    padding: 3px 10px;
}
.store-card-cats {
    font-size: 12px;
    color: #aaaaaa;
    margin-bottom: 16px;
}
.btn-visit-store {
    display: block;
    width: 100%;
    background: var(--orange);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 12px;
    font-size: 14px;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    transition: background .18s;
    cursor: pointer;
}
.btn-visit-store:hover { background: var(--orange-dark); color: #fff; }
.powered-by {
    text-align: center;
    font-size: 12px;
    color: #aaaaaa;
    margin-top: 10px;
    margin-bottom: 0;
}
.stores-empty {
    grid-column: span 3;
    text-align: center;
    padding: 80px 20px;
    color: #aaaaaa;
    font-size: 15px;
}

/* ── Why Join ─────────────────────────────────────── */
.why-join {
    background: #ffffff;
    padding: 64px 0;
}
.why-join-inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 32px;
    text-align: center;
}
.why-join-inner h2 {
    font-size: 28px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 48px;
    font-family: 'Playfair Display', serif;
}
.why-join-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
}
.why-join-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.why-join-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    border: 1.5px solid var(--orange);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    flex-shrink: 0;
}
.why-join-icon svg {
    width: 28px;
    height: 28px;
    stroke: var(--orange);
}
.why-join-item h4 {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 10px;
}
.why-join-item p {
    font-size: 13px;
    color: #888888;
    line-height: 1.7;
    margin: 0;
}

/* ── CTA Banner ───────────────────────────────────── */
.stores-cta {
    display: flex;
    align-items: stretch;
    min-height: 280px;
    background: #fef3e8;
    overflow: hidden;
}
.stores-cta-image {
    width: 40%;
    flex-shrink: 0;
    overflow: hidden;
    position: relative;
    background: #f5efe7;
}
.stores-cta-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}
.stores-cta-content {
    flex: 1;
    padding: 64px 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.stores-cta-content h2 {
    font-size: 32px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 12px;
    font-family: 'Playfair Display', serif;
}
.stores-cta-content > p {
    font-size: 15px;
    color: #666666;
    line-height: 1.7;
    margin-bottom: 28px;
    max-width: 480px;
}

/* ── RTL ──────────────────────────────────────────── */
[dir="rtl"] .stores-hero {
    flex-direction: row-reverse;
}
[dir="rtl"] .stores-hero-left {
    padding: 56px 56px 56px 48px;
    text-align: right;
}

[dir="rtl"] .stores-featured-card {
    flex-direction: row-reverse;
}
[dir="rtl"] .stores-featured-logo {
    left: auto;
    right: 20px;
}
[dir="rtl"] .stores-featured-right {
    text-align: right;
}
[dir="rtl"] .store-card-logo {
    left: auto;
    right: 16px;
}
[dir="rtl"] .store-card-body {
    text-align: right;
}
[dir="rtl"] .store-card-stats {
    flex-direction: row-reverse;
}
[dir="rtl"] .stores-cta {
    flex-direction: row-reverse;
}
[dir="rtl"] .stores-cta-content {
    padding: 64px 80px 64px 40px;
    text-align: right;
}
[dir="rtl"] .why-join-grid {
    direction: rtl;
}
[dir="rtl"] .stores-filters-inner {
    flex-direction: row-reverse;
}
[dir="rtl"] .store-card-stats {
    flex-direction: row-reverse;
}
[dir="rtl"] .stores-hero-btns {
    flex-direction: row-reverse;
}

/* ── Responsive ───────────────────────────────────── */
@media (max-width: 1024px) {
    .stores-grid { grid-template-columns: repeat(2, 1fr); padding: 24px 40px; }
    .stores-empty { grid-column: span 2; }
    .why-join-grid { grid-template-columns: repeat(2, 1fr); }
    .stores-hero-left h1 { font-size: 28px; }
    .stores-hero-left { padding: 40px 32px 40px 40px; }
}
@media (max-width: 768px) {
    .stores-hero { flex-direction: column !important; margin-top: 64px; }
    .stores-hero-left { width: 100%; padding: 40px 24px; }
    .stores-hero-right { width: 100%; height: 260px; min-height: 260px; }
    .stores-hero-right img { min-height: 260px; }
    .stores-grid { grid-template-columns: 1fr; padding: 20px 16px; }
    .stores-empty { grid-column: span 1; }
    .why-join-grid { grid-template-columns: 1fr; }
    .stores-featured-card { flex-direction: column !important; }
    .stores-featured-left { width: 100%; min-height: 220px; }
    .stores-cta { flex-direction: column !important; }
    .stores-cta-image { width: 100%; height: 200px; }
    .stores-cta-content { padding: 40px 24px; }
    .stores-filters-inner { padding: 16px 16px; }
    .stores-featured-wrapper { padding: 0 16px; }
}
</style>

{{-- ═══════════════════════════════════════════
     SECTION 1 — HERO
════════════════════════════════════════════ --}}
<section class="stores-hero">
    <div class="stores-hero-left">
        <h1>{{ __('general.stores_hero_title') }}</h1>
        <p>{{ __('general.stores_hero_sub') }}</p>
        <div class="stores-hero-btns">
            <a href="#store-grid" class="btn-orange">
                {{ __('general.browse_stores') }}
            </a>
            <a href="{{ route('partner.apply') }}" class="btn-outline-dark">
                {{ __('general.for_store_owners') }}
            </a>
        </div>
    </div>
    <div class="stores-hero-right">
        @if(file_exists(public_path('images/stores/stores-hero.png')))
            <img src="{{ asset('images/stores/stores-hero.png') }}"
                 alt="{{ __('general.stores_hero_title') }}">
        @elseif(file_exists(public_path('images/hero-background.png')))
            <img src="{{ asset('images/hero-background.png') }}"
                 alt="{{ __('general.stores_hero_title') }}">
        @else
            <div class="hero-fallback"></div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════
     SECTION 2 — FILTER PILLS
════════════════════════════════════════════ --}}
<div class="stores-filters-wrapper">
    <div class="stores-filters-inner">
        <a href="{{ route('stores.index') }}"
           class="{{ !request('filter') ? 'pill-active' : 'pill' }}">
            {{ __('general.filter_all') }}
        </a>
        <a href="{{ route('stores.index', ['filter' => 'new']) }}"
           class="{{ request('filter') === 'new' ? 'pill-active' : 'pill' }}">
            {{ __('general.filter_new') }}
        </a>
        <a href="{{ route('stores.index', ['filter' => 'popular']) }}"
           class="{{ request('filter') === 'popular' ? 'pill-active' : 'pill' }}">
            {{ __('general.filter_popular') }}
        </a>
        <a href="{{ route('stores.index', ['filter' => '3d']) }}"
           class="{{ request('filter') === '3d' ? 'pill-active' : 'pill' }}">
            {{ __('general.filter_3d') }}
        </a>
        <a href="{{ route('stores.index', ['filter' => 'women']) }}"
           class="{{ request('filter') === 'women' ? 'pill-active' : 'pill' }}">
            {{ __('general.filter_women') }}
        </a>
        <a href="{{ route('stores.index', ['filter' => 'men']) }}"
           class="{{ request('filter') === 'men' ? 'pill-active' : 'pill' }}">
            {{ __('general.filter_men') }}
        </a>
        <a href="{{ route('stores.index', ['filter' => 'casual']) }}"
           class="{{ request('filter') === 'casual' ? 'pill-active' : 'pill' }}">
            {{ __('general.filter_casual') }}
        </a>
        <a href="{{ route('stores.index', ['filter' => 'formal']) }}"
           class="{{ request('filter') === 'formal' ? 'pill-active' : 'pill' }}">
            {{ __('general.filter_formal') }}
        </a>
        <a href="{{ route('stores.index', ['filter' => 'shoes']) }}"
           class="{{ request('filter') === 'shoes' ? 'pill-active' : 'pill' }}">
            {{ __('general.filter_shoes') }}
        </a>
        <a href="{{ route('stores.index', ['filter' => 'accessories']) }}"
           class="{{ request('filter') === 'accessories' ? 'pill-active' : 'pill' }}">
            {{ __('general.filter_accessories') }}
        </a>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     SECTION 3 — FEATURED STORE OF THE WEEK
════════════════════════════════════════════ --}}
@if($featured)
<div class="stores-featured-wrapper">
    <div class="stores-featured-card">

        {{-- Left: banner + logo --}}
        <div class="stores-featured-left">
            @if($featured->banner_path && file_exists(public_path($featured->banner_path)))
                <img class="banner-img"
                     src="{{ asset($featured->banner_path) }}"
                     alt="{{ $featured->name }}">
            @else
                <div style="width:100%;height:100%;background:linear-gradient(135deg,#2a2a2a 0%,#444 100%);position:absolute;inset:0;"></div>
            @endif

            <div class="stores-featured-logo">
                @if($featured->logo_path && file_exists(public_path($featured->logo_path)))
                    <img src="{{ asset($featured->logo_path) }}" alt="{{ $featured->name }}">
                @else
                    {{ strtoupper(substr($featured->name, 0, 2)) }}
                @endif
            </div>
        </div>

        {{-- Right: info --}}
        <div class="stores-featured-right">
            <span class="featured-label">{{ __('general.featured_store_week') }}</span>
            <h2>{{ $featured->name }}</h2>
            <p>{{ $featured->description }}</p>

            @if($featured->products->isNotEmpty())
            <div class="featured-thumbs">
                @foreach($featured->products->take(4) as $product)
                    @php $img = $product->images->first(); @endphp
                    @if($img && file_exists(public_path('images/' . $img->image_path)))
                        <img class="featured-thumb"
                             src="{{ asset('images/' . $img->image_path) }}"
                             alt="{{ $product->name }}">
                    @elseif($product->image && file_exists(public_path('images/' . $product->image)))
                        <img class="featured-thumb"
                             src="{{ asset('images/' . $product->image) }}"
                             alt="{{ $product->name }}">
                    @else
                        <div class="featured-thumb-placeholder"></div>
                    @endif
                @endforeach
            </div>
            @endif

            <a href="{{ route('stores.show', $featured) }}" class="btn-orange" style="width:fit-content">
                {{ __('general.visit_store') }} {{ $featured->name }}
            </a>
        </div>

    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════
     SECTION 4 — STORE CARDS GRID
════════════════════════════════════════════ --}}
<div id="store-grid" class="stores-grid">
    @forelse($stores as $store)
    <div class="store-card">

        {{-- Banner --}}
        <div class="store-card-banner">
            <div class="store-card-banner-inner">
                @if($store->banner_path && file_exists(public_path($store->banner_path)))
                    <img src="{{ asset($store->banner_path) }}" alt="{{ $store->name }}">
                @else
                    <div class="store-card-banner-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                             viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </div>
                @endif
            </div>
            {{-- Avatar overlapping banner bottom edge --}}
            <div class="store-card-logo">
                @if($store->logo_path && file_exists(public_path($store->logo_path)))
                    <img src="{{ asset($store->logo_path) }}" alt="{{ $store->name }}">
                @else
                    <span>{{ strtoupper(substr($store->name, 0, 2)) }}</span>
                @endif
            </div>
        </div>

        {{-- Body --}}
        <div class="store-card-body">
            <h3>{{ $store->name }}</h3>
            <p class="store-card-tagline">{{ $store->tagline }}</p>

            {{-- Product thumbnails --}}
            <div class="store-card-thumbs">
                @php $thumbs = $store->products->take(3); @endphp
                @foreach($thumbs as $product)
                    @php $img = $product->images->first(); @endphp
                    @if($img && file_exists(public_path('images/' . $img->image_path)))
                        <img src="{{ asset('images/' . $img->image_path) }}" alt="{{ $product->name }}">
                    @elseif($product->image && file_exists(public_path('images/' . $product->image)))
                        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}">
                    @else
                        <div class="store-card-thumb-ph"></div>
                    @endif
                @endforeach
                @for($i = $thumbs->count(); $i < 3; $i++)
                    <div class="store-card-thumb-ph"></div>
                @endfor
            </div>

            {{-- Stats row --}}
            <div class="store-card-stats">
                <span class="store-card-stats-count">
                    {{ $store->products_count }} {{ __('general.products') }}
                </span>
                @if($store->has_3d_products)
                    <span class="badge-3d">{{ __('general.3d_available') }}</span>
                @endif
            </div>

            {{-- Categories --}}
            @if(!empty($store->category_tags))
            <p class="store-card-cats">
                {{ implode(' • ', $store->category_tags) }}
            </p>
            @endif

            {{-- Visit button --}}
            <a href="{{ route('stores.show', $store) }}" class="btn-visit-store">
                {{ __('general.visit_store') }}
            </a>

            <p class="powered-by">
                {{ __('general.powered_by_voxura') }}
            </p>
        </div>

    </div>
    @empty
        <div class="stores-empty">
            {{ __('general.no_stores_found') }}
        </div>
    @endforelse
</div>

{{-- ═══════════════════════════════════════════
     SECTION 5 — WHY STORES JOIN VOXURA
════════════════════════════════════════════ --}}
<section class="why-join">
    <div class="why-join-inner">
        <h2>{{ __('general.why_join_voxura') }}</h2>
        <div class="why-join-grid">

            {{-- Branded store page --}}
            <div class="why-join-item">
                <div class="why-join-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <h4>{{ __('general.branded_store_page') }}</h4>
                <p>Get your own branded store page with custom design and your identity at the centre.</p>
            </div>

            {{-- 3D Visualization --}}
            <div class="why-join-item">
                <div class="why-join-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                <h4>{{ __('general.visualization_3d') }}</h4>
                <p>Showcase products with immersive 3D views that build trust and boost conversions.</p>
            </div>

            {{-- Checkout & Orders --}}
            <div class="why-join-item">
                <div class="why-join-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        <path d="m9 11 2 2 4-4"/>
                    </svg>
                </div>
                <h4>{{ __('general.checkout_management') }}</h4>
                <p>Simplify sales with secure checkout, order tracking, and easy management tools.</p>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     SECTION 6 — CTA BANNER
════════════════════════════════════════════ --}}
<section class="stores-cta">
    <div class="stores-cta-image">
        @if(file_exists(public_path('images/stores/cta-illustration.jpg')))
            <img src="{{ asset('images/stores/cta-illustration.jpg') }}" alt="Join Voxura">
        @elseif(file_exists(public_path('images/stores/cta-illustration.png')))
            <img src="{{ asset('images/stores/cta-illustration.png') }}" alt="Join Voxura">
        @endif
    </div>
    <div class="stores-cta-content">
        <h2>{{ __('general.want_store_on_voxura') }}</h2>
        <p>Give your clothing store a modern online presence with product pages, 3D visualization, checkout, and order management.</p>
        <a href="{{ route('partner.apply') }}" class="btn-orange" style="width:fit-content">
            {{ __('general.apply_to_join') }}
        </a>
    </div>
</section>

{{-- ═══════════════════════════════════════════
     SECTION 7 — CONTACT (copied from homepage)
════════════════════════════════════════════ --}}
<x-contact />

</x-layout>
