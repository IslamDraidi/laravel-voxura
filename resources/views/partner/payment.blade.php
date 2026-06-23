<x-layout title="{{ __('general.payment') }}" mainStyle="padding-top:104px">

<style>
#navbar.nav-top { background: rgba(255,255,255,0.95) !important; backdrop-filter: blur(10px); box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
#navbar.nav-top .nav-logo { color: var(--gray-900) !important; }
#navbar.nav-top .nav-links a, #navbar.nav-top .nav-cat-btn { color: var(--gray-600) !important; }
#navbar.nav-top .nav-links a:hover, #navbar.nav-top .nav-cat-btn:hover { color: var(--orange) !important; }
#navbar.nav-top .nav-icon-btn, #navbar.nav-top .nav-search-toggle { color: var(--gray-600) !important; }
#navbar.nav-top .btn-ghost { color: var(--gray-600) !important; border-color: var(--gray-300) !important; background: transparent !important; }
#navbar.nav-top .nav-user-name { color: var(--gray-700) !important; }
#navbar.nav-top .nav-chevron { color: var(--gray-400) !important; }
#navbar.nav-top .hamburger { color: var(--gray-900) !important; }
#navbar.nav-top .lang-btn { color: var(--gray-500) !important; }
#navbar.nav-top .lang-sep { color: var(--gray-300) !important; }
#navbar.nav-top .lang-btn.lang-active { color: var(--orange) !important; }
#navbar.nav-top .nav-badge { border-color: #fff !important; }
</style>

@include('partner.partials.progress', ['currentStep' => 3])

<div class="pay-wrap" x-data="{ method: 'tap' }">
  <div class="pay-form-col">
    <h1 class="partner-h1">{{ __('general.payment') }}</h1>

    @if (session('error'))
      <div class="alert-error">{{ session('error') }}</div>
    @endif

    {{-- Method tabs --}}
    <div class="method-tabs">
      <button type="button" class="method-tab" :class="{ active: method === 'tap' }" @click="method = 'tap'">
        💳 {{ __('general.pay_by_card') }}
      </button>
      <button type="button" class="method-tab" :class="{ active: method === 'bank' }" @click="method = 'bank'">
        🏦 {{ __('general.bank_transfer') }}
      </button>
    </div>

    {{-- Tap --}}
    <div x-show="method === 'tap'" x-cloak>
      <div class="method-panel">
        <p class="method-desc">You will be redirected to Tap Payments to complete your payment securely.</p>
        <form method="POST" action="{{ route('partner.payment.tap') }}">
          @csrf
          <button type="submit" class="btn-pay-primary">
            {{ __('general.pay_by_card') }} — ₪{{ number_format($price, 2) }}
          </button>
        </form>
      </div>
    </div>

    {{-- Bank Transfer --}}
    <div x-show="method === 'bank'" x-cloak>
      <div class="method-panel">
        <table class="bank-table">
          <tr>
            <th>Bank</th>
            <td>{{ $bankDetails['bank_name'] }}</td>
          </tr>
          <tr>
            <th>Account Name</th>
            <td>{{ $bankDetails['account_name'] }}</td>
          </tr>
          <tr>
            <th>Account No.</th>
            <td class="mono">{{ $bankDetails['account_number'] }}</td>
          </tr>
          <tr>
            <th>IBAN</th>
            <td class="mono">{{ $bankDetails['iban'] }}</td>
          </tr>
          <tr>
            <th>SWIFT</th>
            <td class="mono">{{ $bankDetails['swift'] }}</td>
          </tr>
          <tr>
            <th>Reference</th>
            <td class="mono ref-highlight">{{ $bankDetails['reference'] }}</td>
          </tr>
        </table>
        <p class="bank-note">Include the reference code in your transfer description so we can match your payment.</p>
        <form method="POST" action="{{ route('partner.payment.bank') }}">
          @csrf
          <button type="submit" class="btn-pay-primary">
            {{ __('general.confirm_transfer') }}
          </button>
        </form>
      </div>
    </div>

    <a href="{{ route('partner.plan') }}" class="back-link">← {{ __('general.choose_plan') }}</a>
  </div>

  <div class="pay-summary-col">
    <div class="order-summary-card">
      <h3>{{ __('general.order_summary') }}</h3>
      <div class="summary-row">
        <span>{{ ucfirst($plan) }} Plan</span>
        <span class="sum-price">₪{{ number_format($price, 2) }}</span>
      </div>
      <div class="summary-row muted">
        <span>{{ ucfirst($cycle) }}</span>
      </div>
      <hr class="sum-divider">
      <div class="summary-row total">
        <span>Total</span>
        <span>₪{{ number_format($price, 2) }}</span>
      </div>
      <p class="summary-note">
        @if ($cycle === 'yearly')
          Billed once per year. Renews {{ now()->addYear()->format('M d, Y') }}.
        @else
          Billed monthly. Renews {{ now()->addMonth()->format('M d, Y') }}.
        @endif
      </p>
    </div>

    <div class="plan-perks">
      <h4>{{ $planData['name'] }} includes:</h4>
      <ul>
        @php
            $featList = array_slice(
              app()->getLocale() === 'ar'
                ? ($planData['features_ar'] ?? $planData['features'] ?? [])
                : ($planData['features'] ?? []),
              0, 4
            );
          @endphp
          @foreach ($featList as $feat)
            <li>✓ {{ $feat }}</li>
          @endforeach
      </ul>
    </div>
  </div>
</div>

<style>
[x-cloak] { display: none !important; }
.pay-wrap { display: flex; gap: 40px; max-width: 900px; margin: 0 auto; padding: 0 20px 60px; }
.pay-form-col { flex: 1; min-width: 0; }
.pay-summary-col { width: 280px; flex-shrink: 0; }
.partner-h1 { font-size: 26px; font-weight: 800; color: var(--gray-900); margin: 0 0 24px; }
.alert-error { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px 16px; color: #dc2626; font-size: 14px; margin-bottom: 20px; }
.method-tabs { display: flex; gap: 12px; margin-bottom: 24px; }
.method-tab { flex: 1; background: #f3f4f6; border: 2px solid transparent; border-radius: 10px; padding: 14px; font-size: 15px; font-weight: 600; color: #6b7280; cursor: pointer; transition: all .2s; }
.method-tab.active { background: #fff7ed; border-color: var(--orange); color: var(--orange); }
.method-panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 16px; }
.method-desc { font-size: 14px; color: #6b7280; margin: 0 0 20px; }
.btn-pay-primary { display: block; width: 100%; background: var(--orange); color: #fff; border: none; border-radius: 10px; padding: 14px; font-size: 16px; font-weight: 700; cursor: pointer; transition: background .2s; }
.btn-pay-primary:hover { background: var(--orange-dark); }
.bank-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
.bank-table th { text-align: start; font-size: 12px; font-weight: 700; color: #9ca3af; text-transform: uppercase; padding: 8px 0; width: 40%; }
.bank-table td { font-size: 14px; color: var(--gray-900); padding: 8px 0; }
.bank-table tr { border-bottom: 1px solid #f3f4f6; }
.bank-table tr:last-child { border: none; }
.mono { font-family: monospace; font-size: 13px !important; }
.ref-highlight { color: var(--orange) !important; font-weight: 700; }
.bank-note { font-size: 13px; color: #6b7280; margin-bottom: 20px; background: #fffbf0; border-radius: 8px; padding: 10px 12px; }
.back-link { display: block; text-align: center; color: #6b7280; font-size: 14px; text-decoration: none; margin-top: 8px; }
.back-link:hover { color: var(--orange); }
.order-summary-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 16px; }
.order-summary-card h3 { font-size: 14px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: .04em; margin: 0 0 16px; }
.summary-row { display: flex; justify-content: space-between; align-items: center; font-size: 14px; color: var(--gray-900); margin-bottom: 8px; }
.summary-row.muted { color: #9ca3af; }
.summary-row.total { font-weight: 700; font-size: 16px; margin-top: 4px; }
.sum-price { font-weight: 700; }
.sum-divider { border: none; border-top: 1px solid #e5e7eb; margin: 12px 0; }
.summary-note { font-size: 12px; color: #9ca3af; margin: 12px 0 0; }
.plan-perks { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 12px; padding: 16px 20px; }
.plan-perks h4 { font-size: 13px; font-weight: 700; color: var(--orange-dark); margin: 0 0 10px; }
.plan-perks ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 6px; }
.plan-perks li { font-size: 13px; color: #374151; }
@media (max-width: 700px) {
  .pay-wrap { flex-direction: column; }
  .pay-summary-col { width: 100%; }
}
</style>

</x-layout>
