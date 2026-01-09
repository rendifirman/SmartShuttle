<?php
// app/Helpers/UserHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class UserHelper
{
    /**
     * Get avatar URL dengan fallback ke default
     */
    public static function getAvatarUrl($user = null)
    {
        if (!$user && Auth::check()) {
            $user = Auth::user();
        }
        
        if (!$user) {
            return asset('images/default-avatar.png');
        }
        
        return $user->getSafeAvatarUrl();
    }
    
    /**
     * Get user initials
     */
    public static function getUserInitials($user = null)
    {
        if (!$user && Auth::check()) {
            $user = Auth::user();
        }
        
        if (!$user) {
            return 'GU';
        }
        
        return $user->initials;
    }
}