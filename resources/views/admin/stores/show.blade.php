<x-admin-layout title="{{ $store->name }}" section="stores" active="stores-show">

<style>
.store-header{position:relative;border-radius:var(--radius);overflow:hidden;margin-bottom:20px;min-height:200px;background:var(--gray-100);}
.store-header-banner{width:100%;height:220px;object-fit:cover;display:block;}
.store-header-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.7) 0%,transparent 50%);display:flex;align-items:flex-end;justify-content:space-between;padding:20px 24px;}
.store-header-info{display:flex;align-items:center;gap:14px;}
.store-logo-lg{width:60px;height:60px;border-radius:12px;background:var(--orange);display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;font-weight:700;flex-shrink:0;overflow:hidden;border:3px solid rgba(255,255,255,.3);}
.store-logo-lg img{width:100%;height:100%;object-fit:cover;}
.store-header-name{color:#fff;font-size:20px;font-weight:700;}
.store-header-actions{display:flex;gap:8px;flex-wrap:wrap;}
.stats-5{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:20px;}
.stat-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:16px 18px;}
.stat-num{font-size:22px;font-weight:800;color:var(--dark);display:block;}
.stat-label{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;display:block;margin-top:2px;}
.two-col{display:grid;grid-template-columns:1.6fr 1fr;gap:20px;margin-bottom:20px;}
.card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:20px;}
.card-title{font-size:13px;font-weight:700;color:var(--dark);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--border);}
.field-row{display:flex;flex-direction:column;gap:3px;margin-bottom:12px;}
.field-label{font-size:11px;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);font-weight:600;}
.field-value{font-size:13px;color:var(--dark);}
.badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-pending{background:#fef3c7;color:#92400e;}
.badge-approved{background:#dcfce7;color:#15803d;}
.badge-rejected{background:#fee2e2;color:#991b1b;}
.badge-suspended{background:#f3f4f6;color:#374151;}
.plan-basic{background:#f3f4f6;color:#374151;}
.plan-pro{background:#dbeafe;color:#1d4ed8;}
.plan-premium{background:#fff7ed;color:#c2410c;}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:600;border:none;cursor:pointer;text-decoration:none;transition:opacity .15s;}
.btn:hover{opacity:.85;}
.btn-green{background:#dcfce7;color:#15803d;}
.btn-red{background:#fee2e2;color:#991b1b;}
.btn-amber{background:#fef3c7;color:#92400e;}
.btn-orange{background:var(--orange);color:#fff;}
.btn-blue{background:#dbeafe;color:#1d4ed8;}
.btn-ghost{background:var(--gray-100);color:var(--gray-600);}
.btn-full{width:100%;}
.form-input{width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;font-size:13px;font-family:inherit;color:var(--dark);}
.form-input:focus{outline:none;border-color:var(--orange);}
.form-label{font-size:12px;font-weight:600;color:var(--gray-600);margin-bottom:4px;display:block;}
.form-group{margin-bottom:12px;}
.action-form{display:block;}
.products-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:12px;margin-top:16px;}
.product-thumb{background:var(--white);border:1px solid var(--border);border-radius:10px;overflow:hidden;}
.product-thumb-img{width:100%;height:120px;object-fit:cover;background:var(--gray-100);}
.product-thumb-body{padding:8px;}
.product-thumb-name{font-size:12px;font-weight:600;color:var(--dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.product-thumb-price{font-size:11px;color:var(--muted);}
.expiry-ok{color:var(--green);}
.expiry-warn{color:var(--amber);}
.expiry-expired{color:var(--red);}
.section-divider{margin:20px 0;border:none;border-top:1px solid var(--border);}
.color-swatch{width:20px;height:20px;border-radius:4px;border:1px solid var(--border);display:inline-block;}
</style>

{{-- Store Header --}}
<div class="store-header">
    @if($store->banner_path)
        <img src="{{ asset($store->banner_path) }}" class="store-header-banner" alt="">
    @else
        <div style="width:100%;height:220px;background:linear-gradient(135deg,var(--orange),var(--orange-dark));"></div>
    @endif
    <div class="store-header-overlay">
        <div class="store-header-info">
            <div class="store-logo-lg">
                @if($store->logo_path)
                    <img src="{{ asset($store->logo_path) }}" alt="">
                @else
                    {{ strtoupper(substr($store->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <div class="store-header-name">{{ $store->name }}</div>
                <span class="badge badge-{{ $store->status }}" style="margin-top:4px;">{{ ucfirst($store->status) }}</span>
                @if($store->is_featured)
                    <span class="badge" style="background:#fef9c3;color:#854d0e;margin-left:4px;">⭐ Featured</span>
                @endif
            </div>
        </div>
        <div class="store-header-actions">
            <a href="{{ route('admin.stores.edit', $store) }}" class="btn btn-ghost" style="color:#fff;background:rgba(255,255,255,.2);">✏️ Edit</a>
            <a href="{{ route('stores.show', $store->slug) }}" target="_blank" class="btn btn-ghost" style="color:#fff;background:rgba(255,255,255,.2);">🔗 View Live</a>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="stats-5">
    <div class="stat-card">
        <span class="stat-num">{{ $storeStats['total_products'] }}</span>
        <span class="stat-label">Total Products</span>
    </div>
    <div class="stat-card">
        <span class="stat-num" style="color:var(--green)">{{ $storeStats['approved_products'] }}</span>
        <span class="stat-label">Approved</span>
    </div>
    <div class="stat-card">
        <span class="stat-num" style="{{ $storeStats['pending_products'] > 0 ? 'color:var(--amber)' : '' }}">{{ $storeStats['pending_products'] }}</span>
        <span class="stat-label">Pending</span>
    </div>
    <div class="stat-card">
        <span class="stat-num">{{ number_format($storeStats['visit_count']) }}</span>
        <span class="stat-label">Total Visits</span>
    </div>
    <div class="stat-card">
        <span class="stat-num" style="font-size:14px;">{{ $storeStats['last_visited'] ? $storeStats['last_visited']->diffForHumans() : '—' }}</span>
        <span class="stat-label">Last Visit</span>
    </div>
</div>

{{-- Two Column --}}
<div class="two-col">

    {{-- Left Column --}}
    <div>

        {{-- Store Info --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-title">Store Information</div>
            <div class="field-row">
                <span class="field-label">Name</span>
                <span class="field-value">{{ $store->name }}</span>
            </div>
            <div class="field-row">
                <span class="field-label">Slug</span>
                <span class="field-value">/stores/{{ $store->slug }}</span>
            </div>
            @if($store->tagline)
            <div class="field-row">
                <span class="field-label">Tagline</span>
                <span class="field-value">{{ $store->tagline }}</span>
            </div>
            @endif
            @if($store->description)
            <div class="field-row">
                <span class="field-label">Description</span>
                <span class="field-value" style="line-height:1.5;">{{ $store->description }}</span>
            </div>
            @endif
            @if($store->accent_color)
            <div class="field-row">
                <span class="field-label">Accent Color</span>
                <span class="color-swatch" style="background:{{ $store->accent_color }};"></span>
                <span class="field-value" style="display:inline;margin-left:6px;">{{ $store->accent_color }}</span>
            </div>
            @endif
            @if($store->category_tags)
            <div class="field-row">
                <span class="field-label">Categories</span>
                <div style="display:flex;flex-wrap:wrap;gap:4px;margin-top:2px;">
                    @foreach($store->category_tags as $tag)
                        <span style="background:var(--gray-100);border-radius:20px;padding:2px 10px;font-size:11px;">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            @if($store->social_links)
            <div class="field-row">
                <span class="field-label">Social Links</span>
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:4px;">
                    @foreach($store->social_links as $platform => $url)
                        @if($url)
                        <a href="{{ $url }}" target="_blank" style="font-size:12px;color:var(--orange);">{{ ucfirst($platform) }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
            @if($store->admin_notes)
            <div class="field-row">
                <span class="field-label">Admin Notes</span>
                <span class="field-value" style="font-style:italic;color:var(--muted);">{{ $store->admin_notes }}</span>
            </div>
            @endif
        </div>

        {{-- Subscription --}}
        <div class="card" id="subscription">
            <div class="card-title">Subscription & Billing</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                <div class="field-row">
                    <span class="field-label">Plan</span>
                    <span class="badge plan-{{ $store->plan_type }}">{{ ucfirst($store->plan_type) }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Monthly Fee</span>
                    <span class="field-value">${{ number_format($store->subscription_fee, 2) }}</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Commission</span>
                    <span class="field-value">{{ $store->commission_rate }}%</span>
                </div>
                <div class="field-row">
                    <span class="field-label">Status</span>
                    <span class="field-value" style="{{ $store->subscription_active ? 'color:var(--green)' : 'color:var(--muted)' }}">
                        {{ $store->subscription_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                @if($store->subscription_expires_at)
                <div class="field-row">
                    <span class="field-label">Expires</span>
                    @php $exClass = $store->is_expired ? 'expiry-expired' : ($store->days_until_expiry <= 7 ? 'expiry-warn' : 'expiry-ok'); @endphp
                    <span class="field-value {{ $exClass }}">
                        {{ $store->subscription_expires_at->format('d M Y') }}
                        ({{ $store->is_expired ? 'Expired' : $store->days_until_expiry . ' days left' }})
                    </span>
                </div>
                @endif
            </div>
            <form method="POST" action="{{ route('admin.stores.subscription.update', $store) }}" class="action-form">
                @csrf @method('PATCH')
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div class="form-group">
                        <label class="form-label">Plan</label>
                        <select name="plan_type" class="form-input">
                            <option value="basic" {{ $store->plan_type === 'basic' ? 'selected' : '' }}>Basic</option>
                            <option value="pro" {{ $store->plan_type === 'pro' ? 'selected' : '' }}>Pro</option>
                            <option value="premium" {{ $store->plan_type === 'premium' ? 'selected' : '' }}>Premium</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Monthly Fee ($)</label>
                        <input type="number" name="subscription_fee" class="form-input" value="{{ $store->subscription_fee }}" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Commission Rate (%)</label>
                        <input type="number" name="commission_rate" class="form-input" value="{{ $store->commission_rate }}" step="0.01" min="0" max="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="subscription_expires_at" class="form-input"
                            value="{{ $store->subscription_expires_at ? $store->subscription_expires_at->format('Y-m-d') : '' }}">
                    </div>
                </div>
                <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="subscription_active" value="1" id="sub_active" {{ $store->subscription_active ? 'checked' : '' }}>
                    <label for="sub_active" class="form-label" style="margin:0;">Subscription Active</label>
                </div>
                <button type="submit" class="btn btn-orange" style="margin-top:4px;">Update Subscription</button>
            </form>
        </div>

        {{-- 3D Generation Credits --}}
        @if($store->has3DAccess())
        <div class="card" style="margin-top:16px;">
            <div class="card-title">3D Generation Credits</div>
            <div class="field-row">
                <span class="field-label">Current Balance</span>
                <span class="field-value" style="font-weight:700;color:var(--blue)">{{ $store->credits_balance }}</span>
            </div>
            <div class="field-row">
                <span class="field-label">Monthly Allowance</span>
                <span class="field-value">{{ $store->monthlyCredits() }}/mo</span>
            </div>
            <div class="field-row">
                <span class="field-label">Total Used</span>
                <span class="field-value">{{ $store->credits_used_total }}</span>
            </div>
            <div class="field-row">
                <span class="field-label">Bonus Credits</span>
                <span class="field-value">{{ $store->credits_bonus }}</span>
            </div>
            @if($store->credits_last_topped_up_at)
            <div class="field-row">
                <span class="field-label">Last Top-up</span>
                <span class="field-value">{{ $store->credits_last_topped_up_at->format('d M Y') }}</span>
            </div>
            @endif

            {{-- Grant credits --}}
            <form method="POST" action="{{ route('admin.stores.credits.grant', $store) }}" class="action-form" style="margin-top:14px;">
                @csrf
                <div class="form-group">
                    <label class="form-label">Grant Credits</label>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <input type="number" name="amount" class="form-input" min="1" max="500" placeholder="Amount" style="width:100px;">
                        <input type="text" name="reason" class="form-input" placeholder="Reason (optional)">
                        <button type="submit" class="btn btn-orange" style="white-space:nowrap;">Grant</button>
                    </div>
                </div>
            </form>

            {{-- Reset usage counter --}}
            <form method="POST" action="{{ route('admin.stores.credits.reset', $store) }}" class="action-form" style="margin-top:8px;">
                @csrf
                <button type="submit" class="btn btn-ghost" onclick="return confirm('Reset the usage counter for this store?')">Reset Usage Counter</button>
            </form>
        </div>
        @endif
    </div>

    {{-- Right Column --}}
    <div>

        {{-- Owner --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-title">Store Owner</div>
            @if($store->owner)
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                <div style="width:44px;height:44px;border-radius:50%;background:var(--orange);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:16px;">
                    {{ strtoupper(substr($store->owner->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:600;">{{ $store->owner->name }}</div>
                    <div style="font-size:12px;color:var(--muted);">{{ $store->owner->email }}</div>
                </div>
            </div>
            <div class="field-row">
                <span class="field-label">Registered</span>
                <span class="field-value">{{ $store->owner->created_at->format('d M Y') }}</span>
            </div>
            @else
            <p style="color:var(--muted);font-size:13px;">No owner assigned.</p>
            @endif
        </div>

        {{-- Quick Actions --}}
        <div class="card" style="margin-bottom:16px;" id="reject">
            <div class="card-title">Quick Actions</div>

            @if($store->status === 'pending')
                <form method="POST" action="{{ route('admin.stores.approve', $store) }}" class="action-form" style="margin-bottom:10px;">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Admin Note (optional)</label>
                        <textarea name="admin_notes" class="form-input" rows="2" placeholder="Internal note...">{{ $store->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-green btn-full">✓ Approve Store</button>
                </form>
                <form method="POST" action="{{ route('admin.stores.reject', $store) }}" class="action-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Rejection Reason <span style="color:var(--red);">*</span></label>
                        <textarea name="rejection_reason" class="form-input" rows="2" placeholder="Explain why..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-red btn-full">✕ Reject Store</button>
                </form>

            @elseif($store->status === 'approved')
                <div id="suspend" style="margin-bottom:10px;">
                    <form method="POST" action="{{ route('admin.stores.suspend', $store) }}" class="action-form">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Suspension Reason <span style="color:var(--red);">*</span></label>
                            <textarea name="suspension_reason" class="form-input" rows="2" placeholder="Explain why..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-amber btn-full">⊘ Suspend Store</button>
                    </form>
                </div>

            @elseif($store->status === 'suspended')
                <form method="POST" action="{{ route('admin.stores.reactivate', $store) }}" class="action-form">
                    @csrf
                    <button type="submit" class="btn btn-green btn-full">↺ Reactivate Store</button>
                </form>

            @elseif($store->status === 'rejected')
                <form method="POST" action="{{ route('admin.stores.approve', $store) }}" class="action-form">
                    @csrf
                    <button type="submit" class="btn btn-green btn-full">✓ Approve (Reconsider)</button>
                </form>
            @endif
        </div>

        {{-- Featured Control --}}
        <div class="card">
            <div class="card-title">Featured Store</div>
            <form method="POST" action="{{ route('admin.stores.feature', $store) }}" class="action-form" style="margin-bottom:12px;">
                @csrf
                <div class="form-group">
                    <label class="form-label">Featured Label</label>
                    <input type="text" name="featured_label" class="form-input"
                        value="{{ $store->featured_label ?? 'Store of the Week' }}"
                        placeholder="Store of the Week">
                </div>
                <button type="submit" class="btn btn-orange btn-full">
                    {{ $store->is_featured ? '⭐ Update Featured Label' : '⭐ Set as Featured' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.stores.auto-feature') }}" class="action-form">
                @csrf
                <button type="submit" class="btn btn-ghost btn-full" style="font-size:12px;">
                    🤖 Auto-Feature Top Store
                </button>
            </form>
            <p style="font-size:11px;color:var(--muted);margin-top:8px;">Auto-feature picks the most visited approved store.</p>
        </div>
    </div>
</div>

{{-- Recent Products --}}
<div class="card">
    <div class="card-title" style="display:flex;align-items:center;justify-content:space-between;">
        Recent Products
        <a href="{{ route('admin.stores.products', $store) }}" style="font-size:12px;color:var(--orange);">View All →</a>
    </div>
    @if($recentProducts->isEmpty())
        <p style="color:var(--muted);font-size:13px;">No products yet.</p>
    @else
    <div class="products-grid">
        @foreach($recentProducts as $product)
        <div class="product-thumb">
            @php $img = $product->images->first(); @endphp
            <div class="product-thumb-img" style="{{ $img ? '' : 'display:flex;align-items:center;justify-content:center;' }}">
                @if($img)
                    <img src="{{ asset($img->image_path ?? $img->path ?? '') }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                @else
                    <span style="font-size:24px;color:var(--gray-400);">📦</span>
                @endif
            </div>
            <div class="product-thumb-body">
                <div class="product-thumb-name">{{ $product->name }}</div>
                <div class="product-thumb-price">{{ config('shop.currency_symbol', '₪') }}{{ number_format($product->price, 2) }}</div>
                <span class="badge badge-{{ $product->status ?? 'approved' }}" style="margin-top:4px;font-size:10px;">
                    {{ ucfirst($product->status ?? 'approved') }}
                </span>
                @if(($product->status ?? 'approved') === 'pending')
                <div style="display:flex;gap:4px;margin-top:6px;">
                    <form method="POST" action="{{ route('admin.stores.products.approve', [$store, $product]) }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:#dcfce7;color:#15803d;border:none;border-radius:4px;padding:3px 7px;font-size:10px;font-weight:600;cursor:pointer;">✓</button>
                    </form>
                    <form method="POST" action="{{ route('admin.stores.products.reject', [$store, $product]) }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:#fee2e2;color:#991b1b;border:none;border-radius:4px;padding:3px 7px;font-size:10px;font-weight:600;cursor:pointer;">✕</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

</x-admin-layout>
