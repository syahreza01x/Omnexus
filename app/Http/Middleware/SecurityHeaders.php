<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking — halaman tidak bisa di-embed di iframe situs lain
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME-type sniffing — browser tidak boleh menebak tipe konten
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Control referrer information sent to other sites
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Prevent XSS via IE's built-in filter
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Remove server signature
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // Permissions policy — disable unnecessary browser features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
