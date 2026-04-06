<x-admin-layout title="Archive" section="catalog" active="archive">

    @if($products->isEmpty())
        <div class="admin-empty">
            <p style="font-size:2rem;margin-bottom:0.5rem;">📭</p>
            <p>No archived products — everything is live!</p>
        </div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Deleted Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <img src="{{ asset('images/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 style="width:48px;height:48px;object-fit:cover;border-radius:6px;filter:grayscale(40%);">
                        </td>
                        <td style="font-weight:600;">{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>₪{{ number_format($product->price) }}</td>
                        <td style="white-space:nowrap;">
                            <span class="badge badge-red">{{ $product->deleted_at->format('M d, Y') }}</span>
                        </td>
                        <td style="white-space:nowrap;">
                            <form method="POST" action="/admin/products/{{ $product->id }}/restore" style="display:inline;">
                                @csrf
                                <button type="submit" class="act-btn green">Restore</button>
                            </form>
                            <a href="/admin/products/{{ $product->id }}/edit?from=archive" class="act-btn">Edit</a>
                            <form method="POST" action="/admin/products/{{ $product->id }}/force-delete" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red"
                                        onclick="return confirm('Permanently delete \"{{ $product->name }}\"? This cannot be undone!')">
                                    Delete Forever
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
