<x-admin-layout title="{{ __('admin.zones_title') }}" section="configure" active="zones">

<div class="info-banner" style="margin-bottom:1.5rem;">
    <span>ℹ️</span>
    <span>{{ __('admin.zones_info') }}</span>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <p class="result-count">
        {{ __('admin.zones_configured', ['count' => $zones->count(), 'active' => $zones->where('is_active', true)->count()]) }}
    </p>
    <a href="/admin/shipping/zones/create" class="add-btn">{{ __('admin.add_zone_btn') }}</a>
</div>

@if($zones->isEmpty())
    <div class="admin-empty">{{ __('admin.no_zones') }}</div>
@else
    <div class="card" style="padding:0;overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.zone_name_col') }}</th>
                    <th>{{ __('admin.countries_col') }}</th>
                    <th>{{ __('admin.methods_col') }}</th>
                    <th>{{ __('admin.status_col') }}</th>
                    <th>{{ __('admin.actions_col') }}</th>
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
                            {{ $zone->is_active ? __('admin.active_badge') : __('admin.inactive_badge') }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            <a href="/admin/shipping/zones/{{ $zone->id }}/edit" class="act-btn">{{ __('admin.edit_btn') }}</a>
                            <form method="POST" action="/admin/shipping/zones/{{ $zone->id }}/toggle" style="display:inline;">
                                @csrf
                                <button type="submit" class="act-btn {{ $zone->is_active ? 'green' : '' }}">
                                    {{ $zone->is_active ? __('admin.toggle_active_btn') : __('admin.toggle_inactive_btn') }}
                                </button>
                            </form>
                            <form method="POST" action="/admin/shipping/zones/{{ $zone->id }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red"
                                        onclick="return confirm('{{ __('admin.delete_zone_confirm', ['name' => $zone->name]) }}')">{{ __('admin.delete_btn') }}</button>
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
