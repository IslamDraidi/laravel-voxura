<x-admin-layout title="{{ __('admin.tax_title') }}" section="configure" active="tax">

<div class="info-banner" style="margin-bottom:1.5rem;">
    <span>ℹ️</span>
    <span>{!! __('admin.tax_info') !!}</span>
</div>

@php $effectiveRate = $rates->where('is_active', true)->where('type','percentage')->sum('rate'); @endphp
<div class="stat-grid" style="margin-bottom:2rem;">
    <div class="stat-card">
        <span class="sc-label">{{ __('admin.effective_rate') }}</span>
        <span class="sc-value" style="color:var(--orange);">{{ number_format($effectiveRate, $effectiveRate == floor($effectiveRate) ? 0 : 2) }}%</span>
        <span class="sc-sub">{{ __('admin.sum_active_rates') }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">{{ __('admin.total_rates') }}</span>
        <span class="sc-value">{{ $rates->count() }}</span>
        <span class="sc-sub">{{ __('admin.active_rates', ['count' => $rates->where('is_active', true)->count()]) }}</span>
    </div>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <p class="result-count">
        {{ __('admin.rates_configured', ['count' => $rates->count()]) }}
    </p>
    <a href="/admin/tax/create" class="add-btn">{{ __('admin.add_tax_btn') }}</a>
</div>

@if($rates->isEmpty())
    <div class="admin-empty">{{ __('admin.no_tax_rates') }}</div>
@else
    <div class="card" style="padding:0;overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.name_col') }}</th>
                    <th>{{ __('admin.type_col') }}</th>
                    <th>{{ __('admin.rate_col') }}</th>
                    <th>{{ __('admin.scope_col') }}</th>
                    <th>{{ __('admin.country_col') }}</th>
                    <th>{{ __('admin.valid_col') }}</th>
                    <th>{{ __('admin.status_col') }}</th>
                    <th>{{ __('admin.actions_col') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rates as $rate)
                <tr>
                    <td style="font-weight:700;">{{ $rate->name }}</td>
                    <td>
                        <span class="badge badge-{{ match($rate->type) {
                            'percentage' => 'blue', 'fixed' => 'amber', 'compound' => 'orange', default => 'gray'
                        } }}">{{ ucfirst($rate->type) }}</span>
                    </td>
                    <td style="font-weight:800;color:var(--orange);">
                        @if($rate->type === 'fixed')
                            ₪{{ number_format($rate->rate, 2) }}
                        @else
                            {{ number_format($rate->rate, $rate->rate == floor($rate->rate) ? 0 : 2) }}%
                        @endif
                    </td>
                    <td><span class="badge badge-gray">{{ ucfirst($rate->scope) }}</span></td>
                    <td style="color:var(--muted);">{{ $rate->country ?? '—' }}</td>
                    <td style="color:var(--muted);font-size:12px;">
                        @if($rate->valid_from || $rate->valid_to)
                            {{ $rate->valid_from?->format('M d') ?? '...' }} — {{ $rate->valid_to?->format('M d') ?? '...' }}
                        @else
                            {{ __('admin.always_valid') }}
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $rate->is_active ? 'badge-green' : 'badge-gray' }}">
                            {{ $rate->is_active ? __('admin.active_badge') : __('admin.inactive_badge') }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            <button type="button" class="act-btn" onclick="adminNavigate('/admin/tax/{{ $rate->id }}/edit')">{{ __('admin.edit_btn') }}</button>
                            <form method="POST" action="/admin/tax/{{ $rate->id }}/toggle" style="display:inline;">
                                @csrf
                                <button type="submit" class="act-btn {{ $rate->is_active ? 'green' : '' }}">
                                    {{ $rate->is_active ? __('admin.toggle_active_btn') : __('admin.toggle_inactive_btn') }}
                                </button>
                            </form>
                            <form method="POST" action="/admin/tax/{{ $rate->id }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red"
                                        onclick="return confirm('Delete &quot;{{ $rate->name }}&quot;?')">{{ __('admin.delete_btn') }}</button>
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
