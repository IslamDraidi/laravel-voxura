<x-admin-layout title="Add Page" section="cms" active="pages">

    <div style="max-width:820px;">
        <div class="card">
            <p class="section-title">New CMS Page</p>
            <form id="cms-create-form" method="POST" action="{{ route('admin.cms.pages.store') }}">
                @csrf

                <div class="form-grid" style="margin-bottom:14px;">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Page Title <span style="color:var(--red)">*</span></label>
                        <input type="text" name="title" class="form-input"
                               placeholder="e.g. About Us"
                               value="{{ old('title') }}" required>
                        @error('title')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">URL Key (slug)</label>
                        <div style="display:flex;align-items:center;gap:4px;">
                            <span style="color:var(--muted);font-size:13px;white-space:nowrap;">/</span>
                            <input type="text" name="slug" class="form-input"
                                   placeholder="about-us"
                                   value="{{ old('slug') }}">
                        </div>
                        <span style="font-size:11px;color:var(--muted);">Leave blank to auto-generate from title</span>
                        @error('slug')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft"  {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        @error('status')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-input"
                               value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:14px;">
                    <label class="form-label">Page Content</label>
                    <textarea name="content" class="form-textarea" style="min-height:200px;"
                              placeholder="Write your page content here (HTML supported)…">{{ old('content') }}</textarea>
                    @error('content')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-grid" style="margin-bottom:14px;">
                    <div class="form-group">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-input"
                               placeholder="SEO title (optional)"
                               value="{{ old('meta_title') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Description</label>
                        <input type="text" name="meta_description" class="form-input"
                               placeholder="SEO description (optional)"
                               value="{{ old('meta_description') }}">
                    </div>
                </div>

                <div style="display:flex;gap:8px;">
                    <button type="submit" class="add-btn">+ Create Page</button>
                    <button type="button" class="topbar-ghost" onclick="adminNavigate('{{ route('admin.cms.pages.index') }}')">Cancel</button>
                </div>
            </form>
        </div>
    </div>


<script>
(function () {
    var form = document.getElementById('cms-create-form');
    if (!form) return;
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (typeof submitForm === 'function') {
            await submitForm(form, function () {
                if (typeof adminNavigate === 'function') adminNavigate('{{ route('admin.cms.pages.index') }}');
                else window.location.href = '{{ route('admin.cms.pages.index') }}';
            });
        }
    });
}());
</script>
</x-admin-layout>
