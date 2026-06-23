<x-layout title="{{ __('general.contact_team') }}" mainClass="full-width">

{{-- ── HERO ── --}}
<section class="ct-hero">
  <div class="ct-hero-inner">
    <span class="ct-badge">Partner Support</span>
    <h1 class="ct-hero-title">{{ __('general.contact_team') }}</h1>
    <p class="ct-hero-sub">We'll set up your store for you — completely free of charge.</p>
  </div>
</section>

{{-- ── CARD ── --}}
<div class="ct-page-wrap">
  <div class="ct-card">

    {{-- Card header --}}
    <div class="ct-card-header">
      <div class="ct-icon-circle">
        <i class="ti ti-headset"></i>
      </div>
      <h2 class="ct-card-title">{{ __('general.contact_team') }}</h2>
      <p class="ct-card-sub">{{ __('general.not_sure_start') }}</p>
    </div>

    {{-- Success message --}}
    @if (session('success'))
      <div class="ct-alert-success">
        <i class="ti ti-circle-check"></i>
        {{ session('success') }}
      </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('partner.contact.submit') }}" class="ct-form">
      @csrf

      <div class="ct-field">
        <label class="ct-label">{{ __('general.contact_name_label') }} <span class="ct-req">*</span></label>
        <input
          class="ct-input @error('name') ct-input-error @enderror"
          type="text"
          name="name"
          value="{{ old('name', auth()->user()->name ?? '') }}"
          placeholder="{{ __('general.contact_name_label') }}"
          required>
        @error('name')
          <span class="ct-error"><i class="ti ti-alert-circle"></i> {{ $message }}</span>
        @enderror
      </div>

      <div class="ct-field">
        <label class="ct-label">{{ __('general.contact_email_label') }} <span class="ct-req">*</span></label>
        <input
          class="ct-input @error('email') ct-input-error @enderror"
          type="email"
          name="email"
          value="{{ old('email', auth()->user()->email ?? '') }}"
          placeholder="{{ __('general.contact_email_label') }}"
          required>
        @error('email')
          <span class="ct-error"><i class="ti ti-alert-circle"></i> {{ $message }}</span>
        @enderror
      </div>

      <div class="ct-field">
        <label class="ct-label">{{ __('general.business_phone') }} <span class="ct-req">*</span></label>
        <input
          class="ct-input @error('phone') ct-input-error @enderror"
          type="text"
          name="phone"
          value="{{ old('phone') }}"
          placeholder="+966 5X XXX XXXX"
          required>
        @error('phone')
          <span class="ct-error"><i class="ti ti-alert-circle"></i> {{ $message }}</span>
        @enderror
      </div>

      <div class="ct-field">
        <label class="ct-label">{{ __('general.contact_message_label') }}</label>
        <textarea
          class="ct-input ct-textarea @error('message') ct-input-error @enderror"
          name="message"
          placeholder="Tell us about your business and what you're looking for…">{{ old('message') }}</textarea>
        @error('message')
          <span class="ct-error"><i class="ti ti-alert-circle"></i> {{ $message }}</span>
        @enderror
      </div>

      <button type="submit" class="ct-btn-submit">
        <i class="ti ti-send"></i>
        {{ __('general.contact_send') }}
      </button>
    </form>
  </div>

  {{-- Back link --}}
  <a href="{{ route('partner.apply') }}" class="ct-back-link">
    <span class="ct-back-arrow">←</span>
    {{ __('general.application') }}
  </a>
</div>

<style>
/* ── Layout ─────────────────────────────── */
.full-width-main { background: #faf9f7; }

/* ── Hero ───────────────────────────────── */
.ct-hero {
  height: 224px;
  padding-top: 64px;
  background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
}
.ct-hero-inner { display: flex; flex-direction: column; align-items: center; gap: 10px; }
.ct-badge {
  display: inline-block;
  background: rgba(232,98,26,0.15);
  border: 1px solid rgba(232,98,26,0.35);
  color: #f97316;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: 4px 14px;
  border-radius: 20px;
}
.ct-hero-title {
  font-size: 26px;
  font-weight: 800;
  color: #ffffff;
  margin: 0;
  line-height: 1.2;
}
.ct-hero-sub {
  font-size: 14px;
  color: rgba(255,255,255,0.5);
  margin: 0;
}

/* ── Page wrap ──────────────────────────── */
.ct-page-wrap {
  max-width: 560px;
  margin: 0 auto;
  padding: 0 20px 60px;
}

/* ── Card ───────────────────────────────── */
.ct-card {
  background: #ffffff;
  border: 1px solid #f0ede8;
  border-radius: 20px;
  padding: 40px;
  box-shadow: 0 4px 24px rgba(0,0,0,0.06);
  margin-top: -32px;
  position: relative;
}

/* ── Card header ────────────────────────── */
.ct-card-header { text-align: center; margin-bottom: 32px; }
.ct-icon-circle {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: rgba(232,98,26,0.08);
  border: 1px solid rgba(232,98,26,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px;
}
.ct-icon-circle i {
  font-size: 26px;
  color: #E8621A;
}
.ct-card-title {
  font-size: 22px;
  font-weight: 800;
  color: #1a1a1a;
  margin: 0 0 6px;
}
.ct-card-sub {
  font-size: 14px;
  color: #888888;
  margin: 0;
}

/* ── Success alert ──────────────────────── */
.ct-alert-success {
  background: rgba(29,158,117,0.08);
  border: 1px solid rgba(29,158,117,0.25);
  border-radius: 10px;
  padding: 14px 16px;
  color: #0F6E56;
  font-size: 14px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.ct-alert-success i { color: #1D9E75; font-size: 18px; flex-shrink: 0; }

/* ── Form ───────────────────────────────── */
.ct-form { display: flex; flex-direction: column; }
.ct-field { display: flex; flex-direction: column; margin-bottom: 16px; }
.ct-label {
  font-size: 13px;
  font-weight: 600;
  color: #3a3a3a;
  margin-bottom: 6px;
  display: block;
}
.ct-req { color: #E8621A; }

.ct-input {
  background: #faf9f7;
  border: 1.5px solid #e8e4df;
  border-radius: 10px;
  padding: 12px 16px;
  font-size: 14px;
  color: #1a1a1a;
  font-family: inherit;
  width: 100%;
  box-sizing: border-box;
  outline: none;
  transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}
.ct-input::placeholder { color: #c0bbb5; }
.ct-input:focus {
  border-color: #E8621A;
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(232,98,26,0.08);
}
.ct-input-error {
  border-color: #E24B4A;
  background: rgba(226,75,74,0.02);
}
.ct-textarea {
  height: 120px;
  resize: none;
}

/* ── Validation error ───────────────────── */
.ct-error {
  font-size: 12px;
  color: #E24B4A;
  margin-top: 4px;
  display: flex;
  align-items: center;
  gap: 4px;
}
.ct-error i { font-size: 14px; }

/* ── Submit button ──────────────────────── */
.ct-btn-submit {
  width: 100%;
  background: #E8621A;
  color: #ffffff;
  border: none;
  border-radius: 10px;
  padding: 14px;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  font-family: inherit;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: opacity 0.15s, transform 0.15s;
  margin-top: 8px;
}
.ct-btn-submit:hover { opacity: 0.92; transform: translateY(-1px); }
.ct-btn-submit i { font-size: 16px; }

/* ── Back link ──────────────────────────── */
.ct-back-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: #888888;
  text-decoration: none;
  transition: color 0.15s;
  margin-top: 20px;
  justify-content: center;
  width: 100%;
}
.ct-back-link:hover { color: #E8621A; }

/* ── RTL ────────────────────────────────── */
[dir="rtl"] .ct-card { direction: rtl; }
[dir="rtl"] .ct-label { text-align: right; }
[dir="rtl"] .ct-input,
[dir="rtl"] .ct-textarea { text-align: right; direction: rtl; }
[dir="rtl"] .ct-back-link { flex-direction: row-reverse; }
[dir="rtl"] .ct-back-arrow::before { content: '→'; }
[dir="rtl"] .ct-back-arrow { font-size: 0; }
[dir="rtl"] .ct-back-arrow::before { font-size: 13px; }

/* ── Responsive ─────────────────────────── */
@media (max-width: 600px) {
  .ct-hero { height: 200px; }
  .ct-card { padding: 28px 20px; }
}
</style>

</x-layout>
