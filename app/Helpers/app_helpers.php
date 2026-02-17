<?php

use Illuminate\Support\Facades\Cache;
use App\Models\AppSetting;

if (! function_exists('appSetting')) {
    /**
     * Fetch an application setting from cache (forever) or DB.
     * Returns raw string value; caller may json_decode if needed.
     */
    function appSetting(string $key, $default = null)
    {
        $cacheKey = 'app_setting:' . $key;
        return Cache::rememberForever($cacheKey, function () use ($key, $default) {
            return AppSetting::getValue($key, $default);
        });
    }
}
