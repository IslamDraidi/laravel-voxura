<x-layout title="{{ __('general.almost_there') }}">

<div class="success-wrap">
  <div class="success-card">
    @if (session('payment_method') === 'bank_transfer')
      <div class="success-icon">🏦</div>
      <h1>{{ __('general.almost_there') }}</h1>
      <p class="success-sub">
        {{ __('general.bank_transfer') }} — {{ __('general.confirm_transfer') }}
      </p>
      @if (session('reference'))
        <div class="reference-box">
          <span class="ref-label">{{ __('general.order_summary') }}:</span>
          <span class="ref-value">{{ session('reference') }}</span>
        </div>
      @endif
      <p class="success-note">
        Once we confirm your transfer, your store will be activated within 1 business day.
      </p>
    @else
      <div class="success-icon">🎉</div>
      <h1>{{ __('general.almost_there') }}</h1>
      <p class="success-sub">{{ __('general.pay_by_card') }} — Payment received!</p>
      <p class="success-note">Your store has been created. Complete the setup to go live.</p>
    @endif

    <div class="next-steps">
      <h3>{{ __('general.complete_to_publish') }}</h3>
      <ul>
        <li>
          <span class="step-num">1</span>
          <span>{{ __('general.upload_logo') }}</span>
        </li>
        <li>
          <span class="step-num">2</span>
          <span>{{ __('general.upload_banner') }}</span>
        </li>
        <li>
          <span class="step-num">3</span>
          <span>{{ __('general.add_5_products') }}</span>
        </li>
        <li>
          <span class="step-num">4</span>
          <span>{{ __('general.publish_store') }}</span>
        </li>
      </ul>
    </div>

    <a href="{{ route('store.dashboard') }}" class="btn-goto-dashboard">
      {{ __('general.store_dashboard') }} →
    </a>
  </div>
</div>

<style>
.success-wrap { max-width: 560px; margin: 0 auto; padding: 0 20px 60px; }
.success-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 20px; padding: 48px 40px; text-align: center; }
.success-icon { font-size: 64px; margin-bottom: 16px; }
.success-card h1 { font-size: 30px; font-weight: 800; color: var(--gray-900); margin: 0 0 10px; }
.success-sub { color: #6b7280; margin: 0 0 16px; font-size: 16px; }
.reference-box { display: inline-flex; gap: 8px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 16px; margin-bottom: 16px; }
.ref-label { font-size: 13px; color: #6b7280; }
.ref-value { font-size: 13px; font-weight: 700; color: var(--gray-900); font-family: monospace; }
.success-note { color: #6b7280; font-size: 14px; margin: 0 0 32px; }
.next-steps { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 12px; padding: 20px 24px; text-align: start; margin-bottom: 28px; }
.next-steps h3 { font-size: 14px; font-weight: 700; color: var(--orange-dark); text-transform: uppercase; letter-spacing: .04em; margin: 0 0 14px; }
.next-steps ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; }
.next-steps li { display: flex; align-items: center; gap: 12px; font-size: 14px; color: var(--gray-900); }
.step-num { width: 24px; height: 24px; background: var(--orange); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex-shrink: 0; }
.btn-goto-dashboard { display: inline-block; background: var(--orange); color: #fff; border-radius: 10px; padding: 14px 28px; font-size: 16px; font-weight: 700; text-decoration: none; transition: background .2s; }
.btn-goto-dashboard:hover { background: var(--orange-dark); }
</style>

</x-layout>
