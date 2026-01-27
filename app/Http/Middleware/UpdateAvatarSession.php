<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UpdateAvatarSession
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Update session avatar jika user login
        if (Auth::check()) {
            $user = Auth::user();
            
            // Update session user data
            Session::put('user.avatar', $user->getSafeAvatarUrl());
            Session::put('user.name', $user->name);
            Session::put('user.email', $user->email);
        }
        
        return $response;
    }
}