<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Edit Store — {{ $store->name }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #0f0f0f; color: #ffffff; height: 100vh; overflow: hidden; display: flex; flex-direction: column; }

    /* ── Top bar ── */
    .editor-topbar { height: 52px; background: #1a1a1a; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between; padding: 0 16px; flex-shrink: 0; z-index: 100; }
    .editor-topbar-left { display: flex; align-items: center; gap: 12px; }
    .editor-back-btn { width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 18px; text-decoration: none; transition: all 0.15s; }
    .editor-back-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .editor-store-name { font-size: 14px; font-weight: 600; color: #fff; }
    .editor-store-status { font-size: 11px; font-weight: 500; padding: 3px 8px; border-radius: 20px; background: rgba(29,158,117,0.15); color: #5DCAA5; border: 1px solid rgba(29,158,117,0.3); }
    .editor-topbar-center { display: flex; align-items: center; gap: 8px; }
    .preview-toggle { display: flex; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; overflow: hidden; }
    .preview-toggle-btn { padding: 6px 14px; font-size: 12px; font-weight: 500; color: rgba(255,255,255,0.5); cursor: pointer; border: none; background: none; font-family: inherit; display: flex; align-items: center; gap: 5px; transition: all 0.15s; }
    .preview-toggle-btn.active { background: rgba(255,255,255,0.1); color: #fff; }
    .editor-topbar-right { display: flex; align-items: center; gap: 10px; }
    .save-status { font-size: 12px; color: rgba(255,255,255,0.35); }
    .save-status.saved { color: #5DCAA5; }
    .save-status.unsaved { color: #E8621A; }
    .btn-preview-live { font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.6); background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 7px 14px; cursor: pointer; font-family: inherit; text-decoration: none; display: flex; align-items: center; gap: 5px; }
    .btn-save { font-size: 13px; font-weight: 700; color: #fff; background: #E8621A; border: none; border-radius: 8px; padding: 8px 20px; cursor: pointer; font-family: inherit; display: flex; align-items: center; gap: 6px; transition: opacity 0.15s; }
    .btn-save:hover { opacity: 0.9; }
    .btn-save:disabled { opacity: 0.5; cursor: not-allowed; }

    /* ── Main body ── */
    .editor-body { display: flex; flex: 1; overflow: hidden; }

    /* ── Left panel ── */
    .editor-panel { width: 320px; flex-shrink: 0; background: #1a1a1a; border-right: 1px solid rgba(255,255,255,0.08); display: flex; flex-direction: column; overflow: hidden; }
    .editor-panel-scroll { flex: 1; overflow-y: auto; padding-bottom: 40px; }
    .editor-panel-scroll::-webkit-scrollbar { width: 4px; }
    .editor-panel-scroll::-webkit-scrollbar-track { background: transparent; }
    .editor-panel-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

    /* ── Sections ── */
    .editor-section { border-bottom: 1px solid rgba(255,255,255,0.06); }
    .editor-section-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; cursor: pointer; user-select: none; transition: background 0.15s; }
    .editor-section-header:hover { background: rgba(255,255,255,0.03); }
    .editor-section-title { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.85); }
    .editor-section-title i { font-size: 16px; color: #E8621A; }
    .editor-section-chevron { font-size: 16px; color: rgba(255,255,255,0.3); transition: transform 0.2s; }
    .editor-section-header.open .editor-section-chevron { transform: rotate(180deg); }
    .editor-section-body { display: none; padding: 0 16px 16px; }
    .editor-section-body.open { display: block; }

    /* ── Fields ── */
    .field-group { margin-bottom: 14px; }
    .field-label { display: block; font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 6px; }
    .field-input { width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 9px 12px; font-size: 13px; font-family: inherit; color: #fff; outline: none; transition: border-color 0.2s; }
    .field-input::placeholder { color: rgba(255,255,255,0.25); }
    .field-input:focus { border-color: #E8621A; }
    textarea.field-input { resize: none; height: 90px; }

    /* ── Image upload ── */
    .image-upload-area { border: 1.5px dashed rgba(255,255,255,0.15); border-radius: 10px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.2s; position: relative; overflow: hidden; }
    .image-upload-area:hover { border-color: #E8621A; background: rgba(232,98,26,0.04); }
    .image-upload-preview { width: 100%; height: 80px; object-fit: cover; border-radius: 6px; margin-bottom: 8px; display: none; }
    .image-upload-preview.visible { display: block; }
    .image-upload-text { font-size: 12px; color: rgba(255,255,255,0.35); }
    .image-upload-text span { color: #E8621A; font-weight: 600; }

    /* ── Color picker ── */
    .color-picker-row { display: flex; align-items: center; gap: 10px; }
    .color-picker-input { width: 40px; height: 40px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); cursor: pointer; padding: 2px; background: none; }
    .color-hex-input { flex: 1; }

    /* ── Tags ── */
    .tags-container { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
    .tag-pill { display: flex; align-items: center; gap: 4px; background: rgba(232,98,26,0.1); border: 1px solid rgba(232,98,26,0.25); border-radius: 20px; padding: 4px 10px; font-size: 12px; color: #E8621A; }
    .tag-remove { cursor: pointer; font-size: 14px; opacity: 0.7; line-height: 1; }
    .tag-remove:hover { opacity: 1; }
    .tag-add-row { display: flex; gap: 6px; }
    .btn-add-tag { background: #E8621A; color: #fff; border: none; border-radius: 6px; padding: 7px 12px; font-size: 12px; font-weight: 600; cursor: pointer; font-family: inherit; white-space: nowrap; }

    /* ── Social ── */
    .social-row { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
    .social-icon { width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: center; font-size: 16px; color: rgba(255,255,255,0.5); flex-shrink: 0; }

    /* ── Products list ── */
    .product-list { display: flex; flex-direction: column; gap: 8px; }
    .product-item { display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 8px 10px; cursor: pointer; transition: border-color 0.15s; }
    .product-item:hover { border-color: rgba(232,98,26,0.3); }
    .product-item-img { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; background: rgba(255,255,255,0.08); flex-shrink: 0; }
    .product-item-info { flex: 1; min-width: 0; }
    .product-item-name { font-size: 12px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .product-item-meta { font-size: 11px; color: rgba(255,255,255,0.4); }
    .product-item-actions { display: flex; gap: 4px; }
    .product-action-btn { width: 26px; height: 26px; border-radius: 6px; background: rgba(255,255,255,0.06); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 13px; color: rgba(255,255,255,0.5); transition: all 0.15s; }
    .product-action-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .product-action-btn.delete:hover { background: rgba(226,75,74,0.15); color: #F09595; }
    .btn-add-product { width: 100%; background: rgba(232,98,26,0.1); border: 1.5px dashed rgba(232,98,26,0.3); border-radius: 8px; padding: 10px; font-size: 13px; font-weight: 600; color: #E8621A; cursor: pointer; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.15s; margin-top: 10px; }
    .btn-add-product:hover { background: rgba(232,98,26,0.15); }

    /* ── Preview ── */
    .editor-preview { flex: 1; background: #0f0f0f; display: flex; flex-direction: column; align-items: center; overflow: hidden; padding: 16px; gap: 12px; }
    .preview-frame-wrapper { flex: 1; width: 100%; display: flex; justify-content: center; overflow: hidden; position: relative; }
    .preview-frame-wrapper.mobile { max-width: 390px; }
    .preview-frame-wrapper.desktop { max-width: 100%; }
    .preview-iframe { width: 100%; height: 100%; border: none; border-radius: 12px; background: #fff; }
    .preview-iframe.mobile { max-width: 390px; border-radius: 24px; box-shadow: 0 0 0 8px #2a2a2a, 0 0 0 10px #333; }

    /* ── Modal ── */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 1000; display: none; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 28px; width: 520px; max-height: 85vh; overflow-y: auto; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .modal-title { font-size: 16px; font-weight: 700; color: #fff; }
    .modal-close { width: 28px; height: 28px; border-radius: 6px; background: rgba(255,255,255,0.06); border: none; cursor: pointer; color: rgba(255,255,255,0.5); font-size: 16px; display: flex; align-items: center; justify-content: center; }
    .modal-actions { display: flex; gap: 10px; margin-top: 24px; }
    .btn-modal-save { flex: 1; background: #E8621A; color: #fff; border: none; border-radius: 8px; padding: 11px; font-size: 14px; font-weight: 700; cursor: pointer; font-family: inherit; }
    .btn-modal-cancel { flex: 1; background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 11px; font-size: 14px; font-weight: 600; cursor: pointer; font-family: inherit; }

    /* ── Product images grid ── */
    .product-images-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 12px; }
    .product-image-thumb { position: relative; height: 80px; border-radius: 8px; overflow: hidden; }
    .product-image-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .product-image-delete { position: absolute; top: 4px; right: 4px; width: 20px; height: 20px; border-radius: 4px; background: rgba(226,75,74,0.9); color: #fff; border: none; cursor: pointer; font-size: 11px; display: flex; align-items: center; justify-content: center; }

    /* ── Toast ── */
    .toast { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%) translateY(100px); background: #1a1a1a; border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 12px 20px; font-size: 13px; font-weight: 500; color: #fff; display: flex; align-items: center; gap: 8px; z-index: 9999; transition: transform 0.3s ease; box-shadow: 0 8px 24px rgba(0,0,0,0.4); }
    .toast.show { transform: translateX(-50%) translateY(0); }
    .toast.success { border-color: #1D9E75; }
    .toast.success i { color: #5DCAA5; }
    .toast.error { border-color: #E24B4A; }
    .toast.error i { color: #F09595; }

    /* ── 3D button states ── */
    .btn-3d-generate { background: rgba(127,119,221,0.12); border: 1px solid rgba(127,119,221,0.3); color: #7F77DD; }
    .btn-3d-generate:hover { background: rgba(127,119,221,0.2); color: #A09AE8; }
    .btn-3d-ready { background: rgba(29,158,117,0.12); border: 1px solid rgba(29,158,117,0.3); color: #5DCAA5; }
    .btn-3d-ready:hover { background: rgba(29,158,117,0.2); }
    .btn-3d-pending { background: rgba(186,117,23,0.12); border: 1px solid rgba(186,117,23,0.3); color: #D4A44C; cursor: not-allowed; animation: pulse-pending 1.5s ease-in-out infinite; }
    @keyframes pulse-pending { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

    /* ── Credits bar ── */
    .credits-bar { display: flex; align-items: center; justify-content: space-between; background: rgba(127,119,221,0.08); border: 1px solid rgba(127,119,221,0.2); border-radius: 8px; padding: 8px 12px; margin-bottom: 12px; font-size: 12px; }
    .credits-bar-locked { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); color: rgba(255,255,255,0.3); }
    .credits-bar-left { display: flex; align-items: center; gap: 6px; }
    .credits-label { font-weight: 600; color: rgba(255,255,255,0.7); }
    .credits-bar-right { display: flex; align-items: center; gap: 6px; }
    .credits-count { font-size: 16px; font-weight: 800; color: #7F77DD; }
    .credits-sub { font-size: 11px; color: rgba(255,255,255,0.35); }
    .credits-upgrade-link { font-size: 11px; font-weight: 600; color: #E8621A; text-decoration: none; margin-left: 8px; }

    /* ── 3D Queue ── */
    .queue-item { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; padding: 10px 12px; margin-bottom: 8px; }
    .queue-item-name { font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.8); margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .queue-item-status { display: flex; align-items: center; gap: 6px; font-size: 11px; color: rgba(255,255,255,0.45); margin-bottom: 6px; }
    .queue-status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .queue-status-dot.queue-status-queued { background: #D4A44C; }
    .queue-status-dot.queue-status-processing { background: #7F77DD; animation: pulse-pending 1s ease-in-out infinite; }
    .queue-status-dot.queue-status-ready { background: #5DCAA5; }
    .queue-status-dot.queue-status-failed { background: #F09595; }
    .queue-progress-bar { height: 3px; background: rgba(255,255,255,0.08); border-radius: 3px; overflow: hidden; }
    .queue-progress-fill { height: 100%; border-radius: 3px; transition: width 0.5s ease; }
    .queue-progress-queued { background: #D4A44C; width: 20%; }
    .queue-progress-processing { background: #7F77DD; width: 65%; animation: progress-pulse 2s ease-in-out infinite; }
    .queue-progress-ready { background: #5DCAA5; width: 100%; }
    .queue-progress-failed { background: #F09595; width: 100%; }
    @keyframes progress-pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
  </style>
</head>
<body>

<!-- Top Bar -->
<div class="editor-topbar">
  <div class="editor-topbar-left">
    <a href="{{ route('stores.show', $store) }}" class="editor-back-btn" title="View Live Store">
      <i class="ti ti-arrow-left"></i>
    </a>
    <span class="editor-store-name">{{ $store->name }}</span>
    <span class="editor-store-status">Live</span>
  </div>

  <div class="editor-topbar-center">
    <div class="preview-toggle">
      <button class="preview-toggle-btn active" id="btn-desktop" onclick="setPreviewMode('desktop')">
        <i class="ti ti-device-desktop"></i> Desktop
      </button>
      <button class="preview-toggle-btn" id="btn-mobile" onclick="setPreviewMode('mobile')">
        <i class="ti ti-device-mobile"></i> Mobile
      </button>
    </div>
  </div>

  <div class="editor-topbar-right">
    <span class="save-status" id="save-status">All changes saved</span>
    <a href="{{ route('stores.show', $store) }}" target="_blank" class="btn-preview-live">
      <i class="ti ti-external-link"></i> View Live
    </a>
    <button class="btn-save" id="btn-save" onclick="saveStore()">
      <i class="ti ti-device-floppy"></i> Save
    </button>
  </div>
</div>

<!-- Editor Body -->
<div class="editor-body">

  <!-- LEFT PANEL -->
  <div class="editor-panel">
    <div class="editor-panel-scroll">

      <!-- Header Section -->
      <div class="editor-section">
        <div class="editor-section-header open" onclick="toggleSection(this)">
          <span class="editor-section-title"><i class="ti ti-layout-navbar"></i> Header</span>
          <i class="ti ti-chevron-down editor-section-chevron"></i>
        </div>
        <div class="editor-section-body open">
          <div class="field-group">
            <label class="field-label">Store Logo</label>
            <div class="image-upload-area" onclick="document.getElementById('logo-input').click()">
              <img id="logo-preview" class="image-upload-preview {{ $store->logo_path ? 'visible' : '' }}"
                   src="{{ $store->logo_path ? asset($store->logo_path) : '' }}">
              <input type="file" id="logo-input" accept="image/*" style="display:none"
                     onchange="previewImage(this, 'logo-preview')">
              <div class="image-upload-text"><span>Upload logo</span> — PNG or JPG, max 2MB</div>
            </div>
          </div>

          <div class="field-group">
            <label class="field-label">Store Banner</label>
            <div class="image-upload-area" onclick="document.getElementById('banner-input').click()">
              <img id="banner-preview" class="image-upload-preview {{ $store->banner_path ? 'visible' : '' }}"
                   src="{{ $store->banner_path ? asset($store->banner_path) : '' }}">
              <input type="file" id="banner-input" accept="image/*" style="display:none"
                     onchange="previewImage(this, 'banner-preview')">
              <div class="image-upload-text"><span>Upload banner</span> — Recommended 1400×400px</div>
            </div>
          </div>

          <div class="field-group">
            <label class="field-label">Store Name</label>
            <input type="text" class="field-input" id="field-name" value="{{ $store->name }}"
                   oninput="markUnsaved(); updatePreview('name', this.value)">
          </div>

          <div class="field-group">
            <label class="field-label">Tagline</label>
            <input type="text" class="field-input" id="field-tagline" value="{{ $store->tagline }}"
                   placeholder="A short catchy line..."
                   oninput="markUnsaved(); updatePreview('tagline', this.value)">
          </div>
        </div>
      </div>

      <!-- Colors Section -->
      <div class="editor-section">
        <div class="editor-section-header" onclick="toggleSection(this)">
          <span class="editor-section-title"><i class="ti ti-palette"></i> Colors</span>
          <i class="ti ti-chevron-down editor-section-chevron"></i>
        </div>
        <div class="editor-section-body">
          <div class="field-group">
            <label class="field-label">Accent Color</label>
            <div class="color-picker-row">
              <input type="color" class="color-picker-input" id="color-picker"
                     value="{{ $store->accent_color ?? '#E8621A' }}"
                     oninput="markUnsaved(); document.getElementById('field-accent-color').value = this.value; updatePreview('accent_color', this.value)">
              <input type="text" class="field-input color-hex-input" id="field-accent-color"
                     value="{{ $store->accent_color ?? '#E8621A' }}" placeholder="#E8621A"
                     oninput="markUnsaved(); document.getElementById('color-picker').value = this.value">
            </div>
            <p style="font-size:11px;color:rgba(255,255,255,0.3);margin-top:6px;">
              Used on buttons and highlights on your store page only.
            </p>
          </div>
        </div>
      </div>

      <!-- About Section -->
      <div class="editor-section">
        <div class="editor-section-header" onclick="toggleSection(this)">
          <span class="editor-section-title"><i class="ti ti-info-circle"></i> About</span>
          <i class="ti ti-chevron-down editor-section-chevron"></i>
        </div>
        <div class="editor-section-body">
          <div class="field-group">
            <label class="field-label">Store Description</label>
            <textarea class="field-input" id="field-description"
                      oninput="markUnsaved(); updatePreview('description', this.value)"
                      placeholder="Tell customers about your store...">{{ $store->description }}</textarea>
          </div>

          <div class="field-group">
            <label class="field-label">Categories</label>
            <div class="tags-container" id="categories-container">
              @foreach($categories as $cat)
              <div class="tag-pill" data-tag="{{ $cat }}">
                {{ $cat }}
                <span class="tag-remove" onclick="removeTag(this.parentElement)">×</span>
              </div>
              @endforeach
            </div>
            <div class="tag-add-row">
              <input type="text" class="field-input" id="new-tag-input" placeholder="Add category..."
                     onkeydown="if(event.key==='Enter'){addTag();event.preventDefault();}">
              <button class="btn-add-tag" onclick="addTag()">Add</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Social Links Section -->
      <div class="editor-section">
        <div class="editor-section-header" onclick="toggleSection(this)">
          <span class="editor-section-title"><i class="ti ti-share"></i> Social Links</span>
          <i class="ti ti-chevron-down editor-section-chevron"></i>
        </div>
        <div class="editor-section-body">
          @php $social = $store->social_links ?? []; @endphp
          @foreach([
            ['instagram','ti-brand-instagram','Instagram URL'],
            ['facebook','ti-brand-facebook','Facebook URL'],
            ['tiktok','ti-brand-tiktok','TikTok URL'],
            ['whatsapp','ti-brand-whatsapp','WhatsApp number'],
            ['website','ti-world','Website URL'],
          ] as [$key, $icon, $placeholder])
          <div class="field-group social-row">
            <div class="social-icon"><i class="ti {{ $icon }}"></i></div>
            <input type="text" class="field-input" id="social-{{ $key }}"
                   value="{{ $social[$key] ?? '' }}" placeholder="{{ $placeholder }}"
                   oninput="markUnsaved()">
          </div>
          @endforeach
        </div>
      </div>

      {{-- Products Section --}}
      <div class="editor-section">
        <div class="editor-section-header" onclick="toggleSection(this); scrollPreviewTo('product-grid')">
          <span class="editor-section-title">
            <i class="ti ti-package"></i> Products
            <span style="font-size:11px;color:rgba(255,255,255,0.3);font-weight:400;">({{ $products->count() }})</span>
          </span>
          <i class="ti ti-chevron-down editor-section-chevron"></i>
        </div>
        <div class="editor-section-body">
          @php
            $store3d   = auth()->user()->store;
            $has3D     = $store3d?->has3DAccess() ?? false;
            $balance   = $store3d?->credits_balance ?? 0;
            $monthly3d = $store3d?->monthlyCredits() ?? 0;
          @endphp

          @if($has3D)
          <div class="credits-bar" id="credits-bar">
            <div class="credits-bar-left">
              <i class="ti ti-cube-3d-sphere" aria-hidden="true" style="color:#7F77DD;font-size:14px;"></i>
              <span class="credits-label">3D Credits</span>
            </div>
            <div class="credits-bar-right">
              <span class="credits-count" id="credits-count">{{ $balance }}</span>
              <span class="credits-sub">available@if($monthly3d > 0) · +{{ $monthly3d }}/mo@endif</span>
            </div>
          </div>
          @elseif($store3d && !$has3D)
          <div class="credits-bar credits-bar-locked">
            <i class="ti ti-lock" aria-hidden="true" style="font-size:14px;"></i>
            <span class="credits-label" style="margin-left:6px;">3D not available on Basic plan</span>
            <a href="{{ route('store.dashboard') }}" class="credits-upgrade-link">Upgrade</a>
          </div>
          @endif

          <div class="product-list" id="product-list">
            @foreach($products as $product)
            <div class="product-item" id="product-{{ $product->id }}">
              @if($product->images->first())
              <img class="product-item-img" src="{{ asset($product->images->first()->image_path) }}" alt="{{ $product->name }}">
              @else
              <div class="product-item-img" style="display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.2);">
                <i class="ti ti-photo"></i>
              </div>
              @endif
              <div class="product-item-info">
                <div class="product-item-name">{{ $product->name }}</div>
                <div class="product-item-meta">{{ config('shop.currency_symbol', '₪') }}{{ number_format($product->price, 2) }} · {{ $product->stock }} in stock</div>
              </div>
              <div class="product-item-actions">
                <button class="product-action-btn" onclick="event.stopPropagation(); openEditProduct({{ $product->id }})" title="Edit">
                  <i class="ti ti-pencil"></i>
                </button>
                <button class="product-action-btn delete" onclick="event.stopPropagation(); deleteProduct({{ $product->id }}, '{{ addslashes($product->name) }}')" title="Delete">
                  <i class="ti ti-trash"></i>
                </button>
                @if($has3D)
                <button class="product-action-btn {{ $product->is3DProcessing() ? 'btn-3d-pending' : ($product->is3DReady() ? 'btn-3d-ready' : 'btn-3d-generate') }}"
                        id="btn-3d-{{ $product->id }}"
                        onclick="event.stopPropagation(); request3D({{ $product->id }})"
                        {{ $product->is3DProcessing() ? 'disabled' : '' }}
                        title="{{ $product->is3DReady() ? 'Regenerate 3D model' : ($product->is3DProcessing() ? 'Generating...' : 'Generate 3D model') }}">
                  <i class="ti ti-cube-3d-sphere" aria-hidden="true"></i>
                </button>
                @endif
              </div>
            </div>
            @endforeach
          </div>

          <button class="btn-add-product" onclick="openAddProduct()">
            <i class="ti ti-plus"></i> Add Product
          </button>
        </div>
      </div>

      @if($has3D)
      {{-- 3D Queue Section --}}
      <div class="editor-section" id="section-3d-queue">
        <div class="editor-section-header" onclick="toggleSection(this)">
          <span class="editor-section-title">
            <i class="ti ti-cube-3d-sphere" aria-hidden="true"></i>
            3D Queue
            <span id="queue-badge" style="display:none;background:#E8621A;color:#fff;border-radius:10px;font-size:10px;font-weight:700;padding:1px 6px;margin-left:4px;">0</span>
          </span>
          <i class="ti ti-chevron-down editor-section-chevron" aria-hidden="true"></i>
        </div>
        <div class="editor-section-body" id="queue-body">
          <div id="queue-empty" style="font-size:12px;color:rgba(255,255,255,0.3);text-align:center;padding:16px 0;">
            No 3D generation in progress
          </div>
          <div id="queue-items"></div>
        </div>
      </div>
      @endif

    </div>
  </div>

  <!-- RIGHT PREVIEW -->
  <div class="editor-preview">
    <div class="preview-frame-wrapper desktop" id="preview-wrapper">
      <iframe id="preview-iframe" class="preview-iframe" src="{{ route('store.editor.preview') }}"></iframe>
    </div>
  </div>

</div>

<!-- PRODUCT MODAL -->
<div class="modal-overlay" id="product-modal">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title" id="modal-title">Add Product</span>
      <button class="modal-close" onclick="closeProductModal()"><i class="ti ti-x"></i></button>
    </div>

    <input type="hidden" id="modal-product-id">

    <div class="field-group">
      <label class="field-label">Product Images</label>
      <div class="product-images-grid" id="product-images-grid"></div>
      <div class="image-upload-area" onclick="document.getElementById('product-image-input').click()">
        <input type="file" id="product-image-input" accept="image/*" style="display:none"
               onchange="uploadProductImage(this)">
        <div class="image-upload-text"><span>Upload image</span> — PNG or JPG, max 5MB</div>
      </div>
    </div>

    <div class="field-group">
      <label class="field-label">Product Name *</label>
      <input type="text" class="field-input" id="modal-name" placeholder="e.g. Classic Hoodie">
    </div>

    <div class="field-group">
      <label class="field-label">Description</label>
      <textarea class="field-input" id="modal-description" placeholder="Describe your product..."></textarea>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
      <div class="field-group">
        <label class="field-label">Price (USD) *</label>
        <input type="number" class="field-input" id="modal-price" min="0" step="0.01" placeholder="0.00">
      </div>
      <div class="field-group">
        <label class="field-label">Stock *</label>
        <input type="number" class="field-input" id="modal-stock" min="0" placeholder="0">
      </div>
    </div>

    <div class="modal-actions">
      <button class="btn-modal-cancel" onclick="closeProductModal()">Cancel</button>
      <button class="btn-modal-save" onclick="saveProduct()">Save Product</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast">
  <i class="ti ti-circle-check" id="toast-icon"></i>
  <span id="toast-message"></span>
</div>

<script>
let hasUnsavedChanges = false;
let previewMode = 'desktop';
const SAVE_URL = '{{ route("store.editor.save") }}';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function toggleSection(header) {
  header.classList.toggle('open');
  header.nextElementSibling.classList.toggle('open');
}

function setPreviewMode(mode) {
  previewMode = mode;
  const wrapper = document.getElementById('preview-wrapper');
  const iframe  = document.getElementById('preview-iframe');
  document.getElementById('btn-desktop').classList.toggle('active', mode === 'desktop');
  document.getElementById('btn-mobile').classList.toggle('active', mode === 'mobile');
  if (mode === 'mobile') {
    wrapper.classList.replace('desktop', 'mobile');
    iframe.classList.add('mobile');
  } else {
    wrapper.classList.replace('mobile', 'desktop');
    iframe.classList.remove('mobile');
  }
}

function markUnsaved() {
  hasUnsavedChanges = true;
  const s = document.getElementById('save-status');
  s.textContent = 'Unsaved changes';
  s.className = 'save-status unsaved';
}

function markSaved() {
  hasUnsavedChanges = false;
  const s = document.getElementById('save-status');
  s.textContent = 'All changes saved';
  s.className = 'save-status saved';
}

function updatePreview(field, value) {
  const iframe = document.getElementById('preview-iframe');
  if (iframe.contentWindow) {
    iframe.contentWindow.postMessage({ type: 'voxura-update', field, value }, '*');
  }
}

function scrollPreviewTo(sectionId) {
  const iframe = document.getElementById('preview-iframe');
  if (iframe.contentWindow) {
    iframe.contentWindow.postMessage({ type: 'voxura-scroll', sectionId }, '*');
  }
}

function previewImage(input, previewId) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (e) => {
    const preview = document.getElementById(previewId);
    preview.src = e.target.result;
    preview.classList.add('visible');
  };
  reader.readAsDataURL(file);
  markUnsaved();
}

function addTag() {
  const input = document.getElementById('new-tag-input');
  const val = input.value.trim();
  if (!val) return;
  const container = document.getElementById('categories-container');
  const pill = document.createElement('div');
  pill.className = 'tag-pill';
  pill.dataset.tag = val;
  pill.innerHTML = `${val} <span class="tag-remove" onclick="removeTag(this.parentElement)">×</span>`;
  container.appendChild(pill);
  input.value = '';
  markUnsaved();
}

function removeTag(pill) { pill.remove(); markUnsaved(); }

function getCategories() {
  return Array.from(document.querySelectorAll('#categories-container .tag-pill')).map(p => p.dataset.tag);
}

async function saveStore() {
  const btn = document.getElementById('btn-save');
  btn.disabled = true;
  btn.innerHTML = '<i class="ti ti-loader-2"></i> Saving...';

  const formData = new FormData();
  formData.append('name', document.getElementById('field-name').value);
  formData.append('tagline', document.getElementById('field-tagline').value);
  formData.append('description', document.getElementById('field-description').value);
  formData.append('accent_color', document.getElementById('field-accent-color').value);

  getCategories().forEach((cat, i) => formData.append(`category_tags[${i}]`, cat));

  ['instagram','facebook','tiktok','whatsapp','website'].forEach(key => {
    const el = document.getElementById(`social-${key}`);
    if (el && el.value) formData.append(`social_links[${key}]`, el.value);
  });

  const logoInput = document.getElementById('logo-input');
  if (logoInput.files[0]) formData.append('logo', logoInput.files[0]);

  const bannerInput = document.getElementById('banner-input');
  if (bannerInput.files[0]) formData.append('banner', bannerInput.files[0]);

  try {
    const res = await fetch(SAVE_URL, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF }, body: formData });
    const data = await res.json();
    if (data.success) {
      markSaved();
      showToast(data.message, 'success');
      document.getElementById('preview-iframe').src = '{{ route("store.editor.preview") }}' + '?t=' + Date.now();
    } else {
      showToast('Save failed. Try again.', 'error');
    }
  } catch (err) {
    showToast('Network error. Try again.', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="ti ti-device-floppy"></i> Save';
  }
}

function openAddProduct() {
  document.getElementById('modal-title').textContent = 'Add Product';
  document.getElementById('modal-product-id').value = '';
  clearProductModal();
  document.getElementById('product-images-grid').innerHTML = '';
  document.getElementById('product-modal').classList.add('open');
}

async function openEditProduct(productId) {
  document.getElementById('modal-title').textContent = 'Edit Product';
  document.getElementById('modal-product-id').value = productId;

  const res = await fetch(`/store/editor/products/${productId}`, { headers: { 'X-CSRF-TOKEN': CSRF } });
  const product = await res.json();

  document.getElementById('modal-name').value = product.name || '';
  document.getElementById('modal-description').value = product.description || '';
  document.getElementById('modal-price').value = product.price || '';
  document.getElementById('modal-stock').value = product.stock || '';

  const grid = document.getElementById('product-images-grid');
  grid.innerHTML = '';
  (product.images || []).forEach(img => {
    grid.innerHTML += `<div class="product-image-thumb">
      <img src="/${img.image_path}" alt="Product image">
      <button class="product-image-delete" onclick="deleteProductImage(${productId}, ${img.id}, this.parentElement)">
        <i class="ti ti-x"></i>
      </button>
    </div>`;
  });

  document.getElementById('product-modal').classList.add('open');
}

function closeProductModal() { document.getElementById('product-modal').classList.remove('open'); }

function clearProductModal() {
  ['modal-name','modal-description','modal-price','modal-stock'].forEach(id => {
    document.getElementById(id).value = '';
  });
}

async function saveProduct() {
  const productId = document.getElementById('modal-product-id').value;
  const isEdit = !!productId;

  const body = {
    name:        document.getElementById('modal-name').value,
    description: document.getElementById('modal-description').value,
    price:       document.getElementById('modal-price').value,
    stock:       document.getElementById('modal-stock').value,
    _token:      CSRF,
  };

  const url = isEdit ? `/store/editor/products/${productId}` : '/store/editor/products';

  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify(body),
  });

  const data = await res.json();

  if (data.success) {
    closeProductModal();
    showToast('Product saved!', 'success');
    document.getElementById('preview-iframe').src = '{{ route("store.editor.preview") }}' + '?t=' + Date.now();
    setTimeout(() => location.reload(), 800);
  } else {
    showToast('Failed to save product.', 'error');
  }
}

async function deleteProduct(productId, name) {
  if (!confirm(`Delete "${name}"? This cannot be undone.`)) return;

  const res = await fetch(`/store/editor/products/${productId}`, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': CSRF },
  });

  const data = await res.json();
  if (data.success) {
    document.getElementById(`product-${productId}`).remove();
    showToast('Product deleted.', 'success');
    document.getElementById('preview-iframe').src = '{{ route("store.editor.preview") }}' + '?t=' + Date.now();
  }
}

async function uploadProductImage(input) {
  const productId = document.getElementById('modal-product-id').value;
  if (!productId || !input.files[0]) return;

  const formData = new FormData();
  formData.append('image', input.files[0]);

  const res = await fetch(`/store/editor/products/${productId}/images`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': CSRF },
    body: formData,
  });

  const data = await res.json();
  if (data.success) {
    const grid = document.getElementById('product-images-grid');
    grid.innerHTML += `<div class="product-image-thumb">
      <img src="/${data.image.image_path}" alt="Product image">
      <button class="product-image-delete" onclick="deleteProductImage(${productId}, ${data.image.id}, this.parentElement)">
        <i class="ti ti-x"></i>
      </button>
    </div>`;
    showToast('Image uploaded!', 'success');
  }
}

async function deleteProductImage(productId, imageId, thumbEl) {
  const res = await fetch(`/store/editor/products/${productId}/images/${imageId}`, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': CSRF },
  });
  const data = await res.json();
  if (data.success) thumbEl.remove();
}

function showToast(message, type = 'success') {
  const toast = document.getElementById('toast');
  const icon  = document.getElementById('toast-icon');
  document.getElementById('toast-message').textContent = message;
  toast.className = `toast ${type}`;
  icon.className  = type === 'success' ? 'ti ti-circle-check' : 'ti ti-circle-x';
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 3000);
}

window.addEventListener('beforeunload', (e) => {
  if (hasUnsavedChanges) { e.preventDefault(); e.returnValue = ''; }
});

// ── 3D Generation ───────────────────────────────────────────────────────────
const activePollers = {};
const statusLabels = {
  queued:     '⏳ Queued',
  processing: '🔄 Generating...',
  ready:      '✅ Ready',
  failed:     '❌ Failed',
};

async function request3D(productId) {
  const btn = document.getElementById(`btn-3d-${productId}`);
  if (btn) { btn.disabled = true; btn.classList.add('btn-3d-pending'); }

  try {
    const res = await fetch(`/store/products/${productId}/generate-3d`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
    });
    const data = await res.json();

    if (data.success) {
      showToast('3D generation queued!', 'success');
      updateCreditDisplay(data.balance);
      addToQueue(productId, 'queued');
      startPolling(productId);
    } else {
      showToast(data.message || 'Could not start 3D generation.', 'error');
      if (btn) { btn.disabled = false; btn.classList.remove('btn-3d-pending'); }
    }
  } catch (e) {
    showToast('Request failed. Please try again.', 'error');
    if (btn) { btn.disabled = false; btn.classList.remove('btn-3d-pending'); }
  }
}

function updateCreditDisplay(balance) {
  const el = document.getElementById('credits-count');
  if (el) el.textContent = balance;
}

function addToQueue(productId, status) {
  const container = document.getElementById('queue-items');
  const empty     = document.getElementById('queue-empty');
  if (!container) return;

  if (empty) empty.style.display = 'none';

  if (document.getElementById(`queue-item-${productId}`)) {
    updateQueueItem(productId, status);
    return;
  }

  const item = document.createElement('div');
  item.className = 'queue-item';
  item.id = `queue-item-${productId}`;
  item.innerHTML = `
    <div class="queue-item-name">Product #${productId}</div>
    <div class="queue-item-status">
      <span class="queue-status-dot dot-${status}" id="dot-${productId}"></span>
      <span id="label-${productId}">${statusLabels[status] ?? status}</span>
    </div>
    <div class="queue-progress-bar">
      <div class="queue-progress-fill fill-${status}" id="fill-${productId}"></div>
    </div>
  `;
  container.appendChild(item);

  const badge = document.getElementById('queue-badge');
  if (badge) badge.textContent = parseInt(badge.textContent || 0) + 1;
}

function updateQueueItem(productId, status) {
  const dot   = document.getElementById(`dot-${productId}`);
  const label = document.getElementById(`label-${productId}`);
  const fill  = document.getElementById(`fill-${productId}`);
  const btn   = document.getElementById(`btn-3d-${productId}`);

  if (dot)   { dot.className   = `queue-status-dot dot-${status}`; }
  if (label) { label.textContent = statusLabels[status] ?? status; }
  if (fill)  { fill.className  = `queue-progress-fill fill-${status}`; }

  if (status === 'ready') {
    if (btn) { btn.className = 'product-action-btn btn-3d-ready'; btn.disabled = false; }
  } else if (status === 'failed') {
    if (btn) { btn.className = 'product-action-btn btn-3d-generate'; btn.disabled = false; }
  }
}

function startPolling(productId) {
  if (activePollers[productId]) return;
  let attempts = 0;
  const MAX = 120;

  activePollers[productId] = setInterval(async () => {
    attempts++;
    if (attempts > MAX) {
      clearInterval(activePollers[productId]);
      delete activePollers[productId];
      updateQueueItem(productId, 'failed');
      return;
    }

    try {
      const res  = await fetch(`/store/products/${productId}/3d-status`, {
        headers: { 'Accept': 'application/json' },
      });
      const data = await res.json();

      updateQueueItem(productId, data.status);
      updateCreditDisplay(data.balance);

      if (data.status === 'ready' || data.status === 'failed') {
        clearInterval(activePollers[productId]);
        delete activePollers[productId];
      }
    } catch (_) { /* network blip — keep polling */ }
  }, 5000);
}

document.addEventListener('DOMContentLoaded', () => {
  @foreach($products as $product)
    @if($product->is3DProcessing())
      addToQueue({{ $product->id }}, '{{ $product->model3d_status }}');
      startPolling({{ $product->id }});
    @endif
  @endforeach
});
</script>
</body>
</html>
