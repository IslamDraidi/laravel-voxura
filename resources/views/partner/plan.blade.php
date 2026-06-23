<x-layout title="{{ __('general.choose_plan') }}" mainStyle="padding-top:104px">

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

@include('partner.partials.progress', ['currentStep' => 2])

<div class="plan-wrap" x-data="{ cycle: '{{ $cycle }}' }">
  <div class="plan-header">
    <h1 class="partner-h1">{{ __('general.choose_plan') }}</h1>
    <p class="partner-sub">{{ __('general.billing_cycle') }}</p>

    {{-- Billing cycle toggle --}}
    <div class="cycle-toggle">
      <button type="button"
        class="cycle-btn"
        :class="{ active: cycle === 'monthly' }"
        @click="cycle = 'monthly'">
        {{ __('general.monthly') }}
      </button>
      <button type="button"
        class="cycle-btn"
        :class="{ active: cycle === 'yearly' }"
        @click="cycle = 'yearly'">
        {{ __('general.yearly') }}
        <span class="save-badge">{{ __('general.save_percent', ['percent' => 20]) }}</span>
      </button>
    </div>
  </div>

  <div class="plan-cards">
    @foreach ($plans as $key => $plan)
      <div class="plan-card {{ ($plan['popular'] ?? false) ? 'plan-popular' : '' }}" style="--accent: {{ $plan['color'] ?? 'var(--orange)' }}">
        @if ($plan['popular'] ?? false)
          <div class="popular-badge">{{ __('general.most_popular') }}</div>
        @endif

        <div class="plan-card-top">
          <h3 class="plan-name">{{ app()->getLocale() === 'ar' ? ($plan['name_ar'] ?? $plan['name']) : $plan['name'] }}</h3>

          @if ($plan['contact_only'] ?? false)
            <div class="plan-price">
              <span class="price-custom">{{ __('general.contact_pricing') }}</span>
            </div>
          @else
            <div class="plan-price" x-show="cycle === 'monthly'">
              <span class="price-amount">₪{{ $plan['monthly_price'] }}</span>
              <span class="price-period">/{{ __('general.monthly') }}</span>
            </div>
            <div class="plan-price" x-show="cycle === 'yearly'" x-cloak>
              <span class="price-amount">₪{{ $plan['yearly_price'] }}</span>
              <span class="price-period">/{{ __('general.yearly') }}</span>
            </div>
          @endif
        </div>

        <ul class="plan-features">
          @php
            $featureList = app()->getLocale() === 'ar'
              ? ($plan['features_ar'] ?? $plan['features'] ?? [])
              : ($plan['features'] ?? []);
          @endphp
          @foreach ($featureList as $feature)
            <li>
              <span class="feat-check">✓</span>
              {{ $feature }}
            </li>
          @endforeach
        </ul>

        @if ($plan['contact_only'] ?? false)
          <a href="{{ route('partner.contact') }}" class="btn-plan btn-plan-outline">
            {{ __('general.contact_team') }}
          </a>
        @else
          <form method="POST" action="{{ route('partner.plan.submit') }}">
            @csrf
            <input type="hidden" name="plan" value="{{ $key }}">
            <input type="hidden" name="cycle" :value="cycle">
            <button type="submit" class="btn-plan btn-plan-primary">
              {{ __('general.get_started') }}
            </button>
          </form>
        @endif
      </div>
    @endforeach
  </div>

  <div class="plan-back">
    <a href="{{ route('partner.apply') }}">← {{ __('general.application') }}</a>
  </div>
</div>

<style>
[x-cloak] { display: none !important; }
.plan-wrap { max-width: 1080px; margin: 0 auto; padding: 0 20px 60px; }
.plan-header { text-align: center; margin-bottom: 40px; }
.partner-h1 { font-size: 30px; font-weight: 800; color: var(--gray-900); margin: 0 0 8px; }
.partner-sub { color: #6b7280; margin: 0 0 24px; }
.cycle-toggle { display: inline-flex; background: #f3f4f6; border-radius: 10px; padding: 4px; gap: 4px; }
.cycle-btn { background: transparent; border: none; border-radius: 8px; padding: 8px 20px; font-size: 14px; font-weight: 600; color: #6b7280; cursor: pointer; transition: all .2s; display: flex; align-items: center; gap: 8px; }
.cycle-btn.active { background: #fff; color: var(--gray-900); box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.save-badge { background: #d1fae5; color: #065f46; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
.plan-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
.plan-card { background: #fff; border: 2px solid #e5e7eb; border-radius: 16px; padding: 28px 24px; display: flex; flex-direction: column; gap: 20px; position: relative; transition: box-shadow .2s; }
.plan-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,.1); }
.plan-popular { border-color: var(--accent); box-shadow: 0 4px 20px rgba(0,0,0,.1); }
.popular-badge { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--accent); color: #fff; font-size: 11px; font-weight: 800; padding: 4px 14px; border-radius: 20px; white-space: nowrap; text-transform: uppercase; letter-spacing: .05em; }
.plan-card-top { text-align: center; }
.plan-name { font-size: 18px; font-weight: 800; color: var(--gray-900); margin: 0 0 12px; }
.plan-price { display: flex; align-items: baseline; justify-content: center; gap: 4px; }
.price-amount { font-size: 36px; font-weight: 800; color: var(--accent); }
.price-period { font-size: 14px; color: #9ca3af; }
.price-custom { font-size: 18px; font-weight: 700; color: var(--accent); }
.plan-features { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; flex: 1; }
.plan-features li { display: flex; gap: 10px; font-size: 14px; color: #374151; }
.feat-check { color: var(--accent); font-weight: 700; flex-shrink: 0; }
.btn-plan { display: block; width: 100%; border-radius: 10px; padding: 12px; font-size: 15px; font-weight: 700; text-align: center; cursor: pointer; transition: all .2s; text-decoration: none; border: none; }
.btn-plan-primary { background: var(--accent); color: #fff; }
.btn-plan-primary:hover { opacity: .9; }
.btn-plan-outline { background: transparent; color: var(--accent); border: 2px solid var(--accent); }
.btn-plan-outline:hover { background: var(--accent); color: #fff; }
.plan-back { text-align: center; margin-top: 24px; }
.plan-back a { color: #6b7280; font-size: 14px; text-decoration: none; }
.plan-back a:hover { color: var(--orange); }
@media (max-width: 900px) {
  .plan-cards { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 560px) {
  .plan-cards { grid-template-columns: 1fr; }
}
</style>

</x-layout>
