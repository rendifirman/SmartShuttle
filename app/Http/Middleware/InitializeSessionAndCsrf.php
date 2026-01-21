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
    public function handle(Request $request, Closure $next)
    {
        // Untuk web requests (bukan API), pastikan session dan CSRF diinisialisasi
        if (!$request->is('api/*')) {
            try {
                // 1. Pastikan session sudah dimulai
                if (!session()->isStarted()) {
                    session()->start();
                }

                // 2. Pastikan CSRF token di-generate
                // Ini penting untuk form submission dan JavaScript access
                if (!session()->token()) {
                    session()->regenerateToken();
                }

                // 3. Regenerate session ID pada first request untuk security
                // Tapi cek terlebih dahulu untuk menghindari multiple regenerations
                if (!session()->has('_csrf_initialized')) {
                    session()->put('_csrf_initialized', true);
                    // Jangan regenerate session ID pada first time, biarkan Laravel handle-nya
                    // Regenerating terlalu sering bisa menyebabkan session loss
                }

                // Log untuk debugging
                if (env('APP_DEBUG')) {
                    \Log::debug('InitializeSessionAndCsrf: Session initialized', [
                        'path' => $request->path(),
                        'session_id' => session()->getId(),
                        'session_token' => substr(session()->token(), 0, 10) . '...',
                        'session_has_user' => session()->has('user'),
                    ]);
                }

            } catch (\Exception $e) {
                \Log::error('InitializeSessionAndCsrf: Error during initialization', [
                    'error' => $e->getMessage(),
                    'path' => $request->path(),
                ]);
                // Don't fail the request, just log and continue
            }
        }

        $response = $next($request);

        // Pastikan session di-save pada response
        if (!$request->is('api/*') && session()->isStarted()) {
            try {
                session()->save();
            } catch (\Exception $e) {
                \Log::warning('InitializeSessionAndCsrf: Error saving session', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $response;
    }
}
