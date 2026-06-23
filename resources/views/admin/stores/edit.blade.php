<x-admin-layout title="Edit Store — {{ $store->name }}" section="stores" active="stores-edit">

<style>
.edit-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:24px;margin-bottom:20px;}
.section-title{font-size:14px;font-weight:700;color:var(--dark);margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid var(--border);}
.form-group{margin-bottom:14px;}
.form-label{font-size:12px;font-weight:600;color:var(--gray-600);margin-bottom:4px;display:block;}
.form-label small{font-weight:400;color:var(--muted);}
.form-input{width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;font-size:13px;font-family:inherit;color:var(--dark);}
.form-input:focus{outline:none;border-color:var(--orange);}
textarea.form-input{resize:vertical;min-height:80px;}
.form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.media-preview{width:120px;height:80px;object-fit:cover;border-radius:8px;border:1px solid var(--border);display:block;margin-bottom:8px;}
.admin-note-box{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:10px 12px;font-size:12px;color:#92400e;margin-bottom:12px;}
.btn-save{background:var(--orange);color:#fff;border:none;border-radius:10px;padding:13px 32px;font-size:14px;font-weight:700;cursor:pointer;width:100%;margin-top:4px;}
.btn-save:hover{background:var(--orange-dark);}
.back-link{display:inline-flex;align-items:center;gap:6px;color:var(--muted);font-size:13px;text-decoration:none;margin-bottom:16px;}
.back-link:hover{color:var(--dark);}
</style>

<a href="{{ route('admin.stores.show', $store) }}" class="back-link">← Back to {{ $store->name }}</a>

<form method="POST" action="{{ route('admin.stores.update', $store) }}" enctype="multipart/form-data">
    @csrf @method('PATCH')

    {{-- Section 1: Basic Info --}}
    <div class="edit-card">
        <div class="section-title">Basic Information</div>
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Store Name</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $store->name) }}" required>
                @error('name')<div style="color:var(--red);font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Slug <small>(used in URL)</small></label>
                <input type="text" name="slug" class="form-input" value="{{ old('slug', $store->slug) }}" required>
                @error('slug')<div style="color:var(--red);font-size:11px;margin-top:3px;">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Tagline</label>
            <input type="text" name="tagline" class="form-input" value="{{ old('tagline', $store->tagline) }}" placeholder="Short tagline shown on store page">
        </div>
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-input" rows="4">{{ old('description', $store->description) }}</textarea>
        </div>
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Accent Color</label>
                <div style="display:flex;align-items:center;gap:10px;">
                    <input type="color" name="accent_color" value="{{ old('accent_color', $store->accent_color ?? '#ea580c') }}" style="width:44px;height:36px;border:1px solid var(--border);border-radius:6px;cursor:pointer;padding:2px;">
                    <span style="font-size:12px;color:var(--muted);">{{ $store->accent_color ?? '#ea580c' }}</span>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Category Tags <small>(comma-separated)</small></label>
                <input type="text" name="category_tags" class="form-input"
                    value="{{ old('category_tags', $store->category_tags ? implode(', ', $store->category_tags) : '') }}"
                    placeholder="Dresses, Jackets, Accessories">
            </div>
        </div>
    </div>

    {{-- Section 2: Media --}}
    <div class="edit-card">
        <div class="section-title">Media</div>
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Logo</label>
                @if($store->logo_path)
                    <img src="{{ asset($store->logo_path) }}" class="media-preview" alt="">
                @endif
                <input type="file" name="logo" class="form-input" accept="image/*" style="padding:6px;">
                <div style="font-size:11px;color:var(--muted);margin-top:3px;">Max 2MB. Leave empty to keep current.</div>
            </div>
            <div class="form-group">
                <label class="form-label">Banner Image</label>
                @if($store->banner_path)
                    <img src="{{ asset($store->banner_path) }}" class="media-preview" style="width:200px;height:80px;" alt="">
                @endif
                <input type="file" name="banner" class="form-input" accept="image/*" style="padding:6px;">
                <div style="font-size:11px;color:var(--muted);margin-top:3px;">Max 4MB. Leave empty to keep current.</div>
            </div>
        </div>
    </div>

    {{-- Section 3: Admin Notes --}}
    <div class="edit-card">
        <div class="section-title">Admin Notes</div>
        <div class="admin-note-box">⚠️ These notes are for internal use only and are not visible to the store owner.</div>
        <div class="form-group">
            <textarea name="admin_notes" class="form-input" rows="3" placeholder="Internal notes about this store...">{{ old('admin_notes', $store->admin_notes) }}</textarea>
        </div>
    </div>

    {{-- Section 4: Social Links --}}
    <div class="edit-card">
        <div class="section-title">Social Links</div>
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Instagram URL</label>
                <input type="url" name="social_instagram" class="form-input"
                    value="{{ old('social_instagram', $store->social_links['instagram'] ?? '') }}"
                    placeholder="https://instagram.com/...">
            </div>
            <div class="form-group">
                <label class="form-label">Facebook URL</label>
                <input type="url" name="social_facebook" class="form-input"
                    value="{{ old('social_facebook', $store->social_links['facebook'] ?? '') }}"
                    placeholder="https://facebook.com/...">
            </div>
            <div class="form-group">
                <label class="form-label">WhatsApp Number</label>
                <input type="text" name="social_whatsapp" class="form-input"
                    value="{{ old('social_whatsapp', $store->social_links['whatsapp'] ?? '') }}"
                    placeholder="+1234567890">
            </div>
            <div class="form-group">
                <label class="form-label">Website URL</label>
                <input type="url" name="social_website" class="form-input"
                    value="{{ old('social_website', $store->social_links['website'] ?? '') }}"
                    placeholder="https://...">
            </div>
        </div>
    </div>

    <button type="submit" class="btn-save">Save Changes</button>
</form>

</x-admin-layout>
