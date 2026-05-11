<x-admin-layout title="Products" section="catalog" active="products">

{{-- Page header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
    <h2 style="font-size:16px;font-weight:700;color:var(--dark);margin:0;">{{ __('admin.all_products') }}</h2>
    <a href="/admin/products/create" class="add-btn">{{ __('admin.add_product') }}</a>
</div>

{{-- Filters --}}
<form method="GET" action="/admin/products">
    <div class="search-bar" style="margin-bottom:1.25rem;">
        <input type="text" name="search" placeholder="{{ __('admin.search_products') }}" value="{{ request('search') }}">
        <select name="category">
            <option value="">{{ __('admin.all_categories') }}</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status">
            <option value="">{{ __('admin.all_status') }}</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.in_stock') }}</option>
            <option value="out"    {{ request('status') === 'out'    ? 'selected' : '' }}>{{ __('admin.out_of_stock') }}</option>
        </select>
        <button type="submit" class="add-btn">{{ __('admin.filter') }}</button>
        @if(request()->hasAny(['search','category','status']))
            <a href="/admin/products" class="act-btn" style="text-decoration:none;">{{ __('admin.clear') }}</a>
        @endif
    </div>
</form>

{{-- Stats --}}
<div class="stat-grid" style="margin-bottom:1.5rem;">
    <div class="stat-card">
        <span class="sc-label">{{ __('admin.total_products') }}</span>
        <span class="sc-value">{{ $products->count() }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">{{ __('admin.in_stock') }}</span>
        <span class="sc-value green">{{ $products->where('stock', '>', 0)->count() }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">{{ __('admin.out_of_stock') }}</span>
        <span class="sc-value red">{{ $products->where('stock', 0)->count() }}</span>
    </div>
    <div class="stat-card">
        <span class="sc-label">{{ __('admin.categories_col') }}</span>
        <span class="sc-value">{{ $categories->count() }}</span>
    </div>
</div>

{{-- Table --}}
<div class="card" style="padding:0;overflow-x:auto;">
    @if($products->isEmpty())
        <div class="admin-empty">{{ __('admin.no_products_found') }}</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.product_col') }}</th>
                    <th>{{ __('admin.category_col') }}</th>
                    <th>{{ __('admin.price_col') }}</th>
                    <th>{{ __('admin.stock_col') }}</th>
                    <th title="{{ __('admin.model_3d_col') }}">{{ __('admin.model_3d_col') }}</th>
                    <th>{{ __('admin.actions_col') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr data-product-id="{{ $product->id }}">
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            @if($product->image)
                                <img src="{{ asset('images/'.$product->image) }}"
                                     alt="{{ $product->name }}"
                                     style="width:44px;height:44px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                            @else
                                <div style="width:44px;height:44px;border-radius:8px;background:var(--gray-100);flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:18px;">📦</div>
                            @endif
                            <div>
                                <div style="font-weight:600;color:var(--dark);font-size:13px;">{{ $product->name }}</div>
                                @if($product->description)
                                    <div style="font-size:11px;color:var(--muted);margin-top:1px;max-width:220px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">{{ $product->description }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($product->category)
                            <span class="badge badge-orange">{{ $product->category->name }}</span>
                        @else
                            <span style="color:var(--muted);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td style="font-weight:600;color:var(--dark);">
                        ₪{{ number_format($product->price, 2) }}
                    </td>
                    <td>
                        @if($product->stock > 10)
                            <span class="badge badge-green">{{ $product->stock }}</span>
                        @elseif($product->stock > 0)
                            <span class="badge badge-amber">{{ __('admin.low_stock_badge', ['count' => $product->stock]) }}</span>
                        @else
                            <span class="badge badge-red">{{ __('admin.out_of_stock') }}</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $m3dStatus = $product->model3d_status ?? 'idle';
                            $m3dMap = [
                                'idle'       => ['color' => '#9ca3af', 'label' => __('admin.model3d_none'),       'spin' => false],
                                'queued'     => ['color' => '#f59e0b', 'label' => __('admin.model3d_queued'),     'spin' => false],
                                'processing' => ['color' => '#ea580c', 'label' => __('admin.model3d_processing'), 'spin' => true],
                                'ready'      => ['color' => '#16a34a', 'label' => __('admin.model3d_ready'),      'spin' => false],
                                'failed'     => ['color' => '#dc2626', 'label' => __('admin.model3d_failed'),     'spin' => false],
                            ];
                            $m3d = $m3dMap[$m3dStatus] ?? $m3dMap['idle'];
                        @endphp
                        <span title="{{ $m3d['label'] }}" style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $m3d['color'] }};{{ $m3d['spin'] ? 'animation:m3dIndexSpin 1s linear infinite;box-shadow:0 0 0 2px rgba(234,88,12,0.18);' : '' }}"></span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button type="button" class="act-btn" onclick="previewProduct({{ $product->id }})">{{ __('admin.view_btn') }}</button>
                            <button type="button" class="act-btn" onclick="adminNavigate('/admin/products/{{ $product->id }}/edit')">{{ __('admin.edit_btn') }}</button>
                            <button type="button" class="act-btn red" onclick="deleteProduct({{ $product->id }}, '{{ addslashes($product->name) }}')">{{ __('admin.delete_btn') }}</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<style>@keyframes m3dIndexSpin { to { transform: rotate(360deg); } }</style>
</x-admin-layout>
