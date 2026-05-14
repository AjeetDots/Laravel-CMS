@extends('layouts.admin')

@section('title', 'Account')

@section('content')

<style>
    .account-hero {
        border-radius: 14px;
        padding: 22px 24px;
        background: linear-gradient(135deg, #2f2418 0%, #17120e 55%, #1a1410 100%);
        color: #f8fafc;
        margin-bottom: 1.25rem;
        box-shadow: 0 14px 36px rgba(0,0,0,.18);
    }
    .account-hero h1 { font-size: 1.35rem; font-weight: 800; margin: 0 0 6px; letter-spacing: -.02em; }
    .account-hero p { margin: 0; color: #c4b59a; font-size: .9rem; max-width: 52rem; line-height: 1.5; }
    .account-card { border: 0; border-radius: 14px; box-shadow: 0 8px 28px rgba(15,23,42,.06); }
    .account-card .card-header {
        font-weight: 700;
        background: #fff;
        border-bottom: 1px solid #eef2f7;
        padding: 14px 18px;
        border-radius: 14px 14px 0 0;
    }
    .account-card .card-body { padding: 22px 20px; }
    .account-email-current {
        background: linear-gradient(180deg, #faf8f5 0%, #f4f1eb 100%);
        border: 1px solid #e8e0d4;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 1rem;
    }
    .account-email-current__label {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #8e7f67;
        margin-bottom: 4px;
    }
    .account-email-current__value {
        font-weight: 600;
        color: #1e293b;
        word-break: break-all;
    }
    .account-email-current__hint { font-size: .8rem; color: #64748b; margin-top: 8px; margin-bottom: 0; }
    .account-otp-card {
        border-radius: 12px;
        border: 1px solid #e8e0d4;
        border-left: 4px solid #b79860;
        background: #fffefb;
        padding: 18px 18px 16px;
        margin-top: 1.25rem;
    }
    .account-otp-card h6 { color: #2f2418; font-size: 1rem; margin-bottom: 10px; }
    .account-otp-card .otp-row {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 12px;
        margin-bottom: 14px;
    }
    .account-otp-card .otp-row .otp-field { flex: 1 1 140px; max-width: 200px; }
    .account-otp-card .otp-row .otp-field input {
        letter-spacing: 0.2em;
        font-weight: 600;
        text-align: center;
        font-size: 1.05rem;
    }
    .account-otp-card .otp-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        padding-top: 4px;
        border-top: 1px solid #f1e9dc;
    }
    .pw-details {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fafbfc;
        margin-top: 1rem;
    }
    .pw-details > summary {
        list-style: none;
        cursor: pointer;
        padding: 14px 16px;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        user-select: none;
    }
    .pw-details > summary::-webkit-details-marker { display: none; }
    .pw-details > summary::after {
        content: '\f078';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-size: .75rem;
        color: #94a3b8;
        transition: transform .2s;
    }
    .pw-details[open] > summary::after { transform: rotate(180deg); }
    .pw-details__body { padding: 0 16px 16px; border-top: 1px solid #e2e8f0; }
    .avatar-ring {
        width: 88px; height: 88px; border-radius: 50%; margin: 0 auto 14px;
        padding: 3px;
        background: linear-gradient(135deg, #b79860, #d4b77a);
        box-shadow: 0 10px 28px rgba(0,0,0,.12);
    }
    .avatar-ring__in {
        width: 100%; height: 100%; border-radius: 50%; overflow: hidden;
        background: #f8fafc; border: 2px solid #fff;
    }
    .avatar-fallback {
        width: 100%; height: 100%;
        background: linear-gradient(135deg, #b79860, #9a7e4f);
        display:flex; align-items:center; justify-content:center;
        font-size: 2rem; color:#fff; font-weight:800;
    }
    .account-preview { min-height: 280px; }
    .account-preview .pending-pill {
        font-size: .72rem;
        max-width: 100%;
        word-break: break-all;
        white-space: normal;
        line-height: 1.35;
    }
    .pw-reveal { position: relative; }
    .pw-reveal__input { padding-right: 2.75rem; }
    .pw-reveal__btn {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #64748b;
        width: 2.5rem;
        height: 2.5rem;
        padding: 0;
        margin: 0;
        border-radius: 8px;
        line-height: 1;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }
    .pw-reveal__btn i { font-size: 0.95rem; line-height: 1; pointer-events: none; }
    .pw-reveal__btn:hover { color: #475569; background: rgba(241, 245, 249, 0.95); }
    .pw-reveal__btn:focus-visible { outline: 2px solid rgba(183, 152, 96, 0.45); outline-offset: 2px; }
</style>

<div class="account-hero">
    <h1>Account</h1>
    <p>Your display name, email, profile photo, and password for this admin panel. Changing your email sends a one-time code to your <strong>current</strong> address; after you confirm, your <strong>login email</strong> updates. Expand “Change password” only when you want to update it; your current password is required.</p>
</div>

<div class="row g-4 align-items-stretch">
    <div class="col-lg-7">
        <div class="card account-card h-100">
            <div class="card-header">Profile &amp; security</div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success mb-4 py-2">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="profile_avatar">Profile photo</label>
                        <input type="file" name="avatar" id="profile_avatar" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">JPEG, PNG or WebP, up to 2&nbsp;MB.</div>
                        @if($user->avatar)
                            <div class="form-check mt-2">
                                <input type="checkbox" name="remove_avatar" value="1" class="form-check-input" id="remove_avatar" @checked(old('remove_avatar'))>
                                <label class="form-check-label" for="remove_avatar">Remove current photo</label>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required maxlength="100">
                    </div>

                    @if($emailChangePending)
                        <div class="account-email-current">
                            <div class="account-email-current__label">Current sign-in email</div>
                            <div class="account-email-current__value">{{ $user->email }}</div>
                            <p class="account-email-current__hint">The verification code was sent to this inbox. Your preview card on the right also shows this address until you confirm.</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="profile_email">New email (pending confirmation) <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="profile_email" class="form-control" value="{{ old('email', $emailChangePending) }}" required>
                            <div class="form-text">You can correct typos here, then <strong>Save changes</strong> to receive a fresh code if the address changed. Wrong code three times blocks verification for 24 hours; each code expires in {{ $emailChangeOtpTtlMinutes }} minutes.</div>
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label" for="profile_email">Email address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="profile_email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            <div class="form-text">
                                This is the address you use to sign in. If you change it, we email a 6-digit code to <strong>your current inbox</strong> first. Wrong code three times blocks verification for 24 hours; each code expires in {{ $emailChangeOtpTtlMinutes }} minutes and works only once.
                            </div>
                        </div>
                    @endif

                    @if($emailChangeLocked && $emailChangeLockedUntil)
                        <div class="alert alert-warning small mb-3">
                            Email verification is <strong>locked until {{ $emailChangeLockedUntil->timezone(config('app.timezone'))->format('M j, Y g:i a') }}</strong> after three incorrect codes. You can still update your name or photo; you cannot request a new email change until then.
                        </div>
                    @endif

                    @if($passwordChangeLocked && $passwordChangeLockedUntil)
                        <div class="alert alert-warning small mb-3">
                            Password changes are <strong>locked until {{ $passwordChangeLockedUntil->timezone(config('app.timezone'))->format('M j, Y g:i a') }}</strong> after three incorrect current-password attempts.
                            If you still need access, <strong>sign out</strong> and use <strong>Forgot password</strong> on the login page to receive a reset link by email.
                        </div>
                    @endif

                    <details class="pw-details" id="password-panel" @if($errors->has('password') || $errors->has('password_confirmation') || $errors->has('current_password')) open @endif>
                        <summary>Change password <span class="text-muted fw-normal" style="font-size:.82rem;">(optional)</span></summary>
                        <div class="pw-details__body pt-3">
                            @if($passwordChangeLocked)
                                <p class="text-muted small mb-0">This section is disabled while the lock is active.</p>
                            @else
                                <p class="text-muted small">Leave all three fields blank to keep your existing password. We never show your current password.</p>
                                <div class="mb-3">
                                    <label class="form-label" for="profile_current_password">Current password</label>
                                    <div class="pw-reveal">
                                        <input type="password" name="current_password" id="profile_current_password" class="form-control pw-reveal__input" autocomplete="current-password"
                                               @disabled($passwordChangeLocked)>
                                        <button type="button" class="pw-reveal__btn" data-pw-toggle aria-label="Show password" title="Show password" @disabled($passwordChangeLocked)>
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="profile_new_password">New password</label>
                                    <div class="pw-reveal">
                                        <input type="password" name="password" id="profile_new_password" class="form-control pw-reveal__input" autocomplete="new-password" @disabled($passwordChangeLocked)>
                                        <button type="button" class="pw-reveal__btn" data-pw-toggle aria-label="Show password" title="Show password" @disabled($passwordChangeLocked)>
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label" for="profile_password_confirmation">Confirm new password</label>
                                    <div class="pw-reveal">
                                        <input type="password" name="password_confirmation" id="profile_password_confirmation" class="form-control pw-reveal__input" autocomplete="new-password" @disabled($passwordChangeLocked)>
                                        <button type="button" class="pw-reveal__btn" data-pw-toggle aria-label="Show password" title="Show password" @disabled($passwordChangeLocked)>
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </details>

                    <div class="mt-4 d-flex flex-wrap gap-2 align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save changes
                        </button>
                    </div>
                </form>

                @if($emailChangePending && $emailChangeOtpExpiresAt && ! $emailChangeLocked)
                    <div class="account-otp-card" @if(! $canResendEmailOtp && $emailChangeOtpResendAvailableAt) data-resend-until="{{ $emailChangeOtpResendAvailableAt->toIso8601String() }}" @endif>
                        <h6 class="fw-bold mb-0">Confirm new email</h6>
                        <p class="small text-muted mt-2 mb-2">Enter the 6-digit code sent to <strong>{{ $user->email }}</strong> to switch your login to <strong>{{ $emailChangePending }}</strong>. This code is valid for <strong>{{ $emailChangeOtpTtlMinutes }} minutes</strong> and expires {{ $emailChangeOtpExpiresAt->timezone(config('app.timezone'))->format('M j, Y g:i a') }}.</p>
                        <p class="small text-muted mb-3">You can only use <strong>Resend code</strong> after <strong>{{ $emailChangeOtpTtlMinutes }} minutes</strong> from the last send (same window as the code). The server enforces this even if the button were enabled.</p>
                        @if(! $canResendEmailOtp && $emailChangeOtpResendAvailableAt)
                            <p class="small mb-3 py-2 px-3 rounded-2" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                Next resend allowed: <strong>{{ $emailChangeOtpResendAvailableAt->timezone(config('app.timezone'))->format('M j, Y g:i a') }}</strong>
                                <span class="d-block text-muted mt-1" id="profile-resend-cooldown"></span>
                            </p>
                        @endif
                        @if($errors->has('email_otp'))
                            <div class="alert alert-danger py-2 small mb-3">{{ $errors->first('email_otp') }}</div>
                        @endif
                        <form action="{{ route('admin.profile.confirm-email') }}" method="POST" class="mb-0">
                            @csrf
                            <div class="otp-row">
                                <div class="otp-field">
                                    <label class="form-label small mb-1" for="email_otp">Verification code</label>
                                    <input type="text" name="email_otp" id="email_otp" class="form-control" inputmode="numeric" autocomplete="one-time-code" maxlength="6" pattern="[0-9]*" placeholder="000000" required>
                                </div>
                                <div class="flex-shrink-0 pb-1">
                                    <button type="submit" class="btn btn-primary px-4">Confirm email</button>
                                </div>
                            </div>
                        </form>
                        <div class="otp-actions mt-2 pt-3">
                            <form action="{{ route('admin.profile.resend-email-otp') }}" method="POST" class="d-inline m-0">
                                @csrf
                                <button type="submit" id="profile-resend-otp-btn" class="btn btn-outline-secondary btn-sm" @disabled(! $canResendEmailOtp)>Resend code</button>
                            </form>
                            <form action="{{ route('admin.profile.cancel-email-change') }}" method="POST" class="d-inline m-0"
                                  data-swal-confirm="Your login address will stay as it is now."
                                  data-swal-title="Cancel this email change?"
                                  data-swal-confirm-text="Yes, cancel change"
                                  data-swal-icon="warning">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">Cancel change</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card account-card account-preview h-100">
            <div class="card-body text-center p-4 d-flex flex-column align-items-center justify-content-center">
                <div class="avatar-ring">
                    <div class="avatar-ring__in">
                        @if($user->avatar)
                            <img src="{{ asset('storage/'.$user->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                        @else
                            <div class="avatar-fallback">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                    </div>
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-1 small text-break w-100 px-1">{{ $user->email }}</p>
                @if($emailChangePending)
                    <p class="small text-muted mb-2">Pending after confirmation</p>
                    <span class="badge rounded-pill pending-pill mb-3" style="background:#fff7ed;color:#9a3412;border:1px solid #fed7aa;">{{ $emailChangePending }}</span>
                @endif
                <span class="badge rounded-pill" style="background:#ecfdf3;color:#166534;padding:8px 14px;font-weight:600;">Administrator</span>
            </div>
        </div>
    </div>
</div>

@include('admin.auth.partials.password-toggle-script')
@if($emailChangePending && $emailChangeOtpExpiresAt && ! $emailChangeLocked && ! $canResendEmailOtp && $emailChangeOtpResendAvailableAt)
<script>
(function () {
    var card = document.querySelector('.account-otp-card[data-resend-until]');
    var btn = document.getElementById('profile-resend-otp-btn');
    var label = document.getElementById('profile-resend-cooldown');
    if (!card || !btn || !label) return;
    var until = Date.parse(card.getAttribute('data-resend-until'));
    if (Number.isNaN(until)) return;
    function tick() {
        var ms = until - Date.now();
        if (ms <= 0) {
            btn.disabled = false;
            label.textContent = 'You can resend now — click “Resend code”.';
            return;
        }
        var s = Math.ceil(ms / 1000);
        var m = Math.floor(s / 60);
        var r = s % 60;
        label.textContent = 'Unlocks in ' + m + ':' + (r < 10 ? '0' : '') + r;
        setTimeout(tick, 1000);
    }
    tick();
})();
</script>
@endif

@endsection
