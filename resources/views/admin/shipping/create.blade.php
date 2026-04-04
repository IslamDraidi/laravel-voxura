<x-admin-layout title="Add Shipping Method" section="configure" active="shipping">

<div style="margin-bottom:1rem;">
    <a href="/admin/shipping/methods" style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">&larr; Back to Shipping Methods</a>
</div>

<div class="card" x-data="{ type: '{{ old('type', 'flat') }}' }">
    <p class="section-title">Add Shipping Method</p>
    <form method="POST" action="/admin/shipping/methods">
        @csrf

        <div class="form-grid" style="margin-bottom:1rem;">
            {{-- Name --}}
            <div class="form-group">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name') }}" placeholder="e.g. Standard Shipping">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Name EN --}}
            <div class="form-group">
                <label class="form-label">Name (English)</label>
                <input type="text" name="name_en" class="form-input" value="{{ old('name_en') }}" placeholder="English label">
            </div>

            {{-- Name AR --}}
            <div class="form-group">
                <label class="form-label">Name (Arabic)</label>
                <input type="text" name="name_ar" class="form-input" value="{{ old('name_ar') }}" placeholder="Arabic label" dir="rtl">
            </div>

            {{-- Type --}}
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

            {{-- Base Rate --}}
            <div class="form-group">
                <label class="form-label">Base Rate ($) *</label>
                <input type="number" name="base_rate" class="form-input" step="0.01" min="0" value="{{ old('base_rate', '0') }}" required>
                @error('base_rate')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Per Unit Rate --}}
            <div class="form-group" x-show="type === 'per_unit'" x-cloak>
                <label class="form-label">Per Unit Rate ($)</label>
                <input type="number" name="per_unit_rate" class="form-input" step="0.01" min="0" value="{{ old('per_unit_rate') }}" placeholder="Per item charge">
            </div>

            {{-- Weight Rate --}}
            <div class="form-group" x-show="type === 'weight_based'" x-cloak>
                <label class="form-label">Weight Rate ($)</label>
                <input type="number" name="weight_rate" class="form-input" step="0.0001" min="0" value="{{ old('weight_rate') }}" placeholder="Per kg/lb charge">
            </div>

            {{-- Weight Unit --}}
            <div class="form-group" x-show="type === 'weight_based'" x-cloak>
                <label class="form-label">Weight Unit</label>
                <select name="weight_unit" class="form-select">
                    <option value="kg" {{ old('weight_unit') === 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                    <option value="lb" {{ old('weight_unit') === 'lb' ? 'selected' : '' }}>Pounds (lb)</option>
                </select>
            </div>

            {{-- Free Above --}}
            <div class="form-group" x-show="type !== 'free'" x-cloak>
                <label class="form-label">Free Above ($)</label>
                <input type="number" name="free_above" class="form-input" step="0.01" min="0" value="{{ old('free_above') }}" placeholder="Free if order exceeds">
            </div>

            {{-- Min Order --}}
            <div class="form-group">
                <label class="form-label">Min Order Amount ($)</label>
                <input type="number" name="min_order_amount" class="form-input" step="0.01" min="0" value="{{ old('min_order_amount') }}">
            </div>

            {{-- Max Order --}}
            <div class="form-group">
                <label class="form-label">Max Order Amount ($)</label>
                <input type="number" name="max_order_amount" class="form-input" step="0.01" min="0" value="{{ old('max_order_amount') }}">
            </div>

            {{-- Max Weight --}}
            <div class="form-group" x-show="type === 'weight_based'" x-cloak>
                <label class="form-label">Max Weight</label>
                <input type="number" name="max_weight" class="form-input" step="0.01" min="0" value="{{ old('max_weight') }}">
            </div>

            {{-- Channel --}}
            <div class="form-group">
                <label class="form-label">Channel</label>
                <select name="channel" class="form-select">
                    <option value="">All Channels</option>
                    <option value="web" {{ old('channel') === 'web' ? 'selected' : '' }}>Web</option>
                    <option value="mobile" {{ old('channel') === 'mobile' ? 'selected' : '' }}>Mobile</option>
                    <option value="api" {{ old('channel') === 'api' ? 'selected' : '' }}>API</option>
                </select>
            </div>

            {{-- Days Min --}}
            <div class="form-group">
                <label class="form-label">Est. Delivery Min (days)</label>
                <input type="number" name="estimated_days_min" class="form-input" min="0" value="{{ old('estimated_days_min') }}">
            </div>

            {{-- Days Max --}}
            <div class="form-group">
                <label class="form-label">Est. Delivery Max (days)</label>
                <input type="number" name="estimated_days_max" class="form-input" min="0" value="{{ old('estimated_days_max') }}">
            </div>

            {{-- Sort Order --}}
            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-input" min="0" value="{{ old('sort_order', 0) }}">
            </div>
        </div>

        {{-- Metadata --}}
        <div class="form-group" x-show="type === 'custom'" x-cloak style="margin-bottom:1rem;">
            <label class="form-label">Metadata (JSON) — carrier config, API keys, etc.</label>
            <textarea name="metadata" class="form-textarea" rows="4" placeholder='{"carrier":"dhl","api_key":"..."}'>{{ old('metadata') }}</textarea>
            @error('metadata')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="add-btn">Create Shipping Method</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
<style>[x-cloak]{display:none!important}</style>
</x-admin-layout>
