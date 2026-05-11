<x-admin-layout title="{{ __('admin.reviews_title') }}" section="customers" active="reviews">

    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-label">{{ __('admin.total_reviews') }}</div>
            <div class="sc-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">{{ __('admin.avg_rating') }}</div>
            <div class="sc-value green">{{ $stats['avg'] }} ★</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">{{ __('admin.five_star') }}</div>
            <div class="sc-value green">{{ $stats['stars5'] }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">{{ __('admin.one_star') }}</div>
            <div class="sc-value red">{{ $stats['stars1'] }}</div>
        </div>
    </div>

    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="{{ __('admin.search_reviews_ph') }}" value="{{ request('search') }}">
        <select name="rating" class="form-select" style="width:auto">
            <option value="">{{ __('admin.all_ratings') }}</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i !== 1 ? __('admin.stars_label', ['n' => $i]) : __('admin.star_label', ['n' => $i]) }}</option>
            @endfor
        </select>
        <button type="submit" class="add-btn">{{ __('admin.filter') }}</button>
        <a href="{{ route('admin.reviews.index') }}" style="font-size:0.85rem;color:var(--muted);align-self:center;">{{ __('admin.reset_btn') }}</a>
    </form>

    <p class="result-count">{{ $reviews->count() }} review{{ $reviews->count() !== 1 ? 's' : '' }}</p>

    @if($reviews->isEmpty())
        <div class="admin-empty">{{ __('admin.no_reviews') }}</div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('admin.customer_h') }}</th>
                        <th>{{ __('admin.product_h') }}</th>
                        <th>{{ __('admin.rating_h') }}</th>
                        <th>{{ __('admin.comment_h') }}</th>
                        <th>{{ __('admin.date_col') }}</th>
                        <th>{{ __('admin.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr>
                        <td style="font-weight:600;">{{ $review->user->name }}</td>
                        <td>
                            <a href="/product/{{ $review->product->slug }}" target="_blank"
                               style="color:var(--orange);text-decoration:none;font-size:13px;">
                                {{ $review->product->name }}
                            </a>
                        </td>
                        <td>
                            @php $stars = $review->rating; @endphp
                            <span style="color:#f59e0b;letter-spacing:1px;font-size:14px;">
                                {{ str_repeat('★', $stars) }}<span style="color:#d1d5db;">{{ str_repeat('★', 5 - $stars) }}</span>
                            </span>
                        </td>
                        <td style="max-width:300px;font-size:12px;color:var(--muted);">
                            {{ $review->comment ? Str::limit($review->comment, 80) : '—' }}
                        </td>
                        <td style="white-space:nowrap;font-size:12px;">{{ $review->created_at->format('M d, Y') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                                  onsubmit="return confirm('{{ __('admin.delete_review_confirm') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red">{{ __('admin.delete_btn') }}</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
