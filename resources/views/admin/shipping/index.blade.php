<x-admin-layout title="{{ __('admin.shipping_title') }}" section="configure" active="shipping">

{{-- Info --}}
<div class="info-banner" style="margin-bottom:1.5rem;">
    <span>ℹ️</span>
    <span>{{ __('admin.shipping_info_banner') }}</span>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <p class="result-count">
        {{ __('admin.methods_configured', ['count' => $methods->count(), 'active' => $methods->where('is_active', true)->count()]) }}
    </p>
    <a href="/admin/shipping/methods/create" class="add-btn">{{ __('admin.add_method_btn') }}</a>
</div>

@if($methods->isEmpty())
    <div class="admin-empty">{{ __('admin.no_shipping_methods') }}</div>
@else
    <div class="card" style="padding:0;overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.method_name_col') }}</th>
                    <th>{{ __('admin.method_type_col') }}</th>
                    <th>{{ __('admin.base_rate_col') }}</th>
                    <th>{{ __('admin.channel_col') }}</th>
                    <th>{{ __('admin.zones_col') }}</th>
                    <th>{{ __('admin.status_col') }}</th>
                    <th>{{ __('admin.actions_col') }}</th>
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
                    <td style="color:var(--muted);">{{ $method->channel ?? __('admin.all_channels_val') }}</td>
                    <td>{{ $method->zones_count }}</td>
                    <td>
                        <span class="badge {{ $method->is_active ? 'badge-green' : 'badge-gray' }}">
                            {{ $method->is_active ? __('admin.active_badge') : __('admin.inactive_badge') }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            <button type="button" class="act-btn" onclick="adminNavigate('/admin/shipping/methods/{{ $method->id }}/edit')">{{ __('admin.edit_btn') }}</button>
                            <form method="POST" action="/admin/shipping/methods/{{ $method->id }}/toggle" style="display:inline;">
                                @csrf
                                <button type="submit" class="act-btn {{ $method->is_active ? 'green' : '' }}">
                                    {{ $method->is_active ? __('admin.toggle_active_btn') : __('admin.toggle_inactive_btn') }}
                                </button>
                            </form>
                            <form method="POST" action="/admin/shipping/methods/{{ $method->id }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red"
                                        onclick="return confirm('Delete &quot;{{ $method->name }}&quot;?')">{{ __('admin.delete_btn') }}</button>
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
