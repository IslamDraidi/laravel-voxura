<x-layout :title="$product->name">

<style>
/* ═══════════════════════════════════════════
   PDP — PRODUCT DETAIL PAGE
   Fonts: Playfair Display (headings) · DM Sans (body)
   Primary: #ea580c  Border-radius: 0.75rem
════════════════════════════════════════════ */

/* ── Page wrapper ── */
.pdp-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1.5rem 5rem;
    padding-top: 5.5rem;
}

/* ── Main 2-col grid ── */
.pdp-grid {
    display: grid;
    grid-template-columns: 1fr 1.05fr;
    gap: 3rem;
    margin-bottom: 3.5rem;
    align-items: start;
}
@media (max-width: 900px) {
    .pdp-grid { grid-template-columns: 1fr; gap: 1.5rem; }
}

/* ══════════════════════════
   LEFT — IMAGE GALLERY
══════════════════════════ */
.pdp-gallery {}

.gallery-main {
    position: relative;
    background: #f5f5f5;
    border-radius: 0.75rem;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    margin-bottom: 0.75rem;
}
.gallery-main img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
}
.gallery-main-ph {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    color: #ccc; font-size: 4rem;
}

/* badges */
.badge-sale {
    position: absolute; top: 14px; left: 14px; z-index: 4;
    background: #ef4444; color: #fff;
    padding: 0.35rem 0.85rem; border-radius: 999px;
    font-size: 0.75rem; font-weight: 800; letter-spacing: 0.05em;
}
.badge-new {
    position: absolute; top: 14px; left: 14px; z-index: 4;
    background: #2563eb; color: #fff;
    padding: 0.35rem 0.85rem; border-radius: 999px;
    font-size: 0.75rem; font-weight: 800; letter-spacing: 0.05em;
}

/* wishlist overlay btn */
.gallery-wish-btn {
    position: absolute; top: 14px; right: 14px; z-index: 4;
    width: 42px; height: 42px; border-radius: 50%;
    background: #fff; border: 1.5px solid #f3f4f6; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: #d1d5db;
    box-shadow: 0 2px 12px rgba(0,0,0,0.14);
    transition: color 0.2s, transform 0.15s, border-color 0.2s;
}
.gallery-wish-btn:hover { color: #ef4444; transform: scale(1.1); border-color: #fecaca; }
.gallery-wish-btn.active { color: #ef4444; border-color: #fecaca; }

/* 3D button */
.btn-3d-view {
    position: absolute; bottom: 14px; left: 14px; z-index: 4;
    background: #1a1a1a; color: #fff; border: none;
    padding: 0.55rem 1.1rem; border-radius: 999px;
    font-size: 0.8rem; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; gap: 0.4rem;
    transition: background 0.15s; font-family: 'DM Sans', sans-serif;
}
.btn-3d-view:hover { background: #333; }
.btn-3d-view svg { width: 15px; height: 15px; }

/* thumbnails */
.gallery-thumbs {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}
.g-thumb {
    aspect-ratio: 1/1; background: #f5f5f5;
    border-radius: 0.5rem; overflow: hidden; cursor: pointer;
    border: 2px solid transparent; transition: border-color 0.15s;
}
.g-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.g-thumb.active { border-color: #ea580c; }

/* try-on button */
.btn-tryon {
    display: flex; align-items: center; justify-content: center; gap: 0.6rem;
    width: 100%; background: #ea580c; color: #fff; border: none;
    padding: 0.85rem 1.25rem; border-radius: 0.75rem;
    font-size: 0.9rem; font-weight: 700; cursor: pointer;
    transition: background 0.15s; font-family: 'DM Sans', sans-serif;
    text-decoration: none;
}
.btn-tryon:hover { background: #c2410c; color: #fff; }
.btn-tryon-locked {
    background: transparent; color: #888;
    border: 1.5px solid #e0ddd9;
}
.btn-tryon-locked:hover { background: #fafaf8; color: #555; }

/* ══════════════════════════
   RIGHT — PRODUCT INFO
══════════════════════════ */
.pdp-info {}

/* breadcrumb */
.pdp-breadcrumb {
    font-size: 0.72rem; font-weight: 700;
    letter-spacing: 0.12em; text-transform: uppercase;
    color: #ea580c; margin-bottom: 0.75rem;
}
.pdp-breadcrumb a { color: #ea580c; text-decoration: none; }
.pdp-breadcrumb a:hover { text-decoration: underline; }
.pdp-breadcrumb span { margin: 0 0.4rem; opacity: 0.5; }

/* title */
.pdp-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 800;
    color: #111827; line-height: 1.2;
    margin-bottom: 0.75rem;
}

/* rating row */
.pdp-rating-row {
    display: flex; align-items: center; gap: 0.6rem;
    margin-bottom: 1.25rem; flex-wrap: wrap;
}
.pdp-stars { color: #f59e0b; font-size: 1rem; letter-spacing: 0.05em; }
.pdp-rating-val { font-weight: 700; font-size: 0.9rem; color: #374151; }
.pdp-rating-count { font-size: 0.85rem; color: #9ca3af; }

/* price & sku */
.pdp-price {
    font-size: 2rem; font-weight: 800; color: #111827;
    margin-bottom: 0.3rem;
}
.pdp-sku {
    font-size: 0.75rem; color: #9ca3af;
    letter-spacing: 0.08em; text-transform: uppercase;
    margin-bottom: 1rem;
}

/* stock badge */
.pdp-stock-low {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: #fff7ed; border: 1.5px solid #fed7aa;
    color: #c2410c; padding: 0.4rem 0.85rem; border-radius: 999px;
    font-size: 0.8rem; font-weight: 700; margin-bottom: 1.25rem;
}
.pdp-stock-out {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: #f9fafb; border: 1.5px solid #e5e7eb;
    color: #6b7280; padding: 0.4rem 0.85rem; border-radius: 999px;
    font-size: 0.8rem; font-weight: 700; margin-bottom: 1.25rem;
}

/* feature pills */
.pdp-features {
    display: flex; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 1.5rem;
}
.pdp-feat-pill {
    display: inline-flex; align-items: center; gap: 0.4rem;
    border: 1.5px solid #e5e7eb; border-radius: 999px;
    padding: 0.4rem 0.8rem; font-size: 0.75rem; font-weight: 600;
    color: #374151; background: #fff; white-space: nowrap;
}

/* picker group */
.picker-group { margin-bottom: 1.4rem; }
.picker-lbl {
    font-size: 0.75rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.1em;
    color: #6b7280; display: block; margin-bottom: 0.55rem;
}

/* color swatches */
.color-swatches { display: flex; gap: 0.75rem; flex-wrap: wrap; }
.swatch {
    width: 34px; height: 34px; border-radius: 50%;
    border: 3px solid transparent; cursor: pointer;
    transition: border-color 0.15s, transform 0.15s;
}
.swatch:hover { transform: scale(1.12); }
.swatch.active { border-color: #1a1a1a; }

/* size grid */
.size-options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(52px, 1fr));
    gap: 0.5rem;
}
.size-btn {
    padding: 0.6rem; border: 1.5px solid #e5e7eb;
    border-radius: 0.5rem; font-size: 0.85rem; font-weight: 700;
    background: #fff; color: #374151; cursor: pointer;
    transition: all 0.15s; position: relative;
    font-family: 'DM Sans', sans-serif; text-align: center;
}
.size-btn:hover:not(:disabled) { border-color: #ea580c; color: #ea580c; }
.size-btn.active { background: #ea580c; color: #fff; border-color: #ea580c; }
.size-btn:disabled {
    background: #f9fafb; color: #d1d5db; cursor: not-allowed;
}
.size-btn:disabled::after {
    content: 'Out'; position: absolute; top: 100%; right: 0;
    font-size: 0.6rem; color: #ef4444; font-weight: 700;
    white-space: nowrap;
}

/* quantity */
.qty-row { display: flex; align-items: center; gap: 0.5rem; }
.qty-btn {
    width: 36px; height: 36px; border: 1.5px solid #e5e7eb;
    border-radius: 0.5rem; background: #fff; cursor: pointer;
    font-size: 1rem; font-weight: 700; transition: border-color 0.15s;
    font-family: 'DM Sans', sans-serif;
}
.qty-btn:hover { border-color: #ea580c; color: #ea580c; }
.qty-input {
    width: 54px; text-align: center; border: 1.5px solid #e5e7eb;
    border-radius: 0.5rem; padding: 0.4rem; font-size: 0.95rem;
    font-weight: 700; font-family: 'DM Sans', sans-serif;
}
.qty-max { font-size: 0.75rem; color: #9ca3af; margin-left: 0.5rem; }

/* delivery */
.delivery-box {
    background: #f9fafb; border-radius: 0.75rem;
    padding: 1rem; margin-bottom: 1.4rem;
}
.delivery-row { display: flex; gap: 0.5rem; margin-top: 0.5rem; }
.delivery-input {
    flex: 1; border: 1.5px solid #e5e7eb; border-radius: 0.5rem;
    padding: 0.55rem 0.75rem; font-size: 0.85rem;
    font-family: 'DM Sans', sans-serif; outline: none;
    transition: border-color 0.15s;
}
.delivery-input:focus { border-color: #ea580c; }
.delivery-check-btn {
    background: #ea580c; color: #fff; border: none;
    border-radius: 0.5rem; padding: 0.55rem 1rem;
    font-size: 0.85rem; font-weight: 700; cursor: pointer;
    font-family: 'DM Sans', sans-serif; transition: background 0.15s;
}
.delivery-check-btn:hover { background: #c2410c; }
.delivery-result { margin-top: 0.5rem; font-size: 0.8rem; color: #6b7280; }
.delivery-result.ok { color: #16a34a; }
.delivery-result.unavail { color: #ef4444; }

/* CTA buttons */
.btn-add-cart {
    width: 100%; background: #ea580c; color: #fff; border: none;
    padding: 0.9rem; border-radius: 0.75rem; font-size: 0.95rem;
    font-weight: 700; cursor: pointer; transition: background 0.15s;
    font-family: 'DM Sans', sans-serif; margin-bottom: 0.65rem;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
}
.btn-add-cart:hover:not(:disabled) { background: #c2410c; }
.btn-add-cart:disabled { background: #d1d5db; cursor: not-allowed; }

.btn-buy-now {
    width: 100%; background: #fff; color: #111827;
    border: 1.5px solid #e5e7eb; padding: 0.9rem;
    border-radius: 0.75rem; font-size: 0.95rem; font-weight: 700;
    cursor: pointer; transition: all 0.15s;
    font-family: 'DM Sans', sans-serif; margin-bottom: 1.25rem;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
}
.btn-buy-now:hover { border-color: #ea580c; color: #ea580c; }

/* share */
.share-row {
    display: flex; align-items: center; gap: 0.6rem;
    flex-wrap: wrap; padding-top: 1.25rem;
    border-top: 1.5px solid #f3f4f6;
}
.share-lbl { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #9ca3af; }
.share-btn {
    width: 36px; height: 36px; border-radius: 50%;
    border: 1.5px solid #e5e7eb; background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 0.85rem; transition: all 0.15s;
    text-decoration: none; color: #374151;
}
.share-btn:hover { border-color: #ea580c; background: #fff7ed; color: #ea580c; }

/* ══════════════════════════
   SELLER SECTION
══════════════════════════ */
.seller-section {
    background: #fff; border: 1px solid #f0ede8;
    border-radius: 1rem; padding: 1.5rem 1.5rem 1.5rem;
    padding-top: calc(32px + 1.25rem);
    margin-bottom: 2.5rem; margin-top: 32px;
    position: relative; overflow: visible;
    display: grid; grid-template-columns: auto 1fr;
    gap: 2rem; align-items: start;
}
@media (max-width: 700px) {
    .seller-section { grid-template-columns: 1fr; }
}

/* left: seller info */
.seller-left { min-width: 220px; }
.seller-avatar {
    position: absolute; top: -32px; left: 50%; transform: translateX(-50%);
    width: 64px; height: 64px; border-radius: 50%;
    background: #1a1a1a; display: flex; align-items: center;
    justify-content: center; font-size: 18px; font-weight: 700;
    color: #fff; overflow: hidden;
    border: 3px solid #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.12);
}
.seller-avatar img { width: 100%; height: 100%; object-fit: cover; }
.seller-name-row {
    display: flex; align-items: center; gap: 0.4rem;
    margin-bottom: 0.25rem;
}
.seller-name {
    font-size: 1rem; font-weight: 700; color: #1a1a1a;
    text-decoration: none; transition: color 0.15s;
}
.seller-name:hover { color: #ea580c; }
.seller-verified { color: #ea580c; font-size: 1rem; }
.seller-tagline { font-size: 0.8rem; color: #888; margin-bottom: 1rem; }
.seller-btns { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.seller-btn {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.55rem 1rem; border-radius: 0.5rem;
    font-size: 0.8rem; font-weight: 600; text-decoration: none;
    transition: all 0.15s; cursor: pointer; border: none;
    font-family: 'DM Sans', sans-serif;
}
.seller-btn-primary { background: #ea580c; color: #fff; }
.seller-btn-primary:hover { background: #c2410c; color: #fff; }
.seller-btn-outline { background: #f9fafb; border: 1px solid #e5e7eb; color: #374151; }
.seller-btn-outline:hover { border-color: #ea580c; color: #ea580c; }

/* right: seller products scroll */
.seller-products-scroll {
    overflow-x: auto; padding-bottom: 0.25rem;
}
.seller-products-row {
    display: flex; gap: 0.75rem;
    min-width: max-content;
}
.seller-prod-card {
    width: 120px; border-radius: 0.6rem; overflow: hidden;
    border: 1px solid #f0ede8; text-decoration: none;
    transition: border-color 0.15s, transform 0.15s; flex-shrink: 0;
    background: #fff;
}
.seller-prod-card:hover { border-color: #ea580c; transform: translateY(-2px); }
.seller-prod-img {
    width: 100%; height: 90px; object-fit: cover;
    display: block; background: #f5f5f5;
}
.seller-prod-img-ph {
    width: 100%; height: 90px;
    display: flex; align-items: center; justify-content: center;
    background: #f5f5f5; color: #ccc; font-size: 1.5rem;
}
.seller-prod-info { padding: 0.5rem 0.5rem 0.6rem; }
.seller-prod-name {
    font-size: 0.7rem; font-weight: 600; color: #1a1a1a;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    display: block; margin-bottom: 0.2rem;
}
.seller-prod-price { font-size: 0.75rem; font-weight: 700; color: #ea580c; }

/* ══════════════════════════
   TABS
══════════════════════════ */
.pdp-tabs-card {
    background: #fff; border-radius: 0.75rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    overflow: hidden; margin-bottom: 3rem;
}
.tabs-nav {
    display: flex; border-bottom: 2px solid #f3f4f6;
    background: #fff;
}
.tab-btn {
    padding: 1rem 1.4rem; background: none; border: none;
    font-size: 0.875rem; font-weight: 600; color: #9ca3af;
    cursor: pointer; border-bottom: 3px solid transparent;
    margin-bottom: -2px; transition: all 0.15s;
    font-family: 'DM Sans', sans-serif;
}
.tab-btn:hover { color: #ea580c; }
.tab-btn.active { color: #111827; border-bottom-color: #ea580c; }
.tabs-body { padding: 2rem; }
.tab-pane { display: none; }
.tab-pane.active { display: block; }

/* details tab */
.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem; margin-top: 1.5rem;
}
@media (max-width: 600px) { .details-grid { grid-template-columns: 1fr; } }
.detail-item { margin-bottom: 1rem; }
.detail-lbl {
    font-size: 0.72rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 0.3rem;
}
.detail-val { font-weight: 600; color: #374151; }

/* reviews */
.review-stats {
    display: grid; grid-template-columns: auto 1fr;
    gap: 2rem; margin-bottom: 2rem; align-items: center;
}
@media (max-width: 600px) { .review-stats { grid-template-columns: 1fr; } }
.avg-big { font-size: 3.5rem; font-weight: 800; color: #ea580c; line-height: 1; }
.avg-stars { color: #f59e0b; font-size: 1rem; margin: 0.3rem 0 0.2rem; }
.avg-count { font-size: 0.8rem; color: #9ca3af; }
.rating-bars { display: flex; flex-direction: column; gap: 0.5rem; }
.rating-bar { display: flex; align-items: center; gap: 0.6rem; font-size: 0.8rem; }
.bar-lbl { width: 28px; text-align: right; color: #6b7280; font-weight: 600; }
.bar-track {
    flex: 1; height: 7px; background: #f3f4f6;
    border-radius: 999px; overflow: hidden;
}
.bar-fill { height: 100%; background: #f59e0b; border-radius: 999px; }
.bar-count { width: 28px; color: #9ca3af; font-size: 0.75rem; }

.reviews-list { display: flex; flex-direction: column; gap: 1.25rem; }
.review-card {
    padding: 1.1rem; border: 1.5px solid #f3f4f6;
    border-radius: 0.75rem;
}
.review-author { font-weight: 700; color: #111827; margin-bottom: 0.2rem; }
.review-meta { display: flex; align-items: center; gap: 0.4rem; font-size: 0.78rem; color: #9ca3af; }
.review-badge {
    display: inline-flex; align-items: center; gap: 0.25rem;
    background: #dcfce7; color: #16a34a;
    font-size: 0.72rem; font-weight: 700; padding: 0.1rem 0.45rem; border-radius: 999px;
}
.review-stars { color: #f59e0b; margin: 0.4rem 0; }
.review-text { color: #6b7280; font-size: 0.875rem; line-height: 1.65; margin-bottom: 0.65rem; }
.helpful-btn {
    background: none; border: 1px solid #e5e7eb; border-radius: 0.4rem;
    padding: 0.3rem 0.65rem; font-size: 0.75rem; cursor: pointer;
    color: #6b7280; transition: all 0.15s; font-family: 'DM Sans', sans-serif;
}
.helpful-btn:hover { border-color: #ea580c; color: #ea580c; }

/* ══════════════════════════
   RELATED PRODUCTS (3 cols)
══════════════════════════ */
.section-heading {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem; font-weight: 800;
    color: #111827; margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 0.6rem;
}
.related-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    margin-bottom: 3.5rem;
}
@media (max-width: 700px) { .related-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 460px) { .related-grid { grid-template-columns: 1fr; } }
.related-card {
    text-decoration: none; border-radius: 0.75rem;
    border: 1.5px solid #f3f4f6; overflow: hidden;
    background: #fff; transition: border-color 0.15s, box-shadow 0.15s;
    display: block;
}
.related-card:hover {
    border-color: #fed7aa;
    box-shadow: 0 4px 18px rgba(234,88,12,0.1);
}
.related-img {
    width: 100%; aspect-ratio: 1/1; object-fit: cover;
    background: #f5f5f5; display: block;
}
.related-body { padding: 0.85rem; }
.related-name {
    font-family: 'Playfair Display', serif;
    font-size: 0.9rem; font-weight: 700; color: #111827;
    margin-bottom: 0.35rem; line-height: 1.3;
}
.related-price { font-size: 0.95rem; font-weight: 800; color: #ea580c; }

/* ══════════════════════════
   RECENTLY VIEWED (3 cols, horizontal cards)
══════════════════════════ */
.rv-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 3.5rem;
}
@media (max-width: 700px) { .rv-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 460px) { .rv-grid { grid-template-columns: 1fr; } }
.rv-card {
    display: flex; align-items: center; gap: 0.9rem;
    background: #fff; border: 1.5px solid #f3f4f6;
    border-radius: 0.75rem; padding: 0.75rem;
    text-decoration: none; transition: border-color 0.15s, box-shadow 0.15s;
}
.rv-card:hover { border-color: #fed7aa; box-shadow: 0 2px 12px rgba(234,88,12,0.08); }
.rv-img {
    width: 100px; height: 100px; border-radius: 0.6rem;
    object-fit: cover; background: #f5f5f5; flex-shrink: 0;
}
.rv-img-ph {
    width: 100px; height: 100px; border-radius: 0.6rem;
    background: #f5f5f5; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    color: #ccc; font-size: 2rem;
}
.rv-name {
    font-size: 0.875rem; font-weight: 700; color: #111827;
    line-height: 1.35; margin-bottom: 0.4rem;
    display: -webkit-box; -webkit-line-clamp: 3;
    -webkit-box-orient: vertical; overflow: hidden;
}
.rv-price { font-size: 0.95rem; font-weight: 800; color: #ea580c; }

/* ══════════════════════════
   3D VIEWER — FULLSCREEN MODAL
══════════════════════════ */
#viewer3d-overlay {
    display: none; position: fixed; inset: 0; z-index: 9000;
    background: rgba(0,0,0,0.85);
    align-items: center; justify-content: center;
    padding: 1.5rem;
}
#viewer3d-overlay.open { display: flex; }

.viewer3d-modal {
    position: relative;
    width: 100%; max-width: 900px;
    background: #111; border-radius: 1rem;
    overflow: hidden;
    display: grid; grid-template-columns: 1fr 240px;
    aspect-ratio: 16/9;
}
@media (max-width: 640px) {
    .viewer3d-modal { grid-template-columns: 1fr; aspect-ratio: auto; max-height: 90vh; }
}

.viewer3d-close {
    position: absolute; top: 12px; left: 12px; z-index: 20;
    background: rgba(255,255,255,0.12); color: #fff; border: none;
    padding: 0.45rem 0.9rem; border-radius: 999px;
    font-size: 0.8rem; font-weight: 700; cursor: pointer;
    backdrop-filter: blur(8px); font-family: 'DM Sans', sans-serif;
    transition: background 0.15s;
}
.viewer3d-close:hover { background: rgba(255,255,255,0.22); }

#viewer-3d {
    position: absolute; inset: 0;
    background: #111; border-radius: 0.75rem;
}
#viewer-3d canvas { display: block; width: 100% !important; height: 100% !important; }

.viewer-loading {
    position: absolute; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
    color: rgba(255,255,255,0.45); font-size: 0.85rem;
    letter-spacing: 0.05em; z-index: 5;
    font-family: 'DM Sans', sans-serif;
}
@keyframes v3d-dots { 0%,20%{content:''} 40%{content:'.'} 60%{content:'..'} 80%,100%{content:'...'} }
.viewer-loading::after { content: ''; animation: v3d-dots 1.5s steps(1) infinite; }

/* instructions panel */
.viewer-panel {
    background: rgba(18,18,18,0.9); backdrop-filter: blur(20px);
    display: flex; flex-direction: column;
    border-left: 1px solid rgba(255,255,255,0.07);
    font-family: 'DM Sans', sans-serif; color: #fff;
}
.viewer-panel-hd {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.2rem 1rem 0.5rem;
}
.viewer-panel-title { font-size: 0.9rem; font-weight: 700; }
.viewer-panel-x {
    background: rgba(255,255,255,0.08); border: none; color: rgba(255,255,255,0.6);
    width: 28px; height: 28px; border-radius: 50%; cursor: pointer; font-size: 1rem;
    display: flex; align-items: center; justify-content: center; transition: background 0.15s;
}
.viewer-panel-x:hover { background: rgba(255,255,255,0.16); color: #fff; }
.viewer-panel-body { flex: 1; padding: 0 1rem; overflow-y: auto; }
.vp-section {
    padding: 0.65rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.vp-section:last-child { border-bottom: none; }
.vp-section-title {
    font-size: 0.75rem; font-weight: 700; color: #ea580c;
    display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.25rem;
}
.vp-section-title svg { width: 14px; height: 14px; }
.vp-instruction { font-size: 0.7rem; color: rgba(255,255,255,0.45); line-height: 1.55; }
.vp-instruction span { color: rgba(255,255,255,0.8); font-weight: 600; }
.viewer-panel-ft { padding: 0.75rem 1rem 1rem; }
.viewer-panel-ok {
    width: 100%; background: #ea580c; color: #fff; border: none;
    padding: 0.55rem; border-radius: 0.5rem; font-size: 0.82rem;
    font-weight: 700; cursor: pointer; font-family: 'DM Sans', sans-serif;
    transition: background 0.15s;
}
.viewer-panel-ok:hover { background: #c2410c; }

/* ══════════════════════════
   TRY-ON MODAL (unchanged styles, re-declared cleanly)
══════════════════════════ */
#tryon-modal-overlay {
    display: none; position: fixed; inset: 0; z-index: 9999;
    background: rgba(26,26,26,0.6); backdrop-filter: blur(4px);
    align-items: center; justify-content: center; padding: 20px;
}
#tryon-modal-overlay.is-open { display: flex !important; }
#tryon-modal {
    background: #fff; border-radius: 24px; border: 1px solid #E8E0D8;
    width: 100%; max-width: 560px; max-height: 92vh;
    overflow: auto; box-shadow: 0 20px 60px rgba(232,98,26,0.2);
}
@keyframes tryonBlobPulse {
    0%,100%{transform:scale(1);filter:blur(0)} 50%{transform:scale(1.06);filter:blur(1px)}
}
@keyframes tryonStepBlink { 0%,100%{opacity:1} 50%{opacity:.35} }
.tryon-step-dot { width:10px;height:10px;border-radius:50%;background:#E8E0D8;flex-shrink:0; }
#tryon-upload-state #tryon-step-1 .tryon-step-dot,
#tryon-processing-state [data-status="done"] .tryon-step-dot { background:#16A34A; }
#tryon-processing-state [data-status="active"] .tryon-step-dot {
    background:#E8621A; animation:tryonStepBlink 1s ease-in-out infinite;
}
#tryon-processing-state [data-status="active"] { background:#FFF8F2 !important; }

/* ══════════════════════════
   SIZE GUIDE MODAL
══════════════════════════ */
.sgm-overlay {
    display: none; position: fixed; inset: 0; z-index: 8000;
    background: rgba(0,0,0,0.4);
    align-items: center; justify-content: center; padding: 1.5rem;
}
.sgm-overlay.open { display: flex; }
.sgm-box {
    background: #fff; border-radius: 0.75rem; padding: 2rem;
    max-width: 500px; width: 100%; position: relative;
}
.sgm-close {
    position: absolute; top: 1rem; right: 1rem;
    background: none; border: none; font-size: 1.4rem; cursor: pointer;
    color: #9ca3af;
}

/* ══════════════════════════
   GENERATING SPINNER
══════════════════════════ */
@keyframes m3dSpin { to { transform: rotate(360deg); } }
.btn-3d-spin {
    width: 12px; height: 12px;
    border: 2px solid #fff; border-top-color: transparent;
    border-radius: 50%; display: inline-block;
    animation: m3dSpin 0.8s linear infinite;
}
</style>

<div class="pdp-wrap">

    {{-- ════════════════════════════════
         MAIN GRID: LEFT + RIGHT
    ═════════════════════════════════ --}}
    <div class="pdp-grid">

        {{-- ── LEFT: IMAGE GALLERY ── --}}
        <div class="pdp-gallery">

            {{-- Main image --}}
            <div class="gallery-main">
                @if($product->sale_badge)
                    <div class="badge-sale">{{ $product->sale_badge }}</div>
                @elseif($product->is_new ?? false)
                    <div class="badge-new">NEW</div>
                @endif

                @php
                    $mainImg = $product->images->first()?->image_path
                        ?? ($product->image ? 'images/'.$product->image : null);
                @endphp
                @if($mainImg)
                    <img id="mainImage" src="{{ asset($mainImg) }}" alt="{{ $product->name }}">
                @else
                    <div class="gallery-main-ph"><i class="ti ti-photo"></i></div>
                @endif

                <button class="gallery-wish-btn {{ in_array($product->id, $likedIds) ? 'active' : '' }}"
                        onclick="toggleWishlist({{ $product->id }})" aria-label="{{ __('general.add_to_wishlist') }}">
                    ♥
                </button>

                {{-- Inline 3D viewer (fills gallery when active) --}}
                @if($product->is3DReady())
                <div id="viewer-3d" style="display:none;position:absolute;inset:0;background:#111;border-radius:0.75rem;z-index:5;">
                    <div class="viewer-loading">Rendering</div>
                </div>
                <button id="btn-close-3d" onclick="close3DInline()"
                        style="display:none;position:absolute;top:12px;left:12px;z-index:10;background:rgba(0,0,0,0.55);color:#fff;border:none;padding:0.4rem 0.9rem;border-radius:999px;font-size:0.8rem;font-weight:700;cursor:pointer;backdrop-filter:blur(8px);font-family:'DM Sans',sans-serif;transition:background 0.15s;">
                    ← {{ __('general.back') }}
                </button>
                @endif

                @php
                    $m3dState = $product->model3d_status ?? 'idle';
                    $m3dReady = $product->is3DReady();
                    $m3dBusy  = $product->is3DProcessing();
                @endphp

                @if($m3dReady)
                    <button class="btn-3d-view" id="btn-open-3d" onclick="open3DInline()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                        {{ __('general.view_in_3d') }}
                    </button>
                @elseif($m3dBusy)
                    <button class="btn-3d-view" style="background:#d97706;opacity:.85;cursor:not-allowed;" disabled>
                        <span class="btn-3d-spin"></span>
                        {{ __('general.generating_3d') }}
                    </button>
                @endif
            </div>

            {{-- Thumbnails --}}
            <div class="gallery-thumbs">
                @php
                    $thumbs = $product->images->count() > 1 ? $product->images : collect();
                    $firstThumb = $mainImg;
                @endphp
                <div class="g-thumb active" onclick="switchThumb(this)">
                    @if($firstThumb)
                        <img src="{{ asset($firstThumb) }}" alt="{{ $product->name }}">
                    @endif
                </div>
                @foreach($thumbs->skip(1)->take(3) as $img)
                    <div class="g-thumb" onclick="switchThumb(this)">
                        <img src="{{ asset($img->image_path) }}" alt="{{ $product->name }}">
                    </div>
                @endforeach
            </div>

            {{-- Virtual Try-On button --}}
            @if($m3dReady && config('model3d.tryon.enabled', false))
                @auth
                    <button id="tryon-btn" type="button" onclick="openTryOnModal()" class="btn-tryon">
                        <i class="ti ti-user" style="font-size:1.1rem;"></i>
                        {{ __('general.virtual_tryon') }}
                        <span style="font-size:0.78rem;font-weight:400;opacity:.85;">— {{ __('general.tryon_subtitle') }}</span>
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn-tryon btn-tryon-locked">
                        🔒 {{ __('general.tryon_login_prompt') }}
                    </a>
                @endauth
            @endif

        </div>{{-- /gallery --}}

        {{-- ── RIGHT: PRODUCT INFO ── --}}
        <div class="pdp-info">

            {{-- Breadcrumb --}}
            <div class="pdp-breadcrumb">
                @if($product->category?->parent)
                    <a href="{{ route('products.index', ['category' => $product->category->parent->name]) }}">
                        {{ strtoupper($product->category->parent->name) }}
                    </a>
                    <span>/</span>
                @endif
                <a href="{{ route('products.index', ['category' => $product->category?->name]) }}">
                    {{ strtoupper($product->category?->name ?? 'PRODUCTS') }}
                </a>
            </div>

            {{-- Title --}}
            <h1 class="pdp-title">{{ $product->name }}</h1>

            {{-- Rating --}}
            <div class="pdp-rating-row">
                @if($reviews->isNotEmpty())
                    <div class="pdp-stars">
                        @for($i = 0; $i < 5; $i++)
                            {{ $i < floor($averageRating) ? '★' : ($i < $averageRating ? '½' : '☆') }}
                        @endfor
                    </div>
                    <span class="pdp-rating-val">{{ $averageRating }}</span>
                    <span class="pdp-rating-count">({{ $reviews->count() }} {{ __('general.reviews') }})</span>
                @else
                    <span class="pdp-rating-count">{{ __('general.no_reviews') }}</span>
                @endif
            </div>

            {{-- Price --}}
            <div class="pdp-price">{{ config('shop.currency_symbol', '₪') }}{{ number_format($product->price, 2) }}</div>

            {{-- SKU --}}
            @if($product->sku)
                <div class="pdp-sku">{{ __('general.sku') }}: {{ $product->sku }}</div>
            @endif

            {{-- Stock badge --}}
            @if($product->stock > 0 && $product->stock <= $product->stock_alert_threshold)
                <div class="pdp-stock-low">⚡ {{ __('general.low_stock', ['count' => $product->stock]) }}</div>
            @elseif($product->stock === 0)
                <div class="pdp-stock-out">✕ {{ __('general.out_of_stock') }}</div>
            @endif

            {{-- Feature pills --}}
            <div class="pdp-features">
                <div class="pdp-feat-pill">🚚 {{ __('general.free_shipping') }}</div>
                <div class="pdp-feat-pill">🔄 {{ __('general.product_replace') }}</div>
                <div class="pdp-feat-pill">💳 {{ __('general.emi_available') }}</div>
                <div class="pdp-feat-pill">⏰ {{ __('general.support_247') }}</div>
            </div>

            {{-- Color swatches --}}
            @if($product->has_colors && !empty($product->color_swatches))
            <div class="picker-group">
                <label class="picker-lbl">{{ __('general.color') }}</label>
                <div class="color-swatches">
                    @foreach($product->color_swatches as $idx => $color)
                        <div class="swatch {{ $idx === 0 ? 'active' : '' }}"
                             style="background:{{ $color['hex'] }};"
                             onclick="selectColor(this)"
                             title="{{ $color['name'] }}"></div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Size picker --}}
            @php $sizes = ['XS','S','M','L','XL','XXL']; @endphp
            <div class="picker-group">
                <label class="picker-lbl">{{ __('general.size') }}</label>
                <div class="size-options">
                    @foreach($sizes as $size)
                        <button class="size-btn {{ $size === 'M' ? 'active' : '' }}"
                                onclick="selectSize(this)"
                                {{ $size === 'XXL' ? 'disabled' : '' }}>{{ $size }}</button>
                    @endforeach
                </div>
                @if($product->size_guide)
                    <button type="button" onclick="openSizeGuide()"
                            style="margin-top:.6rem;background:none;border:none;color:#ea580c;cursor:pointer;font-size:.82rem;font-weight:700;font-family:'DM Sans',sans-serif;">
                        📏 {{ __('general.size_guide') }}
                    </button>
                @endif
            </div>

            {{-- Quantity --}}
            <div class="picker-group">
                <label class="picker-lbl">{{ __('general.quantity') }}</label>
                <div class="qty-row">
                    <button class="qty-btn" onclick="changeQty(-1)">−</button>
                    <input type="number" id="qty" class="qty-input" value="1" min="1" max="{{ $product->max_order_quantity }}">
                    <button class="qty-btn" onclick="changeQty(1)">+</button>
                    <span class="qty-max">Max: {{ $product->max_order_quantity }}</span>
                </div>
            </div>

            {{-- Delivery check --}}
            <div class="delivery-box">
                <label class="picker-lbl">📍 {{ __('general.check_delivery') }}</label>
                <div class="delivery-row">
                    <input type="text" class="delivery-input" id="postalCode"
                           placeholder="{{ __('general.enter_postal_code') }}" maxlength="6">
                    <button class="delivery-check-btn" onclick="checkDelivery()">{{ __('general.check') }}</button>
                </div>
                <div class="delivery-result" id="deliveryResult"></div>
            </div>

            {{-- CTA --}}
            @php $isPreview = \App\Http\Middleware\AdminPreviewMode::isActive(); @endphp
            <form id="add-to-cart-form" method="POST" action="/cart/add">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" id="cartQty" value="1">
                <button type="submit" id="add-to-cart-btn" class="btn-add-cart"
                        data-dusk="add-to-cart-btn"
                        @if($product->stock === 0 || $isPreview) disabled @endif>
                    <i class="ti ti-shopping-cart"></i>
                    {{ __('general.add_to_cart') }}
                    @if($isPreview)<span style="font-size:0.75rem;font-weight:400;opacity:.6;margin-left:0.35rem;">(preview)</span>@endif
                </button>
            </form>

            <form method="POST" action="{{ route('checkout.quick') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" id="quickbuyQty" value="1">
                <button type="submit" class="btn-buy-now" @if($isPreview) disabled @endif>
                    ⚡ {{ __('general.buy_now') }}
                    @if($isPreview)<span style="font-size:0.75rem;font-weight:400;opacity:.6;margin-left:0.35rem;">(preview)</span>@endif
                </button>
            </form>

            {{-- Share --}}
            <div class="share-row">
                <span class="share-lbl">{{ __('general.share') }}</span>
                <a class="share-btn" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" title="Facebook">f</a>
                <a class="share-btn" href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->name) }}" target="_blank" title="Twitter">𝕏</a>
                <a class="share-btn" href="https://www.instagram.com/" target="_blank" title="Instagram">📷</a>
                <a class="share-btn" href="https://api.whatsapp.com/send?text={{ urlencode($product->name.' '.request()->url()) }}" target="_blank" title="WhatsApp">💬</a>
                <a class="share-btn" href="https://pinterest.com/pin/create/button/?url={{ urlencode(request()->url()) }}" target="_blank" title="Pinterest">📌</a>
                <button class="share-btn" onclick="copyLink()" title="Copy link">🔗</button>
            </div>

        </div>{{-- /info --}}
    </div>{{-- /pdp-grid --}}


    {{-- ════════════════════════════════
         SELLER SECTION
    ═════════════════════════════════ --}}
    @if($product->store)
    <div class="seller-section">

        {{-- Avatar overlapping the top border --}}
        <div class="seller-avatar">
            @if($product->store->logo_path)
                <img src="{{ asset($product->store->logo_path) }}" alt="{{ $product->store->name }}">
            @else
                {{ strtoupper(substr($product->store->name, 0, 2)) }}
            @endif
        </div>

        {{-- Left: seller info --}}
        <div class="seller-left">
            <div class="seller-name-row">
                <a href="{{ route('stores.show', $product->store) }}" class="seller-name">
                    {{ $product->store->name }}
                </a>
                <i class="ti ti-circle-check-filled seller-verified" aria-label="{{ __('general.seller_verified') }}"></i>
            </div>
            <p class="seller-tagline">{{ $product->store->tagline }}</p>
            <div class="seller-btns">
                <a href="{{ route('stores.show', $product->store) }}" class="seller-btn seller-btn-primary">
                    <i class="ti ti-layout-grid"></i>
                    {{ __('general.view_store') }}
                </a>
                <a href="{{ route('stores.show', $product->store) }}#contact" class="seller-btn seller-btn-outline">
                    <i class="ti ti-message"></i>
                    {{ __('general.contact_store_short') }}
                </a>
            </div>
        </div>

        {{-- Right: scrollable products from this store --}}
        @if($moreFromStore->isNotEmpty())
        <div class="seller-products-scroll">
            <p style="font-size:.78rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.65rem;">
                {{ __('general.more_from_store', ['store' => $product->store->name]) }}
            </p>
            <div class="seller-products-row">
                @foreach($moreFromStore as $item)
                <a href="{{ route('products.show', $item) }}" class="seller-prod-card">
                    @php
                        $itemImg = $item->images->first()?->image_path
                            ?? ($item->image ? 'images/'.$item->image : null);
                    @endphp
                    @if($itemImg)
                        <img src="{{ asset($itemImg) }}" alt="{{ $item->name }}" class="seller-prod-img">
                    @else
                        <div class="seller-prod-img-ph"><i class="ti ti-photo"></i></div>
                    @endif
                    <div class="seller-prod-info">
                        <span class="seller-prod-name">{{ Str::limit($item->name, 20) }}</span>
                        <span class="seller-prod-price">{{ config('shop.currency_symbol','₪') }}{{ number_format($item->price, 2) }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
    @endif


    {{-- ════════════════════════════════
         TABS
    ═════════════════════════════════ --}}
    <div class="pdp-tabs-card">
        <div class="tabs-nav">
            <button class="tab-btn active" onclick="openTab(event,'details')">
                📋 {{ __('general.product_details') }}
            </button>
            <button class="tab-btn" onclick="openTab(event,'shipping')">
                🚚 {{ __('general.shipping_returns') }}
            </button>
            <button class="tab-btn" onclick="openTab(event,'reviews')">
                ⭐ {{ __('general.reviews_count', ['count' => $reviews->count()]) }}
            </button>
        </div>

        <div class="tabs-body">

            {{-- Details --}}
            <div id="details" class="tab-pane active">
                @if($product->description)
                    <p style="color:#6b7280;line-height:1.75;margin-bottom:1rem;">
                        {{ $product->description }}
                    </p>
                @endif
                <div class="details-grid">
                    @if($product->material)
                        <div class="detail-item">
                            <div class="detail-lbl">🧵 {{ __('general.material') }}</div>
                            <div class="detail-val">{{ $product->material }}</div>
                        </div>
                    @endif
                    @if($product->fit)
                        <div class="detail-item">
                            <div class="detail-lbl">👕 {{ __('general.fit') }}</div>
                            <div class="detail-val">{{ $product->fit }}</div>
                        </div>
                    @endif
                    @if($product->sku)
                        <div class="detail-item">
                            <div class="detail-lbl">🏷️ {{ __('general.sku') }}</div>
                            <div class="detail-val" style="font-family:monospace;">{{ $product->sku }}</div>
                        </div>
                    @endif
                    @if($product->category)
                        <div class="detail-item">
                            <div class="detail-lbl">📂 {{ __('general.category') }}</div>
                            <div class="detail-val">{{ $product->category->name }}</div>
                        </div>
                    @endif
                    @if($product->brand ?? null)
                        <div class="detail-item">
                            <div class="detail-lbl">🏷 {{ __('general.brand') }}</div>
                            <div class="detail-val">{{ $product->brand }}</div>
                        </div>
                    @endif
                    @if($product->care_instructions)
                        <div class="detail-item">
                            <div class="detail-lbl">🫧 {{ __('general.care_instructions') }}</div>
                            <div class="detail-val">{{ $product->care_instructions }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Shipping --}}
            <div id="shipping" class="tab-pane">
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;">
                    <div>
                        <h4 style="font-weight:700;margin-bottom:.5rem;">🚚 {{ __('general.shipping') }}</h4>
                        <p style="color:#6b7280;line-height:1.7;">
                            {{ $product->delivery_estimate ?? __('general.default_delivery_estimate') }}
                        </p>
                    </div>
                    <div>
                        <h4 style="font-weight:700;margin-bottom:.5rem;">↩️ {{ __('general.returns_exchanges') }}</h4>
                        <p style="color:#6b7280;line-height:1.7;">
                            {{ $product->shipping_returns ?? __('general.default_return_policy') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Reviews --}}
            <div id="reviews" class="tab-pane">
                @if($reviews->isNotEmpty())
                    <div class="review-stats">
                        <div style="text-align:center;">
                            <div class="avg-big">{{ $averageRating }}</div>
                            <div class="avg-stars">
                                @for($i=0;$i<5;$i++) {{ $i < floor($averageRating) ? '★' : '☆' }} @endfor
                            </div>
                            <div class="avg-count">{{ $reviews->count() }} {{ __('general.reviews') }}</div>
                        </div>
                        <div class="rating-bars">
                            @for($r=5;$r>=1;$r--)
                                @php $c=$ratingsBreakdown[$r]??0; $pct=$reviews->count()>0?round(($c/$reviews->count())*100):0; @endphp
                                <div class="rating-bar">
                                    <span class="bar-lbl">{{ $r }}★</span>
                                    <div class="bar-track"><div class="bar-fill" style="width:{{ $pct }}%"></div></div>
                                    <span class="bar-count">{{ $c }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="reviews-list">
                        @foreach($reviews as $review)
                            <div class="review-card">
                                <div class="review-author">{{ $review->user->name }}</div>
                                <div class="review-meta">
                                    @if(in_array($review->user_id, $verifiedBuyerIds))
                                        <span class="review-badge">✓ {{ __('general.verified_buyer') }}</span>
                                    @endif
                                    <span>{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="review-stars">
                                    @for($i=0;$i<5;$i++) {{ $i < $review->rating ? '★' : '☆' }} @endfor
                                </div>
                                <p class="review-text">{{ $review->comment }}</p>
                                <div style="display:flex;align-items:center;gap:.5rem;">
                                    <span style="font-size:.78rem;color:#9ca3af;">{{ __('general.helpful') }}</span>
                                    <button class="helpful-btn" onclick="voteHelpful({{ $review->id }})">
                                        👍 {{ $review->helpful_votes ?? 0 }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="text-align:center;color:#9ca3af;padding:2rem 0;">{{ __('general.no_reviews') }}</p>
                @endif
            </div>

        </div>
    </div>{{-- /tabs --}}


    {{-- ════════════════════════════════
         RELATED PRODUCTS (3 cols)
    ═════════════════════════════════ --}}
    @if($relatedProducts->isNotEmpty())
    <div style="margin-bottom:3.5rem;">
        <h2 class="section-heading">
            <span>💡</span> {{ __('general.related_products') }}
        </h2>
        <div class="related-grid">
            @foreach($relatedProducts->take(3) as $related)
            @php
                $relImg = $related->images->first()?->image_path
                    ?? ($related->image ? 'images/'.$related->image : null);
            @endphp
            <a href="{{ route('products.show', $related) }}" class="related-card">
                @if($relImg)
                    <img src="{{ asset($relImg) }}" alt="{{ $related->name }}" class="related-img">
                @else
                    <div class="related-img" style="display:flex;align-items:center;justify-content:center;color:#ccc;font-size:2rem;">
                        <i class="ti ti-photo"></i>
                    </div>
                @endif
                <div class="related-body">
                    <div class="related-name">{{ $related->name }}</div>
                    <div class="related-price">{{ config('shop.currency_symbol','₪') }}{{ number_format($related->price, 2) }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif


    {{-- ════════════════════════════════
         RECENTLY VIEWED (3 cols, horizontal cards)
    ═════════════════════════════════ --}}
    @if($recentlyViewed->isNotEmpty())
    <div style="margin-bottom:3.5rem;">
        <h2 class="section-heading">
            <span>🕒</span> {{ __('general.recently_viewed') }}
        </h2>
        <div class="rv-grid">
            @foreach($recentlyViewed->take(3) as $item)
            @php
                $rvImg = $item->images->first()?->image_path
                    ?? ($item->image ? 'images/'.$item->image : null);
            @endphp
            <a href="{{ route('products.show', $item) }}" class="rv-card">
                @if($rvImg)
                    <img src="{{ asset($rvImg) }}" alt="{{ $item->name }}" class="rv-img">
                @else
                    <div class="rv-img-ph"><i class="ti ti-photo"></i></div>
                @endif
                <div>
                    <div class="rv-name">{{ $item->name }}</div>
                    <div class="rv-price">{{ config('shop.currency_symbol','₪') }}{{ number_format($item->price, 2) }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>{{-- /pdp-wrap --}}


{{-- 3D viewer is now inline inside .gallery-main — no overlay needed --}}


{{-- ════════════════════════════════
     SIZE GUIDE MODAL
═════════════════════════════════ --}}
<div id="sizeGuideOverlay" class="sgm-overlay" onclick="if(event.target===this)closeSizeGuide()">
    <div class="sgm-box">
        <button class="sgm-close" onclick="closeSizeGuide()">✕</button>
        <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:800;margin-bottom:1rem;">
            📏 {{ __('general.size_guide') }}
        </h2>
        @if($product->size_guide)
            <p style="color:#6b7280;line-height:1.7;">{{ $product->size_guide }}</p>
        @else
            <p style="color:#6b7280;">{{ __('general.default_size_guide') ?? 'Standard sizing chart and fit guide available upon request.' }}</p>
        @endif
    </div>
</div>


{{-- ════════════════════════════════
     TRY-ON MODAL (full logic preserved)
═════════════════════════════════ --}}
@auth
@if($product->is3DReady() && config('model3d.tryon.enabled', false))
<div id="tryon-modal-overlay">
    <div id="tryon-modal">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #F2EDE6">
            <div>
                <div id="tryon-modal-title" style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:#1A1A1A">{{ __('general.virtual_tryon') }}</div>
                <div id="tryon-modal-subtitle" style="font-size:13px;color:#6B6B6B;margin-top:2px">See how {{ $product->name }} fits on you</div>
            </div>
            <button type="button" onclick="closeTryOnModal()" style="background:none;border:none;font-size:28px;line-height:1;color:#6B6B6B;cursor:pointer">&times;</button>
        </div>

        {{-- Step pills --}}
        <div style="display:flex;gap:8px;padding:14px 24px;background:#FBF7F1;font-size:12px;color:#6B6B6B">
            <span id="tryon-pill-1" style="padding:4px 10px;border-radius:999px;background:#fff;border:1px solid #E8E0D8;font-weight:600">1. Upload</span>
            <span id="tryon-pill-2" style="padding:4px 10px;border-radius:999px;background:#fff;border:1px solid #E8E0D8">2. Process</span>
            <span id="tryon-pill-3" style="padding:4px 10px;border-radius:999px;background:#fff;border:1px solid #E8E0D8">3. Result</span>
        </div>

        {{-- Upload state --}}
        <div id="tryon-upload-state" style="padding:24px">
            <label for="tryon-photo" id="tryon-drop-zone"
                   style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:28px;border:2px dashed #E8621A;border-radius:16px;background:#FFF8F2;cursor:pointer;text-align:center">
                <div style="font-size:42px">🧍</div>
                <div style="font-weight:700;color:#1A1A1A">{{ __('general.tryon_upload_prompt') }}</div>
                <div style="font-size:12px;color:#6B6B6B">{{ __('general.tryon_photo_hint') }}</div>
                <div style="font-size:11px;color:#9A9A9A">{{ __('general.tryon_file_hint') }}</div>
                <input id="tryon-photo" name="photo" type="file" accept="image/jpeg,image/png" hidden>
            </label>
            <img id="tryon-photo-preview" alt="Preview"
                 style="display:none;margin-top:12px;width:100%;max-height:240px;object-fit:contain;border-radius:12px;border:1px solid #E8E0D8;background:#1A1A1A">
            <div style="margin-top:18px">
                <label for="tryon-height" style="display:block;font-size:13px;font-weight:600;color:#1A1A1A;margin-bottom:6px">{{ __('general.tryon_height_label') }}</label>
                <div style="display:flex;align-items:center;gap:8px">
                    <input id="tryon-height" name="height_cm" type="number" min="100" max="250" placeholder="e.g. 175"
                           style="flex:1;padding:10px 12px;border:1px solid #E8E0D8;border-radius:10px;font-size:14px">
                    <span style="color:#6B6B6B;font-size:13px">cm</span>
                </div>
            </div>
            <div style="margin-top:18px;display:flex;flex-direction:column;gap:10px">
                <label style="display:flex;align-items:flex-start;gap:8px;font-size:13px;color:#1A1A1A;cursor:pointer">
                    <input id="tryon-save-body" type="checkbox" checked style="margin-top:3px">
                    <span>{{ __('general.tryon_save_body') }} <span style="color:#9A9A9A">(faster next time)</span></span>
                </label>
                <label style="display:flex;align-items:flex-start;gap:8px;font-size:13px;color:#1A1A1A;cursor:pointer">
                    <input id="tryon-consent-photo" type="checkbox" style="margin-top:3px">
                    <span>{{ __('general.tryon_keep_photo') }} <span style="color:#9A9A9A">(otherwise deleted after 24h)</span></span>
                </label>
                <div style="font-size:11px;color:#9A9A9A;line-height:1.5">{{ __('general.tryon_privacy_note') }}</div>
            </div>
            <button id="tryon-submit-btn" type="button" onclick="submitTryOn()"
                    style="margin-top:20px;width:100%;background:#ea580c;color:#fff;border:none;border-radius:12px;padding:14px;font-size:15px;font-weight:700;cursor:pointer">
                {{ __('general.tryon_generate_btn') }}
            </button>
        </div>

        {{-- Processing state --}}
        <div id="tryon-processing-state" style="display:none;padding:24px">
            <div style="display:flex;justify-content:center;margin-bottom:18px">
                <div id="tryon-blob"
                     style="width:120px;height:120px;border-radius:50%;background:radial-gradient(circle at 30% 30%, #F5A673, #E8621A 70%);box-shadow:0 0 60px rgba(232,98,26,0.4);animation:tryonBlobPulse 2.4s ease-in-out infinite"></div>
            </div>
            <div style="text-align:center;font-weight:700;color:#1A1A1A;margin-bottom:14px">{{ __('general.tryon_creating') }}</div>
            <ol style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;font-size:13px">
                <li id="tryon-step-1" data-status="active" style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:#FBF7F1;border-radius:10px">
                    <span class="tryon-step-dot"></span><span>{{ __('general.tryon_step_upload') }}</span>
                </li>
                <li id="tryon-step-2" data-status="pending" style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:#FBF7F1;border-radius:10px">
                    <span class="tryon-step-dot"></span><span>{{ __('general.tryon_step_body') }}</span>
                </li>
                <li id="tryon-step-3" data-status="pending" style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:#FBF7F1;border-radius:10px">
                    <span class="tryon-step-dot"></span><span>{{ __('general.tryon_step_fitting', ['product' => $product->name]) }}</span>
                </li>
                <li id="tryon-step-4" data-status="pending" style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:#FBF7F1;border-radius:10px">
                    <span class="tryon-step-dot"></span><span>{{ __('general.tryon_step_finalizing') }}</span>
                </li>
            </ol>
            <div style="margin-top:18px">
                <div style="display:flex;justify-content:space-between;font-size:12px;color:#6B6B6B;margin-bottom:6px">
                    <span id="tryon-est">Starting…</span>
                    <span id="tryon-pct">0%</span>
                </div>
                <div style="height:8px;background:#F2EDE6;border-radius:999px;overflow:hidden">
                    <div id="tryon-bar" style="height:100%;width:0;background:linear-gradient(90deg,#ea580c,#F5A673);transition:width .6s ease"></div>
                </div>
            </div>
            <div style="margin-top:18px;font-size:12px;color:#6B6B6B;text-align:center;line-height:1.5">
                This takes 1–3 minutes. You can close this window — we'll email you when it's ready.
            </div>
            <button type="button" onclick="closeTryOnModal()"
                    style="margin-top:14px;width:100%;background:transparent;color:#6B6B6B;border:1.5px solid #E8E0D8;border-radius:12px;padding:12px;font-size:14px;font-weight:600;cursor:pointer">
                {{ __('general.continue_shopping') }}
            </button>
        </div>

        {{-- Result state --}}
        <div id="tryon-result-state" style="display:none;padding:20px 24px 24px">
            <div style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:#1A1A1A;margin-bottom:12px">{{ __('general.tryon_ready_title') }} 🎉</div>
            <div id="tryon-viewer" style="height:350px;background:#1A1A1A;border-radius:14px;position:relative;overflow:hidden"></div>
            <div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap">
                <button type="button" onclick="closeTryOnModal()"
                        style="flex:1;min-width:140px;background:#ea580c;color:#fff;border:none;border-radius:10px;padding:12px;font-weight:700;cursor:pointer">
                    {{ __('general.tryon_close') }}
                </button>
                <button type="button" onclick="resetTryOnToUpload()"
                        style="flex:1;min-width:140px;background:transparent;color:#1A1A1A;border:1.5px solid #E8E0D8;border-radius:10px;padding:12px;font-weight:600;cursor:pointer">
                    {{ __('general.tryon_try_again') }}
                </button>
                <button type="button" onclick="deleteCurrentTryOn()"
                        style="flex:1;min-width:140px;background:transparent;color:#B91C1C;border:1.5px solid #FECACA;border-radius:10px;padding:12px;font-weight:600;cursor:pointer">
                    {{ __('general.tryon_delete') }}
                </button>
            </div>
            <div style="margin-top:12px;font-size:11px;color:#9A9A9A;text-align:center">{{ __('general.tryon_disclaimer') }}</div>
        </div>

    </div>
</div>
@endif
@endauth


{{-- ════════════════════════════════
     JAVASCRIPT
═════════════════════════════════ --}}
<script>
/* ── thumbnail switch ── */
function switchThumb(el) {
    document.querySelectorAll('.g-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    const img = el.querySelector('img');
    if (img) document.getElementById('mainImage').src = img.src;
}

/* ── color / size pickers ── */
function selectColor(el) {
    document.querySelectorAll('.swatch').forEach(s => s.classList.remove('active'));
    el.classList.add('active');
}
function selectSize(el) {
    if (el.disabled) return;
    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
}

/* ── quantity ── */
function changeQty(delta) {
    const inp = document.getElementById('qty');
    let val = Math.max(1, Math.min(parseInt(inp.value) + delta, parseInt(inp.max)));
    inp.value = val;
    const cq = document.getElementById('cartQty');
    const qb = document.getElementById('quickbuyQty');
    if (cq) cq.value = val;
    if (qb) qb.value = val;
}
document.getElementById('qty')?.addEventListener('change', () => {
    const v = document.getElementById('qty').value;
    const cq = document.getElementById('cartQty'); if(cq) cq.value = v;
    const qb = document.getElementById('quickbuyQty'); if(qb) qb.value = v;
});

/* ── delivery check ── */
function checkDelivery() {
    const postal = document.getElementById('postalCode').value.trim();
    const res = document.getElementById('deliveryResult');
    if (!postal) { res.textContent = ''; return; }
    const ok = parseInt(postal.charCodeAt(0)) % 2 === 0;
    res.className = ok ? 'delivery-result ok' : 'delivery-result unavail';
    res.textContent = ok ? '✓ Delivery available — Est. 3–5 days' : '✕ Not available in this area';
}

/* ── add to cart AJAX ── */
(function () {
    const form = document.getElementById('add-to-cart-form');
    const btn  = document.getElementById('add-to-cart-btn');
    if (!form || !btn) return;
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (btn.disabled) return;
        btn.disabled = true;
        try {
            const res  = await fetch('/cart/add', {
                method: 'POST', body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
            });
            const data = await res.json().catch(() => ({}));
            if (res.ok && data.success) {
                const orig = btn.innerHTML;
                btn.innerHTML = '✓ Added';
                btn.style.background = '#16a34a';
                const badge = document.getElementById('nav-cart-badge');
                if (badge && data.cartCount !== undefined) {
                    badge.textContent = data.cartCount > 99 ? '99+' : data.cartCount;
                    badge.style.display = data.cartCount > 0 ? '' : 'none';
                }
                setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.disabled = false; }, 2000);
            } else {
                btn.disabled = false;
                alert(data.message || (data.errors && Object.values(data.errors)[0]?.[0]) || 'Could not add to cart.');
            }
        } catch { btn.disabled = false; }
    });
}());

/* ── wishlist ── */
function toggleWishlist(id) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    fetch('/likes/' + id + '/toggle', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json().catch(() => ({})))
        .then(data => {
            document.querySelectorAll('.btn-wishlist, .gallery-wish-btn').forEach(el => {
                el.classList.toggle('active', data.liked !== undefined ? data.liked : !el.classList.contains('active'));
            });
        });
}

/* ── copy link ── */
function copyLink() { navigator.clipboard.writeText(window.location.href).then(() => alert('Link copied!')); }

/* ── tabs ── */
function openTab(e, name) {
    document.querySelectorAll('.tab-pane').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(name).classList.add('active');
    e.target.classList.add('active');
}

/* ── review helpful ── */
function voteHelpful(reviewId) {
    fetch('/reviews/' + reviewId + '/helpful', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(() => location.reload());
}

/* ── size guide ── */
function openSizeGuide()  { document.getElementById('sizeGuideOverlay').classList.add('open'); }
function closeSizeGuide() { document.getElementById('sizeGuideOverlay').classList.remove('open'); }

/* ── 3D Viewer — inline gallery replacement ── */
const product3dModelPath = @json($product->is3DReady() && $product->model3d_path ? asset('storage/models/' . $product->id . '/' . $product->model3d_path) : null);
let viewerInitialized = false;

function open3DInline() {
    const viewer   = document.getElementById('viewer-3d');
    const closeBtn = document.getElementById('btn-close-3d');
    const openBtn  = document.getElementById('btn-open-3d');
    if (!viewer) return;

    viewer.style.display   = 'block';
    if (closeBtn) closeBtn.style.display = 'block';
    if (openBtn)  openBtn.style.display  = 'none';

    if (!viewerInitialized && typeof initViewer3D === 'function') {
        if (!product3dModelPath) { close3DInline(); return; }
        initViewer3D('viewer-3d', product3dModelPath);
        viewerInitialized = true;
    }
}

function close3DInline() {
    const viewer   = document.getElementById('viewer-3d');
    const closeBtn = document.getElementById('btn-close-3d');
    const openBtn  = document.getElementById('btn-open-3d');

    if (viewer)   viewer.style.display   = 'none';
    if (closeBtn) closeBtn.style.display = 'none';
    if (openBtn)  openBtn.style.display  = '';

    if (typeof disposeViewer3D === 'function') {
        disposeViewer3D();
        viewerInitialized = false;
    }
}

/* legacy aliases so nothing else breaks */
function open3DModal()  { open3DInline(); }
function close3DModal() { close3DInline(); }
function handle3DOverlayClick() {}
function toggle3DViewer(show) { show ? open3DInline() : close3DInline(); }
</script>

@if($product->is3DReady())
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script src="{{ asset('js/viewer3d.js') }}?v={{ filemtime(public_path('js/viewer3d.js')) }}"></script>
@endif

@auth
@if($product->is3DReady() && config('model3d.tryon.enabled', false))
<script>
(function () {
    const TRYON_PRODUCT_ID = {{ $product->id }};
    const USER_ID          = {{ auth()->id() }};
    const STORAGE_KEY      = `tryon_${TRYON_PRODUCT_ID}_${USER_ID}`;
    const csrfToken        = document.querySelector('meta[name="csrf-token"]').content;

    let pollTimer = null, currentTryonId = null, viewerInited = false;
    let estTimer = null, estStart = 0;
    const $ = id => document.getElementById(id);

    function showState(s) {
        ['tryon-upload-state','tryon-processing-state','tryon-result-state']
            .forEach(n => { const el=$(n); if(el) el.style.display = n===s ? 'block':'none'; });
    }

    window.openTryOnModal = function () {
        $('tryon-modal-overlay').classList.add('is-open');
        document.body.style.overflow = 'hidden';
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) { currentTryonId = parseInt(saved, 10); checkStatus(true); }
        else showState('tryon-upload-state');
    };

    window.closeTryOnModal = function () {
        $('tryon-modal-overlay').classList.remove('is-open');
        document.body.style.overflow = '';
        if (pollTimer) { clearTimeout(pollTimer); pollTimer = null; }
        if (estTimer)  { clearInterval(estTimer); estTimer = null; }
    };

    const photoInput = $('tryon-photo');
    const preview    = $('tryon-photo-preview');
    if (photoInput) {
        photoInput.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = ev => { if (preview) { preview.src = ev.target.result; preview.style.display = 'block'; } };
            reader.readAsDataURL(file);
        });
    }

    window.submitTryOn = async function () {
        const photo = photoInput.files[0];
        if (!photo) { window.showToast('Please choose a full-body photo first.', 'error'); return; }
        const fd = new FormData();
        fd.append('photo', photo);
        const h = $('tryon-height')?.value;
        if (h) fd.append('height_cm', h);
        fd.append('photo_consent', $('tryon-consent-photo')?.checked ? '1' : '0');
        fd.append('save_body',     $('tryon-save-body')?.checked     ? '1' : '0');
        const btn = $('tryon-submit-btn');
        if (btn) { btn.disabled = true; btn.textContent = 'Submitting…'; }
        try {
            const res  = await fetch(`/products/${TRYON_PRODUCT_ID}/tryon`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: fd,
            });
            const data = await res.json();
            if (!res.ok || !data.success) {
                window.showToast(data.message || 'Could not start the try-on.', 'error');
                if (btn) { btn.disabled = false; btn.textContent = 'Generate Try-On'; }
                return;
            }
            currentTryonId = data.tryon_id;
            localStorage.setItem(STORAGE_KEY, String(currentTryonId));
            startProcessing();
        } catch (err) {
            window.showToast('Network error. Please try again.', 'error');
            if (btn) { btn.disabled = false; btn.textContent = 'Generate Try-On'; }
        }
    };

    function startProcessing() {
        showState('tryon-processing-state');
        setStep(1,'done'); setStep(2,'active'); setStep(3,'pending'); setStep(4,'pending');
        startEst(); pollNow();
    }
    function setStep(n, status) { const li=$(`tryon-step-${n}`); if(li) li.dataset.status=status; }
    function startEst() {
        estStart = Date.now(); const total = 90;
        if (estTimer) clearInterval(estTimer);
        estTimer = setInterval(() => {
            const elapsed = (Date.now()-estStart)/1000;
            const pct = Math.min(95, Math.round((elapsed/total)*100));
            const bar=$('tryon-bar'); if(bar) bar.style.width=pct+'%';
            const pctEl=$('tryon-pct'); if(pctEl) pctEl.textContent=pct+'%';
            const est=$('tryon-est'); if(est) est.textContent=`≈ ${Math.max(0,Math.round(total-elapsed))}s remaining`;
        }, 500);
    }
    function pollNow() { if(pollTimer) clearTimeout(pollTimer); checkStatus(false); }
    async function checkStatus(reopen) {
        if (!currentTryonId) { showState('tryon-upload-state'); return; }
        try {
            const res = await fetch(`/tryon/${currentTryonId}/status`, { headers:{'Accept':'application/json'} });
            if (res.status===403||res.status===404) { localStorage.removeItem(STORAGE_KEY); currentTryonId=null; showState('tryon-upload-state'); return; }
            handleStatus(await res.json(), reopen);
        } catch { pollTimer = setTimeout(pollNow, 5000); }
    }
    function handleStatus(data, reopen) {
        if (data.status==='pending'||data.status==='processing_body') {
            if(reopen) showState('tryon-processing-state');
            if(!estTimer) startEst();
            setStep(1,'done'); setStep(2,'active'); setStep(3,'pending'); setStep(4,'pending');
            pollTimer = setTimeout(pollNow, 5000);
        } else if (data.status==='processing_fit') {
            if(reopen) showState('tryon-processing-state');
            if(!estTimer) startEst();
            setStep(1,'done'); setStep(2,'done'); setStep(3,'active'); setStep(4,'pending');
            pollTimer = setTimeout(pollNow, 5000);
        } else if (data.status==='ready') {
            if(estTimer){clearInterval(estTimer);estTimer=null;}
            setStep(1,'done');setStep(2,'done');setStep(3,'done');setStep(4,'done');
            const bar=$('tryon-bar');if(bar)bar.style.width='100%';
            const pctEl=$('tryon-pct');if(pctEl)pctEl.textContent='100%';
            loadResult(data.result_url);
        } else if (data.status==='failed') {
            if(estTimer){clearInterval(estTimer);estTimer=null;}
            window.showToast(data.error_message||'Try-on failed. Please try again.','error');
            localStorage.removeItem(STORAGE_KEY); currentTryonId=null;
            const btn=$('tryon-submit-btn');
            if(btn){btn.disabled=false;btn.textContent='Generate Try-On';}
            showState('tryon-upload-state');
        }
    }
    function loadResult(resultUrl) {
        showState('tryon-result-state');
        if (resultUrl && typeof window.initViewer3D==='function' && !viewerInited) {
            window.initViewer3D('tryon-viewer', resultUrl); viewerInited=true;
        }
    }
    window.resetTryOnToUpload = function () {
        if(pollTimer){clearTimeout(pollTimer);pollTimer=null;}
        if(estTimer){clearInterval(estTimer);estTimer=null;}
        if(viewerInited && typeof window.disposeViewer3D==='function'){window.disposeViewer3D();viewerInited=false;}
        currentTryonId=null; localStorage.removeItem(STORAGE_KEY);
        if(photoInput) photoInput.value='';
        if(preview){preview.src='';preview.style.display='none';}
        const btn=$('tryon-submit-btn');
        if(btn){btn.disabled=false;btn.textContent='Generate Try-On';}
        showState('tryon-upload-state');
    };
    window.deleteCurrentTryOn = async function () {
        if (!currentTryonId) { window.resetTryOnToUpload(); return; }
        if (!confirm('Delete this try-on? This cannot be undone.')) return;
        try {
            const res = await fetch(`/tryon/${currentTryonId}`, {
                method:'DELETE', headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'}
            });
            if(res.ok){window.showToast('Try-on deleted.','success');window.resetTryOnToUpload();}
            else window.showToast('Could not delete try-on.','error');
        } catch { window.showToast('Network error.','error'); }
    };

    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            currentTryonId = parseInt(saved, 10);
            fetch(`/tryon/${currentTryonId}/status`, {headers:{'Accept':'application/json'}})
                .then(r => r.ok ? r.json() : null)
                .then(d => { if(!d) localStorage.removeItem(STORAGE_KEY); });
        }
    });
}());
</script>
@endif
@endauth

</x-layout>
