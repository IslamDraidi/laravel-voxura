<x-admin-layout title="Customers" section="customers" active="customers">

    {{-- Stats --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-icon" style="background:#fff3e0;color:var(--orange);">👥</div>
            <div class="sc-label">Total Users</div>
            <div class="sc-value" style="color:var(--orange);">{{ $admins->count() + $customers->count() }}</div>
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
            <option value="">All</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
            <option value="blocked"  {{ request('status') === 'blocked'  ? 'selected' : '' }}>Blocked</option>
        </select>
        <button type="submit" class="add-btn">Filter</button>
        <a href="{{ route('admin.customers.index') }}"
           style="font-size:0.85rem;color:var(--muted);text-decoration:none;align-self:center;">Reset</a>
    </form>

    {{-- ── Admins Table ── --}}
    <h2 style="font-size:1rem;font-weight:700;color:var(--text);margin-bottom:0.75rem;display:flex;align-items:center;gap:0.5rem;">
        <span class="badge badge-orange" style="font-size:0.7rem;">Admin</span>
        Administrators
        <span style="font-size:0.8rem;font-weight:400;color:var(--muted);">({{ $admins->count() }})</span>
    </h2>

    @if($admins->isEmpty())
        <div class="admin-empty" style="margin-bottom:2rem;">No administrators match your filter.</div>
    @else
        <div class="card" style="margin-bottom:2rem;">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    @php $isSelf = $admin->id === auth()->id(); @endphp
                    <tr>
                        <td style="font-weight:600;">
                            {{ $admin->name }}
                            @if($isSelf)
                                <span style="font-size:0.7rem;color:var(--muted);font-weight:400;margin-left:0.3rem;">(you)</span>
                            @endif
                        </td>
                        <td style="color:var(--muted);">{{ $admin->email }}</td>
                        <td style="white-space:nowrap;">{{ $admin->created_at->format('M d, Y') }}</td>
                        <td>
                            <span class="badge {{ $admin->is_blocked ? 'badge-red' : 'badge-green' }}">
                                {{ $admin->is_blocked ? 'Blocked' : 'Active' }}
                            </span>
                        </td>
                        <td>
                            @if($admin->is_blocked)
                                <form method="POST" action="{{ route('admin.customers.unblock', $admin) }}">
                                    @csrf
                                    <button type="submit" class="act-btn green">Unblock</button>
                                </form>
                            @elseif($isSelf)
                                <button type="button" class="act-btn red"
                                        disabled
                                        style="opacity:0.35;cursor:not-allowed;"
                                        title="You cannot block yourself">Block</button>
                            @else
                                <form method="POST" action="{{ route('admin.customers.block', $admin) }}"
                                      onsubmit="return confirm('Block {{ $admin->name }}? They won\'t be able to log in.')">
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

    {{-- ── Customers Table ── --}}
    <h2 style="font-size:1rem;font-weight:700;color:var(--text);margin-bottom:0.75rem;display:flex;align-items:center;gap:0.5rem;">
        <span style="font-size:1rem;">👥</span>
        Customers
        <span style="font-size:0.8rem;font-weight:400;color:var(--muted);">({{ $customers->count() }})</span>
    </h2>

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
