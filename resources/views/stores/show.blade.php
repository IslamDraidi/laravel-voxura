<x-layout :title="$store->name" mainClass="full-width">

<style>
/* ═══════════════════════════════════════
   STORE SHOW PAGE — Custom Styles
═══════════════════════════════════════ */

/* ── Nav override ─────────────────────────────────── */
html, body { background: #f8f7f5; }
nav.nav-top { background: #ffffff !important; box-shadow: 0 1px 0 rgba(0,0,0,0.06) !important; }
nav.nav-top .nav-logo      { color: var(--orange) !important; }
nav.nav-top .nav-links a   { color: #374151 !important; }
nav.nav-top .nav-links a:hover { color: var(--orange) !important; }
nav.nav-top .nav-icon-btn  { color: #374151 !important; }
nav.nav-top .nav-icon-btn:hover { color: var(--orange) !important; }
nav.nav-top .nav-badge     { background: var(--orange) !important; color: #fff !important; }
nav.nav-top .lang-btn      { color: #374151 !important; }

/* ── Hero Banner ──────────────────────────────────── */
.store-hero {
    width: 100%;
    height: 300px;
    position: relative;
    overflow: hidden;
    background: #1a1a1a;
    margin-top: 64px;
}
.store-hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    opacity: 0.75;
    display: block;
}
.store-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.3) 60%, rgba(0,0,0,0.1) 100%);
}
.store-hero-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 28px 48px;
    display: flex;
    align-items: flex-end;
    gap: 24px;
}
.store-hero-logo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid #ffffff;
    overflow: hidden;
    background: #1a1a1a;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: 32px;
    flex-shrink: 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}
.store-hero-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.store-hero-text {
    flex: 1;
    color: #ffffff;
}
.store-hero-name {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 4px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.3);
}
.store-hero-tagline {
    font-size: 15px;
    color: rgba(255,255,255,0.85);
    margin-bottom: 14px;
}
.store-hero-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 12px;
}
.badge-powered {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(234,88,12,0.15);
    border: 1.5px solid var(--orange);
    border-radius: 8px;
    padding: 7px 14px;
    color: var(--orange);
    font-size: 13px;
    font-weight: 600;
}
.badge-3d-store {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.1);
    border: 1.5px solid rgba(255,255,255,0.4);
    border-radius: 8px;
    padding: 7px 14px;
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
}
.store-hero-btns {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.btn-hero-primary {
    background: var(--orange);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    cursor: pointer;
    transition: background .18s;
}
.btn-hero-primary:hover { background: var(--orange-dark); color: #fff; }
.btn-hero-ghost {
    background: rgba(255,255,255,0.1);
    color: #ffffff;
    border: 1.5px solid rgba(255,255,255,0.4);
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    cursor: pointer;
    transition: background .18s;
}
.btn-hero-ghost:hover { background: rgba(255,255,255,0.18); color: #fff; }

/* ── Store Info Bar ───────────────────────────────── */
.store-info-bar {
    background: #ffffff;
    border: 1px solid #f0ede8;
    border-radius: 16px;
    margin: 24px 48px 0;
    padding: 24px 32px;
    display: flex;
    align-items: flex-start;
    gap: 32px;
}
.store-info-left {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    flex: 1;
}
.store-info-logo {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    border: 3px solid #f0ede8;
    overflow: hidden;
    background: #1a1a1a;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: 20px;
    flex-shrink: 0;
}
.store-info-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.store-info-name {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 4px;
}
.store-info-desc {
    font-size: 13px;
    color: #666666;
    line-height: 1.6;
    max-width: 400px;
    margin-bottom: 12px;
}
.store-info-cats {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.category-tag {
    background: #f8f7f5;
    border: 1px solid #e0ddd9;
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 12px;
    color: #555555;
    font-weight: 500;
}
.store-info-stats {
    display: flex;
    align-items: center;
    gap: 0;
    flex-shrink: 0;
}
.stat-col {
    text-align: center;
    padding: 0 28px;
    border-right: 1px solid #f0ede8;
}
.stat-col:last-child { border-right: none; }
.stat-num {
    font-size: 22px;
    font-weight: 800;
    color: #1a1a1a;
    display: block;
}
.stat-label {
    font-size: 12px;
    color: #888888;
    display: block;
}
.stat-star { color: var(--orange); }
.store-info-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
    flex-shrink: 0;
}
.btn-follow {
    background: var(--orange);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 10px 22px;
    font-size: 14px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: background .18s;
}
.btn-follow:hover { background: var(--orange-dark); }
.followers-text {
    font-size: 13px;
    color: #888888;
    text-align: right;
}
.store-transparency {
    background: #fdf5ef;
    border: 1px solid #fad4c0;
    border-radius: 10px;
    margin: 14px 48px 24px;
    padding: 11px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #666666;
}

/* ── Featured Collection ──────────────────────────── */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 48px;
    margin-bottom: 18px;
}
.section-title {
    font-size: 20px;
    font-weight: 800;
    color: #1a1a1a;
}
.link-view-all {
    color: var(--orange);
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
}
.link-view-all:hover { text-decoration: underline; }
.featured-row {
    display: flex;
    gap: 14px;
    padding: 0 48px;
    overflow-x: auto;
    margin-bottom: 36px;
    scrollbar-width: none;
}
.featured-row::-webkit-scrollbar { display: none; }
.feat-card {
    flex: 0 0 200px;
    height: 320px;
    border-radius: 14px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    background: #e8e4df;
    flex-shrink: 0;
    text-decoration: none;
    display: block;
}
.feat-card:first-child { flex: 0 0 250px; }
.feat-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.feat-card-ph {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #e8e4df 0%, #d4cfc9 100%);
}
.feat-badge-3d {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: var(--orange);
    color: #ffffff;
    border-radius: 20px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 4px;
}
.feat-heart {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* ── Filter Tabs + Sort ───────────────────────────── */
.filter-sort-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 48px 0;
    border-bottom: 1px solid #f0ede8;
    background: #ffffff;
}
.filter-tabs {
    display: flex;
    overflow-x: auto;
    scrollbar-width: none;
}
.filter-tabs::-webkit-scrollbar { display: none; }
.filter-tab {
    padding: 12px 16px 14px;
    font-size: 14px;
    font-weight: 500;
    color: #888888;
    text-decoration: none;
    white-space: nowrap;
    border-bottom: 2px solid transparent;
    transition: color .15s, border-color .15s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.filter-tab:hover { color: var(--orange); }
.filter-tab.active {
    color: var(--orange);
    border-bottom-color: var(--orange);
    font-weight: 600;
}
.sort-select {
    background: #ffffff;
    border: 1px solid #e0ddd9;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 13px;
    color: #555555;
    cursor: pointer;
    flex-shrink: 0;
    margin-left: 16px;
}

/* ── Product Grid ─────────────────────────────────── */
.product-grid-wrap {
    padding: 28px 48px 0;
    background: #f8f7f5;
}
.product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 32px;
}
.prod-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
}
.prod-card-img-wrap {
    position: relative;
    height: 260px;
    background: #f5f4f2;
    overflow: hidden;
    border-radius: 12px 12px 0 0;
}
.prod-card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}
.prod-card:hover .prod-card-img-wrap img { transform: scale(1.04); }
.prod-img-ph {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #e8e4df 0%, #d4cfc9 100%);
}
.prod-badge-3d {
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
.prod-heart {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.prod-card-body { padding: 12px 12px 12px; }
.prod-name {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 4px;
}
.prod-price {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 3px;
}
.prod-sold-by {
    font-size: 12px;
    color: #888888;
    margin-bottom: 10px;
}
.prod-btns { display: flex; gap: 6px; }
.btn-view-prod {
    flex: 1;
    background: #ffffff;
    border: 1.5px solid #e0ddd9;
    border-radius: 8px;
    padding: 8px;
    font-size: 12px;
    font-weight: 600;
    color: #333333;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    display: block;
    transition: border-color .15s;
}
.btn-view-prod:hover { border-color: #aaa; color: #111; }
.btn-add-cart {
    flex: 1;
    background: var(--orange);
    border: none;
    border-radius: 8px;
    padding: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #ffffff;
    cursor: pointer;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    transition: background .18s;
}
.btn-add-cart:hover { background: var(--orange-dark); }
.prod-empty {
    grid-column: span 4;
    text-align: center;
    padding: 60px;
    color: #aaaaaa;
    font-size: 15px;
}

/* ── Load More ────────────────────────────────────── */
.load-more-wrap {
    text-align: center;
    padding: 0 48px 40px;
    background: #f8f7f5;
}
.btn-load-more {
    background: #ffffff;
    border: 1.5px solid #e0ddd9;
    border-radius: 10px;
    padding: 13px 44px;
    font-size: 14px;
    font-weight: 600;
    color: #555555;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: border-color .15s;
}
.btn-load-more:hover { border-color: #aaa; color: #333; }
.load-more-count {
    font-size: 13px;
    color: #aaaaaa;
    margin-top: 10px;
}

/* ── 3D Section Banner ────────────────────────────── */
.section-3d {
    margin: 0 48px 36px;
    background: #fdf5ef;
    border: 1px solid #fad4c0;
    border-radius: 16px;
    padding: 32px 36px;
    display: flex;
    align-items: center;
    gap: 40px;
}
.section-3d-left { flex: 0 0 35%; }
.section-3d-icon-wrap {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(234,88,12,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
}
.section-3d-title {
    font-size: 22px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 10px;
    line-height: 1.2;
}
.section-3d-title span { color: var(--orange); }
.section-3d-sub {
    font-size: 13px;
    color: #666666;
    margin-bottom: 18px;
    line-height: 1.6;
}
.section-3d-sub strong { color: var(--orange); font-weight: 600; }
.section-3d-right {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}
.feat-col { text-align: center; }
.feat-col-icon {
    font-size: 32px;
    color: var(--orange);
    display: block;
    margin-bottom: 8px;
}
.feat-col h4 {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 6px;
}
.feat-col p {
    font-size: 13px;
    color: #666666;
    line-height: 1.6;
    margin: 0;
}

/* ── About Section ────────────────────────────────── */
.section-about {
    margin: 0 48px 36px;
    background: #ffffff;
    border: 1px solid #f0ede8;
    border-radius: 16px;
    padding: 32px 36px;
}
.section-about h2 {
    font-size: 20px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 24px;
}
.about-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    align-items: start;
}
.about-col h4 {
    font-size: 14px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.about-col h4 i { color: var(--orange); }
.about-col p {
    font-size: 13px;
    color: #666666;
    line-height: 1.6;
    margin-bottom: 14px;
}
.about-features {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-top: 14px;
}
.about-feat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: #666666;
}
.about-feat-item i { font-size: 14px; color: #888; }
.contact-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #555555;
    margin-bottom: 10px;
}
.contact-row i { color: var(--orange); font-size: 15px; }
.link-map {
    color: var(--orange);
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
}
.social-icons {
    display: flex;
    gap: 10px;
    margin-bottom: 18px;
}
.social-icon-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f8f7f5;
    border: 1px solid #e0ddd9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #555555;
    font-size: 17px;
    text-decoration: none;
    transition: background .15s;
}
.social-icon-btn:hover { background: #f0ede8; }
.care-link {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: #555555;
    margin-bottom: 8px;
    text-decoration: none;
}
.care-link:hover { color: var(--orange); }
.powered-box {
    background: #fdf5ef;
    border: 1px solid #fad4c0;
    border-radius: 10px;
    padding: 14px;
    margin-top: 14px;
}
.powered-box-title {
    font-weight: 700;
    color: var(--orange);
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}
.powered-box-sub {
    font-size: 12px;
    color: #555555;
    line-height: 1.5;
    margin-bottom: 8px;
}
.powered-box-link {
    font-size: 12px;
    color: var(--orange);
    font-weight: 600;
    text-decoration: none;
}

/* ── Trust Bar ────────────────────────────────────── */
.trust-bar {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    margin: 0 48px 36px;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #f0ede8;
}
.trust-bar-cell {
    padding: 22px 28px;
    display: flex;
    align-items: center;
    gap: 16px;
    border-right: 1px solid #f0ede8;
}
.trust-bar-cell:last-child { border-right: none; }
.trust-icon { font-size: 30px; color: var(--orange); flex-shrink: 0; }
.trust-label {
    font-size: 14px;
    font-weight: 700;
    color: #1a1a1a;
    display: block;
    margin-bottom: 2px;
}
.trust-sub { font-size: 12px; color: #888888; display: block; }

/* ── Similar Stores ───────────────────────────────── */
.similar-stores-wrap {
    padding: 0 48px 36px;
    background: #f8f7f5;
}
.similar-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}
.sim-card {
    background: #ffffff;
    border: 1px solid #f0ede8;
    border-radius: 14px;
    overflow: hidden;
}
.sim-card-banner {
    position: relative;
    height: 180px;
    background: #e8e4df;
    overflow: hidden;
}
.sim-card-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.sim-card-logo {
    position: absolute;
    bottom: -32px;
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
    font-weight: 800;
    font-size: 18px;
    z-index: 2;
}
.sim-card-logo img { width: 100%; height: 100%; object-fit: cover; }
.sim-card-body { padding: 44px 16px 16px; }
.sim-card-name {
    font-size: 16px;
    font-weight: 700;
    color: #1a1a1a;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}
.sim-card-name i { color: var(--orange); font-size: 16px; }
.sim-card-tagline { font-size: 12px; color: #888888; margin-bottom: 12px; }
.sim-card-btns { display: flex; gap: 8px; }
.btn-sim-follow {
    background: var(--orange);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 18px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: background .18s;
}
.btn-sim-follow:hover { background: var(--orange-dark); color: #fff; }
.btn-sim-visit {
    background: #ffffff;
    border: 1.5px solid #e0ddd9;
    border-radius: 8px;
    padding: 8px 18px;
    font-size: 13px;
    font-weight: 600;
    color: #333333;
    text-decoration: none;
    transition: border-color .15s;
}
.btn-sim-visit:hover { border-color: #aaa; color: #111; }
.sim-powered {
    font-size: 11px;
    color: #aaaaaa;
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 4px;
}
.sim-powered i { color: var(--orange); font-size: 12px; }

/* ── CTA Banner ───────────────────────────────────── */
.store-cta {
    margin: 0 48px 40px;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    min-height: 220px;
    background: #1a1a1a;
    display: flex;
    align-items: center;
}
.store-cta-bg {
    position: absolute;
    inset: 0;
    object-fit: cover;
    width: 100%;
    height: 100%;
    opacity: 0.4;
    display: block;
}
.store-cta-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.55);
}
.store-cta-content {
    position: relative;
    z-index: 2;
    padding: 48px 56px;
    max-width: 55%;
}
.store-cta-content h2 {
    font-size: 28px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
}
.store-cta-content p {
    font-size: 14px;
    color: rgba(255,255,255,0.75);
    line-height: 1.7;
    margin-bottom: 24px;
}
.btn-cta-apply {
    background: var(--orange);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 13px 26px;
    font-size: 14px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    cursor: pointer;
    transition: background .18s;
}
.btn-cta-apply:hover { background: var(--orange-dark); color: #fff; }

/* ── RTL ──────────────────────────────────────────── */
[dir="rtl"] .store-hero-content   { flex-direction: row-reverse; }
[dir="rtl"] .store-hero-text      { text-align: right; }
[dir="rtl"] .store-hero-badges    { flex-direction: row-reverse; }
[dir="rtl"] .store-hero-btns      { flex-direction: row-reverse; }
[dir="rtl"] .store-info-bar       { flex-direction: row-reverse; }
[dir="rtl"] .store-info-left      { flex-direction: row-reverse; }
[dir="rtl"] .store-info-right     { align-items: flex-start; }
[dir="rtl"] .store-info-cats      { flex-direction: row-reverse; }
[dir="rtl"] .filter-sort-bar      { flex-direction: row-reverse; }
[dir="rtl"] .section-header       { flex-direction: row-reverse; }
[dir="rtl"] .product-grid         { direction: rtl; }
[dir="rtl"] .prod-badge-3d        { left: auto; right: 10px; }
[dir="rtl"] .prod-heart           { right: auto; left: 10px; }
[dir="rtl"] .feat-badge-3d        { left: auto; right: 12px; }
[dir="rtl"] .feat-heart           { right: auto; left: 12px; }
[dir="rtl"] .about-grid           { direction: rtl; }
[dir="rtl"] .section-3d           { flex-direction: row-reverse; }
[dir="rtl"] .section-3d-left      { text-align: right; }
[dir="rtl"] .section-3d-right     { direction: rtl; }
[dir="rtl"] .sim-card-logo        { left: auto; right: 20px; }
[dir="rtl"] .sim-card-name        { flex-direction: row-reverse; }
[dir="rtl"] .trust-bar-cell       { flex-direction: row-reverse; border-right: none; border-left: 1px solid #f0ede8; }
[dir="rtl"] .trust-bar-cell:last-child { border-left: none; }
[dir="rtl"] .store-contact-card  { direction: rtl; }
[dir="rtl"] .store-contact-info-row { flex-direction: row-reverse; }
[dir="rtl"] .store-contact-input { text-align: right; direction: rtl; }
[dir="rtl"] .store-cta-content    { margin-left: auto; margin-right: 0; text-align: right; }

/* ── Responsive ───────────────────────────────────── */
@media (max-width: 1200px) {
    .product-grid { grid-template-columns: repeat(3, 1fr); }
    .prod-empty   { grid-column: span 3; }
    .about-grid   { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 1024px) {
    .section-3d       { flex-direction: column; gap: 24px; }
    .section-3d-left  { flex: none; width: 100%; }
    .similar-grid     { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .store-hero           { height: auto; min-height: 240px; }
    .store-hero-content   { padding: 20px; flex-direction: column; align-items: flex-start; }
    .store-hero-logo      { width: 80px; height: 80px; font-size: 22px; }
    .store-hero-name      { font-size: 22px; }
    .store-info-bar       { flex-direction: column; margin: 16px 16px 0; padding: 20px; }
    .store-info-stats     { width: 100%; overflow-x: auto; }
    .store-transparency   { margin: 12px 16px 16px; }
    .section-header       { padding: 0 16px; }
    .featured-row         { padding: 0 16px; }
    .filter-sort-bar      { padding: 0 16px; flex-wrap: wrap; gap: 8px; }
    .sort-select          { margin-left: 0; width: 100%; }
    .product-grid-wrap    { padding: 16px 16px 0; }
    .product-grid         { grid-template-columns: repeat(2, 1fr); }
    .prod-empty           { grid-column: span 2; }
    .load-more-wrap       { padding: 0 16px 28px; }
    .section-3d           { margin: 0 16px 24px; padding: 24px 20px; }
    .section-3d-right     { grid-template-columns: 1fr; }
    .section-about        { margin: 0 16px 24px; padding: 24px 20px; }
    .about-grid           { grid-template-columns: 1fr; }
    .trust-bar            { margin: 0 16px 24px; }
    .trust-bar-cell       { padding: 16px 20px; }
    .similar-stores-wrap  { padding: 0 16px 28px; }
    .similar-grid         { grid-template-columns: 1fr; }
    .store-cta            { margin: 0 16px 28px; }
    .store-cta-content    { padding: 32px 24px; max-width: 100%; }
    .store-contact-card   { margin: 0 16px 32px !important; padding: 32px 24px !important; grid-template-columns: 1fr !important; gap: 32px !important; }
}

/* ── Contact Form (dark style) ────────────────────── */
.store-contact-input::placeholder {
    color: rgba(255,255,255,0.3);
}
.store-contact-input:focus {
    border-color: #E8621A !important;
    background: rgba(255,255,255,0.07) !important;
    outline: none;
}
.store-contact-btn:hover {
    opacity: 0.92;
    transform: translateY(-1px);
}
.store-contact-social-link:hover {
    background: rgba(232,98,26,0.12) !important;
    border-color: rgba(232,98,26,0.3) !important;
    color: #E8621A !important;
}
</style>

{{-- ═══════════════════════════════════════════
     SECTION 1 — STORE HERO BANNER
════════════════════════════════════════════ --}}
<div class="store-hero">
    @if($store->banner_path && file_exists(public_path($store->banner_path)))
        <img class="store-hero-img"
             src="{{ asset($store->banner_path) }}"
             alt="{{ $store->name }}">
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
            <div class="store-hero-name">{{ $store->name }}</div>
            <div class="store-hero-tagline">{{ $store->tagline }}</div>

            <div class="store-hero-badges">
                <span class="badge-powered">
                    <i class="ti ti-bolt"></i>
                    {{ __('general.powered_by_voxura') }}
                </span>
                @if($store->has_3d_products)
                <span class="badge-3d-store">
                    <i class="ti ti-cube-3d-sphere"></i>
                    {{ __('general.3d_enabled_store') }}
                </span>
                @endif
            </div>

            <div class="store-hero-btns">
                <a href="#product-grid" class="btn-hero-primary">
                    <i class="ti ti-shopping-bag"></i>
                    {{ __('general.shop_products') }}
                </a>
                @if($store->has_3d_products)
                <a href="{{ route('stores.show', [$store, 'type' => '3d']) }}#product-grid"
                   class="btn-hero-ghost">
                    <i class="ti ti-cube-3d-sphere"></i>
                    {{ __('general.explore_3d_items') }}
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     SECTION 2 — STORE INFO BAR
════════════════════════════════════════════ --}}
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
            <div class="store-info-name">{{ $store->name }}</div>
            <p class="store-info-desc">{{ $store->description }}</p>
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
            <span class="stat-label">{{ __('general.products') }}</span>
        </div>
        <div class="stat-col">
            <span class="stat-num">{{ $total3DProducts }}</span>
            <span class="stat-label">3D Products</span>
        </div>
        <div class="stat-col">
            <span class="stat-num"><span class="stat-star">★</span> 4.8</span>
            <span class="stat-label">128 reviews</span>
        </div>
    </div>

    <div class="store-info-right">
        <button class="btn-follow">
            <i class="ti ti-plus"></i>
            {{ __('general.follow_store') }}
        </button>
        <span class="followers-text">{{ __('general.followers', ['count' => '2.1K']) }}</span>
    </div>
</div>

<div class="store-transparency">
    <i class="ti ti-shield-check" style="color:var(--orange);font-size:17px;flex-shrink:0;"></i>
    {{ __('general.manages_own_products', ['store' => $store->name]) }}
</div>

{{-- ═══════════════════════════════════════════
     SECTION 3 — FEATURED COLLECTION + PRODUCT GRID
════════════════════════════════════════════ --}}
<div class="section-header" style="margin-top:8px;">
    <span class="section-title">{{ __('general.featured_collection') }}</span>
    <a href="{{ route('stores.show', $store) }}" class="link-view-all">
        {{ __('general.view_all') }} →
    </a>
</div>

@if($featuredProducts->isNotEmpty())
<div class="featured-row">
    @foreach($featuredProducts as $fp)
        @php
            $fpImg    = $fp->images->first();
            $fpImgSrc = null;
            if ($fpImg && file_exists(public_path('images/' . $fpImg->image_path))) {
                $fpImgSrc = asset('images/' . $fpImg->image_path);
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
            @if($fp->is3DReady())
            <span class="feat-badge-3d">
                <i class="ti ti-cube-3d-sphere" style="font-size:11px;"></i> 3D
            </span>
            @endif
            <button class="feat-heart" onclick="event.preventDefault()">
                <i class="ti ti-heart" style="font-size:14px;color:#666;"></i>
            </button>
        </a>
    @endforeach
</div>
@endif

{{-- Filter tabs + sort --}}
<div id="product-grid" class="filter-sort-bar">
    <div class="filter-tabs">
        <a href="{{ route('stores.show', $store) }}"
           class="filter-tab {{ !request('type') && !request('category') ? 'active' : '' }}">
            All
        </a>
        <a href="{{ route('stores.show', [$store, 'category' => 'new']) }}"
           class="filter-tab {{ request('category') === 'new' ? 'active' : '' }}">
            {{ __('general.new_arrivals') }}
        </a>
        <a href="{{ route('stores.show', [$store, 'category' => 'dresses']) }}"
           class="filter-tab {{ request('category') === 'dresses' ? 'active' : '' }}">
            Dresses
        </a>
        <a href="{{ route('stores.show', [$store, 'category' => 'jackets']) }}"
           class="filter-tab {{ request('category') === 'jackets' ? 'active' : '' }}">
            Jackets
        </a>
        <a href="{{ route('stores.show', [$store, 'category' => 'shirts']) }}"
           class="filter-tab {{ request('category') === 'shirts' ? 'active' : '' }}">
            Shirts
        </a>
        <a href="{{ route('stores.show', [$store, 'category' => 'tops']) }}"
           class="filter-tab {{ request('category') === 'tops' ? 'active' : '' }}">
            Tops
        </a>
        <a href="{{ route('stores.show', [$store, 'category' => 'bottoms']) }}"
           class="filter-tab {{ request('category') === 'bottoms' ? 'active' : '' }}">
            Bottoms
        </a>
        @if($store->has_3d_products)
        <a href="{{ route('stores.show', [$store, 'type' => '3d']) }}"
           class="filter-tab {{ request('type') === '3d' ? 'active' : '' }}">
            <i class="ti ti-cube-3d-sphere" style="font-size:13px;"></i>
            {{ __('general.3d_try_on') }}
        </a>
        @endif
    </div>

    <select class="sort-select" onchange="window.location.href=this.value">
        <option value="{{ route('stores.show', $store) }}"
                {{ !request('sort') ? 'selected' : '' }}>
            {{ __('general.sort_by') }}: {{ __('general.sort_featured') }}
        </option>
        <option value="{{ route('stores.show', [$store, 'sort' => 'price_asc']) }}"
                {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
            {{ __('general.sort_price_asc') }}
        </option>
        <option value="{{ route('stores.show', [$store, 'sort' => 'price_desc']) }}"
                {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
            {{ __('general.sort_price_desc') }}
        </option>
    </select>
</div>

<div class="product-grid-wrap">
    <div class="product-grid">
        @forelse($products as $product)
            @php
                $pImg    = $product->images->first();
                $pImgSrc = null;
                if ($pImg && file_exists(public_path('images/' . $pImg->image_path))) {
                    $pImgSrc = asset('images/' . $pImg->image_path);
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
                    @if($product->is3DReady())
                    <span class="prod-badge-3d">
                        <i class="ti ti-cube-3d-sphere" style="font-size:11px;"></i> 3D
                    </span>
                    @endif
                    <button class="prod-heart">
                        <i class="ti ti-heart" style="font-size:13px;color:#666;"></i>
                    </button>
                </div>

                <div class="prod-card-body">
                    <div class="prod-name">{{ $product->name }}</div>
                    <div class="prod-price">{{ config('shop.currency_symbol', '₪') }}{{ number_format($product->price, 2) }}</div>
                    <div class="prod-sold-by">
                        {{ __('general.sold_by', ['store' => $store->name]) }}
                    </div>
                    <div class="prod-btns">
                        <a href="{{ route('products.show', $product) }}"
                           class="btn-view-prod">
                            {{ __('general.view_product') }}
                        </a>
                        <form method="POST" action="{{ route('cart.add') }}"
                              style="flex:1;display:flex;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-add-cart" style="width:100%;">
                                <i class="ti ti-shopping-cart" style="font-size:13px;"></i>
                                {{ __('general.add_to_cart') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="prod-empty">No products found in this category.</div>
        @endforelse
    </div>
</div>

<div class="load-more-wrap">
    @if($products->hasMorePages())
    <a href="{{ $products->nextPageUrl() }}" class="btn-load-more">
        <i class="ti ti-chevron-down"></i>
        {{ __('general.load_more_products') }}
    </a>
    @endif
    <p class="load-more-count">
        {{ __('general.showing_of_products', ['shown' => $products->count(), 'total' => $totalProducts]) }}
    </p>
</div>

{{-- ═══════════════════════════════════════════
     SECTION 4 — 3D SECTION BANNER
════════════════════════════════════════════ --}}
@if($store->has_3d_products)
<div class="section-3d">
    <div class="section-3d-left">
        <div class="section-3d-icon-wrap">
            <i class="ti ti-cube-3d-sphere" style="font-size:28px;color:var(--orange);"></i>
        </div>
        <div class="section-3d-title">
            Shop {{ $store->name }} in <span>3D</span>
        </div>
        <p class="section-3d-sub">
            Selected products support 3D product visualization and virtual try-on
            powered by <strong>Voxura</strong>.
        </p>
        <a href="{{ route('stores.show', [$store, 'type' => '3d']) }}#product-grid"
           class="btn-hero-primary" style="width:fit-content;">
            <i class="ti ti-cube-3d-sphere"></i>
            {{ __('general.view_3d_products') }}
        </a>
    </div>

    <div class="section-3d-right">
        <div class="feat-col">
            <i class="ti ti-cube-3d-sphere feat-col-icon"></i>
            <h4>3D Product View</h4>
            <p>Inspect fabric, fit, and details from every angle with interactive 3D models.</p>
        </div>
        <div class="feat-col">
            <i class="ti ti-user-scan feat-col-icon"></i>
            <h4>Virtual Try-On</h4>
            <p>See how it looks on you in real time with our AI-powered try-on.</p>
        </div>
        <div class="feat-col">
            <i class="ti ti-ruler feat-col-icon"></i>
            <h4>Smart Size Guide</h4>
            <p>Get personalized size recommendations for the perfect, confident fit.</p>
        </div>
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════
     SECTION 5 — ABOUT THE STORE
════════════════════════════════════════════ --}}
<div class="section-about">
    <h2>{{ __('general.about_the_store') }}</h2>

    <div class="about-grid">
        <div class="about-col">
            <h4>About {{ $store->name }}</h4>
            <p>{{ $store->description }}</p>
            <div class="about-features">
                <div class="about-feat-item">
                    <i class="ti ti-star"></i><span>Premium Quality</span>
                </div>
                <div class="about-feat-item">
                    <i class="ti ti-leaf"></i><span>Sustainable Materials</span>
                </div>
                <div class="about-feat-item">
                    <i class="ti ti-shield"></i><span>Secure Payments</span>
                </div>
                <div class="about-feat-item">
                    <i class="ti ti-headset"></i><span>Dedicated Support</span>
                </div>
            </div>
        </div>

        <div class="about-col">
            <h4><i class="ti ti-map-pin"></i>{{ __('general.store_location') }}</h4>
            @php $address = $store->social_links['address'] ?? ''; @endphp
            <p>{{ $address ?: 'Contact store for location' }}</p>
            <a href="#" class="link-map">View on Map →</a>
        </div>

        <div class="about-col">
            <h4>{{ __('general.contact') }}</h4>
            @php
                $phone = $store->social_links['phone'] ?? '';
                $email = $store->social_links['email'] ?? '';
            @endphp
            @if($phone)
            <div class="contact-row">
                <i class="ti ti-phone"></i><span>{{ $phone }}</span>
            </div>
            @endif
            @if($email)
            <div class="contact-row">
                <i class="ti ti-mail"></i><span>{{ $email }}</span>
            </div>
            @endif
            <div class="contact-row">
                <i class="ti ti-clock"></i><span>Mon–Sat: 10:00 AM – 8:00 PM</span>
            </div>
        </div>

        <div class="about-col">
            <h4>{{ __('general.follow_us') }}</h4>
            <div class="social-icons">
                <a href="#" class="social-icon-btn"><i class="ti ti-brand-instagram"></i></a>
                <a href="#" class="social-icon-btn"><i class="ti ti-brand-facebook"></i></a>
                <a href="#" class="social-icon-btn"><i class="ti ti-brand-pinterest"></i></a>
                <a href="#" class="social-icon-btn"><i class="ti ti-brand-youtube"></i></a>
            </div>

            <h4 style="margin-top:4px;">{{ __('general.customer_care') }}</h4>
            <a href="#" class="care-link">Shipping &amp; Delivery <span>›</span></a>
            <a href="#" class="care-link">Returns &amp; Refunds <span>›</span></a>
            <a href="#" class="care-link">Track Your Order <span>›</span></a>
            <a href="#" class="care-link">FAQs <span>›</span></a>

            <div class="powered-box">
                <div class="powered-box-title">
                    <i class="ti ti-bolt"></i>
                    {{ __('general.powered_by_voxura') }}
                </div>
                <p class="powered-box-sub">3D Commerce. Real Experiences. Built for the Future.</p>
                <a href="{{ route('partner.apply') }}" class="powered-box-link">
                    {{ __('general.learn_more_voxura') }} →
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     SECTION 5B — CONTACT FORM
════════════════════════════════════════════ --}}
<div class="store-contact-card" style="margin:0 48px 40px;background:#111111;border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:48px;display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:start;">

    {{-- Left: Info --}}
    <div>
        <h2 style="font-size:24px;font-weight:800;color:#ffffff;margin-bottom:10px;">
            {{ __('general.contact_store', ['store' => $store->name]) }}
        </h2>
        <p style="font-size:14px;color:rgba(255,255,255,0.5);line-height:1.7;margin-bottom:32px;">
            {{ __('general.have_question_for', ['store' => $store->name]) }}
        </p>

        <div style="display:flex;flex-direction:column;">
            <div class="store-contact-info-row" style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div style="width:36px;height:36px;border-radius:50%;background:rgba(232,98,26,0.12);border:1px solid rgba(232,98,26,0.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="ti ti-clock" style="color:#E8621A;font-size:16px;"></i>
                </div>
                <span style="font-size:13px;color:rgba(255,255,255,0.6);line-height:1.5;">{{ __('general.usually_responds') }}</span>
            </div>
            <div class="store-contact-info-row" style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div style="width:36px;height:36px;border-radius:50%;background:rgba(232,98,26,0.12);border:1px solid rgba(232,98,26,0.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="ti ti-shield-check" style="color:#E8621A;font-size:16px;"></i>
                </div>
                <span style="font-size:13px;color:rgba(255,255,255,0.6);line-height:1.5;">{{ __('general.message_reviewed_note') }}</span>
            </div>
            <div class="store-contact-info-row" style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div style="width:36px;height:36px;border-radius:50%;background:rgba(232,98,26,0.12);border:1px solid rgba(232,98,26,0.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="ti ti-lock" style="color:#E8621A;font-size:16px;"></i>
                </div>
                <span style="font-size:13px;color:rgba(255,255,255,0.6);line-height:1.5;">{{ __('general.your_email_private') }}</span>
            </div>
        </div>

        @if($store->social_links)
        <div style="margin-top:8px;display:flex;gap:10px;">
            @if(!empty($store->social_links['instagram']))
            <a href="{{ $store->social_links['instagram'] }}" target="_blank" class="store-contact-social-link"
               style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.5);font-size:18px;text-decoration:none;transition:all 0.15s;">
                <i class="ti ti-brand-instagram"></i>
            </a>
            @endif
            @if(!empty($store->social_links['whatsapp']))
            <a href="https://wa.me/{{ $store->social_links['whatsapp'] }}" target="_blank" class="store-contact-social-link"
               style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.5);font-size:18px;text-decoration:none;transition:all 0.15s;">
                <i class="ti ti-brand-whatsapp"></i>
            </a>
            @endif
        </div>
        @endif
    </div>

    {{-- Right: Form --}}
    <div>
        @if(session('message_sent'))
        <div style="background:rgba(29,158,117,0.1);border:1px solid rgba(29,158,117,0.3);border-radius:10px;padding:14px 18px;color:#5DCAA5;font-size:14px;margin-bottom:20px;display:flex;align-items:center;gap:8px;">
            <i class="ti ti-circle-check" style="font-size:18px;"></i>
            {{ session('message_sent') }}
        </div>
        @endif

        @if($errors->any())
        <div style="background:rgba(226,75,74,0.1);border:1px solid rgba(226,75,74,0.3);border-radius:10px;padding:14px 18px;color:#F09595;font-size:13px;margin-bottom:20px;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('stores.contact.send', $store) }}">
            @csrf
            <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                   placeholder="{{ __('general.contact_name') }}" required
                   class="store-contact-input"
                   style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:13px 16px;font-size:14px;font-family:inherit;color:#ffffff;outline:none;margin-bottom:14px;transition:border-color 0.2s;">
            <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                   placeholder="{{ __('general.contact_email') }}" required
                   class="store-contact-input"
                   style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:13px 16px;font-size:14px;font-family:inherit;color:#ffffff;outline:none;margin-bottom:14px;transition:border-color 0.2s;">
            <input type="text" name="subject" value="{{ old('subject') }}"
                   placeholder="{{ __('general.contact_subject') }}"
                   class="store-contact-input"
                   style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:13px 16px;font-size:14px;font-family:inherit;color:#ffffff;outline:none;margin-bottom:14px;transition:border-color 0.2s;">
            <textarea name="message" placeholder="{{ __('general.contact_message') }}" required
                      class="store-contact-input"
                      style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:13px 16px;font-size:14px;font-family:inherit;color:#ffffff;outline:none;resize:none;height:140px;margin-bottom:20px;transition:border-color 0.2s;">{{ old('message') }}</textarea>
            <button type="submit" class="store-contact-btn"
                    style="width:100%;background:linear-gradient(135deg,#f07030 0%,#E8621A 100%);color:#ffffff;border:none;border-radius:12px;padding:15px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:8px;transition:opacity 0.15s,transform 0.15s;margin-bottom:12px;">
                <i class="ti ti-send"></i>
                {{ __('general.send_message_to', ['store' => $store->name]) }}
            </button>
            <p style="font-size:12px;color:rgba(255,255,255,0.3);text-align:center;">
                By sending a message you agree to our
                <a href="/pages/privacy-policy" style="color:#E8621A;text-decoration:none;">Privacy Policy</a>
            </p>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     SECTION 6 — TRUST BAR
════════════════════════════════════════════ --}}
<div class="trust-bar">
    <div class="trust-bar-cell">
        <i class="ti ti-truck trust-icon"></i>
        <div>
            <span class="trust-label">{{ __('general.free_shipping') }}</span>
            <span class="trust-sub">{{ __('general.on_orders_above') }}</span>
        </div>
    </div>
    <div class="trust-bar-cell">
        <i class="ti ti-refresh trust-icon"></i>
        <div>
            <span class="trust-label">{{ __('general.easy_returns') }}</span>
            <span class="trust-sub">{{ __('general.day_return_policy') }}</span>
        </div>
    </div>
    <div class="trust-bar-cell">
        <i class="ti ti-shield-check trust-icon"></i>
        <div>
            <span class="trust-label">{{ __('general.secure_checkout') }}</span>
            <span class="trust-sub">{{ __('general.secure_payments') }}</span>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     SECTION 7 — MORE STORES YOU MAY LIKE
════════════════════════════════════════════ --}}
@if($similarStores->isNotEmpty())
<div class="similar-stores-wrap">
    <div class="section-header" style="padding:0;margin-bottom:18px;">
        <span class="section-title">{{ __('general.more_stores_like') }}</span>
        <a href="{{ route('stores.index') }}" class="link-view-all">
            {{ __('general.view_all_stores') }} →
        </a>
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
                <div class="sim-card-name">
                    {{ $similar->name }}
                    <i class="ti ti-circle-check-filled"></i>
                </div>
                <div class="sim-card-tagline">{{ $similar->tagline }}</div>

                <div class="sim-card-btns">
                    <button class="btn-sim-follow">{{ __('general.follow_store') }}</button>
                    <a href="{{ route('stores.show', $similar) }}"
                       class="btn-sim-visit">{{ __('general.visit_store') }}</a>
                </div>

                <div class="sim-powered">
                    <i class="ti ti-bolt"></i>
                    {{ __('general.powered_by_voxura') }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════
     SECTION 8 — JOIN VOXURA CTA BANNER
════════════════════════════════════════════ --}}
<div class="store-cta">
    @if(file_exists(public_path('images/stores/stores-hero.png')))
        <img class="store-cta-bg"
             src="{{ asset('images/stores/stores-hero.png') }}" alt="">
    @elseif(file_exists(public_path('images/hero-background.png')))
        <img class="store-cta-bg"
             src="{{ asset('images/hero-background.png') }}" alt="">
    @endif
    <div class="store-cta-overlay"></div>

    <div class="store-cta-content">
        <h2>{{ __('general.own_clothing_store') }}</h2>
        <p>Create your branded store page on Voxura and sell your collections with immersive 3D product visualization.</p>
        <a href="{{ route('partner.apply') }}" class="btn-cta-apply">
            {{ __('general.apply_join_voxura') }}
            <i class="ti ti-arrow-right"></i>
        </a>
    </div>
</div>

</x-layout>
