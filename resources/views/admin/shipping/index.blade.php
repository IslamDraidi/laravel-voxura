<x-admin-layout title="Shipping Methods" section="configure" active="shipping">

{{-- Info --}}
<div class="info-banner" style="margin-bottom:1.5rem;">
    <span>ℹ️</span>
    <span>Configure shipping methods with flexible rate types: flat, per-unit, weight-based, or free. Methods can be scoped to specific channels and zones. Set <strong>free_above</strong> for conditional free shipping.</span>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <p class="result-count">
        {{ $methods->count() }} method{{ $methods->count() > 1 ? 's' : '' }} configured
        · {{ $methods->where('is_active', true)->count() }} active
    </p>
    <a href="/admin/shipping/methods/create" class="add-btn">+ Add Method</a>
</div>

@if($methods->isEmpty())
    <div class="admin-empty">No shipping methods configured. Add one to offer shipping options at checkout.</div>
@else
    <div class="card" style="padding:0;overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Base Rate</th>
                    <th>Channel</th>
                    <th>Zones</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($methods as $method)
                <tr>
                    <td style="font-weight:700;">{{ $method->name }}</td>
                    <td>
                        <span class="badge badge-{{ match($method->type) {
                            'flat' => 'blue', 'per_unit' => 'amber', 'weight_based' => 'orange',
                            'free' => 'green', 'custom' => 'gray', default => 'gray'
                        } }}">{{ ucfirst(str_replace('_', ' ', $method->type)) }}</span>
                    </td>
                    <td style="font-weight:700;">{{ $method->formatted_rate }}</td>
                    <td style="color:var(--muted);">{{ $method->channel ?? 'All' }}</td>
                    <td>{{ $method->zones_count }}</td>
                    <td>
                        <span class="badge {{ $method->is_active ? 'badge-green' : 'badge-gray' }}">
                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            <a href="/admin/shipping/methods/{{ $method->id }}/edit" class="act-btn">Edit</a>
                            <form method="POST" action="/admin/shipping/methods/{{ $method->id }}/toggle" style="display:inline;">
                                @csrf
                                <button type="submit" class="act-btn {{ $method->is_active ? 'green' : '' }}">
                                    {{ $method->is_active ? '✓ Active' : '○ Inactive' }}
                                </button>
                            </form>
                            <form method="POST" action="/admin/shipping/methods/{{ $method->id }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red"
                                        onclick="return confirm('Delete &quot;{{ $method->name }}&quot;?')">Delete</button>
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
