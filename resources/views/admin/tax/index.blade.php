<x-admin-layout title="Tax Rates" section="configure" active="tax">

{{-- Info --}}
<div class="info-banner" style="margin-bottom:1.5rem;">
    <span>ℹ️</span>
    <span>All <strong>active</strong> tax rates are summed and applied to orders at checkout (on subtotal after any coupon discount). Inactive rates are saved but not applied.</span>
</div>

{{-- Effective rate summary --}}
@php $effectiveRate = $rates->where('is_active', true)->sum('rate'); @endphp
<div class="stat-grid" style="margin-bottom:2rem;">
    <div class="stat-card">
        <span class="sc-label">Effective Tax Rate</span>
        <span class="sc-value" style="color:var(--orange);">{{ number_format($effectiveRate, $effectiveRate == floor($effectiveRate) ? 0 : 2) }}%</span>
        <span class="sc-sub">Sum of all active rates</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">Total Rates</span>
        <span class="sc-value">{{ $rates->count() }}</span>
        <span class="sc-sub">{{ $rates->where('is_active', true)->count() }} active</span>
    </div>
</div>

{{-- Add Tax Rate Form --}}
<div class="card" style="margin-bottom:2rem;">
    <p class="section-title">Add Tax Rate</p>
    <form method="POST" action="/admin/tax">
        @csrf
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;">
            <div class="form-group" style="flex:1;min-width:180px;">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-input"
                       placeholder="e.g. VAT, GST, Sales Tax" required
                       value="{{ old('name') }}">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group" style="width:160px;">
                <label class="form-label">Rate (%) *</label>
                <input type="number" name="rate" class="form-input"
                       placeholder="e.g. 10" step="0.01" min="0" max="100" required
                       value="{{ old('rate') }}">
                @error('rate')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <button type="submit" class="add-btn" style="height:40px;">+ Add Rate</button>
            </div>
        </div>
    </form>
</div>

{{-- Tax Rate List --}}
@if($rates->isEmpty())
    <div class="admin-empty">No tax rates configured. Add one above to start collecting tax at checkout.</div>
@else
    <p class="result-count" style="margin-bottom:0.75rem;">
        {{ $rates->count() }} rate{{ $rates->count() > 1 ? 's' : '' }} configured
        · {{ $rates->where('is_active', true)->count() }} active
    </p>
    <div class="card" style="padding:0;">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Rate</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rates as $rate)
                <tr>
                    <td style="font-weight:700;">{{ $rate->name }}</td>
                    <td style="font-size:1.1rem;font-weight:800;color:var(--orange);">{{ number_format($rate->rate, $rate->rate == floor($rate->rate) ? 0 : 2) }}%</td>
                    <td>
                        <span class="badge {{ $rate->is_active ? 'badge-green' : 'badge-gray' }}">
                            {{ $rate->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;">
                            <form method="POST" action="/admin/tax/{{ $rate->id }}/toggle" style="display:inline;">
                                @csrf
                                <button type="submit" class="act-btn {{ $rate->is_active ? 'green' : '' }}">
                                    {{ $rate->is_active ? '✓ Active' : '○ Inactive' }}
                                </button>
                            </form>
                            <form method="POST" action="/admin/tax/{{ $rate->id }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red"
                                        onclick="return confirm('Delete tax rate &quot;{{ $rate->name }}&quot;?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

</x-admin-layout>
