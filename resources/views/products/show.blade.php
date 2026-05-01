<x-layout :title="$product->name">

<style>
/* ═══ PAGE LAYOUT ═══ */
.pdp-page { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem 5rem; padding-top: 6rem; }
.pdp-grid { display: grid; grid-template-columns: 1fr 1.1fr; gap: 3rem; margin-bottom: 4rem; }
@media (max-width: 900px) { .pdp-grid { grid-template-columns: 1fr; gap: 2rem; } }

/* ═══ IMAGE GALLERY ═══ */
.gallery-main { position: relative; aspect-ratio: 1/1; background: var(--gray-100); border-radius: 1rem; overflow: hidden; margin-bottom: 0.75rem; box-shadow: var(--shadow-sm); }
.gallery-main img { width: 100%; height: 100%; object-fit: cover; display: block; }
.badge-sale { position: absolute; top: 12px; left: 12px; background: #ef4444; color: #fff; padding: 0.6rem 1.1rem; border-radius: 999px; font-size: 0.8rem; font-weight: 700; z-index: 5; }
.badge-new { position: absolute; top: 12px; right: 12px; background: #2563eb; color: #fff; padding: 0.6rem 1.1rem; border-radius: 999px; font-size: 0.8rem; font-weight: 700; z-index: 5; }
.gallery-thumbs { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; }
.thumb { aspect-ratio: 1/1; background: var(--gray-100); border-radius: 0.5rem; cursor: pointer; overflow: hidden; border: 2px solid transparent; transition: border-color 0.15s; }
.thumb img { width: 100%; height: 100%; object-fit: cover; }
.thumb.active { border-color: var(--orange); }
.gallery-wishlist { position: absolute; top: 12px; right: 12px; width: 44px; height: 44px; background: #fff; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 12px rgba(0,0,0,0.1); color: var(--gray-400); transition: color 0.2s, transform 0.2s; font-size: 1.3rem; z-index: 4; }
.gallery-wishlist:hover { color: #ef4444; transform: scale(1.1); }
.gallery-wishlist.active { color: #ef4444; }

/* ═══ PRODUCT INFO ═══ */
.prod-category { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--orange); margin-bottom: 0.5rem; }
.prod-title { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 800; color: var(--gray-900); line-height: 1.2; margin-bottom: 0.75rem; }
.prod-meta { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; font-size: 0.9rem; }
.rating-stars { display: flex; gap: 0.2rem; color: #f59e0b; }
.rating-count { color: var(--muted); }
.prod-price { font-size: 2rem; font-weight: 800; color: var(--gray-900); margin-bottom: 0.5rem; }
.prod-sku { font-size: 0.75rem; color: var(--muted); letter-spacing: 0.1em; text-transform: uppercase; }

/* ═══ FEATURE PILLS ═══ */
.feature-pills { display: flex; gap: 0.75rem; margin: 1.75rem 0; flex-wrap: wrap; }
.pill { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; font-weight: 600; padding: 0.5rem 0.9rem; background: #f9fafb; border-radius: 999px; color: var(--gray-600); }

/* ═══ PICKERS ═══ */
.picker-group { margin-bottom: 1.5rem; }
.picker-label { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--gray-600); margin-bottom: 0.6rem; display: block; }
.color-swatches { display: flex; gap: 1rem; flex-wrap: wrap; }
.swatch { width: 50px; height: 50px; border-radius: 50%; border: 3px solid transparent; cursor: pointer; transition: border-color 0.15s, transform 0.15s; position: relative; }
.swatch:hover { transform: scale(1.1); }
.swatch.active { border-color: var(--gray-900); }
.size-options { display: grid; grid-template-columns: repeat(auto-fit, minmax(55px, 1fr)); gap: 0.6rem; }
.size-btn { padding: 0.7rem 1rem; border: 1.5px solid var(--gray-200); border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600; background: #fff; color: var(--gray-700); cursor: pointer; transition: all 0.15s; position: relative; }
.size-btn:hover { border-color: var(--orange); }
.size-btn.active { background: var(--orange); color: #fff; border-color: var(--orange); }
.size-btn:disabled { background: var(--gray-100); color: var(--gray-400); cursor: not-allowed; }
.size-btn:disabled::after { content: 'Out'; position: absolute; top: 100%; right: 0; font-size: 0.64rem; color: #ef4444; font-weight: 700; white-space: nowrap; }

/* ═══ QUANTITY & CTA ═══ */
.qty-selector { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; }
.qty-btn { width: 36px; height: 36px; border: 1.5px solid var(--gray-200); background: #fff; border-radius: 0.5rem; cursor: pointer; font-weight: 700; transition: all 0.15s; font-size: 0.9rem; }
.qty-btn:hover { border-color: var(--orange); }
.qty-input { width: 60px; text-align: center; border: 1.5px solid var(--gray-200); border-radius: 0.5rem; padding: 0.4rem 0.6rem; font-size: 0.95rem; font-weight: 600; }
.qty-max { font-size: 0.75rem; color: var(--muted); margin-left: 0.75rem; }

.delivery-wrap { background: #f9fafb; border-radius: 0.75rem; padding: 1rem; margin-bottom: 1.5rem; }
.delivery-form { display: flex; gap: 0.5rem; margin-top: 0.5rem; }
.delivery-input { flex: 1; border: 1.5px solid var(--gray-200); border-radius: 0.5rem; padding: 0.6rem 0.75rem; font-size: 0.85rem; font-family: 'DM Sans', sans-serif; }
.delivery-btn { background: var(--orange); color: #fff; border: none; border-radius: 0.5rem; padding: 0.6rem 1rem; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: background 0.15s; font-family: 'DM Sans', sans-serif; }
.delivery-btn:hover { background: var(--orange-dark); }
.delivery-result { margin-top: 0.6rem; font-size: 0.8rem; color: var(--gray-600); }
.delivery-result.ok { color: #16a34a; }
.delivery-result.unavail { color: #ef4444; }

.cta-row { display: flex; gap: 0.75rem; margin-bottom: 1.5rem; }
.btn-buy { flex: 1; background: var(--orange); color: #fff; border: none; padding: 0.9rem 1.5rem; border-radius: 0.75rem; font-size: 0.95rem; font-weight: 700; cursor: pointer; transition: background 0.15s; font-family: 'DM Sans', sans-serif; }
.btn-buy:hover { background: var(--orange-dark); }
.btn-wishlist { flex: 0 0 48px; background: #fff; border: 1.5px solid var(--gray-200); border-radius: 0.75rem; cursor: pointer; font-size: 1.3rem; display: flex; align-items: center; justify-content: center; transition: all 0.15s; }
.btn-wishlist:hover { border-color: #ef4444; color: #ef4444; }
.btn-wishlist.active { background: #fee2e2; border-color: #ef4444; color: #ef4444; }

/* ═══ SHARE & URGENCY ═══ */
.share-wrap { border-top: 1.5px solid var(--gray-200); padding-top: 1rem; margin-top: 1.5rem; }
.share-buttons { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.share-btn { width: 40px; height: 40px; border-radius: 50%; border: 1.5px solid var(--gray-200); background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s; font-size: 0.9rem; }
.share-btn:hover { border-color: var(--orange); background: var(--orange-pale); color: var(--orange); }

.urgency-alert { background: #fef3c7; border: 1.5px solid #fcd34d; border-radius: 0.75rem; padding: 0.8rem 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-size: 0.85rem; font-weight: 600; color: #92400e; }
.urgency-alert.unavail { background: #fee2e2; border-color: #fecaca; color: #991b1b; }

/* ═══ TABS ═══ */
.tabs-nav { display: flex; gap: 0; border-bottom: 2px solid var(--gray-200); margin: 2rem 0; }
.tab-btn { background: none; border: none; padding: 1rem 1.5rem; font-size: 0.9rem; font-weight: 600; color: var(--gray-500); cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.15s; margin-bottom: -2px; font-family: 'DM Sans', sans-serif; }
.tab-btn:hover { color: var(--orange); }
.tab-btn.active { color: var(--gray-900); border-bottom-color: var(--orange); }
.tabs-content { display: none; }
.tabs-content.active { display: block; }

/* ═══ REVIEWS ═══ */
.reviews-section { }
.review-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem; }
.stat-avg { display: flex; align-items: center; gap: 1.5rem; }
.avg-big { font-size: 3rem; font-weight: 800; color: var(--orange); }
.avg-meta { display: flex; flex-direction: column; gap: 0.3rem; }
.avg-stars { color: #f59e0b; font-size: 0.9rem; }
.avg-count { font-size: 0.8rem; color: var(--gray-500); }
.rating-bars { display: flex; flex-direction: column; gap: 0.75rem; }
.rating-bar { display: flex; align-items: center; gap: 0.75rem; font-size: 0.85rem; }
.bar-label { width: 50px; text-align: right; font-weight: 600; color: var(--gray-600); }
.bar-track { flex: 1; height: 8px; background: var(--gray-200); border-radius: 999px; overflow: hidden; }
.bar-fill { height: 100%; background: #f59e0b; border-radius: 999px; }
.bar-count { width: 50px; text-align: left; color: var(--gray-500); font-size: 0.8rem; }

.reviews-list { display: flex; flex-direction: column; gap: 1.5rem; }
.review-card { padding: 1.25rem; border: 1.5px solid var(--gray-200); border-radius: 0.75rem; }
.review-author { font-weight: 700; color: var(--gray-900); }
.review-meta { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--gray-500); margin-top: 0.2rem; }
.review-badge { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.75rem; font-weight: 700; background: #dcfce7; color: #16a34a; padding: 0.15rem 0.5rem; border-radius: 999px; }
.review-stars { color: #f59e0b; font-size: 0.9rem; margin: 0.5rem 0; }
.review-text { color: var(--gray-700); font-size: 0.9rem; line-height: 1.6; margin-bottom: 0.75rem; }
.review-helpful { display: flex; gap: 0.75rem; align-items: center; }
.helpful-btn { background: none; border: 1px solid var(--gray-300); border-radius: 0.5rem; padding: 0.4rem 0.75rem; font-size: 0.75rem; cursor: pointer; transition: all 0.15s; color: var(--gray-600); }
.helpful-btn:hover { border-color: var(--orange); color: var(--orange); }
.helpful-btn.active { background: var(--orange-pale); border-color: var(--orange); color: var(--orange); }

/* ═══ RELATED PRODUCTS ═══ */
.related-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; }
.related-card { background: #fff; border: 1.5px solid var(--gray-200); border-radius: var(--radius); overflow: hidden; text-decoration: none; transition: all 0.15s; }
.related-card:hover { border-color: var(--orange-muted); box-shadow: 0 4px 16px rgba(234,88,12,0.12); }
.related-img { width: 100%; aspect-ratio: 1/1; object-fit: cover; background: var(--gray-100); }
.related-body { padding: 0.9rem; }
.related-name { font-family: 'Playfair Display', serif; font-size: 0.95rem; font-weight: 700; color: var(--gray-900); line-height: 1.3; margin-bottom: 0.4rem; }
.related-price { font-size: 1rem; font-weight: 800; color: var(--gray-900); }

/* ═══ MODAL ═══ */
.modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 100; align-items: center; justify-content: center; }
.modal.active { display: flex; }
.modal-content { background: #fff; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; }
.modal-close { position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--gray-500); }

@media (max-width: 768px) { .review-stats { grid-template-columns: 1fr; } }

/* ═══ 3D VIEWER ═══ */
.btn-3d-view { position: absolute; bottom: 12px; left: 12px; background: #1A1A1A; color: #fff; border: none; padding: 0.6rem 1.1rem; border-radius: 999px; font-size: 0.8rem; font-weight: 700; cursor: pointer; z-index: 5; display: flex; align-items: center; gap: 0.4rem; transition: background 0.15s; font-family: 'DM Sans', sans-serif; }
.btn-3d-view:hover { background: #333; }
.btn-3d-view svg { width: 16px; height: 16px; }
#viewer-3d { display: none; width: 100%; aspect-ratio: 1/1; background: #111; border-radius: 1rem; overflow: hidden; position: relative; margin-bottom: 0.75rem; }
#viewer-3d canvas { display: block; width: 100% !important; height: 100% !important; }
.btn-close-3d { position: absolute; top: 12px; left: 12px; background: rgba(255,255,255,0.12); color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 999px; font-size: 0.8rem; font-weight: 700; cursor: pointer; z-index: 15; transition: background 0.15s; font-family: 'DM Sans', sans-serif; backdrop-filter: blur(8px); }
.btn-close-3d:hover { background: rgba(255,255,255,0.22); }

/* "Generating..." loading overlay */
.viewer-loading { position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%); z-index: 10; font-family: 'DM Sans', sans-serif; color: rgba(255,255,255,0.5); font-size: 0.9rem; letter-spacing: 0.05em; }
@keyframes v3d-dots { 0%,20%{content:''} 40%{content:'.'} 60%{content:'..'} 80%,100%{content:'...'} }
.viewer-loading::after { content: ''; animation: v3d-dots 1.5s steps(1) infinite; }

/* Right-side control panel */
.viewer-panel { position: absolute; top: 1rem; right: 1rem; bottom: 1rem; width: 240px; background: rgba(18,18,18,0.82); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-radius: 1rem; z-index: 12; display: flex; flex-direction: column; font-family: 'DM Sans', sans-serif; color: #fff; overflow: hidden; border: 1px solid rgba(255,255,255,0.07); transition: opacity 0.3s, transform 0.3s; }
.viewer-panel.hidden { opacity: 0; pointer-events: none; transform: translateX(20px); }
.viewer-panel-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.15rem 0.5rem; }
.viewer-panel-title { font-size: 0.95rem; font-weight: 700; }
.viewer-panel-close { background: rgba(255,255,255,0.08); border: none; color: rgba(255,255,255,0.6); width: 30px; height: 30px; border-radius: 50%; cursor: pointer; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; transition: background 0.15s; }
.viewer-panel-close:hover { background: rgba(255,255,255,0.16); color: #fff; }
.viewer-panel-body { flex: 1; padding: 0.25rem 1.15rem; overflow-y: auto; }
.viewer-panel-section { padding: 0.7rem 0; border-bottom: 1px solid rgba(255,255,255,0.06); }
.viewer-panel-section:last-child { border-bottom: none; }
.viewer-panel-section-title { font-size: 0.78rem; font-weight: 700; color: #E8621A; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.45rem; }
.viewer-panel-section-title svg { width: 16px; height: 16px; opacity: 0.85; }
.viewer-panel-instruction { font-size: 0.72rem; color: rgba(255,255,255,0.45); line-height: 1.55; }
.viewer-panel-instruction span { color: rgba(255,255,255,0.8); font-weight: 600; }
.viewer-panel-footer { padding: 0.75rem 1.15rem 1rem; }
.viewer-panel-ok { width: 100%; background: #E8621A; color: #fff; border: none; padding: 0.6rem; border-radius: 0.6rem; font-size: 0.82rem; font-weight: 700; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background 0.15s; }
.viewer-panel-ok:hover { background: #d4570f; }
@media (max-width: 768px) {
    .btn-3d-view { padding: 0.7rem 1.2rem; font-size: 0.85rem; min-height: 44px; }
    .viewer-panel { top: auto; right: 0; bottom: 0; left: 0; width: 100%; max-height: 50%; border-radius: 1rem 1rem 0 0; }
    .viewer-panel.hidden { opacity: 0; transform: translateY(100%); }
}
</style>

<div class="pdp-page">
    <div class="pdp-grid">
        {{-- LEFT: GALLERY ──--}}
        <div>
            <div class="gallery-main">
                @if($product->sale_badge)
                    <div class="badge-sale">{{ $product->sale_badge }}</div>
                @endif
                @if($product->is_new)
                    <div class="badge-new">NEW</div>
                @endif
                <img id="mainImage" src="{{ asset('images/' . ($product->images->first()?->image ?? $product->image)) }}" alt="{{ $product->name }}">
                @unless(\App\Http\Middleware\AdminPreviewMode::isActive())
                <button class="btn-wishlist" id="wishlistBtn" onclick="toggleWishlist({{ $product->id }})">♥</button>
                @endunless
                @php
                    $m3dState = $product->model3d_status ?? 'idle';
                    $m3dReady = $product->is3DReady();
                    $m3dBusy  = $product->is3DProcessing();
                @endphp
                @if($m3dReady)
                    <button class="btn-3d-view" onclick="toggle3DViewer(true)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        View in 3D
                    </button>
                @elseif($m3dBusy)
                    <button class="btn-3d-view" style="background:#d97706;opacity:0.85;cursor:not-allowed;" disabled title="Your 3D model is being generated. Check back soon!">
                        <span style="width:12px;height:12px;border:2px solid #fff;border-top-color:transparent;border-radius:50%;display:inline-block;animation:m3dStoreSpin 0.8s linear infinite;"></span>
                        Generating 3D…
                    </button>
                @else
                    <button class="btn-3d-view" onclick="toggle3DViewer(true)"
                            style="background:transparent;color:#6B6B6B;border:1.5px solid #E8E0D8;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        View in 3D (Demo)
                    </button>
                @endif
                <style>@keyframes m3dStoreSpin { to { transform: rotate(360deg); } }</style>
            </div>

            {{-- Virtual Try-On --}}
            @if($m3dReady || true)
                @auth
                    <button id="tryon-btn" type="button" onclick="openTryOnModal()"
                            style="background:var(--orange,#E8621A);color:#fff;border:none;border-radius:12px;padding:12px 20px;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;width:100%;justify-content:center;margin-top:10px">
                        <span style="font-size:18px">👗</span>
                        Virtual Try-On
                        <span style="font-size:11px;font-weight:400;opacity:.8">— See how it fits on you</span>
                    </button>
                @else
                    <a href="{{ route('login') }}"
                       style="background:transparent;color:#6B6B6B;border:1.5px solid #E8E0D8;border-radius:12px;padding:12px 20px;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:8px;width:100%;justify-content:center;margin-top:10px;text-decoration:none">
                        🔒 Login to virtually try this on
                    </a>
                @endauth
            @endif

            @auth
            @if($m3dReady || true)
            {{-- Try-On Modal --}}
            <div id="tryon-modal-overlay"
                 style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(26,26,26,0.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:20px">
                <div id="tryon-modal"
                     style="background:#fff;border-radius:24px;border:1px solid #E8E0D8;width:100%;max-width:560px;max-height:92vh;overflow:auto;box-shadow:0 20px 60px rgba(232,98,26,0.2)">

                    {{-- HEADER --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #F2EDE6">
                        <div>
                            <div id="tryon-modal-title" style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:#1A1A1A">Virtual Try-On</div>
                            <div id="tryon-modal-subtitle" style="font-size:13px;color:#6B6B6B;margin-top:2px">See how {{ $product->name }} fits on you</div>
                        </div>
                        <button type="button" onclick="closeTryOnModal()" aria-label="Close"
                                style="background:none;border:none;font-size:28px;line-height:1;color:#6B6B6B;cursor:pointer">&times;</button>
                    </div>

                    {{-- STEP INDICATOR --}}
                    <div style="display:flex;gap:8px;padding:14px 24px;background:#FBF7F1;font-size:12px;color:#6B6B6B">
                        <span id="tryon-pill-1" style="padding:4px 10px;border-radius:999px;background:#fff;border:1px solid #E8E0D8;font-weight:600">1. Upload</span>
                        <span id="tryon-pill-2" style="padding:4px 10px;border-radius:999px;background:#fff;border:1px solid #E8E0D8">2. Process</span>
                        <span id="tryon-pill-3" style="padding:4px 10px;border-radius:999px;background:#fff;border:1px solid #E8E0D8">3. Result</span>
                    </div>

                    {{-- STATE 1: UPLOAD --}}
                    <div id="tryon-upload-state" style="padding:24px">
                        <label for="tryon-photo"
                               id="tryon-drop-zone"
                               style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:28px;border:2px dashed #E8621A;border-radius:16px;background:#FFF8F2;cursor:pointer;text-align:center">
                            <div style="font-size:42px">🧍</div>
                            <div style="font-weight:700;color:#1A1A1A">Upload a full-body photo</div>
                            <div style="font-size:12px;color:#6B6B6B">Stand straight, facing camera, plain background preferred</div>
                            <div style="font-size:11px;color:#9A9A9A">JPG or PNG · Max 10MB</div>
                            <input id="tryon-photo" name="photo" type="file" accept="image/jpeg,image/png" hidden>
                        </label>

                        <img id="tryon-photo-preview" alt="Preview"
                             style="display:none;margin-top:12px;width:100%;max-height:240px;object-fit:contain;border-radius:12px;border:1px solid #E8E0D8;background:#1A1A1A">

                        <div style="margin-top:18px">
                            <label for="tryon-height" style="display:block;font-size:13px;font-weight:600;color:#1A1A1A;margin-bottom:6px">
                                Your height <span style="color:#9A9A9A;font-weight:400">(optional — improves accuracy)</span>
                            </label>
                            <div style="display:flex;align-items:center;gap:8px">
                                <input id="tryon-height" name="height_cm" type="number" min="100" max="250" placeholder="e.g. 175"
                                       style="flex:1;padding:10px 12px;border:1px solid #E8E0D8;border-radius:10px;font-size:14px">
                                <span style="color:#6B6B6B;font-size:13px">cm</span>
                            </div>
                        </div>

                        <div style="margin-top:18px;display:flex;flex-direction:column;gap:10px">
                            <label style="display:flex;align-items:flex-start;gap:8px;font-size:13px;color:#1A1A1A;cursor:pointer">
                                <input id="tryon-save-body" type="checkbox" checked style="margin-top:3px">
                                <span>Save my body model for future try-ons <span style="color:#9A9A9A">(faster next time)</span></span>
                            </label>
                            <label style="display:flex;align-items:flex-start;gap:8px;font-size:13px;color:#1A1A1A;cursor:pointer">
                                <input id="tryon-consent-photo" type="checkbox" style="margin-top:3px">
                                <span>Keep my photo on file <span style="color:#9A9A9A">(otherwise deleted after 24h)</span></span>
                            </label>
                            <div style="font-size:11px;color:#9A9A9A;line-height:1.5">
                                Your photo is processed securely and never shared with other users.
                            </div>
                        </div>

                        <button id="tryon-submit-btn" type="button" onclick="submitTryOn()"
                                style="margin-top:20px;width:100%;background:var(--orange,#E8621A);color:#fff;border:none;border-radius:12px;padding:14px;font-size:15px;font-weight:700;cursor:pointer">
                            Generate My Try-On
                        </button>
                    </div>

                    {{-- STATE 2: PROCESSING --}}
                    <div id="tryon-processing-state" style="display:none;padding:24px">
                        <div style="display:flex;justify-content:center;margin-bottom:18px">
                            <div id="tryon-blob" aria-hidden="true"
                                 style="width:120px;height:120px;border-radius:50%;background:radial-gradient(circle at 30% 30%, #F5A673, #E8621A 70%);box-shadow:0 0 60px rgba(232,98,26,0.4);animation:tryonBlobPulse 2.4s ease-in-out infinite"></div>
                        </div>

                        <div style="text-align:center;font-weight:700;color:#1A1A1A;margin-bottom:14px">Creating your virtual fitting…</div>

                        <ol style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;font-size:13px">
                            <li id="tryon-step-1" data-status="active"
                                style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:#FBF7F1;border-radius:10px">
                                <span class="tryon-step-dot"></span><span>Uploading photo</span>
                            </li>
                            <li id="tryon-step-2" data-status="pending"
                                style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:#FBF7F1;border-radius:10px">
                                <span class="tryon-step-dot"></span><span>Analyzing body shape with SAM 3D</span>
                            </li>
                            <li id="tryon-step-3" data-status="pending"
                                style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:#FBF7F1;border-radius:10px">
                                <span class="tryon-step-dot"></span><span>Fitting {{ $product->name }} to your body</span>
                            </li>
                            <li id="tryon-step-4" data-status="pending"
                                style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:#FBF7F1;border-radius:10px">
                                <span class="tryon-step-dot"></span><span>Finalizing your try-on</span>
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
                            Continue Shopping
                        </button>
                    </div>

                    {{-- STATE 3: RESULT --}}
                    <div id="tryon-result-state" style="display:none;padding:20px 24px 24px">
                        <div style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:#1A1A1A;margin-bottom:12px">Your Try-On is Ready! 🎉</div>

                        <div id="tryon-viewer"
                             style="height:350px;background:#1A1A1A;border-radius:14px;position:relative;overflow:hidden"></div>

                        <div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap">
                            <button type="button" onclick="closeTryOnModal()"
                                    style="flex:1;min-width:140px;background:var(--orange,#E8621A);color:#fff;border:none;border-radius:10px;padding:12px;font-weight:700;cursor:pointer">
                                Close
                            </button>
                            <button type="button" onclick="resetTryOnToUpload()"
                                    style="flex:1;min-width:140px;background:transparent;color:#1A1A1A;border:1.5px solid #E8E0D8;border-radius:10px;padding:12px;font-weight:600;cursor:pointer">
                                Try Again
                            </button>
                            <button type="button" onclick="deleteCurrentTryOn()"
                                    style="flex:1;min-width:140px;background:transparent;color:#B91C1C;border:1.5px solid #FECACA;border-radius:10px;padding:12px;font-weight:600;cursor:pointer">
                                Delete Try-On
                            </button>
                        </div>

                        <div style="margin-top:12px;font-size:11px;color:#9A9A9A;text-align:center">
                            3D fitting is approximate. Actual fit may vary slightly.
                        </div>
                    </div>
                </div>
            </div>

            <style>
                #tryon-modal-overlay.is-open { display:flex !important; }
                @keyframes tryonBlobPulse {
                    0%, 100% { transform: scale(1); filter: blur(0); }
                    50%      { transform: scale(1.06); filter: blur(1px); }
                }
                @keyframes tryonStepBlink { 0%,100% { opacity: 1; } 50% { opacity: 0.35; } }
                .tryon-step-dot {
                    width:10px;height:10px;border-radius:50%;background:#E8E0D8;flex-shrink:0;
                }
                #tryon-upload-state #tryon-step-1 .tryon-step-dot,
                #tryon-processing-state [data-status="done"] .tryon-step-dot { background:#16A34A; }
                #tryon-processing-state [data-status="active"] .tryon-step-dot {
                    background:#E8621A; animation: tryonStepBlink 1s ease-in-out infinite;
                }
                #tryon-processing-state [data-status="active"] { background:#FFF8F2 !important; }
            </style>
            @endif
            @endauth

            {{-- 3D Viewer Container --}}
            <div id="viewer-3d">
                <button class="btn-close-3d" onclick="toggle3DViewer(false)">&times; Close</button>

                <div class="viewer-panel" id="viewerPanel">
                    <div class="viewer-panel-header">
                        <span class="viewer-panel-title">View Your Model</span>
                        <button class="viewer-panel-close" onclick="document.getElementById('viewerPanel').classList.add('hidden')">&times;</button>
                    </div>
                    <div class="viewer-panel-body">
                        <div class="viewer-panel-section">
                            <div class="viewer-panel-section-title">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Rotate View
                            </div>
                            <div class="viewer-panel-instruction" id="panelRotate"><span>Left click + drag</span></div>
                        </div>
                        <div class="viewer-panel-section">
                            <div class="viewer-panel-section-title">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="5 9 2 12 5 15"/><polyline points="9 5 12 2 15 5"/><polyline points="15 19 12 22 9 19"/><polyline points="19 9 22 12 19 15"/><line x1="2" y1="12" x2="22" y2="12"/><line x1="12" y1="2" x2="12" y2="22"/></svg>
                                Pan View
                            </div>
                            <div class="viewer-panel-instruction" id="panelPan"><span>Right click + drag</span></div>
                        </div>
                        <div class="viewer-panel-section">
                            <div class="viewer-panel-section-title">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                Zoom Active
                            </div>
                            <div class="viewer-panel-instruction" id="panelZoom"><span>Scroll up & down</span></div>
                        </div>
                    </div>
                    <div class="viewer-panel-footer">
                        <button class="viewer-panel-ok" onclick="document.getElementById('viewerPanel').classList.add('hidden')">OK</button>
                    </div>
                </div>
            </div>

            {{-- Thumbnails ──--}}
            @if($product->images->count() > 1)
                <div class="gallery-thumbs">
                    <div class="thumb active" onclick="switchImage(this)">
                        <img src="{{ asset('images/' . ($product->images->first()?->image ?? $product->image)) }}" alt="Main">
                    </div>
                    @foreach($product->images->skip(1) as $img)
                        <div class="thumb" onclick="switchImage(this)">
                            <img src="{{ asset('images/' . $img->image) }}" alt="Gallery">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- RIGHT: INFO ──--}}
        <div>
            <span class="prod-category">{{ $product->category?->name ?? 'Uncategorized' }}</span>
            <h1 class="prod-title">{{ $product->name }}</h1>

            {{-- Rating ──--}}
            <div class="prod-meta">
                @if($reviews->isNotEmpty())
                    <div class="rating-stars">
                        @for($i = 0; $i < 5; $i++)
                            <span>{{ $i < floor($averageRating) ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    <span class="rating-count">{{ $averageRating }} ({{ $reviews->count() }} review{{ $reviews->count() !== 1 ? 's' : '' }})</span>
                @else
                    <span class="rating-count">No reviews yet</span>
                @endif
            </div>

            <div class="prod-price">₪{{ number_format($product->price, 2) }}</div>
            @if($product->sku)
                <div class="prod-sku">SKU: {{ $product->sku }}</div>
            @endif

            {{-- Stock Urgency ──--}}
            @if($product->stock > 0 && $product->stock <= $product->stock_alert_threshold)
                <div class="urgency-alert">
                    <span>⚡ Only {{ $product->stock }} left in stock</span>
                </div>
            @elseif($product->stock === 0)
                <div class="urgency-alert unavail">
                    <span>✕ Out of stock</span>
                </div>
            @endif

            {{-- Feature Pills ──--}}
            <div class="feature-pills">
                <div class="pill">🚚 Free Shipping</div>
                <div class="pill">🔄 Product Replace</div>
                <div class="pill">💳 EMI Available</div>
                <div class="pill">⏰ 24/7 Support</div>
            </div>

            {{-- Color Swatches ──--}}
            @if($product->has_colors && !empty($product->color_swatches))
            <div class="picker-group">
                <label class="picker-label">Color</label>
                <div class="color-swatches">
                    @foreach($product->color_swatches as $idx => $color)
                        <div class="swatch {{ $idx === 0 ? 'active' : '' }}" style="background-color: {{ $color['hex'] }};" onclick="selectColor(this)" title="{{ $color['name'] }}"></div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Size Picker ──--}}
            @php $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL']; @endphp
            <div class="picker-group">
                <label class="picker-label">Size</label>
                <div class="size-options">
                    @foreach($sizes as $size)
                        <button class="size-btn {{ $size === 'M' ? 'active' : '' }} {{ $size === 'XXL' ? 'disabled' : '' }}" onclick="selectSize(this)">{{ $size }}</button>
                    @endforeach
                </div>
                @if($product->size_guide)
                    <button type="button" style="margin-top: 0.75rem; background: none; border: none; color: var(--orange); cursor: pointer; font-size: 0.85rem; font-weight: 700;">📏 Size Guide</button>
                @endif
            </div>

            {{-- Quantity ──--}}
            <div class="picker-group">
                <label class="picker-label">Quantity</label>
                <div class="qty-selector">
                    <button class="qty-btn" onclick="changeQty(-1)">−</button>
                    <input type="number" id="qty" class="qty-input" value="1" min="1" max="{{ $product->max_order_quantity }}">
                    <button class="qty-btn" onclick="changeQty(1)">+</button>
                    <span class="qty-max">Max: {{ $product->max_order_quantity }}</span>
                </div>
            </div>

            {{-- Delivery Checker ──--}}
            <div class="delivery-wrap">
                <label class="picker-label">📍 Check Delivery</label>
                <div class="delivery-form">
                    <input type="text" class="delivery-input" placeholder="Enter postal code" id="postalCode" maxlength="6">
                    <button class="delivery-btn" onclick="checkDelivery()">Check</button>
                </div>
                <div class="delivery-result" id="deliveryResult"></div>
            </div>

            {{-- CTA Buttons ──--}}
            @unless(\App\Http\Middleware\AdminPreviewMode::isActive())
            <div class="cta-row">
                <form id="add-to-cart-form" method="POST" action="/cart/add" style="flex: 1;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" id="cartQty" value="1">
                    <button type="submit" id="add-to-cart-btn" class="btn-buy" @if($product->stock === 0) disabled @endif>
                        🛒 Add to Cart
                    </button>
                </form>
                <button class="btn-wishlist {{ in_array($product->id, $likedIds) ? 'active' : '' }}" id="wishlistBtn" onclick="toggleWishlist({{ $product->id }})">♡</button>
            </div>

            {{-- Buy Now ──--}}
            <div style="margin-bottom: 1.5rem;">
                <form method="POST" action="/checkout/quick">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" id="quickbuyQty" value="1">
                    <button type="submit" style="width: 100%; background: #f3f4f6; color: var(--gray-700); border: 1.5px solid var(--gray-200); padding: 0.75rem; border-radius: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.15s; font-family: 'DM Sans', sans-serif;">
                        ⚡ Buy Now
                    </button>
                </form>
            </div>
            @endunless

            {{-- Share ──--}}
            <div class="share-wrap">
                <label class="picker-label">Share</label>
                <div class="share-buttons">
                    <a class="share-btn" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" title="Share on Facebook">f</a>
                    <a class="share-btn" href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->name) }}" target="_blank" title="Share on X">𝕏</a>
                    <a class="share-btn" href="https://www.instagram.com/" target="_blank" title="Share on Instagram">📷</a>
                    <a class="share-btn" href="https://api.whatsapp.com/send?text={{ urlencode($product->name . ' ' . request()->url()) }}" target="_blank" title="Share on WhatsApp">💬</a>
                    <button class="share-btn" onclick="copyLink()" title="Copy link">🔗</button>
                </div>
            </div>
        </div>
    </div>

    {{-- TABS ──--}}
    <div class="card" style="margin-bottom: 3rem; border-radius: 1rem; box-shadow: var(--shadow-sm); overflow: hidden;">
        <div class="tabs-nav">
            <button class="tab-btn active" onclick="openTab(event, 'details')">📋 Product Details</button>
            <button class="tab-btn" onclick="openTab(event, 'shipping')">🚚 Shipping & Returns</button>
            <button class="tab-btn" onclick="openTab(event, 'reviews')">⭐ Reviews ({{ $reviews->count() }})</button>
        </div>

        <div style="padding: 2rem;">
            {{-- Details Tab ──--}}
            <div id="details" class="tabs-content active">
                @if($product->description)
                    <div style="margin-bottom: 1.5rem;">
                        <h3 style="font-weight: 700; margin-bottom: 0.75rem;">Description</h3>
                        <p style="color: var(--gray-700); line-height: 1.7;">{{ $product->description }}</p>
                    </div>
                @endif
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    @if($product->material)
                    <div>
                        <p style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--muted); margin-bottom: 0.4rem;">🧵 Material</p>
                        <p style="font-weight: 600;">{{ $product->material }}</p>
                    </div>
                    @endif
                    @if($product->fit)
                    <div>
                        <p style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--muted); margin-bottom: 0.4rem;">👕 Fit</p>
                        <p style="font-weight: 600;">{{ $product->fit }}</p>
                    </div>
                    @endif
                    @if($product->sku)
                    <div>
                        <p style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--muted); margin-bottom: 0.4rem;">🏷️ SKU</p>
                        <p style="font-family: monospace; font-weight: 600; font-size: 0.9rem;">{{ $product->sku }}</p>
                    </div>
                    @endif
                </div>
                @if($product->care_instructions)
                    <div style="margin-top: 1.5rem;">
                        <p style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--muted); margin-bottom: 0.4rem;">Care Instructions</p>
                        <p style="color: var(--gray-700); line-height: 1.7;">{{ $product->care_instructions }}</p>
                    </div>
                @endif
            </div>

            {{-- Shipping Tab ──--}}
            <div id="shipping" class="tabs-content">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                    <div>
                        <h4 style="font-weight: 700; margin-bottom: 0.75rem;">🚚 Shipping</h4>
                        <p style="color: var(--gray-700); line-height: 1.7;">{{ $product->delivery_estimate ?? 'Estimated delivery within 3–5 business days.' }}</p>
                    </div>
                    <div>
                        <h4 style="font-weight: 700; margin-bottom: 0.75rem;">↩️ Returns & Exchanges</h4>
                        <p style="color: var(--gray-700); line-height: 1.7;">{{ $product->shipping_returns ?? '30-day return policy. Free returns. No questions asked.' }}</p>
                    </div>
                </div>
            </div>

            {{-- Reviews Tab ──--}}
            <div id="reviews" class="tabs-content">
                <div class="reviews-section">
                    @if($reviews->isNotEmpty())
                        {{-- Rating Summary ──--}}
                        <div class="review-stats">
                            <div class="stat-avg">
                                <div class="avg-big">{{ $averageRating }}</div>
                                <div class="avg-meta">
                                    <div class="avg-stars">
                                        @for($i = 0; $i < 5; $i++)
                                            {{ $i < floor($averageRating) ? '★' : '☆' }}
                                        @endfor
                                    </div>
                                    <div class="avg-count">Based on {{ $reviews->count() }} review{{ $reviews->count() !== 1 ? 's' : '' }}</div>
                                </div>
                            </div>
                            <div>
                                <div class="rating-bars">
                                    @for($rating = 5; $rating >= 1; $rating--)
                                        @php $count = $ratingsBreakdown[$rating] ?? 0; $pct = $reviews->count() > 0 ? round(($count / $reviews->count()) * 100) : 0; @endphp
                                        <div class="rating-bar">
                                            <span class="bar-label">{{ $rating }}★</span>
                                            <div class="bar-track">
                                                <div class="bar-fill" style="width: {{ $pct }}%;"></div>
                                            </div>
                                            <span class="bar-count">{{ $count }}</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        {{-- Reviews List ──--}}
                        <div class="reviews-list">
                            @foreach($reviews as $review)
                                <div class="review-card">
                                    <div>
                                        <div class="review-author">{{ $review->user->name }}</div>
                                        <div class="review-meta">
                                            @if(in_array($review->user_id, $verifiedBuyerIds))
                                                <span class="review-badge">✓ Verified Buyer</span>
                                            @endif
                                            <span>{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="review-stars">
                                        @for($i = 0; $i < 5; $i++)
                                            {{ $i < $review->rating ? '★' : '☆' }}
                                        @endfor
                                    </div>
                                    <p class="review-text">{{ $review->comment }}</p>
                                    <div class="review-helpful">
                                        <span style="font-size: 0.8rem;">Helpful?</span>
                                        <button class="helpful-btn" onclick="voteHelpful({{ $review->id }})">👍 {{ $review->helpful_votes ?? 0 }}</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="text-align: center; color: var(--gray-500); padding: 2rem;">No reviews yet. Be the first to review this product!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- YOU MAY ALSO LIKE ──--}}
    @if($relatedProducts->isNotEmpty())
    <div style="margin-bottom: 4rem;">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 800; margin-bottom: 1.5rem;">💡 You May Also Like</h2>
        <div class="related-grid">
            @foreach($relatedProducts as $related)
                <a href="{{ route('products.show', $related) }}" class="related-card">
                    <img src="{{ asset('images/' . $related->image) }}" alt="{{ $related->name }}" class="related-img">
                    <div class="related-body">
                        <div class="related-name">{{ $related->name }}</div>
                        <div class="related-price">₪{{ number_format($related->price, 2) }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- SIZE GUIDE MODAL ──--}}
<div id="sizeGuideModal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeSizeGuide()">✕</button>
        <h2 style="margin-bottom: 1rem;">📏 Size Guide</h2>
        @if($product->size_guide)
            <p style="color: var(--gray-700); line-height: 1.7;">{{ $product->size_guide }}</p>
        @else
            <p style="color: var(--gray-700);">Standard sizing chart and fit guide available upon request.</p>
        @endif
    </div>
</div>

<script>
function switchImage(el) {
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('mainImage').src = el.querySelector('img').src;
}

function selectColor(el) {
    document.querySelectorAll('.swatch').forEach(s => s.classList.remove('active'));
    el.classList.add('active');
}

function selectSize(el) {
    if (el.disabled) return;
    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
}

function changeQty(delta) {
    const inp = document.getElementById('qty');
    let val = parseInt(inp.value) + delta;
    val = Math.max(1, Math.min(val, parseInt(inp.max)));
    inp.value = val;
    document.getElementById('cartQty').value = val;
    document.getElementById('quickbuyQty').value = val;
}

function checkDelivery() {
    const postal = document.getElementById('postalCode').value.trim();
    const res = document.getElementById('deliveryResult');
    if (!postal) { res.textContent = ''; return; }
    const available = parseInt(postal.charCodeAt(0)) % 2 === 0;
    res.className = available ? 'delivery-result ok' : 'delivery-result unavail';
    res.textContent = available ? '✓ Delivery available — Est. 3–5 days' : '✕ Not available in this area';
}

// AJAX: Add to Cart
(function () {
    var form = document.getElementById('add-to-cart-form');
    var btn  = document.getElementById('add-to-cart-btn');
    if (!form || !btn) return;
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (btn.disabled) return;
        btn.disabled = true;
        var fd = new FormData(form);
        try {
            var res  = await fetch('/cart/add', {
                method: 'POST', body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
            });
            var data = await res.json().catch(function () { return {}; });
            if (res.ok && data.success) {
                // Button feedback
                var orig = btn.innerHTML;
                btn.innerHTML = '✓ Added';
                btn.style.background = '#16a34a';
                // Update cart count badge
                var badge = document.getElementById('nav-cart-badge');
                if (badge && data.cartCount !== undefined) {
                    badge.textContent = data.cartCount > 99 ? '99+' : data.cartCount;
                    badge.style.display = data.cartCount > 0 ? '' : 'none';
                }
                setTimeout(function () {
                    btn.innerHTML = orig;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 2000);
            } else {
                btn.disabled = false;
                alert(data.message || (data.errors && Object.values(data.errors)[0]?.[0]) || 'Could not add to cart.');
            }
        } catch (e) {
            btn.disabled = false;
        }
    });
}());

function toggleWishlist(id) {
    var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    fetch('/likes/' + id + '/toggle', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.json().catch(function () { return {}; }); })
        .then(function (data) {
            document.querySelectorAll('.btn-wishlist, .gallery-wishlist').forEach(function (el) {
                if (data.liked !== undefined) {
                    el.classList.toggle('active', data.liked);
                } else {
                    el.classList.toggle('active');
                }
            });
        });
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => alert('Link copied!'));
}

function openTab(e, name) {
    document.querySelectorAll('.tabs-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(name).classList.add('active');
    e.target.classList.add('active');
}

function voteHelpful(reviewId) {
    fetch(`/reviews/${reviewId}/helpful`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
        .then(() => location.reload());
}

function closeSizeGuide() {
    document.getElementById('sizeGuideModal').classList.remove('active');
}

document.querySelector('button[onclick*="sizeGuide"]')?.addEventListener('click', () => {
    document.getElementById('sizeGuideModal').classList.add('active');
});

document.getElementById('qty')?.addEventListener('change', () => {
    document.getElementById('cartQty').value = document.getElementById('qty').value;
    document.getElementById('quickbuyQty').value = document.getElementById('qty').value;
});

// 3D Viewer toggle
const product3dModelPath = @json($product->is3DReady() && $product->model3d_path ? asset('storage/models/' . $product->id . '/' . $product->model3d_path) : null);
const product3dIsDemo = {{ $product->is3DReady() ? 'false' : 'true' }};
let viewerInitialized = false;

function toggle3DViewer(show) {
    const galleryMain = document.querySelector('.gallery-main');
    const thumbs = document.querySelector('.gallery-thumbs');
    const viewer = document.getElementById('viewer-3d');
    const panel = document.getElementById('viewerPanel');
    if (show) {
        galleryMain.style.display = 'none';
        if (thumbs) thumbs.style.display = 'none';
        viewer.style.display = 'block';
        // Show control panel each time
        if (panel) panel.classList.remove('hidden');
        // Adapt instructions for touch devices
        if ('ontouchstart' in window) {
            document.getElementById('panelRotate').innerHTML = '<span>Press &amp; drag</span>';
            document.getElementById('panelPan').innerHTML = '<span>Two-finger drag</span>';
            document.getElementById('panelZoom').innerHTML = '<span>Pinch to zoom</span>';
        }
        if (!viewerInitialized && typeof initViewer3D === 'function') {
            initViewer3D('viewer-3d', product3dModelPath);
            viewerInitialized = true;
        }
    } else {
        viewer.style.display = 'none';
        galleryMain.style.display = '';
        if (thumbs) thumbs.style.display = '';
        if (typeof disposeViewer3D === 'function') {
            disposeViewer3D();
            viewerInitialized = false;
        }
    }
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script src="{{ asset('js/viewer3d.js') }}?v={{ filemtime(public_path('js/viewer3d.js')) }}"></script>

@auth
@if($product->is3DReady() || true)
<script>
(function () {
    const TRYON_PRODUCT_ID = {{ $product->id }};
    const USER_ID          = {{ auth()->id() }};
    const STORAGE_KEY      = `tryon_${TRYON_PRODUCT_ID}_${USER_ID}`;
    const csrfToken        = document.querySelector('meta[name="csrf-token"]').content;

    let pollTimer   = null;
    let currentTryonId = null;
    let viewerInited = false;
    let estTimer = null;
    let estStart = 0;

    const $ = id => document.getElementById(id);

    function showState(state) {
        ['tryon-upload-state', 'tryon-processing-state', 'tryon-result-state']
            .forEach(s => { const el = $(s); if (el) el.style.display = (s === state) ? 'block' : 'none'; });
    }

    window.openTryOnModal = function () {
        $('tryon-modal-overlay').classList.add('is-open');
        document.body.style.overflow = 'hidden';
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            currentTryonId = parseInt(saved, 10);
            checkStatus(true);
        } else {
            showState('tryon-upload-state');
        }
    };

    window.closeTryOnModal = function () {
        $('tryon-modal-overlay').classList.remove('is-open');
        document.body.style.overflow = '';
        if (pollTimer) { clearTimeout(pollTimer); pollTimer = null; }
        if (estTimer)  { clearInterval(estTimer); estTimer = null; }
    };

    // Photo preview
    const photoInput = $('tryon-photo');
    const preview    = $('tryon-photo-preview');
    if (photoInput) {
        photoInput.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = ev => {
                if (preview) {
                    preview.src = ev.target.result;
                    preview.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        });
    }

    window.submitTryOn = async function () {
        const photo = photoInput.files[0];
        if (!photo) {
            window.showToast('Please choose a full-body photo first.', 'error');
            return;
        }

        const fd = new FormData();
        fd.append('photo', photo);
        const h = $('tryon-height')?.value;
        if (h) fd.append('height_cm', h);
        fd.append('photo_consent', $('tryon-consent-photo')?.checked ? '1' : '0');
        fd.append('save_body',     $('tryon-save-body')?.checked     ? '1' : '0');

        const btn = $('tryon-submit-btn');
        if (btn) { btn.disabled = true; btn.textContent = 'Submitting…'; }

        try {
            const res = await fetch(`/products/${TRYON_PRODUCT_ID}/tryon`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: fd,
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
        setStep(1, 'done');
        setStep(2, 'active');
        setStep(3, 'pending');
        setStep(4, 'pending');
        startEst();
        pollNow();
    }

    function setStep(n, status) {
        const li = $(`tryon-step-${n}`);
        if (li) li.dataset.status = status;
    }

    function startEst() {
        estStart = Date.now();
        const total = 90;
        if (estTimer) clearInterval(estTimer);
        estTimer = setInterval(() => {
            const elapsed = (Date.now() - estStart) / 1000;
            const pct = Math.min(95, Math.round((elapsed / total) * 100));
            const bar = $('tryon-bar'); if (bar) bar.style.width = pct + '%';
            const pctEl = $('tryon-pct'); if (pctEl) pctEl.textContent = pct + '%';
            const remaining = Math.max(0, Math.round(total - elapsed));
            const est = $('tryon-est'); if (est) est.textContent = `≈ ${remaining}s remaining`;
        }, 500);
    }

    function pollNow() {
        if (pollTimer) clearTimeout(pollTimer);
        checkStatus(false);
    }

    async function checkStatus(reopen) {
        if (!currentTryonId) { showState('tryon-upload-state'); return; }
        try {
            const res = await fetch(`/tryon/${currentTryonId}/status`, {
                headers: { 'Accept': 'application/json' },
            });
            if (res.status === 403 || res.status === 404) {
                localStorage.removeItem(STORAGE_KEY);
                currentTryonId = null;
                showState('tryon-upload-state');
                return;
            }
            const data = await res.json();
            handleStatus(data, reopen);
        } catch (e) {
            pollTimer = setTimeout(pollNow, 5000);
        }
    }

    function handleStatus(data, reopen) {
        if (data.status === 'pending' || data.status === 'processing_body') {
            if (reopen) showState('tryon-processing-state');
            if (!estTimer) startEst();
            setStep(1, 'done');
            setStep(2, 'active');
            setStep(3, 'pending');
            setStep(4, 'pending');
            pollTimer = setTimeout(pollNow, 5000);
        } else if (data.status === 'processing_fit') {
            if (reopen) showState('tryon-processing-state');
            if (!estTimer) startEst();
            setStep(1, 'done');
            setStep(2, 'done');
            setStep(3, 'active');
            setStep(4, 'pending');
            pollTimer = setTimeout(pollNow, 5000);
        } else if (data.status === 'ready') {
            if (estTimer) { clearInterval(estTimer); estTimer = null; }
            setStep(1, 'done'); setStep(2, 'done'); setStep(3, 'done'); setStep(4, 'done');
            const bar = $('tryon-bar'); if (bar) bar.style.width = '100%';
            const pctEl = $('tryon-pct'); if (pctEl) pctEl.textContent = '100%';
            loadResult(data.result_url);
        } else if (data.status === 'failed') {
            if (estTimer) { clearInterval(estTimer); estTimer = null; }
            window.showToast(data.error_message || 'Try-on failed. Please try again.', 'error');
            localStorage.removeItem(STORAGE_KEY);
            currentTryonId = null;
            const btn = $('tryon-submit-btn');
            if (btn) { btn.disabled = false; btn.textContent = 'Generate Try-On'; }
            showState('tryon-upload-state');
        }
    }

    function loadResult(resultUrl) {
        showState('tryon-result-state');
        if (resultUrl && typeof window.initViewer3D === 'function' && !viewerInited) {
            window.initViewer3D('tryon-viewer', resultUrl);
            viewerInited = true;
        }
    }

    window.resetTryOnToUpload = function () {
        if (pollTimer) { clearTimeout(pollTimer); pollTimer = null; }
        if (estTimer)  { clearInterval(estTimer); estTimer = null; }
        if (viewerInited && typeof window.disposeViewer3D === 'function') {
            window.disposeViewer3D();
            viewerInited = false;
        }
        currentTryonId = null;
        localStorage.removeItem(STORAGE_KEY);
        if (photoInput) photoInput.value = '';
        if (preview) { preview.src = ''; preview.style.display = 'none'; }
        const btn = $('tryon-submit-btn');
        if (btn) { btn.disabled = false; btn.textContent = 'Generate Try-On'; }
        showState('tryon-upload-state');
    };

    window.deleteCurrentTryOn = async function () {
        if (!currentTryonId) { window.resetTryOnToUpload(); return; }
        if (!confirm('Delete this try-on? This cannot be undone.')) return;
        try {
            const res = await fetch(`/tryon/${currentTryonId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            });
            if (res.ok) {
                window.showToast('Try-on deleted.', 'success');
                window.resetTryOnToUpload();
            } else {
                window.showToast('Could not delete try-on.', 'error');
            }
        } catch (e) {
            window.showToast('Network error.', 'error');
        }
    };

    // Resume on page load if a try-on is in progress
    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            currentTryonId = parseInt(saved, 10);
            fetch(`/tryon/${currentTryonId}/status`, { headers: { 'Accept': 'application/json' } })
                .then(r => r.ok ? r.json() : null)
                .then(d => {
                    if (!d) { localStorage.removeItem(STORAGE_KEY); return; }
                    if (d.status === 'ready' || d.status === 'failed') return;
                });
        }
    });
})();
</script>
@endif
@endauth
</x-layout>