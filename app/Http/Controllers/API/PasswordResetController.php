<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\SmartShuttlePasswordReset;

class PasswordResetController extends Controller
{
    /**
     * Send password reset link/email
     */
    public function forgotPassword(Request $request)
    {
        try {
            Log::info('Forgot password request', ['email' => $request->email]);

            $request->validate([
                'email' => 'required|email|exists:users,email'
            ]);

            $user = \App\Models\User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'Email tidak ditemukan'], 404);
            }

            // Generate simple token 6 digit
            $token = Str::upper(Str::random(6));

            // Simpan token ke database
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

            // Kirim token via email yang lebih baik
            try {
                $user->notify(new SmartShuttlePasswordReset($token));
                Log::info('Password reset email sent', ['email' => $request->email]);
            } catch (\Exception $e) {
                Log::error('Failed to send email', [
                    'email' => $request->email,
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'message' => 'Token berhasil dibuat tapi gagal mengirim email',
                    'token' => $token // Hanya untuk development
                ], 200);
            }

            return response()->json([
                'message' => 'Kode reset password telah dikirim ke email Anda'
            ]);

        } catch (\Exception $e) {
            Log::error('Forgot password error', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Reset password with token
     */
    public function resetPassword(Request $request)
    {
        try {
            Log::info('Reset password request', ['email' => $request->email]);

            $request->validate([
                'email' => 'required|email|exists:users,email',
                'token' => 'required|string|size:6',
                'password' => 'required|confirmed|min:8'
            ]);

            // Cari token di database
            $record = DB::table('password_reset_tokens')
                        ->where('email', $request->email)
                        ->first();

            if (!$record) {
                return response()->json([
                    'message' => 'Tidak ada permintaan reset password untuk email ini'
                ], 400);
            }

            if (!Hash::check($request->token, $record->token)) {
                Log::warning('Invalid token attempt', [
                    'email' => $request->email,
                    'provided_token' => $request->token
                ]);
                return response()->json([
                    'message' => 'Kode reset password tidak valid'
                ], 400);
            }

            // Check token expiry (60 menit)
            if (now()->diffInMinutes($record->created_at) > 60) {
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                return response()->json([
                    'message' => 'Kode reset password telah kadaluarsa'
                ], 400);
            }

            // Update password user
            $user = \App\Models\User::where('email', $request->email)->first();
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Hapus token setelah digunakan
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            Log::info('Password reset successful', ['email' => $request->email]);

            return response()->json([
                'message' => 'Password berhasil direset! Silakan login dengan password baru Anda'
            ]);

        } catch (\Exception $e) {
            Log::error('Reset password error', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }
}
