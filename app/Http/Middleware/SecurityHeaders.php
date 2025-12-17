<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $next($request);

        $headers = [
            'X-Frame-Options' => config('security.x_frame_options', 'SAMEORIGIN'),
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => config('security.referrer_policy', 'strict-origin-when-cross-origin'),
            'X-XSS-Protection' => '0',
            'Permissions-Policy' => config('security.permissions_policy', 'camera=(), geolocation=(), microphone=(), usb=()'),
            'Cross-Origin-Opener-Policy' => 'same-origin',
            'Cross-Origin-Embedder-Policy' => config('security.coep', 'require-corp'),
            'Cross-Origin-Resource-Policy' => config('security.corp', 'same-site'),
        ];

        foreach ($headers as $key => $value) {
            if (!$response->headers->has($key) && $value) {
                $response->headers->set($key, $value);
            }
        }

        // Basic CSP that still allows inline Blade styles/scripts used today.
        if (!$response->headers->has('Content-Security-Policy')) {
            $csp = config('security.csp');
            if ($csp) {
                $response->headers->set('Content-Security-Policy', $csp);
            }
        }

        return $response;
    }
}
