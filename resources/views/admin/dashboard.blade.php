<x-layout>
    <x-slot name="title">Admin Panel</x-slot>

   
        <div style="max-width:1100px; margin:0 auto;">

            {{-- Header --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem;">
                <h1 style="font-family:'Playfair Display',serif; font-size:3rem; font-weight:700;">
                    Admin <span style="color:#ea580c;">Panel</span>
                </h1>
                <div style="display:flex; gap:1rem;">
                    <a href="/"
                       style="background:#ea580c; color:#fff; padding:0.75rem 1.5rem; border-radius:999px; text-decoration:none; font-weight:600;">
                        ← Home
                    </a>
                    <a href="/admin/archive"
                       style="background:#ea580c; color:#fff; padding:0.75rem 1.5rem; border-radius:999px; text-decoration:none; font-weight:600;">
                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="hsl(15, 29%, 97%)" stroke-width="2" style="display:inline; vertical-align:middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                       </svg>

                    Archive
                    </a>
                    <a href="/admin/products/create"
                       style="background:#ea580c; color:#fff; padding:0.75rem 1.5rem; border-radius:999px; text-decoration:none; font-weight:600;">
                        + Add Product
                    </a>
                </div>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div style="background:#dcfce7; color:#16a34a; padding:1rem; border-radius:0.5rem; margin-bottom:1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Products List --}}
            <div style="display:flex; flex-direction:column; gap:1rem;">
                @foreach($products as $product)
                <div style="background:#fff; border-radius:0.75rem; padding:1.5rem; box-shadow:0 4px 16px rgba(0,0,0,0.08); display:flex; align-items:center; gap:1.5rem;">

                    <img src="{{ asset('images/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         style="width:96px; height:96px; object-fit:cover; border-radius:0.5rem;">

                    <div style="flex:1;">
                        <h3 style="font-size:1.25rem; font-weight:700; color:#000;">{{ $product->name }}</h3>
                        <p style="color:#6b7280;">{{ $product->category->name }}</p>
                        <div style="display:flex; gap:1rem; margin-top:0.5rem; font-size:0.9rem;">
                            <span style="font-weight:600;">${{ $product->price }}</span>
                            <span style="color:{{ $product->stock > 10 ? '#16a34a' : '#ea580c' }};">
                                Stock: {{ $product->stock }}
                            </span>
                        </div>
                    </div>

                    <div style="display:flex; gap:0.5rem;">
                        <a href="/admin/products/{{ $product->id }}/edit"
                           style="border:1.5px solid #e5e7eb; padding:0.5rem 1rem; border-radius:0.5rem; text-decoration:none; color:#374151;">
                            ✏️ Edit
                        </a>
                        <form method="POST" action="/admin/products/{{ $product->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this product?')"
                                    style="border:1.5px solid #fee2e2; padding:0.5rem 1rem; border-radius:0.5rem; background:none; cursor:pointer; color:#ef4444;">
                                🗑️ Delete
                            </button>
                        </form>
                    </div>

                </div>
                @endforeach
            </div>

        </div>
    </div>
</x-layout>