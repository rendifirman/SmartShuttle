<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Cek apakah user memiliki role admin_pusat
        if (!$user->hasRole('admin_pusat')) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk mengakses fitur ini'
            ], 403);
        }

        return $next($request);
    }
}