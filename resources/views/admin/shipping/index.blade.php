<x-admin-layout title="Shipping Methods" section="configure" active="shipping">
<style>
/* Inline edit row toggle */
.edit-form { display:none; background:#f9fafb; border:1.5px solid var(--border); border-radius:0.5rem; padding:1rem; margin-top:0.75rem; }
.edit-form.open { display:flex; flex-wrap:wrap; gap:0.6rem; align-items:flex-end; }
</style>

{{-- Info --}}
<div class="info-banner" style="margin-bottom:1.5rem;">
    <span>ℹ️</span>
    <span>Active shipping methods are shown to customers at checkout. Set a price of <strong>0</strong> for free shipping. Inactive methods are hidden from customers but kept for reference.</span>
</div>

{{-- Add Form --}}
<div class="card" style="margin-bottom:2rem;">
    <p class="section-title">Add Shipping Method</p>
    <form method="POST" action="/admin/shipping">
        @csrf
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;">
            <div class="form-group" style="flex:1;min-width:180px;">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-input"
                       placeholder="e.g. Standard Shipping, Express" required
                       value="{{ old('name') }}">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group" style="width:140px;">
                <label class="form-label">Price ($) *</label>
                <input type="number" name="price" class="form-input"
                       placeholder="0.00" step="0.01" min="0" required
                       value="{{ old('price') }}">
                @error('price')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group" style="flex:1;min-width:160px;">
                <label class="form-label">Est. Delivery</label>
                <input type="text" name="estimated_delivery" class="form-input"
                       placeholder="e.g. 3–5 business days"
                       value="{{ old('estimated_delivery') }}">
            </div>
            <div class="form-group">
                <button type="submit" class="add-btn" style="height:40px;">+ Add Method</button>
            </div>
        </div>
    </form>
</div>

{{-- Method List --}}
@if($methods->isEmpty())
    <div class="admin-empty">No shipping methods configured. Add one above to offer shipping options at checkout.</div>
@else
    <p class="result-count" style="margin-bottom:0.75rem;">
        {{ $methods->count() }} method{{ $methods->count() > 1 ? 's' : '' }} configured
        · {{ $methods->where('is_active', true)->count() }} active
    </p>
    <div class="card" style="padding:0;overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Est. Delivery</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($methods as $method)
                <tr>
                    <td style="font-weight:700;">{{ $method->name }}</td>
                    <td>
                        @if($method->price == 0)
                            <span style="color:#16a34a;font-weight:700;">Free</span>
                        @else
                            <span style="font-weight:700;">${{ number_format($method->price, 2) }}</span>
                        @endif
                    </td>
                    <td style="color:var(--muted);">{{ $method->estimated_delivery ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $method->is_active ? 'badge-green' : 'badge-gray' }}">
                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                            <button type="button" class="act-btn" onclick="toggleEdit({{ $method->id }})">Edit</button>
                            <form method="POST" action="/admin/shipping/{{ $method->id }}/toggle" style="display:inline;">
                                @csrf
                                <button type="submit" class="act-btn {{ $method->is_active ? 'green' : '' }}">
                                    {{ $method->is_active ? '✓ Active' : '○ Inactive' }}
                                </button>
                            </form>
                            <form method="POST" action="/admin/shipping/{{ $method->id }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red"
                                        onclick="return confirm('Delete &quot;{{ $method->name }}&quot;?')">Delete</button>
                            </form>
                        </div>
                        {{-- Inline edit form --}}
                        <div class="edit-form" id="edit-{{ $method->id }}">
                            <form method="POST" action="/admin/shipping/{{ $method->id }}" style="display:contents;">
                                @csrf @method('PUT')
                                <div class="form-group" style="flex:1;min-width:160px;">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-input" required value="{{ $method->name }}">
                                </div>
                                <div class="form-group" style="width:130px;">
                                    <label class="form-label">Price ($)</label>
                                    <input type="number" name="price" class="form-input" step="0.01" min="0" required value="{{ $method->price }}">
                                </div>
                                <div class="form-group" style="flex:1;min-width:150px;">
                                    <label class="form-label">Est. Delivery</label>
                                    <input type="text" name="estimated_delivery" class="form-input" value="{{ $method->estimated_delivery }}">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="add-btn" style="height:40px;">Save</button>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="act-btn" style="height:40px;"
                                            onclick="toggleEdit({{ $method->id }})">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

<script>
function toggleEdit(id) {
    const el = document.getElementById('edit-' + id);
    el.classList.toggle('open');
}
</script>
</x-admin-layout>
