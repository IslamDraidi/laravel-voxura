<x-admin-layout title="Reviews" section="customers" active="reviews">

    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-label">Total Reviews</div>
            <div class="sc-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Avg Rating</div>
            <div class="sc-value green">{{ $stats['avg'] }} ★</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">5-Star Reviews</div>
            <div class="sc-value green">{{ $stats['stars5'] }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">1-Star Reviews</div>
            <div class="sc-value red">{{ $stats['stars1'] }}</div>
        </div>
    </div>

    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search by customer or product…" value="{{ request('search') }}">
        <select name="rating" class="form-select" style="width:auto">
            <option value="">All Ratings</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i !== 1 ? 's' : '' }}</option>
            @endfor
        </select>
        <button type="submit" class="add-btn">Filter</button>
        <a href="{{ route('admin.reviews.index') }}" style="font-size:0.85rem;color:var(--muted);align-self:center;">Reset</a>
    </form>

    <p class="result-count">{{ $reviews->count() }} review{{ $reviews->count() !== 1 ? 's' : '' }}</p>

    @if($reviews->isEmpty())
        <div class="admin-empty">No reviews found.</div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Actions</th>
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
                                  onsubmit="return confirm('Delete this review?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn red">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
