<div class="product-card" data-index="{{ $index }}">
    <div class="pc-card">

        {{-- الصورة --}}
        <div class="pc-image-wrap">

            {{-- الصورة نفسها --}}
            <a href="/product/{{ $product->id }}">
                <img src="{{ asset('images/' . $product->image) }}"
                     alt="{{ $product->name }}" />
            </a>

            {{-- Overlay --}}
            <div class="pc-overlay"></div>

            {{-- زر اللايك --}}
            <form method="POST" action="/likes/{{ $product->id }}/toggle"
                  class="pc-like-form">
                @csrf
                <button type="submit"
                        class="pc-like-btn {{ in_array($product->id, $likedIds ?? []) ? 'liked' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </button>
            </form>

            {{-- زر Add to Cart فوق الصورة --}}
            <div class="pc-cart-hover">
                <form method="POST" action="/cart/add">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn-pc-cart">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="8" cy="21" r="1"/>
                            <circle cx="19" cy="21" r="1"/>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                        </svg>
                        Add to Cart
                    </button>
                </form>
            </div>

        </div>

        {{-- المعلومات --}}
        <div class="pc-info">
            <p class="pc-category">{{ $product->category->name}}</p>
            <h3 class="pc-name">
                <a href="/product/{{ $product->id }}">{{ $product->name }}</a>
            </h3>
            <p class="pc-description">{{ $product->description }}</p>
            <div class="pc-footer">
                <span class="pc-price">₪{{ number_format($product->price) }}</span>
                <a href="/product/{{ $product->id }}" class="pc-learn">
                    Learn more →
                </a>
            </div>
            {{-- Compare toggle --}}
            @php $inCompare = in_array($product->id, session('compare', [])); @endphp
            <form method="POST" action="/compare/{{ $product->id }}"
                  style="margin-top:0.5rem;">
                @csrf
                @if($inCompare)
                    @method('DELETE')
                    <button type="submit" style="
                        background:none;border:none;padding:0;
                        font-size:0.75rem;font-weight:600;cursor:pointer;
                        color:var(--orange);font-family:'DM Sans',sans-serif;
                        display:flex;align-items:center;gap:0.3rem;
                        transition:color 0.15s;
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Added to compare
                    </button>
                @else
                    <button type="submit" style="
                        background:none;border:none;padding:0;
                        font-size:0.75rem;font-weight:600;cursor:pointer;
                        color:var(--gray-400);font-family:'DM Sans',sans-serif;
                        display:flex;align-items:center;gap:0.3rem;
                        transition:color 0.15s;
                    " onmouseover="this.style.color='var(--orange)'" onmouseout="this.style.color='var(--gray-400)'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Compare
                    </button>
                @endif
            </form>
        </div>

    </div>
</div>