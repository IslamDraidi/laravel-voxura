@php $heroBanners = $banners ?? collect(); @endphp

@if($heroBanners->isNotEmpty())
{{-- ── Dynamic Banner Slider ── --}}
<section class="hero hero-slider" id="heroSlider">

    @foreach($heroBanners as $i => $banner)
    <div class="hero-slide {{ $i === 0 ? 'active' : '' }}"
         style="{{ $banner->image ? 'background-image:url(' . asset('images/' . $banner->image) . ');' : '' }}">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">{{ $banner->title }}</h1>
            @if($banner->subtitle)
                <p class="hero-subtitle">{{ $banner->subtitle }}</p>
            @endif
            @if($banner->button_text && $banner->button_url)
                <a href="{{ $banner->button_url }}" class="btn-hero">
                    {{ $banner->button_text }}
                </a>
            @else
                <a href="#products" class="btn-hero">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    Explore Collection
                </a>
            @endif
        </div>
    </div>
    @endforeach

    @if($heroBanners->count() > 1)
    {{-- Dots --}}
    <div class="slider-dots">
        @foreach($heroBanners as $i => $banner)
            <button class="slider-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}" aria-label="Slide {{ $i + 1 }}"></button>
        @endforeach
    </div>

    {{-- Arrows --}}
    <button class="slider-arrow slider-prev" aria-label="Previous">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="slider-arrow slider-next" aria-label="Next">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    @endif

    <div class="scroll-indicator"><div class="scroll-dot"></div></div>
</section>

<style>
.hero-slider { position:relative; overflow:hidden; }
.hero-slide {
    position:absolute; inset:0;
    background-size:cover; background-position:center;
    opacity:0; transition:opacity 0.7s ease;
    background-color:#1a1a1a;
}
.hero-slide.active { position:relative; opacity:1; }
/* keep non-active slides stacked but hidden */
.hero-slider .hero-slide:not(.active) { position:absolute; }

.slider-dots {
    position:absolute; bottom:2rem; left:50%; transform:translateX(-50%);
    display:flex; gap:0.5rem; z-index:10;
}
.slider-dot {
    width:8px; height:8px; border-radius:50%;
    background:rgba(255,255,255,0.4); border:none; cursor:pointer;
    transition:background 0.2s, transform 0.2s; padding:0;
}
.slider-dot.active { background:#fff; transform:scale(1.3); }

.slider-arrow {
    position:absolute; top:50%; transform:translateY(-50%);
    background:rgba(255,255,255,0.15); border:1.5px solid rgba(255,255,255,0.3);
    color:#fff; width:42px; height:42px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    cursor:pointer; z-index:10; transition:background 0.2s;
    backdrop-filter:blur(4px);
}
.slider-arrow:hover { background:rgba(255,255,255,0.3); }
.slider-prev { left:1.5rem; }
.slider-next { right:1.5rem; }
</style>

<script>
(function(){
    const slider = document.getElementById('heroSlider');
    if (!slider) return;
    const slides = slider.querySelectorAll('.hero-slide');
    const dots   = slider.querySelectorAll('.slider-dot');
    if (slides.length <= 1) return;

    let cur = 0, timer;

    function goTo(n) {
        slides[cur].classList.remove('active');
        dots[cur]?.classList.remove('active');
        cur = (n + slides.length) % slides.length;
        slides[cur].classList.add('active');
        dots[cur]?.classList.add('active');
    }

    function start() { timer = setInterval(() => goTo(cur + 1), 5000); }
    function reset()  { clearInterval(timer); start(); }

    slider.querySelector('.slider-prev')?.addEventListener('click', () => { goTo(cur - 1); reset(); });
    slider.querySelector('.slider-next')?.addEventListener('click', () => { goTo(cur + 1); reset(); });
    dots.forEach((d, i) => d.addEventListener('click', () => { goTo(i); reset(); }));

    start();
})();
</script>

@else
{{-- ── Static fallback hero ── --}}
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">VOXURA</h1>
        <p class="hero-subtitle">Elevate Your Tech Experience</p>
        <a href="#products" class="btn-hero">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            Explore Collection
        </a>
    </div>
    <div class="scroll-indicator"><div class="scroll-dot"></div></div>
</section>
@endif
