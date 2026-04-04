<x-admin-layout title="Categories" section="catalog" active="categories">

    <div class="two-col">

        {{-- Add Form --}}
        <div class="card" style="position:sticky;top:20px;align-self:start;">
            <p class="section-title">Add Category</p>
            <form method="POST" action="/admin/categories">
                @csrf
                <div class="form-group">
                    <input type="text" name="name" class="form-input"
                           placeholder="e.g. Headphones"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="add-btn" style="margin-top:0.75rem;width:100%;">+ Add Category</button>
            </form>
        </div>

        {{-- Category List --}}
        <div class="card">
            @if($categories->isEmpty())
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
                        @foreach($categories as $category)
                        <tr id="row-{{ $category->id }}">
                            <td>
                                <div id="view-{{ $category->id }}"
                                     style="display:flex;align-items:center;gap:0.75rem;">
                                    <span style="font-weight:600;">{{ $category->name }}</span>
                                </div>
                                <form id="edit-form-{{ $category->id }}"
                                      method="POST" action="/admin/categories/{{ $category->id }}"
                                      style="display:none;gap:0.4rem;align-items:center;">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" class="form-input"
                                           value="{{ $category->name }}" required
                                           style="padding:0.35rem 0.6rem;font-size:0.85rem;">
                                    <button type="submit" class="act-btn"
                                            style="background:var(--orange);color:#fff;border-color:var(--orange);">Save</button>
                                    <button type="button" class="act-btn"
                                            onclick="toggleEdit({{ $category->id }}, false)">Cancel</button>
                                </form>
                            </td>
                            <td>{{ $category->products_count }}</td>
                            <td>
                                <div id="actions-{{ $category->id }}" style="display:flex;gap:0.4rem;">
                                    <button class="act-btn"
                                            onclick="toggleEdit({{ $category->id }}, true)">Edit</button>
                                    <form method="POST" action="/admin/categories/{{ $category->id }}" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn red"
                                                onclick="return confirm('Delete \"{{ $category->name }}\"?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
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
        if (show) document.querySelector('#edit-form-' + id + ' input').focus();
    }
    </script>

</x-admin-layout>
