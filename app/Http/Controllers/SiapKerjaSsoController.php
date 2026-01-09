<?php

namespace App\Http\Controllers;

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
            'client_id' => config('services.siapkerja.client_id'),
            'redirect_uri' => config('services.siapkerja.redirect'),
            'scope' => config('services.siapkerja.scope', 'basic email'),
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
            ->post(self::TOKEN_URL, [
                'client_id' => config('services.siapkerja.client_id'),
                'client_secret' => config('services.siapkerja.client_secret'),
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => config('services.siapkerja.redirect'),
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
            ->get(self::PROFILE_URL);

        if ($profileResponse->failed()) {
            return redirect()->route('login')->withErrors('Gagal mengambil profil SIAPKerja.');
        }

        $profile = $profileResponse->json();
        $profileData = $profile['data'] ?? $profile;

        $email = $profileData['email'] ?? null;
        if (! $email) {
            return redirect()->route('login')->withErrors('Profil SIAPKerja tidak menyediakan email.');
        }

        $name = $profileData['name'] ?? ($profileData['full_name'] ?? 'Pengguna SIAPKerja');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Str::random(32),
                'two_factor_enabled' => false,
            ]
        );

        if (! $user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        Auth::login($user, true);

        $intended = session()->pull('url.intended');
        return redirect()->to($intended ?? route('home'));
    }
}
