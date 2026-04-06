<x-admin-layout title="Refunds" section="sales" active="refunds">

    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-label">Total Refunds</div>
            <div class="sc-value">{{ $stats['total_count'] }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Completed</div>
            <div class="sc-value" style="color:var(--green);">{{ $stats['completed_count'] }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Failed</div>
            <div class="sc-value red">{{ $stats['failed_count'] }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Total Refunded</div>
            <div class="sc-value red" style="font-size:1.4rem;">₪{{ number_format($stats['total_amount'], 2) }}</div>
        </div>
    </div>

    @if($refunds->isEmpty())
        <div class="admin-empty">
            <p style="font-size:2rem;margin-bottom:0.5rem;">✓</p>
            <p>No refunds issued yet.</p>
        </div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Gateway</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Issued By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($refunds as $refund)
                    @php
                        $rBadge = match($refund->status) {
                            'completed' => 'badge-green',
                            'failed' => 'badge-red',
                            default => 'badge-amber',
                        };
                    @endphp
                    <tr>
                        <td style="font-weight:700;">#{{ $refund->id }}</td>
                        <td>
                            <a href="/admin/orders/{{ $refund->order_id }}" style="font-weight:700;color:var(--orange);text-decoration:none;">
                                #{{ $refund->order_id }}
                            </a>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $refund->order?->user?->name ?? '—' }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ $refund->order?->user?->email ?? '' }}</div>
                        </td>
                        <td style="font-weight:700;color:var(--red);">₪{{ number_format($refund->amount, 2) }}</td>
                        <td>
                            <span class="badge {{ $refund->gateway === 'paypal' ? 'badge-amber' : 'badge-green' }}">
                                {{ ucfirst($refund->gateway) }}
                            </span>
                        </td>
                        <td style="font-size:0.78rem;max-width:200px;">{{ Str::limit($refund->reason, 50) }}</td>
                        <td><span class="badge {{ $rBadge }}">{{ ucfirst($refund->status) }}</span></td>
                        <td style="font-size:0.82rem;">{{ $refund->admin?->name ?? '—' }}</td>
                        <td style="white-space:nowrap;font-size:12px;">{{ $refund->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-admin-layout>
