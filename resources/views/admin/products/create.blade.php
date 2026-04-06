<x-admin-layout title="Add Product" section="catalog" active="products">
<style>
.form-group.full { grid-column: 1 / -1; }
.form-group.split { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:0.85rem; }
.form-note { font-size:0.78rem; color:var(--muted); margin-top:0.35rem; line-height:1.5; }
.form-toggle { display:flex; align-items:center; gap:0.65rem; min-height:46px; }
.form-toggle input { width:18px; height:18px; accent-color:var(--orange); }
@media (max-width: 720px) { .form-group.split { grid-template-columns:1fr; } }
</style>

<div class="card">
    <form method="POST" action="/admin/products" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-input"
                       placeholder="e.g. Voxura Pro Headset"
                       value="{{ old('name') }}" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Price ($)</label>
                <input type="number" name="price" class="form-input"
                       placeholder="999" min="0" step="0.01"
                       value="{{ old('price') }}" required>
                @error('price')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
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

            <div class="form-group">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-input"
                       placeholder="50" min="0"
                       value="{{ old('stock') }}" required>
                @error('stock')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full">
                <label class="form-label">Cover Image</label>
                <input type="file" name="image" class="form-input" accept="image/*">
                @error('image')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full">
                <label class="form-label">Gallery Images (optional, multiple)</label>
                <input type="file" name="gallery[]" class="form-input" accept="image/*" multiple>
                <p style="font-size:0.78rem;color:var(--muted);margin-top:0.25rem;">Hold Ctrl/Cmd to select multiple images</p>
                @error('gallery.*')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea" rows="4"
                          placeholder="Describe this product…" required>{{ old('description') }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Sale Badge</label>
                <input type="text" name="sale_badge" class="form-input"
                       placeholder="e.g. 20% OFF"
                       value="{{ old('sale_badge') }}">
                @error('sale_badge')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Delivery Estimate</label>
                <input type="text" name="delivery_estimate" class="form-input"
                       placeholder="e.g. Arrives in 2-4 business days"
                       value="{{ old('delivery_estimate', 'Arrives in 2-4 business days') }}">
                @error('delivery_estimate')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Max Order Quantity</label>
                <input type="number" name="max_order_quantity" class="form-input"
                       min="1" max="99" value="{{ old('max_order_quantity', 5) }}" required>
                @error('max_order_quantity')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Low Stock Alert Threshold</label>
                <input type="number" name="stock_alert_threshold" class="form-input"
                       min="1" max="99" value="{{ old('stock_alert_threshold', 6) }}" required>
                @error('stock_alert_threshold')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full">
                <label class="form-label">Product Flags</label>
                <label class="form-toggle form-input" style="justify-content:flex-start;cursor:pointer;">
                    <input type="checkbox" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}>
                    <span>Show a New badge on the product image</span>
                </label>
                @error('is_new')<p class="form-error">{{ $message }}</p>@enderror
                <label class="form-toggle form-input" style="justify-content:flex-start;cursor:pointer;margin-top:0.5rem;">
                    <input type="checkbox" name="has_colors" id="has_colors_toggle" value="1"
                           {{ old('has_colors') ? 'checked' : '' }}
                           onchange="document.getElementById('color_swatches_section').style.display = this.checked ? 'block' : 'none'">
                    <span>Enable color picker on the product page</span>
                </label>
                @error('has_colors')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group split full">
                <div>
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-input"
                           placeholder="e.g. VX-TSH-001"
                           value="{{ old('sku') }}">
                    @error('sku')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Material</label>
                    <input type="text" name="material" class="form-input"
                           placeholder="e.g. 100% Cotton"
                           value="{{ old('material') }}">
                    @error('material')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="form-group split full">
                <div>
                    <label class="form-label">Fit</label>
                    <input type="text" name="fit" class="form-input"
                           placeholder="e.g. Relaxed fit"
                           value="{{ old('fit') }}">
                    @error('fit')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Care Instructions</label>
                    <textarea name="care_instructions" class="form-textarea" rows="3" placeholder="Machine wash cold. Tumble dry low.">{{ old('care_instructions') }}</textarea>
                    @error('care_instructions')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="form-group full">
                <label class="form-label">Shipping & Returns</label>
                <textarea name="shipping_returns" class="form-textarea" rows="4" placeholder="Add shipping windows, free shipping threshold, and return policy details.">{{ old('shipping_returns') }}</textarea>
                @error('shipping_returns')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full" id="color_swatches_section" style="{{ old('has_colors') ? 'display:block' : 'display:none' }}">
                <label class="form-label">Color Swatches</label>
                <textarea name="color_swatches_rows" class="form-textarea" rows="5" placeholder="Midnight|#111827&#10;Clay|#c2410c&#10;Sand|#d6b58a">{{ old('color_swatches_rows') }}</textarea>
                <p class="form-note">One swatch per line in the format <strong>Name|#HEX</strong>. These render as the clickable color picker on the product page.</p>
                @error('color_swatches_rows')<p class="form-error">{{ $message }}</p>@enderror
                @error('color_swatches')<p class="form-error">{{ $message }}</p>@enderror
                @error('color_swatches.*.name')<p class="form-error">{{ $message }}</p>@enderror
                @error('color_swatches.*.hex')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full">
                <label class="form-label">Size Guide</label>
                <textarea name="size_guide_rows" class="form-textarea" rows="5" placeholder="XS|34-36|28-30|25&#10;S|36-38|30-32|26&#10;M|38-40|32-34|27">{{ old('size_guide_rows') }}</textarea>
                <p class="form-note">One size per line in the format <strong>Size|Chest|Waist|Length</strong>. Use the variant manager after saving to add the actual Size options (XS to XXL) and mark stock per size.</p>
                @error('size_guide_rows')<p class="form-error">{{ $message }}</p>@enderror
                @error('size_guide')<p class="form-error">{{ $message }}</p>@enderror
                @error('size_guide.*.size')<p class="form-error">{{ $message }}</p>@enderror
            </div>

        </div>

        <div style="display:flex;gap:0.75rem;margin-top:1.75rem;flex-wrap:wrap;">
            <button type="submit" class="add-btn">💾 Save Product</button>
            <a href="/admin" class="act-btn" style="text-decoration:none;">✕ Cancel</a>
        </div>

    </form>
</div>
</x-admin-layout>
