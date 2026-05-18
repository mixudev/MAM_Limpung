<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: SecurityHeaders
 *
 * Injects HTTP security headers on every web response to mitigate:
 *   - Clickjacking      → X-Frame-Options: DENY
 *   - MIME sniffing     → X-Content-Type-Options: nosniff
 *   - XSS via CSP       → Content-Security-Policy (baseline)
 *   - Info leakage      → Referrer-Policy
 *   - Feature abuse     → Permissions-Policy
 *   - Protocol downgrade→ Strict-Transport-Security (HSTS, production only)
 *
 * Registration in bootstrap/app.php:
 *   $middleware->web(append: [SecurityHeaders::class]);
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent this page from being rendered inside an iframe (Clickjacking)
        $response->headers->set('X-Frame-Options', 'DENY');

        // Stop browser from guessing content-type (MIME sniffing attacks)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Limit referrer info sent to other origins
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Disable dangerous browser features not needed by this app
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=()'
        );

        // Bangun kebijakan CSP secara dinamis
        $isLocal = config('app.env') === 'local';

        $scriptSrc = ["'self'", "'unsafe-inline'", "'unsafe-eval'", 'https://kit.fontawesome.com', 'https://cdn.tailwindcss.com', 'https://unpkg.com', 'https://cdn.jsdelivr.net'];
        $styleSrc = ["'self'", "'unsafe-inline'", 'https://fonts.bunny.net', 'https://unpkg.com', 'https://fonts.googleapis.com', 'https://cdnjs.cloudflare.com'];
        $connectSrc = ["'self'", 'https://ka-f.fontawesome.com', 'https://cdn.jsdelivr.net'];
        $imgSrc = ["'self'", 'data:', 'https://images.unsplash.com', 'https://api.qrserver.com', 'https://barcode.tec-it.com', 'https://bwipjs-api.metafloor.com'];
        $fontSrc = ["'self'", 'data:', 'https://fonts.bunny.net', 'https://ka-f.fontawesome.com', 'https://fonts.gstatic.com', 'https://cdnjs.cloudflare.com'];

        // Jika di lingkungan local, izinkan aset dari Vite Dev Server
        if ($isLocal) {
            $viteUrls = [
                'http://localhost:*',
                'ws://localhost:*',
                'http://127.0.0.1:*',
                'ws://127.0.0.1:*',
            ];
            $scriptSrc = array_merge($scriptSrc, $viteUrls);
            $styleSrc = array_merge($styleSrc, $viteUrls);
            $connectSrc = array_merge($connectSrc, $viteUrls);
            $imgSrc = array_merge($imgSrc, $viteUrls);
        }

        $csp = [
            "default-src 'self'",
            'script-src '.implode(' ', $scriptSrc),
            'style-src '.implode(' ', $styleSrc),
            'img-src '.implode(' ', $imgSrc),
            'font-src '.implode(' ', $fontSrc),
            'connect-src '.implode(' ', $connectSrc),
            "frame-src 'self' https://www.youtube.com https://youtube.com https://*.youtube.com https://www.youtube-nocookie.com https://www.google.com https://*.google.com", // Tambah wildcard youtube & google maps
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ];

        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        // HSTS — only meaningful in production over HTTPS.
        // Enable this (via env) once you have SSL configured.
        if (config('app.env') === 'production') {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
