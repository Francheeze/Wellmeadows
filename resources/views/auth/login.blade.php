<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellmeadows Hospital - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Cinzel:wght@700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Figtree', 'Segoe UI', sans-serif;
        }

        /* FIX: Override app.css body { display: flex } */
        body {
            display: block !important;
            min-height: 100vh;
            background-image: url('{{ asset('images/hospital.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .overlay {
            min-height: 100vh;
            width: 100%;
            background-color: rgba(3, 65, 110, 0.60);
            display: flex;
            flex-direction: column;
        }

        /* MAIN */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .hero-text {
            text-align: center;
            margin-bottom: 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* CENTERED LOGO */
        .hero-logo {
            width: 400px;
            height: 400px;
            object-fit: contain;
            margin-bottom: -80px;
            filter: drop-shadow(0 6px 20px rgba(0,0,0,0.45));
        }

        /* CREATIVE TITLE */
        .hero-title {
            font-family: 'Cinzel', 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            background: linear-gradient(135deg, #ffffff 0%, #C8ECEE 40%, #a8d8db 70%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
            position: relative;
            padding-bottom: 10px;
        }

        .hero-title::after {
            content: '';
            display: block;
            width: 60%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #C8ECEE, transparent);
            margin: 8px auto 0;
        }

        .hero-text p {
            font-size: 13px;
            color: rgba(200, 236, 238, 0.85);
            margin-top: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 500;
        }

        /* CARD */
        .login-card {
            background: linear-gradient(145deg, rgba(255,255,255,0.95) 0%, rgba(240,248,255,0.97) 100%);
            border-radius: 18px;
            padding: 36px 32px;
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(3, 65, 110, 0.15);
            box-shadow: 0 20px 60px rgba(3, 65, 110, 0.25), 0 4px 20px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: #03416E;
            margin-bottom: 4px;
        }

        .card-sub {
            font-size: 12px;
            color: #4a8fa0;
            margin-bottom: 24px;
        }

        /* SESSION STATUS */
        .session-status {
            background: #e0f7f9;
            color: #03416E;
            padding: 10px 14px;
            border-radius: 7px;
            font-size: 13px;
            margin-bottom: 16px;
            border: 1px solid #C8ECEE;
        }

        /* FORM */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #03416E;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border-radius: 9px;
            border: 1.5px solid #C8ECEE;
            background: rgba(200, 236, 238, 0.12);
            font-size: 14px;
            color: #03416E;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .form-group input:focus {
            border-color: #03416E;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(3, 65, 110, 0.1);
        }

        /* ERROR */
        .error-msg {
            font-size: 11px;
            color: #c0392b;
            margin-top: 4px;
        }

        /* REMEMBER ME */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .remember-row input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #03416E;
        }

        .remember-row label {
            font-size: 12px;
            color: #4a7a8a;
        }

        /* BOTTOM ROW */
        .form-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-top: 16px;
            border-top: 1px solid rgba(3, 65, 110, 0.1);
        }

        .forgot-link {
            font-size: 12px;
            color: #4a8fa0;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: #03416E;
            text-decoration: underline;
        }

        .login-btn {
            padding: 12px 28px;
            background: linear-gradient(135deg, #03416E 0%, #0a6aad 100%);
            color: #ffffff;
            border: none;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 4px 15px rgba(3, 65, 110, 0.35);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(3, 65, 110, 0.45);
        }

        /* REGISTER LINK */
        .register-note {
            text-align: center;
            font-size: 12px;
            color: #4a8fa0;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(3, 65, 110, 0.1);
        }

        .register-note a {
            color: #03416E;
            font-weight: 600;
            text-decoration: none;
        }

        .register-note a:hover {
            text-decoration: underline;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 14px;
            font-size: 11px;
            color: rgba(200, 236, 238, 0.55);
            background: rgba(3, 65, 110, 0.75);
        }
    </style>
</head>
<body>
    <div class="overlay">

        {{-- MAIN CONTENT --}}
        <div class="main">
            <div class="hero-text">
                <img src="{{ asset('images/logo.png') }}" alt="Wellmeadows Logo" class="hero-logo">
                <h1 class="hero-title">Wellmeadows Hospital</h1>
                <p>Caring for Life &bull; Est. 1974</p>
            </div>

            <div class="login-card">
                <div class="card-title">Welcome back</div>
                <div class="card-sub">Sign in to your account to continue</div>

                @if (session('status'))
                    <div class="session-status">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" type="email" name="email"
                               value="{{ old('email') }}"
                               required autofocus autocomplete="username"
                               placeholder="your@email.com" />
                        @error('email')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password"
                               required autocomplete="current-password"
                               placeholder="Enter your password" />
                        @error('password')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="remember-row">
                        <input id="remember_me" type="checkbox" name="remember">
                        <label for="remember_me">Remember me</label>
                    </div>

                    <div class="form-bottom">
                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                Forgot your password?
                            </a>
                        @endif
                        <button type="submit" class="login-btn">Log in</button>
                    </div>
                </form>

                @if (Route::has('register'))
                    <div class="register-note">
                        Don't have an account? <a href="{{ route('register') }}">Register here</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Wellmeadows Hospital. All rights reserved.
        </div>

    </div>
</body>
</html>