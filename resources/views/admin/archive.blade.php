<x-layout>
    <x-slot name="title">Archive</x-slot>

    
        <div style="max-width:1100px; margin:0 auto;">

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem;">
                <h1 style="font-family:'Playfair Display',serif; font-size:3rem; font-weight:700;">
                    
                    
                    <span >Archive</span>
                </h1
                <a href="/admin"
                   style="background:#ea580c; color:#fff; padding:0.75rem 1.5rem; border-radius:999px; text-decoration:none; font-weight:600;">
                    ← Back to Admin
                </a>
            </div>

            @if(session('success'))
                <div style="background:#dcfce7; color:#16a34a; padding:1rem; border-radius:0.5rem; margin-bottom:1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if($products->isEmpty())
                
                    <p style="color:#6b7280; font-size:1.1rem; text-align:center;">No archived products</p>
                </div>
            @else
                <div style="display:flex; flex-direction:column; gap:1rem;">
                    @foreach($products as $product)
                    <div style="background:#fff; border-radius:0.75rem; padding:1.5rem; box-shadow:0 4px 16px rgba(0,0,0,0.08); display:flex; align-items:center; gap:1.5rem; opacity:0.8;">

                        <img src="{{ asset('images/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             style="width:96px; height:96px; object-fit:cover; border-radius:0.5rem; filter:grayscale(50%);">

                        <div style="flex:1;">
                            <h3 style="font-size:1.25rem; font-weight:700; color:#000;">{{ $product->name }}</h3>
                            <p style="color:#6b7280;">{{ $product->category->name }}</p>
                            <div style="display:flex; gap:1rem; margin-top:0.5rem; font-size:0.9rem;">
                                <span style="font-weight:600;">${{ $product->price }}</span>
                                <span style="color:#ef4444;">Deleted: {{ $product->deleted_at->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <div style="display:flex; gap:0.5rem;">
                            <form method="POST" action="/admin/products/{{ $product->id }}/restore">
                                @csrf
                                <button type="submit"
                                        style="background:#ea580c; color:#fff; padding:0.5rem 1rem; border-radius:0.5rem; border:none; cursor:pointer; font-weight:600;">
                                    ↩️ Restore
                                </button>
                            </form>
                            <a href="/admin/products/{{ $product->id }}/edit?from=archive"
                               style="background:#ea580c; color:#fff; padding:0.5rem 1rem; border-radius:0.5rem; text-decoration:none; font-weight:600;">
                                ✏️ Edit
                            </a>
                        </div>

                    </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-layout>
