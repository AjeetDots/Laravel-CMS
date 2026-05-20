<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password | {{ $loginBrandName }}</title>
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
        <h1 class="auth-title">Forgot password</h1>
        <p class="auth-sub">Enter the email on your admin account. If it matches a user, we will send a secure reset link.</p>

        @if(session('status'))
            <div class="auth-alert auth-alert--ok"><i class="fas fa-check-circle me-2"></i>{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="auth-alert auth-alert--error"><i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.password.email') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label" for="fp_email">Email address</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="fp_email" class="form-control" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>
            </div>
            <button type="submit" class="btn btn-auth-primary">
                <i class="fas fa-paper-plane me-2"></i>Send reset link
            </button>
        </form>

        <div class="auth-footer-links">
            <a href="{{ route('admin.login') }}"><i class="fas fa-arrow-left me-1"></i>Back to sign in</a>
            <span class="text-muted"> · </span>
            <a href="{{ route('home') }}">Website</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
