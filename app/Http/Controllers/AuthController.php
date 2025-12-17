<?php

namespace App\Http\Controllers;

use App\Mail\TwoFactorCode;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 8;
    private const LOCKOUT_MINUTES = 15;
    private const TWO_FACTOR_ATTEMPTS = 5;

    public function __construct(private ActivityLogger $logger)
    {
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan, coba lagi dalam {$seconds} detik.",
            ])->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials)) {
            RateLimiter::hit($throttleKey, self::LOCKOUT_MINUTES * 60);
            $this->logger->log(null, 'login.failed', 'Percobaan login gagal', null, [
                'email' => $request->input('email'),
            ]);
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->withInput($request->only('email'));
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        $user = Auth::user();
        $defaultRedirect = $user->hasPermission('access-admin')
            ? route('admin.dashboard')
            : route('alumni.forum.index');

        if ($user->two_factor_enabled) {
            $intended = session('url.intended') ?? $defaultRedirect;
            $request->session()->put('two_factor.pending_user', $user->id);
            $request->session()->put('two_factor.intended', $intended);
            $this->sendTwoFactorCode($user);
            $this->logger->log($user, 'login.challenge', 'Login menunggu konfirmasi 2FA');
            Auth::logout();
            return redirect()->route('two-factor')->with('status', 'Kode 2FA telah dikirim ke email Anda.');
        }

        $this->logger->log($user, 'login.success', 'Login berhasil');
        return redirect()->intended($defaultRedirect);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $this->logger->log($user, 'logout', 'Akun keluar');

        return redirect()->route('login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        $this->logger->log(null, 'password.reset.requested', 'Permintaan reset password', null, [
            'email' => $request->input('email'),
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(string $token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $redirectTo = session('url.intended');

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) use ($request, &$redirectTo) {
                $user->password = $password;
                $user->save();
                event(new PasswordReset($user));
                $this->logger->log($user, 'password.reset', 'Pengguna memperbarui password melalui tautan reset');
                Auth::login($user);
                $redirectTo ??= $user->hasPermission('access-admin')
                    ? route('admin.dashboard')
                    : route('alumni.forum.index');
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->intended($redirectTo ?? route('home'))->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showTwoFactorForm()
    {
        if (! session()->has('two_factor.pending_user')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $pendingId = session('two_factor.pending_user');
        if (! $pendingId) {
            return redirect()->route('login');
        }

        if (RateLimiter::tooManyAttempts($this->twoFactorAttemptKey($pendingId), self::TWO_FACTOR_ATTEMPTS)) {
            return back()->withErrors(['code' => 'Terlalu banyak percobaan 2FA. Coba lagi nanti.']);
        }

        $expectedCode = Cache::get($this->twoFactorCacheKey($pendingId));
        if (! $expectedCode || ! hash_equals($expectedCode, $request->input('code'))) {
            RateLimiter::hit($this->twoFactorAttemptKey($pendingId), self::LOCKOUT_MINUTES * 60);
            $this->logger->log(null, 'twofactor.failed', 'Kode 2FA tidak valid');
            return back()->withErrors(['code' => 'Kode 2FA tidak cocok.']);
        }

        Cache::forget($this->twoFactorCacheKey($pendingId));
        RateLimiter::clear($this->twoFactorAttemptKey($pendingId));

        $user = User::find($pendingId);
        if (! $user) {
            return redirect()->route('login');
        }

        Auth::login($user);
        session()->forget(['two_factor.pending_user']);
        $intended = session()->pull('two_factor.intended', route('admin.dashboard'));
        $request->session()->regenerate();

        $this->logger->log($user, 'login.success', 'Login 2FA berhasil');

        return redirect()->to($intended);
    }

    public function resendTwoFactorCode(Request $request)
    {
        $pendingId = session('two_factor.pending_user');
        if (! $pendingId) {
            return redirect()->route('login');
        }

        $user = User::find($pendingId);
        if (! $user) {
            return redirect()->route('login');
        }

        $this->sendTwoFactorCode($user, true);
        return back()->with('status', 'Kode 2FA baru telah dikirim.');
    }

    private function throttleKey(Request $request): string
    {
        return Str::lower($request->input('email', '')) . '|' . $request->ip();
    }

    private function twoFactorCacheKey(string $userId): string
    {
        return "two-factor:{$userId}";
    }

    private function twoFactorAttemptKey(string $userId): string
    {
        return "two-factor-attempts:{$userId}";
    }

    private function sendTwoFactorCode(User $user, bool $resend = false): void
    {
        $code = (string) random_int(100000, 999999);
        Cache::put($this->twoFactorCacheKey($user->id), $code, now()->addMinutes(5));
        RateLimiter::clear($this->twoFactorAttemptKey($user->id));
        Mail::to($user->email)->send(new TwoFactorCode($user, $code));
        $this->logger->log(
            $user,
            $resend ? 'twofactor.resend' : 'twofactor.sent',
            $resend ? 'Kode 2FA dikirim ulang' : 'Kode 2FA dikirim ke email'
        );
    }
}
