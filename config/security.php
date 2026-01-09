<?php

return [
    'x_frame_options' => env('SECURITY_X_FRAME', 'SAMEORIGIN'),
    'referrer_policy' => env('SECURITY_REFERRER_POLICY', 'strict-origin-when-cross-origin'),
    'permissions_policy' => env('SECURITY_PERMISSIONS_POLICY', 'camera=(), geolocation=(), microphone=(), usb=()'),
    'coep' => env('SECURITY_COEP'), // kosongkan untuk mengizinkan embed lintas domain (YouTube, dll.)
    'corp' => env('SECURITY_CORP', 'cross-origin'),
    // Keep CSP relaxed for inline Blade usage; adjust in production as you harden assets.
    'csp' => env('SECURITY_CSP', implode('; ', [
        "default-src 'self'",
        "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://www.google.com https://maps.google.com https://www.google.co.id",
        "img-src 'self' https: data:",
        "font-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net data:",
        "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
        "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
        "connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
    ])),
];
