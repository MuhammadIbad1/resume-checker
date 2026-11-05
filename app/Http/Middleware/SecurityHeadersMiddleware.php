<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and attach enterprise security headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent Clickjacking (allow only same origin frames)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Legacy XSS filter protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control referrer information leakage
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restrict sensitive browser APIs
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content Security Policy (relaxed to support Tailwind CDN, Google Fonts, Livewire)
        $csp = "default-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.googleapis.com https://fonts.gstatic.com https://cdn.tailwindcss.com data:; " .
               "img-src 'self' data: https: blob:; " .
               "font-src 'self' https://fonts.gstatic.com data:; " .
               "connect-src 'self' ws: wss:; " .
               "frame-ancestors 'self';";
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}

