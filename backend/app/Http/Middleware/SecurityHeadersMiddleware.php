<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and add security headers to the response.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Content Security Policy - Prevents XSS attacks
        // This is a permissive policy that allows the app to function while providing security
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'", // Allow inline scripts for React
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com", // Allow inline styles and Google Fonts
            "font-src 'self' https://fonts.gstatic.com data:", // Allow Google Fonts
            "img-src 'self' data: https:", // Allow images from self, data URIs, and HTTPS
            "connect-src 'self' http://localhost:* http://127.0.0.1:*", // Allow API connections
            "frame-ancestors 'none'", // Prevent clickjacking (same as X-Frame-Options: DENY)
            "base-uri 'self'",
            "form-action 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        // Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'DENY');

        // Prevent MIME-sniffing attacks
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable XSS protection for older browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy - Restrict browser features
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Strict Transport Security - Force HTTPS (only in production with HTTPS)
        if (config('app.env') === 'production' && $request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Remove X-Powered-By header to hide server technology
        $response->headers->remove('X-Powered-By');
        header_remove('X-Powered-By');

        return $response;
    }
}
