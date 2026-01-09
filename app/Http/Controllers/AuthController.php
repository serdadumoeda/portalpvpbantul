<?php

namespace App\Http\Controllers;

use App\Mail\TwoFactorCode;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

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
        return redirect()->route('sso.siapkerja.redirect');
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
        return redirect()->route('login')->with('error', 'Reset password dikelola melalui SIAP Kerja.');
    }

    public function sendResetLink(Request $request)
    {
        return redirect()->route('login')->with('error', 'Reset password dikelola melalui SIAP Kerja.');
    }

    public function showResetForm(string $token)
    {
        return redirect()->route('login')->with('error', 'Reset password dikelola melalui SIAP Kerja.');
    }

    public function resetPassword(Request $request)
    {
        return redirect()->route('login')->with('error', 'Reset password dikelola melalui SIAP Kerja.');
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

        return redirect()->to($intended ?? $this->defaultRedirect($user));
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

    private function defaultRedirect(User $user): string
    {
        if ($user->hasPermission('access-admin')) {
            return route('admin.dashboard');
        }

        if ($user->hasRole('participant')) {
            return route('participant.classes');
        }

        if ($user->hasPermission('access-alumni-forum')) {
            return route('alumni.forum.index');
        }

        return route('home');
    }
}
