<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedDriver
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Pastikan session sudah dimulai untuk semua request (penting untuk CSRF)
        if (!session()->isStarted()) {
            session()->start();
        }

        // Cek jika driver sudah login melalui guard 'driver'
        if (Auth::guard('driver')->check()) {
            return redirect()->route('driver.dashboard');
        }

        return $next($request);
    }
}
