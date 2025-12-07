<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SecurityHeaders Middleware
 *
 * Applies security-related HTTP headers to all responses to protect against
 * common web vulnerabilities including XSS, clickjacking, MIME sniffing, etc.
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request and add security headers to the response.
     *
     * Security headers added:
     * - X-Frame-Options: Prevents clickjacking attacks
     * - X-Content-Type-Options: Prevents MIME type sniffing
     * - X-XSS-Protection: Enables browser XSS protection (legacy support)
     * - Referrer-Policy: Controls referrer information
     * - Permissions-Policy: Controls browser features
     * - Strict-Transport-Security: Forces HTTPS (production only)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking by disallowing iframe embedding
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable XSS protection (for older browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control referrer information sent to external sites
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Control browser features and APIs
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Force HTTPS in production (HSTS)
        if (app()->environment('production') && $request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
