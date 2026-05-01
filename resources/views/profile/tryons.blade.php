<x-layout title="My Try-Ons">

<style>
.tryons-page { max-width: 1100px; margin: 4rem auto; padding: 2rem 1.5rem; }
.tryons-heading {
    font-family: 'Playfair Display', serif;
    font-size: 2.25rem; font-weight: 800;
    color: var(--gray-900); margin-bottom: 0.4rem;
}
.tryons-heading .accent { color: var(--orange); }
.tryons-sub { color: #6B6B6B; font-size: 0.95rem; margin-bottom: 2rem; }

.tryons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.25rem;
}

.tryon-card {
    background: #fff;
    border: 1px solid #E8E0D8;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    transition: transform 0.18s, box-shadow 0.18s;
    display: flex; flex-direction: column;
}
.tryon-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.08);
}

.tryon-thumb {
    aspect-ratio: 1;
    background: #FBF7F1;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}
.tryon-thumb img {
    width: 100%; height: 100%; object-fit: cover;
}

.tryon-body { padding: 1rem 1.1rem 1.1rem; flex: 1; display: flex; flex-direction: column; gap: 0.55rem; }
.tryon-name {
    font-weight: 700; color: var(--gray-900);
    font-size: 0.95rem; line-height: 1.3;
    text-decoration: none;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden;
}
.tryon-name:hover { color: var(--orange); }

.tryon-meta {
    font-size: 0.78rem; color: #9A9A9A; display: flex; gap: 6px; align-items: center;
}

.tryon-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.tryon-badge--ready    { background: #DCFCE7; color: #166534; }
.tryon-badge--failed   { background: #FEE2E2; color: #991B1B; }
.tryon-badge--pending,
.tryon-badge--processing { background: #FEF3C7; color: #92400E; }

.tryon-actions { display: flex; gap: 8px; margin-top: auto; }
.tryon-btn {
    flex: 1;
    padding: 8px 10px;
    border-radius: 9px;
    font-size: 0.8rem; font-weight: 600;
    text-align: center; text-decoration: none;
    cursor: pointer; border: 1.5px solid transparent;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}
.tryon-btn--view {
    background: var(--orange); color: #fff;
}
.tryon-btn--view:hover { background: var(--orange-dark); }
.tryon-btn--view[disabled] {
    background: #E8E0D8; color: #9A9A9A; cursor: not-allowed;
}
.tryon-btn--delete {
    background: transparent; color: #B91C1C;
    border-color: #FECACA;
}
.tryon-btn--delete:hover { background: #FEF2F2; }

.tryons-empty {
    text-align: center; padding: 4rem 1.5rem;
    background: #fff; border-radius: 16px; border: 1px solid #E8E0D8;
}
.tryons-empty-icon { font-size: 3.5rem; margin-bottom: 1rem; }
.tryons-empty-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem; font-weight: 700;
    color: var(--gray-900); margin-bottom: 0.4rem;
}
.tryons-empty-sub { color: #6B6B6B; margin-bottom: 1.5rem; }
.tryons-empty-btn {
    display: inline-block;
    background: var(--orange); color: #fff;
    padding: 0.7rem 1.6rem; border-radius: 999px;
    font-weight: 700; text-decoration: none;
}
.tryons-empty-btn:hover { background: var(--orange-dark); }

.tryons-pagination { margin-top: 2rem; }

/* Result modal */
#tryon-history-overlay {
    display: none;
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(26,26,26,0.7); backdrop-filter: blur(4px);
    align-items: center; justify-content: center;
    padding: 20px;
}
#tryon-history-overlay.is-open { display: flex; }
#tryon-history-modal {
    background: #fff; border-radius: 20px;
    width: 100%; max-width: 640px;
    overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
.tryon-history-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid #F2EDE6;
}
.tryon-history-header-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem; font-weight: 700;
}
.tryon-history-close {
    background: none; border: none; font-size: 26px;
    color: #6B6B6B; cursor: pointer; line-height: 1;
}
#tryon-history-viewer {
    height: 420px; background: #1A1A1A;
}
</style>

<div class="tryons-page">
    <h1 class="tryons-heading">My Virtual <span class="accent">Try-Ons</span></h1>
    <p class="tryons-sub">View, manage, and revisit your past virtual fittings.</p>

    @if($tryons->isEmpty())
        <div class="tryons-empty">
            <div class="tryons-empty-icon">👗</div>
            <div class="tryons-empty-title">No try-ons yet</div>
            <p class="tryons-empty-sub">Browse our products to virtually try one on.</p>
            <a href="/#products" class="tryons-empty-btn">Browse Products →</a>
        </div>
    @else
        <div class="tryons-grid" id="tryons-grid">
            @foreach($tryons as $tryon)
                @php
                    $product   = $tryon->product;
                    $thumb     = $product?->image ? asset('images/' . $product->image) : null;
                    $statusKey = match(true) {
                        $tryon->isReady()      => 'ready',
                        $tryon->isFailed()     => 'failed',
                        $tryon->isProcessing() => 'processing',
                        default                => 'pending',
                    };
                    $statusLabels = [
                        'ready'      => 'Ready',
                        'failed'     => 'Failed',
                        'processing' => 'Processing',
                        'pending'    => 'Queued',
                    ];
                @endphp
                <div class="tryon-card" id="tryon-card-{{ $tryon->id }}">
                    <a href="{{ $product ? route('products.show', $product->slug) : '#' }}" class="tryon-thumb">
                        @if($thumb)
                            <img src="{{ $thumb }}" alt="{{ $product?->name }}">
                        @else
                            <span style="font-size:2.5rem;">👗</span>
                        @endif
                    </a>
                    <div class="tryon-body">
                        <a href="{{ $product ? route('products.show', $product->slug) : '#' }}" class="tryon-name">
                            {{ $product?->name ?? 'Product unavailable' }}
                        </a>
                        <div>
                            <span class="tryon-badge tryon-badge--{{ $statusKey }}">
                                {{ $statusLabels[$statusKey] }}
                            </span>
                        </div>
                        <div class="tryon-meta">{{ $tryon->created_at->diffForHumans() }}</div>
                        @if($tryon->isFailed() && $tryon->error_message)
                            <div style="font-size:0.75rem;color:#B91C1C;line-height:1.4;">
                                {{ \Illuminate\Support\Str::limit($tryon->error_message, 90) }}
                            </div>
                        @endif
                        <div class="tryon-actions">
                            @if($tryon->isReady() && $tryon->getResultUrl())
                                <button type="button"
                                        class="tryon-btn tryon-btn--view"
                                        onclick="openHistoryViewer({{ $tryon->id }}, '{{ $tryon->getResultUrl() }}')">
                                    View 3D
                                </button>
                            @else
                                <button type="button" class="tryon-btn tryon-btn--view" disabled>
                                    @if($tryon->isProcessing()) Processing… @else Unavailable @endif
                                </button>
                            @endif
                            <button type="button"
                                    class="tryon-btn tryon-btn--delete"
                                    onclick="deleteHistoryTryon({{ $tryon->id }})">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="tryons-pagination">
            {{ $tryons->links() }}
        </div>
    @endif
</div>

{{-- Viewer Modal --}}
<div id="tryon-history-overlay" onclick="if (event.target === this) closeHistoryViewer()">
    <div id="tryon-history-modal">
        <div class="tryon-history-header">
            <span class="tryon-history-header-title">Your Try-On</span>
            <button type="button" class="tryon-history-close" onclick="closeHistoryViewer()">&times;</button>
        </div>
        <div id="tryon-history-viewer"></div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script src="{{ asset('js/viewer3d.js') }}?v={{ filemtime(public_path('js/viewer3d.js')) }}"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let viewerInited = false;

window.openHistoryViewer = function (tryonId, url) {
    const overlay = document.getElementById('tryon-history-overlay');
    overlay.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    if (typeof window.initViewer3D === 'function' && !viewerInited) {
        window.initViewer3D('tryon-history-viewer', url);
        viewerInited = true;
    }
};

window.closeHistoryViewer = function () {
    document.getElementById('tryon-history-overlay').classList.remove('is-open');
    document.body.style.overflow = '';
    if (viewerInited && typeof window.disposeViewer3D === 'function') {
        window.disposeViewer3D();
        viewerInited = false;
    }
};

window.deleteHistoryTryon = async function (tryonId) {
    if (!confirm('Delete this try-on permanently?')) return;
    try {
        const res = await fetch(`/tryon/${tryonId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        });
        if (res.ok) {
            const card = document.getElementById(`tryon-card-${tryonId}`);
            if (card) card.remove();
            window.showToast('Try-on deleted.', 'success');
        } else {
            window.showToast('Could not delete try-on.', 'error');
        }
    } catch (e) {
        window.showToast('Network error.', 'error');
    }
};
</script>

</x-layout>
