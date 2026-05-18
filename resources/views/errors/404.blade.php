@php
    $redirectUrl = route('home');
    try {
        $siteName = trim((string) (\App\Models\Setting::get('site_name') ?? ''));
        $siteLogo = \App\Models\Setting::get('site_logo');
    } catch (\Throwable $e) {
        $siteName = '';
        $siteLogo = null;
    }
    $siteName = $siteName !== '' ? $siteName : trim((string) config('app.name', 'Site'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="refresh" content="5;url={{ e($redirectUrl) }}">
    <title>Page not found — {{ $siteName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #211f1c;
            --ink-mid: #5c5348;
            --cream: #f7f3ed;
            --cream-deep: #efe8de;
            --gold: #aa8453;
            --gold-dark: #927848;
            --border: rgba(42, 38, 34, 0.1);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            min-height: 100dvh;
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(ellipse 120% 80% at 50% -20%, rgba(170, 132, 83, 0.16), transparent 55%),
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
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow:
                0 1px 2px rgba(22, 19, 17, 0.04),
                0 24px 56px rgba(42, 38, 34, 0.08);
            padding: clamp(1.75rem, 4vw, 2.5rem) clamp(1.5rem, 4vw, 2.25rem);
        }
        .brand-logo {
            display: block;
            max-height: 72px;
            max-width: min(220px, 70vw);
            width: auto;
            margin: 0 auto 1.25rem;
            object-fit: contain;
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
        .code span { color: var(--gold); }
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
            color: var(--gold-dark);
            background: rgba(170, 132, 83, 0.12);
            border: 1px solid rgba(170, 132, 83, 0.28);
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
            transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
        }
        a.btn:active { transform: scale(0.98); }
        a.btn-primary {
            background: linear-gradient(180deg, var(--gold) 0%, var(--gold-dark) 100%);
            color: #fff;
            border: 1px solid var(--gold-dark);
            box-shadow: 0 2px 8px rgba(42, 38, 34, 0.12);
        }
        a.btn-primary:hover {
            filter: brightness(1.05);
            box-shadow: 0 4px 14px rgba(42, 38, 34, 0.14);
        }
        footer {
            margin-top: 2rem;
            font-size: 0.75rem;
            color: rgba(92, 83, 72, 0.55);
        }
    </style>
</head>
<body>
    <div class="panel" role="alert">
        @if(!empty($siteLogo))
            <a href="{{ $redirectUrl }}" aria-label="{{ $siteName }} — Home">
                <img src="{{ asset('storage/' . $siteLogo) }}" alt="" class="brand-logo" decoding="async">
            </a>
        @else
            <a href="{{ $redirectUrl }}" aria-label="{{ $siteName }} — Home">
                <img src="{{ asset('images/brand/footer-logo-bop.svg') }}" alt="" class="brand-logo" decoding="async">
            </a>
        @endif
        <p class="code" aria-hidden="true"><span>404</span></p>
        <h1>Page not found</h1>
        <p class="lead">
            This page may have moved or no longer exists.
            You will be taken to the home page in a few seconds.
        </p>
        <p class="countdown" aria-live="polite">
            Redirecting to home in <span id="seconds">5</span>s
        </p>
        <div class="actions">
            <a class="btn btn-primary" href="{{ $redirectUrl }}">Go to home now</a>
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
                window.location.replace(url);
            }, 5000);
        })();
    </script>
</body>
</html>
