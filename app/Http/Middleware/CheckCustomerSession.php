<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomerSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika session user ada
        if (!session()->has('user')) {
            return redirect()->route('customer.login')->with('error', 'Silakan login terlebih dahulu');
        }

        return $next($request);
    }
}