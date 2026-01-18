<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSessionStarted
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
        // Ensure session is started for all web requests that might need CSRF protection
        if (!$request->is('api/*') && !session()->isStarted()) {
            session()->start();
        }

        return $next($request);
    }
}
