<x-admin-layout title="{{ __('admin.banners_title') }}" section="cms" active="banners">
<style>
/* SortableJS drag behavior — unique to this page */
.drag-handle { color:#d1d5db; cursor:grab; flex-shrink:0; user-select:none; font-size:1.2rem; }
.drag-handle:active { cursor:grabbing; }
/* Banner list layout */
.banner-list { display:flex; flex-direction:column; gap:0.75rem; }
.banner-row { background:#fff; border:1px solid var(--border); border-radius:12px; padding:1rem 1.25rem; display:flex; align-items:center; gap:1.25rem; transition:box-shadow 0.2s; }
.banner-row:hover { box-shadow:0 4px 12px rgba(0,0,0,0.08); }
.banner-row.inactive { opacity:0.55; }
.banner-thumb { width:100px; height:60px; object-fit:cover; border-radius:0.5rem; flex-shrink:0; background:#f3f4f6; }
.banner-thumb-placeholder { width:100px; height:60px; border-radius:0.5rem; background:#f3f4f6; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#d1d5db; font-size:1.5rem; }
.banner-info { flex:1; min-width:0; }
.banner-title { font-family:'Playfair Display',serif; font-size:1rem; font-weight:700; color:var(--dark); }
.banner-sub { font-size:0.82rem; color:var(--muted); margin-top:0.2rem; }
.banner-meta { display:flex; gap:0.75rem; margin-top:0.4rem; flex-wrap:wrap; font-size:0.78rem; color:var(--muted); }
</style>

{{-- Add Banner Form --}}
<div class="card" style="margin-bottom:2rem;">
    <p class="section-title">{{ __('admin.add_banner') }}</p>
    <form method="POST" action="/admin/banners" enctype="multipart/form-data">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">{{ __('admin.banner_title_label') }}</label>
                <input type="text" name="title" class="form-input" placeholder="e.g. Summer Sale" required>
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('admin.banner_subtitle_label') }}</label>
                <input type="text" name="subtitle" class="form-input" placeholder="e.g. Up to 50% off">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('admin.banner_btn_text') }}</label>
                <input type="text" name="button_text" class="form-input" placeholder="e.g. Shop Now">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('admin.banner_btn_url') }}</label>
                <input type="text" name="button_url" class="form-input" placeholder="e.g. /search?q=sale">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('admin.banner_sort_order') }}</label>
                <input type="number" name="sort_order" class="form-input" value="0" min="0">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('admin.banner_image_label') }}</label>
                <input type="file" name="image" class="form-input" accept="image/*">
            </div>
        </div>
        <div style="margin-top:1.25rem;">
            <button type="submit" class="add-btn">{{ __('admin.add_banner_btn') }}</button>
        </div>
    </form>
</div>

{{-- Banner List --}}
@if($banners->isEmpty())
    <div class="admin-empty">{{ __('admin.no_banners') }}</div>
@else
    <p class="result-count" style="margin-bottom:0.75rem;">{{ __('admin.drag_to_reorder') }} · {{ $banners->count() }} banner{{ $banners->count() > 1 ? 's' : '' }}</p>
    <div class="banner-list" id="bannerList">
        @foreach($banners as $banner)
        <div class="banner-row {{ $banner->is_active ? '' : 'inactive' }}" data-id="{{ $banner->id }}">
            <span class="drag-handle" title="Drag to reorder">⠿</span>

            @if($banner->image)
                <img src="{{ asset('images/' . $banner->image) }}" alt="{{ $banner->title }}" class="banner-thumb">
            @else
                <div class="banner-thumb-placeholder">🖼</div>
            @endif

            <div class="banner-info">
                <p class="banner-title">{{ $banner->title }}</p>
                @if($banner->subtitle)
                    <p class="banner-sub">{{ $banner->subtitle }}</p>
                @endif
                <div class="banner-meta">
                    <span class="{{ $banner->is_active ? 'badge badge-green' : 'badge badge-gray' }}">
                        {{ $banner->is_active ? __('admin.banner_active_badge') : __('admin.banner_inactive_badge') }}
                    </span>
                    @if($banner->button_text)
                        <span>{{ __('admin.banner_btn_label') }} {{ $banner->button_text }}</span>
                    @endif
                    @if($banner->button_url)
                        <span>→ {{ $banner->button_url }}</span>
                    @endif
                    <span>{{ __('admin.banner_order_label') }} {{ $banner->sort_order }}</span>
                </div>
            </div>

            <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                {{-- Toggle active --}}
                <form method="POST" action="/admin/banners/{{ $banner->id }}/toggle">
                    @csrf
                    <button type="submit" class="act-btn {{ $banner->is_active ? 'green' : '' }}">
                        {{ $banner->is_active ? __('admin.toggle_active_btn') : __('admin.toggle_inactive_btn') }}
                    </button>
                </form>
                {{-- Delete --}}
                <form method="POST" action="/admin/banners/{{ $banner->id }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="act-btn red"
                            onclick="return confirm('{{ __('admin.delete_banner_confirm') }}')">{{ __('admin.delete_btn') }}</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- Drag-to-reorder (SortableJS via CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const list = document.getElementById('bannerList');
if (list) {
    Sortable.create(list, {
        handle: '.drag-handle',
        animation: 150,
        onEnd() {
            const order = [...list.querySelectorAll('.banner-row')].map(r => r.dataset.id);
            fetch('/admin/banners/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ order })
            });
        }
    });
}
</script>
</x-admin-layout>
