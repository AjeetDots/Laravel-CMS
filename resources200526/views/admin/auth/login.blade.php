<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in | {{ $loginBrandName }}</title>
    <link rel="icon" href="{{ $loginFaviconUrl }}" sizes="any">
    <link rel="shortcut icon" href="{{ $loginFaviconUrl }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('admin.auth.partials.auth-login-split-theme')
</head>
<body class="auth-login-page">
    <div class="auth-login-shell">
        <div class="auth-login-card">
            <div class="auth-login-visual">
                <img class="auth-login-visual__photo" src="{{ $loginHeroUrl }}" alt="" width="960" height="1200" loading="eager" decoding="async">
            </div>
            <div class="auth-login-form-panel">
                <div class="auth-login-brand">
                    <div class="auth-login-brand__crest">
                        @if(!empty($loginBrandLogoUrl))
                            <img class="auth-login-brand__logo auth-login-brand__logo--primary" src="{{ $loginBrandLogoUrl }}" alt="{{ $loginBrandName }}">
                        @else
                            <img class="auth-login-brand__favicon auth-login-brand__favicon--primary" src="{{ $loginFaviconUrl }}" alt="" width="80" height="80" decoding="async">
                        @endif
                        <p class="auth-login-brand__subtitle">{{ $loginBrandName }}</p>
                    </div>
                </div>

                <div class="auth-login-form-column">
                @if(session('status'))
                    <div class="auth-login-alert auth-login-alert--ok"><i class="fas fa-check-circle me-2"></i>{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="auth-login-alert auth-login-alert--error"><i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('admin.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="login_email">Email</label>
                        <input type="email" name="email" id="login_email" class="form-control" placeholder="you@example.com"
                               value="{{ old('email') }}" required autofocus autocomplete="username">
                    </div>
                    <div class="auth-field-row">
                        <label class="form-label mb-0" for="login_password">Password</label>
                        <a href="{{ route('admin.password.request') }}" class="auth-login-forgot">Forgot password?</a>
                    </div>
                    <div class="mb-3 auth-login-pw-wrap pw-reveal">
                        <input type="password" name="password" id="login_password" class="form-control pw-reveal__input" placeholder="Enter password here" required autocomplete="current-password">
                        <button type="button" class="auth-login-pw-toggle pw-reveal__btn" data-pw-toggle aria-label="Show password" title="Show password">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn-auth-login">Sign In</button>
                </form>

                <div class="auth-login-footer">
                    <a href="{{ route('home') }}"><i class="fas fa-arrow-left me-1"></i>Back to website</a>
                </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('admin.auth.partials.password-toggle-script')
</body>
</html>
