<x-layout>
    <x-slot name="title">Add Product</x-slot>

    <div style="max-width:800px; margin:0 auto; padding:2rem 1.5rem;">

        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem;">
            <h1 style="font-family:'Playfair Display',serif; font-size:2rem; font-weight:700;">
                Add New Product
            </h1>
            <a href="/admin"
               style="border:1.5px solid #e5e7eb; color:#374151; padding:0.6rem 1.5rem; border-radius:999px; text-decoration:none; font-weight:600;">
                ← Back
            </a>
        </div>

        <div style="background:#fff; border-radius:0.75rem; padding:2rem; box-shadow:0 4px 16px rgba(0,0,0,0.08);">
            <form method="POST" action="/admin/products" enctype="multipart/form-data">
                @csrf

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div>
                        <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.5rem;">Product Name</label>
                        <input type="text" name="name" placeholder="Voxura Product Name" required
                               style="width:100%; padding:0.75rem; border:1px solid #e5e7eb; border-radius:0.5rem; font-size:1rem;">
                    </div>

                    <div>
                        <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.5rem;">Price</label>
                        <input type="number" name="price" placeholder="999" required
                               style="width:100%; padding:0.75rem; border:1px solid #e5e7eb; border-radius:0.5rem; font-size:1rem;">
                    </div>

                    <div>
                        <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.5rem;">Category</label>
                        <select name="category_id" required
                                style="width:100%; padding:0.75rem; border:1px solid #e5e7eb; border-radius:0.5rem; font-size:1rem;">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.5rem;">Stock</label>
                        <input type="number" name="stock" placeholder="50" required
                               style="width:100%; padding:0.75rem; border:1px solid #e5e7eb; border-radius:0.5rem; font-size:1rem;">
                    </div>

                    <div style="grid-column:1/-1;">
                        <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.5rem;">Image</label>
                        <input type="file" name="image" accept="image/*"
                               style="width:100%; padding:0.75rem; border:1px solid #e5e7eb; border-radius:0.5rem;">
                    </div>

                    <div style="grid-column:1/-1;">
                        <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.5rem;">Short Description</label>
                        <textarea name="description" placeholder="Brief product description" rows="2" required
                                  style="width:100%; padding:0.75rem; border:1px solid #e5e7eb; border-radius:0.5rem; font-size:1rem;"></textarea>
                    </div>

                    <div style="grid-column:1/-1;">
                        <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.5rem;">Detailed Description</label>
                        <textarea name="detailed_description" placeholder="Comprehensive product details" rows="4"
                                  style="width:100%; padding:0.75rem; border:1px solid #e5e7eb; border-radius:0.5rem; font-size:1rem;"></textarea>
                    </div>
                </div>

                <div style="display:flex; gap:1rem; margin-top:1.5rem;">
                    <button type="submit"
                            style="background:#ea580c; color:#fff; padding:0.75rem 2rem; border-radius:999px; border:none; cursor:pointer; font-size:1rem; font-weight:600;">
                        💾 Save
                    </button>
                    <a href="/admin"
                       style="border:1.5px solid #e5e7eb; padding:0.75rem 2rem; border-radius:999px; text-decoration:none; color:#374151; font-weight:600;">
                        ✕ Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layout>