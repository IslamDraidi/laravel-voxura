<x-admin-layout title="{{ __('admin.add_shipping_title') }}" section="configure" active="shipping">

<div style="margin-bottom:1rem;">
    <a href="/admin/shipping/methods" style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">&larr; {{ __('admin.back_to_shipping') }}</a>
</div>

<div class="card" x-data="{ type: '{{ old('type', 'flat') }}' }">
    <p class="section-title">{{ __('admin.add_shipping_title') }}</p>
    <form method="POST" action="/admin/shipping/methods">
        @csrf

        <div class="form-grid" style="margin-bottom:1rem;">
            {{-- Name --}}
            <div class="form-group">
                <label class="form-label">{{ __('admin.name_req_label') }}</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name') }}" placeholder="e.g. Standard Shipping">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Name EN --}}
            <div class="form-group">
                <label class="form-label">{{ __('admin.name_en_label') }}</label>
                <input type="text" name="name_en" class="form-input" value="{{ old('name_en') }}" placeholder="English label">
            </div>

            {{-- Name AR --}}
            <div class="form-group">
                <label class="form-label">{{ __('admin.name_ar_label') }}</label>
                <input type="text" name="name_ar" class="form-input" value="{{ old('name_ar') }}" placeholder="Arabic label" dir="rtl">
            </div>

            {{-- Type --}}
            <div class="form-group">
                <label class="form-label">{{ __('admin.type_req_label') }}</label>
                <select name="type" class="form-select" x-model="type">
                    <option value="flat">{{ __('admin.type_flat') }}</option>
                    <option value="per_unit">{{ __('admin.type_per_unit') }}</option>
                    <option value="weight_based">{{ __('admin.type_weight') }}</option>
                    <option value="free">{{ __('admin.type_free') }}</option>
                    <option value="custom">{{ __('admin.type_custom') }}</option>
                </select>
            </div>

            {{-- Base Rate --}}
            <div class="form-group">
                <label class="form-label">{{ __('admin.base_rate_req') }}</label>
                <input type="number" name="base_rate" class="form-input" step="0.01" min="0" value="{{ old('base_rate', '0') }}" required>
                @error('base_rate')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Per Unit Rate --}}
            <div class="form-group" x-show="type === 'per_unit'" x-cloak>
                <label class="form-label">{{ __('admin.per_unit_rate_label') }}</label>
                <input type="number" name="per_unit_rate" class="form-input" step="0.01" min="0" value="{{ old('per_unit_rate') }}" placeholder="Per item charge">
            </div>

            {{-- Weight Rate --}}
            <div class="form-group" x-show="type === 'weight_based'" x-cloak>
                <label class="form-label">{{ __('admin.weight_rate_label') }}</label>
                <input type="number" name="weight_rate" class="form-input" step="0.0001" min="0" value="{{ old('weight_rate') }}" placeholder="Per kg/lb charge">
            </div>

            {{-- Weight Unit --}}
            <div class="form-group" x-show="type === 'weight_based'" x-cloak>
                <label class="form-label">{{ __('admin.weight_unit_label') }}</label>
                <select name="weight_unit" class="form-select">
                    <option value="kg" {{ old('weight_unit') === 'kg' ? 'selected' : '' }}>{{ __('admin.kg_label') }}</option>
                    <option value="lb" {{ old('weight_unit') === 'lb' ? 'selected' : '' }}>{{ __('admin.lb_label') }}</option>
                </select>
            </div>

            {{-- Free Above --}}
            <div class="form-group" x-show="type !== 'free'" x-cloak>
                <label class="form-label">{{ __('admin.free_above_label') }}</label>
                <input type="number" name="free_above" class="form-input" step="0.01" min="0" value="{{ old('free_above') }}" placeholder="Free if order exceeds">
            </div>

            {{-- Min Order --}}
            <div class="form-group">
                <label class="form-label">{{ __('admin.min_order_label') }}</label>
                <input type="number" name="min_order_amount" class="form-input" step="0.01" min="0" value="{{ old('min_order_amount') }}">
            </div>

            {{-- Max Order --}}
            <div class="form-group">
                <label class="form-label">{{ __('admin.max_order_label') }}</label>
                <input type="number" name="max_order_amount" class="form-input" step="0.01" min="0" value="{{ old('max_order_amount') }}">
            </div>

            {{-- Max Weight --}}
            <div class="form-group" x-show="type === 'weight_based'" x-cloak>
                <label class="form-label">{{ __('admin.max_weight_label') }}</label>
                <input type="number" name="max_weight" class="form-input" step="0.01" min="0" value="{{ old('max_weight') }}">
            </div>

            {{-- Channel --}}
            <div class="form-group">
                <label class="form-label">{{ __('admin.channel_label') }}</label>
                <select name="channel" class="form-select">
                    <option value="">{{ __('admin.all_channels') }}</option>
                    <option value="web" {{ old('channel') === 'web' ? 'selected' : '' }}>{{ __('admin.channel_web') }}</option>
                    <option value="mobile" {{ old('channel') === 'mobile' ? 'selected' : '' }}>{{ __('admin.channel_mobile') }}</option>
                    <option value="api" {{ old('channel') === 'api' ? 'selected' : '' }}>{{ __('admin.channel_api') }}</option>
                </select>
            </div>

            {{-- Days Min --}}
            <div class="form-group">
                <label class="form-label">{{ __('admin.est_days_min_label') }}</label>
                <input type="number" name="estimated_days_min" class="form-input" min="0" value="{{ old('estimated_days_min') }}">
            </div>

            {{-- Days Max --}}
            <div class="form-group">
                <label class="form-label">{{ __('admin.est_days_max_label') }}</label>
                <input type="number" name="estimated_days_max" class="form-input" min="0" value="{{ old('estimated_days_max') }}">
            </div>

            {{-- Sort Order --}}
            <div class="form-group">
                <label class="form-label">{{ __('admin.sort_order_label') }}</label>
                <input type="number" name="sort_order" class="form-input" min="0" value="{{ old('sort_order', 0) }}">
            </div>
        </div>

        {{-- Metadata --}}
        <div class="form-group" x-show="type === 'custom'" x-cloak style="margin-bottom:1rem;">
            <label class="form-label">{{ __('admin.metadata_label') }}</label>
            <textarea name="metadata" class="form-textarea" rows="4" placeholder='{"carrier":"dhl","api_key":"..."}'>{{ old('metadata') }}</textarea>
            @error('metadata')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="add-btn">{{ __('admin.create_shipping_btn') }}</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
<style>[x-cloak]{display:none!important}</style>
</x-admin-layout>
