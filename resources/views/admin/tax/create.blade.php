<x-admin-layout title="{{ __('admin.add_tax_rate_title') }}" section="configure" active="tax">

<div style="margin-bottom:1rem;">
    <a href="/admin/tax" style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">{{ __('admin.back_to_tax') }}</a>
</div>

<div class="card">
    <p class="section-title">{{ __('admin.add_tax_rate_title') }}</p>
    <form method="POST" action="/admin/tax">
        @csrf
        @include('admin.tax._form', ['rate' => null])
        <button type="submit" class="add-btn">{{ __('admin.create_tax_btn') }}</button>
    </form>
</div>

</x-admin-layout>
