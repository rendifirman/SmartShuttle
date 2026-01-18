{
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Cek apakah user memiliki role admin_pusat atau admin_cabang
        if (!$user->hasRole('admin_pusat') && !$user->hasRole('admin_cabang')) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk mengakses fitur ini'
            ], 403);
        }

        return $next($request);
    }
=======
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Cek apakah user memiliki role admin_pusat atau admin_cabang
        if (!$user->hasRole('admin_pusat') && !$user->hasRole('admin_cabang')) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk mengakses fitur ini'
            ], 403);
        }

        // Jika user adalah admin_cabang, pastikan mereka memiliki branch yang assigned
        if ($user->hasRole('admin_cabang') && !$user->branch_id) {
            return response()->json([
                'message' => 'Branch belum di-assign ke akun admin cabang Anda'
            ], 403);
        }

        return $next($request);
    }
