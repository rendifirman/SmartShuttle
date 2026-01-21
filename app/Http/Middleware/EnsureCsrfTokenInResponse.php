<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware untuk memastikan CSRF token tersedia di response headers
 * agar JavaScript bisa mengaksesnya untuk AJAX requests
 */
class EnsureCsrfTokenInResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Untuk requests yang memerlukan CSRF protection (bukan API)
        if (!$request->is('api/*')) {
            // CSRF token sudah di-generate oleh @csrf directive di view
            // Tambahkan ke response header untuk akses dari JavaScript
            $csrfToken = session()->token();

            if ($csrfToken) {
                $response->headers->set('X-CSRF-Token', $csrfToken);
            }
        }

        return $response;
    }
}
