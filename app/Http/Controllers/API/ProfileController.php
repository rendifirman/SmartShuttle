<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
  public function show(Request $request)
{
    $user = $request->user()->load('roles');

    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar_url,
            'email_verified_at' => $user->email_verified_at,
            'roles' => $user->getRoleNames(),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ]
    ]);
}

public function update(Request $request)
{
    $user = $request->user();

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    $user->update($validator->validated());

    return response()->json([
        'message' => 'Profil berhasil diperbarui',
        'user' => $user->fresh()->load('roles')
    ]);
}
    public function updateProfilePicture(Request $request)
    {
        try {
            \Log::info('Avatar upload started', ['user_id' => $request->user()->id]);

            $validator = Validator::make($request->all(), [
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                \Log::error('Avatar validation failed', ['errors' => $validator->errors()]);
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            if ($request->hasFile('avatar')) {
                \Log::info('Avatar file detected', ['file_name' => $request->file('avatar')->getClientOriginalName()]);

                // Check if avatar column exists in database
                if (!\Schema::hasColumn('users', 'avatar')) {
                    \Log::error('Avatar column does not exist in users table');
                    return response()->json([
                        'message' => 'Avatar feature not available - database column missing'
                    ], 500);
                }

                $path = $request->file('avatar')->store('avatars', 'public');
                \Log::info('Avatar stored', ['path' => $path]);

                $user->update(['avatar' => $path]);

                return response()->json([
                    'message' => 'Profile picture updated successfully',
                    'avatar_url' => url('storage/' . $path)
                ]);
            }

            \Log::warning('No avatar file provided');
            return response()->json([
                'message' => 'No avatar file provided'
            ], 400);

        } catch (\Exception $e) {
            \Log::error('Avatar upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to upload avatar',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
