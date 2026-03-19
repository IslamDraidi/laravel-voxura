<x-layout :title="$product->name">

<style>
/* ── Product Detail ── */
.pd-page { max-width: 1100px; margin: 0 auto; padding: 2.5rem 1.5rem 5rem; animation: pdFadeIn 0.45s ease both; }
@keyframes pdFadeIn { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
.pd-back { display: inline-flex; align-items: center; gap: 0.4rem; color: var(--gray-500); text-decoration: none; font-size: 0.875rem; font-weight: 600; margin-bottom: 2.5rem; transition: color 0.18s; }
.pd-back svg { width: 16px; height: 16px; transition: transform 0.18s; }
.pd-back:hover { color: var(--orange); }
.pd-back:hover svg { transform: translateX(-3px); }
.pd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4.5rem; align-items: start; }
.pd-image-wrap { position: relative; border-radius: 1.25rem; overflow: hidden; background: var(--gray-100); aspect-ratio: 1/1; box-shadow: var(--shadow-card); }
.pd-image-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s ease; }
.pd-image-wrap:hover img { transform: scale(1.03); }
.pd-image-placeholder { width: 100%; height: 100%; min-height: 380px; display: flex; align-items: center; justify-content: center; color: var(--gray-300); }
.pd-wishlist { position: absolute; top: 14px; right: 14px; width: 42px; height: 42px; background: var(--white); border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 12px rgba(0,0,0,0.13); color: var(--gray-400); transition: color 0.2s, transform 0.2s; z-index: 2; }
.pd-wishlist:hover { color: #ef4444; transform: scale(1.12); }
.pd-wishlist.liked { color: #ef4444; }
.pd-wishlist svg { width: 18px; height: 18px; }
.pd-info { display: flex; flex-direction: column; }
.pd-category { font-size: 0.7rem; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: var(--orange); margin-bottom: 0.6rem; }
.pd-name { font-family: "Playfair Display", serif; font-size: 2.5rem; font-weight: 800; color: var(--gray-900); line-height: 1.15; margin-bottom: 0.8rem; }

/* Rating summary inline */
.pd-rating-summary {
    display: flex; align-items: center; gap: 0.5rem;
    margin-bottom: 0.75rem;
}
.pd-stars { display: flex; gap: 2px; }
.pd-stars svg { width: 16px; height: 16px; }
.pd-rating-text { font-size: 0.82rem; color: var(--gray-400); font-weight: 600; }

.pd-price { font-size: 2.1rem; font-weight: 800; color: var(--gray-900); margin-bottom: 1.1rem; }
.pd-stock { display: inline-flex; align-items: center; gap: 0.45rem; font-size: 0.84rem; font-weight: 600; margin-bottom: 1.75rem; }
.pd-stock svg { width: 16px; height: 16px; }
.pd-stock.in { color: #16a34a; }
.pd-stock.out { color: #dc2626; }
.pd-divider { border: none; border-top: 1px solid var(--gray-200); margin: 0 0 1.5rem; }
.pd-desc-label { font-size: 1rem; font-weight: 700; color: var(--gray-900); margin-bottom: 0.55rem; }
.pd-desc-text { font-size: 0.92rem; color: var(--gray-500); line-height: 1.8; margin-bottom: 1.75rem; }
.pd-cart-btn { width: 100%; padding: 1rem 1.5rem; background: var(--orange); color: var(--white); border: none; border-radius: 999px; font-family: "DM Sans", sans-serif; font-size: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.6rem; box-shadow: 0 4px 20px rgba(234,88,12,0.28); transition: background 0.18s, box-shadow 0.18s, transform 0.1s; }
.pd-cart-btn:hover { background: var(--orange-dark); box-shadow: 0 6px 28px rgba(234,88,12,0.38); }
.pd-cart-btn:active { transform: scale(0.98); }
.pd-cart-btn:disabled { background: var(--gray-300); box-shadow: none; cursor: not-allowed; }
.pd-cart-btn svg { width: 18px; height: 18px; }

/* ── Reviews Section ── */
.reviews-section {
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 1.5px solid var(--gray-200);
}

.reviews-header {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 1rem; margin-bottom: 2rem;
}

.reviews-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 800;
    color: var(--gray-900); letter-spacing: -0.02em;
}
.reviews-title span { color: var(--orange); }

/* ── Rating Overview ── */
.rating-overview {
    display: flex; align-items: center; gap: 2rem;
    background: #fff; border: 1.5px solid var(--gray-200);
    border-radius: var(--radius); padding: 1.5rem 2rem;
    margin-bottom: 2rem; flex-wrap: wrap;
}

.rating-big {
    text-align: center; flex-shrink: 0;
}

.rating-big-num {
    font-family: 'Playfair Display', serif;
    font-size: 3.5rem; font-weight: 800;
    color: var(--gray-900); line-height: 1;
}

.rating-big-stars { display: flex; gap: 3px; justify-content: center; margin: 0.35rem 0 0.2rem; }
.rating-big-stars svg { width: 20px; height: 20px; }
.rating-big-count { font-size: 0.78rem; color: var(--gray-400); }

.rating-bars { flex: 1; min-width: 180px; display: flex; flex-direction: column; gap: 0.4rem; }

.rating-bar-row { display: flex; align-items: center; gap: 0.6rem; }
.rating-bar-label { font-size: 0.75rem; font-weight: 700; color: var(--gray-500); width: 32px; flex-shrink: 0; }
.rating-bar-track { flex: 1; height: 7px; background: var(--gray-100); border-radius: 999px; overflow: hidden; }
.rating-bar-fill { height: 100%; background: var(--orange); border-radius: 999px; transition: width 0.4s ease; }
.rating-bar-count { font-size: 0.72rem; color: var(--gray-400); width: 20px; text-align: right; flex-shrink: 0; }

/* ── Write Review Form ── */
.review-form-card {
    background: #fff; border: 1.5px solid var(--gray-200);
    border-radius: var(--radius); padding: 1.75rem;
    margin-bottom: 2rem;
}

.review-form-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem; font-weight: 800;
    color: var(--gray-900); margin-bottom: 1.25rem;
}

/* Star picker */
.star-picker { display: flex; gap: 0.3rem; margin-bottom: 1rem; flex-direction: row-reverse; justify-content: flex-end; }
.star-picker input { display: none; }
.star-picker label {
    cursor: pointer; font-size: 1.8rem; color: var(--gray-200);
    transition: color 0.15s; line-height: 1;
}
.star-picker label:hover,
.star-picker label:hover ~ label,
.star-picker input:checked ~ label { color: #f59e0b; }

.review-textarea {
    width: 100%; padding: 0.75rem 0.9rem;
    border: 1.5px solid var(--gray-200); border-radius: 0.5rem;
    font-size: 0.9rem; font-family: 'DM Sans', sans-serif;
    color: var(--gray-900); outline: none; resize: vertical;
    transition: border-color 0.15s; min-height: 100px;
}
.review-textarea:focus { border-color: var(--orange); }

.btn-submit-review {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: var(--orange); color: #fff; border: none;
    padding: 0.65rem 1.75rem; border-radius: 999px;
    font-size: 0.9rem; font-weight: 700; cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.15s; margin-top: 0.75rem;
}
.btn-submit-review:hover { background: var(--orange-dark); }

.review-already {
    background: var(--orange-light); border: 1.5px solid var(--orange-muted);
    border-radius: var(--radius); padding: 1rem 1.25rem;
    font-size: 0.88rem; color: var(--orange); font-weight: 600;
    margin-bottom: 2rem;
}

.review-login-prompt {
    background: var(--gray-50); border: 1.5px solid var(--gray-200);
    border-radius: var(--radius); padding: 1.25rem;
    text-align: center; margin-bottom: 2rem;
    font-size: 0.9rem; color: var(--gray-500);
}
.review-login-prompt a { color: var(--orange); font-weight: 700; text-decoration: none; }

/* ── Review Cards ── */
.reviews-list { display: flex; flex-direction: column; gap: 1rem; }

.review-card {
    background: #fff; border: 1.5px solid var(--gray-200);
    border-radius: var(--radius); padding: 1.25rem 1.5rem;
    transition: box-shadow 0.2s;
}
.review-card:hover { box-shadow: var(--shadow-md); }

.review-card-header {
    display: flex; align-items: center;
    justify-content: space-between; margin-bottom: 0.6rem; flex-wrap: wrap; gap: 0.5rem;
}

.review-user {
    display: flex; align-items: center; gap: 0.6rem;
}

.review-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--orange); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem; font-weight: 800; flex-shrink: 0;
}

.review-user-name { font-size: 0.9rem; font-weight: 700; color: var(--gray-900); }
.review-date { font-size: 0.72rem; color: var(--gray-400); }

.review-stars { display: flex; gap: 2px; }
.review-stars svg { width: 14px; height: 14px; }

.review-comment {
    font-size: 0.88rem; color: var(--gray-600); line-height: 1.7;
    margin-top: 0.5rem;
}

.btn-delete-review {
    background: none; border: none; cursor: pointer;
    color: var(--gray-300); font-size: 0.75rem;
    transition: color 0.15s; padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
}
.btn-delete-review:hover { color: #ef4444; }

.reviews-empty {
    text-align: center; padding: 3rem 1rem;
    color: var(--gray-400); font-size: 0.9rem;
}

@media (max-width: 768px) {
    .pd-grid { grid-template-columns: 1fr; gap: 2rem; }
    .pd-name { font-size: 1.9rem; }
    .pd-price { font-size: 1.7rem; }
    .rating-overview { gap: 1rem; }
}
</style>

<div class="pd-page">

    <a href="{{ url()->previous() }}" class="pd-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Back
    </a>

    {{-- ── Product Grid ── --}}
    <div class="pd-grid">

        {{-- Image --}}
        <div class="pd-image-wrap">
            @if($product->image)
                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}">
            @else
                <div class="pd-image-placeholder">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.2">
                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="M21 15l-5-5L5 21"/>
                    </svg>
                </div>
            @endif

            {{-- Wishlist button --}}
            @auth
                @php $isLiked = in_array($product->id, auth()->user()->likedProductIds()); @endphp
                <form method="POST" action="/likes/{{ $product->id }}/toggle" style="position:absolute;top:14px;right:14px;">
                    @csrf
                    <button type="submit"
                            class="pd-wishlist {{ $isLiked ? 'liked' : '' }}"
                            title="{{ $isLiked ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                        <svg viewBox="0 0 24 24" fill="{{ $isLiked ? 'currentColor' : 'none' }}"
                             stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                </form>
            @else
                <a href="/login" class="pd-wishlist" title="Login to add to Wishlist">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </a>
            @endauth
        </div>

        {{-- Info --}}
        <div class="pd-info">
            @if($product->category)
                <span class="pd-category">{{ $product->category->name }}</span>
            @endif

            <h1 class="pd-name">{{ $product->name }}</h1>

            {{-- Inline rating summary --}}
            @if($reviews->count() > 0)
                <div class="pd-rating-summary">
                    <div class="pd-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg viewBox="0 0 24 24"
                                 fill="{{ $i <= round($avgRating) ? '#f59e0b' : 'none' }}"
                                 stroke="#f59e0b" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="pd-rating-text">
                        {{ number_format($avgRating, 1) }} · {{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }}
                    </span>
                </div>
            @endif

            <p class="pd-price">${{ number_format($product->price, 2) }}</p>

            <p class="pd-stock {{ $product->stock > 0 ? 'in' : 'out' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
                {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}
            </p>

            <hr class="pd-divider">
            <h3 class="pd-desc-label">Description</h3>
            <p class="pd-desc-text">{{ $product->description ?? 'No description available.' }}</p>
            <hr class="pd-divider">

            <form method="POST" action="/cart/add">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="pd-cart-btn" {{ $product->stock < 1 ? 'disabled' : '' }}>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="8" cy="21" r="1"/>
                        <circle cx="19" cy="21" r="1"/>
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                    </svg>
                    {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                </button>
            </form>
        </div>
    </div>

    {{-- ── Reviews ── --}}
    <div class="reviews-section">

        <div class="reviews-header">
            <h2 class="reviews-title">Customer <span>Reviews</span></h2>
        </div>

        {{-- Rating Overview --}}
        @if($reviews->count() > 0)
            @php
                $starCounts = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
                foreach($reviews as $r) $starCounts[$r->rating]++;
            @endphp
            <div class="rating-overview">
                <div class="rating-big">
                    <p class="rating-big-num">{{ number_format($avgRating, 1) }}</p>
                    <div class="rating-big-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg viewBox="0 0 24 24"
                                 fill="{{ $i <= round($avgRating) ? '#f59e0b' : 'none' }}"
                                 stroke="#f59e0b" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="rating-big-count">{{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }}</p>
                </div>

                <div class="rating-bars">
                    @foreach([5,4,3,2,1] as $star)
                        <div class="rating-bar-row">
                            <span class="rating-bar-label">{{ $star }}★</span>
                            <div class="rating-bar-track">
                                <div class="rating-bar-fill"
                                     style="width:{{ $reviews->count() ? ($starCounts[$star] / $reviews->count() * 100) : 0 }}%">
                                </div>
                            </div>
                            <span class="rating-bar-count">{{ $starCounts[$star] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Write Review --}}
        @auth
            @if($userReview)
                <div class="review-already">
                    ✓ You've already reviewed this product. Delete your review below to write a new one.
                </div>
            @else
                <div class="review-form-card">
                    <p class="review-form-title">Write a Review</p>
                    <form method="POST" action="/products/{{ $product->id }}/reviews">
                        @csrf

                        {{-- Star Picker --}}
                        <div class="star-picker">
                            @foreach([5,4,3,2,1] as $star)
                                <input type="radio" name="rating" id="star{{ $star }}"
                                       value="{{ $star }}" {{ old('rating') == $star ? 'checked' : '' }}>
                                <label for="star{{ $star }}" title="{{ $star }} stars">★</label>
                            @endforeach
                        </div>
                        @error('rating')
                            <p style="color:#ef4444;font-size:0.78rem;margin-bottom:0.5rem;">{{ $message }}</p>
                        @enderror

                        <textarea name="comment" class="review-textarea"
                                  placeholder="Share your experience with this product…"
                                  required>{{ old('comment') }}</textarea>
                        @error('comment')
                            <p style="color:#ef4444;font-size:0.78rem;margin-top:0.25rem;">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="btn-submit-review">
                            Post Review →
                        </button>
                    </form>
                </div>
            @endif
        @else
            <div class="review-login-prompt">
                <a href="/login">Sign in</a> to leave a review for this product.
            </div>
        @endauth

        {{-- Reviews List --}}
        @if($reviews->isEmpty())
            <div class="reviews-empty">
                <p style="font-size:2rem;margin-bottom:0.5rem;">💬</p>
                <p>No reviews yet — be the first to share your thoughts!</p>
            </div>
        @else
            <div class="reviews-list">
                @foreach($reviews as $review)
                    <div class="review-card">
                        <div class="review-card-header">
                            <div class="review-user">
                                <div class="review-avatar">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="review-user-name">{{ $review->user->name }}</p>
                                    <p class="review-date">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                <div class="review-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg viewBox="0 0 24 24"
                                             fill="{{ $i <= $review->rating ? '#f59e0b' : 'none' }}"
                                             stroke="#f59e0b" stroke-width="2">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    @endfor
                                </div>

                                {{-- Delete if own review or admin --}}
                                @auth
                                    @if($review->user_id === auth()->id() || auth()->user()->isAdmin())
                                        <form method="POST" action="/reviews/{{ $review->id }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-delete-review"
                                                    onclick="return confirm('Delete this review?')"
                                                    title="Delete review">
                                                🗑️
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        <p class="review-comment">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

</x-layout>