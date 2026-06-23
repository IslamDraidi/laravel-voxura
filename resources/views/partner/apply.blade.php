<x-layout title="{{ __('general.become_partner') }}" mainClass="full-width">

{{-- ── HERO BANNER ─────────────────────────────────────────────────────────── --}}
<div class="pa-hero">
  <div class="pa-hero-inner">
    <div class="pa-hero-left">
      <span class="pa-hero-badge">Partner Program</span>
      <h1 class="pa-hero-title">{{ __('general.become_partner') }}</h1>
      <p class="pa-hero-sub">Sell your fashion products to thousands of customers with 3D visualization</p>
    </div>
    <div class="pa-hero-right">
      <span class="pa-stat-pill"><i class="ti ti-users"></i> 500+ Customers</span>
      <span class="pa-stat-pill"><i class="ti ti-cube"></i> 3D Technology</span>
      <span class="pa-stat-pill"><i class="ti ti-bolt"></i> Easy Setup</span>
    </div>
  </div>
</div>

{{-- ── PROGRESS BAR ─────────────────────────────────────────────────────────── --}}
<div class="pa-progress-wrap">
  <div class="pa-progress-inner">
    @include('partner.partials.progress', ['currentStep' => 1])
  </div>
</div>

{{-- ── MAIN FORM ────────────────────────────────────────────────────────────── --}}
<form method="POST" action="{{ route('partner.apply.submit') }}" enctype="multipart/form-data" id="apply-form">
@csrf

<div class="pa-columns">

  {{-- ── LEFT COLUMN ── --}}
  <div class="pa-form-col">

    @if ($errors->any())
      <div class="pa-error-banner">
        <i class="ti ti-alert-triangle"></i>
        <div>
          @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      </div>
    @endif

    {{-- Business Info --}}
    <div class="pa-card">
      <div class="pa-section-heading">
        <i class="ti ti-building"></i>
        {{ __('general.business_name') }}
      </div>

      <div class="pa-grid-2">
        <div class="pa-field">
          <label class="pa-label">{{ __('general.business_name') }} <span class="pa-req">*</span></label>
          <input type="text" name="business_name" value="{{ old('business_name') }}" required
            class="pa-input {{ $errors->has('business_name') ? 'pa-input-error' : '' }}"
            placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: عايشة للأزياء' : 'e.g. Aisha Fashion LLC' }}">
          @error('business_name')
            <span class="pa-field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</span>
          @enderror
        </div>
        <div class="pa-field">
          <label class="pa-label">{{ __('general.business_id') }}</label>
          <input type="text" name="business_id" value="{{ old('business_id') }}"
            class="pa-input"
            placeholder="{{ app()->getLocale() === 'ar' ? 'رقم السجل التجاري' : '123456789' }}">
        </div>
      </div>

      <div class="pa-field">
        <label class="pa-label">{{ __('general.business_phone') }} <span class="pa-req">*</span></label>
        <input type="text" name="business_phone" value="{{ old('business_phone') }}" required
          class="pa-input {{ $errors->has('business_phone') ? 'pa-input-error' : '' }}"
          placeholder="{{ app()->getLocale() === 'ar' ? '+970 59 000 0000' : '+970 59 000 0000' }}">
        @error('business_phone')
          <span class="pa-field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</span>
        @enderror
      </div>
    </div>

    {{-- Store Info --}}
    <div class="pa-card">
      <div class="pa-section-heading">
        <i class="ti ti-store"></i>
        {{ __('general.store_name') }}
      </div>

      <div class="pa-field">
        <label class="pa-label">{{ __('general.store_name') }} <span class="pa-req">*</span></label>
        <input type="text" name="store_name" value="{{ old('store_name') }}" required
          class="pa-input {{ $errors->has('store_name') ? 'pa-input-error' : '' }}"
          placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: بوتيك عايشة' : 'e.g. Aisha Boutique' }}">
        @error('store_name')
          <span class="pa-field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</span>
        @enderror
      </div>

      <div class="pa-field">
        <label class="pa-label">{{ __('general.description') }}</label>
        <textarea name="description" class="pa-input pa-textarea"
          placeholder="{{ app()->getLocale() === 'ar' ? 'صِف متجرك ومجموعتك...' : 'Describe your store and collection...' }}">{{ old('description') }}</textarea>
      </div>
    </div>

    {{-- Brand Assets --}}
    <div class="pa-card">
      <div class="pa-section-heading">
        <i class="ti ti-photo"></i>
        {{ __('general.brand_assets') }}
      </div>

      <div class="pa-grid-2">
        <div class="pa-field">
          <label class="pa-label">{{ __('general.store_logo') }}</label>
          <div class="pa-upload-area" id="logo-upload-area">
            <div class="upload-icon-wrap">
              <i class="ti ti-cloud-upload pa-upload-icon"></i>
              <p class="pa-upload-text">{{ app()->getLocale() === 'ar' ? 'انقر لرفع الشعار' : 'Click to upload logo' }}</p>
              <p class="pa-upload-hint">PNG / JPG · max 2 MB</p>
            </div>
            <input type="file" name="logo" id="logo-input" accept="image/*"
              class="pa-upload-input"
              onchange="previewUpload('logo-input','logo-upload-area')">
          </div>
        </div>
        <div class="pa-field">
          <label class="pa-label">{{ __('general.store_banner') }}</label>
          <div class="pa-upload-area" id="banner-upload-area">
            <div class="upload-icon-wrap">
              <i class="ti ti-cloud-upload pa-upload-icon"></i>
              <p class="pa-upload-text">{{ app()->getLocale() === 'ar' ? 'انقر لرفع اللافتة' : 'Click to upload banner' }}</p>
              <p class="pa-upload-hint">PNG / JPG · max 5 MB · 1400×400</p>
            </div>
            <input type="file" name="banner" id="banner-input" accept="image/*"
              class="pa-upload-input"
              onchange="previewUpload('banner-input','banner-upload-area')">
          </div>
        </div>
      </div>
    </div>

    {{-- Categories --}}
    <div class="pa-card">
      <div class="pa-section-heading">
        <i class="ti ti-tag"></i>
        {{ __('general.filter_women') }} / {{ __('general.filter_men') }}
      </div>

      <div id="tags-container" class="pa-tags-container">
        @foreach (old('category_tags', []) as $tag)
          <span class="pa-tag">
            {{ $tag }}
            <span class="pa-tag-remove" onclick="removeTag(this, '{{ $tag }}')">×</span>
            <input type="hidden" name="category_tags[]" value="{{ $tag }}">
          </span>
        @endforeach
      </div>

      <div class="pa-tag-input-row">
        <input type="text" id="tag-input" class="pa-input"
          placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: أزياء نسائية' : 'e.g. Women, Casual, Shoes' }}"
          onkeydown="if(event.key==='Enter'){event.preventDefault();addTag();}">
        <button type="button" class="pa-tag-add-btn" onclick="addTag()">
          {{ app()->getLocale() === 'ar' ? 'إضافة' : 'Add' }}
        </button>
      </div>
    </div>

    {{-- Social Media --}}
    <div class="pa-card">
      <div class="pa-section-heading">
        <i class="ti ti-share"></i>
        {{ __('general.follow_us') }}
      </div>

      <div class="pa-social-row">
        <div class="pa-social-icon">
          {{-- Instagram --}}
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <radialGradient id="ig-g1" cx="30%" cy="107%" r="150%">
                <stop offset="0%" stop-color="#fdf497"/>
                <stop offset="5%" stop-color="#fdf497"/>
                <stop offset="45%" stop-color="#fd5949"/>
                <stop offset="60%" stop-color="#d6249f"/>
                <stop offset="90%" stop-color="#285AEB"/>
              </radialGradient>
            </defs>
            <rect x="2" y="2" width="20" height="20" rx="5.5" fill="url(#ig-g1)"/>
            <circle cx="12" cy="12" r="4.5" stroke="#fff" stroke-width="1.8" fill="none"/>
            <circle cx="17.2" cy="6.8" r="1.1" fill="#fff"/>
          </svg>
        </div>
        <input type="text" name="social_links[instagram]" value="{{ old('social_links.instagram') }}"
          class="pa-input" placeholder="https://instagram.com/yourstore">
      </div>
      <div class="pa-social-row">
        <div class="pa-social-icon">
          {{-- TikTok --}}
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="24" height="24" rx="5.5" fill="#010101"/>
            <path d="M16.6 8.4a4.1 4.1 0 0 1-2.4-.8v5.5a3.9 3.9 0 1 1-3.9-3.9h.4v2a2 2 0 1 0 1.5 1.9V4h2a4.1 4.1 0 0 0 4 4v.4h-1.6z" fill="#fff"/>
            <path d="M16.6 8.4a4.1 4.1 0 0 1-2.4-.8v5.5a3.9 3.9 0 1 1-3.9-3.9h.4v2a2 2 0 1 0 1.5 1.9V4h2a4.1 4.1 0 0 0 4 4v.4h-1.6z" fill="none" stroke="#69C9D0" stroke-width=".3"/>
          </svg>
        </div>
        <input type="text" name="social_links[tiktok]" value="{{ old('social_links.tiktok') }}"
          class="pa-input" placeholder="https://tiktok.com/@yourstore">
      </div>
      <div class="pa-social-row">
        <div class="pa-social-icon">
          {{-- Facebook --}}
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="24" height="24" rx="5.5" fill="#1877F2"/>
            <path d="M16 12h-2.5v8h-3v-8H8.5v-3H10.5V7.5C10.5 5.6 11.6 4 14 4h2v3h-1.5c-.6 0-.5.3-.5.8V9H16l-.5 3z" fill="#fff"/>
          </svg>
        </div>
        <input type="text" name="social_links[facebook]" value="{{ old('social_links.facebook') }}"
          class="pa-input" placeholder="https://facebook.com/yourstore">
      </div>
      <div class="pa-social-row">
        <div class="pa-social-icon">
          {{-- WhatsApp --}}
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="24" height="24" rx="5.5" fill="#25D366"/>
            <path d="M12 4a8 8 0 0 0-6.9 12L4 20l4.2-1.1A8 8 0 1 0 12 4zm4.7 11.3c-.2.5-1 1-1.5 1-.4 0-.9.1-2.7-.6a9.4 9.4 0 0 1-3.7-3.3c-.4-.5-.9-1.4-.9-2.2 0-.7.4-1.1.5-1.3.2-.2.4-.2.6-.2h.4c.2 0 .4 0 .5.4l.7 1.7c.1.2 0 .4-.1.5l-.4.5c-.1.1-.2.3-.1.5a7 7 0 0 0 1.2 1.5 6.5 6.5 0 0 0 1.7 1c.2.1.4.1.5-.1l.5-.6c.1-.2.3-.2.5-.1l1.6.8c.2.1.4.2.4.4 0 .3-.1.8-.2 1z" fill="#fff"/>
          </svg>
        </div>
        <input type="text" name="social_links[whatsapp]" value="{{ old('social_links.whatsapp') }}"
          class="pa-input" placeholder="+970 59 000 0000">
      </div>
    </div>

  </div>{{-- end left col --}}

  {{-- ── RIGHT COLUMN / SIDEBAR ── --}}
  <div class="pa-sidebar">

    {{-- Card 1: Contact --}}
    <div class="pa-sidebar-card">
      <div class="pa-sb-icon-wrap">
        <i class="ti ti-headset"></i>
      </div>
      <h3 class="pa-sb-title">{{ __('general.not_sure_start') }}</h3>
      <p class="pa-sb-sub">Our team will help set up your store — completely free of charge.</p>
      <a href="{{ route('partner.contact') }}" class="pa-sb-btn">
        <i class="ti ti-message"></i> {{ __('general.contact_team') }}
      </a>
    </div>

    {{-- Card 2: Why Join --}}
    <div class="pa-sidebar-card">
      <h3 class="pa-sb-heading">{{ __('general.why_join_voxura') }}</h3>

      <div class="pa-why-row">
        <div class="pa-why-icon"><i class="ti ti-layout"></i></div>
        <div>
          <p class="pa-why-title">{{ __('general.branded_store_page') }}</p>
          <p class="pa-why-sub">Your own URL, logo, colors and collection</p>
        </div>
      </div>
      <div class="pa-why-row">
        <div class="pa-why-icon"><i class="ti ti-cube"></i></div>
        <div>
          <p class="pa-why-title">{{ __('general.visualization_3d') }}</p>
          <p class="pa-why-sub">Let customers view products in 3D before buying</p>
        </div>
      </div>
      <div class="pa-why-row">
        <div class="pa-why-icon"><i class="ti ti-shopping-cart-check"></i></div>
        <div>
          <p class="pa-why-title">{{ __('general.checkout_management') }}</p>
          <p class="pa-why-sub">Payments, orders and shipping handled for you</p>
        </div>
      </div>
    </div>

    {{-- Card 3: Trust Signals --}}
    <div class="pa-sidebar-card pa-trust-card">
      <div class="pa-trust-row"><i class="ti ti-circle-check-filled"></i> Setup takes less than 10 minutes</div>
      <div class="pa-trust-row"><i class="ti ti-circle-check-filled"></i> No technical knowledge required</div>
      <div class="pa-trust-row"><i class="ti ti-circle-check-filled"></i> Cancel anytime</div>
      <div class="pa-trust-row"><i class="ti ti-circle-check-filled"></i> {{ app()->getLocale() === 'ar' ? 'ندعم اللغة العربية بالكامل' : 'We support Arabic language' }}</div>
    </div>

  </div>{{-- end sidebar --}}

</div>{{-- end columns --}}

{{-- ── BOTTOM CTA BAR ───────────────────────────────────────────────────────── --}}
<div class="pa-bottom-bar">
  <span class="pa-bottom-label">
    {{ app()->getLocale() === 'ar' ? 'الخطوة 1 من 3 — طلب الانضمام' : 'Step 1 of 3 — Store Application' }}
  </span>
  <button type="submit" class="pa-submit-btn">
    {{ __('general.choose_plan') }}
    <i class="ti ti-arrow-right"></i>
  </button>
</div>

</form>

<style>
/* ── Base ─────────────────────────────────────────────────────────────────── */
body { background: #faf9f7; }

/* ── Hero ─────────────────────────────────────────────────────────────────── */
.pa-hero {
  background: linear-gradient(135deg,#0f0f0f 0%,#1a1a1a 100%);
  height: 264px;
  padding-top: 64px;
  display: flex;
  align-items: center;
}
.pa-hero-inner {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 32px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
}
.pa-hero-badge {
  display: inline-block;
  background: rgba(232,98,26,.15);
  border: 1px solid rgba(232,98,26,.3);
  color: #E8621A;
  border-radius: 20px;
  padding: 4px 14px;
  font-size: 12px;
  font-weight: 600;
  margin-bottom: 12px;
}
.pa-hero-title {
  font-size: 28px;
  font-weight: 800;
  color: #fff;
  margin: 0 0 8px;
  line-height: 1.2;
}
.pa-hero-sub {
  font-size: 14px;
  color: rgba(255,255,255,.5);
  margin: 0;
  max-width: 380px;
}
.pa-hero-right {
  display: flex;
  gap: 10px;
  flex-shrink: 0;
  flex-wrap: wrap;
  justify-content: flex-end;
}
.pa-stat-pill {
  background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 20px;
  padding: 6px 16px;
  font-size: 12px;
  color: rgba(255,255,255,.6);
  display: inline-flex;
  align-items: center;
  gap: 6px;
  white-space: nowrap;
}

/* ── Progress wrap ────────────────────────────────────────────────────────── */
.pa-progress-wrap {
  background: #fff;
  border-bottom: 1px solid #f0ede8;
  padding: 20px 0;
}
.pa-progress-inner {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 32px;
}
.pa-progress-inner .partner-progress { margin-bottom: 0; }

/* ── Columns ──────────────────────────────────────────────────────────────── */
.pa-columns {
  max-width: 1100px;
  margin: 36px auto 120px;
  padding: 0 32px;
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 32px;
  align-items: start;
}

/* ── Cards ────────────────────────────────────────────────────────────────── */
.pa-card {
  background: #fff;
  border: 1px solid #f0ede8;
  border-radius: 16px;
  padding: 28px;
  margin-bottom: 20px;
}

/* ── Section heading ──────────────────────────────────────────────────────── */
.pa-section-heading {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 1.2px;
  text-transform: uppercase;
  color: #E8621A;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.pa-section-heading i { font-size: 15px; }

/* ── Fields ───────────────────────────────────────────────────────────────── */
.pa-field { margin-bottom: 16px; }
.pa-field:last-child { margin-bottom: 0; }
.pa-label {
  font-size: 13px;
  font-weight: 600;
  color: #3a3a3a;
  margin-bottom: 6px;
  display: block;
}
.pa-req { color: #E8621A; }
.pa-input {
  background: #faf9f7;
  border: 1.5px solid #e8e4df;
  border-radius: 10px;
  padding: 12px 16px;
  font-size: 14px;
  color: #1a1a1a;
  font-family: inherit;
  width: 100%;
  outline: none;
  transition: border-color .2s, background .2s, box-shadow .2s;
}
.pa-input::placeholder { color: #c0bbb5; }
.pa-input:focus {
  border-color: #E8621A;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(232,98,26,.08);
}
.pa-input-error {
  border-color: #E24B4A;
  background: rgba(226,75,74,.03);
}
.pa-input-error:focus { box-shadow: 0 0 0 3px rgba(226,75,74,.08); }
.pa-textarea { height: 100px; resize: none; }
.pa-field-error {
  font-size: 12px;
  color: #E24B4A;
  margin-top: 5px;
  display: flex;
  align-items: center;
  gap: 4px;
}
.pa-field-error i { font-size: 14px; }
.pa-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

/* ── Upload areas ─────────────────────────────────────────────────────────── */
.pa-upload-area {
  border: 2px dashed #e0ddd9;
  border-radius: 12px;
  padding: 28px 16px;
  text-align: center;
  cursor: pointer;
  transition: all .2s;
  position: relative;
  background: #faf9f7;
  background-size: cover;
  background-position: center;
  min-height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.pa-upload-area:hover { border-color: #E8621A; background-color: rgba(232,98,26,.02); }
.upload-icon-wrap { pointer-events: none; }
.pa-upload-icon { font-size: 28px; color: #E8621A; display: block; margin-bottom: 8px; }
.pa-upload-text { font-size: 13px; font-weight: 600; color: #3a3a3a; margin: 0 0 4px; }
.pa-upload-hint { font-size: 11px; color: #aaa; margin: 0; }
.pa-upload-input {
  position: absolute;
  inset: 0;
  opacity: 0;
  cursor: pointer;
  width: 100%;
  height: 100%;
}

/* ── Error banner ─────────────────────────────────────────────────────────── */
.pa-error-banner {
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 12px;
  padding: 14px 18px;
  margin-bottom: 20px;
  display: flex;
  gap: 12px;
  align-items: flex-start;
  color: #dc2626;
  font-size: 14px;
}
.pa-error-banner i { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
.pa-error-banner p { margin: 0 0 4px; }
.pa-error-banner p:last-child { margin: 0; }

/* ── Category tags ────────────────────────────────────────────────────────── */
.pa-tags-container {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 12px;
  min-height: 8px;
}
.pa-tag {
  background: rgba(232,98,26,.08);
  border: 1px solid rgba(232,98,26,.2);
  border-radius: 20px;
  padding: 5px 12px;
  font-size: 13px;
  color: #E8621A;
  display: flex;
  align-items: center;
  gap: 6px;
}
.pa-tag-remove {
  color: #E8621A;
  opacity: .6;
  cursor: pointer;
  font-size: 16px;
  line-height: 1;
}
.pa-tag-remove:hover { opacity: 1; }
.pa-tag-input-row { display: flex; gap: 8px; }
.pa-tag-input-row .pa-input { flex: 1; margin-bottom: 0; }
.pa-tag-add-btn {
  background: #E8621A;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 9px 16px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  white-space: nowrap;
  font-family: inherit;
}
.pa-tag-add-btn:hover { background: #c2410c; }

/* ── Social rows ──────────────────────────────────────────────────────────── */
.pa-social-row {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 12px;
}
.pa-social-row:last-child { margin-bottom: 0; }
.pa-social-icon {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  overflow: hidden;
}
.pa-social-row .pa-input { flex: 1; margin-bottom: 0; }

/* ── Sidebar ──────────────────────────────────────────────────────────────── */
.pa-sidebar { position: sticky; top: 24px; }
.pa-sidebar-card {
  background: #fff;
  border: 1px solid #f0ede8;
  border-radius: 16px;
  padding: 24px;
  margin-bottom: 16px;
}
.pa-trust-card { background: #faf9f7; }
.pa-sb-icon-wrap {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: rgba(232,98,26,.08);
  border: 1px solid rgba(232,98,26,.15);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 14px;
  font-size: 24px;
  color: #E8621A;
}
.pa-sb-title {
  font-size: 16px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 6px;
  text-align: center;
}
.pa-sb-sub {
  font-size: 13px;
  color: #888;
  line-height: 1.6;
  margin: 0 0 18px;
  text-align: center;
}
.pa-sb-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  background: transparent;
  border: 1.5px solid #E8621A;
  color: #E8621A;
  border-radius: 10px;
  padding: 11px 24px;
  font-size: 14px;
  font-weight: 600;
  width: 100%;
  cursor: pointer;
  transition: all .15s;
  text-decoration: none;
  font-family: inherit;
}
.pa-sb-btn:hover { background: #E8621A; color: #fff; }
.pa-sb-heading {
  font-size: 15px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 16px;
}
.pa-why-row {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 14px;
}
.pa-why-row:last-child { margin-bottom: 0; }
.pa-why-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: rgba(232,98,26,.08);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  font-size: 16px;
  color: #E8621A;
}
.pa-why-title { font-size: 13px; font-weight: 600; color: #1a1a1a; margin: 0 0 2px; }
.pa-why-sub { font-size: 12px; color: #888; line-height: 1.5; margin: 0; }
.pa-trust-row {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: #555;
  margin-bottom: 10px;
}
.pa-trust-row:last-child { margin-bottom: 0; }
.pa-trust-row i { color: #1D9E75; font-size: 16px; flex-shrink: 0; }

/* ── Bottom bar ───────────────────────────────────────────────────────────── */
.pa-bottom-bar {
  position: sticky;
  bottom: 0;
  background: #fff;
  border-top: 1px solid #f0ede8;
  padding: 16px 32px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  z-index: 100;
  box-shadow: 0 -4px 20px rgba(0,0,0,.06);
}
.pa-bottom-label {
  font-size: 13px;
  color: #888;
}
.pa-submit-btn {
  background: #E8621A;
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 13px 28px;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: inherit;
  transition: background .2s;
}
.pa-submit-btn:hover { background: #c2410c; }

/* ── RTL ──────────────────────────────────────────────────────────────────── */
[dir="rtl"] .pa-hero-inner   { flex-direction: row-reverse; }
[dir="rtl"] .pa-hero-right   { justify-content: flex-start; }
[dir="rtl"] .pa-hero-sub     { max-width: none; }
[dir="rtl"] .pa-columns      { direction: rtl; }
[dir="rtl"] .pa-label        { text-align: right; }
[dir="rtl"] .pa-social-row   { flex-direction: row-reverse; }
[dir="rtl"] .pa-section-heading { flex-direction: row-reverse; }
[dir="rtl"] .pa-bottom-bar   { flex-direction: row-reverse; }
[dir="rtl"] .pa-submit-btn   { flex-direction: row-reverse; }
[dir="rtl"] .pa-sb-btn       { flex-direction: row-reverse; }
[dir="rtl"] .pa-field-error  { flex-direction: row-reverse; }
[dir="rtl"] .pa-error-banner { flex-direction: row-reverse; }
[dir="rtl"] input,
[dir="rtl"] textarea { text-align: right; direction: rtl; }

/* ── Responsive ───────────────────────────────────────────────────────────── */
@media (max-width: 900px) {
  .pa-columns { grid-template-columns: 1fr; }
  .pa-sidebar { position: static; }
  .pa-hero-right { display: none; }
  .pa-hero-title { font-size: 22px; }
}
@media (max-width: 560px) {
  .pa-columns { padding: 0 16px; margin-bottom: 100px; }
  .pa-hero-inner { padding: 0 16px; }
  .pa-progress-inner { padding: 0 16px; }
  .pa-grid-2 { grid-template-columns: 1fr; }
  .pa-bottom-bar { padding: 12px 16px; }
}
</style>

<script>
function previewUpload(inputId, areaId) {
  const input = document.getElementById(inputId);
  const area  = document.getElementById(areaId);
  if (!input.files[0]) return;
  const reader = new FileReader();
  reader.onload = (e) => {
    area.style.backgroundImage    = `url(${e.target.result})`;
    area.style.backgroundSize     = 'cover';
    area.style.backgroundPosition = 'center';
    area.style.borderStyle        = 'solid';
    area.style.borderColor        = '#E8621A';
    const wrap = area.querySelector('.upload-icon-wrap');
    if (wrap) wrap.style.display = 'none';
  };
  reader.readAsDataURL(input.files[0]);
}

function addTag() {
  const input = document.getElementById('tag-input');
  const val   = input.value.trim();
  if (!val) return;

  const container = document.getElementById('tags-container');
  const tag = document.createElement('span');
  tag.className = 'pa-tag';
  tag.innerHTML = `${val}
    <span class="pa-tag-remove" onclick="removeTag(this,'${val}')">×</span>
    <input type="hidden" name="category_tags[]" value="${val}">`;
  container.appendChild(tag);
  input.value = '';
  input.focus();
}

function removeTag(el, val) {
  el.closest('.pa-tag').remove();
}
</script>

</x-layout>
