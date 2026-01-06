<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
'paylabs' => [
        'mid' => env('PAYLABS_MID'),
        'private_key' => env('PAYLABS_PRIVATE_KEY'),
        'public_key' => env('PAYLABS_PUBLIC_KEY'),
        'base_url' => env('PAYLABS_BASE_URL'),
        'callback_url' => env('PAYLABS_CALLBACK_URL'),
    ],
     'qris' => [
        'merchant_id' => env('QRIS_MERCHANT_ID'),
        'merchant_name' => env('QRIS_MERCHANT_NAME'),
        'merchant_city' => env('QRIS_MERCHANT_CITY'),
        'terminal_id' => env('QRIS_TERMINAL_ID'),
        'store_id' => env('QRIS_STORE_ID'),
    ],


];
