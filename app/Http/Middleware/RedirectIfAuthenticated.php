<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika sudah login, redirect ke beranda
        if (session()->has('user')) {
            return redirect()->route('customer.beranda')->with('info', 'Anda sudah login!');
        }

        return $next($request);
    }
}