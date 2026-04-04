<x-admin-layout title="Transactions" section="sales" active="transactions">

    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-label">Total Processed</div>
            <div class="sc-value green" style="font-size:1.4rem;">${{ number_format($stats['completed'], 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Total Volume</div>
            <div class="sc-value" style="font-size:1.4rem;">${{ number_format($stats['total'], 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Refunded</div>
            <div class="sc-value red" style="font-size:1.4rem;">${{ number_format($stats['refunded'], 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Pending</div>
            <div class="sc-value amber">{{ $stats['pending'] }}</div>
        </div>
    </div>

    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search by customer or transaction ID…" value="{{ request('search') }}">
        <select name="status" class="form-select" style="width:auto">
            <option value="">All Statuses</option>
            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="failed"    {{ request('status') === 'failed'    ? 'selected' : '' }}>Failed</option>
            <option value="refunded"  {{ request('status') === 'refunded'  ? 'selected' : '' }}>Refunded</option>
        </select>
        <button type="submit" class="add-btn">Filter</button>
        <a href="{{ route('admin.transactions.index') }}" style="font-size:0.85rem;color:var(--muted);align-self:center;">Reset</a>
    </form>

    @if($transactions->isEmpty())
        <div class="admin-empty">No transactions found.</div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $tx)
                    @php
                        $badgeMap = [
                            'completed' => 'badge-green',
                            'pending'   => 'badge-amber',
                            'failed'    => 'badge-red',
                            'refunded'  => 'badge-blue',
                        ];
                        $badge = $badgeMap[$tx->status] ?? 'badge-gray';
                    @endphp
                    <tr>
                        <td style="font-family:monospace;font-size:12px;">{{ $tx->transaction_id ?? '—' }}</td>
                        <td style="font-weight:700;">#{{ $tx->order_id }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $tx->customer_name }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ $tx->customer_email }}</div>
                        </td>
                        <td style="font-size:12px;">{{ ucfirst($tx->payment_method ?? '—') }}</td>
                        <td style="font-weight:700;">${{ number_format($tx->amount, 2) }}</td>
                        <td><span class="badge {{ $badge }}">{{ ucfirst($tx->status) }}</span></td>
                        <td style="white-space:nowrap;font-size:12px;">{{ \Carbon\Carbon::parse($tx->created_at)->format('M d, Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
