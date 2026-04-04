<x-admin-layout title="Edit Tax Rate" section="configure" active="tax">

<div style="margin-bottom:1rem;">
    <a href="/admin/tax" style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">&larr; Back to Tax Rates</a>
</div>

<div class="card">
    <p class="section-title">Edit: {{ $rate->name }}</p>
    <form method="POST" action="/admin/tax/{{ $rate->id }}">
        @csrf @method('PUT')
        @include('admin.tax._form')
        <button type="submit" class="add-btn">Save Changes</button>
    </form>
</div>

</x-admin-layout>
