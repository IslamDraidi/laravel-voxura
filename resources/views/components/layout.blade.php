<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' — Voxura' : 'Voxura' }}</title>

    <meta property="og:title" content="Voxura" />
    <meta property="og:description" content="A social platform built with Laravel." />
    <meta property="og:url" content="https://voxura.laravel.cloud" />

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts — same as signup page -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Reset ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        /* ── Design tokens — matching signup page ── */
        :root {
            --orange:         #ea580c;
            --orange-dark:    #c2410c;
            --orange-light:   #fff7ed;
            --orange-muted:   #fed7aa;
            --gray-50:        #f9fafb;
            --gray-100:       #f3f4f6;
            --gray-200:       #e5e7eb;
            --gray-300:       #d1d5db;
            --gray-400:       #9ca3af;
            --gray-500:       #6b7280;
            --gray-600:       #4b5563;
            --gray-900:       #111827;
            --white:          #ffffff;
            --shadow-sm:      0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md:      0 4px 16px rgba(0,0,0,0.08);
            --shadow-card:    0 25px 50px -12px rgba(0,0,0,0.12);
            --radius:         0.75rem;
        }

        /* ── Base ── */
        body {
            background: linear-gradient(135deg, var(--orange-light) 0%, var(--white) 50%, var(--gray-50) 100%);
            color: var(--gray-900);
            font-family: 'DM Sans', sans-serif;
            font-weight: 400;
            min-height: 100svh;
            display: flex;
            flex-direction: column;
        }

        /* Ambient background blobs — same as signup */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.28;
            pointer-events: none;
            z-index: 0;
        }
        body::before {
            width: 560px; height: 560px;
            background: radial-gradient(circle, #fdba74, #fb923c);
            top: -180px; left: -180px;
            animation: blob1 9s ease-in-out infinite alternate;
        }
        body::after {
            width: 420px; height: 420px;
            background: radial-gradient(circle, #fed7aa, #fbbf24);
            bottom: -130px; right: -130px;
            animation: blob2 11s ease-in-out infinite alternate;
        }

        @keyframes blob1 { to { transform: translate(45px, 35px) scale(1.07); } }
        @keyframes blob2 { to { transform: translate(-35px, -25px) scale(1.06); } }

        /* ─── NAV ─────────────────────────────────── */
        nav {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 247, 237, 0.75);
            backdrop-filter: blur(18px) saturate(160%);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            border-bottom: 1px solid rgba(234, 88, 12, 0.12);
        }

        .nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        /* Wordmark — Playfair Display to match signup heading */
        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--gray-900);
            text-decoration: none;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .nav-logo .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px; height: 30px;
            background: var(--orange);
            border-radius: 8px;
            color: var(--white);
            flex-shrink: 0;
        }

        .nav-logo .logo-icon svg { width: 15px; height: 15px; }

        /* Orange accent on VOX, matching signup "Join VOX URA" */
        .nav-logo .accent { color: var(--orange); }

        .nav-end {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-user {
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--gray-500);
            padding: 0 0.6rem;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.83rem;
            font-weight: 600;
            padding: 0.45rem 1.15rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.1s;
            white-space: nowrap;
        }
        .btn:active { transform: scale(0.97); }

        /* Ghost — thin orange border */
        .btn-ghost {
            background: transparent;
            color: var(--gray-600);
            border: 1.5px solid var(--gray-300);
        }
        .btn-ghost:hover {
            color: var(--orange);
            border-color: var(--orange);
            background: rgba(234, 88, 12, 0.05);
        }

        /* Primary — solid orange, same as signup submit */
        .btn-primary {
            background: var(--orange);
            color: var(--white);
            box-shadow: 0 2px 10px rgba(234, 88, 12, 0.22);
        }
        .btn-primary:hover {
            background: var(--orange-dark);
            box-shadow: 0 4px 18px rgba(234, 88, 12, 0.32);
        }

        /* ─── TOAST ──────────────────────────────── */
        .toast-wrap {
            position: fixed;
            top: 74px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
        }

        .toast-alert {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            background: var(--white);
            border: 1.5px solid rgba(234, 88, 12, 0.3);
            color: var(--orange);
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.65rem 1.3rem;
            border-radius: 999px;
            box-shadow: var(--shadow-md);
            white-space: nowrap;
            animation: toastFade 4s ease-in-out forwards;
        }

        .toast-alert svg { width: 15px; height: 15px; flex-shrink: 0; }

        @keyframes toastFade {
            0%   { opacity: 0; transform: translateY(-6px); }
            12%  { opacity: 1; transform: translateY(0); }
            75%  { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-6px); pointer-events: none; }
        }

        /* ─── MAIN ───────────────────────────────── */
        main {
            position: relative;
            z-index: 1;
            flex: 1;
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }

        /* ─── FOOTER ─────────────────────────────── */
        footer {
            position: relative;
            z-index: 1;
            margin-top: auto;
            background: #0a0a0a !important;
            padding: 3.5rem 1.5rem 0 !important;
        }

        .footer-inner {
            max-width: 1100px;
            margin: 0 auto;
        }

        /* 4-column grid: brand | Shop | Support | Company */
        .footer-grid {
            display: grid !important;
            grid-template-columns: 1.6fr 1fr 1fr 1fr;
            gap: 2rem;
            padding-bottom: 3rem;
        }

        /* ── Brand column ── */
        .footer-brand .footer-wordmark {
            font-family: 'Playfair Display', serif !important;
            font-weight: 800 !important;
            font-size: 1.55rem !important;
            letter-spacing: -0.02em !important;
            color: #ffffff !important;
            line-height: 1 !important;
            text-decoration: none !important;
            display: inline-block !important;
            margin-bottom: 1rem !important;
        }

        .footer-brand .footer-wordmark span {
            color: var(--orange) !important;
        }

        .footer-tagline {
            font-size: 0.875rem !important;
            color: rgba(255,255,255,0.45) !important;
            line-height: 1.6 !important;
            max-width: 220px;
            font-weight: 400 !important;
        }

        /* ── Link columns ── */
        /* Override app.css h1-h6 { font-weight: 400 !important } */
        footer .footer-col h4 {
            font-family: 'DM Sans', sans-serif !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            color: #ffffff !important;
            margin-bottom: 1.1rem !important;
            letter-spacing: 0.01em !important;
        }

        footer .footer-col ul {
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 0.65rem !important;
        }

        /* Override app.css global `a { color: var(--link) }` */
        footer .footer-col ul li a {
            font-size: 0.875rem !important;
            color: rgba(255,255,255,0.45) !important;
            text-decoration: none !important;
            transition: color 0.15s !important;
        }

        footer .footer-col ul li a:hover {
            color: #ffffff !important;
        }

        /* ── Divider ── */
        .footer-rule {
            border: none !important;
            border-top: 1px solid rgba(255,255,255,0.08) !important;
            margin: 0 !important;
            background: none !important;
            height: 0 !important;
        }

        /* ── Copyright bar ── */
        .footer-copy {
            text-align: center !important;
            font-size: 0.8rem !important;
            color: rgba(255,255,255,0.3) !important;
            padding: 1.4rem 0 !important;
            font-weight: 400 !important;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr !important;
            }
            .footer-brand {
                grid-column: 1 / -1 !important;
            }
        }

        @media (max-width: 480px) {
            .footer-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</head>

<body>

    {{-- ── Navbar ── --}}
    <nav>
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <span class="logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 10v4"/>
                        <path d="M6 6v12"/>
                        <path d="M10 3v18"/>
                        <path d="M14 6v12"/>
                        <path d="M18 10v4"/>
                        <path d="M22 12h0"/>
                    </svg>
                </span>
                <span class="accent">VOX</span>URA
            </a>

            <div class="nav-end">
                @auth
                    <span class="nav-user">{{ auth()->user()->name }}</span>
                    <form method="POST" action="/logout" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-ghost">Logout</button>
                    </form>
                @else
                    <a href="/login"    class="btn btn-ghost">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ── Success Toast ── --}}
    @if (session('success'))
        <div class="toast-wrap">
            <div class="toast-alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- ── Page Content ── --}}
    <main>
        {{ $slot }}
    </main>

    {{-- ── Footer ── --}}
    <footer>
        <div class="footer-inner">

            <div class="footer-grid">

                {{-- Brand column --}}
                <div class="footer-brand">
                    <a href="/" class="footer-wordmark"><span>VOX</span>URA</a>
                    <p class="footer-tagline">Elevating your tech experience with premium products</p>
                </div>

                {{-- Shop column --}}
                <div class="footer-col">
                    <h4>Shop</h4>
                    <ul>
                        <li><a href="#">All Products</a></li>
                        <li><a href="#">New Arrivals</a></li>
                        <li><a href="#">Best Sellers</a></li>
                        <li><a href="#">Sale</a></li>
                    </ul>
                </div>

                {{-- Support column --}}
                <div class="footer-col">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>

                {{-- Company column --}}
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Press</a></li>
                        <li><a href="#">Privacy</a></li>
                    </ul>
                </div>

            </div>

            <hr class="footer-rule">
            <p class="footer-copy">© {{ date('Y') }} Voxura. All rights reserved.</p>

        </div>
    </footer>

</body>
</html>