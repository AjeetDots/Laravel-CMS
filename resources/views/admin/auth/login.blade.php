<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 48px 44px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 60px rgba(0,0,0,.3);
        }
        .login-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #2563eb, #60a5fa);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; color: #fff;
            margin: 0 auto 24px;
        }
        .login-title { font-size: 1.6rem; font-weight: 800; color: #0f172a; text-align: center; margin-bottom: 6px; }
        .login-sub { color: #64748b; text-align: center; font-size: .9rem; margin-bottom: 32px; }
        .form-label { font-weight: 600; font-size: .85rem; color: #374151; }
        .form-control {
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 12px 16px; font-size: .92rem;
        }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
        .btn-login {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none; color: #fff; width: 100%;
            padding: 13px; border-radius: 10px;
            font-weight: 700; font-size: 1rem;
            transition: all .3s;
        }
        .btn-login:hover { background: linear-gradient(135deg, #1d4ed8, #1e40af); color: #fff; }
        .input-icon { position: relative; }
        .input-icon i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .input-icon .form-control { padding-left: 40px; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-icon"><i class="fas fa-bolt"></i></div>
        <h1 class="login-title">Welcome Back</h1>
        <p class="login-sub">Sign in to your CMS admin panel</p>

        @if($errors->any())
            <div class="alert" style="background:#fee2e2; color:#b91c1c; border-radius:10px; padding:12px 16px; font-size:.88rem; margin-bottom:20px;">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="admin@cms.com"
                           value="{{ old('email') }}" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" style="font-size:.85rem;" for="remember">Remember me</label>
                </div>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" style="color:#64748b; font-size:.85rem; text-decoration:none;">
                <i class="fas fa-arrow-left me-1"></i>Back to Website
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
