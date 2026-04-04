<x-admin-layout title="Add Shipping Zone" section="configure" active="zones">

<div style="margin-bottom:1rem;">
    <a href="/admin/shipping/zones" style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">&larr; Back to Zones</a>
</div>

<div class="card">
    <p class="section-title">Add Shipping Zone</p>
    <form method="POST" action="/admin/shipping/zones">
        @csrf
        @include('admin.shipping.zones._form', ['zone' => null])
        <button type="submit" class="add-btn">Create Zone</button>
    </form>
</div>

</x-admin-layout>
