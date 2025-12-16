<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SecurityController extends Controller
{
    public function enableTwoFactor(Request $request)
    {
        try {
            $user = $request->user();
            $user->update(['two_factor_enabled' => true]);

            return response()->json([
                'message' => 'Two-factor authentication enabled',
                'two_factor_enabled' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to enable two-factor authentication',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function disableTwoFactor(Request $request)
    {
        try {
            $user = $request->user();
            $user->update(['two_factor_enabled' => false]);

            return response()->json([
                'message' => 'Two-factor authentication disabled',
                'two_factor_enabled' => false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to disable two-factor authentication',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSecuritySettings(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'two_factor_enabled' => $user->two_factor_enabled ?? false,
            'email_verified' => $user->hasVerifiedEmail(),
            'last_password_change' => $user->updated_at
        ]);
    }
}