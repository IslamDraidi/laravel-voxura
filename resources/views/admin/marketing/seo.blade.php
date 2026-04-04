<x-admin-layout title="Search &amp; SEO" section="marketing" active="seo">

    <div class="info-banner">
        <span>🔍</span>
        <div>
            <strong>Search &amp; SEO</strong><br>
            Review your product and category slugs used in URLs. Clean, descriptive slugs improve search ranking.
            Meta title and description support will be available in a future update.
        </div>
    </div>

    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search by name or slug…" value="{{ request('search') }}">
        <button type="submit" class="add-btn">Search</button>
        <a href="{{ route('admin.seo.index') }}" style="font-size:0.85rem;color:var(--muted);align-self:center;">Reset</a>
    </form>

    <div class="two-col">
        {{-- Products --}}
        <div class="card">
            <p class="section-title">📦 Product URLs</p>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Slug / URL</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td style="font-weight:600;font-size:12px;">{{ $product->name }}</td>
                        <td>
                            <a href="/product/{{ $product->slug }}" target="_blank"
                               style="color:var(--orange);text-decoration:none;font-family:monospace;font-size:11px;">
                                /product/{{ $product->slug }}
                            </a>
                        </td>
                        <td style="font-size:12px;color:var(--muted);">{{ $product->category->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Categories --}}
        <div class="card">
            <p class="section-title">🗂️ Category URLs</p>
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Slug</th>
                        <th>Products</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    <tr>
                        <td style="font-weight:600;font-size:12px;">{{ $cat->name }}</td>
                        <td style="font-family:monospace;font-size:11px;color:var(--muted);">{{ $cat->slug }}</td>
                        <td>{{ $cat->products()->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--border);">
                <p style="font-size:12px;color:var(--muted);margin-bottom:0.5rem;font-weight:600;">🚀 Coming Soon</p>
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    <span class="badge badge-gray">Meta Titles</span>
                    <span class="badge badge-gray">Meta Descriptions</span>
                    <span class="badge badge-gray">Open Graph Tags</span>
                    <span class="badge badge-gray">Sitemap Generator</span>
                    <span class="badge badge-gray">Canonical URLs</span>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
