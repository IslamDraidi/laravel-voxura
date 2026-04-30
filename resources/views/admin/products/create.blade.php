<x-admin-layout title="Add Product" section="catalog" active="products">
<style>
/* ── Create Product Page ── */
.cp-header { margin-bottom: 20px; }
.cp-breadcrumb { font-size: 12px; color: var(--muted); margin-bottom: 8px; }
.cp-breadcrumb a { color: var(--muted); text-decoration: none; transition: color .15s; }
.cp-breadcrumb a:hover { color: var(--orange); }
.cp-title-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.cp-page-title { font-size: 22px; font-weight: 700; color: var(--dark); }
.cp-header-actions { display: flex; gap: 8px; }

.cp-layout { display: grid; grid-template-columns: 1fr 370px; gap: 20px; align-items: start; margin-bottom: 80px; }

/* Cards */
.cp-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; margin-bottom: 16px; }
.cp-card-header { padding: 14px 20px 12px; border-bottom: 1px solid var(--border); border-left: 3px solid var(--orange); }
.cp-card-title { font-size: 14px; font-weight: 700; color: var(--dark); line-height: 1.3; }
.cp-card-sub { font-size: 11px; color: var(--muted); margin-top: 2px; }
.cp-card-body { padding: 20px; }

/* Fields */
.cp-field { margin-bottom: 14px; }
.cp-field:last-child { margin-bottom: 0; }
.cp-label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: 5px; }
.cp-label .req { color: var(--red); margin-left: 2px; }
.cp-helper { font-size: 11px; color: #9ca3af; margin-top: 4px; line-height: 1.5; }
.cp-card .form-input:focus, .cp-card .form-select:focus, .cp-card .form-textarea:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(234,88,12,.1); outline: none; }

/* Side-by-side grid */
.cp-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

/* Buttons */
.cp-btn-draft { background: #fff; color: var(--dark); border: 1.5px solid var(--border); border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: border-color .15s; white-space: nowrap; }
.cp-btn-draft:hover { border-color: var(--dark); }
.cp-btn-publish { background: var(--orange); color: #fff; border: none; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background .15s; white-space: nowrap; }
.cp-btn-publish:hover { background: var(--orange-dark); }

/* Toggles */
.cp-toggle-row { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; transition: border-color .15s; margin-bottom: 8px; }
.cp-toggle-row:last-child { margin-bottom: 0; }
.cp-toggle-row:hover { border-color: var(--orange); }
.cp-toggle-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--orange); flex-shrink: 0; }
.cp-toggle-label { font-size: 13px; color: var(--dark); font-weight: 500; }
.cp-toggle-sub { font-size: 11px; color: var(--muted); }

/* Image zones */
.cp-image-zone { border: 2px dashed var(--border); border-radius: 10px; padding: 18px; text-align: center; cursor: pointer; transition: border-color .15s, background .15s; position: relative; }
.cp-image-zone:hover, .cp-image-zone.drag-over { border-color: var(--orange); background: var(--orange-pale); }
.cp-image-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.cp-zone-icon { font-size: 22px; margin-bottom: 5px; }
.cp-zone-text { font-size: 13px; font-weight: 600; color: var(--dark); }
.cp-zone-sub { font-size: 11px; color: var(--muted); margin-top: 2px; }

/* Cover preview */
.cp-preview-cover { display: none; align-items: center; gap: 10px; padding: 8px; background: var(--gray-50); border-radius: 8px; border: 1px solid var(--border); margin-bottom: 8px; }
.cp-preview-cover img { width: 52px; height: 52px; object-fit: cover; border-radius: 6px; flex-shrink: 0; }
.cp-preview-cover-info { flex: 1; min-width: 0; }
.cp-preview-cover-name { font-size: 12px; font-weight: 600; color: var(--dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cp-preview-cover-size { font-size: 11px; color: var(--muted); }
.cp-preview-cover-main { background: var(--orange); color: #fff; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; white-space: nowrap; }

/* Gallery thumbnails */
.cp-gallery-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px; }
.cp-gallery-item { position: relative; width: 68px; height: 68px; border-radius: 8px; cursor: grab; user-select: none; }
.cp-gallery-item:active { cursor: grabbing; }
.cp-gallery-item.dragging { opacity: .35; }
.cp-gallery-item.drag-target { outline: 2px dashed var(--orange); outline-offset: 2px; }
.cp-gallery-item img { width: 68px; height: 68px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); pointer-events: none; display: block; }
.cp-gallery-item:first-child::after { content: 'Main'; position: absolute; bottom: -7px; left: 50%; transform: translateX(-50%); background: var(--orange); color: #fff; font-size: 9px; font-weight: 700; padding: 1px 6px; border-radius: 999px; white-space: nowrap; }
.cp-gallery-remove { position: absolute; top: -7px; right: -7px; width: 20px; height: 20px; background: #ef4444; color: #fff; border: none; border-radius: 50%; font-size: 11px; font-weight: 900; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 2; line-height: 1; }

/* Character counter */
.cp-char-counter { font-size: 11px; color: var(--muted); text-align: right; margin-top: 3px; }
.cp-char-counter.warn { color: #d97706; }
.cp-char-counter.over { color: var(--red); }

/* 3D placeholder */
.cp-3d-info { background: var(--orange-pale); border: 1px solid var(--orange-muted); border-radius: 10px; padding: 14px 16px; font-size: 13px; color: var(--dark); line-height: 1.6; }
.cp-3d-info strong { color: var(--orange); }

/* Size guide & swatch row builders */
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

/* Sticky bottom bar */
.cp-bottom-bar { position: fixed; bottom: 0; left: 200px; right: 0; z-index: 40; background: #fff; border-top: 1px solid var(--border); padding: 12px 28px; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
.cp-bottom-warn { font-size: 12px; color: var(--muted); }
.cp-bottom-actions { display: flex; gap: 8px; align-items: center; }

/* Responsive */
@media (max-width: 1024px) {
    .cp-layout { grid-template-columns: 1fr; }
    .cp-col-right { order: -1; }
    .cp-bottom-bar { left: 0; padding: 12px 16px; }
}
@media (max-width: 640px) {
    .cp-grid-2 { grid-template-columns: 1fr; }
    .cp-size-row { grid-template-columns: 1fr 1fr; }
    .cp-size-header { grid-template-columns: 1fr 1fr; }
    .cp-title-row { flex-direction: column; align-items: flex-start; }
    .cp-header-actions { width: 100%; }
    .cp-header-actions button { flex: 1; text-align: center; justify-content: center; }
    .cp-bottom-warn { display: none; }
}
</style>

{{-- ── Page Header ── --}}
<div class="cp-header">
    <div class="cp-breadcrumb">
        <a href="/admin">Admin</a> ›
        <a href="/admin/products">Products</a> ›
        Add New Product
    </div>
    <div class="cp-title-row">
        <h1 class="cp-page-title">Add New Product</h1>
        <div class="cp-header-actions">
            <button type="button" class="cp-btn-draft" onclick="cpSubmit('draft')">Save as Draft</button>
            <button type="button" class="cp-btn-publish" onclick="cpSubmit('active')">Publish Product</button>
        </div>
    </div>
</div>

<form id="cp-form" method="POST" action="/admin/products" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="status" id="cp-status" value="active">

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
                    <div class="cp-field">
                        <label class="cp-label" for="cp-name">Product Name <span class="req">*</span></label>
                        <input type="text" id="cp-name" name="name" class="form-input"
                               placeholder="e.g. Premium Linen Blazer"
                               value="{{ old('name') }}" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="cp-grid-2">
                        <div class="cp-field">
                            <label class="cp-label" for="cp-sku">SKU</label>
                            <input type="text" id="cp-sku" name="sku" class="form-input"
                                   placeholder="e.g. VX-TSH-001"
                                   value="{{ old('sku') }}">
                            <p class="cp-helper">Leave blank to auto-generate</p>
                            @error('sku')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cp-field">
                            <label class="cp-label" for="cp-material">Material</label>
                            <input type="text" id="cp-material" name="material" class="form-input"
                                   placeholder="e.g. 100% Cotton"
                                   value="{{ old('material') }}">
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
                                   value="{{ old('price') }}" required>
                            @error('price')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cp-field">
                            <label class="cp-label" for="cp-sale-badge">Sale Badge</label>
                            <input type="text" id="cp-sale-badge" name="sale_badge" class="form-input"
                                   placeholder="e.g. 20% OFF"
                                   value="{{ old('sale_badge') }}">
                            @error('sale_badge')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="cp-grid-2">
                        <div class="cp-field">
                            <label class="cp-label" for="cp-stock">Stock Quantity <span class="req">*</span></label>
                            <input type="number" id="cp-stock" name="stock" class="form-input"
                                   placeholder="0" min="0"
                                   value="{{ old('stock') }}" required>
                            @error('stock')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cp-field">
                            <label class="cp-label" for="cp-stock-alert">Low Stock Alert <span class="req">*</span></label>
                            <input type="number" id="cp-stock-alert" name="stock_alert_threshold" class="form-input"
                                   min="1" max="99" value="{{ old('stock_alert_threshold', 6) }}" required>
                            <p class="cp-helper">Alert when stock falls below this</p>
                            @error('stock_alert_threshold')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="cp-grid-2">
                        <div class="cp-field">
                            <label class="cp-label" for="cp-max-qty">Max Order Quantity <span class="req">*</span></label>
                            <input type="number" id="cp-max-qty" name="max_order_quantity" class="form-input"
                                   min="1" max="99" value="{{ old('max_order_quantity', 5) }}" required>
                            @error('max_order_quantity')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cp-field">
                            <label class="cp-label" for="cp-delivery">Delivery Estimate</label>
                            <input type="text" id="cp-delivery" name="delivery_estimate" class="form-input"
                                   placeholder="e.g. Arrives in 2-4 business days"
                                   value="{{ old('delivery_estimate', 'Arrives in 2-4 business days') }}">
                            @error('delivery_estimate')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Product Description --}}
            <div class="cp-card">
                <div class="cp-card-header">
                    <div class="cp-card-title">Product Description</div>
                    <div class="cp-card-sub">Shown on the product page — be detailed and persuasive</div>
                </div>
                <div class="cp-card-body">
                    <div class="cp-field">
                        <label class="cp-label" for="cp-desc">Description <span class="req">*</span></label>
                        <textarea id="cp-desc" name="description" class="form-textarea" rows="7"
                                  placeholder="Describe your product in detail — materials, fit, features, and what makes it special…"
                                  required>{{ old('description') }}</textarea>
                        <div class="cp-char-counter"><span id="desc-count">{{ strlen(old('description', '')) }}</span> characters</div>
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
                               value="{{ old('fit') }}">
                        @error('fit')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="cp-field">
                        <label class="cp-label" for="cp-care">Care Instructions</label>
                        <textarea id="cp-care" name="care_instructions" class="form-textarea" rows="3"
                                  placeholder="Machine wash cold. Tumble dry low.">{{ old('care_instructions') }}</textarea>
                        @error('care_instructions')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="cp-field">
                        <label class="cp-label" for="cp-shipping">Shipping & Returns</label>
                        <textarea id="cp-shipping" name="shipping_returns" class="form-textarea" rows="4"
                                  placeholder="Add shipping windows, free shipping threshold, and return policy details.">{{ old('shipping_returns') }}</textarea>
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
                    <textarea name="size_guide_rows" id="size-guide-hidden" style="display:none;" rows="5">{{ old('size_guide_rows') }}</textarea>
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
                        <input type="checkbox" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}>
                        <div>
                            <div class="cp-toggle-label">Show "New" badge</div>
                            <div class="cp-toggle-sub">Displays a New badge on the product image</div>
                        </div>
                    </label>
                    @error('is_new')<p class="form-error">{{ $message }}</p>@enderror
                    <label class="cp-toggle-row">
                        <input type="checkbox" name="has_colors" id="has_colors_toggle" value="1"
                               {{ old('has_colors') ? 'checked' : '' }}
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
                            <option value="">Select category…</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                    <div class="cp-field">
                        <label class="cp-label">Cover Image</label>
                        <div id="cp-cover-preview" class="cp-preview-cover">
                            <img id="cp-cover-img" src="" alt="">
                            <div class="cp-preview-cover-info">
                                <div class="cp-preview-cover-name" id="cp-cover-name"></div>
                                <div class="cp-preview-cover-size" id="cp-cover-size"></div>
                            </div>
                            <span class="cp-preview-cover-main">Main</span>
                        </div>
                        <div class="cp-image-zone" id="cp-cover-zone">
                            <input type="file" id="cp-image-input" name="image" accept="image/*"
                                   onchange="cpCoverPreview(this)">
                            <div class="cp-zone-icon">🖼️</div>
                            <div class="cp-zone-text">Drag or click to upload cover</div>
                            <div class="cp-zone-sub">JPG, PNG, WEBP</div>
                        </div>
                        @error('image')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="cp-field">
                        <label class="cp-label">Gallery Images</label>
                        <div id="cp-gallery-grid" class="cp-gallery-grid"></div>
                        <div class="cp-image-zone" id="cp-gallery-zone">
                            <input type="file" id="cp-gallery-input" name="gallery[]"
                                   accept="image/*" multiple
                                   onchange="cpGalleryAdd(this.files)">
                            <div class="cp-zone-icon">📷</div>
                            <div class="cp-zone-text">Drag or click to add gallery images</div>
                            <div class="cp-zone-sub">Hold Ctrl/Cmd to select multiple</div>
                        </div>
                        <input type="hidden" name="gallery_order" id="gallery-order-input" value="[]">
                        <p class="cp-helper" id="cp-gallery-hint" style="display:none;">Drag thumbnails to reorder · First image shown as "Main"</p>
                        @error('gallery.*')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Card 9: 3D Model --}}
            <div class="cp-card">
                <div class="cp-card-header">
                    <div class="cp-card-title">3D Model</div>
                    <div class="cp-card-sub">AI-powered 3D generation</div>
                </div>
                <div class="cp-card-body">
                    <div class="cp-3d-info">
                        <strong>3D model generation</strong> is available after saving the product for the first time.
                        Upload images above, save the product, then return to the edit page to generate or upload a 3D model.
                    </div>
                </div>
            </div>

            {{-- Card 10: Color Swatches (conditional) --}}
            <div class="cp-card" id="color_swatches_section" style="{{ old('has_colors') ? 'display:block' : 'display:none' }}">
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
                    <textarea name="color_swatches_rows" id="swatches-hidden" style="display:none;" rows="5">{{ old('color_swatches_rows') }}</textarea>
                    @error('color_swatches_rows')<p class="form-error">{{ $message }}</p>@enderror
                    @error('color_swatches')<p class="form-error">{{ $message }}</p>@enderror
                    @error('color_swatches.*.name')<p class="form-error">{{ $message }}</p>@enderror
                    @error('color_swatches.*.hex')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

        </div>{{-- /cp-col-right --}}

    </div>{{-- /cp-layout --}}

</form>

{{-- Sticky bottom bar — outside form, submits via JS --}}
<div class="cp-bottom-bar">
    <span class="cp-bottom-warn">⚠ All changes will be lost if you leave without saving</span>
    <div class="cp-bottom-actions">
        <a href="/admin/products" class="act-btn" id="cp-cancel-btn">Cancel</a>
        <button type="button" class="cp-btn-draft" onclick="cpSubmit('draft')">Save as Draft</button>
        <button type="button" class="cp-btn-publish" onclick="cpSubmit('active')">Publish Product</button>
    </div>
</div>

<script>
(function () {
    var form = document.getElementById('cp-form');
    var isDirty = false;

    /* ── Dirty state tracking ── */
    form.addEventListener('input', function () { isDirty = true; }, true);
    form.addEventListener('change', function () { isDirty = true; }, true);

    window.addEventListener('beforeunload', function (e) {
        if (isDirty) { e.preventDefault(); e.returnValue = ''; }
    });

    document.getElementById('cp-cancel-btn').addEventListener('click', function (e) {
        if (isDirty && !confirm('Leave this page? All unsaved changes will be lost.')) {
            e.preventDefault();
        }
    });

    /* ── Form submit ── */
    window.cpSubmit = function (status) {
        document.getElementById('cp-status').value = status;
        isDirty = false;
        form.submit();
    };

    /* ── Description character counter ── */
    var descEl = document.getElementById('cp-desc');
    var descCount = document.getElementById('desc-count');
    function updateDescCount() {
        var n = descEl.value.length;
        descCount.textContent = n;
        descCount.parentElement.className = 'cp-char-counter' + (n > 2000 ? ' over' : n > 1600 ? ' warn' : '');
    }
    descEl.addEventListener('input', updateDescCount);
    updateDescCount();

    /* ── Cover image preview ── */
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

    /* ── Gallery: preview + drag-to-reorder ── */
    var galleryFiles = [];

    window.cpGalleryAdd = function (files) {
        Array.prototype.forEach.call(files, function (f) { galleryFiles.push(f); });
        renderGallery();
    };

    function renderGallery() {
        var grid = document.getElementById('cp-gallery-grid');
        var hint = document.getElementById('cp-gallery-hint');
        grid.innerHTML = '';
        if (!galleryFiles.length) {
            hint.style.display = 'none';
            syncGalleryInput();
            updateGalleryOrder();
            return;
        }
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
            rm.type = 'button';
            rm.className = 'cp-gallery-remove';
            rm.textContent = '×';
            rm.title = 'Remove';
            (function (i) {
                rm.onclick = function () { galleryFiles.splice(i, 1); renderGallery(); };
            }(idx));

            item.appendChild(img);
            item.appendChild(rm);
            grid.appendChild(item);

            /* ── Drag events for reorder ── */
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
                /* Only handle internal gallery reorder drags */
                if (e.dataTransfer.types.indexOf('text/plain') === -1) return;
                e.preventDefault();
                e.stopPropagation();
                e.dataTransfer.dropEffect = 'move';
                document.querySelectorAll('.cp-gallery-item').forEach(function (el) { el.classList.remove('drag-target'); });
                item.classList.add('drag-target');
            });
            item.addEventListener('drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                item.classList.remove('drag-target');
                var fromIdx = parseInt(e.dataTransfer.getData('text/plain'), 10);
                if (isNaN(fromIdx) || fromIdx === idx) return;
                var moved = galleryFiles.splice(fromIdx, 1)[0];
                galleryFiles.splice(idx, 0, moved);
                renderGallery();
            });
        });

        syncGalleryInput();
        updateGalleryOrder();
    }

    function syncGalleryInput() {
        try {
            var dt = new DataTransfer();
            galleryFiles.forEach(function (f) { dt.items.add(f); });
            document.getElementById('cp-gallery-input').files = dt.files;
        } catch (e) { /* DataTransfer not available in this browser */ }
    }

    function updateGalleryOrder() {
        document.getElementById('gallery-order-input').value = JSON.stringify(
            galleryFiles.map(function (_, i) { return i; })
        );
    }

    /* ── Drop-zone drag-and-drop from OS file manager ── */
    function setupZoneDrop(zoneId, isGallery) {
        var zone = document.getElementById(zoneId);
        zone.addEventListener('dragover', function (e) {
            if (e.dataTransfer.types.indexOf('Files') !== -1) { e.preventDefault(); zone.classList.add('drag-over'); }
        });
        zone.addEventListener('dragleave', function (e) {
            if (!zone.contains(e.relatedTarget)) zone.classList.remove('drag-over');
        });
        zone.addEventListener('drop', function (e) {
            e.preventDefault();
            zone.classList.remove('drag-over');
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

    /* ── Size Guide: dynamic row builder ── */
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
        var vals = [size || '', chest || '', waist || '', length || ''];
        vals.forEach(function (val, i) {
            var inp = document.createElement('input');
            inp.type = 'text';
            inp.className = 'form-input';
            inp.placeholder = placeholders[i];
            inp.value = val;
            inp.addEventListener('input', sgSync);
            row.appendChild(inp);
        });
        var rm = document.createElement('button');
        rm.type = 'button';
        rm.className = 'cp-row-remove';
        rm.textContent = '×';
        rm.onclick = function () { row.remove(); sgSync(); };
        row.appendChild(rm);
        container.appendChild(row);
        sgSync();
    };

    /* Pre-populate from old() value */
    (function () {
        var raw = document.getElementById('size-guide-hidden').value.trim();
        if (raw) {
            raw.split('\n').forEach(function (line) {
                var p = line.split('|');
                sgAddRow(p[0], p[1], p[2], p[3]);
            });
        } else {
            sgAddRow();
        }
    }());

    /* ── Color Swatches: dynamic row builder ── */
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
        nameInp.type = 'text';
        nameInp.className = 'form-input sw-name';
        nameInp.placeholder = 'e.g. Midnight';
        nameInp.value = name || '';
        nameInp.addEventListener('input', swSync);

        var safeHex = (hex && /^#[0-9a-fA-F]{6}$/.test(hex)) ? hex : '#111111';
        var colorPick = document.createElement('input');
        colorPick.type = 'color';
        colorPick.className = 'cp-color-pick';
        colorPick.value = safeHex;

        var hexInp = document.createElement('input');
        hexInp.type = 'text';
        hexInp.className = 'form-input sw-hex';
        hexInp.placeholder = '#000000';
        hexInp.value = hex || '';
        hexInp.maxLength = 7;

        colorPick.addEventListener('input', function () { hexInp.value = colorPick.value; swSync(); });
        hexInp.addEventListener('input', function () {
            var v = hexInp.value.trim();
            if (/^#[0-9a-fA-F]{6}$/.test(v)) colorPick.value = v;
            swSync();
        });

        var rm = document.createElement('button');
        rm.type = 'button';
        rm.className = 'cp-row-remove';
        rm.textContent = '×';
        rm.onclick = function () { row.remove(); swSync(); };

        row.appendChild(nameInp);
        row.appendChild(colorPick);
        row.appendChild(hexInp);
        row.appendChild(rm);
        container.appendChild(row);
        swSync();
    };

    /* Pre-populate swatches from old() value */
    (function () {
        var raw = document.getElementById('swatches-hidden').value.trim();
        if (raw) {
            raw.split('\n').forEach(function (line) {
                var p = line.split('|');
                swAddRow(p[0], p[1]);
            });
        }
    }());

}());

// AJAX form submission
(function () {
    var form = document.getElementById('cp-form');
    if (!form) return;
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (typeof submitForm === 'function') {
            await submitForm(form, function (data) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = '/admin/products';
                }
            });
        }
    });
}());
</script>
</x-admin-layout>
