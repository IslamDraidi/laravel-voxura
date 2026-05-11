<x-admin-layout title="{{ __('admin.edit_shipping_title') }}" section="configure" active="shipping">

<div style="margin-bottom:1rem;">
    <a href="/admin/shipping/methods" onclick="event.preventDefault();adminNavigate('/admin/shipping/methods')"
       style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">&larr; {{ __('admin.back_to_shipping') }}</a>
</div>

<div class="card">
    <p class="section-title">{{ __('admin.edit_prefix') }} {{ $method->name }}</p>
    <form id="shipping-edit-form" method="POST" action="/admin/shipping/methods/{{ $method->id }}">
        @csrf @method('PUT')

        <div class="form-grid" style="margin-bottom:1rem;">
            <div class="form-group">
                <label class="form-label">{{ __('admin.name_req_label') }}</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name', $method->name) }}">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.name_en_label') }}</label>
                <input type="text" name="name_en" class="form-input" value="{{ old('name_en', $method->name_translations['en'] ?? '') }}">
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.name_ar_label') }}</label>
                <input type="text" name="name_ar" class="form-input" value="{{ old('name_ar', $method->name_translations['ar'] ?? '') }}" dir="rtl">
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.type_req_label') }}</label>
                <select name="type" id="shipping-type-select" class="form-select" onchange="shippingTypeChange(this.value)">
                    <option value="flat"         {{ old('type', $method->type) === 'flat'         ? 'selected' : '' }}>{{ __('admin.type_flat') }}</option>
                    <option value="per_unit"     {{ old('type', $method->type) === 'per_unit'     ? 'selected' : '' }}>{{ __('admin.type_per_unit') }}</option>
                    <option value="weight_based" {{ old('type', $method->type) === 'weight_based' ? 'selected' : '' }}>{{ __('admin.type_weight') }}</option>
                    <option value="free"         {{ old('type', $method->type) === 'free'         ? 'selected' : '' }}>{{ __('admin.type_free') }}</option>
                    <option value="custom"       {{ old('type', $method->type) === 'custom'       ? 'selected' : '' }}>{{ __('admin.type_custom') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.base_rate_req') }}</label>
                <input type="number" name="base_rate" class="form-input" step="0.01" min="0" value="{{ old('base_rate', $method->base_rate) }}" required>
                @error('base_rate')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group" id="field-per-unit-rate">
                <label class="form-label">{{ __('admin.per_unit_rate_label') }}</label>
                <input type="number" name="per_unit_rate" class="form-input" step="0.01" min="0" value="{{ old('per_unit_rate', $method->per_unit_rate) }}">
            </div>

            <div class="form-group" id="field-weight-rate">
                <label class="form-label">{{ __('admin.weight_rate_label') }}</label>
                <input type="number" name="weight_rate" class="form-input" step="0.0001" min="0" value="{{ old('weight_rate', $method->weight_rate) }}">
            </div>

            <div class="form-group" id="field-weight-unit">
                <label class="form-label">{{ __('admin.weight_unit_label') }}</label>
                <select name="weight_unit" class="form-select">
                    <option value="kg" {{ old('weight_unit', $method->weight_unit) === 'kg' ? 'selected' : '' }}>kg</option>
                    <option value="lb" {{ old('weight_unit', $method->weight_unit) === 'lb' ? 'selected' : '' }}>lb</option>
                </select>
            </div>

            <div class="form-group" id="field-free-above">
                <label class="form-label">{{ __('admin.free_above_label') }}</label>
                <input type="number" name="free_above" class="form-input" step="0.01" min="0" value="{{ old('free_above', $method->free_above) }}">
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.min_order_label') }}</label>
                <input type="number" name="min_order_amount" class="form-input" step="0.01" min="0" value="{{ old('min_order_amount', $method->min_order_amount) }}">
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.max_order_label') }}</label>
                <input type="number" name="max_order_amount" class="form-input" step="0.01" min="0" value="{{ old('max_order_amount', $method->max_order_amount) }}">
            </div>

            <div class="form-group" id="field-max-weight">
                <label class="form-label">{{ __('admin.max_weight_label') }}</label>
                <input type="number" name="max_weight" class="form-input" step="0.01" min="0" value="{{ old('max_weight', $method->max_weight) }}">
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.channel_label') }}</label>
                <select name="channel" class="form-select">
                    <option value="">{{ __('admin.all_channels') }}</option>
                    <option value="web"    {{ old('channel', $method->channel) === 'web'    ? 'selected' : '' }}>{{ __('admin.channel_web') }}</option>
                    <option value="mobile" {{ old('channel', $method->channel) === 'mobile' ? 'selected' : '' }}>{{ __('admin.channel_mobile') }}</option>
                    <option value="api"    {{ old('channel', $method->channel) === 'api'    ? 'selected' : '' }}>{{ __('admin.channel_api') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.est_days_min_label') }}</label>
                <input type="number" name="estimated_days_min" class="form-input" min="0" value="{{ old('estimated_days_min', $method->estimated_days_min) }}">
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.est_days_max_label') }}</label>
                <input type="number" name="estimated_days_max" class="form-input" min="0" value="{{ old('estimated_days_max', $method->estimated_days_max) }}">
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.sort_order_label') }}</label>
                <input type="number" name="sort_order" class="form-input" min="0" value="{{ old('sort_order', $method->sort_order) }}">
            </div>
        </div>

        <div class="form-group" id="field-metadata" style="margin-bottom:1rem;">
            <label class="form-label">{{ __('admin.metadata_label') }}</label>
            <textarea name="metadata" class="form-textarea" rows="4">{{ old('metadata', $method->metadata ? json_encode($method->metadata, JSON_PRETTY_PRINT) : '') }}</textarea>
            @error('metadata')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="add-btn">{{ __('admin.save_shipping_btn') }}</button>
    </form>
</div>

<script>
function shippingTypeChange(type) {
    document.getElementById('field-per-unit-rate').style.display = type === 'per_unit'     ? '' : 'none';
    document.getElementById('field-weight-rate').style.display   = type === 'weight_based' ? '' : 'none';
    document.getElementById('field-weight-unit').style.display   = type === 'weight_based' ? '' : 'none';
    document.getElementById('field-max-weight').style.display    = type === 'weight_based' ? '' : 'none';
    document.getElementById('field-free-above').style.display    = type === 'free'         ? 'none' : '';
    document.getElementById('field-metadata').style.display      = type === 'custom'       ? '' : 'none';
}
(function () {
    var sel = document.getElementById('shipping-type-select');
    if (sel) shippingTypeChange(sel.value);

    var form = document.getElementById('shipping-edit-form');
    if (!form) return;
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (typeof submitForm === 'function') {
            await submitForm(form, function () {
                if (typeof adminNavigate === 'function') adminNavigate('/admin/shipping/methods');
                else window.location.href = '/admin/shipping/methods';
            });
        }
    });
}());
</script>
</x-admin-layout>
