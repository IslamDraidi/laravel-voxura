<x-layout>
    <x-slot:title>{{ __('general.forgot_password_title') }}</x-slot:title>

    <style>
        main {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex: 1 !important;
            padding: 2rem 1rem !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        body {
            background: #ffffff !important;
            display: flex !important;
            flex-direction: column !important;
            min-height: 100vh !important;
            font-family: 'DM Sans', sans-serif;
            padding: 0 !important;
        }

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

        .login-card {
            background: #ffffff !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.18) !important;
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

        .login-card input[type="email"] {
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

        .login-card .back-link {
            text-align: center;
            font-size: 0.875rem;
            color: #4b5563;
            margin-top: 1rem;
        }

        .login-card .back-link a {
            color: #ea580c !important;
            font-weight: 600;
            text-decoration: none;
        }

        .login-card .back-link a:hover {
            color: #c2410c !important;
            text-decoration: underline;
        }

        .status-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 0.5rem;
            padding: 0.9rem 1rem;
            font-size: 0.9rem;
            color: #166534;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            margin-bottom: 1.1rem;
        }

        .status-box svg {
            flex-shrink: 0;
            margin-top: 1px;
        }
    </style>

    <div class="login-wrapper">

        <div class="login-heading">
            <h1>Welcome to <span>VOX</span>URA</h1>
            <p>{{ __('general.forgot_password_sub') }}</p>
        </div>

        <div class="login-card">
            <div class="card-top">
                <h2>{{ __('general.forgot_password_title') }}</h2>
                <p>{{ __('general.forgot_password_sub') }}</p>
            </div>

            <div class="card-inner">

                @if (session('status'))
                    <div class="status-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" novalidate>
                    @csrf

                    <div class="form-group">
                        <label for="email">{{ __('general.email_address') }}</label>
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

                    <button type="submit" class="btn-submit">
                        {{ __('general.send_reset_link') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>

                    <p class="back-link">
                        <a href="{{ route('login') }}">← {{ __('general.back_to_login') }}</a>
                    </p>

                </form>
            </div>
        </div>

    </div>

</x-layout>
