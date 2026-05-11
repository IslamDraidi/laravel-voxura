<x-admin-layout title="{{ __('admin.add_zone_title') }}" section="configure" active="zones">

<div style="margin-bottom:1rem;">
    <a href="/admin/shipping/zones" style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">&larr; {{ __('admin.back_to_zones') }}</a>
</div>

<div class="card">
    <p class="section-title">{{ __('admin.add_zone_title') }}</p>
    <form method="POST" action="/admin/shipping/zones">
        @csrf
        @include('admin.shipping.zones._form', ['zone' => null])
        <button type="submit" class="add-btn">{{ __('admin.create_zone_btn') }}</button>
    </form>
</div>

</x-admin-layout>
