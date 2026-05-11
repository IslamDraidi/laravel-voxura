<x-admin-layout title="{{ __('admin.edit_tax_title') }}" section="configure" active="tax">

<div style="margin-bottom:1rem;">
    <a href="/admin/tax" onclick="event.preventDefault();adminNavigate('/admin/tax')"
       style="color:var(--orange);font-size:13px;text-decoration:none;font-weight:600;">{{ __('admin.back_to_tax') }}</a>
</div>

<div class="card">
    <p class="section-title">{{ __('admin.edit_prefix') }} {{ $rate->name }}</p>
    <form id="tax-edit-form" method="POST" action="/admin/tax/{{ $rate->id }}">
        @csrf @method('PUT')
        @include('admin.tax._form')
        <button type="submit" class="add-btn">{{ __('admin.save_changes') }}</button>
    </form>
</div>

<script>
(function () {
    var form = document.getElementById('tax-edit-form');
    if (!form) return;
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (typeof submitForm === 'function') {
            await submitForm(form, function () {
                if (typeof adminNavigate === 'function') adminNavigate('/admin/tax');
                else window.location.href = '/admin/tax';
            });
        }
    });
}());
</script>
</x-admin-layout>
