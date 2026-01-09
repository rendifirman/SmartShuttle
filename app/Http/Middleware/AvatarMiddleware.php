<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvatarMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('customer.login');
        }
        
        return $next($request);
    }
}