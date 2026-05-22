<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Wellmeadows') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Cinzel:wght@700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
        .auth-card {
            background: linear-gradient(145deg, rgba(255,255,255,0.95) 0%, rgba(240,248,255,0.97) 100%);
            border-radius: 18px;
            padding: 36px 32px;
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(3, 65, 110, 0.15);
            box-shadow: 0 20px 60px rgba(3, 65, 110, 0.25), 0 4px 20px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        /* FORM LABELS */
        .auth-card label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #03416E;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        /* FORM INPUTS */
        .auth-card input[type="text"],
        .auth-card input[type="email"],
        .auth-card input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border-radius: 9px;
            border: 1.5px solid #C8ECEE;
            background: rgba(200, 236, 238, 0.12);
            font-size: 14px;
            color: #03416E;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            margin-bottom: 18px;
        }

        .auth-card input:focus {
            border-color: #03416E;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(3, 65, 110, 0.1);
        }

        /* BUTTON */
        .auth-card button[type="submit"],
        .auth-card input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #03416E 0%, #0a6aad 100%);
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            border: none;
            border-radius: 9px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 4px 15px rgba(3, 65, 110, 0.35);
            margin-top: 6px;
        }

        .auth-card button[type="submit"]:hover,
        .auth-card input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(3, 65, 110, 0.45);
        }

        /* LINKS */
        .auth-card a {
            color: #03416E;
            font-size: 13px;
            text-decoration: underline;
            opacity: 0.75;
            transition: opacity 0.2s;
        }

        .auth-card a:hover { opacity: 1; }

        /* FORM FOOTER */
        .auth-card .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            padding-top: 16px;
            border-top: 1px solid rgba(3, 65, 110, 0.1);
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

        <div class="main">
            <div class="hero-text">
                <img src="{{ asset('images/logo.png') }}" alt="Wellmeadows Logo" class="hero-logo">
                <h1 class="hero-title">Wellmeadows Hospital</h1>
                <p>Caring for Life &bull; Est. 1974</p>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Wellmeadows Hospital. All rights reserved.
        </div>

    </div>
</body>
</html>