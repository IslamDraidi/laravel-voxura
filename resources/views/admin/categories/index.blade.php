<x-admin-layout title="Categories" section="catalog" active="categories">

    <div class="two-col">

        {{-- Add Form --}}
        <div class="card" style="position:sticky;top:20px;align-self:start;">
            <p class="section-title">Add Category</p>
            <form id="cat-add-form" method="POST" action="/admin/categories">
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
                <div class="admin-empty" id="cat-empty">No categories yet — add one!</div>
            @endif
            <table id="cat-table" style="{{ $parents->isEmpty() ? 'display:none' : '' }}">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="cat-tbody">
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
                        <td id="count-{{ $parent->id }}">{{ $parent->products_count }}</td>
                        <td>
                            <div id="actions-{{ $parent->id }}" style="display:flex;gap:0.4rem;">
                                <button class="act-btn" onclick="toggleEdit({{ $parent->id }}, true)">Edit</button>
                                <button type="button" class="act-btn red"
                                        onclick="deleteCategory({{ $parent->id }}, '{{ addslashes($parent->name) }}')">Delete</button>
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
                        <td id="count-{{ $child->id }}">{{ $child->products_count }}</td>
                        <td>
                            <div id="actions-{{ $child->id }}" style="display:flex;gap:0.4rem;">
                                <button class="act-btn" onclick="toggleEdit({{ $child->id }}, true)">Edit</button>
                                <button type="button" class="act-btn red"
                                        onclick="deleteCategory({{ $child->id }}, '{{ addslashes($child->name) }}')">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script>
    function toggleEdit(id, show) {
        document.getElementById('view-' + id).style.display      = show ? 'none' : 'flex';
        document.getElementById('edit-form-' + id).style.display = show ? 'flex' : 'none';
        document.getElementById('actions-' + id).style.display   = show ? 'none' : 'flex';
        if (show) document.querySelector('#edit-form-' + id + ' input[name="name"]').focus();
    }

    // AJAX: Add category
    (function () {
        var form = document.getElementById('cat-add-form');
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (typeof submitForm !== 'function') return;
            await submitForm(form, function (data) {
                var cat = data.category;
                // Show table if hidden
                var table = document.getElementById('cat-table');
                var empty = document.getElementById('cat-empty');
                if (empty) empty.style.display = 'none';
                if (table) table.style.display = '';
                // Add row to table
                var tbody = document.getElementById('cat-tbody');
                var row   = document.createElement('tr');
                row.id = 'row-' + cat.id;
                row.style.background = '#fafafa';
                row.innerHTML = '<td>' +
                    '<div id="view-' + cat.id + '" style="display:flex;align-items:center;gap:0.75rem;">' +
                        '<span style="font-weight:700;">' + escHtml(cat.name) + '</span>' +
                        (cat.parent_id ? '' : '<span class="badge badge-orange" style="font-size:0.65rem;">Parent</span>') +
                    '</div>' +
                    '<form id="edit-form-' + cat.id + '" method="POST" action="/admin/categories/' + cat.id + '" style="display:none;gap:0.4rem;align-items:center;flex-wrap:wrap;">' +
                        '<input type="hidden" name="_token" value="' + (document.querySelector('meta[name=csrf-token]')?.content || '') + '">' +
                        '<input type="hidden" name="_method" value="PUT">' +
                        '<input type="text" name="name" class="form-input" value="' + escAttr(cat.name) + '" required style="padding:0.35rem 0.6rem;font-size:0.85rem;">' +
                        '<input type="hidden" name="parent_id" value="">' +
                        '<button type="submit" class="act-btn" style="background:var(--orange);color:#fff;border-color:var(--orange);">Save</button>' +
                        '<button type="button" class="act-btn" onclick="toggleEdit(' + cat.id + ', false)">Cancel</button>' +
                    '</form>' +
                '</td>' +
                '<td id="count-' + cat.id + '">0</td>' +
                '<td>' +
                    '<div id="actions-' + cat.id + '" style="display:flex;gap:0.4rem;">' +
                        '<button class="act-btn" onclick="toggleEdit(' + cat.id + ', true)">Edit</button>' +
                        '<button type="button" class="act-btn red" onclick="deleteCategory(' + cat.id + ', \'' + escJs(cat.name) + '\')">Delete</button>' +
                    '</div>' +
                '</td>';
                tbody.appendChild(row);
                // Attach submit listener to new edit form
                attachEditForm(cat.id);
                // Reset add form
                form.reset();
            });
        });
    }());

    // AJAX: Edit category inline forms (existing rows)
    document.querySelectorAll('[id^="edit-form-"]').forEach(function (form) {
        var id = form.id.replace('edit-form-', '');
        attachEditForm(id);
    });

    function attachEditForm(id) {
        var form = document.getElementById('edit-form-' + id);
        if (!form || form._ajaxAttached) return;
        form._ajaxAttached = true;
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (typeof submitForm !== 'function') return;
            await submitForm(form, function (data) {
                // Update displayed name
                var viewEl = document.getElementById('view-' + id);
                if (viewEl) {
                    var nameSpan = viewEl.querySelector('span:first-child');
                    if (nameSpan) nameSpan.textContent = data.name || data.message;
                }
                // Update form input value
                var nameInput = form.querySelector('input[name="name"]');
                if (nameInput) nameInput.defaultValue = nameInput.value;
                toggleEdit(id, false);
            });
        });
    }

    // AJAX: Delete category
    window.deleteCategory = function (id, name) {
        if (typeof confirmAction !== 'function') return;
        confirmAction('Delete category?', 'Delete "' + name + '"? This cannot be undone.', async function () {
            try {
                var fd = new FormData();
                fd.append('_method', 'DELETE');
                fd.append('_token', document.querySelector('meta[name=csrf-token]')?.content || '');
                var res = await fetch('/admin/categories/' + id, {
                    method: 'POST', body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                var data = await res.json().catch(function () { return {}; });
                if (res.ok) {
                    var row = document.getElementById('row-' + id);
                    if (row) { row.style.transition = 'opacity 0.3s'; row.style.opacity = '0'; setTimeout(function () { if (row.parentNode) row.remove(); }, 330); }
                    if (typeof showToast === 'function') showToast(data.message || 'Deleted.', 'success');
                } else {
                    if (typeof showToast === 'function') showToast(data.message || 'Could not delete.', 'error');
                }
            } catch (e) {
                if (typeof showToast === 'function') showToast('Request failed.', 'error');
            }
        });
    };

    function escHtml(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
    function escAttr(s) { return String(s).replace(/"/g, '&quot;'); }
    function escJs(s) { return String(s).replace(/\\/g,'\\\\').replace(/'/g,"\\'"); }
    </script>

</x-admin-layout>
