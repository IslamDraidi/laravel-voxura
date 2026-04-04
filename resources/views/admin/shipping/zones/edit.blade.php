<x-admin-layout title="Edit Shipping Zone" section="configure" active="zones">

<div style="margin-bottom:1rem;">
    <a href="/admin/shipping/zones" style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">&larr; Back to Zones</a>
</div>

<div class="card">
    <p class="section-title">Edit: {{ $zone->name }}</p>
    <form method="POST" action="/admin/shipping/zones/{{ $zone->id }}">
        @csrf @method('PUT')
        @include('admin.shipping.zones._form')
        <button type="submit" class="add-btn">Save Changes</button>
    </form>
</div>

</x-admin-layout>
