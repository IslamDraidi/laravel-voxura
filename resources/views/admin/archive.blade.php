<x-layout title="Archive">
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

.btn-admin {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: var(--orange);
    color: #fff;
    padding: 0.55rem 1.1rem;
    border-radius: 999px;
    text-decoration: none;
    font-size: 0.83rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
    font-family: 'DM Sans', sans-serif;
}

.btn-admin:hover { background: var(--orange-dark); }

.btn-admin-ghost {
    background: transparent;
    color: var(--gray-600);
    border: 1.5px solid var(--gray-300);
}

.btn-admin-ghost:hover {
    color: var(--orange);
    border-color: var(--orange);
    background: rgba(234,88,12,0.05);
}

.alert-success {
    background: #dcfce7;
    color: #16a34a;
    padding: 0.85rem 1.25rem;
    border-radius: 0.5rem;
    margin-bottom: 1.25rem;
    font-weight: 600;
    font-size: 0.9rem;
}

.archive-rows { display: flex; flex-direction: column; gap: 0.75rem; }

.archive-row {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    opacity: 0.82;
    transition: opacity 0.2s, box-shadow 0.2s;
}

.archive-row:hover { opacity: 1; box-shadow: var(--shadow-md); }

.archive-row img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 0.5rem;
    flex-shrink: 0;
    filter: grayscale(40%);
}

.archive-row-info { flex: 1; min-width: 0; }

.archive-row-name {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-900);
}

.archive-row-meta {
    display: flex;
    gap: 1rem;
    margin-top: 0.3rem;
    font-size: 0.82rem;
    color: var(--gray-400);
    flex-wrap: wrap;
}

.deleted-badge {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.15rem 0.6rem;
    border-radius: 999px;
    background: #fee2e2;
    color: #ef4444;
}

.archive-row-actions { display: flex; gap: 0.5rem; flex-shrink: 0; flex-wrap: wrap; }

.btn-restore {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.4rem 0.9rem;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid #bbf7d0;
    color: #16a34a;
    background: transparent;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.15s;
}

.btn-restore:hover { background: #dcfce7; }

.btn-edit-arch {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.4rem 0.9rem;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid var(--gray-200);
    color: var(--gray-600);
    background: transparent;
    font-family: 'DM Sans', sans-serif;
    transition: color 0.15s, border-color 0.15s;
}

.btn-edit-arch:hover { color: var(--orange); border-color: var(--orange); }

.btn-perm-delete {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.4rem 0.9rem;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid #fecaca;
    color: #ef4444;
    background: transparent;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.15s;
}

.btn-perm-delete:hover { background: #fee2e2; }

.archive-empty {
    text-align: center;
    padding: 4rem 1rem;
    color: var(--gray-400);
    font-size: 0.95rem;
}

@media (max-width: 640px) {
    .archive-row { flex-wrap: wrap; }
    .archive-row-actions { width: 100%; justify-content: flex-end; }
}
</style>

<div class="admin-page">

    <div class="admin-header">
        <h1 class="admin-title">📦 <span>Archive</span></h1>
        <a href="/admin" class="btn-admin btn-admin-ghost">← Back to Admin</a>
    </div>

    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    @if($products->isEmpty())
        <div class="archive-empty">
            <p style="font-size:2rem;margin-bottom:0.5rem;">📭</p>
            <p>No archived products — everything is live!</p>
        </div>
    @else
        <div class="archive-rows">
            @foreach($products as $product)
            <div class="archive-row">

                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}">

                <div class="archive-row-info">
                    <p class="archive-row-name">{{ $product->name }}</p>
                    <div class="archive-row-meta">
                        <span>{{ $product->category->name }}</span>
                        <span style="font-weight:700;color:var(--gray-900);">${{ number_format($product->price) }}</span>
                        <span class="deleted-badge">Deleted {{ $product->deleted_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="archive-row-actions">

                    {{-- Restore --}}
                    <form method="POST" action="/admin/products/{{ $product->id }}/restore">
                        @csrf
                        <button type="submit" class="btn-restore">↩️ Restore</button>
                    </form>

                    {{-- Edit --}}
                    <a href="/admin/products/{{ $product->id }}/edit?from=archive"
                       class="btn-edit-arch">✏️ Edit</a>

                    {{-- Permanent Delete --}}
                    <form method="POST" action="/admin/products/{{ $product->id }}/force-delete">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-perm-delete"
                                onclick="return confirm('Permanently delete \"{{ $product->name }}\"? This cannot be undone!')">
                            🗑️ Delete Forever
                        </button>
                    </form>

                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
</x-layout>