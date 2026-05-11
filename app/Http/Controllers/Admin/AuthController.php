<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ForgotPasswordRequest;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\ResetAdminPasswordRequest;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/admin');
        }

        return view('admin.auth.login', $this->authGuestBranding());
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect('/admin');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect('/admin');
        }

        return view('admin.auth.forgot-password', $this->authGuestBranding());
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $status = Password::broker('users')->sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, string $token)
    {
        if (Auth::check()) {
            return redirect('/admin');
        }

        $email = trim(urldecode((string) $request->query('email', '')));
        if ($email === '') {
            return redirect()
                ->route('admin.password.request')
                ->withErrors([
                    'email' => 'This reset link is missing a valid email address. Open the link from your email, or request a new password reset.',
                ]);
        }

        $user = User::query()->where('email', $email)->first();
        $broker = Password::broker('users');
        $expireMinutes = (int) config('auth.passwords.users.expire', 60);

        if (! $user || ! $broker->tokenExists($user, $token)) {
            return redirect()
                ->route('admin.password.request')
                ->withInput(['email' => $email])
                ->withErrors([
                    'email' => 'This password reset link is invalid, was already used, or has expired (links are valid for '.$expireMinutes.' minutes). Please request a new reset email.',
                ]);
        }

        return view('admin.auth.reset-password', array_merge($this->authGuestBranding(), [
            'token' => $token,
            'email' => $email,
        ]));
    }

    public function resetPassword(ResetAdminPasswordRequest $request)
    {
        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'password_change_failed_count' => 0,
                    'password_change_locked_until' => null,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')->with('status', __($status));
        }

        return back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * @return array{loginBrandName: string, backendLogo: ?string}
     */
    private function authGuestBranding(): array
    {
        try {
            $name = trim((string) (Setting::get('site_name') ?? ''));
            $logo = Setting::get('backend_logo');
        } catch (\Throwable) {
            $name = '';
            $logo = null;
        }

        return [
            'loginBrandName' => $name !== '' ? $name : (string) config('cms.panel_name', 'BOP CMS'),
            'backendLogo' => $logo ? (string) $logo : null,
        ];
    }
}
