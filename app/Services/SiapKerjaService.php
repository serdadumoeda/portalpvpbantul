<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SiapKerjaService
{
    private const TOKEN_CACHE_KEY = 'siapkerja.admin_token';

    public function fetchPrograms(): array
    {
        return $this->get('/programs');
    }

    public function fetchSchedules(string $programId): array
    {
        return $this->get("/programs/{$programId}/batches");
    }

    public function fetchInstructors(): array
    {
        return $this->get('/instructors');
    }

    public function fetchParticipants(string $batchId): array
    {
        return $this->get("/training/{$batchId}/participants");
    }

    private function get(string $path, array $query = []): array
    {
        $response = $this->http()->get(ltrim($path, '/'), $query);

        if ($response->failed()) {
            $response->throw();
        }

        return $response->json('data') ?? $response->json() ?? [];
    }

    private function http(): PendingRequest
    {
        $baseUrl = rtrim($this->setting('api_base') ?? config('services.siapkerja.api_base'), '/');

        return Http::baseUrl($baseUrl)
            ->acceptJson()
            ->withToken($this->getAccessToken());
    }

    private function getAccessToken(): string
    {
        $cached = Cache::get(self::TOKEN_CACHE_KEY);
        if ($cached) {
            return $cached;
        }

        $response = Http::asJson()
            ->acceptJson()
            ->post($this->setting('token_url') ?? config('services.siapkerja.token_url'), [
                'grant_type' => 'client_credentials',
                'client_id' => $this->setting('admin_client_id') ?? config('services.siapkerja.admin_client_id'),
                'client_secret' => $this->setting('admin_client_secret') ?? config('services.siapkerja.admin_client_secret'),
                'scope' => $this->setting('admin_scope') ?? config('services.siapkerja.admin_scope', 'client'),
            ]);

        if ($response->failed()) {
            $response->throw();
        }

        $data = $response->json();
        $token = Arr::get($data, 'data.access_token') ?? Arr::get($data, 'access_token');
        if (! $token) {
            throw new RuntimeException('Token SIAP Kerja tidak ditemukan dalam response.');
        }

        $expiresIn = (int) (Arr::get($data, 'data.expires_in') ?? Arr::get($data, 'expires_in') ?? 3600);
        Cache::put(self::TOKEN_CACHE_KEY, $token, now()->addSeconds(max(60, $expiresIn - 60)));

        return $token;
    }

    private function setting(string $key): ?string
    {
        return SiteSetting::valueOf("siapkerja_{$key}");
    }
}
