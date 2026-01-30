<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBranchAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Only apply branch restrictions to admin_cabang users
        if ($user->hasRole('admin_cabang')) {
            // Check if user has branch assignment
            if (!$user->branch_id) {
                return response()->json([
                    'message' => 'Branch belum di-assign ke akun admin cabang Anda'
                ], 403);
            }

            // Get branch_id from route parameters or request
            $branchId = $request->route('id') ?? $request->route('branch_id') ?? $request->input('branch_id');

            // If accessing specific branch data, ensure it matches user's branch
            if ($branchId && $branchId != $user->branch_id) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke cabang ini'
                ], 403);
            }

            // For outlet operations, check if outlet belongs to user's branch
            if ($request->route('outlet')) {
                $outlet = \App\Models\Outlet::find($request->route('outlet'));
                if ($outlet && $outlet->branch_id != $user->branch_id) {
                    return response()->json([
                        'message' => 'Anda tidak memiliki akses ke outlet ini'
                    ], 403);
                }
            }
        }

        return $next($request);
    }
}
