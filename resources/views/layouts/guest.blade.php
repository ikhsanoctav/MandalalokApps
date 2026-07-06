<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo_Mandalaloka.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.mobile-responsive-styles')
    <style>
        :root {
            --guest-primary: #0f2e5c;
            --guest-primary-2: #1a498b;
            --guest-gold: #d4af37;
            --guest-line: rgba(226, 232, 240, 0.82);
            --guest-batik: url("{{ asset('images/batik-jabar-tile.svg') }}");
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(212, 175, 55, 0.14), transparent 28rem),
                linear-gradient(135deg, #f8fbff 0%, #ffffff 50%, #fffaf0 100%);
        }

        .guest-shell {
            position: relative;
            overflow: hidden;
        }

        .guest-shell::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: var(--guest-batik);
            background-size: 120px 120px;
            opacity: 0.045;
            pointer-events: none;
        }

        .guest-logo-wrap {
            width: 92px;
            height: 92px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(212, 175, 55, 0.28);
            box-shadow: 0 18px 42px rgba(15, 46, 92, 0.1);
            position: relative;
            z-index: 1;
        }

        .guest-card {
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.76);
            box-shadow: 0 24px 56px rgba(15, 46, 92, 0.12);
            backdrop-filter: blur(16px);
        }

        .guest-card input,
        .guest-card select,
        .guest-card textarea {
            border-radius: 14px;
            border-color: #dbe3ef;
        }

        .guest-card input:focus,
        .guest-card select:focus,
        .guest-card textarea:focus {
            border-color: var(--guest-primary-2);
            box-shadow: 0 0 0 4px rgba(26, 73, 139, 0.12);
        }

        .guest-card button[type="submit"],
        .guest-card .bg-gray-800 {
            background: linear-gradient(135deg, var(--guest-primary-2), var(--guest-primary)) !important;
            border-radius: 999px;
            box-shadow: 0 12px 24px rgba(15, 46, 92, 0.18);
        }

        @media (max-width: 640px) {
            .guest-logo-wrap {
                width: 76px;
                height: 76px;
                border-radius: 20px;
            }
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="guest-shell min-h-screen flex flex-col sm:justify-center items-center px-3 pt-6 sm:pt-0">
        <div class="guest-logo-wrap">
            <a href="/">
                <x-application-logo class="w-16 h-16 fill-current text-gray-500" />
            </a>
        </div>

        <div
            class="guest-card w-[calc(100%-1.5rem)] sm:w-full sm:max-w-md mt-6 px-4 sm:px-6 py-5 bg-white/88 shadow-md overflow-hidden rounded-2xl sm:rounded-[1.5rem]">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
