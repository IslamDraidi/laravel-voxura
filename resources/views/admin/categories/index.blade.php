<x-admin-layout title="Categories" section="catalog" active="categories">

    <div class="two-col">

        {{-- Add Form --}}
        <div class="card" style="position:sticky;top:20px;align-self:start;">
            <p class="section-title">Add Category</p>
            <form method="POST" action="/admin/categories">
                @csrf
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-input"
                           placeholder="e.g. Formal Wear"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group" style="margin-top:0.75rem;">
                    <label class="form-label">Parent Category <span style="color:var(--muted);font-weight:400;">(optional)</span></label>
                    <select name="parent_id" class="form-input">
                        <option value="">— Top-level category —</option>
                        @foreach($parentOptions as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="add-btn" style="margin-top:0.75rem;width:100%;">+ Add Category</button>
            </form>
        </div>

        {{-- Category List --}}
        <div class="card">
            @if($parents->isEmpty())
                <div class="admin-empty">No categories yet — add one!</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parents as $parent)
                        {{-- Parent row --}}
                        <tr id="row-{{ $parent->id }}" style="background:#fafafa;">
                            <td>
                                <div id="view-{{ $parent->id }}" style="display:flex;align-items:center;gap:0.75rem;">
                                    <span style="font-weight:700;color:var(--text);">{{ $parent->name }}</span>
                                    <span class="badge badge-orange" style="font-size:0.65rem;">Parent</span>
                                </div>
                                <form id="edit-form-{{ $parent->id }}"
                                      method="POST" action="/admin/categories/{{ $parent->id }}"
                                      style="display:none;gap:0.4rem;align-items:center;flex-wrap:wrap;">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" class="form-input"
                                           value="{{ $parent->name }}" required
                                           style="padding:0.35rem 0.6rem;font-size:0.85rem;">
                                    <input type="hidden" name="parent_id" value="">
                                    <button type="submit" class="act-btn"
                                            style="background:var(--orange);color:#fff;border-color:var(--orange);">Save</button>
                                    <button type="button" class="act-btn"
                                            onclick="toggleEdit({{ $parent->id }}, false)">Cancel</button>
                                </form>
                            </td>
                            <td>{{ $parent->products_count }}</td>
                            <td>
                                <div id="actions-{{ $parent->id }}" style="display:flex;gap:0.4rem;">
                                    <button class="act-btn" onclick="toggleEdit({{ $parent->id }}, true)">Edit</button>
                                    <form method="POST" action="/admin/categories/{{ $parent->id }}" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn red"
                                                onclick="return confirm('Delete \"{{ $parent->name }}\"?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Child rows --}}
                        @foreach($parent->children as $child)
                        <tr id="row-{{ $child->id }}">
                            <td>
                                <div id="view-{{ $child->id }}" style="display:flex;align-items:center;gap:0.5rem;padding-left:1.5rem;">
                                    <span style="color:var(--muted);font-size:0.85rem;">└</span>
                                    <span style="font-weight:600;">{{ $child->name }}</span>
                                </div>
                                <form id="edit-form-{{ $child->id }}"
                                      method="POST" action="/admin/categories/{{ $child->id }}"
                                      style="display:none;gap:0.4rem;align-items:center;flex-wrap:wrap;padding-left:1.5rem;">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" class="form-input"
                                           value="{{ $child->name }}" required
                                           style="padding:0.35rem 0.6rem;font-size:0.85rem;">
                                    <select name="parent_id" class="form-input" style="padding:0.35rem 0.6rem;font-size:0.85rem;width:auto;">
                                        <option value="">— Top-level —</option>
                                        @foreach($parentOptions as $opt)
                                            <option value="{{ $opt->id }}" {{ $child->parent_id == $opt->id ? 'selected' : '' }}>
                                                {{ $opt->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="act-btn"
                                            style="background:var(--orange);color:#fff;border-color:var(--orange);">Save</button>
                                    <button type="button" class="act-btn"
                                            onclick="toggleEdit({{ $child->id }}, false)">Cancel</button>
                                </form>
                            </td>
                            <td>{{ $child->products_count }}</td>
                            <td>
                                <div id="actions-{{ $child->id }}" style="display:flex;gap:0.4rem;">
                                    <button class="act-btn" onclick="toggleEdit({{ $child->id }}, true)">Edit</button>
                                    <form method="POST" action="/admin/categories/{{ $child->id }}" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn red"
                                                onclick="return confirm('Delete \"{{ $child->name }}\"?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>

    <script>
    function toggleEdit(id, show) {
        document.getElementById('view-' + id).style.display      = show ? 'none' : 'flex';
        document.getElementById('edit-form-' + id).style.display = show ? 'flex' : 'none';
        document.getElementById('actions-' + id).style.display   = show ? 'none' : 'flex';
        if (show) document.querySelector('#edit-form-' + id + ' input[name="name"]').focus();
    }
    </script>

</x-admin-layout>
