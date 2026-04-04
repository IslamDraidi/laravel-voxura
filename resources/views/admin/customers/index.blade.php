<x-admin-layout title="Customers" section="customers" active="customers">

    {{-- Stats --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-icon" style="background:#fff3e0;color:var(--orange);">👥</div>
            <div class="sc-label">Total Customers</div>
            <div class="sc-value" style="color:var(--orange);">{{ $customers->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-icon" style="background:#dcfce7;color:#16a34a;">✓</div>
            <div class="sc-label">Active</div>
            <div class="sc-value green">{{ $customers->where('is_blocked', false)->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-icon" style="background:#fee2e2;color:#dc2626;">⊘</div>
            <div class="sc-label">Blocked</div>
            <div class="sc-value red">{{ $customers->where('is_blocked', true)->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-icon" style="background:#dbeafe;color:#2563eb;">$</div>
            <div class="sc-label">Total Revenue</div>
            <div class="sc-value blue" style="font-size:1.5rem;">${{ number_format($customers->sum('orders_sum_total_amount')) }}</div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.customers.index') }}" class="search-bar" style="margin-bottom:1.25rem;">
        <input type="text" name="search" placeholder="Search by name or email…" value="{{ request('search') }}">
        <select name="status">
            <option value="">All Customers</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
            <option value="blocked"  {{ request('status') === 'blocked'  ? 'selected' : '' }}>Blocked</option>
        </select>
        <button type="submit" class="add-btn">Filter</button>
        <a href="{{ route('admin.customers.index') }}"
           style="font-size:0.85rem;color:var(--muted);text-decoration:none;align-self:center;">Reset</a>
    </form>

    <p class="result-count">Showing {{ $customers->count() }} customer{{ $customers->count() !== 1 ? 's' : '' }}</p>

    @if($customers->isEmpty())
        <div class="admin-empty">No customers found.</div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td style="font-weight:600;">{{ $customer->name }}</td>
                        <td style="color:var(--muted);">{{ $customer->email }}</td>
                        <td style="white-space:nowrap;">{{ $customer->created_at->format('M d, Y') }}</td>
                        <td>{{ $customer->orders_count }}</td>
                        <td>${{ number_format($customer->orders_sum_total_amount ?? 0) }}</td>
                        <td>
                            <span class="badge {{ $customer->is_blocked ? 'badge-red' : 'badge-green' }}">
                                {{ $customer->is_blocked ? 'Blocked' : 'Active' }}
                            </span>
                        </td>
                        <td>
                            @if($customer->is_blocked)
                                <form method="POST" action="{{ route('admin.customers.unblock', $customer) }}">
                                    @csrf
                                    <button type="submit" class="act-btn green">Unblock</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.customers.block', $customer) }}"
                                      onsubmit="return confirm('Block {{ $customer->name }}? They won\'t be able to log in.')">
                                    @csrf
                                    <button type="submit" class="act-btn red">Block</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
