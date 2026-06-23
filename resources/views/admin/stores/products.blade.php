<x-admin-layout title="{{ $store->name }} — Products" section="stores" active="stores-products">

<style>
.back-link{display:inline-flex;align-items:center;gap:6px;color:var(--muted);font-size:13px;text-decoration:none;margin-bottom:16px;}
.back-link:hover{color:var(--dark);}
.tab-bar{display:flex;gap:0;border-bottom:1px solid var(--border);margin-bottom:20px;}
.tab-btn{padding:10px 16px;font-size:13px;font-weight:600;border:none;background:none;color:var(--muted);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-1px;text-decoration:none;}
.tab-btn.active{color:var(--orange);border-bottom-color:var(--orange);}
.products-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;}
.product-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;}
.product-img{width:100%;height:200px;object-fit:cover;background:var(--gray-100);display:block;}
.product-img-placeholder{width:100%;height:200px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;font-size:36px;color:var(--gray-400);}
.product-body{padding:12px;}
.product-name{font-size:13px;font-weight:600;color:var(--dark);margin-bottom:4px;}
.product-price{font-size:13px;color:var(--muted);margin-bottom:8px;}
.badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-pending{background:#fef3c7;color:#92400e;}
.badge-approved{background:#dcfce7;color:#15803d;}
.badge-rejected{background:#fee2e2;color:#991b1b;}
.product-actions{display:flex;gap:6px;margin-top:8px;}
.btn-sm{display:inline-flex;align-items:center;padding:5px 10px;border-radius:6px;font-size:12px;font-weight:600;border:none;cursor:pointer;text-decoration:none;}
.btn-approve{background:#dcfce7;color:#15803d;flex:1;justify-content:center;}
.btn-approve:hover{background:#bbf7d0;}
.btn-reject{background:#fee2e2;color:#991b1b;flex:1;justify-content:center;}
.btn-reject:hover{background:#fecaca;}
.btn-remove{background:var(--gray-100);color:var(--gray-600);flex:1;justify-content:center;}
.btn-remove:hover{background:var(--gray-200);}
.action-form{display:contents;}
.empty-state{text-align:center;padding:40px;color:var(--muted);}
</style>

<a href="{{ route('admin.stores.show', $store) }}" class="back-link">← {{ $store->name }}</a>

<h2 style="font-size:18px;font-weight:700;color:var(--dark);margin-bottom:16px;">
    {{ $store->name }} — Products
</h2>

<div class="tab-bar">
    <a href="{{ route('admin.stores.products', [$store, 'tab' => 'pending']) }}"
       class="tab-btn {{ $tab === 'pending' ? 'active' : '' }}">
        Pending ({{ $productCounts['pending'] }})
    </a>
    <a href="{{ route('admin.stores.products', [$store, 'tab' => 'approved']) }}"
       class="tab-btn {{ $tab === 'approved' ? 'active' : '' }}">
        Approved ({{ $productCounts['approved'] }})
    </a>
    <a href="{{ route('admin.stores.products', [$store, 'tab' => 'rejected']) }}"
       class="tab-btn {{ $tab === 'rejected' ? 'active' : '' }}">
        Rejected ({{ $productCounts['rejected'] }})
    </a>
</div>

@if($products->isEmpty())
    <div class="empty-state">
        <div style="font-size:36px;margin-bottom:8px;">📦</div>
        No {{ $tab }} products for this store.
    </div>
@else
<div class="products-grid">
    @foreach($products as $product)
    <div class="product-card">
        @php $img = $product->images->first(); @endphp
        @if($img)
            <img src="{{ asset($img->image_path ?? $img->path ?? '') }}" class="product-img" alt="">
        @else
            <div class="product-img-placeholder">📦</div>
        @endif
        <div class="product-body">
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-price">{{ config('shop.currency_symbol', '₪') }}{{ number_format($product->price, 2) }}</div>
            <span class="badge badge-{{ $product->status ?? 'approved' }}">{{ ucfirst($product->status ?? 'approved') }}</span>

            @if(($product->status ?? 'approved') === 'pending')
            <div class="product-actions">
                <form method="POST" action="{{ route('admin.stores.products.approve', [$store, $product]) }}" class="action-form">
                    @csrf
                    <button type="submit" class="btn-sm btn-approve">✓ Approve</button>
                </form>
                <form method="POST" action="{{ route('admin.stores.products.reject', [$store, $product]) }}" class="action-form">
                    @csrf
                    <button type="submit" class="btn-sm btn-reject">✕ Reject</button>
                </form>
            </div>
            @elseif(($product->status ?? 'approved') === 'approved')
            <div class="product-actions">
                <form method="POST" action="{{ route('admin.stores.products.remove', [$store, $product]) }}" class="action-form">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-sm btn-remove">Remove</button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

<div style="margin-top:20px;">
    {{ $products->appends(['tab' => $tab])->links() }}
</div>
@endif

</x-admin-layout>
