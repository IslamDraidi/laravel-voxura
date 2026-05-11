<x-admin-layout title="{{ __('admin.edit_zone_title') }}" section="configure" active="zones">

<div style="margin-bottom:1rem;">
    <a href="/admin/shipping/zones" style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">&larr; {{ __('admin.back_to_zones') }}</a>
</div>

<div class="card">
    <p class="section-title">{{ __('admin.edit_prefix') }} {{ $zone->name }}</p>
    <form method="POST" action="/admin/shipping/zones/{{ $zone->id }}">
        @csrf @method('PUT')
        @include('admin.shipping.zones._form')
        <button type="submit" class="add-btn">{{ __('admin.save_changes') }}</button>
    </form>
</div>

</x-admin-layout>
