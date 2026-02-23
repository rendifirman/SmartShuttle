<?php

use Illuminate\Broadcasting\Broadcast;
use Illuminate\Broadcasting\Channel;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are used
| to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('admin.driver-locations', function ($user) {
    // Allow only admin users (roles may be admin_pusat, admin_cabang, operator)
    try {
        if (method_exists($user, 'hasAnyRole')) {
            return $user->hasAnyRole(['admin_pusat', 'admin_cabang', 'operator']);
        }
        return false;
    } catch (\Throwable $e) {
        return false;
    }
});

Broadcast::channel('trip.location.{tripId}', function ($user, $tripId) {
    // Allow admins to listen to trip channels. Drivers can be allowed with additional checks.
    try {
        if (method_exists($user, 'hasAnyRole')) {
            return $user->hasAnyRole(['admin_pusat', 'admin_cabang', 'operator']);
        }
        return false;
    } catch (\Throwable $e) {
        return false;
    }
});
