<x-layout>
    <x-slot:title>Register</x-slot:title>

    <style>
        /* ── Override layout <main> to allow centering ── */
        main {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex: 1 !important;
            padding: 2rem 1rem !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        /* ── Override layout body for register page only ── */
        body {
            background: #ffffff !important;
            display: flex !important;
            flex-direction: column !important;
            min-height: 100vh !important;
            font-family: 'DM Sans', sans-serif;
            padding: 0 !important;
        }

        /* ── Wrapper ── */
        .register-wrapper {
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
        .register-heading {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-heading h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: #111827;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .register-heading h1 span { color: #ea580c; }

        .register-heading p {
            color: #6b7280;
            font-size: 0.95rem;
        }

        /* ── Card ── */
        .register-card {
            background: #ffffff !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.18) !important;
            border: none !important;
            outline: none !important;
            overflow: hidden;
            width: 100%;
            -webkit-box-shadow: 0 25px 50px -12px rgba(0,0,0,0.18) !important;
        }

        .register-card .card-top {
            padding: 1.75rem 1.75rem 0;
        }

        .register-card .card-top h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700 !important;
            color: #111827;
        }

        .register-card .card-top p {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .register-card .card-inner {
            padding: 1.5rem 1.75rem 1.75rem;
        }

        /* ── Form groups ── */
        .register-card .form-group {
            margin-bottom: 1.1rem;
        }

        .register-card .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.35rem;
        }

        .register-card .input-wrap {
            position: relative;
        }

        .register-card .input-icon {
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

        .register-card input[type="text"],
        .register-card input[type="email"],
        .register-card input[type="password"] {
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

        .register-card input:focus {
            border-color: #ea580c !important;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12) !important;
        }

        .register-card input.input-error {
            border-color: #ef4444 !important;
        }

        .register-card .error-msg {
            font-size: 0.8rem;
            color: #ef4444;
            margin-top: 0.3rem;
            display: block;
        }

        /* ── Submit button ── matches React py-6 text-lg ── */
        .register-card .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 1.5rem !important;      /* py-6 = 1.5rem top/bottom */
            background: #ea580c !important;
            color: #ffffff !important;
            font-family: 'DM Sans', sans-serif;
            font-size: 1.125rem !important;  /* text-lg */
            font-weight: 600 !important;
            border: none !important;
            border-radius: 0.5rem !important;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            margin-top: 0.5rem;
            height: auto !important;
            min-height: unset !important;
            line-height: 1.5 !important;
        }

        .register-card .btn-submit:hover {
            background: #c2410c !important;
            box-shadow: 0 4px 20px rgba(234,88,12,0.3) !important;
            transform: translateY(-1px);
            color: #ffffff !important;
        }

        .register-card .btn-submit:active { transform: translateY(0); }

        .register-card .btn-submit svg {
            transition: transform 0.2s;
            flex-shrink: 0;
        }

        .register-card .btn-submit:hover svg {
            transform: translateX(4px);
        }

        /* ── Sign-in link ── */
        .register-card .signin-link {
            text-align: center;
            font-size: 0.875rem;
            color: #4b5563;
            margin-top: 1rem;
        }

        .register-card .signin-link a {
            color: #ea580c !important;
            font-weight: 600;
            text-decoration: none;
        }

        .register-card .signin-link a:hover {
            color: #c2410c !important;
            text-decoration: underline;
        }

        /* ── Footer note ── */
        .register-footer-note {
            text-align: center;
            font-size: 0.72rem;
            color: #9ca3af;
            margin-top: 1.25rem;
        }
    </style>

    <div class="register-wrapper">

        {{-- Heading --}}
        <div class="register-heading">
            <h1>Join <span>VOX</span>URA</h1>
            <p>Create your account to start shopping</p>
        </div>

        {{-- Card --}}
        <div class="register-card">
            <div class="card-top">
                <h2>Sign Up</h2>
                <p>Enter your details to create an account</p>
            </div>

            <div class="card-inner">
                <form method="POST" action="/register" novalidate>
                    @csrf

                    {{-- Username --}}
                    <div class="form-group">
                        <label for="name">Username</label>
                        <div class="input-wrap">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   placeholder="johndoe"
                                   value="{{ old('name') }}"
                                   class="@error('name') input-error @enderror"
                                   required>
                        </div>
                        @error('name')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

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
                                   required>
                        </div>
                        @error('email')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label for="password">Password</label>
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

                    {{-- Confirm Password --}}
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="input-wrap">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="••••••••"
                                   required>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit">
                        Create Account
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>

                    {{-- Already have an account — inside form, no divider, matching React --}}
                    <p class="signin-link">
                        Already have an account?
                        <a href="/login">Sign In</a>
                    </p>

                </form>
            </div>
        </div>

        <p class="register-footer-note">
            By signing up, you agree to our Terms of Service and Privacy Policy
        </p>

    </div>

</x-layout>