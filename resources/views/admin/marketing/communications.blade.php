<x-admin-layout title="Communications" section="marketing" active="communications">

    <div class="stat-grid">
        <div class="stat-card">
            <div class="sc-label">Email Templates</div>
            <div class="sc-value">{{ \App\Models\EmailTemplate::count() }}</div>
            <div class="sc-sub">Configured</div>
        </div>
        <div class="stat-card">
            <div class="sc-label">Total Customers</div>
            <div class="sc-value blue">{{ \App\Models\User::where('is_admin', false)->count() }}</div>
            <div class="sc-sub">Reachable</div>
        </div>
    </div>

    <div class="two-col">
        <div class="card">
            <p class="section-title">📧 Email Templates</p>
            <p style="font-size:13px;color:var(--muted);margin-bottom:1rem;">
                Manage transactional email templates sent to customers for orders, shipping, account events, and more.
            </p>
            <a href="{{ route('admin.email-templates.index') }}" class="add-btn">Manage Templates</a>
        </div>

        <div class="card">
            <p class="section-title">📢 Broadcast Campaigns</p>
            <p style="font-size:13px;color:var(--muted);margin-bottom:1rem;">
                Send bulk promotional emails or newsletters to customer segments. Requires an email service provider integration.
            </p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:auto;">
                <span class="badge badge-gray">Coming Soon</span>
                <span class="badge badge-gray">Requires SMTP / Mailgun / SES</span>
            </div>
        </div>

        <div class="card">
            <p class="section-title">💬 SMS Notifications</p>
            <p style="font-size:13px;color:var(--muted);margin-bottom:1rem;">
                Send order status SMS updates to customers via Twilio or similar providers.
            </p>
            <span class="badge badge-gray">Coming Soon</span>
        </div>

        <div class="card">
            <p class="section-title">🔔 Push Notifications</p>
            <p style="font-size:13px;color:var(--muted);margin-bottom:1rem;">
                Browser and mobile push notifications for promotions, back-in-stock alerts, and more.
            </p>
            <span class="badge badge-gray">Coming Soon</span>
        </div>
    </div>

</x-admin-layout>
