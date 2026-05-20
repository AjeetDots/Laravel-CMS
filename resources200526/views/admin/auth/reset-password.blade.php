<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set new password | {{ $loginBrandName }}</title>
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
        <h1 class="auth-title">Choose a new password</h1>
        <p class="auth-sub">Use a strong password you have not used elsewhere. After saving, sign in with your email and this password.</p>

        @if($errors->any())
            <div class="auth-alert auth-alert--error"><i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
                <label class="form-label" for="rp_email">Email</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="rp_email" class="form-control" value="{{ old('email', $email) }}" required readonly autocomplete="username" aria-readonly="true" title="Email is fixed from your reset link">
                </div>
                <div class="form-text" style="font-size: 0.78rem; color: #64748b;">This address is set from your reset link and cannot be changed.</div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="rp_password">New password</label>
                <div class="input-icon pw-reveal">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="rp_password" class="form-control pw-reveal__input" required autocomplete="new-password" minlength="8">
                    <button type="button" class="pw-reveal__btn" data-pw-toggle aria-label="Show password" title="Show password">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label" for="rp_password_confirmation">Confirm password</label>
                <div class="input-icon pw-reveal">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password_confirmation" id="rp_password_confirmation" class="form-control pw-reveal__input" required autocomplete="new-password" minlength="8">
                    <button type="button" class="pw-reveal__btn" data-pw-toggle aria-label="Show password" title="Show password">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-auth-primary">
                <i class="fas fa-shield-halved me-2"></i>Update password
            </button>
        </form>

        <div class="auth-footer-links">
            <a href="{{ route('admin.login') }}"><i class="fas fa-arrow-left me-1"></i>Back to sign in</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('admin.auth.partials.password-toggle-script')
</body>
</html>
