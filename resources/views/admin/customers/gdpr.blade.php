<x-admin-layout title="GDPR Data Requests" section="customers" active="gdpr">

    <div class="info-banner">
        <span>🔒</span>
        <div>
            <strong>GDPR Compliance</strong><br>
            Under GDPR, customers have the right to request a copy of their personal data or request deletion of their account.
            Use this panel to manage such requests. Always process data requests within 30 days.
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-label">Total Customers</div>
            <div class="sc-value">{{ $users->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">With Orders</div>
            <div class="sc-value blue">{{ $users->where('orders_count', '>', 0)->count() }}</div>
        </div>
    </div>

    <div class="card">
        <p class="section-title">Customer Data Registry</p>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th>Orders</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td style="font-weight:600;">{{ $user->name }}</td>
                    <td style="color:var(--muted);">{{ $user->email }}</td>
                    <td style="font-size:12px;">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>{{ $user->orders_count }}</td>
                    <td style="display:flex;gap:6px;">
                        <span class="act-btn" style="cursor:default;opacity:0.6;">Export Data</span>
                        <span class="act-btn red" style="cursor:default;opacity:0.6;">Delete Account</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-top:12px;font-size:12px;color:var(--muted);">
            ⚠ Export and Account Deletion actions will be fully functional in a future update. Always confirm identity before processing a request.
        </p>
    </div>

</x-admin-layout>
