<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — Voxura' : 'Voxura' }}</title>

    <meta property="og:title" content="Voxura" />
    <meta property="og:description" content="A social platform built with Laravel." />
    <meta property="og:url" content="https://voxura.laravel.cloud" />

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Playfair+Display:ital,wght@0,700;0,800;1,900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @if(app()->getLocale() === 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; overflow-x: hidden; }
        body { overflow-x: hidden; }

        :root {
            --orange:       #ea580c;
            --orange-dark:  #c2410c;
            --orange-light: #fff7ed;
            --orange-pale:  #fff7ed;
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
            background: #ffffff;
            color: var(--gray-900);
            font-family: 'DM Sans', sans-serif;
            font-weight: 400;
            min-height: 100svh;
            display: flex;
            flex-direction: column;
        }

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
            max-width: 1200px; margin: 0 auto; padding: 0 0.75rem;
            height: 64px; display: flex; align-items: center;
            justify-content: space-between; gap: 1rem; position: relative;
        }

        .nav-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-weight: 800; font-size: 1.9rem;
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
        nav.nav-top .nav-links a      { color: rgba(255,255,255,0.65); }
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

        /* ── Lang Switcher ── */
        .lang-switcher {
            display: flex; align-items: center; gap: 3px;
        }
        .lang-sep {
            font-size: 11px; color: rgba(255,255,255,0.35); line-height: 1;
        }
        nav.nav-scrolled .lang-sep { color: var(--gray-300); }
        .lang-btn {
            background: none; border: none;
            font-size: 11px; font-weight: 700;
            color: rgba(255,255,255,0.7);
            cursor: pointer; padding: 3px 5px;
            border-radius: 5px;
            transition: all 0.15s;
            font-family: 'Tajawal', 'DM Sans', sans-serif;
            line-height: 1;
        }
        nav.nav-scrolled .lang-btn { color: var(--gray-500); }
        .lang-btn:hover { color: var(--orange); }
        nav.nav-top .lang-btn:hover { color: #fb923c; }
        .lang-btn.lang-active {
            color: var(--orange) !important;
            background: rgba(234,88,12,0.12);
        }
        nav.nav-top .lang-btn.lang-active { color: #fb923c !important; }

        /* ── RTL Support ── */
        [dir="rtl"] body {
            font-family: 'Tajawal', sans-serif;
            text-align: right;
        }
        [dir="rtl"] .nav-inner {
            flex-direction: row-reverse;
        }
        [dir="rtl"] .nav-links {
            flex-direction: row-reverse;
        }
        [dir="rtl"] .nav-end {
            flex-direction: row-reverse;
        }
        [dir="rtl"] .nav-mega-child:hover {
            padding-left: 0;
            padding-right: 4px;
        }
        [dir="rtl"] .mobile-menu a,
        [dir="rtl"] .mobile-menu button {
            text-align: right;
        }
        [dir="rtl"] footer div[style*="grid-template-columns"] {
            direction: rtl;
        }
        [dir="rtl"] .toast-wrap {
            direction: rtl;
        }

        /* ── Categories Button in Navbar ── */
        .nav-cat-btn {
            display: inline-flex; align-items: center; gap: 4px;
            background: none; border: none; cursor: pointer;
            font-size: 0.9rem; font-weight: 500;
            font-family: 'DM Sans', sans-serif; padding: 0;
            transition: color 0.3s;
        }
        nav.nav-top      .nav-cat-btn       { color: rgba(255,255,255,0.65); }
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

        /* ── Live Search (hero frosted pill) ── */
        .hero-search {
            width: 580px; max-width: 90vw;
            margin: 0 auto 32px;
            position: relative;
        }
        .ls-panel-form {
            display: flex; align-items: center; gap: 12px;
            background: rgba(20,20,20,0.75);
            backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 50px; padding: 14px 20px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .ls-panel-form:focus-within {
            border-color: rgba(232,98,26,0.65);
            box-shadow: 0 0 0 3px rgba(232,98,26,0.18);
        }
        .ls-icon-search { flex-shrink: 0; color: #E8621A; }
        .ls-input {
            flex: 1; min-width: 0; background: none; border: none; outline: none;
            font-family: 'DM Sans', sans-serif; font-size: 1rem; color: #fff;
        }
        .ls-input::placeholder { color: rgba(255,255,255,0.48); }
        .ls-spinner {
            flex-shrink: 0; width: 15px; height: 15px;
            border: 2px solid rgba(232,98,26,0.25); border-top-color: #E8621A;
            border-radius: 50%; animation: ls-spin 0.65s linear infinite; display: none;
        }
        .ls-spinner.ls-spin-on { display: block; }
        @keyframes ls-spin { to { transform: rotate(360deg); } }
        .ls-close-btn {
            flex-shrink: 0; background: none; border: none; cursor: pointer;
            color: rgba(255,255,255,0.45); padding: 4px; line-height: 0;
            transition: color 0.15s; border-radius: 50%;
        }
        .ls-close-btn:hover { color: rgba(255,255,255,0.9); }

        .ls-dropdown {
            position: absolute; top: calc(100% + 8px); left: 0; right: 0;
            background: #fff; border-radius: 0.875rem;
            box-shadow: 0 12px 48px rgba(0,0,0,0.22), 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid var(--gray-200); z-index: 200; overflow: hidden;
            opacity: 0; transform: translateY(-4px); pointer-events: none;
            transition: opacity 0.15s ease, transform 0.15s ease;
        }
        .ls-dropdown.ls-open { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .ls-dropdown-inner   { max-height: 380px; overflow-y: auto; }
        .ls-result {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.65rem 1rem; text-decoration: none; color: var(--gray-900);
            border-bottom: 1px solid var(--gray-100); background: none;
            transition: background 0.1s; cursor: pointer;
        }
        .ls-result:last-child { border-bottom: none; }
        .ls-result:hover, .ls-result.ls-focused { background: var(--orange-light); }
        .ls-result-img {
            width: 44px; height: 44px; border-radius: 0.5rem; object-fit: cover;
            flex-shrink: 0; background: var(--gray-100); border: 1px solid var(--gray-200);
        }
        .ls-result-ph {
            width: 44px; height: 44px; border-radius: 0.5rem; background: var(--gray-100);
            flex-shrink: 0; display: flex; align-items: center; justify-content: center;
            color: var(--gray-300); border: 1px solid var(--gray-200);
        }
        .ls-result-body  { flex: 1; min-width: 0; }
        .ls-result-name  {
            font-size: 0.875rem; font-weight: 600; color: var(--gray-900);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.3;
        }
        .ls-result-meta  { display: flex; gap: 0.4rem; align-items: center; margin-top: 2px; }
        .ls-result-cat   { font-size: 0.72rem; color: var(--gray-400); }
        .ls-result-price { font-size: 0.8rem; font-weight: 700; color: #E8621A; }
        .ls-empty { padding: 1.25rem; text-align: center; font-size: 0.85rem; color: var(--gray-500); }
        .ls-footer a {
            display: block; padding: 0.65rem 1rem; text-align: center;
            font-size: 0.82rem; font-weight: 600; color: #E8621A; text-decoration: none;
            border-top: 1px solid var(--gray-100); transition: background 0.1s;
        }
        .ls-footer a:hover { background: var(--orange-light); }

        /* Mobile search in menu */
        .ls-mobile-wrap { padding: 0.5rem 0 0.75rem; border-bottom: 1px solid var(--gray-200); margin-bottom: 0.25rem; }
        .ls-mobile-form {
            display: flex; align-items: center; gap: 0.5rem;
            background: var(--gray-100); border-radius: 999px;
            padding: 0 0.75rem; height: 38px;
            border: 1.5px solid var(--gray-200);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .ls-mobile-form:focus-within {
            border-color: #E8621A; box-shadow: 0 0 0 3px rgba(232,98,26,0.12); background: #fff;
        }
        .ls-mobile-input {
            flex: 1; background: none; border: none; outline: none;
            font-family: 'DM Sans', sans-serif; font-size: 0.9rem; color: var(--gray-900);
        }
        .ls-mobile-input::placeholder { color: var(--gray-400); }

        @media (max-width: 768px) { .hero-search { width: 90%; } }

        /* ── Navbar slide-down search panel ── */
        .nav-search-panel {
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid #f0eeeb;
            padding: 12px 1.5rem 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        .nav-search-inner {
            max-width: 640px;
            margin: 0 auto;
            position: relative;
        }
        .nav-ls-form {
            background: #f8f7f5 !important;
            border: 1.5px solid #e0ddd9 !important;
            border-radius: 50px !important;
            padding: 10px 18px !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }
        .nav-ls-form .ls-input {
            color: #1a1a1a !important;
        }
        .nav-ls-form .ls-input::placeholder {
            color: #9ca3af !important;
        }
        .nav-ls-form .ls-icon-search { color: #E8621A; }
        .nav-ls-form:focus-within {
            border-color: #E8621A !important;
            box-shadow: 0 0 0 3px rgba(232,98,26,0.12) !important;
        }

        /* Slide-down transitions via Alpine custom classes */
        .nsp-enter        { transition: opacity 0.18s ease, transform 0.18s ease; }
        .nsp-enter-start  { opacity: 0; transform: translateY(-6px); }
        .nsp-enter-end    { opacity: 1; transform: translateY(0); }
        .nsp-leave        { transition: opacity 0.14s ease, transform 0.14s ease; }
        .nsp-leave-start  { opacity: 1; transform: translateY(0); }
        .nsp-leave-end    { opacity: 0; transform: translateY(-6px); }

        /* Nav search toggle icon color states */
        nav.nav-top     .nav-search-toggle { color: rgba(255,255,255,0.8); }
        nav.nav-scrolled .nav-search-toggle { color: var(--gray-600); }
        .nav-search-toggle:hover { color: #E8621A !important; }
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
         x-data="{ catOpen: false, userOpen: false, searchOpen: false }"
         @click.outside="catOpen = false; userOpen = false; searchOpen = false"
         @keydown.escape.window="catOpen = false; userOpen = false; searchOpen = false">
        <div class="nav-inner">

            <a href="/" class="nav-logo"><span class="accent">VOX</span>URA</a>

            <div class="nav-links">
                {{-- Categories mega-dropdown trigger --}}
                @if(isset($navCategories) && $navCategories->isNotEmpty())
                <button class="nav-cat-btn" @click="catOpen = !catOpen" :aria-expanded="catOpen">
                    {{ __('general.categories') }}
                    <svg class="nav-cat-arrow" :class="{ rotated: catOpen }"
                         xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                         viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                @endif

                <a href="/#products">{{ __('general.products') }}</a>
                <a href="/#about">{{ __('general.about') }}</a>
                <a href="/#contact">{{ __('general.contact') }}</a>
            </div>

            <div class="nav-end">
                @php
                    $cartCount = auth()->check()
                        ? auth()->user()->cartCount()
                        : (new \App\Services\GuestCart())->itemCount();
                @endphp

                {{-- Search toggle --}}
                <button class="nav-icon-btn nav-search-toggle"
                        @click="searchOpen = !searchOpen; if(searchOpen) $nextTick(() => document.getElementById('lsInput')?.focus())"
                        :aria-label="searchOpen ? '{{ __('general.close') }}' : '{{ __('general.search') }}'"
                        title="{{ __('general.search') }}">
                    <svg x-show="!searchOpen" xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <svg x-show="searchOpen" xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round" style="display:none">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Language Switcher --}}
                <form method="POST" action="{{ route('language.switch') }}" id="lang-form-nav" style="display:none;">
                    @csrf
                    <input type="hidden" name="locale" id="lang-input-nav" value="">
                </form>
                <div class="lang-switcher">
                    <button type="button" onclick="switchLang('en')"
                            class="lang-btn {{ app()->getLocale() === 'en' ? 'lang-active' : '' }}">EN</button>
                    <span class="lang-sep">|</span>
                    <button type="button" onclick="switchLang('ar')"
                            class="lang-btn {{ app()->getLocale() === 'ar' ? 'lang-active' : '' }}">ع</button>
                </div>

                {{-- Cart — always visible --}}
                <a href="/cart" class="nav-icon-btn" title="Cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="nav-badge" id="nav-cart-badge" style="{{ $cartCount < 1 ? 'display:none' : '' }}">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                </a>

                @auth
                    @php
                        $wishlistCount = auth()->user()->wishlistCount();
                    @endphp
                @endauth

                    {{-- Wishlist — visible to all; guests redirected to login --}}
                    <a href="{{ auth()->check() ? '/wishlist' : route('login') }}" class="nav-icon-btn" title="Wishlist">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                        </svg>
                        @auth
                        @if($wishlistCount > 0)
                            <span class="nav-badge">{{ $wishlistCount > 99 ? '99+' : $wishlistCount }}</span>
                        @endif
                        @endauth
                    </a>

                @auth

                    {{-- User profile dropdown --}}
                    <div style="position:relative;" @click.outside="userOpen = false">
                        <button class="nav-icon-btn" @click="userOpen = !userOpen"
                                :aria-expanded="userOpen" title="{{ auth()->user()->name }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </button>

                        <div x-show="userOpen"
                             x-transition:enter="mega-enter"
                             x-transition:enter-start="mega-enter-start"
                             x-transition:enter-end="mega-enter-end"
                             x-transition:leave="mega-leave"
                             x-transition:leave-start="mega-leave-start"
                             x-transition:leave-end="mega-leave-end"
                             style="display:none;position:absolute;top:calc(100% + 10px);right:0;
                                    min-width:190px;background:#fff;border-radius:0.75rem;
                                    box-shadow:0 8px 32px rgba(0,0,0,0.12);
                                    border:1.5px solid var(--gray-200);
                                    overflow:hidden;z-index:100;">

                            {{-- User name header --}}
                            <div style="padding:0.75rem 1rem 0.5rem;
                                        border-bottom:1px solid var(--gray-100);">
                                <p style="font-size:0.78rem;color:var(--gray-400);margin:0 0 1px;">
                                    {{ __('general.signed_in_as') }}
                                </p>
                                <p style="font-size:0.88rem;font-weight:700;color:var(--gray-900);
                                          margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ auth()->user()->name }}
                                </p>
                            </div>

                            {{-- My Orders --}}
                            <a href="/orders" @click="userOpen = false"
                               style="display:flex;align-items:center;gap:0.6rem;
                                      padding:0.65rem 1rem;font-size:0.85rem;
                                      font-weight:500;color:var(--gray-700);
                                      text-decoration:none;transition:background 0.12s,color 0.12s;"
                               onmouseover="this.style.background='var(--orange-light)';this.style.color='var(--orange)'"
                               onmouseout="this.style.background='';this.style.color='var(--gray-700)'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ __('general.my_orders') }}
                            </a>

                            {{-- My Messages --}}
                            <a href="{{ route('profile.messages') }}" @click="userOpen = false"
                               style="display:flex;align-items:center;gap:0.6rem;
                                      padding:0.65rem 1rem;font-size:0.85rem;
                                      font-weight:500;color:var(--gray-700);
                                      text-decoration:none;transition:background 0.12s,color 0.12s;"
                               onmouseover="this.style.background='var(--orange-light)';this.style.color='var(--orange)'"
                               onmouseout="this.style.background='';this.style.color='var(--gray-700)'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                                {{ __('general.my_messages') }}
                            </a>

                            {{-- Profile / Settings --}}
                            <a href="/profile" @click="userOpen = false"
                               style="display:flex;align-items:center;gap:0.6rem;
                                      padding:0.65rem 1rem;font-size:0.85rem;
                                      font-weight:500;color:var(--gray-700);
                                      text-decoration:none;transition:background 0.12s,color 0.12s;"
                               onmouseover="this.style.background='var(--orange-light)';this.style.color='var(--orange)'"
                               onmouseout="this.style.background='';this.style.color='var(--gray-700)'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ __('general.settings') }}
                            </a>

                            {{-- Divider + Logout --}}
                            <div style="border-top:1px solid var(--gray-100);">
                                <form method="POST" action="/logout" style="margin:0;">
                                    @csrf
                                    <button type="submit"
                                            style="display:flex;align-items:center;gap:0.6rem;
                                                   width:100%;padding:0.65rem 1rem;
                                                   font-size:0.85rem;font-weight:500;
                                                   color:#ef4444;background:none;border:none;
                                                   cursor:pointer;text-align:left;
                                                   font-family:'DM Sans',sans-serif;
                                                   transition:background 0.12s;"
                                            onmouseover="this.style.background='#fef2f2'"
                                            onmouseout="this.style.background=''">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        {{ __('general.sign_out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="/login" class="btn btn-ghost">{{ __('general.sign_in') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">{{ __('general.sign_up') }}</a>
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
                        {{ __('general.view_all_categories') }} →
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Mobile Menu --}}
        <div class="mobile-menu" id="mobileMenu">
            {{-- Mobile Live Search --}}
            <div class="ls-mobile-wrap">
                <form class="ls-mobile-form" action="/search" method="GET" autocomplete="off">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"
                         style="flex-shrink:0;color:var(--gray-400);">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" name="q" id="lsMobileInput" class="ls-mobile-input"
                           placeholder="{{ __('general.search') }}…"
                           aria-label="{{ __('general.search') }}"
                           autocomplete="off">
                </form>
            </div>
            @if(isset($navCategories) && $navCategories->isNotEmpty())
            <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;
                        letter-spacing:0.08em;color:#E8621A;padding:0.5rem 0 0.25rem;
                        border-bottom:1px solid #F0EBE4;margin-bottom:0.25rem;">
                {{ __('general.categories') }}
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
            <a href="/#products">{{ __('general.products') }}</a>
            <a href="/#about">{{ __('general.about') }}</a>
            <a href="/#contact">{{ __('general.contact') }}</a>
            @auth
            <a href="/wishlist">{{ __('general.my_wishlist') }} @if(auth()->user()->wishlistCount() > 0)({{ auth()->user()->wishlistCount() }})@endif</a>
            @endauth
            <a href="/cart">{{ __('general.cart') }} @if($cartCount > 0)({{ $cartCount }})@endif</a>
            @auth
            <a href="/orders">{{ __('general.my_orders') }}</a>
            <a href="{{ route('profile.messages') }}">{{ __('general.my_messages') }}</a>
            <a href="/profile">{{ __('general.settings') }}</a>
            @endauth
            @auth
                <div style="border-top:1px solid #e5e7eb;margin-top:1rem;padding-top:1rem;">
                    <div style="font-size:0.85rem;font-weight:600;color:#111;margin-bottom:0.5rem;">
                        {{ auth()->user()->name }}
                    </div>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" style="color:#ef4444 !important;">{{ __('general.sign_out') }}</button>
                    </form>
                </div>
            @else
                <div style="border-top:1px solid #e5e7eb;margin-top:1rem;padding-top:1rem;">
                    <a href="/login">{{ __('general.sign_in') }}</a>
                    <a href="{{ route('register') }}" style="color:#ea580c;">{{ __('general.sign_up') }}</a>
                </div>
            @endauth
        </div>

        {{-- Slide-down search panel --}}
        <div class="nav-search-panel"
             id="heroSearch"
             x-show="searchOpen"
             x-transition:enter="nsp-enter"
             x-transition:enter-start="nsp-enter-start"
             x-transition:enter-end="nsp-enter-end"
             x-transition:leave="nsp-leave"
             x-transition:leave-start="nsp-leave-start"
             x-transition:leave-end="nsp-leave-end"
             style="display:none">
            <div class="nav-search-inner">
                <form class="ls-panel-form nav-ls-form" action="/search" method="GET"
                      id="lsForm" autocomplete="off">
                    <svg class="ls-icon-search" xmlns="http://www.w3.org/2000/svg"
                         width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" name="q" id="lsInput" class="ls-input"
                           placeholder="{{ __('general.hero_search_placeholder') }}"
                           aria-label="{{ __('general.search') }}"
                           role="combobox" aria-autocomplete="list"
                           aria-controls="lsDropdown" aria-expanded="false"
                           aria-haspopup="listbox" autocomplete="off">
                    <div class="ls-spinner" id="lsSpinner" aria-hidden="true"></div>
                    <button type="button" id="lsClose" class="ls-close-btn"
                            @click="searchOpen = false" aria-label="{{ __('general.close') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </form>
                <div class="ls-dropdown" id="lsDropdown" role="listbox"
                     aria-label="Search results" hidden></div>
            </div>
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
            {{ $compareCount === 1 ? __('general.compare_product_in', ['count' => '']) : __('general.compare_products_in', ['count' => '']) }}
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
            {{ __('general.compare_now') }} →
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
                {{ __('general.compare_bar_clear') }}
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
                        {{ __('general.footer_tagline') }}
                    </p>
                </div>
                <div>
                    <h4 style="font-weight:600;margin:0 0 1rem;font-size:15px;">{{ __('general.footer_shop') }}</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                        <li><a href="{{ route('products.index') }}" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.all_products') }}</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.footer_new_arrivals') }}</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.footer_best_sellers') }}</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.footer_sale') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-weight:600;margin:0 0 1rem;font-size:15px;">{{ __('general.footer_support') }}</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                        <li><a href="/#contact" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.footer_contact_us') }}</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.footer_shipping_info') }}</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.footer_returns') }}</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.footer_faq') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-weight:600;margin:0 0 1rem;font-size:15px;">{{ __('general.footer_company') }}</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                        <li><a href="/#about" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.footer_about_us') }}</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.footer_careers') }}</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.footer_press') }}</a></li>
                        <li><a href="#" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.footer_privacy') }}</a></li>
                        <li><a href="/pages/cookies-policy" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.cookies_policy') }}</a></li>
                        <li><a href="/pages/privacy-policy" style="color:#9ca3af;text-decoration:none;font-size:14px;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#9ca3af'">{{ __('general.privacy_policy') }}</a></li>
                    </ul>
                </div>
            </div>
            <div style="border-top:1px solid #1f2937;padding-top:2rem;text-align:center;">
                <p style="color:#9ca3af;margin:0;font-size:14px;">&copy; {{ date('Y') }} Voxura. {{ __('general.footer_all_rights') }}</p>
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

        // ── Language switcher ──
        function switchLang(locale) {
            document.getElementById('lang-input-nav').value = locale;
            document.getElementById('lang-form-nav').submit();
        }

        // ── Live Search ──
        (function () {
            const input    = document.getElementById('lsInput');
            const dropdown = document.getElementById('lsDropdown');
            const spinner  = document.getElementById('lsSpinner');
            const closeBtn = document.getElementById('lsClose');
            if (!input || !dropdown) return;

            let timer = null, ctrl = null, curIdx = -1;
            const cache = {};
            const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
            const getItems = () => dropdown.querySelectorAll('.ls-result');

            function openDropdown()  { dropdown.classList.add('ls-open'); dropdown.removeAttribute('hidden'); input.setAttribute('aria-expanded','true'); }
            function closeDropdown() {
                dropdown.classList.remove('ls-open');
                input.setAttribute('aria-expanded','false'); curIdx = -1;
                setTimeout(() => { if (!dropdown.classList.contains('ls-open')) dropdown.setAttribute('hidden',''); }, 160);
            }
            function moveFocus(idx) {
                const items = getItems();
                items.forEach((el, i) => el.classList.toggle('ls-focused', i === idx));
                curIdx = idx;
                if (items[idx]) items[idx].scrollIntoView({ block: 'nearest' });
            }
            function render(results, q) {
                if (!results.length) {
                    dropdown.innerHTML = `<div class="ls-empty">No products found for "<strong>${esc(q)}</strong>"</div>`;
                    openDropdown(); return;
                }
                const ph = `<div class="ls-result-ph"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></div>`;
                const rows = results.map((r, i) => {
                    const img = r.image ? `<img class="ls-result-img" src="${esc(r.image)}" alt="${esc(r.name)}" loading="lazy">` : ph;
                    const cat = r.category ? `<span class="ls-result-cat">${esc(r.category)}</span><span class="ls-result-cat">·</span>` : '';
                    return `<a href="${esc(r.url)}" class="ls-result" role="option" id="ls-r-${i}" tabindex="-1">${img}<div class="ls-result-body"><div class="ls-result-name">${esc(r.name)}</div><div class="ls-result-meta">${cat}<span class="ls-result-price">$${esc(r.price)}</span></div></div></a>`;
                }).join('');
                dropdown.innerHTML = `<div class="ls-dropdown-inner">${rows}</div><div class="ls-footer"><a href="/search?q=${encodeURIComponent(q)}">View all results →</a></div>`;
                openDropdown();
            }
            async function doFetch(q) {
                if (cache[q]) { render(cache[q], q); return; }
                if (ctrl) ctrl.abort();
                const c = new AbortController(); ctrl = c;
                spinner && spinner.classList.add('ls-spin-on');
                try {
                    const res  = await fetch('/search/live?q=' + encodeURIComponent(q), { signal: c.signal, headers: {'X-Requested-With':'XMLHttpRequest'} });
                    const data = await res.json();
                    cache[q] = data.results;
                    render(data.results, q);
                } catch(e) { if (e.name !== 'AbortError') closeDropdown(); }
                finally   { if (ctrl === c) { spinner && spinner.classList.remove('ls-spin-on'); ctrl = null; } }
            }
            if (closeBtn) closeBtn.addEventListener('click', () => {
                input.value = ''; dropdown.innerHTML = '';
                if (ctrl) { ctrl.abort(); ctrl = null; }
                spinner && spinner.classList.remove('ls-spin-on');
                closeDropdown(); input.focus();
            });
            input.addEventListener('input', () => {
                const q = input.value.trim(); clearTimeout(timer); curIdx = -1;
                if (q.length < 2) { if (ctrl) { ctrl.abort(); ctrl = null; } spinner && spinner.classList.remove('ls-spin-on'); closeDropdown(); dropdown.innerHTML = ''; return; }
                timer = setTimeout(() => doFetch(q), 300);
            });
            input.addEventListener('keydown', e => {
                const items = getItems();
                if      (e.key === 'ArrowDown')                              { e.preventDefault(); moveFocus(Math.min(curIdx + 1, items.length - 1)); }
                else if (e.key === 'ArrowUp')                                { e.preventDefault(); moveFocus(Math.max(curIdx - 1, -1)); }
                else if (e.key === 'Escape')                                 { closeDropdown(); input.blur(); }
                else if (e.key === 'Enter' && curIdx >= 0 && items[curIdx]) { e.preventDefault(); items[curIdx].click(); }
            });
            document.addEventListener('click', e => {
                if (!e.target.closest('#heroSearch') && !e.target.closest('.hero-search')) closeDropdown();
            });
        })();

        // ── Global toast helper ──
        window.showToast = function(message, type = 'info') {
            const wrap = document.createElement('div');
            wrap.className = 'toast-wrap';
            const colors = {
                success: { color: '#ea580c', border: 'rgba(234,88,12,0.3)' },
                error:   { color: '#ef4444', border: 'rgba(239,68,68,0.3)' },
                info:    { color: '#ea580c', border: 'rgba(234,88,12,0.3)' },
            };
            const c = colors[type] || colors.info;
            const icon = type === 'error'
                ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>'
                : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
            wrap.innerHTML = `<div class="toast-alert" style="color:${c.color};border-color:${c.border};">${icon}${message}</div>`;
            document.body.appendChild(wrap);
            setTimeout(() => wrap.remove(), 4000);
        };
    </script>

    {{-- ── Cookie Consent Banner ── --}}
    <div id="cookie-banner" style="
        display:none;
        position:fixed;bottom:0;left:0;right:0;z-index:9999;
        background:#fff;border-top:1.5px solid #e5e7eb;
        box-shadow:0 -4px 24px rgba(0,0,0,0.08);
        padding:1rem 1.5rem;
        animation:slideUpBanner 0.35s ease;
    ">
        <style>
            @keyframes slideUpBanner { from { transform:translateY(100%); opacity:0; } to { transform:translateY(0); opacity:1; } }
            #cookie-banner .cb-inner { max-width:1200px; margin:0 auto; display:flex; align-items:center; gap:1rem; flex-wrap:wrap; }
            #cookie-banner .cb-text { flex:1; min-width:200px; font-size:0.88rem; color:#374151; line-height:1.55; }
            #cookie-banner .cb-text a { color:#ea580c; text-decoration:underline; }
            #cookie-banner .cb-actions { display:flex; gap:0.6rem; flex-shrink:0; }
            #cookie-banner .cb-btn { padding:0.5rem 1.2rem; border-radius:999px; font-size:0.83rem; font-weight:700; cursor:pointer; border:none; transition:opacity 0.15s; }
            #cookie-banner .cb-btn:hover { opacity:0.85; }
            #cookie-banner .cb-accept { background:#ea580c; color:#fff; }
            #cookie-banner .cb-decline { background:#f3f4f6; color:#6b7280; }
        </style>
        <div class="cb-inner">
            <p class="cb-text">
                {{ __('general.cookie_consent_message') }}
                <a href="/pages/cookies-policy">{{ __('general.cookie_learn_more') }}</a>
            </p>
            <div class="cb-actions">
                <button class="cb-btn cb-decline" onclick="cookieConsent('decline')">{{ __('general.cookie_decline') }}</button>
                <button class="cb-btn cb-accept" onclick="cookieConsent('accept')">{{ __('general.cookie_accept') }}</button>
            </div>
        </div>
    </div>
    <script>
        (function() {
            var consent = localStorage.getItem('voxura_cookie_consent');
            if (!consent) {
                setTimeout(function() {
                    var b = document.getElementById('cookie-banner');
                    if (b) b.style.display = 'block';
                }, 800);
            }
        })();
        function cookieConsent(choice) {
            localStorage.setItem('voxura_cookie_consent', choice);
            var b = document.getElementById('cookie-banner');
            if (b) { b.style.opacity = '0'; b.style.transform = 'translateY(100%)'; b.style.transition = 'all 0.3s ease'; setTimeout(function(){b.style.display='none';}, 300); }
        }
    </script>

</body>
</html>