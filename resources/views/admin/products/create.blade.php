<x-layout title="Add Product">
<style>
.admin-page { padding-top: 90px; padding-bottom: 4rem; }

.form-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}

.form-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 800;
    color: var(--gray-900);
    letter-spacing: -0.03em;
}

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

.form-card {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 2rem;
    box-shadow: var(--shadow-md);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}

@media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }

.form-group { display: flex; flex-direction: column; gap: 0.4rem; }
.form-group.full { grid-column: 1 / -1; }

.form-label {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--gray-500);
}

.form-input,
.form-select,
.form-textarea {
    padding: 0.7rem 0.9rem;
    border: 1.5px solid var(--gray-200);
    border-radius: 0.5rem;
    font-size: 0.9rem;
    font-family: 'DM Sans', sans-serif;
    color: var(--gray-900);
    outline: none;
    transition: border-color 0.15s;
    width: 100%;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus { border-color: var(--orange); }

.form-textarea { resize: vertical; }

.form-error {
    font-size: 0.78rem;
    color: #ef4444;
    margin-top: 0.2rem;
}

.form-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 1.75rem;
    flex-wrap: wrap;
}

.btn-save {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: var(--orange);
    color: #fff;
    padding: 0.7rem 2rem;
    border-radius: 999px;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 700;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.15s;
}

.btn-save:hover { background: var(--orange-dark); }

.btn-cancel {
    display: inline-flex;
    align-items: center;
    padding: 0.7rem 2rem;
    border: 1.5px solid var(--gray-200);
    border-radius: 999px;
    text-decoration: none;
    color: var(--gray-600);
    font-size: 0.9rem;
    font-weight: 600;
    transition: color 0.15s, border-color 0.15s;
}

.btn-cancel:hover { color: var(--orange); border-color: var(--orange); }
</style>

<div class="admin-page">

    <div class="form-header">
        <h1 class="form-title">Add New Product</h1>
        <a href="/admin" class="btn-back">← Back</a>
    </div>

    <div class="form-card">
        <form method="POST" action="/admin/products" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-input"
                           placeholder="e.g. Voxura Pro Headset"
                           value="{{ old('name') }}" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Price ($)</label>
                    <input type="number" name="price" class="form-input"
                           placeholder="999" min="0" step="0.01"
                           value="{{ old('price') }}" required>
                    @error('price')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select category…</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-input"
                           placeholder="50" min="0"
                           value="{{ old('stock') }}" required>
                    @error('stock')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group full">
                    <label class="form-label">Product Image</label>
                    <input type="file" name="image" class="form-input" accept="image/*">
                    @error('image')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group full">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="4"
                              placeholder="Describe this product…" required>{{ old('description') }}</textarea>
                    @error('description')<p class="form-error">{{ $message }}</p>@enderror
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">💾 Save Product</button>
                <a href="/admin" class="btn-cancel">✕ Cancel</a>
            </div>

        </form>
    </div>

</div>
</x-layout>