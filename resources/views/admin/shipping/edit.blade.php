<x-admin-layout title="Edit Shipping Method" section="configure" active="shipping">

<div style="margin-bottom:1rem;">
    <a href="/admin/shipping/methods" style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">&larr; Back to Shipping Methods</a>
</div>

<div class="card" x-data="{ type: '{{ old('type', $method->type) }}' }">
    <p class="section-title">Edit: {{ $method->name }}</p>
    <form method="POST" action="/admin/shipping/methods/{{ $method->id }}">
        @csrf @method('PUT')

        <div class="form-grid" style="margin-bottom:1rem;">
            <div class="form-group">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name', $method->name) }}">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Name (English)</label>
                <input type="text" name="name_en" class="form-input" value="{{ old('name_en', $method->name_translations['en'] ?? '') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Name (Arabic)</label>
                <input type="text" name="name_ar" class="form-input" value="{{ old('name_ar', $method->name_translations['ar'] ?? '') }}" dir="rtl">
            </div>

            <div class="form-group">
                <label class="form-label">Type *</label>
                <select name="type" class="form-select" x-model="type">
                    <option value="flat">Flat Rate</option>
                    <option value="per_unit">Per Unit</option>
                    <option value="weight_based">Weight Based</option>
                    <option value="free">Free</option>
                    <option value="custom">Custom (API)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Base Rate ($) *</label>
                <input type="number" name="base_rate" class="form-input" step="0.01" min="0" value="{{ old('base_rate', $method->base_rate) }}" required>
                @error('base_rate')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group" x-show="type === 'per_unit'" x-cloak>
                <label class="form-label">Per Unit Rate ($)</label>
                <input type="number" name="per_unit_rate" class="form-input" step="0.01" min="0" value="{{ old('per_unit_rate', $method->per_unit_rate) }}">
            </div>

            <div class="form-group" x-show="type === 'weight_based'" x-cloak>
                <label class="form-label">Weight Rate ($)</label>
                <input type="number" name="weight_rate" class="form-input" step="0.0001" min="0" value="{{ old('weight_rate', $method->weight_rate) }}">
            </div>

            <div class="form-group" x-show="type === 'weight_based'" x-cloak>
                <label class="form-label">Weight Unit</label>
                <select name="weight_unit" class="form-select">
                    <option value="kg" {{ old('weight_unit', $method->weight_unit) === 'kg' ? 'selected' : '' }}>kg</option>
                    <option value="lb" {{ old('weight_unit', $method->weight_unit) === 'lb' ? 'selected' : '' }}>lb</option>
                </select>
            </div>

            <div class="form-group" x-show="type !== 'free'" x-cloak>
                <label class="form-label">Free Above ($)</label>
                <input type="number" name="free_above" class="form-input" step="0.01" min="0" value="{{ old('free_above', $method->free_above) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Min Order ($)</label>
                <input type="number" name="min_order_amount" class="form-input" step="0.01" min="0" value="{{ old('min_order_amount', $method->min_order_amount) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Max Order ($)</label>
                <input type="number" name="max_order_amount" class="form-input" step="0.01" min="0" value="{{ old('max_order_amount', $method->max_order_amount) }}">
            </div>

            <div class="form-group" x-show="type === 'weight_based'" x-cloak>
                <label class="form-label">Max Weight</label>
                <input type="number" name="max_weight" class="form-input" step="0.01" min="0" value="{{ old('max_weight', $method->max_weight) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Channel</label>
                <select name="channel" class="form-select">
                    <option value="">All Channels</option>
                    <option value="web" {{ old('channel', $method->channel) === 'web' ? 'selected' : '' }}>Web</option>
                    <option value="mobile" {{ old('channel', $method->channel) === 'mobile' ? 'selected' : '' }}>Mobile</option>
                    <option value="api" {{ old('channel', $method->channel) === 'api' ? 'selected' : '' }}>API</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Est. Days Min</label>
                <input type="number" name="estimated_days_min" class="form-input" min="0" value="{{ old('estimated_days_min', $method->estimated_days_min) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Est. Days Max</label>
                <input type="number" name="estimated_days_max" class="form-input" min="0" value="{{ old('estimated_days_max', $method->estimated_days_max) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-input" min="0" value="{{ old('sort_order', $method->sort_order) }}">
            </div>
        </div>

        <div class="form-group" x-show="type === 'custom'" x-cloak style="margin-bottom:1rem;">
            <label class="form-label">Metadata (JSON)</label>
            <textarea name="metadata" class="form-textarea" rows="4">{{ old('metadata', $method->metadata ? json_encode($method->metadata, JSON_PRETTY_PRINT) : '') }}</textarea>
            @error('metadata')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="add-btn">Save Changes</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
<style>[x-cloak]{display:none!important}</style>
</x-admin-layout>
