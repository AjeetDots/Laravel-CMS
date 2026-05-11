<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in | {{ $loginBrandName }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    @include('admin.auth.partials.auth-guest-theme')
</head>
<body class="auth-guest">
    <div class="auth-card">
        <div class="auth-brand">
            @if(!empty($backendLogo))
                <img src="{{ asset('storage/'.$backendLogo) }}" alt="{{ $loginBrandName }}">
            @else
                <div class="auth-mark">pr</div>
            @endif
        </div>
        <h1 class="auth-title">Welcome back</h1>
        <p class="auth-sub">Sign in to manage {{ $loginBrandName }}.</p>

        @if(session('status'))
            <div class="auth-alert auth-alert--ok"><i class="fas fa-check-circle me-2"></i>{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="auth-alert auth-alert--error"><i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label" for="login_email">Email address</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="login_email" class="form-control" placeholder="you@example.com"
                           value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>
            </div>
            <div class="mb-2">
                <label class="form-label" for="login_password">Password</label>
                <div class="input-icon pw-reveal">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="login_password" class="form-control pw-reveal__input" placeholder="Enter password" required autocomplete="current-password">
                    <button type="button" class="pw-reveal__btn" data-pw-toggle aria-label="Show password" title="Show password">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" style="font-size:.85rem;" for="remember">Remember me</label>
                </div>
                <a href="{{ route('admin.password.request') }}" style="font-size:.85rem; font-weight:600; color: var(--auth-primary-dark); text-decoration:none;">Forgot password?</a>
            </div>
            <button type="submit" class="btn btn-auth-primary">
                <i class="fas fa-sign-in-alt me-2"></i>Sign in
            </button>
        </form>

        <div class="auth-footer-links">
            <a href="{{ route('home') }}"><i class="fas fa-arrow-left me-1"></i>Back to website</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('admin.auth.partials.password-toggle-script')
</body>
</html>
