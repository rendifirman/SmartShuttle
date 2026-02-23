<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('admin')->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }
            return redirect()->route('admin.login');
        }

        // Check if user has admin role
        if (!$user->hasAnyRole(['admin_pusat', 'admin_cabang', 'operator'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki izin untuk mengakses fitur ini'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki izin untuk mengakses fitur ini');
        }

        // If user is branch admin, ensure they have branch assignment
        if ($user->hasRole('admin_cabang') && !$user->branch_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Branch belum di-assign ke akun admin cabang Anda'
                ], 403);
            }
            abort(403, 'Branch belum di-assign ke akun admin cabang Anda');
        }

        return $next($request);
    }
}
