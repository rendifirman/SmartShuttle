<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedCustomer
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

        // Cek jika user sudah login (baik melalui session atau auth)
        if (Auth::check()) {
            return redirect()->route('customer.beranda');
        }

        return $next($request);
    }
}
