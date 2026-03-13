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

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:400,500,600,700,900i&family=dm-sans:300,400,500" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg:        #0e0e10;
            --surface:   #18181c;
            --border:    rgba(255,255,255,0.08);
            --text:      #f0ede8;
            --muted:     rgba(240,237,232,0.45);
            --accent:    #c8f04d;
            --accent-2:  #5b8def;
            --radius:    14px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-weight: 400;
            min-height: 100svh;
            display: flex;
            flex-direction: column;
            /* Subtle noise texture */
            background-image:
                radial-gradient(ellipse 80% 50% at 20% -10%, rgba(91,141,239,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 85% 90%,  rgba(200,240,77,0.07)  0%, transparent 55%);
        }

        /* ─── NAV ───────────────────────────────────── */
        nav {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(14,14,16,0.75);
            backdrop-filter: blur(18px) saturate(160%);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            border-bottom: 1px solid var(--border);
        }

        .nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .nav-logo {
            font-family: 'Fraunces', serif;
            font-weight: 900;
            font-style: italic;
            font-size: 1.45rem;
            color: var(--text);
            text-decoration: none;
            letter-spacing: -0.03em;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .nav-logo .bird {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px; height: 28px;
            background: var(--accent);
            border-radius: 8px;
            color: #0e0e10;
        }

        .nav-logo .bird svg { width: 16px; height: 16px; }

        .nav-end {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-user {
            font-size: 0.82rem;
            color: var(--muted);
            padding: 0 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.83rem;
            font-weight: 500;
            padding: 0.45rem 1.1rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.15s, background 0.15s, transform 0.1s;
            white-space: nowrap;
        }
        .btn:active { transform: scale(0.97); }

        .btn-ghost {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { color: var(--text); border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.05); }

        .btn-primary {
            background: var(--accent);
            color: #0e0e10;
        }
        .btn-primary:hover { opacity: 0.88; }

        /* ─── TOAST ─────────────────────────────────── */
        .toast-wrap {
            position: fixed;
            top: 72px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
        }

        .toast-alert {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--surface);
            border: 1px solid rgba(200,240,77,0.3);
            color: var(--accent);
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.65rem 1.25rem;
            border-radius: 999px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.35);
            white-space: nowrap;
            animation: fadeOut 4s ease-in-out forwards;
        }

        .toast-alert svg { width: 16px; height: 16px; flex-shrink: 0; }

        @keyframes fadeOut {
            0%, 70% { opacity: 1; transform: translateY(0); }
            100%     { opacity: 0; transform: translateY(-6px); pointer-events: none; }
        }

        /* ─── MAIN ───────────────────────────────────── */
        main {
            flex: 1;
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }

        /* ─── FOOTER ─────────────────────────────────── */
        footer {
            margin-top: auto;
            border-top: 1px solid var(--border);
            padding: 2.5rem 1.5rem 2rem;
        }

        .footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-wordmark {
            font-family: 'Fraunces', serif;
            font-style: italic;
            font-weight: 900;
            font-size: 2.5rem;
            letter-spacing: -0.04em;
            color: rgba(240,237,232,0.08);
            line-height: 1;
            user-select: none;
        }

        .footer-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.35rem;
        }

        .footer-meta p {
            font-size: 0.78rem;
            color: var(--muted);
        }

        .footer-meta a {
            color: var(--muted);
            text-decoration: none;
            transition: color 0.15s;
        }
        .footer-meta a:hover { color: var(--text); }

        .footer-dot {
            display: inline-block;
            width: 5px; height: 5px;
            background: var(--accent);
            border-radius: 50%;
            margin: 0 0.4rem;
            vertical-align: middle;
        }
    </style>
</head>

<body>

    {{-- ── Navbar ── --}}
    <nav>
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <span class="bird">
                    {{-- Sound wave / voice icon --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 10v4"/>
                        <path d="M6 6v12"/>
                        <path d="M10 3v18"/>
                        <path d="M14 6v12"/>
                        <path d="M18 10v4"/>
                        <path d="M22 12h0"/>
                    </svg>
                </span>
                Voxura
            </a>

            <div class="nav-end">
                @auth
                    <span class="nav-user">{{ auth()->user()->name }}</span>
                    <form method="POST" action="/logout" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-ghost">Logout</button>
                    </form>
                @else
                    <a href="/login" class="btn btn-ghost">Sign In</a>
                    <a href="/register" class="btn btn-primary">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ── Success Toast ── --}}
    @if (session('success'))
        <div class="toast-wrap">
            <div class="toast-alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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
            <span class="footer-wordmark">Voxura</span>
            <div class="footer-meta">
                <p>© {{ date('Y') }} Voxura &mdash; Built with Laravel <span class="footer-dot"></span> ❤️</p>
                <p>
                    <a href="#">Privacy</a>
                    <span class="footer-dot"></span>
                    <a href="#">Terms</a>
                    <span class="footer-dot"></span>
                    <a href="https://laravel.com" target="_blank">Laravel</a>
                </p>
            </div>
        </div>
    </footer>

</body>
</html>