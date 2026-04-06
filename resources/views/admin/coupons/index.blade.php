<x-admin-layout title="Coupons" section="marketing" active="promotions">

    {{-- Create Form --}}
    <div class="card" style="margin-bottom:2rem;">
        <p class="section-title">Create New Coupon</p>
        <form method="POST" action="{{ route('admin.coupons.store') }}">
            @csrf
            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-input" placeholder="SUMMER20"
                           value="{{ old('code') }}" required style="text-transform:uppercase;">
                    @error('code')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed"      {{ old('type') === 'fixed'      ? 'selected' : '' }}>Fixed Amount ($)</option>
                    </select>
                    @error('type')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Value</label>
                    <input type="number" name="value" class="form-input" placeholder="20"
                           min="0.01" step="0.01" value="{{ old('value') }}" required>
                    @error('value')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Min. Order ($)</label>
                    <input type="number" name="min_order_amount" class="form-input" placeholder="0"
                           min="0" step="0.01" value="{{ old('min_order_amount', 0) }}">
                    @error('min_order_amount')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Max Uses</label>
                    <input type="number" name="max_uses" class="form-input" placeholder="Unlimited"
                           min="1" value="{{ old('max_uses') }}">
                    @error('max_uses')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Expires At</label>
                    <input type="date" name="expires_at" class="form-input"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           value="{{ old('expires_at') }}">
                    @error('expires_at')<p class="form-error">{{ $message }}</p>@enderror
                </div>

            </div>
            <div style="margin-top:1.25rem;">
                <button type="submit" class="add-btn">+ Create Coupon</button>
            </div>
        </form>
    </div>

    {{-- Coupons List --}}
    @if($coupons->isEmpty())
        <div class="admin-empty">No coupons yet. Create one above.</div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type / Value</th>
                        <th>Min Order</th>
                        <th>Used</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $coupon)
                    @php
                        $expired = $coupon->expires_at && $coupon->expires_at->isPast();
                        $maxed   = $coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses;
                    @endphp
                    <tr style="{{ !$coupon->is_active ? 'opacity:0.6;' : '' }}">
                        <td style="font-weight:700;letter-spacing:0.06em;">{{ $coupon->code }}</td>
                        <td>
                            <span class="badge {{ $coupon->type === 'percentage' ? 'badge-blue' : 'badge-green' }}">
                                {{ $coupon->type === 'percentage' ? $coupon->value . '% off' : '₪' . number_format($coupon->value, 2) . ' off' }}
                            </span>
                        </td>
                        <td>
                            @if($coupon->min_order_amount > 0)
                                ₪{{ number_format($coupon->min_order_amount) }}
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>
                        <td>{{ $coupon->used_count }}{{ $coupon->max_uses ? ' / ' . $coupon->max_uses : '' }}</td>
                        <td style="white-space:nowrap;">
                            @if($coupon->expires_at)
                                {{ $coupon->expires_at->format('M d, Y') }}
                            @else
                                <span style="color:var(--muted);">Never</span>
                            @endif
                        </td>
                        <td>
                            @if($expired)
                                <span class="badge badge-red">Expired</span>
                            @elseif($maxed)
                                <span class="badge badge-red">Limit reached</span>
                            @elseif($coupon->is_active)
                                <span class="badge badge-green">Active</span>
                            @else
                                <span class="badge badge-gray">Inactive</span>
                            @endif
                        </td>
                        <td style="white-space:nowrap;">
                            <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="act-btn">
                                    {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}"
                                  style="display:inline;"
                                  onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
