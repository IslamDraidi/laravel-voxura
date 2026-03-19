<x-layout title="Categories">
<style>
.admin-page { padding-top: 90px; padding-bottom: 4rem; }

.admin-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}

.admin-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--gray-900);
    letter-spacing: -0.03em;
}

.admin-title span { color: var(--orange); }

.btn-back {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1.25rem;
    border: 1.5px solid var(--gray-200);
    border-radius: 999px;
    text-decoration: none;
    color: var(--gray-600);
    font-size: 0.85rem;
    font-weight: 600;
    transition: color 0.15s, border-color 0.15s;
}

.btn-back:hover { color: var(--orange); border-color: var(--orange); }

.alert-success, .alert-error {
    padding: 0.85rem 1.25rem;
    border-radius: 0.5rem;
    margin-bottom: 1.25rem;
    font-weight: 600;
    font-size: 0.9rem;
}

.alert-success { background: #dcfce7; color: #16a34a; }
.alert-error   { background: #fee2e2; color: #ef4444; }

/* ── Layout: add form + list side by side ── */
.cat-layout {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 1.5rem;
    align-items: start;
}

@media (max-width: 760px) { .cat-layout { grid-template-columns: 1fr; } }

/* ── Add Form ── */
.cat-add-card {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 1.5rem;
    position: sticky;
    top: 84px;
}

.cat-add-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 1rem;
}

.cat-input {
    width: 100%;
    padding: 0.7rem 0.9rem;
    border: 1.5px solid var(--gray-200);
    border-radius: 0.5rem;
    font-size: 0.9rem;
    font-family: 'DM Sans', sans-serif;
    color: var(--gray-900);
    outline: none;
    transition: border-color 0.15s;
    margin-bottom: 0.75rem;
}

.cat-input:focus { border-color: var(--orange); }

.btn-cat-add {
    width: 100%;
    background: var(--orange);
    color: #fff;
    border: none;
    padding: 0.7rem;
    border-radius: 999px;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.15s;
}

.btn-cat-add:hover { background: var(--orange-dark); }

/* ── Category List ── */
.cat-list { display: flex; flex-direction: column; gap: 0.65rem; }

.cat-row {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: box-shadow 0.2s;
}

.cat-row:hover { box-shadow: var(--shadow-md); }

.cat-row-name {
    flex: 1;
    font-weight: 700;
    color: var(--gray-900);
    font-size: 0.95rem;
}

.cat-row-count {
    font-size: 0.78rem;
    font-weight: 700;
    padding: 0.15rem 0.65rem;
    border-radius: 999px;
    background: var(--orange-light);
    color: var(--orange);
}

/* Inline edit form */
.cat-edit-form {
    display: flex;
    gap: 0.4rem;
    align-items: center;
    flex: 1;
}

.cat-edit-input {
    flex: 1;
    padding: 0.45rem 0.75rem;
    border: 1.5px solid var(--orange);
    border-radius: 0.5rem;
    font-size: 0.88rem;
    font-family: 'DM Sans', sans-serif;
    outline: none;
}

.btn-cat-save {
    background: var(--orange);
    color: #fff;
    border: none;
    padding: 0.45rem 0.9rem;
    border-radius: 0.5rem;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.15s;
}

.btn-cat-save:hover { background: var(--orange-dark); }

.btn-cat-edit {
    background: none;
    border: 1.5px solid var(--gray-200);
    color: var(--gray-500);
    padding: 0.35rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: color 0.15s, border-color 0.15s;
}

.btn-cat-edit:hover { color: var(--orange); border-color: var(--orange); }

.btn-cat-delete {
    background: none;
    border: 1.5px solid #fecaca;
    color: #ef4444;
    padding: 0.35rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.15s;
}

.btn-cat-delete:hover { background: #fee2e2; }

.btn-cat-cancel {
    background: none;
    border: 1.5px solid var(--gray-200);
    color: var(--gray-400);
    padding: 0.45rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.82rem;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
}
</style>

<div class="admin-page">

    <div class="admin-header">
        <h1 class="admin-title">🏷️ <span>Categories</span></h1>
        <a href="/admin" class="btn-back">← Back to Admin</a>
    </div>

    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">✕ {{ session('error') }}</div>
    @endif

    <div class="cat-layout">

        {{-- ── Add Form ── --}}
        <div class="cat-add-card">
            <p class="cat-add-title">Add New Category</p>
            <form method="POST" action="/admin/categories">
                @csrf
                <input type="text" name="name" class="cat-input"
                       placeholder="e.g. Headphones"
                       value="{{ old('name') }}" required>
                @error('name')
                    <p style="color:#ef4444;font-size:0.78rem;margin-bottom:0.5rem;">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn-cat-add">+ Add Category</button>
            </form>
        </div>

        {{-- ── Category List ── --}}
        <div class="cat-list">
            @forelse($categories as $category)
                <div class="cat-row" id="row-{{ $category->id }}">

                    {{-- View mode --}}
                    <div id="view-{{ $category->id }}"
                         style="display:flex;align-items:center;gap:0.75rem;flex:1;">
                        <span class="cat-row-name">{{ $category->name }}</span>
                        <span class="cat-row-count">{{ $category->products_count }} products</span>
                    </div>

                    {{-- Edit mode (hidden) --}}
                    <form id="edit-form-{{ $category->id }}"
                          method="POST" action="/admin/categories/{{ $category->id }}"
                          class="cat-edit-form" style="display:none;">
                        @csrf @method('PUT')
                        <input type="text" name="name" class="cat-edit-input"
                               value="{{ $category->name }}" required>
                        <button type="submit" class="btn-cat-save">Save</button>
                        <button type="button" class="btn-cat-cancel"
                                onclick="toggleEdit({{ $category->id }}, false)">✕</button>
                    </form>

                    {{-- Action buttons --}}
                    <div id="actions-{{ $category->id }}"
                         style="display:flex;gap:0.4rem;flex-shrink:0;">
                        <button class="btn-cat-edit"
                                onclick="toggleEdit({{ $category->id }}, true)">✏️ Edit</button>
                        <form method="POST" action="/admin/categories/{{ $category->id }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-cat-delete"
                                    onclick="return confirm('Delete \"{{ $category->name }}\"?')">
                                🗑️
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div style="text-align:center;padding:3rem;color:var(--gray-400);">
                    No categories yet — add one!
                </div>
            @endforelse
        </div>

    </div>
</div>

<script>
function toggleEdit(id, show) {
    document.getElementById('view-' + id).style.display    = show ? 'none'  : 'flex';
    document.getElementById('edit-form-' + id).style.display = show ? 'flex'  : 'none';
    document.getElementById('actions-' + id).style.display = show ? 'none'  : 'flex';
    if (show) document.querySelector('#edit-form-' + id + ' input').focus();
}
</script>
</x-layout>