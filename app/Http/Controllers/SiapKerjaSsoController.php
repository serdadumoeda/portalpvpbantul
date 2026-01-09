<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SiapKerjaSsoController extends Controller
{
    private const AUTH_URL = 'https://account.kemnaker.go.id/auth';
    private const TOKEN_URL = 'https://account.kemnaker.go.id/api/v1/tokens';
    private const PROFILE_URL = 'https://account.kemnaker.go.id/api/v1/users/me';

    public function redirect(): RedirectResponse
    {
        $state = Str::random(40);
        session(['siapkerja_state' => $state]);

        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $this->setting('client_id', config('services.siapkerja.client_id')),
            'redirect_uri' => $this->setting('redirect', config('services.siapkerja.redirect')),
            'scope' => $this->setting('scope', config('services.siapkerja.scope', 'basic email')),
            'state' => $state,
        ]);

        return redirect()->away(self::AUTH_URL . '?' . $query);
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            return redirect()->route('login')->withErrors($request->get('error_description', 'SSO dibatalkan.'));
        }

        if ($request->input('state') !== session('siapkerja_state')) {
            return redirect()->route('login')->withErrors('State SSO tidak valid.');
        }
        session()->forget('siapkerja_state');

        $code = $request->input('code');
        if (! $code) {
            return redirect()->route('login')->withErrors('Kode otorisasi tidak ditemukan.');
        }

        $tokenResponse = Http::asJson()
            ->acceptJson()
            ->post($this->setting('token_url', config('services.siapkerja.token_url', self::TOKEN_URL)), [
                'client_id' => $this->setting('client_id', config('services.siapkerja.client_id')),
                'client_secret' => $this->setting('client_secret', config('services.siapkerja.client_secret')),
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->setting('redirect', config('services.siapkerja.redirect')),
            ]);

        if ($tokenResponse->failed()) {
            return redirect()->route('login')->withErrors('Gagal menukar token SIAPKerja.');
        }

        $tokenData = $tokenResponse->json();
        $accessToken = data_get($tokenData, 'data.access_token');
        if (! $accessToken) {
            return redirect()->route('login')->withErrors('Token akses SIAPKerja tidak ditemukan.');
        }

        $profileResponse = Http::withToken($accessToken)
            ->acceptJson()
            ->get($this->setting('profile_url', self::PROFILE_URL));

        if ($profileResponse->failed()) {
            return redirect()->route('login')->withErrors('Gagal mengambil profil SIAPKerja.');
        }

        $profile = $profileResponse->json();
        $profileData = $profile['data'] ?? $profile;

        $email = data_get($profileData, 'email') ?? data_get($profileData, 'user.email');
        $nik = data_get($profileData, 'nik') ?? data_get($profileData, 'identity_number');

        if (! $nik && ! $email) {
            return redirect()->route('login')->withErrors('Profil SIAPKerja tidak menyediakan identitas NIK atau email.');
        }

        $name = data_get($profileData, 'name') ?? data_get($profileData, 'full_name') ?? ($email ?: 'Pengguna SIAPKerja');
        $siapKerjaId = data_get($profileData, 'id') ?? data_get($profileData, 'user.id');

        $existingUserQuery = User::query();
        if ($nik) {
            $existingUserQuery->where('nik', $nik);
        }
        if ($email) {
            $existingUserQuery->{$nik ? 'orWhere' : 'where'}('email', $email);
        }
        $existingUser = $existingUserQuery->first();

        $lookupAttributes = $existingUser
            ? ['id' => $existingUser->id]
            : ($nik ? ['nik' => $nik] : ['email' => $email]);

        $user = User::updateOrCreate($lookupAttributes, [
            'name' => $name,
            'nik' => $nik,
            'email' => $email,
            'siap_kerja_id' => $siapKerjaId,
            'sso_payload' => $profile,
            'password' => Str::random(32), // dummy password because login is via SSO
            'email_verified_at' => now(),
        ]);

        Auth::login($user, true);
        session()->regenerate();

        $intended = session()->pull('url.intended');
        return redirect()->to($intended ?? route('home'));
    }

    private function setting(string $key, ?string $default = null): ?string
    {
        return SiteSetting::valueOf("siapkerja_{$key}", $default);
    }
}
