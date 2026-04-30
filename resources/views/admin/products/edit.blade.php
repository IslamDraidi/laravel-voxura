<x-admin-layout title="Edit Product" section="catalog" active="products">
<style>
/* ── Edit Product Page ── */
.cp-header { margin-bottom: 20px; }
.cp-breadcrumb { font-size: 12px; color: var(--muted); margin-bottom: 8px; }
.cp-breadcrumb a { color: var(--muted); text-decoration: none; transition: color .15s; }
.cp-breadcrumb a:hover { color: var(--orange); }
.cp-title-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.cp-page-title { font-size: 22px; font-weight: 700; color: var(--dark); }
.cp-page-title span { font-size: 14px; font-weight: 400; color: var(--muted); margin-left: 6px; }
.cp-header-actions { display: flex; gap: 8px; align-items: center; }

.cp-layout { display: grid; grid-template-columns: 1fr 370px; gap: 20px; align-items: start; margin-bottom: 80px; }

.cp-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; margin-bottom: 16px; }
.cp-card-header { padding: 14px 20px 12px; border-bottom: 1px solid var(--border); border-left: 3px solid var(--orange); }
.cp-card-title { font-size: 14px; font-weight: 700; color: var(--dark); line-height: 1.3; }
.cp-card-sub { font-size: 11px; color: var(--muted); margin-top: 2px; }
.cp-card-body { padding: 20px; }

.cp-field { margin-bottom: 14px; }
.cp-field:last-child { margin-bottom: 0; }
.cp-label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: 5px; }
.cp-label .req { color: var(--red); margin-left: 2px; }
.cp-helper { font-size: 11px; color: #9ca3af; margin-top: 4px; line-height: 1.5; }
.cp-card .form-input:focus, .cp-card .form-select:focus, .cp-card .form-textarea:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(234,88,12,.1); outline: none; }

.cp-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

.cp-btn-save { background: var(--orange); color: #fff; border: none; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background .15s; white-space: nowrap; }
.cp-btn-save:hover { background: var(--orange-dark); }

.cp-toggle-row { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; transition: border-color .15s; margin-bottom: 8px; }
.cp-toggle-row:last-child { margin-bottom: 0; }
.cp-toggle-row:hover { border-color: var(--orange); }
.cp-toggle-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--orange); flex-shrink: 0; }
.cp-toggle-label { font-size: 13px; color: var(--dark); font-weight: 500; }
.cp-toggle-sub { font-size: 11px; color: var(--muted); }

.cp-image-zone { border: 2px dashed var(--border); border-radius: 10px; padding: 18px; text-align: center; cursor: pointer; transition: border-color .15s, background .15s; position: relative; }
.cp-image-zone:hover, .cp-image-zone.drag-over { border-color: var(--orange); background: var(--orange-pale); }
.cp-image-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.cp-zone-icon { font-size: 22px; margin-bottom: 5px; }
.cp-zone-text { font-size: 13px; font-weight: 600; color: var(--dark); }
.cp-zone-sub { font-size: 11px; color: var(--muted); margin-top: 2px; }

.cp-preview-cover { display: none; align-items: center; gap: 10px; padding: 8px; background: var(--gray-50); border-radius: 8px; border: 1px solid var(--border); margin-bottom: 8px; }
.cp-preview-cover img { width: 52px; height: 52px; object-fit: cover; border-radius: 6px; flex-shrink: 0; }
.cp-preview-cover-info { flex: 1; min-width: 0; }
.cp-preview-cover-name { font-size: 12px; font-weight: 600; color: var(--dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cp-preview-cover-size { font-size: 11px; color: var(--muted); }
.cp-preview-cover-main { background: var(--orange); color: #fff; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; white-space: nowrap; }

.cp-existing-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
.cp-existing-item { position: relative; width: 68px; height: 68px; }
.cp-existing-item img { width: 68px; height: 68px; object-fit: cover; border-radius: 8px; border: 1.5px solid var(--border); display: block; }
.cp-existing-item.marked { opacity: .35; }
.cp-existing-remove { position: absolute; top: -7px; right: -7px; width: 20px; height: 20px; background: #ef4444; color: #fff; border: none; border-radius: 50%; font-size: 11px; font-weight: 900; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 2; line-height: 1; }

.cp-gallery-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px; }
.cp-gallery-item { position: relative; width: 68px; height: 68px; border-radius: 8px; cursor: grab; user-select: none; }
.cp-gallery-item:active { cursor: grabbing; }
.cp-gallery-item.dragging { opacity: .35; }
.cp-gallery-item.drag-target { outline: 2px dashed var(--orange); outline-offset: 2px; }
.cp-gallery-item img { width: 68px; height: 68px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); pointer-events: none; display: block; }
.cp-gallery-remove { position: absolute; top: -7px; right: -7px; width: 20px; height: 20px; background: #ef4444; color: #fff; border: none; border-radius: 50%; font-size: 11px; font-weight: 900; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 2; line-height: 1; }

.cp-char-counter { font-size: 11px; color: var(--muted); text-align: right; margin-top: 3px; }
.cp-char-counter.warn { color: #d97706; }
.cp-char-counter.over { color: var(--red); }

.cp-col-header { display: grid; gap: 6px; margin-bottom: 5px; }
.cp-col-header span { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); }
.cp-size-header { grid-template-columns: 1fr 1fr 1fr 1fr auto; }
.cp-swatch-header { grid-template-columns: 1fr 36px 90px auto; }
.cp-size-row { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 6px; align-items: center; margin-bottom: 6px; }
.cp-swatch-row { display: grid; grid-template-columns: 1fr 36px 90px auto; gap: 6px; align-items: center; margin-bottom: 6px; }
.cp-color-pick { width: 36px; height: 34px; border: 1px solid var(--border); border-radius: 6px; padding: 2px 3px; cursor: pointer; background: none; }
.cp-row-remove { background: none; border: 1px solid #fecaca; color: #ef4444; border-radius: 6px; padding: 6px 9px; font-size: 12px; cursor: pointer; transition: background .15s; font-family: 'DM Sans', sans-serif; white-space: nowrap; }
.cp-row-remove:hover { background: #fee2e2; }
.cp-add-row-btn { display: inline-flex; align-items: center; gap: 5px; background: none; border: 1.5px dashed var(--border); border-radius: 8px; padding: 7px 14px; font-size: 13px; font-weight: 600; color: var(--muted); cursor: pointer; transition: all .15s; font-family: 'DM Sans', sans-serif; margin-top: 2px; }
.cp-add-row-btn:hover { border-color: var(--orange); color: var(--orange); }

.cp-product-id { display: inline-flex; align-items: center; gap: 6px; background: var(--gray-50); border: 1px solid var(--border); border-radius: 6px; padding: 5px 10px; font-size: 12px; color: var(--muted); font-family: monospace; margin-bottom: 14px; }

.cp-bottom-bar { position: fixed; bottom: 0; left: 200px; right: 0; z-index: 40; background: #fff; border-top: 1px solid var(--border); padding: 12px 28px; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
.cp-bottom-warn { font-size: 12px; color: var(--muted); }
.cp-bottom-actions { display: flex; gap: 8px; align-items: center; }

/* Variants */
.variants-section { margin-top: 0; }
.variants-title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); margin-bottom: 1rem; }
.variants-table { width: 100%; border-collapse: collapse; margin-bottom: 1.25rem; }
.variants-table th { text-align: left; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); padding: 0.5rem 0.75rem; border-bottom: 1.5px solid var(--border); }
.variants-table td { padding: 0.6rem 0.75rem; font-size: 0.88rem; color: var(--dark); border-bottom: 1px solid var(--border); vertical-align: middle; }
.variants-table tr:last-child td { border-bottom: none; }
.variant-type-badge { display: inline-block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.15rem 0.55rem; border-radius: 999px; background: #fff7ed; color: var(--orange); }
.btn-variant-delete { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.3rem 0.7rem; border-radius: 0.4rem; font-size: 0.78rem; font-weight: 600; color: #ef4444; border: 1.5px solid #fecaca; background: transparent; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background 0.15s; }
.btn-variant-delete:hover { background: #fee2e2; }
.variant-add-grid { display: grid; grid-template-columns: 1fr 1fr 120px 100px auto; gap: 0.6rem; align-items: end; }
.variant-add-grid .form-input { margin: 0; }
.btn-add-variant { display: inline-flex; align-items: center; gap: 0.3rem; background: var(--orange); color: #fff; padding: 0.7rem 1rem; border-radius: 0.5rem; border: none; cursor: pointer; font-size: 0.85rem; font-weight: 700; font-family: 'DM Sans', sans-serif; white-space: nowrap; transition: background 0.15s; }
.btn-add-variant:hover { background: var(--orange-dark); }

/* m3d */
@@keyframes m3dPulse { 0%,100% { opacity:1; } 50% { opacity:0.3; } }
@@keyframes m3dSpin { to { transform: rotate(360deg); } }
.m3d-badge { padding:0.3rem 0.8rem;border-radius:999px;font-size:0.78rem;font-weight:700;display:inline-flex;align-items:center;gap:0.4rem; }
.m3d-dot { width:8px;height:8px;border-radius:50%;background:#f59e0b;animation:m3dPulse 1.2s infinite; }
.m3d-spinner { width:12px;height:12px;border:2px solid #ea580c;border-top-color:transparent;border-radius:50%;animation:m3dSpin 0.8s linear infinite; }

/* gen3d */
@@keyframes gen3d-blink { 0%,100% { opacity:1; } 50% { opacity:0.3; } }
@@keyframes gen3d-spin  { to { transform:rotate(360deg); } }
.gen3d-step { display:flex; align-items:center; gap:12px; padding:8px 0; border-bottom:1px solid #F8F3EF; }
.gen3d-step:last-child { border-bottom:none; }
.gen3d-step-dot { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; flex-shrink:0; background:#F0EBE4; color:#ccc; transition:all 0.3s ease; }
.gen3d-step[data-status="active"] .gen3d-step-dot { background:#ea580c; color:#fff; animation:gen3d-blink 1.5s infinite; }
.gen3d-step[data-status="done"] .gen3d-step-dot { background:#DCFCE7; color:#166534; }
.gen3d-step-info { flex:1; }
.gen3d-step-name { font-size:12px; font-weight:600; color:#1a1a1a; transition:color 0.3s; }
.gen3d-step[data-status="active"] .gen3d-step-name { color:#ea580c; }
.gen3d-step[data-status="pending"] .gen3d-step-name { color:#ccc; }
.gen3d-step-desc { font-size:10px; color:#6b7280; margin-top:1px; }
.gen3d-step[data-status="pending"] .gen3d-step-desc { color:#ddd; }
.gen3d-step-time { font-size:10px; color:#6b7280; white-space:nowrap; min-width:40px; text-align:right; }
.gen3d-step[data-status="active"] .gen3d-step-time { color:#ea580c; font-weight:600; }

@media (max-width: 1024px) {
    .cp-layout { grid-template-columns: 1fr; }
    .cp-col-right { order: -1; }
    .cp-bottom-bar { left: 0; padding: 12px 16px; }
    .variant-add-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 640px) {
    .cp-grid-2 { grid-template-columns: 1fr; }
    .cp-size-row { grid-template-columns: 1fr 1fr; }
    .cp-size-header { grid-template-columns: 1fr 1fr; }
    .cp-title-row { flex-direction: column; align-items: flex-start; }
    .cp-header-actions { width: 100%; }
    .cp-bottom-warn { display: none; }
}
</style>

@php $backUrl = request()->query('from') === 'archive' ? '/admin/archive' : '/admin'; @endphp
@php
    $colorSwatchesRows = old('color_swatches_rows', collect($product->color_swatches ?? [])->map(fn ($swatch) => ($swatch['name'] ?? '').'|'.($swatch['hex'] ?? ''))->implode(PHP_EOL));
    $sizeGuideRows = old('size_guide_rows', collect($product->size_guide ?? [])->map(fn ($row) => implode('|', [
        $row['size'] ?? '',
        $row['chest'] ?? '',
        $row['waist'] ?? '',
        $row['length'] ?? '',
    ]))->implode(PHP_EOL));
@endphp

{{-- Page Header --}}
<div class="cp-header">
    <div class="cp-breadcrumb">
        <a href="/admin" onclick="event.preventDefault();adminNavigate('/admin')">Admin</a> ›
        <a href="/admin/products" onclick="event.preventDefault();adminNavigate('/admin/products')">Products</a> ›
        Edit Product
    </div>
    <div class="cp-title-row">
        <h1 class="cp-page-title">Edit Product <span>#{{ $product->id }}</span></h1>
        <div class="cp-header-actions">
            <button type="button" class="act-btn" id="cp-cancel-btn" onclick="cpCancelEdit()">Cancel</button>
            <button type="submit" form="cp-form" class="cp-btn-save">Save Changes</button>
        </div>
    </div>
</div>

<form id="cp-form" method="POST" action="/admin/products/{{ $product->id }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <input type="hidden" name="redirect_to" value="{{ request()->query('from') }}">

    <div class="cp-layout">

        {{-- ══ LEFT COLUMN ══ --}}
        <div class="cp-col-left">

            {{-- Card 1: Basic Information --}}
            <div class="cp-card">
                <div class="cp-card-header">
                    <div class="cp-card-title">Basic Information</div>
                    <div class="cp-card-sub">Core product identity and identifiers</div>
                </div>
                <div class="cp-card-body">
                    <div class="cp-product-id">Product ID: #{{ $product->id }}</div>
                    <div class="cp-field">
                        <label class="cp-label" for="cp-name">Product Name <span class="req">*</span></label>
                        <input type="text" id="cp-name" name="name" class="form-input"
                               placeholder="e.g. Premium Linen Blazer"
                               value="{{ old('name', $product->name) }}" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="cp-grid-2">
                        <div class="cp-field">
                            <label class="cp-label" for="cp-sku">SKU</label>
                            <input type="text" id="cp-sku" name="sku" class="form-input"
                                   placeholder="e.g. VX-TSH-001"
                                   value="{{ old('sku', $product->sku) }}">
                            @error('sku')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cp-field">
                            <label class="cp-label" for="cp-material">Material</label>
                            <input type="text" id="cp-material" name="material" class="form-input"
                                   placeholder="e.g. 100% Cotton"
                                   value="{{ old('material', $product->material) }}">
                            @error('material')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Pricing & Inventory --}}
            <div class="cp-card">
                <div class="cp-card-header">
                    <div class="cp-card-title">Pricing & Inventory</div>
                    <div class="cp-card-sub">Price, stock levels and sales configuration</div>
                </div>
                <div class="cp-card-body">
                    <div class="cp-grid-2">
                        <div class="cp-field">
                            <label class="cp-label" for="cp-price">Price ($) <span class="req">*</span></label>
                            <input type="number" id="cp-price" name="price" class="form-input"
                                   placeholder="0.00" min="0" step="0.01"
                                   value="{{ old('price', $product->price) }}" required>
                            @error('price')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cp-field">
                            <label class="cp-label" for="cp-sale-badge">Sale Badge</label>
                            <input type="text" id="cp-sale-badge" name="sale_badge" class="form-input"
                                   placeholder="e.g. 20% OFF"
                                   value="{{ old('sale_badge', $product->sale_badge) }}">
                            @error('sale_badge')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="cp-grid-2">
                        <div class="cp-field">
                            <label class="cp-label" for="cp-stock">Stock Quantity <span class="req">*</span></label>
                            <input type="number" id="cp-stock" name="stock" class="form-input"
                                   placeholder="0" min="0"
                                   value="{{ old('stock', $product->stock) }}" required>
                            @error('stock')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cp-field">
                            <label class="cp-label" for="cp-stock-alert">Low Stock Alert <span class="req">*</span></label>
                            <input type="number" id="cp-stock-alert" name="stock_alert_threshold" class="form-input"
                                   min="1" max="99"
                                   value="{{ old('stock_alert_threshold', $product->stock_alert_threshold ?? 6) }}" required>
                            <p class="cp-helper">Alert when stock falls below this</p>
                            @error('stock_alert_threshold')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="cp-grid-2">
                        <div class="cp-field">
                            <label class="cp-label" for="cp-max-qty">Max Order Quantity <span class="req">*</span></label>
                            <input type="number" id="cp-max-qty" name="max_order_quantity" class="form-input"
                                   min="1" max="99"
                                   value="{{ old('max_order_quantity', $product->max_order_quantity ?? 5) }}" required>
                            @error('max_order_quantity')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cp-field">
                            <label class="cp-label" for="cp-delivery">Delivery Estimate</label>
                            <input type="text" id="cp-delivery" name="delivery_estimate" class="form-input"
                                   placeholder="e.g. Arrives in 2-4 business days"
                                   value="{{ old('delivery_estimate', $product->delivery_estimate) }}">
                            @error('delivery_estimate')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Description --}}
            <div class="cp-card">
                <div class="cp-card-header">
                    <div class="cp-card-title">Product Description</div>
                    <div class="cp-card-sub">Shown on the product page — be detailed and persuasive</div>
                </div>
                <div class="cp-card-body">
                    <div class="cp-field">
                        <label class="cp-label" for="cp-desc">Description <span class="req">*</span></label>
                        <textarea id="cp-desc" name="description" class="form-textarea" rows="7"
                                  placeholder="Describe your product in detail…"
                                  required>{{ old('description', $product->description) }}</textarea>
                        <div class="cp-char-counter"><span id="desc-count">{{ strlen(old('description', $product->description ?? '')) }}</span> characters</div>
                        @error('description')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Card 4: Product Details --}}
            <div class="cp-card">
                <div class="cp-card-header">
                    <div class="cp-card-title">Product Details</div>
                    <div class="cp-card-sub">Fit, care instructions and shipping policy</div>
                </div>
                <div class="cp-card-body">
                    <div class="cp-field">
                        <label class="cp-label" for="cp-fit">Fit</label>
                        <input type="text" id="cp-fit" name="fit" class="form-input"
                               placeholder="e.g. Relaxed fit"
                               value="{{ old('fit', $product->fit) }}">
                        @error('fit')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="cp-field">
                        <label class="cp-label" for="cp-care">Care Instructions</label>
                        <textarea id="cp-care" name="care_instructions" class="form-textarea" rows="3"
                                  placeholder="Machine wash cold. Tumble dry low.">{{ old('care_instructions', $product->care_instructions) }}</textarea>
                        @error('care_instructions')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="cp-field">
                        <label class="cp-label" for="cp-shipping">Shipping & Returns</label>
                        <textarea id="cp-shipping" name="shipping_returns" class="form-textarea" rows="4"
                                  placeholder="Add shipping windows, free shipping threshold, and return policy details.">{{ old('shipping_returns', $product->shipping_returns) }}</textarea>
                        @error('shipping_returns')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Card 5: Size Guide --}}
            <div class="cp-card">
                <div class="cp-card-header">
                    <div class="cp-card-title">Size Guide</div>
                    <div class="cp-card-sub">Measurements displayed in the sizing chart on the product page</div>
                </div>
                <div class="cp-card-body">
                    <div class="cp-col-header cp-size-header">
                        <span>Size</span><span>Chest</span><span>Waist</span><span>Length</span><span></span>
                    </div>
                    <div id="size-guide-rows"></div>
                    <button type="button" class="cp-add-row-btn" onclick="sgAddRow()">+ Add Size</button>
                    <textarea name="size_guide_rows" id="size-guide-hidden" style="display:none;" rows="5">{{ $sizeGuideRows }}</textarea>
                    @error('size_guide_rows')<p class="form-error">{{ $message }}</p>@enderror
                    @error('size_guide')<p class="form-error">{{ $message }}</p>@enderror
                    @error('size_guide.*.size')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

        </div>{{-- /cp-col-left --}}

        {{-- ══ RIGHT COLUMN ══ --}}
        <div class="cp-col-right">

            {{-- Card 6: Product Flags --}}
            <div class="cp-card">
                <div class="cp-card-header">
                    <div class="cp-card-title">Product Flags</div>
                </div>
                <div class="cp-card-body">
                    <label class="cp-toggle-row">
                        <input type="checkbox" name="is_new" value="1"
                               {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                        <div>
                            <div class="cp-toggle-label">Show "New" badge</div>
                            <div class="cp-toggle-sub">Displays a New badge on the product image</div>
                        </div>
                    </label>
                    @error('is_new')<p class="form-error">{{ $message }}</p>@enderror
                    <label class="cp-toggle-row">
                        <input type="checkbox" name="has_colors" id="has_colors_toggle" value="1"
                               {{ old('has_colors', $product->has_colors) ? 'checked' : '' }}
                               onchange="document.getElementById('color_swatches_section').style.display = this.checked ? 'block' : 'none'">
                        <div>
                            <div class="cp-toggle-label">Enable color picker</div>
                            <div class="cp-toggle-sub">Shows clickable color swatches on the product page</div>
                        </div>
                    </label>
                    @error('has_colors')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Card 7: Category --}}
            <div class="cp-card">
                <div class="cp-card-header">
                    <div class="cp-card-title">Category</div>
                </div>
                <div class="cp-card-body">
                    <div class="cp-field">
                        <label class="cp-label" for="cp-category">Category <span class="req">*</span></label>
                        <select id="cp-category" name="category_id" class="form-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Card 8: Product Images --}}
            <div class="cp-card">
                <div class="cp-card-header">
                    <div class="cp-card-title">Product Images</div>
                    <div class="cp-card-sub">JPG, PNG, WEBP — max 5MB per image</div>
                </div>
                <div class="cp-card-body">
                    {{-- Cover image --}}
                    <div class="cp-field">
                        <label class="cp-label">Cover Image</label>
                        @if($product->image)
                        <div style="display:flex;align-items:center;gap:10px;padding:8px;background:var(--gray-50);border-radius:8px;border:1px solid var(--border);margin-bottom:8px">
                            <img src="{{ asset('images/' . $product->image) }}"
                                 style="width:52px;height:52px;object-fit:cover;border-radius:6px;flex-shrink:0" alt="">
                            <div style="flex:1;min-width:0">
                                <div style="font-size:12px;font-weight:600;color:var(--dark)">Current Cover</div>
                                <div style="font-size:11px;color:var(--muted)">Upload a new one below to replace it</div>
                            </div>
                            <span style="background:var(--orange);color:#fff;font-size:10px;font-weight:700;padding:2px 8px;border-radius:999px">Main</span>
                        </div>
                        @endif
                        <div id="cp-cover-preview" class="cp-preview-cover">
                            <img id="cp-cover-img" src="" alt="">
                            <div class="cp-preview-cover-info">
                                <div class="cp-preview-cover-name" id="cp-cover-name"></div>
                                <div class="cp-preview-cover-size" id="cp-cover-size"></div>
                            </div>
                            <span class="cp-preview-cover-main">New</span>
                        </div>
                        <div class="cp-image-zone" id="cp-cover-zone">
                            <input type="file" id="cp-image-input" name="image" accept="image/*"
                                   onchange="cpCoverPreview(this)">
                            <div class="cp-zone-icon">🖼️</div>
                            <div class="cp-zone-text">{{ $product->image ? 'Replace cover image' : 'Upload cover image' }}</div>
                            <div class="cp-zone-sub">JPG, PNG, WEBP</div>
                        </div>
                        @error('image')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Gallery images --}}
                    <div class="cp-field">
                        <label class="cp-label">Gallery Images</label>
                        @if($product->images->isNotEmpty())
                        <div class="cp-existing-grid" id="cp-existing-grid">
                            @foreach($product->images as $img)
                            <div class="cp-existing-item" id="gi-{{ $img->id }}">
                                <img src="{{ asset('images/' . $img->image) }}" alt="Gallery">
                                <button type="button" class="cp-existing-remove"
                                        onclick="markRemove({{ $img->id }})" title="Remove on save">×</button>
                                <input type="checkbox" name="remove_images[]" value="{{ $img->id }}"
                                       id="rm-{{ $img->id }}" style="display:none">
                            </div>
                            @endforeach
                        </div>
                        <p style="font-size:11px;color:var(--muted);margin-bottom:8px">Click × to mark an image for removal on save.</p>
                        @endif
                        <div id="cp-gallery-grid" class="cp-gallery-grid"></div>
                        <div class="cp-image-zone" id="cp-gallery-zone">
                            <input type="file" id="cp-gallery-input" name="gallery[]"
                                   accept="image/*" multiple
                                   onchange="cpGalleryAdd(this.files)">
                            <div class="cp-zone-icon">📷</div>
                            <div class="cp-zone-text">Drag or click to add more images</div>
                            <div class="cp-zone-sub">Hold Ctrl/Cmd to select multiple</div>
                        </div>
                        <p class="cp-helper" id="cp-gallery-hint" style="display:none">Drag thumbnails to reorder</p>
                        @error('gallery.*')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Card 9: 3D Model --}}
            <div class="cp-card">
                <div class="cp-card-header">
                    <div class="cp-card-title">AI 3D Model</div>
                    <div class="cp-card-sub">Generate a 3D model from your product images</div>
                </div>
                <div class="cp-card-body">
                    <div id="m3d-card" style="padding:1rem;background:var(--gray-50,#f9fafb);border:1px solid var(--gray-200);border-radius:0.75rem;">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.6rem;">
                            <span id="m3d-badge" class="m3d-badge"></span>
                        </div>
                        <div id="m3d-body"></div>
                        <div id="m3d-actions" style="margin-top:0.75rem;display:flex;gap:0.5rem;flex-wrap:wrap;"></div>
                    </div>
                </div>
            </div>

            {{-- Card 10: Color Swatches (conditional) --}}
            <div class="cp-card" id="color_swatches_section"
                 style="{{ old('has_colors', $product->has_colors) ? 'display:block' : 'display:none' }}">
                <div class="cp-card-header">
                    <div class="cp-card-title">Color Swatches</div>
                    <div class="cp-card-sub">Clickable color options shown on the product page</div>
                </div>
                <div class="cp-card-body">
                    <div class="cp-col-header cp-swatch-header">
                        <span>Name</span><span>Pick</span><span>Hex</span><span></span>
                    </div>
                    <div id="swatches-rows"></div>
                    <button type="button" class="cp-add-row-btn" onclick="swAddRow()">+ Add Color</button>
                    <textarea name="color_swatches_rows" id="swatches-hidden" style="display:none;" rows="5">{{ $colorSwatchesRows }}</textarea>
                    @error('color_swatches_rows')<p class="form-error">{{ $message }}</p>@enderror
                    @error('color_swatches')<p class="form-error">{{ $message }}</p>@enderror
                    @error('color_swatches.*.name')<p class="form-error">{{ $message }}</p>@enderror
                    @error('color_swatches.*.hex')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

        </div>{{-- /cp-col-right --}}

    </div>{{-- /cp-layout --}}

</form>

{{-- Sticky bottom bar --}}
<div class="cp-bottom-bar">
    <span class="cp-bottom-warn">⚠ Unsaved changes will be lost if you leave</span>
    <div class="cp-bottom-actions">
        <button type="button" class="act-btn" id="cp-cancel-btn-bar" onclick="cpCancelEdit()">Cancel</button>
        <button type="submit" form="cp-form" class="cp-btn-save">Save Changes</button>
    </div>
</div>

{{-- Variants section (outside main form) --}}
<div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:24px;margin-top:8px;margin-bottom:80px">
    <div class="variants-section">
        <p class="variants-title">Product Variants (Size, Color, etc.)</p>
        <p style="font-size:0.85rem;color:var(--muted);margin-bottom:1rem;">For the storefront size picker, add variants with the type <strong>Size</strong> and values like <strong>XS</strong>, <strong>S</strong>, <strong>M</strong>, <strong>L</strong>, <strong>XL</strong>, and <strong>XXL</strong>. Set stock to <strong>0</strong> to show an option as out of stock.</p>

        @if($product->variants->isNotEmpty())
            <table class="variants-table">
                <thead>
                    <tr>
                        <th>Type</th><th>Value</th><th>Price Modifier</th><th>Stock</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product->variants as $variant)
                    <tr>
                        <td><span class="variant-type-badge">{{ $variant->type }}</span></td>
                        <td><strong>{{ $variant->value }}</strong></td>
                        <td>
                            @if($variant->price_modifier > 0)
                                <span style="color:#16a34a;font-weight:700;">+₪{{ number_format($variant->price_modifier, 2) }}</span>
                            @elseif($variant->price_modifier < 0)
                                <span style="color:#ef4444;font-weight:700;">₪{{ number_format($variant->price_modifier, 2) }}</span>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($variant->stock !== null)
                                {{ $variant->stock }}
                            @else
                                <span style="color:var(--muted);font-size:0.8rem;">uses product stock</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="/admin/products/{{ $product->id }}/variants/{{ $variant->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-variant-delete"
                                        onclick="if(confirm('Remove {{ $variant->type }}: {{ $variant->value }}?')) this.closest('form').submit()">
                                    ✕ Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="font-size:0.85rem;color:var(--muted);margin-bottom:1rem;">No variants yet. Add one below.</p>
        @endif

        <form method="POST" action="/admin/products/{{ $product->id }}/variants">
            @csrf
            <div class="variant-add-grid">
                <div>
                    <label class="form-label" style="margin-bottom:0.3rem;display:block;">Type</label>
                    <input type="text" name="type" class="form-input" placeholder="e.g. Size, Color"
                           list="variant-types" required>
                    <datalist id="variant-types">
                        <option value="Size"><option value="Color"><option value="Material"><option value="Style">
                    </datalist>
                </div>
                <div>
                    <label class="form-label" style="margin-bottom:0.3rem;display:block;">Value</label>
                    <input type="text" name="value" class="form-input" placeholder="e.g. M, Red" required>
                </div>
                <div>
                    <label class="form-label" style="margin-bottom:0.3rem;display:block;">Price (+/-)</label>
                    <input type="number" name="price_modifier" class="form-input" step="0.01" value="0" placeholder="0.00" required>
                </div>
                <div>
                    <label class="form-label" style="margin-bottom:0.3rem;display:block;">Stock</label>
                    <input type="number" name="stock" class="form-input" min="0" placeholder="(optional)">
                </div>
                <div>
                    <label class="form-label" style="margin-bottom:0.3rem;display:block;">&nbsp;</label>
                    <button type="submit" class="btn-add-variant">+ Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- gen3d Modal --}}
<div id="gen3d-overlay"
     style="display:none;position:fixed;inset:0;z-index:9999;
            background:rgba(26,26,26,0.6);backdrop-filter:blur(4px);
            align-items:center;justify-content:center;padding:20px">
    <div id="gen3d-card" style="background:#fff;border-radius:24px;border:1px solid #e5e7eb;width:100%;max-width:500px;overflow:hidden;box-shadow:0 20px 60px rgba(234,88,12,0.2)">
        <div style="background:#FFF0E8;padding:18px 24px 0">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                <div style="font-size:15px;font-weight:700;color:#1a1a1a">
                    Vox<span style="color:#ea580c">ura</span>
                    <span style="font-size:11px;font-weight:400;color:#6b7280"> 3D Generator</span>
                </div>
                <div id="gen3d-badge" style="display:flex;align-items:center;gap:5px;background:#ea580c;color:#fff;font-size:10px;font-weight:700;padding:4px 10px;border-radius:20px">
                    <div style="width:6px;height:6px;background:#fff;border-radius:50%;animation:gen3d-blink 1s infinite"></div>
                    LIVE
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;background:#fff;border-radius:12px;padding:10px 14px;margin-bottom:0">
                <div style="width:36px;height:36px;background:#e5e7eb;border-radius:8px;overflow:hidden;flex-shrink:0">
                    @if($product->image)
                        <img src="{{ asset('images/' . $product->image) }}" style="width:100%;height:100%;object-fit:cover" alt="">
                    @endif
                </div>
                <div>
                    <div style="font-size:13px;font-weight:700;color:#1a1a1a">{{ $product->name }}</div>
                    <div style="font-size:10px;color:#6b7280;margin-top:1px" id="gen3d-image-count">
                        {{ $product->images->count() }} image{{ $product->images->count() === 1 ? '' : 's' }} · AI pipeline running
                    </div>
                </div>
            </div>
        </div>
        <div style="background:#FFF0E8;padding:28px 24px;display:flex;flex-direction:column;align-items:center">
            <canvas id="gen3d-canvas" width="160" height="160"></canvas>
            <div style="font-size:11px;color:#6b7280;margin-top:12px;letter-spacing:0.5px;text-align:center">
                AI is sculpting your <strong style="color:#ea580c">3D model</strong>
            </div>
        </div>
        <div style="padding:16px 24px" id="gen3d-steps">
            <div class="gen3d-step" id="gen3d-step-1" data-status="pending">
                <div class="gen3d-step-dot">1</div>
                <div class="gen3d-step-info"><div class="gen3d-step-name">Analyzing Images</div><div class="gen3d-step-desc">Qwen3-VL reviewing your product photos</div></div>
                <div class="gen3d-step-time" id="gen3d-time-1"></div>
            </div>
            <div class="gen3d-step" id="gen3d-step-2" data-status="pending">
                <div class="gen3d-step-dot">2</div>
                <div class="gen3d-step-info"><div class="gen3d-step-name">Best Image Selected</div><div class="gen3d-step-desc">Choosing optimal angle for 3D reconstruction</div></div>
                <div class="gen3d-step-time" id="gen3d-time-2"></div>
            </div>
            <div class="gen3d-step" id="gen3d-step-3" data-status="pending">
                <div class="gen3d-step-dot">3</div>
                <div class="gen3d-step-info"><div class="gen3d-step-name">Generating 3D Model</div><div class="gen3d-step-desc">TRELLIS.2 building mesh at 512³ resolution</div></div>
                <div class="gen3d-step-time" id="gen3d-time-3"></div>
            </div>
            <div class="gen3d-step" id="gen3d-step-4" data-status="pending">
                <div class="gen3d-step-dot">4</div>
                <div class="gen3d-step-info"><div class="gen3d-step-name">Saving Model</div><div class="gen3d-step-desc">Storing .glb file and updating product</div></div>
                <div class="gen3d-step-time" id="gen3d-time-4"></div>
            </div>
        </div>
        <div style="padding:0 24px 16px">
            <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                <span style="font-size:11px;color:#6b7280">Overall Progress</span>
                <strong style="font-size:11px;color:#ea580c" id="gen3d-pct">0%</strong>
            </div>
            <div style="background:#F0EBE4;border-radius:20px;height:8px;overflow:hidden">
                <div id="gen3d-bar" style="height:100%;width:0%;border-radius:20px;background:linear-gradient(90deg,#ea580c,#F5A673);transition:width 0.8s ease"></div>
            </div>
            <div style="font-size:10px;color:#6b7280;margin-top:5px;text-align:center" id="gen3d-est">Initializing pipeline...</div>
        </div>
        <div style="background:#FFF0E8;border-top:1px solid #e5e7eb;padding:12px 24px;display:flex;align-items:center;justify-content:space-between">
            <div style="font-size:10px;color:#6b7280;line-height:1.6">
                You can <strong style="color:#1a1a1a">leave this page</strong> safely —<br>generation continues in the background
            </div>
            <button onclick="closeGen3DModal()" style="font-size:11px;color:#6b7280;cursor:pointer;border:1px solid #e5e7eb;background:#fff;border-radius:8px;padding:5px 14px">Close</button>
        </div>
    </div>
</div>

<script>
/* ── Page interactions ── */
(function () {
    var form = document.getElementById('cp-form');
    form.addEventListener('input',  function () { form.dataset.dirty = '1'; }, true);
    form.addEventListener('change', function () { form.dataset.dirty = '1'; }, true);
    form.addEventListener('submit', function () { delete form.dataset.dirty; });
    window.addEventListener('beforeunload', function (e) {
        if (form.dataset.dirty === '1') { e.preventDefault(); e.returnValue = ''; }
    });
    window.cpCancelEdit = function () {
        if (form.dataset.dirty === '1' && !confirm('Leave this page? Unsaved changes will be lost.')) return;
        var backUrl = '{{ $backUrl }}';
        if (typeof adminNavigate === 'function') adminNavigate(backUrl);
        else window.location.href = backUrl;
    };

    /* Character counter */
    var descEl = document.getElementById('cp-desc');
    var descCount = document.getElementById('desc-count');
    function updateDescCount() {
        var n = descEl.value.length;
        descCount.textContent = n;
        descCount.parentElement.className = 'cp-char-counter' + (n > 2000 ? ' over' : n > 1600 ? ' warn' : '');
    }
    descEl.addEventListener('input', updateDescCount);
    updateDescCount();

    /* Cover image preview */
    window.cpCoverPreview = function (input) {
        if (!input.files || !input.files[0]) return;
        var file = input.files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('cp-cover-img').src = e.target.result;
            document.getElementById('cp-cover-name').textContent = file.name;
            document.getElementById('cp-cover-size').textContent = (file.size / 1024).toFixed(0) + ' KB';
            document.getElementById('cp-cover-preview').style.display = 'flex';
        };
        reader.readAsDataURL(file);
    };

    /* Mark existing gallery image for removal */
    window.markRemove = function (id) {
        var cb = document.getElementById('rm-' + id);
        var item = document.getElementById('gi-' + id);
        if (cb) cb.checked = true;
        if (item) {
            item.classList.add('marked');
            item.querySelector('.cp-existing-remove').textContent = '↩';
        }
    };

    /* New gallery upload with drag-to-reorder */
    var galleryFiles = [];
    window.cpGalleryAdd = function (files) {
        Array.prototype.forEach.call(files, function (f) { galleryFiles.push(f); });
        renderGallery();
    };

    function renderGallery() {
        var grid = document.getElementById('cp-gallery-grid');
        var hint = document.getElementById('cp-gallery-hint');
        grid.innerHTML = '';
        if (!galleryFiles.length) { hint.style.display = 'none'; syncGalleryInput(); return; }
        hint.style.display = 'block';
        galleryFiles.forEach(function (file, idx) {
            var item = document.createElement('div');
            item.className = 'cp-gallery-item';
            item.draggable = true;
            var img = document.createElement('img');
            img.alt = '';
            (function (imgEl, f) {
                var r = new FileReader();
                r.onload = function (e) { imgEl.src = e.target.result; };
                r.readAsDataURL(f);
            }(img, file));
            var rm = document.createElement('button');
            rm.type = 'button'; rm.className = 'cp-gallery-remove'; rm.textContent = '×';
            (function (i) { rm.onclick = function () { galleryFiles.splice(i, 1); renderGallery(); }; }(idx));
            item.appendChild(img); item.appendChild(rm); grid.appendChild(item);
            item.addEventListener('dragstart', function (e) {
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', String(idx));
                setTimeout(function () { item.classList.add('dragging'); }, 0);
            });
            item.addEventListener('dragend', function () {
                item.classList.remove('dragging');
                document.querySelectorAll('.cp-gallery-item').forEach(function (el) { el.classList.remove('drag-target'); });
            });
            item.addEventListener('dragover', function (e) {
                if (e.dataTransfer.types.indexOf('text/plain') === -1) return;
                e.preventDefault(); e.stopPropagation();
                document.querySelectorAll('.cp-gallery-item').forEach(function (el) { el.classList.remove('drag-target'); });
                item.classList.add('drag-target');
            });
            item.addEventListener('drop', function (e) {
                e.preventDefault(); e.stopPropagation();
                item.classList.remove('drag-target');
                var fromIdx = parseInt(e.dataTransfer.getData('text/plain'), 10);
                if (isNaN(fromIdx) || fromIdx === idx) return;
                var moved = galleryFiles.splice(fromIdx, 1)[0];
                galleryFiles.splice(idx, 0, moved);
                renderGallery();
            });
        });
        syncGalleryInput();
    }

    function syncGalleryInput() {
        try {
            var dt = new DataTransfer();
            galleryFiles.forEach(function (f) { dt.items.add(f); });
            document.getElementById('cp-gallery-input').files = dt.files;
        } catch (e) {}
    }

    /* OS drag-and-drop onto zones */
    function setupZoneDrop(zoneId, isGallery) {
        var zone = document.getElementById(zoneId);
        if (!zone) return;
        zone.addEventListener('dragover', function (e) {
            if (e.dataTransfer.types.indexOf('Files') !== -1) { e.preventDefault(); zone.classList.add('drag-over'); }
        });
        zone.addEventListener('dragleave', function (e) {
            if (!zone.contains(e.relatedTarget)) zone.classList.remove('drag-over');
        });
        zone.addEventListener('drop', function (e) {
            e.preventDefault(); zone.classList.remove('drag-over');
            if (!e.dataTransfer.files.length) return;
            if (isGallery) {
                cpGalleryAdd(e.dataTransfer.files);
            } else {
                var input = document.getElementById('cp-image-input');
                try { var dt = new DataTransfer(); dt.items.add(e.dataTransfer.files[0]); input.files = dt.files; } catch (err) {}
                cpCoverPreview(input);
            }
        });
    }
    setupZoneDrop('cp-cover-zone', false);
    setupZoneDrop('cp-gallery-zone', true);

    /* Size guide dynamic rows */
    function sgSync() {
        var rows = document.querySelectorAll('#size-guide-rows .cp-size-row');
        var lines = [];
        rows.forEach(function (row) {
            var inputs = row.querySelectorAll('input[type="text"]');
            var vals = Array.prototype.map.call(inputs, function (inp) { return inp.value.trim(); });
            if (vals.some(function (v) { return v !== ''; })) lines.push(vals.join('|'));
        });
        document.getElementById('size-guide-hidden').value = lines.join('\n');
    }
    window.sgAddRow = function (size, chest, waist, length) {
        var container = document.getElementById('size-guide-rows');
        var row = document.createElement('div');
        row.className = 'cp-size-row';
        var placeholders = ['e.g. XS', 'Chest', 'Waist', 'Length'];
        [size || '', chest || '', waist || '', length || ''].forEach(function (val, i) {
            var inp = document.createElement('input');
            inp.type = 'text'; inp.className = 'form-input';
            inp.placeholder = placeholders[i]; inp.value = val;
            inp.addEventListener('input', sgSync);
            row.appendChild(inp);
        });
        var rm = document.createElement('button');
        rm.type = 'button'; rm.className = 'cp-row-remove'; rm.textContent = '×';
        rm.onclick = function () { row.remove(); sgSync(); };
        row.appendChild(rm);
        container.appendChild(row);
        sgSync();
    };
    (function () {
        var raw = document.getElementById('size-guide-hidden').value.trim();
        if (raw) {
            raw.split('\n').forEach(function (line) { var p = line.split('|'); sgAddRow(p[0], p[1], p[2], p[3]); });
        } else {
            sgAddRow();
        }
    }());

    /* Color swatches dynamic rows */
    function swSync() {
        var rows = document.querySelectorAll('#swatches-rows .cp-swatch-row');
        var lines = [];
        rows.forEach(function (row) {
            var name = row.querySelector('.sw-name').value.trim();
            var hex = row.querySelector('.sw-hex').value.trim();
            if (name || hex) lines.push(name + '|' + hex);
        });
        document.getElementById('swatches-hidden').value = lines.join('\n');
    }
    window.swAddRow = function (name, hex) {
        var container = document.getElementById('swatches-rows');
        var row = document.createElement('div');
        row.className = 'cp-swatch-row';
        var nameInp = document.createElement('input');
        nameInp.type = 'text'; nameInp.className = 'form-input sw-name';
        nameInp.placeholder = 'e.g. Midnight'; nameInp.value = name || '';
        nameInp.addEventListener('input', swSync);
        var safeHex = (hex && /^#[0-9a-fA-F]{6}$/.test(hex)) ? hex : '#111111';
        var colorPick = document.createElement('input');
        colorPick.type = 'color'; colorPick.className = 'cp-color-pick'; colorPick.value = safeHex;
        var hexInp = document.createElement('input');
        hexInp.type = 'text'; hexInp.className = 'form-input sw-hex';
        hexInp.placeholder = '#000000'; hexInp.value = hex || ''; hexInp.maxLength = 7;
        colorPick.addEventListener('input', function () { hexInp.value = colorPick.value; swSync(); });
        hexInp.addEventListener('input', function () {
            var v = hexInp.value.trim();
            if (/^#[0-9a-fA-F]{6}$/.test(v)) colorPick.value = v;
            swSync();
        });
        var rm = document.createElement('button');
        rm.type = 'button'; rm.className = 'cp-row-remove'; rm.textContent = '×';
        rm.onclick = function () { row.remove(); swSync(); };
        row.appendChild(nameInp); row.appendChild(colorPick); row.appendChild(hexInp); row.appendChild(rm);
        container.appendChild(row);
        swSync();
    };
    (function () {
        var raw = document.getElementById('swatches-hidden').value.trim();
        if (raw) { raw.split('\n').forEach(function (line) { var p = line.split('|'); swAddRow(p[0], p[1]); }); }
    }());
}());

/* ── m3d inline status card ── */
(function () {
    const statusUrl = @json(route('admin.products.3d-status', $product));
    const regenUrl  = @json(route('admin.products.regenerate-3d', $product));
    const csrf      = @json(csrf_token());
    @php
        $m3dInitialState = [
            'status'                 => $product->model3d_status ?? 'idle',
            'has_3d_model'           => (bool) $product->has_3d_model,
            'model3d_queued_at'      => optional($product->model3d_queued_at)->toDateTimeString(),
            'model3d_generated_at'   => optional($product->model3d_generated_at)->toDateTimeString(),
            'model3d_error'          => $product->model3d_error,
            'model3d_selected_image' => $product->model3d_selected_image ? asset('images/' . basename($product->model3d_selected_image)) : null,
            'model_url'              => $product->is3DReady() ? $product->get3DModelUrl() : null,
        ];
    @endphp
    let state  = @json($m3dInitialState);
    let poller = null;

    const badge   = document.getElementById('m3d-badge');
    const body    = document.getElementById('m3d-body');
    const actions = document.getElementById('m3d-actions');

    function escapeHtml(s) { return (s||'').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

    function render() {
        const s = state.status || 'idle';
        const badges = {
            idle:       { text: 'No 3D Model', bg: '#e5e7eb', color: '#374151', prefix: '' },
            queued:     { text: 'Queued',      bg: '#fef3c7', color: '#92400e', prefix: '<span class="m3d-dot"></span>' },
            processing: { text: 'Processing',  bg: '#dbeafe', color: '#1e40af', prefix: '<span class="m3d-spinner"></span>' },
            ready:      { text: '✓ Ready',     bg: '#d1fae5', color: '#065f46', prefix: '' },
            failed:     { text: '✕ Failed',    bg: '#fee2e2', color: '#991b1b', prefix: '' },
        };
        const b = badges[s] || badges.idle;
        badge.style.background = b.bg; badge.style.color = b.color;
        badge.innerHTML = b.prefix + escapeHtml(b.text);

        let html = '', actionHtml = '';
        const hasImg = {{ $product->images->count() > 0 ? 'true' : 'false' }};
        const regenBtn = hasImg
            ? '<button type="button" style="background:#ea580c;color:#fff;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:600;cursor:pointer;" onclick="window._m3dRegen()">Re-generate 3D Model</button>'
            : '<button type="button" disabled title="Upload images first" style="background:#e5e7eb;color:#9ca3af;border:none;border-radius:8px;padding:8px 16px;font-size:13px;font-weight:600;cursor:not-allowed;">Re-generate 3D Model</button>';

        if (s === 'idle') {
            html = '<p style="font-size:0.85rem;color:var(--muted);margin:0;">Upload product images to auto-generate a 3D model.</p>';
        } else if (s === 'queued') {
            html = '<p style="font-size:0.85rem;color:var(--muted);margin:0 0 0.3rem;">3D generation is queued. Processing will begin shortly.</p>'
                + (state.model3d_queued_at ? '<p style="font-size:0.75rem;color:var(--muted);margin:0;">Queued at: ' + escapeHtml(state.model3d_queued_at) + '</p>' : '')
                + '<p style="font-size:0.75rem;color:#92400e;margin-top:0.4rem;">If status stays "Queued" for more than 2 minutes, make sure the queue worker is running: <code>php artisan queue:work</code></p>';
        } else if (s === 'processing') {
            html = '<p style="font-size:0.85rem;color:var(--muted);margin:0 0 0.5rem;">AI is analyzing images and generating your 3D model…</p>'
                + '<ul style="list-style:none;padding:0;margin:0;font-size:0.8rem;color:var(--gray-600);">'
                + '<li>✓ Images uploaded</li><li>⟳ Analyzing with Qwen-VL (selecting best image)</li>'
                + '<li>○ Generating 3D with TRELLIS</li><li>○ Saving model</li></ul>';
        } else if (s === 'ready') {
            html = '<p style="font-size:0.85rem;color:#065f46;margin:0 0 0.5rem;">3D model generated successfully.</p>'
                + (state.model3d_generated_at ? '<p style="font-size:0.75rem;color:var(--muted);margin:0 0 0.6rem;">Generated at: ' + escapeHtml(state.model3d_generated_at) + '</p>' : '')
                + (state.model3d_selected_image ? '<div style="display:flex;align-items:center;gap:0.75rem;"><span style="font-size:0.75rem;color:var(--muted);">Selected image:</span><img src="' + escapeHtml(state.model3d_selected_image) + '" style="width:56px;height:56px;object-fit:cover;border-radius:0.4rem;border:1px solid var(--gray-200);"></div>' : '');
            if (state.model_url) actionHtml += '<a href="' + escapeHtml(state.model_url) + '" target="_blank" class="add-btn" style="text-decoration:none;">Download .glb</a>';
            actionHtml += regenBtn;
        } else if (s === 'failed') {
            html = '<p style="font-size:0.85rem;color:#991b1b;margin:0 0 0.5rem;">3D generation failed.</p>';
            if (state.model3d_error) {
                html += '<details style="margin-bottom:0.6rem;"><summary style="font-size:0.78rem;cursor:pointer;color:var(--gray-600);">Error details</summary>'
                    + '<pre style="font-size:0.72rem;background:#fef2f2;padding:0.5rem;border-radius:0.4rem;margin-top:0.4rem;white-space:pre-wrap;color:#991b1b;">' + escapeHtml(state.model3d_error) + '</pre></details>';
            }
            actionHtml = regenBtn;
        }
        body.innerHTML = html; actions.innerHTML = actionHtml;
    }

    function startPolling() { if (poller) return; poller = setInterval(fetchStatus, 5000); }
    function stopPolling()  { if (poller) { clearInterval(poller); poller = null; } }
    async function fetchStatus() {
        try {
            const res = await fetch(statusUrl, { headers: { 'Accept': 'application/json' }});
            if (!res.ok) return;
            state = await res.json(); render();
            if (['ready','failed','idle'].includes(state.status)) stopPolling();
        } catch (_) {}
    }
    window._m3dRegen = async function () {
        try {
            const res = await fetch(regenUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }});
            if (res.ok) {
                state.status = 'queued'; state.model3d_error = null;
                render(); startPolling();
                if (typeof openGen3DModal === 'function') openGen3DModal();
            }
        } catch (_) {}
    };
    render();
    if (['queued','processing'].includes(state.status)) startPolling();
}());

/* ── gen3d modal ── */
(function () {
    function initGen3DBlob() {
        var canvas = document.getElementById('gen3d-canvas');
        if (!canvas) return function () {};
        var ctx = canvas.getContext('2d');
        var W = 160, H = 160, CX = 80, CY = 80, animId = null;
        function drawBlob(t) {
            ctx.clearRect(0, 0, W, H);
            var n = 8, pts = [], pts2 = [], i, angle, r, cur, nxt;
            for (i = 0; i < n; i++) {
                angle = (i / n) * Math.PI * 2;
                r = 42 + Math.sin(t*1.3+i*1.1)*10 + Math.cos(t*0.9+i*2.3)*8 + Math.sin(t*2.1+i*0.7)*5;
                pts.push({ x: CX + Math.cos(angle)*r, y: CY + Math.sin(angle)*r });
            }
            ctx.beginPath();
            ctx.moveTo((pts[0].x+pts[n-1].x)/2, (pts[0].y+pts[n-1].y)/2);
            for (i = 0; i < n; i++) { cur=pts[i]; nxt=pts[(i+1)%n]; ctx.quadraticCurveTo(cur.x,cur.y,(cur.x+nxt.x)/2,(cur.y+nxt.y)/2); }
            ctx.closePath();
            var grad = ctx.createRadialGradient(CX-10,CY-10,5,CX,CY,55);
            grad.addColorStop(0,'#F5A673'); grad.addColorStop(0.5,'#ea580c'); grad.addColorStop(1,'#c2410c');
            ctx.fillStyle = grad; ctx.shadowColor = 'rgba(234,88,12,0.45)'; ctx.shadowBlur = 20; ctx.fill(); ctx.shadowBlur = 0;
            var shine = ctx.createRadialGradient(CX-14,CY-14,2,CX-8,CY-8,24);
            shine.addColorStop(0,'rgba(255,255,255,0.38)'); shine.addColorStop(1,'rgba(255,255,255,0)');
            ctx.fillStyle = shine; ctx.fill();
            for (i = 0; i < n; i++) {
                angle = (i/n)*Math.PI*2+0.4; r = 20+Math.sin(t*1.7+i*1.4)*6+Math.cos(t*1.1+i*2.0)*5;
                pts2.push({ x: CX+Math.cos(angle)*r, y: CY+Math.sin(angle)*r });
            }
            ctx.beginPath();
            ctx.moveTo((pts2[0].x+pts2[n-1].x)/2,(pts2[0].y+pts2[n-1].y)/2);
            for (i = 0; i < n; i++) { cur=pts2[i]; nxt=pts2[(i+1)%n]; ctx.quadraticCurveTo(cur.x,cur.y,(cur.x+nxt.x)/2,(cur.y+nxt.y)/2); }
            ctx.closePath(); ctx.fillStyle='rgba(255,255,255,0.18)'; ctx.fill();
            t += 0.025; animId = requestAnimationFrame(function () { drawBlob(t); });
        }
        drawBlob(0);
        return function () { if (animId) { cancelAnimationFrame(animId); animId = null; } };
    }

    var gen3dStopBlob = null, gen3dPollInterval = null, gen3dElapsed = 0, gen3dCurrentPct = 0;
    var gen3dStatusUrl = @json(route('admin.products.3d-status', $product));

    function setStepStatus(num, status) {
        var step = document.getElementById('gen3d-step-' + num); if (!step) return;
        step.setAttribute('data-status', status);
        var dot = step.querySelector('.gen3d-step-dot');
        if (status === 'done') dot.textContent = '✓';
        else if (status === 'active') dot.textContent = '⟳';
        else dot.textContent = num;
    }
    function updateProgress(pct, estText) {
        gen3dCurrentPct = pct;
        document.getElementById('gen3d-pct').textContent = Math.round(pct) + '%';
        document.getElementById('gen3d-bar').style.width = pct + '%';
        if (estText) document.getElementById('gen3d-est').textContent = estText;
    }
    function resetModal() {
        [1,2,3,4].forEach(function (i) { setStepStatus(i,'pending'); document.getElementById('gen3d-time-'+i).textContent=''; });
        updateProgress(0,'Initializing pipeline...');
        var badge = document.getElementById('gen3d-badge');
        badge.style.background = '#ea580c';
        badge.innerHTML = '<div style="width:6px;height:6px;background:#fff;border-radius:50%;animation:gen3d-blink 1s infinite"></div> LIVE';
        document.getElementById('gen3d-est').style.color = '#6b7280';
    }
    function updateGen3DModal(data, elapsed) {
        var s = data.status;
        if (s === 'queued') { setStepStatus(1,'active'); updateProgress(8,'Starting AI pipeline...'); }
        else if (s === 'processing') {
            setStepStatus(1,'done'); document.getElementById('gen3d-time-1').textContent = elapsed+'s';
            if (data.model3d_selected_image) {
                document.querySelector('#gen3d-step-2 .gen3d-step-desc').textContent = 'Best image selected — AI analyzing geometry';
                setStepStatus(2,'done'); document.getElementById('gen3d-time-2').textContent = elapsed+'s';
                setStepStatus(3,'active'); updateProgress(Math.min(85,30+elapsed),'~'+Math.max(5,60-elapsed)+' seconds remaining');
            } else { setStepStatus(2,'active'); updateProgress(25,'Analyzing images with Qwen3-VL...'); }
        } else if (s === 'ready') {
            [1,2,3,4].forEach(function (i) { setStepStatus(i,'done'); });
            updateProgress(100,'Your 3D model is ready!');
            document.getElementById('gen3d-est').style.color = '#16a34a';
            var badge = document.getElementById('gen3d-badge'); badge.style.background='#16a34a'; badge.textContent='COMPLETE';
        } else if (s === 'failed') {
            updateProgress(gen3dCurrentPct,'Generation failed — check error details');
            var badge = document.getElementById('gen3d-badge'); badge.style.background='#dc2626'; badge.textContent='FAILED';
        }
    }
    function startGen3DPolling() {
        if (gen3dPollInterval) return;
        gen3dElapsed = 0;
        gen3dPollInterval = setInterval(function () {
            gen3dElapsed += 5;
            fetch(gen3dStatusUrl, { headers: {'Accept':'application/json'} })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    updateGen3DModal(data, gen3dElapsed);
                    if (data.status === 'ready') {
                        clearInterval(gen3dPollInterval); gen3dPollInterval = null;
                        setTimeout(function () {
                        closeGen3DModal();
                        if (typeof adminNavigate === 'function') adminNavigate(window.location.pathname);
                        else window.location.reload();
                    }, 2000);
                    } else if (data.status === 'failed') { clearInterval(gen3dPollInterval); gen3dPollInterval = null; }
                })
                .catch(function (e) { console.warn('gen3d poll failed', e); });
        }, 5000);
    }

    window.openGen3DModal = function () {
        var overlay = document.getElementById('gen3d-overlay');
        overlay.style.display = 'flex'; document.body.style.overflow = 'hidden';
        resetModal();
        if (gen3dStopBlob) { gen3dStopBlob(); gen3dStopBlob = null; }
        gen3dStopBlob = initGen3DBlob();
        startGen3DPolling();
    };
    window.closeGen3DModal = function () {
        document.getElementById('gen3d-overlay').style.display = 'none';
        document.body.style.overflow = '';
        if (gen3dStopBlob) { gen3dStopBlob(); gen3dStopBlob = null; }
        if (gen3dPollInterval) { clearInterval(gen3dPollInterval); gen3dPollInterval = null; }
    };

    document.getElementById('gen3d-overlay').addEventListener('click', function (e) {
        if (e.target === this) window.closeGen3DModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') window.closeGen3DModal();
    });

    @if(in_array($product->model3d_status ?? 'idle', ['queued', 'processing']))
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(window.openGen3DModal, 800);
    });
    @endif
}());

// AJAX form submission
(function () {
    var form = document.getElementById('cp-form');
    if (!form || document.getElementById('admin-drawer-body')?.contains(form)) return;
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (typeof submitForm === 'function') {
            await submitForm(form, function () {
                var redirectTo = form.querySelector('[name="redirect_to"]')?.value;
                var dest = redirectTo === 'archive' ? '/admin/archive' : '/admin/products';
                if (typeof adminNavigate === 'function') adminNavigate(dest);
                else window.location.href = dest;
            });
        }
    });
}());
</script>
</x-admin-layout>
