@php
    $redirectUrl = Auth::check() ? route('admin.dashboard') : route('home');
    $siteName = trim((string) config('app.name', 'Site'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="5;url={{ e($redirectUrl) }}">
    <title>Page not found (404) — {{ $siteName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #161311;
            --ink-mid: #3a342e;
            --cream: #f7f3ed;
            --cream-deep: #efe8de;
            --wine: #c96b3f;
            --wine-dark: #a8502a;
            --gold: #b8925a;
            --border: rgba(22, 19, 17, 0.12);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            min-height: 100dvh;
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(ellipse 120% 80% at 50% -20%, rgba(184, 146, 90, 0.18), transparent 55%),
                linear-gradient(180deg, #fffefb 0%, var(--cream) 45%, var(--cream-deep) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: clamp(1.25rem, 4vw, 2.5rem);
            text-align: center;
            -webkit-font-smoothing: antialiased;
        }
        .panel {
            width: 100%;
            max-width: 32rem;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow:
                0 1px 2px rgba(22, 19, 17, 0.04),
                0 24px 56px rgba(42, 38, 34, 0.08);
            padding: clamp(1.75rem, 4vw, 2.5rem) clamp(1.5rem, 4vw, 2.25rem);
        }
        .code {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(3.25rem, 10vw, 4.25rem);
            font-weight: 600;
            letter-spacing: -0.03em;
            line-height: 1;
            color: var(--ink);
            margin: 0 0 0.35rem;
        }
        .code span {
            color: var(--gold);
        }
        h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(1.25rem, 3.5vw, 1.5rem);
            font-weight: 600;
            margin: 0 0 0.75rem;
            color: var(--ink);
        }
        p.lead {
            margin: 0 0 1.25rem;
            font-size: 0.98rem;
            line-height: 1.65;
            color: var(--ink-mid);
        }
        .countdown {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--wine-dark);
            background: rgba(201, 107, 63, 0.1);
            border: 1px solid rgba(201, 107, 63, 0.22);
            border-radius: 999px;
            padding: 0.45rem 1rem;
            margin-bottom: 1.5rem;
        }
        .countdown #seconds { font-variant-numeric: tabular-nums; min-width: 1ch; }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            justify-content: center;
        }
        a.btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }
        a.btn:active { transform: scale(0.98); }
        a.btn-primary {
            background: linear-gradient(180deg, var(--gold) 0%, #9a7b45 100%);
            color: #fff;
            border: 1px solid rgba(74, 60, 32, 0.35);
            box-shadow: 0 2px 8px rgba(42, 38, 34, 0.12);
        }
        a.btn-primary:hover {
            filter: brightness(1.05);
            box-shadow: 0 4px 14px rgba(42, 38, 34, 0.14);
        }
        a.btn-outline {
            background: #fff;
            color: var(--ink-mid);
            border: 1px solid rgba(22, 19, 17, 0.18);
        }
        a.btn-outline:hover {
            border-color: var(--wine);
            color: var(--wine-dark);
        }
        footer {
            margin-top: 2rem;
            font-size: 0.75rem;
            color: rgba(58, 52, 46, 0.55);
        }
    </style>
</head>
<body>
    <div class="panel" role="alert">
        <p class="code" aria-hidden="true"><span>404</span></p>
        <h1>Page not found</h1>
        <p class="lead">
            The address may have changed, or the page may have been removed.
            You will be redirected automatically in a few seconds.
        </p>
        <p class="countdown" aria-live="polite">
            Redirecting in <span id="seconds">5</span>s to
            @auth
                <strong>Dashboard</strong>
            @else
                <strong>Home</strong>
            @endauth
        </p>
        <div class="actions">
            <a class="btn btn-primary" href="{{ $redirectUrl }}">Continue now</a>
            @auth
                <a class="btn btn-outline" href="{{ route('home') }}">View website</a>
            @else
                <a class="btn btn-outline" href="{{ route('home') }}">Home</a>
            @endauth
        </div>
    </div>
    <footer>{{ $siteName }}</footer>
    <script>
        (function () {
            var url = @json($redirectUrl);
            var el = document.getElementById('seconds');
            var left = 5;
            var t = setInterval(function () {
                left--;
                if (el) el.textContent = String(Math.max(0, left));
                if (left <= 0) clearInterval(t);
            }, 1000);
            setTimeout(function () {
                window.location.href = url;
            }, 5000);
        })();
    </script>
</body>
</html>
