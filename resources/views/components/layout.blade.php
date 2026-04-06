<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' — Voxura' : 'Voxura' }}</title>

    <meta property="og:title" content="Voxura" />
    <meta property="og:description" content="A social platform built with Laravel." />
    <meta property="og:url" content="https://voxura.laravel.cloud" />

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; overflow-x: hidden; }
        body { overflow-x: hidden; }

        :root {
            --orange:       #ea580c;
            --orange-dark:  #c2410c;
            --orange-light: #fff7ed;
            --orange-muted: #fed7aa;
            --gray-50:      #f9fafb;
            --gray-100:     #f3f4f6;
            --gray-200:     #e5e7eb;
            --gray-300:     #d1d5db;
            --gray-400:     #9ca3af;
            --gray-500:     #6b7280;
            --gray-600:     #4b5563;
            --gray-900:     #111827;
            --white:        #ffffff;
            --shadow-sm:    0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md:    0 4px 16px rgba(0,0,0,0.08);
            --shadow-card:  0 25px 50px -12px rgba(0,0,0,0.12);
            --radius:       0.75rem;
        }

        body {
            background: linear-gradient(135deg, var(--orange-light) 0%, var(--white) 50%, var(--gray-50) 100%);
            color: var(--gray-900);
            font-family: 'DM Sans', sans-serif;
            font-weight: 400;
            min-height: 100svh;
            display: flex;
            flex-direction: column;
        }

        body::before, body::after {
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

        /* ── NAV ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 50; width: 100%;
            transition: background 0.3s, box-shadow 0.3s;
        }
        nav.nav-top     { background: transparent; }
        nav.nav-scrolled {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .nav-inner {
            max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;
            height: 64px; display: flex; align-items: center;
            justify-content: space-between; gap: 1rem; position: relative;
        }

        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-weight: 800; font-size: 1.5rem;
            text-decoration: none; letter-spacing: -0.02em;
            transition: color 0.3s;
        }
        nav.nav-top .nav-logo      { color: #fff; }
        nav.nav-scrolled .nav-logo { color: var(--gray-900); }
        .nav-logo .accent          { color: var(--orange); }

        .nav-links {
            display: flex; align-items: center; gap: 2rem;
            position: absolute; left: 50%; transform: translateX(-50%);
        }
        .nav-links a {
            font-size: 0.9rem; font-weight: 500;
            text-decoration: none; transition: color 0.3s;
        }
        nav.nav-top .nav-links a      { color: #fff; }
        nav.nav-top .nav-links a:hover { color: #fb923c; }
        nav.nav-scrolled .nav-links a  { color: var(--gray-600); }
        nav.nav-scrolled .nav-links a:hover { color: var(--orange); }

        .nav-end { display: flex; align-items: center; gap: 0.5rem; }

        .nav-user {
            font-size: 0.82rem; font-weight: 500;
            padding: 0 0.3rem; transition: color 0.3s;
        }
        nav.nav-top .nav-user      { color: #fff; }
        nav.nav-scrolled .nav-user { color: var(--gray-600); }

        /* ── Nav Icon Button ── */
        .nav-icon-btn {
            display: flex; align-items: center; justify-content: center;
            width: 38px; height: 38px; border-radius: 50%;
            text-decoration: none; background: none; border: none;
            cursor: pointer; position: relative;
            transition: color 0.3s, background 0.3s;
        }
        nav.nav-top .nav-icon-btn       { color: #fff; }
        nav.nav-top .nav-icon-btn:hover { color: #fb923c; }
        nav.nav-scrolled .nav-icon-btn  { color: var(--gray-600); }
        nav.nav-scrolled .nav-icon-btn:hover {
            color: var(--orange);
            background: rgba(234,88,12,0.08);
        }

        /* ── Badge ── */
        .nav-badge {
            position: absolute;
            top: 2px; right: 2px;
            min-width: 17px; height: 17px;
            background: var(--orange);
            color: #fff;
            font-size: 0.62rem;
            font-weight: 800;
            font-family: 'DM Sans', sans-serif;
            border-radius: 999px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px;
            border: 2px solid transparent;
            line-height: 1;
            pointer-events: none;
        }

        nav.nav-top .nav-badge {
            border-color: rgba(255,255,255,0.2);
        }
        nav.nav-scrolled .nav-badge {
            border-color: #fff;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            font-family: 'DM Sans', sans-serif; font-size: 0.83rem; font-weight: 600;
            padding: 0.45rem 1.15rem; border-radius: 999px; border: none;
            cursor: pointer; text-decoration: none;
            transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.1s;
            white-space: nowrap;
        }
        .btn:active { transform: scale(0.97); }

        .btn-ghost {
            background: transparent; color: var(--gray-600);
            border: 1.5px solid var(--gray-300);
        }
        .btn-ghost:hover {
            color: var(--orange); border-color: var(--orange);
            background: rgba(234,88,12,0.05);
        }
        nav.nav-top .btn-ghost {
            color: #fff; border-color: rgba(255,255,255,0.4);
        }
        nav.nav-top .btn-ghost:hover {
            color: #fff; border-color: #fff; background: rgba(255,255,255,0.1);
        }

        .btn-primary {
            background: var(--orange); color: var(--white);
            box-shadow: 0 2px 10px rgba(234,88,12,0.22);
        }
        .btn-primary:hover {
            background: var(--orange-dark);
            box-shadow: 0 4px 18px rgba(234,88,12,0.32);
        }

        /* ── Hamburger ── */
        .hamburger {
            display: none; background: none; border: none;
            cursor: pointer; padding: 0.4rem; transition: color 0.3s;
        }
        nav.nav-top .hamburger      { color: #fff; }
        nav.nav-scrolled .hamburger { color: var(--gray-900); }

        /* ── Mobile Menu ── */
        .mobile-menu {
            display: none; background: #fff;
            border-top: 1px solid var(--gray-200); padding: 1.5rem;
        }
        .mobile-menu.open { display: block; }

        .mobile-menu a, .mobile-menu button {
            display: block; width: 100%; text-align: left;
            padding: 0.65rem 0; color: var(--gray-700);
            font-weight: 500; text-decoration: none;
            background: none; border: none; cursor: pointer;
            font-size: 0.95rem; font-family: 'DM Sans', sans-serif;
            transition: color 0.2s;
        }
        .mobile-menu a:hover, .mobile-menu button:hover { color: var(--orange); }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hamburger { display: flex; }
        }

        /* ── Toast ── */
        .toast-wrap {
            position: fixed; top: 74px; left: 50%;
            transform: translateX(-50%); z-index: 100;
        }
        .toast-alert {
            display: flex; align-items: center; gap: 0.55rem;
            background: var(--white);
            border: 1.5px solid rgba(234,88,12,0.3);
            color: var(--orange); font-size: 0.85rem; font-weight: 600;
            padding: 0.65rem 1.3rem; border-radius: 999px;
            box-shadow: var(--shadow-md); white-space: nowrap;
            animation: toastFade 4s ease-in-out forwards;
        }
        .toast-alert svg { width: 15px; height: 15px; flex-shrink: 0; }

        @keyframes toastFade {
            0%   { opacity: 0; transform: translateY(-6px); }
            12%  { opacity: 1; transform: translateY(0); }
            75%  { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-6px); pointer-events: none; }
        }

        /* ── Main ── */
        main {
            position: relative; z-index: 1; flex: 1; width: 100%;
            max-width: 1100px; margin: 0 auto; padding: 2.5rem 1.5rem;
        }
        main.full-width { max-width: 100% !important; padding: 0 !important; }

        /* ── Categories Button in Navbar ── */
        .nav-cat-btn {
            display: inline-flex; align-items: center; gap: 4px;
            background: none; border: none; cursor: pointer;
            font-size: 0.9rem; font-weight: 500;
            font-family: 'DM Sans', sans-serif; padding: 0;
            transition: color 0.3s;
        }
        nav.nav-top      .nav-cat-btn       { color: #fff; }
        nav.nav-top      .nav-cat-btn:hover { color: #fb923c; }
        nav.nav-scrolled .nav-cat-btn       { color: var(--gray-600); }
        nav.nav-scrolled .nav-cat-btn:hover { color: var(--orange); }

        .nav-cat-arrow {
            transition: transform 0.18s ease;
            flex-shrink: 0;
        }
        .nav-cat-arrow.rotated { transform: rotate(180deg); }

        /* ── Mega Dropdown ── */
        .nav-mega {
            position: absolute;
            top: 64px; left: 0; right: 0;
            z-index: 48;
            background: #fff;
            border-top: 2px solid #E8621A;
            box-shadow: 0 8px 40px rgba(0,0,0,0.13);
        }
        /* slide-down enter/leave via x-transition custom classes */
        .mega-enter        { transition: opacity 0.18s ease, transform 0.18s ease; }
        .mega-enter-start  { opacity: 0; transform: translateY(-6px); }
        .mega-enter-end    { opacity: 1; transform: translateY(0); }
        .mega-leave        { transition: opacity 0.12s ease, transform 0.12s ease; }
        .mega-leave-start  { opacity: 1; transform: translateY(0); }
        .mega-leave-end    { opacity: 0; transform: translateY(-6px); }

        .nav-mega-inner {
            max-width: 1200px; margin: 0 auto;
            padding: 1.75rem 1.5rem 1.25rem;
        }

        .nav-mega-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 0.25rem 2.5rem;
            margin-bottom: 1.25rem;
        }

        .nav-mega-group { padding-bottom: 0.5rem; }

        .nav-mega-group-title {
            font-size: 12px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.09em;
            color: #E8621A;
            border-bottom: 1px solid #F0EBE4;
            padding-bottom: 6px; margin-bottom: 8px;
            font-family: 'DM Sans', sans-serif;
        }

        .nav-mega-child {
            display: block;
            font-size: 13px; color: #4B4B4B;
            text-decoration: none; padding: 4px 0;
            font-family: 'DM Sans', sans-serif;
            transition: color 0.15s, padding-left 0.15s;
        }
        .nav-mega-child:hover { color: #E8621A; padding-left: 4px; }

        .nav-mega-footer {
            text-align: center;
            border-top: 1px solid #F0EBE4;
            padding-top: 1rem;
        }
        .nav-mega-view-all {
            font-size: 13px; font-weight: 600;
            color: #E8621A; text-decoration: none;
            font-family: 'DM Sans', sans-serif;
            transition: opacity 0.15s;
        }
        .nav-mega-view-all:hover { opacity: 0.7; }

        @media (max-width: 768px) {
            .nav-mega-grid { grid-template-columns: 1fr; gap: 0; }
            .nav-mega-inner { padding: 1rem 1.5rem; }
        }
    </style>
</head>

@php $preview = \App\Http\Middleware\AdminPreviewMode::isActive(); @endphp
<body class="{{ $preview ? 'preview-active' : '' }}">

    {{-- ── Admin Preview Banner ── --}}
    @if($preview)
    <div id="previewBanner" style="
        position: fixed; top: 0; left: 0; right: 0; z-index: 200;
        background: #E8621A; color: #fff;
        padding: 0 1.5rem;
        height: 42px;
        display: flex; align-items: center; justify-content: center; gap: 1rem;
        font-family: 'DM Sans', sans-serif; font-size: 0.85rem; font-weight: 600;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    ">
        <span>👁 You are viewing as a customer — admin preview mode. Interactive features are hidden.</span>
        <a href="{{ route('admin.preview.disable') }}" style="
            color: #fff; font-weight: 800; text-decoration: underline;
            white-space: nowrap; transition: opacity 0.15s;
        " onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
            Click here to exit
        </a>
    </div>
    <style>
        .preview-active nav { top: 42px !important; }
        .preview-active .toast-wrap { top: 116px !important; }
    </style>
    @endif

    {{-- ── Navbar ── --}}
    <nav id="navbar" class="nav-top"
         x-data="{ catOpen: false }"
         @click.outside="catOpen = false"
         @keydown.escape.window="catOpen = false">
        <div class="nav-inner">

            <a href="/" class="nav-logo"><span class="accent">VOX</span>URA</a>

            <div class="nav-links">
                {{-- Categories mega-dropdown trigger --}}
                @if(isset($navCategories) && $navCategories->isNotEmpty())
                <button class="nav-cat-btn" @click="catOpen = !catOpen" :aria-expanded="catOpen">
                    Categories
                    <svg class="nav-cat-arrow" :class="{ rotated: catOpen }"
                         xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                         viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                @endif

                <a href="/#products">Products</a>
                <a href="/search">Search</a>
                <a href="/#about">About</a>
                <a href="/#contact">Contact</a>
                @auth
                    <a href="/orders">My Orders</a>
                    @if(auth()->user()->isAdmin())
                        <a href="/admin">Admin</a>
                        <a href="/admin/orders">Orders</a>
                    @endif
                @endauth
            </div>

            <div class="nav-end">
                @auth
                    @php
                        $cartCount     = auth()->user()->cartCount();
                        $wishlistCount = auth()->user()->wishlistCount();
                    @endphp

                    {{-- Wishlist --}}
                    <a href="/wishlist" class="nav-icon-btn" title="Wishlist">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                        </svg>
                        @if($wishlistCount > 0)
                            <span class="nav-badge">{{ $wishlistCount > 99 ? '99+' : $wishlistCount }}</span>
                        @endif
                    </a>

                    {{-- Cart --}}
                    <a href="/cart" class="nav-icon-btn" title="Cart">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        @if($cartCount > 0)
                            <span class="nav-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                        @endif
                    </a>

                    {{-- Settings --}}
                    <a href="/profile" class="nav-icon-btn" title="Settings">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>

                    <span class="nav-user">{{ auth()->user()->name }}</span>

                    <form method="POST" action="/logout" style="display:inline">
                        @csrf
                        <button type="submit" class="nav-icon-btn" title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                @else
                    <a href="/login" class="btn btn-ghost">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                @endauth

                <button class="hamburger" id="hamburger" onclick="toggleMenu()">
                    <svg id="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- ── Mega Dropdown ── --}}
        @if(isset($navCategories) && $navCategories->isNotEmpty())
        <div class="nav-mega"
             x-show="catOpen"
             x-transition:enter="mega-enter"
             x-transition:enter-start="mega-enter-start"
             x-transition:enter-end="mega-enter-end"
             x-transition:leave="mega-leave"
             x-transition:leave-start="mega-leave-start"
             x-transition:leave-end="mega-leave-end"
             style="display:none;">
            <div class="nav-mega-inner">
                <div class="nav-mega-grid">
                    @foreach($navCategories as $parent)
                    <div class="nav-mega-group">
                        <div class="nav-mega-group-title">{{ $parent->name }}</div>
                        @if($parent->children->isNotEmpty())
                            @foreach($parent->children as $child)
                            <a href="/?cat={{ $child->id }}#products"
                               class="nav-mega-child"
                               @click="catOpen = false">
                                {{ $child->name }}
                            </a>
                            @endforeach
                        @else
                            <a href="/?cat={{ $parent->id }}#products"
                               class="nav-mega-child"
                               @click="catOpen = false">
                                {{ $parent->name }}
                            </a>
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="nav-mega-footer">
                    <a href="/#products" class="nav-mega-view-all" @click="catOpen = false">
                        View All Categories →
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Mobile Menu --}}
        <div class="mobile-menu" id="mobileMenu">
            @if(isset($navCategories) && $navCategories->isNotEmpty())
            <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;
                        letter-spacing:0.08em;color:#E8621A;padding:0.5rem 0 0.25rem;
                        border-bottom:1px solid #F0EBE4;margin-bottom:0.25rem;">
                Categories
            </div>
            @foreach($navCategories as $parent)
                @if($parent->children->isNotEmpty())
                <div style="font-size:0.78rem;font-weight:700;color:#1A1A1A;
                            padding:0.5rem 0 0.1rem;pointer-events:none;">
                    {{ $parent->name }}
                </div>
                @foreach($parent->children as $child)
                <a href="/?cat={{ $child->id }}#products"
                   style="padding-left:1rem;" onclick="toggleMenu()">
                    {{ $child->name }}
                </a>
                @endforeach
                @else
                <a href="/?cat={{ $parent->id }}#products" onclick="toggleMenu()">
                    {{ $parent->name }}
                </a>
                @endif
            @endforeach
            <div style="border-top:1px solid #F0EBE4;margin:0.5rem 0;"></div>
            @endif
            <a href="/#products">Products</a>
            <a href="/#about">About</a>
            <a href="/#contact">Contact</a>
            <a href="/wishlist">Wishlist @auth @if(auth()->user()->wishlistCount() > 0)({{ auth()->user()->wishlistCount() }})@endif @endauth</a>
            <a href="/cart">Cart @auth @if(auth()->user()->cartCount() > 0)({{ auth()->user()->cartCount() }})@endif @endauth</a>
            <a href="/orders">My Orders</a>
            <a href="/profile">Settings</a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="/admin">Admin</a>
                @endif
                <div style="border-top:1px solid #e5e7eb;margin-top:1rem;padding-top:1rem;">
                    <div style="font-size:0.85rem;font-weight:600;color:#111;margin-bottom:0.5rem;">
                        {{ auth()->user()->name }}
                    </div>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" style="color:#ef4444 !important;">Sign Out</button>
                    </form>
                </div>
            @else
                <div style="border-top:1px solid #e5e7eb;margin-top:1rem;padding-top:1rem;">
                    <a href="/login">Sign In</a>
                    <a href="{{ route('register') }}" style="color:#ea580c;">Sign Up</a>
                </div>
            @endauth
        </div>
    </nav>

    {{-- Toast --}}
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

    @if (session('error'))
        <div class="toast-wrap">
            <div class="toast-alert" style="color:#ef4444;border-color:rgba(239,68,68,0.3);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4m0 4h.01"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Page Content --}}
    <main {{ isset($mainClass) ? "class=$mainClass" : '' }}>
        {{ $slot }}
    </main>

    {{-- ── Compare Bar ── --}}
    @php $compareCount = count(session('compare', [])); @endphp
    @if($compareCount > 0)
    <div id="compareBar" style="
        position: fixed; bottom: 0; left: 0; right: 0; z-index: 90;
        background: rgba(17,24,39,0.97); backdrop-filter: blur(8px);
        border-top: 2px solid var(--orange);
        padding: 0.9rem 1.5rem;
        display: flex; align-items: center; justify-content: center; gap: 1.25rem;
        font-family: 'DM Sans', sans-serif;
    ">
        <span style="color:#fff;font-size:0.88rem;font-weight:600;">
            <span style="color:var(--orange);font-weight:800;">{{ $compareCount }}</span>
            {{ $compareCount === 1 ? 'product' : 'products' }} in compare
        </span>
        <a href="/compare" style="
            background:var(--orange);color:#fff;
            border:none;border-radius:999px;
            padding:0.5rem 1.25rem;
            font-size:0.85rem;font-weight:700;
            text-decoration:none;
            transition:background 0.15s;
            white-space:nowrap;
        " onmouseover="this.style.background='#c2410c'" onmouseout="this.style.background='#ea580c'">
            Compare now →
        </a>
        <form method="POST" action="/compare" style="margin:0;">
            @csrf @method('DELETE')
            <button type="submit" style="
                background:none;border:1.5px solid rgba(255,255,255,0.25);
                color:rgba(255,255,255,0.6);
                border-radius:999px;
                padding:0.45rem 1rem;
                font-size:0.82rem;font-weight:600;
                cursor:pointer;
                font-family:'DM Sans',sans-serif;
                transition:border-color 0.15s,color 0.15s;
            " onmouseover="this.style.borderColor='#ef4444';this.style.color='#ef4444'"
               onmouseout="this.style.borderColor='rgba(255,255,255,0.25)';this.style.color='rgba(255,255,255,0.6)'">
                Clear
            </button>
        </form>
    </div>
    @endif

    {{-- Footer --}}
    <footer style="background:#000;color:#fff;padding:4rem 0;">
        <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:2rem;margin-bottom:2rem;">
                <div>
                    <h3 style="font-size:1.5rem;font-weight:700;margin:0 0 1rem;">
                        <span style="color:#ea580c;">VOX</span>URA
                    </h3>
                    <p style="color:#9ca3af;margin:0;font-size:14px;line-height:1.6;">
                        Elevating your tech experience with premium products
                    </p>
                </div>
                <div>
                    <h4 style="font-weight:600;margin:0 0 1rem;font-size:15px;">Shop</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">All Products</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">New Arrivals</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">Best Sellers</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">Sale</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-weight:600;margin:0 0 1rem;font-size:15px;">Support</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">Contact Us</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">Shipping Info</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">Returns</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-weight:600;margin:0 0 1rem;font-size:15px;">Company</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">About Us</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">Careers</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">Press</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">Privacy</a></li>
                        <li><a href="/pages/cookies-policy" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">Cookies Policy</a></li>
                        <li><a href="/pages/privacy-policy" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div style="border-top:1px solid #1f2937;padding-top:2rem;text-align:center;">
                <p style="color:#9ca3af;margin:0;font-size:14px;">&copy; {{ date('Y') }} Voxura. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
    <script>
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.remove('nav-top');
                navbar.classList.add('nav-scrolled');
            } else {
                navbar.classList.remove('nav-scrolled');
                navbar.classList.add('nav-top');
            }
        });

        function toggleMenu() {
            const menu      = document.getElementById('mobileMenu');
            const iconMenu  = document.getElementById('icon-menu');
            const iconClose = document.getElementById('icon-close');
            menu.classList.toggle('open');
            iconMenu.style.display  = menu.classList.contains('open') ? 'none'  : 'block';
            iconClose.style.display = menu.classList.contains('open') ? 'block' : 'none';
        }
    </script>

</body>
</html>