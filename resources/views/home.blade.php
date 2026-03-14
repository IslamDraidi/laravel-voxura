<x-layout>
    <x-slot:title>Home</x-slot:title>

    <style>
        /* ── Reset main padding for full-bleed hero ── */
        main {
            padding: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        /* ─── HERO ─────────────────────────────────── */
        .hero {
            position: relative;
            height: 100svh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000000;
            color: #ffffff;
            overflow: hidden;
        }

        /* Orange gradient overlay — from-orange-600/20 via-black to-black */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                rgba(234, 88, 12, 0.20) 0%,
                #000000 45%,
                #000000 100%);
            z-index: 0;
        }

        /* ── Content ── */
        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 0 1rem;
            max-width: 900px;
            width: 100%;

            /* Entrance animation — mirrors motion initial {opacity:0, y:30} */
            animation: heroFadeUp 1s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes heroFadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── VOXURA headline ── */
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: clamp(4rem, 14vw, 9rem); /* text-7xl md:text-9xl */
            line-height: 1;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;

            /* bg-gradient-to-r from-white via-orange-500 to-orange-600 bg-clip-text */
            background: linear-gradient(90deg, #ffffff 0%, #f97316 55%, #ea580c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;

            animation: heroFadeUp 1s 0.2s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        /* ── Subtitle ── */
        .hero-subtitle {
            font-size: clamp(1.1rem, 3vw, 1.5rem); /* text-xl md:text-2xl */
            color: #d1d5db; /* text-gray-300 */
            font-weight: 300;
            margin-bottom: 3rem;
            letter-spacing: 0.01em;

            animation: heroFadeIn 1s 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes heroFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* ── CTA button ── */
        .hero-btn-wrap {
            animation: heroFadeUp 1s 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .hero-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #ea580c;
            color: #ffffff;
            font-family: 'DM Sans', sans-serif;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 1rem 2rem;          /* px-8 py-6 */
            border-radius: 999px;        /* rounded-full */
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 24px rgba(234, 88, 12, 0.35);
        }

        .hero-btn:hover {
            background: #c2410c;
            transform: scale(1.05);      /* hover:scale-105 */
            box-shadow: 0 8px 32px rgba(234, 88, 12, 0.5);
            color: #ffffff;
        }

        .hero-btn:active { transform: scale(1.01); }

        .hero-btn svg {
            width: 1.25rem;
            height: 1.25rem;
            flex-shrink: 0;
        }

        /* ── Scroll indicator ── */
        .scroll-indicator {
            position: absolute;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;

            animation: heroFadeIn 2s 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .scroll-mouse {
            width: 1.5rem;      /* w-6 */
            height: 2.5rem;     /* h-10 */
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 999px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 0.4rem;
        }

        .scroll-dot {
            width: 0.375rem;    /* w-1.5 */
            height: 0.375rem;   /* h-1.5 */
            background: #f97316;
            border-radius: 50%;
            animation: scrollBounce 1.5s ease-in-out infinite;
        }

    /* Mirrors animate y: [0, 12, 0] */
        @keyframes scrollBounce {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(12px); }
        }
    </style>

    {{-- ─── Hero ─── --}}
    <section class="hero">

        {{-- Content --}}
        <div class="hero-content">

            <h1 class="hero-title">VOXURA</h1>

            <p class="hero-subtitle">Elevate Your Tech Experience</p>

            <div class="hero-btn-wrap">
                <a href="#products" class="hero-btn" onclick="scrollToProducts(event)">
                    {{-- ShoppingBag icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    Explore Collection
                </a>
            </div>

        </div>

        {{-- Scroll indicator --}}
        <div class="scroll-indicator">
            <div class="scroll-mouse">
                <div class="scroll-dot"></div>
            </div>
        </div>

    </section>

    {{-- ─── Products section (hero scrolls here) ─── --}}
    <section id="products">
        {{-- your products content goes here --}}
    </section>

    <script>
        function scrollToProducts(e) {
            e.preventDefault();
            const el = document.getElementById('products');
            if (el) el.scrollIntoView({ behavior: 'smooth' });
        }
    </script>

</x-layout>