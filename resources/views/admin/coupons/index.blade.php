<x-admin-layout title="{{ __('admin.coupons_title') }}" section="marketing" active="promotions">

    {{-- Create Form --}}
    <div class="card" style="margin-bottom:2rem;">
        <p class="section-title">{{ __('admin.create_coupon') }}</p>
        <form id="coupon-add-form" method="POST" action="{{ route('admin.coupons.store') }}">
            @csrf
            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">{{ __('admin.code_label') }}</label>
                    <input type="text" name="code" class="form-input" placeholder="SUMMER20"
                           value="{{ old('code') }}" required style="text-transform:uppercase;">
                    @error('code')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('admin.type_label') }}</label>
                    <select name="type" class="form-select" required>
                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>{{ __('admin.type_percentage') }}</option>
                        <option value="fixed"      {{ old('type') === 'fixed'      ? 'selected' : '' }}>{{ __('admin.type_fixed') }}</option>
                    </select>
                    @error('type')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('admin.value_label') }}</label>
                    <input type="number" name="value" class="form-input" placeholder="20"
                           min="0.01" step="0.01" value="{{ old('value') }}" required>
                    @error('value')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('admin.min_order_label') }}</label>
                    <input type="number" name="min_order_amount" class="form-input" placeholder="0"
                           min="0" step="0.01" value="{{ old('min_order_amount', 0) }}">
                    @error('min_order_amount')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('admin.max_uses_label') }}</label>
                    <input type="number" name="max_uses" class="form-input" placeholder="{{ __('admin.unlimited_ph') }}"
                           min="1" value="{{ old('max_uses') }}">
                    @error('max_uses')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('admin.expires_at_label') }}</label>
                    <input type="date" name="expires_at" class="form-input"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           value="{{ old('expires_at') }}">
                    @error('expires_at')<p class="form-error">{{ $message }}</p>@enderror
                </div>

            </div>
            <div style="margin-top:1.25rem;">
                <button type="submit" class="add-btn">{{ __('admin.create_coupon_btn') }}</button>
            </div>
        </form>
    </div>

    {{-- Coupons List --}}
    <div class="card" id="coupons-card" style="{{ $coupons->isEmpty() ? 'display:none' : '' }}">
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.code_col') }}</th>
                    <th>{{ __('admin.type_value_col') }}</th>
                    <th>{{ __('admin.min_order_col') }}</th>
                    <th>{{ __('admin.used_col') }}</th>
                    <th>{{ __('admin.expires_col') }}</th>
                    <th>{{ __('admin.status_col_h') }}</th>
                    <th>{{ __('admin.actions_col') }}</th>
                </tr>
            </thead>
            <tbody id="coupons-tbody">
                @foreach($coupons as $coupon)
                @php
                    $expired = $coupon->expires_at && $coupon->expires_at->isPast();
                    $maxed   = $coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses;
                @endphp
                <tr id="coupon-row-{{ $coupon->id }}" style="{{ !$coupon->is_active ? 'opacity:0.6;' : '' }}">
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
                            <span style="color:var(--muted);">{{ __('admin.never_expires') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($expired)
                            <span class="badge badge-red" id="coupon-status-{{ $coupon->id }}">{{ __('admin.coupon_expired') }}</span>
                        @elseif($maxed)
                            <span class="badge badge-red" id="coupon-status-{{ $coupon->id }}">{{ __('admin.coupon_limit_reached') }}</span>
                        @elseif($coupon->is_active)
                            <span class="badge badge-green" id="coupon-status-{{ $coupon->id }}">{{ __('admin.coupon_active') }}</span>
                        @else
                            <span class="badge badge-gray" id="coupon-status-{{ $coupon->id }}">{{ __('admin.coupon_inactive') }}</span>
                        @endif
                    </td>
                    <td style="white-space:nowrap;">
                        <button type="button" class="act-btn" id="coupon-toggle-{{ $coupon->id }}"
                                onclick="toggleCoupon({{ $coupon->id }}, '{{ $coupon->code }}', {{ $coupon->is_active ? 'true' : 'false' }})">
                            {{ $coupon->is_active ? __('admin.deactivate_btn') : __('admin.activate_btn') }}
                        </button>
                        <button type="button" class="act-btn red"
                                onclick="deleteCoupon({{ $coupon->id }}, '{{ $coupon->code }}')">{{ __('admin.delete_btn') }}</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($coupons->isEmpty())
        <div class="admin-empty" id="coupons-empty">{{ __('admin.no_coupons') }}</div>
    @endif

    <script>
    // AJAX: Create coupon
    (function () {
        var form = document.getElementById('coupon-add-form');
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (typeof submitForm !== 'function') return;
            await submitForm(form, function (data) {
                var coupon = data.coupon;
                // Show table
                var card  = document.getElementById('coupons-card');
                var empty = document.getElementById('coupons-empty');
                if (card)  card.style.display  = '';
                if (empty) empty.style.display = 'none';
                // Add row
                var tbody = document.getElementById('coupons-tbody');
                var row = document.createElement('tr');
                row.id = 'coupon-row-' + coupon.id;
                var badge = coupon.type === 'percentage' ? 'badge-blue' : 'badge-green';
                var valLabel = coupon.type === 'percentage' ? coupon.value + '% off' : '₪' + parseFloat(coupon.value).toFixed(2) + ' off';
                row.innerHTML = '<td style="font-weight:700;letter-spacing:0.06em;">' + escHtml(coupon.code) + '</td>' +
                    '<td><span class="badge ' + badge + '">' + escHtml(valLabel) + '</span></td>' +
                    '<td>' + (parseFloat(coupon.min_order_amount) > 0 ? '₪' + parseFloat(coupon.min_order_amount).toFixed(0) : '<span style="color:var(--muted);">—</span>') + '</td>' +
                    '<td>0' + (coupon.max_uses ? ' / ' + coupon.max_uses : '') + '</td>' +
                    '<td style="white-space:nowrap;">' + (coupon.expires_at ? coupon.expires_at : '<span style="color:var(--muted);">{{ __('admin.never_expires') }}</span>') + '</td>' +
                    '<td><span class="badge badge-green" id="coupon-status-' + coupon.id + '">{{ __('admin.coupon_active') }}</span></td>' +
                    '<td style="white-space:nowrap;">' +
                        '<button type="button" class="act-btn" id="coupon-toggle-' + coupon.id + '" onclick="toggleCoupon(' + coupon.id + ', \'' + escJs(coupon.code) + '\', true)">{{ __('admin.deactivate_btn') }}</button>' +
                        '<button type="button" class="act-btn red" onclick="deleteCoupon(' + coupon.id + ', \'' + escJs(coupon.code) + '\')">{{ __('admin.delete_btn') }}</button>' +
                    '</td>';
                tbody.appendChild(row);
                form.reset();
            });
        });
    }());

    // AJAX: Toggle coupon
    window.toggleCoupon = async function (id, code, isActive) {
        try {
            var fd = new FormData();
            fd.append('_token', document.querySelector('meta[name=csrf-token]')?.content || '');
            var res = await fetch('/admin/coupons/' + id + '/toggle', {
                method: 'POST', body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            var data = await res.json().catch(function () { return {}; });
            if (res.ok) {
                var newActive = data.is_active;
                var row = document.getElementById('coupon-row-' + id);
                var statusEl = document.getElementById('coupon-status-' + id);
                var toggleBtn = document.getElementById('coupon-toggle-' + id);
                if (row) row.style.opacity = newActive ? '1' : '0.6';
                if (statusEl) {
                    statusEl.className = 'badge ' + (newActive ? 'badge-green' : 'badge-gray');
                    statusEl.textContent = newActive ? '{{ __('admin.coupon_active') }}' : '{{ __('admin.coupon_inactive') }}';
                }
                if (toggleBtn) {
                    toggleBtn.textContent = newActive ? '{{ __('admin.deactivate_btn') }}' : '{{ __('admin.activate_btn') }}';
                    toggleBtn.setAttribute('onclick', 'toggleCoupon(' + id + ', \'' + escJs(code) + '\', ' + newActive + ')');
                }
                if (typeof showToast === 'function') showToast(data.message || 'Updated.', 'success');
            }
        } catch (e) {
            if (typeof showToast === 'function') showToast('Request failed.', 'error');
        }
    };

    // AJAX: Delete coupon
    window.deleteCoupon = function (id, code) {
        if (typeof confirmAction !== 'function') return;
        confirmAction('Delete coupon?', 'Delete coupon "' + code + '"? This cannot be undone.', async function () {
            try {
                var fd = new FormData();
                fd.append('_method', 'DELETE');
                fd.append('_token', document.querySelector('meta[name=csrf-token]')?.content || '');
                var res = await fetch('/admin/coupons/' + id, {
                    method: 'POST', body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                var data = await res.json().catch(function () { return {}; });
                if (res.ok) {
                    var row = document.getElementById('coupon-row-' + id);
                    if (row) { row.style.transition = 'opacity 0.3s'; row.style.opacity = '0'; setTimeout(function () { if (row.parentNode) row.remove(); }, 330); }
                    if (typeof showToast === 'function') showToast(data.message || 'Deleted.', 'success');
                } else {
                    if (typeof showToast === 'function') showToast(data.message || 'Delete failed.', 'error');
                }
            } catch (e) {
                if (typeof showToast === 'function') showToast('Request failed.', 'error');
            }
        });
    };

    function escHtml(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
    function escAttr(s) { return String(s).replace(/"/g, '&quot;'); }
    function escJs(s) { return String(s).replace(/\\/g,'\\\\').replace(/'/g,"\\'"); }
    </script>

</x-admin-layout>
