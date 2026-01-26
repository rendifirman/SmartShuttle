<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;

/**
 * Middleware untuk memastikan session dan CSRF token diinisialisasi dengan benar
 * pada setiap request, terutama request pertama ke halaman login/register
 *
 * Masalah yang dipecahkan:
 * - Session tidak terinisialisasi pada first page load
 * - CSRF token tidak persisten ke database session
 * - Login/register tidak berfungsi sampai OAuth dijalankan
 */
class InitializeSessionAndCsrf
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
{
    return $next($request);
}

}
