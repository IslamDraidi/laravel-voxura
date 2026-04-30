<x-admin-layout title="Tax Rates" section="configure" active="tax">

<div class="info-banner" style="margin-bottom:1.5rem;">
    <span>ℹ️</span>
    <span>Tax rates support multiple types: <strong>percentage</strong>, <strong>fixed</strong>, and <strong>compound</strong> (applied after other taxes). Rates can be scoped to products, categories, orders, or shipping. Country and date-range filtering enable precise tax rules.</span>
</div>

@php $effectiveRate = $rates->where('is_active', true)->where('type','percentage')->sum('rate'); @endphp
<div class="stat-grid" style="margin-bottom:2rem;">
    <div class="stat-card">
        <span class="sc-label">Effective % Rate</span>
        <span class="sc-value" style="color:var(--orange);">{{ number_format($effectiveRate, $effectiveRate == floor($effectiveRate) ? 0 : 2) }}%</span>
        <span class="sc-sub">Sum of active percentage rates</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">Total Rates</span>
        <span class="sc-value">{{ $rates->count() }}</span>
        <span class="sc-sub">{{ $rates->where('is_active', true)->count() }} active</span>
    </div>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <p class="result-count">
        {{ $rates->count() }} rate{{ $rates->count() > 1 ? 's' : '' }} configured
    </p>
    <a href="/admin/tax/create" class="add-btn">+ Add Tax Rate</a>
</div>

@if($rates->isEmpty())
    <div class="admin-empty">No tax rates configured. Add one to start collecting tax at checkout.</div>
@else
    <div class="card" style="padding:0;overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Rate</th>
                    <th>Scope</th>
                    <th>Country</th>
                    <th>Valid</th>
                    <th>Status</th>
                    <th>Actions</th>
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
                            Always
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $rate->is_active ? 'badge-green' : 'badge-gray' }}">
                            {{ $rate->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            <button type="button" class="act-btn" onclick="adminNavigate('/admin/tax/{{ $rate->id }}/edit')">Edit</button>
                            <form method="POST" action="/admin/tax/{{ $rate->id }}/toggle" style="display:inline;">
                                @csrf
                                <button type="submit" class="act-btn {{ $rate->is_active ? 'green' : '' }}">
                                    {{ $rate->is_active ? '✓ Active' : '○ Inactive' }}
                                </button>
                            </form>
                            <form method="POST" action="/admin/tax/{{ $rate->id }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red"
                                        onclick="return confirm('Delete &quot;{{ $rate->name }}&quot;?')">Delete</button>
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
