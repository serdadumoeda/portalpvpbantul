<?php

return [
    'x_frame_options' => env('SECURITY_X_FRAME', 'SAMEORIGIN'),
    'referrer_policy' => env('SECURITY_REFERRER_POLICY', 'strict-origin-when-cross-origin'),
    'permissions_policy' => env('SECURITY_PERMISSIONS_POLICY', 'camera=(), geolocation=(), microphone=(), usb=()'),
    'coep' => env('SECURITY_COEP', 'require-corp'),
    'corp' => env('SECURITY_CORP', 'same-site'),
    // Keep CSP relaxed for inline Blade usage; adjust in production as you harden assets.
    'csp' => env('SECURITY_CSP', implode('; ', [
        "default-src 'self'",
        "img-src 'self' data:",
        "font-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net data:",
        "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
        "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
        "connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
    ])),
];
