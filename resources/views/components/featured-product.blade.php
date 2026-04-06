{{-- 
    $product = object فيه: name, category, description, price, image
    $index   = رقم المنتج (0, 1, 2)
--}}

<div class="featured-product {{ $index % 2 === 0 ? 'even' : 'odd' }}"
     data-index="{{ $index }}">

    {{-- الصورة --}}
    <div class="fp-image">
        <div class="fp-image-wrap">
            <img src="{{ asset('images/' . $product->image) }}" 
            alt="{{ $product->name }}" />
        </div>
    </div>

    {{-- المحتوى --}}
    <div class="fp-content">

        {{-- الكاتيغوري --}}
        <p class="fp-category">{{ $product->category->name }}</p>

        {{-- اسم المنتج --}}
        <h2 class="fp-name">{{ $product->name }}</h2>

        {{-- الوصف --}}
        <p class="fp-description">{{ $product->description }}</p>

        {{-- السعر + الزر --}}
      <div class="fp-actions">
    <span class="fp-price">₪{{ number_format($product->price) }}</span>

            <form method="POST" action="/cart/add">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn-add-cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
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

</div>