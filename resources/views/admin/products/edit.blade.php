<x-admin-layout title="Edit Product" section="catalog" active="products">
<style>
.form-group.full { grid-column: 1 / -1; }
.form-group.split { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:0.85rem; }
.form-note { font-size:0.78rem; color:var(--muted); margin-top:0.35rem; line-height:1.5; }
.form-toggle { display:flex; align-items:center; gap:0.65rem; min-height:46px; }
.form-toggle input { width:18px; height:18px; accent-color:var(--orange); }
/* Current cover image preview */
.current-image { display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem; }
.current-image img { width:64px; height:64px; object-fit:cover; border-radius:0.5rem; border:1.5px solid var(--border); }
.current-image span { font-size:0.78rem; color:var(--muted); }
/* Gallery */
.gallery-grid { display:flex; flex-wrap:wrap; gap:0.75rem; margin-bottom:0.75rem; }
.gallery-item { position:relative; width:80px; height:80px; }
.gallery-item img { width:100%; height:100%; object-fit:cover; border-radius:0.5rem; border:1.5px solid var(--border); }
.gallery-remove { position:absolute; top:-8px; right:-8px; width:22px; height:22px; background:#ef4444; color:#fff; border:none; border-radius:50%; font-size:0.7rem; font-weight:900; cursor:pointer; line-height:1; display:flex; align-items:center; justify-content:center; }
/* Variants */
.variants-section { margin-top:2rem; border-top:1.5px solid var(--border); padding-top:2rem; }
.variants-title { font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--muted); margin-bottom:1rem; }
.variants-table { width:100%; border-collapse:collapse; margin-bottom:1.25rem; }
.variants-table th { text-align:left; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--muted); padding:0.5rem 0.75rem; border-bottom:1.5px solid var(--border); }
.variants-table td { padding:0.6rem 0.75rem; font-size:0.88rem; color:var(--dark); border-bottom:1px solid var(--border); vertical-align:middle; }
.variants-table tr:last-child td { border-bottom:none; }
.variant-type-badge { display:inline-block; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; padding:0.15rem 0.55rem; border-radius:999px; background:#fff7ed; color:var(--orange); }
.btn-variant-delete { display:inline-flex; align-items:center; gap:0.25rem; padding:0.3rem 0.7rem; border-radius:0.4rem; font-size:0.78rem; font-weight:600; color:#ef4444; border:1.5px solid #fecaca; background:transparent; cursor:pointer; font-family:'DM Sans',sans-serif; transition:background 0.15s; }
.btn-variant-delete:hover { background:#fee2e2; }
.variant-add-grid { display:grid; grid-template-columns:1fr 1fr 120px 100px auto; gap:0.6rem; align-items:end; }
@media (max-width: 720px) { .variant-add-grid { grid-template-columns:1fr 1fr; } }
.variant-add-grid .form-input { margin:0; }
.btn-add-variant { display:inline-flex; align-items:center; gap:0.3rem; background:var(--orange); color:#fff; padding:0.7rem 1rem; border-radius:0.5rem; border:none; cursor:pointer; font-size:0.85rem; font-weight:700; font-family:'DM Sans',sans-serif; white-space:nowrap; transition:background 0.15s; }
.btn-add-variant:hover { background:#c2410c; }
@media (max-width: 720px) { .form-group.split { grid-template-columns:1fr; } }
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

<div class="card">
    <form method="POST" action="/admin/products/{{ $product->id }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <input type="hidden" name="redirect_to" value="{{ request()->query('from') }}">

        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">Product ID</label>
                <input type="text" class="form-input" value="#{{ $product->id }}" disabled style="opacity:0.6;cursor:not-allowed;">
                <p class="form-note">This ID is permanent and cannot be changed.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-input"
                       value="{{ old('name', $product->name) }}" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Price ($)</label>
                <input type="number" name="price" class="form-input" min="0" step="0.01"
                       value="{{ old('price', $product->price) }}" required>
                @error('price')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-input" min="0"
                       value="{{ old('stock', $product->stock) }}" required>
                @error('stock')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full">
                <label class="form-label">Cover Image</label>
                @if($product->image)
                    <div class="current-image">
                        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}">
                        <span>Current cover — upload a new one to replace it</span>
                    </div>
                @endif
                <input type="file" name="image" class="form-input" accept="image/*">
                @error('image')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Gallery --}}
            <div class="form-group full">
                <label class="form-label">Gallery Images</label>
                @if($product->images->isNotEmpty())
                    <div class="gallery-grid" id="galleryGrid">
                        @foreach($product->images as $img)
                        <div class="gallery-item" id="gi-{{ $img->id }}">
                            <img src="{{ asset('images/' . $img->image) }}" alt="Gallery">
                            <button type="button" class="gallery-remove"
                                    onclick="markRemove({{ $img->id }})" title="Remove">✕</button>
                            <input type="checkbox" name="remove_images[]" value="{{ $img->id }}"
                                   id="rm-{{ $img->id }}" style="display:none;">
                        </div>
                        @endforeach
                    </div>
                    <p style="font-size:0.78rem;color:var(--muted);margin-bottom:0.5rem;">Click ✕ to remove an image on save.</p>
                @endif
                <input type="file" name="gallery[]" class="form-input" accept="image/*" multiple>
                <p style="font-size:0.78rem;color:var(--muted);margin-top:0.25rem;">Add more images (Hold Ctrl/Cmd for multiple)</p>
                @error('gallery.*')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea" rows="4" required>{{ old('description', $product->description) }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Sale Badge</label>
                <input type="text" name="sale_badge" class="form-input"
                       value="{{ old('sale_badge', $product->sale_badge) }}"
                       placeholder="e.g. 20% OFF">
                @error('sale_badge')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Delivery Estimate</label>
                <input type="text" name="delivery_estimate" class="form-input"
                       value="{{ old('delivery_estimate', $product->delivery_estimate) }}"
                       placeholder="e.g. Arrives in 2-4 business days">
                @error('delivery_estimate')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Max Order Quantity</label>
                <input type="number" name="max_order_quantity" class="form-input" min="1" max="99"
                       value="{{ old('max_order_quantity', $product->max_order_quantity ?? 5) }}" required>
                @error('max_order_quantity')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Low Stock Alert Threshold</label>
                <input type="number" name="stock_alert_threshold" class="form-input" min="1" max="99"
                       value="{{ old('stock_alert_threshold', $product->stock_alert_threshold ?? 6) }}" required>
                @error('stock_alert_threshold')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full">
                <label class="form-label">Product Flags</label>
                <label class="form-toggle form-input" style="justify-content:flex-start;cursor:pointer;">
                    <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                    <span>Show a New badge on the product image</span>
                </label>
                @error('is_new')<p class="form-error">{{ $message }}</p>@enderror
                <label class="form-toggle form-input" style="justify-content:flex-start;cursor:pointer;margin-top:0.5rem;">
                    <input type="checkbox" name="has_colors" id="has_colors_toggle" value="1"
                           {{ old('has_colors', $product->has_colors) ? 'checked' : '' }}
                           onchange="document.getElementById('color_swatches_section').style.display = this.checked ? 'block' : 'none'">
                    <span>Enable color picker on the product page</span>
                </label>
                @error('has_colors')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group split full">
                <div>
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-input"
                           value="{{ old('sku', $product->sku) }}"
                           placeholder="e.g. VX-TSH-001">
                    @error('sku')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Material</label>
                    <input type="text" name="material" class="form-input"
                           value="{{ old('material', $product->material) }}"
                           placeholder="e.g. 100% Cotton">
                    @error('material')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="form-group split full">
                <div>
                    <label class="form-label">Fit</label>
                    <input type="text" name="fit" class="form-input"
                           value="{{ old('fit', $product->fit) }}"
                           placeholder="e.g. Relaxed fit">
                    @error('fit')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Care Instructions</label>
                    <textarea name="care_instructions" class="form-textarea" rows="3" placeholder="Machine wash cold. Tumble dry low.">{{ old('care_instructions', $product->care_instructions) }}</textarea>
                    @error('care_instructions')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="form-group full">
                <label class="form-label">Shipping & Returns</label>
                <textarea name="shipping_returns" class="form-textarea" rows="4" placeholder="Add shipping windows, free shipping threshold, and return policy details.">{{ old('shipping_returns', $product->shipping_returns) }}</textarea>
                @error('shipping_returns')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full" id="color_swatches_section"
                 style="{{ old('has_colors', $product->has_colors) ? 'display:block' : 'display:none' }}">
                <label class="form-label">Color Swatches</label>
                <textarea name="color_swatches_rows" class="form-textarea" rows="5" placeholder="Midnight|#111827&#10;Clay|#c2410c&#10;Sand|#d6b58a">{{ $colorSwatchesRows }}</textarea>
                <p class="form-note">One swatch per line in the format <strong>Name|#HEX</strong>. These render as the clickable color picker on the product page.</p>
                @error('color_swatches_rows')<p class="form-error">{{ $message }}</p>@enderror
                @error('color_swatches')<p class="form-error">{{ $message }}</p>@enderror
                @error('color_swatches.*.name')<p class="form-error">{{ $message }}</p>@enderror
                @error('color_swatches.*.hex')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full">
                <label class="form-label">Size Guide</label>
                <textarea name="size_guide_rows" class="form-textarea" rows="5" placeholder="XS|34-36|28-30|25&#10;S|36-38|30-32|26&#10;M|38-40|32-34|27">{{ $sizeGuideRows }}</textarea>
                <p class="form-note">One size per line in the format <strong>Size|Chest|Waist|Length</strong>. Use the variant manager below to add the actual Size options (XS to XXL) and set stock per size.</p>
                @error('size_guide_rows')<p class="form-error">{{ $message }}</p>@enderror
                @error('size_guide')<p class="form-error">{{ $message }}</p>@enderror
                @error('size_guide.*.size')<p class="form-error">{{ $message }}</p>@enderror
            </div>

        </div>

        <div style="display:flex;gap:0.75rem;margin-top:1.75rem;flex-wrap:wrap;">
            <button type="submit" class="add-btn">💾 Save Changes</button>
            <a href="{{ $backUrl }}" class="act-btn" style="text-decoration:none;">✕ Cancel</a>
        </div>

    </form>

    {{-- ── Variants Section (separate form, outside product update form) ── --}}
    <div class="variants-section">
        <p class="variants-title">Product Variants (Size, Color, etc.)</p>
        <p class="form-note" style="margin-bottom:1rem;">For the storefront size picker, add variants with the type <strong>Size</strong> and values like <strong>XS</strong>, <strong>S</strong>, <strong>M</strong>, <strong>L</strong>, <strong>XL</strong>, and <strong>XXL</strong>. Set stock to <strong>0</strong> to show an option as out of stock.</p>

        @if(session('success') && $product->variants->isNotEmpty())
        @endif

        {{-- Existing variants --}}
        @if($product->variants->isNotEmpty())
            <table class="variants-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Price Modifier</th>
                        <th>Stock</th>
                        <th></th>
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

        {{-- Add variant --}}
        <form method="POST" action="/admin/products/{{ $product->id }}/variants">
            @csrf
            <div class="variant-add-grid">
                <div>
                    <label class="form-label" style="margin-bottom:0.3rem;display:block;">Type</label>
                    <input type="text" name="type" class="form-input" placeholder="e.g. Size, Color"
                           list="variant-types" required>
                    <datalist id="variant-types">
                        <option value="Size">
                        <option value="Color">
                        <option value="Material">
                        <option value="Style">
                    </datalist>
                </div>
                <div>
                    <label class="form-label" style="margin-bottom:0.3rem;display:block;">Value</label>
                    <input type="text" name="value" class="form-input" placeholder="e.g. M, Red" required>
                </div>
                <div>
                    <label class="form-label" style="margin-bottom:0.3rem;display:block;">Price (+/-)</label>
                    <input type="number" name="price_modifier" class="form-input"
                           step="0.01" value="0" placeholder="0.00" required>
                </div>
                <div>
                    <label class="form-label" style="margin-bottom:0.3rem;display:block;">Stock</label>
                    <input type="number" name="stock" class="form-input"
                           min="0" placeholder="(optional)">
                </div>
                <div>
                    <label class="form-label" style="margin-bottom:0.3rem;display:block;">&nbsp;</label>
                    <button type="submit" class="btn-add-variant">+ Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function markRemove(id) {
    const cb = document.getElementById('rm-' + id);
    const item = document.getElementById('gi-' + id);
    if (cb) { cb.checked = true; }
    if (item) { item.style.opacity = '0.35'; item.querySelector('.gallery-remove').textContent = '↩'; }
}
</script>
</x-admin-layout>
