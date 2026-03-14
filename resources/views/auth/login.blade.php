<x-layout>
    <x-slot:title>Sign In</x-slot:title>

    <style>
        /* ── Override layout <main> ── */
        main {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex: 1 !important;
            padding: 2rem 1rem !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        /* ── Body ── */
        body {
            background: linear-gradient(135deg, #fff7ed 0%, #ffffff 50%, #f9fafb 100%) !important;
            display: flex !important;
            flex-direction: column !important;
            min-height: 100vh !important;
            font-family: 'DM Sans', sans-serif;
            padding: 0 !important;
        }

        /* Animated blobs */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
            pointer-events: none;
            z-index: 0;
        }
        body::before {
            width: 520px; height: 520px;
            background: radial-gradient(circle, #fdba74, #fb923c);
            top: -160px; left: -160px;
            animation: blob1 8s ease-in-out infinite alternate;
        }
        body::after {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #fed7aa, #fbbf24);
            bottom: -120px; right: -120px;
            animation: blob2 10s ease-in-out infinite alternate;
        }

        @keyframes blob1 { to { transform: translate(40px, 30px) scale(1.08); } }
        @keyframes blob2 { to { transform: translate(-30px, -20px) scale(1.05); } }

        /* ── Wrapper ── */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            animation: slideUp 0.5s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Page heading ── */
        .login-heading {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-heading h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: #111827;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .login-heading h1 span { color: #ea580c; }

        .login-heading p {
            color: #6b7280;
            font-size: 0.95rem;
        }

        /* ── Card ── */
        .login-card {
            background: #ffffff !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.18) !important;
            -webkit-box-shadow: 0 25px 50px -12px rgba(0,0,0,0.18) !important;
            border: none !important;
            outline: none !important;
            overflow: hidden;
            width: 100%;
        }

        .login-card .card-top {
            padding: 1.75rem 1.75rem 0;
        }

        .login-card .card-top h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700 !important;
            color: #111827;
        }

        .login-card .card-top p {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .login-card .card-inner {
            padding: 1.5rem 1.75rem 1.75rem;
        }

        /* ── Form groups ── */
        .login-card .form-group {
            margin-bottom: 1.1rem;
        }

        .login-card .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.35rem;
        }

        .login-card .input-wrap {
            position: relative;
        }

        .login-card .input-icon {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.15rem;
            height: 1.15rem;
            color: #9ca3af;
            pointer-events: none;
            z-index: 1;
        }

        .login-card input[type="email"],
        .login-card input[type="password"] {
            width: 100% !important;
            padding: 0.7rem 0.9rem 0.7rem 2.6rem !important;
            border: 1.5px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem !important;
            color: #111827 !important;
            background: #ffffff !important;
            outline: none !important;
            box-shadow: none !important;
            transition: border-color 0.2s, box-shadow 0.2s;
            height: auto !important;
        }

        .login-card input:focus {
            border-color: #ea580c !important;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12) !important;
        }

        .login-card input.input-error {
            border-color: #ef4444 !important;
        }

        .login-card .error-msg {
            font-size: 0.8rem;
            color: #ef4444;
            margin-top: 0.3rem;
            display: block;
        }

        /* ── Password label row with Forgot link ── */
        .login-card .password-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.35rem;
        }

        .login-card .password-label-row label {
            margin-bottom: 0 !important;
        }

        .login-card .forgot-link {
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            color: #ea580c !important;
            text-decoration: none !important;
            transition: color 0.15s !important;
        }

        .login-card .forgot-link:hover {
            color: #c2410c !important;
            text-decoration: underline !important;
        }

        /* ── Submit button ── */
        .login-card .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 1.5rem !important;
            background: #ea580c !important;
            color: #ffffff !important;
            font-family: 'DM Sans', sans-serif;
            font-size: 1.125rem !important;
            font-weight: 600 !important;
            border: none !important;
            border-radius: 0.5rem !important;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            margin-top: 1.5rem;
            height: auto !important;
            min-height: unset !important;
            line-height: 1.5 !important;
        }

        .login-card .btn-submit:hover {
            background: #c2410c !important;
            box-shadow: 0 4px 20px rgba(234,88,12,0.3) !important;
            transform: translateY(-1px);
            color: #ffffff !important;
        }

        .login-card .btn-submit:active { transform: translateY(0); }

        .login-card .btn-submit svg {
            transition: transform 0.2s;
            flex-shrink: 0;
        }

        .login-card .btn-submit:hover svg {
            transform: translateX(4px);
        }

        /* ── Register link ── */
        .login-card .register-link {
            text-align: center;
            font-size: 0.875rem;
            color: #4b5563;
            margin-top: 1rem;
        }

        .login-card .register-link a {
            color: #ea580c !important;
            font-weight: 600;
            text-decoration: none;
        }

        .login-card .register-link a:hover {
            color: #c2410c !important;
            text-decoration: underline;
        }

        /* ── Or continue with divider ── */
        .social-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0 1rem;
        }

        .social-divider::before,
        .social-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .social-divider span {
            font-size: 0.8rem;
            color: #9ca3af;
            white-space: nowrap;
            padding: 0 0.25rem;
        }

        /* ── Social buttons ── */
        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.7rem 1rem;
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 0.5rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: #111827 !important;
            text-decoration: none !important;
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
            cursor: pointer;
        }

        .social-btn:hover {
            border-color: #d1d5db;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transform: translateY(-1px);
            color: #111827 !important;
        }

        .social-btn:active { transform: translateY(0); }

        /* ── Footer note ── */
        .login-footer-note {
            text-align: center;
            font-size: 0.72rem;
            color: #9ca3af;
            margin-top: 1.25rem;
            margin-bottom: 0;
        }
    </style>

    <div class="login-wrapper">

        {{-- Heading --}}
        <div class="login-heading">
            <h1>Welcome to <span>VOX</span>URA</h1>
            <p>Sign in to continue shopping</p>
        </div>

        {{-- Card --}}
        <div class="login-card">
            <div class="card-top">
                <h2>Sign In</h2>
                <p>Enter your credentials to access your account</p>
            </div>

            <div class="card-inner">
                <form method="POST" action="/login" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrap">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   placeholder="john@example.com"
                                   value="{{ old('email') }}"
                                   class="@error('email') input-error @enderror"
                                   required
                                   autofocus>
                        </div>
                        @error('email')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <div class="password-label-row">
                            <label for="password">Password</label>
                            <a href="#" class="forgot-link">Forgot password?</a>
                        </div>
                        <div class="input-wrap">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   placeholder="••••••••"
                                   class="@error('password') input-error @enderror"
                                   required>
                        </div>
                        @error('password')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit">
                        Sign In
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>

                    {{-- Register link --}}
                    <p class="register-link">
                        Don't have an account?
                        <a href="{{ route('register') }}">Sign Up</a>
                    </p>

                </form>
            </div>
        </div>

        <p class="login-footer-note">
            By signing in, you agree to our Terms of Service and Privacy Policy
        </p>

        {{-- Or continue with --}}
        <div class="social-divider">
            <span>Or continue with</span>
        </div>

        <div class="social-buttons">
            <a href="#" class="social-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Google
            </a>
            <a href="#" class="social-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.342-3.369-1.342-.454-1.155-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.202 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                </svg>
                GitHub
            </a>
        </div>

    </div>

</x-layout>