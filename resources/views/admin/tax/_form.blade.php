@php
    $r = $rate ?? null;
    $taxType  = old('type',  $r->type  ?? 'percentage');
    $taxScope = old('scope', $r->scope ?? 'product');
@endphp

<div class="form-grid" style="margin-bottom:1rem;">
    <div class="form-group">
        <label class="form-label">Name *</label>
        <input type="text" name="name" class="form-input" required value="{{ old('name', $r->name ?? '') }}" placeholder="e.g. VAT, GST">
        @error('name')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label class="form-label">Name (English)</label>
        <input type="text" name="name_en" class="form-input" value="{{ old('name_en', $r->name_translations['en'] ?? '') }}">
    </div>

    <div class="form-group">
        <label class="form-label">Name (Arabic)</label>
        <input type="text" name="name_ar" class="form-input" value="{{ old('name_ar', $r->name_translations['ar'] ?? '') }}" dir="rtl">
    </div>

    <div class="form-group">
        <label class="form-label">Type *</label>
        <select name="type" id="tax-type-select" class="form-select" onchange="taxFormChange()">
            <option value="percentage" {{ $taxType === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
            <option value="fixed"      {{ $taxType === 'fixed'      ? 'selected' : '' }}>Fixed Amount ($)</option>
            <option value="compound"   {{ $taxType === 'compound'   ? 'selected' : '' }}>Compound (%)</option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" id="tax-rate-label">{{ $taxType === 'fixed' ? 'Amount ($) *' : 'Rate (%) *' }}</label>
        <input type="number" name="rate" class="form-input" step="0.01" min="0" max="100" required
               value="{{ old('rate', $r->rate ?? '') }}" placeholder="e.g. 16">
        @error('rate')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label class="form-label">Scope *</label>
        <select name="scope" id="tax-scope-select" class="form-select" onchange="taxFormChange()">
            <option value="product"  {{ $taxScope === 'product'  ? 'selected' : '' }}>Product</option>
            <option value="category" {{ $taxScope === 'category' ? 'selected' : '' }}>Category</option>
            <option value="order"    {{ $taxScope === 'order'    ? 'selected' : '' }}>Order</option>
            <option value="shipping" {{ $taxScope === 'shipping' ? 'selected' : '' }}>Shipping</option>
        </select>
    </div>

    <div class="form-group" id="field-tax-category" style="{{ $taxScope !== 'category' ? 'display:none' : '' }}">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select">
            <option value="">— Select —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $r->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label class="form-label">Country (ISO 2-letter)</label>
        <input type="text" name="country" class="form-input" maxlength="2" value="{{ old('country', $r->country ?? '') }}" placeholder="e.g. JO, US" style="text-transform:uppercase;">
    </div>

    <div class="form-group">
        <label class="form-label">Region/State</label>
        <input type="text" name="region" class="form-input" value="{{ old('region', $r->region ?? '') }}" placeholder="e.g. CA, NY">
    </div>

    <div class="form-group">
        <label class="form-label">Postal Code Pattern</label>
        <input type="text" name="postal_code_pattern" class="form-input" value="{{ old('postal_code_pattern', $r->postal_code_pattern ?? '') }}" placeholder="Regex e.g. ^110\d{2}$">
    </div>

    <div class="form-group">
        <label class="form-label">Channel</label>
        <select name="channel" class="form-select">
            <option value="">All Channels</option>
            <option value="web"    {{ old('channel', $r->channel ?? '') === 'web'    ? 'selected' : '' }}>Web</option>
            <option value="mobile" {{ old('channel', $r->channel ?? '') === 'mobile' ? 'selected' : '' }}>Mobile</option>
            <option value="api"    {{ old('channel', $r->channel ?? '') === 'api'    ? 'selected' : '' }}>API</option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label">Priority <span style="font-weight:400;text-transform:none;">(for compound ordering)</span></label>
        <input type="number" name="priority" class="form-input" min="0" value="{{ old('priority', $r->priority ?? 0) }}">
    </div>

    <div class="form-group">
        <label class="form-label">Valid From</label>
        <input type="date" name="valid_from" class="form-input" value="{{ old('valid_from', $r && $r->valid_from ? $r->valid_from->format('Y-m-d') : '') }}">
    </div>

    <div class="form-group">
        <label class="form-label">Valid To</label>
        <input type="date" name="valid_to" class="form-input" value="{{ old('valid_to', $r && $r->valid_to ? $r->valid_to->format('Y-m-d') : '') }}">
        @error('valid_to')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Checkboxes --}}
<div style="display:flex;gap:2rem;flex-wrap:wrap;margin-bottom:1.5rem;">
    <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--dark);cursor:pointer;">
        <input type="hidden" name="is_inclusive" value="0">
        <input type="checkbox" name="is_inclusive" value="1"
               {{ old('is_inclusive', $r->is_inclusive ?? false) ? 'checked' : '' }}
               style="width:16px;height:16px;accent-color:var(--orange);">
        <span><strong>Inclusive</strong> — price already includes this tax</span>
    </label>

    <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--dark);cursor:pointer;">
        <input type="hidden" name="apply_to_shipping" value="0">
        <input type="checkbox" name="apply_to_shipping" value="1"
               {{ old('apply_to_shipping', $r->apply_to_shipping ?? false) ? 'checked' : '' }}
               style="width:16px;height:16px;accent-color:var(--orange);">
        <span><strong>Apply to Shipping</strong> — also tax the shipping amount</span>
    </label>
</div>

<script>
function taxFormChange() {
    var type  = (document.getElementById('tax-type-select')  || {}).value || '';
    var scope = (document.getElementById('tax-scope-select') || {}).value || '';
    var catField = document.getElementById('field-tax-category');
    var rateLabel = document.getElementById('tax-rate-label');
    if (catField)   catField.style.display  = scope === 'category' ? '' : 'none';
    if (rateLabel)  rateLabel.textContent   = type  === 'fixed'    ? 'Amount ($) *' : 'Rate (%) *';
}
</script>
