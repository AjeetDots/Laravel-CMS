<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfirmProfileEmailOtpRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Mail\AdminEmailChangeOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->fresh();

        if ($user->password_change_locked_until?->isPast()) {
            $user->forceFill([
                'password_change_locked_until' => null,
                'password_change_failed_count' => 0,
            ])->save();
            $user->refresh();
        }

        if ($user->email_change_locked_until?->isPast()) {
            $user->forceFill([
                'email_change_locked_until' => null,
                'email_change_verify_failed_count' => 0,
            ])->save();
            $user->refresh();
        }

        if ($user->email_change_pending
            && $user->email_change_otp_expires_at?->isPast()
            && ! $user->email_change_locked_until?->isFuture()) {
            $this->clearEmailChangePendingFields($user);
            $user->save();
            $user->refresh();
        }

        $otpTtl = (int) config('cms.admin_email_change_otp_ttl', 15);
        $lastOtpSent = $user->email_change_otp_last_sent_at;
        $emailChangeOtpResendAvailableAt = $lastOtpSent?->copy()->addMinutes($otpTtl);
        $canResendEmailOtp = ! $lastOtpSent || now()->greaterThanOrEqualTo($emailChangeOtpResendAvailableAt);

        return view('admin.profile.edit', [
            'user' => $user,
            'passwordChangeLocked' => (bool) ($user->password_change_locked_until?->isFuture()),
            'passwordChangeLockedUntil' => $user->password_change_locked_until,
            'emailChangePending' => $user->email_change_pending,
            'emailChangeOtpExpiresAt' => $user->email_change_otp_expires_at,
            'emailChangeLocked' => (bool) ($user->email_change_locked_until?->isFuture()),
            'emailChangeLockedUntil' => $user->email_change_locked_until,
            'emailChangeOtpTtlMinutes' => $otpTtl,
            'canResendEmailOtp' => $canResendEmailOtp,
            'emailChangeOtpResendAvailableAt' => $emailChangeOtpResendAvailableAt,
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user()->fresh();
        $data = $request->validated();
        $requestedEmail = trim($data['email']);

        if ($request->filled('password')) {
            if ($user->password_change_locked_until?->isFuture()) {
                $until = $user->password_change_locked_until
                    ->timezone(config('app.timezone'))
                    ->format('M j, Y g:i a');

                return back()->withErrors([
                    'current_password' => 'Password changes are temporarily locked until '.$until.'. Sign out and use Forgot password on the login page if you need to reset your password sooner.',
                ])->withInput();
            }

            if (! Hash::check($request->input('current_password'), $user->getAuthPassword())) {
                $n = (int) $user->password_change_failed_count + 1;

                if ($n >= 3) {
                    $user->forceFill([
                        'password_change_failed_count' => 0,
                        'password_change_locked_until' => now()->addHours(24),
                    ])->save();

                    return back()->withErrors([
                        'current_password' => 'Three incorrect current passwords. Password changes are blocked for 24 hours. Sign out and use Forgot password if you need to reset your login.',
                    ])->withInput();
                }

                $user->password_change_failed_count = $n;
                $user->save();

                $left = 3 - $n;

                return back()->withErrors([
                    'current_password' => 'Current password is incorrect. '.$left.' attempt'.($left === 1 ? '' : 's').' remaining before a 24-hour lockout.',
                ])->withInput();
            }

            $user->forceFill([
                'password_change_failed_count' => 0,
                'password_change_locked_until' => null,
            ])->save();
        }

        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        unset($data['avatar'], $data['remove_avatar'], $data['password_confirmation'], $data['current_password']);

        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        } else {
            unset($data['password']);
        }

        unset($data['email']);

        $currentNorm = strtolower($user->email);
        $requestedNorm = strtolower($requestedEmail);

        if ($requestedNorm === $currentNorm) {
            if ($user->email_change_pending) {
                $this->clearEmailChangePendingFields($user);
            }
            $user->email = $requestedEmail;
        } else {
            if ($user->email_change_locked_until?->isFuture()) {
                $until = $user->email_change_locked_until
                    ->timezone(config('app.timezone'))
                    ->format('M j, Y g:i a');

                return back()->withErrors([
                    'email' => 'Email verification is locked until '.$until.' after three incorrect codes. Try again later or contact another administrator.',
                ])->withInput();
            }

            $pendingNorm = $user->email_change_pending ? strtolower(trim((string) $user->email_change_pending)) : null;
            $otpStillValid = $user->email_change_otp_hash
                && $user->email_change_otp_expires_at
                && $user->email_change_otp_expires_at->isFuture();

            $reuseOtp = ($pendingNorm === $requestedNorm && $otpStillValid);

            if (! $reuseOtp) {
                $otp = str_pad((string) random_int(0, 999_999), 6, '0', STR_PAD_LEFT);
                $ttl = (int) config('cms.admin_email_change_otp_ttl', 15);
                $hash = hash_hmac('sha256', $otp, (string) config('app.key'));

                // Persist OTP hash before sending mail so the row always reflects the active code,
                // and failed mail sends roll back pending state with a second save().
                $user->forceFill([
                    'email_change_pending' => $requestedEmail,
                    'email_change_otp_hash' => $hash,
                    'email_change_otp_expires_at' => now()->addMinutes($ttl),
                    'email_change_otp_last_sent_at' => now(),
                    'email_change_verify_failed_count' => 0,
                ])->save();

                try {
                    Mail::to($user->email)->send(new AdminEmailChangeOtpMail($user, $otp, $requestedEmail, $ttl));
                } catch (\Throwable $e) {
                    $this->clearEmailChangePendingFields($user);
                    $user->save();
                    report($e);

                    return back()->withErrors([
                        'email' => 'We could not send the verification email. Check your mail configuration (e.g. .env MAIL_*) and try again.',
                    ])->withInput();
                }
            }
        }

        $user->fill($data);
        $user->save();

        if ($requestedNorm !== $currentNorm) {
            return back()->with(
                'success',
                'A 6-digit verification code was sent to your current email ('.$user->email.'). Enter it below to confirm your new address. This code expires in '.(int) config('cms.admin_email_change_otp_ttl', 15).' minutes and works only once.'
            );
        }

        return back()->with('success', 'Account saved.');
    }

    public function confirmEmailOtp(ConfirmProfileEmailOtpRequest $request)
    {
        $user = Auth::user()->fresh();

        if (! $user->email_change_pending || ! $user->email_change_otp_hash) {
            return back()->withErrors([
                'email_otp' => 'There is no pending email change. Update your email on the form above first.',
            ]);
        }

        if ($user->email_change_locked_until?->isFuture()) {
            $until = $user->email_change_locked_until
                ->timezone(config('app.timezone'))
                ->format('M j, Y g:i a');

            return back()->withErrors([
                'email_otp' => 'Too many incorrect codes. Try again after '.$until.'.',
            ]);
        }

        if ($user->email_change_otp_expires_at?->isPast()) {
            return back()->withErrors([
                'email_otp' => 'This code has expired. Save your profile again with the new email to receive a fresh code.',
            ]);
        }

        $otp = $request->input('email_otp');
        $expected = (string) $user->email_change_otp_hash;
        $actual = hash_hmac('sha256', $otp, (string) config('app.key'));

        if (! hash_equals($expected, $actual)) {
            $n = (int) $user->email_change_verify_failed_count + 1;

            if ($n >= 3) {
                $user->forceFill([
                    'email_change_pending' => null,
                    'email_change_otp_hash' => null,
                    'email_change_otp_expires_at' => null,
                    'email_change_otp_last_sent_at' => null,
                    'email_change_verify_failed_count' => 0,
                    'email_change_locked_until' => now()->addHours(24),
                ])->save();

                return back()->withErrors([
                    'email_otp' => 'Three incorrect codes. Email verification is blocked for 24 hours.',
                ]);
            }

            $user->email_change_verify_failed_count = $n;
            $user->save();

            $left = 3 - $n;

            return back()->withErrors([
                'email_otp' => 'That code is incorrect. '.$left.' attempt'.($left === 1 ? '' : 's').' remaining before a 24-hour lockout.',
            ]);
        }

        $newEmail = trim((string) $user->email_change_pending);

        if (User::query()->where('email', $newEmail)->where('id', '!=', $user->id)->exists()) {
            $this->clearEmailChangePendingFields($user);
            $user->save();

            return back()->withErrors([
                'email' => 'That email address is no longer available. Choose a different one and save again.',
            ]);
        }

        $user->email = $newEmail;
        $this->clearEmailChangePendingFields($user);
        $user->email_change_locked_until = null;
        $user->save();

        return back()->with(
            'success',
            'Your login email is now '.$newEmail.'. Use it the next time you sign in.'
        );
    }

    public function resendEmailOtp(Request $request)
    {
        $user = Auth::user()->fresh();

        if ($user->email_change_locked_until?->isFuture()) {
            return back()->withErrors([
                'email_otp' => 'Verification is temporarily locked. Try again after the lock expires.',
            ]);
        }

        if (! $user->email_change_pending) {
            return back()->withErrors([
                'email_otp' => 'There is no pending email change to resend a code for.',
            ]);
        }

        $ttl = (int) config('cms.admin_email_change_otp_ttl', 15);
        if ($user->email_change_otp_last_sent_at) {
            $nextResend = $user->email_change_otp_last_sent_at->copy()->addMinutes($ttl);
            if (now()->lt($nextResend)) {
                return back()->withErrors([
                    'email_otp' => 'A code was already sent. You can request another after '.$nextResend->timezone(config('app.timezone'))->format('M j, Y g:i a').' (once per '.$ttl.' minutes, same as the code validity).',
                ]);
            }
        }

        $otp = str_pad((string) random_int(0, 999_999), 6, '0', STR_PAD_LEFT);
        $hash = hash_hmac('sha256', $otp, (string) config('app.key'));

        try {
            Mail::to($user->email)->send(new AdminEmailChangeOtpMail($user, $otp, (string) $user->email_change_pending, $ttl));
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors([
                'email_otp' => 'Could not send email. Check your mail configuration.',
            ]);
        }

        $user->forceFill([
            'email_change_otp_hash' => $hash,
            'email_change_otp_expires_at' => now()->addMinutes($ttl),
            'email_change_otp_last_sent_at' => now(),
        ])->save();

        return back()->with('success', 'A new verification code was sent to '.$user->email.'. It expires in '.$ttl.' minutes.');
    }

    public function cancelEmailChange(Request $request)
    {
        $user = Auth::user()->fresh();

        if (! $user->email_change_pending) {
            return back()->with('success', 'Nothing to cancel.');
        }

        $this->clearEmailChangePendingFields($user);
        $user->save();

        return back()->with('success', 'Pending email change was cancelled. Your login email is unchanged.');
    }

    /**
     * Clears pending email + OTP fields only (does not clear an active lock).
     */
    private function clearEmailChangePendingFields(User $user): void
    {
        $user->forceFill([
            'email_change_pending' => null,
            'email_change_otp_hash' => null,
            'email_change_otp_expires_at' => null,
            'email_change_otp_last_sent_at' => null,
            'email_change_verify_failed_count' => 0,
        ]);
    }
}
