<x-admin-layout title="Shipping Zones" section="configure" active="zones">

<div class="info-banner" style="margin-bottom:1.5rem;">
    <span>ℹ️</span>
    <span>Shipping zones group countries/regions together. Assign shipping methods to zones with optional rate overrides for location-specific pricing.</span>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <p class="result-count">
        {{ $zones->count() }} zone{{ $zones->count() > 1 ? 's' : '' }}
        · {{ $zones->where('is_active', true)->count() }} active
    </p>
    <a href="/admin/shipping/zones/create" class="add-btn">+ Add Zone</a>
</div>

@if($zones->isEmpty())
    <div class="admin-empty">No shipping zones configured. Add a zone to enable region-based shipping rates.</div>
@else
    <div class="card" style="padding:0;overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Zone Name</th>
                    <th>Countries</th>
                    <th>Methods</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($zones as $zone)
                <tr>
                    <td style="font-weight:700;">{{ $zone->name }}</td>
                    <td style="color:var(--muted);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ implode(', ', array_slice($zone->countries ?? [], 0, 5)) }}{{ count($zone->countries ?? []) > 5 ? ' +' . (count($zone->countries) - 5) . ' more' : '' }}
                    </td>
                    <td>{{ $zone->methods_count }}</td>
                    <td>
                        <span class="badge {{ $zone->is_active ? 'badge-green' : 'badge-gray' }}">
                            {{ $zone->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            <a href="/admin/shipping/zones/{{ $zone->id }}/edit" class="act-btn">Edit</a>
                            <form method="POST" action="/admin/shipping/zones/{{ $zone->id }}/toggle" style="display:inline;">
                                @csrf
                                <button type="submit" class="act-btn {{ $zone->is_active ? 'green' : '' }}">
                                    {{ $zone->is_active ? '✓ Active' : '○ Inactive' }}
                                </button>
                            </form>
                            <form method="POST" action="/admin/shipping/zones/{{ $zone->id }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red"
                                        onclick="return confirm('Delete zone &quot;{{ $zone->name }}&quot;?')">Delete</button>
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
