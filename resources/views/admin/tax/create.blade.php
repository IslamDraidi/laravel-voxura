<x-admin-layout title="Add Tax Rate" section="configure" active="tax">

<div style="margin-bottom:1rem;">
    <a href="/admin/tax" style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">&larr; Back to Tax Rates</a>
</div>

<div class="card">
    <p class="section-title">Add Tax Rate</p>
    <form method="POST" action="/admin/tax">
        @csrf
        @include('admin.tax._form', ['rate' => null])
        <button type="submit" class="add-btn">Create Tax Rate</button>
    </form>
</div>

</x-admin-layout>
