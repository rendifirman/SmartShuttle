<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paylabs Configuration
    |--------------------------------------------------------------------------
    */

    // Environment
    'environment' => env('PAYLABS_ENVIRONMENT', 'sandbox'),

    // Merchant Credentials
    'mid' => env('PAYLABS_MID', '010529'),
    'merchant_name' => env('PAYLABS_MERCHANT_NAME', 'Smart Shuttle'),

    // API Endpoints
    'base_url' => env('PAYLABS_BASE_URL', 'https://sandbox.paylabs.co.id'),
    'api_version' => 'v4.8.1',

    // URLs
    'callback_url' => env('PAYLABS_CALLBACK_URL', 'http://localhost:8000/api/payment/callback'),
    'return_url' => env('PAYLABS_RETURN_URL', 'http://localhost:8000/customer/detail-pemesanan'),
    'notify_url' => env('PAYLABS_NOTIFY_URL', 'http://localhost:8000/api/payment/callback'),

    // Security
    'private_key' => env('PAYLABS_PRIVATE_KEY', ''),
    'private_key_file' => env('PAYLABS_PRIVATE_KEY_FILE', 'storage/app/keys/paylabs_private.pem'),
    'public_key' => env('PAYLABS_PUBLIC_KEY', ''),
    'public_key_file' => env('PAYLABS_PUBLIC_KEY_FILE', 'storage/app/keys/paylabs_public.pem'),

    // Timeout Settings
    'timeout' => 30,
    'connect_timeout' => 10,

    // Retry Settings
    'retry' => [
        'times' => 3,
        'sleep' => 1000,
        'throw' => false,
    ],

    // Payment Settings
    'payment' => [
        'expiry_minutes' => env('PAYMENT_EXPIRY_MINUTES', 30),
        'default_method' => env('DEFAULT_PAYMENT_METHOD', 'qris'),
        'currency' => 'IDR',
        'country' => 'ID',
    ],

    // Channel Mapping
    'channels' => [
        'qris' => [
            'code' => 'QRIS',
            'name' => 'QRIS',
            'endpoint' => '/payment/qris/create',
        ],
        'bca_va' => [
            'code' => 'VA_BCA',
            'name' => 'BCA Virtual Account',
            'endpoint' => '/payment/va/create',
        ],
        'mandiri_va' => [
            'code' => 'VA_MANDIRI',
            'name' => 'Mandiri Virtual Account',
            'endpoint' => '/payment/va/create',
        ],
        'bni_va' => [
            'code' => 'VA_BNI',
            'name' => 'BNI Virtual Account',
            'endpoint' => '/payment/va/create',
        ],
        'bri_va' => [
            'code' => 'VA_BRI',
            'name' => 'BRI Virtual Account',
            'endpoint' => '/payment/va/create',
        ],
        'dana' => [
            'code' => 'EW_DANA',
            'name' => 'DANA',
            'endpoint' => '/payment/ewallet/create',
        ],
        'gopay' => [
            'code' => 'EW_GOPAY',
            'name' => 'GoPay',
            'endpoint' => '/payment/ewallet/create',
        ],
        'ovo' => [
            'code' => 'EW_OVO',
            'name' => 'OVO',
            'endpoint' => '/payment/ewallet/create',
        ],
        'shopeepay' => [
            'code' => 'EW_SHOPEEPAY',
            'name' => 'ShopeePay',
            'endpoint' => '/payment/ewallet/create',
        ],
    ],

    // Status Mapping
    'status_mapping' => [
        'PENDING' => 'menunggu',
        'PROCESSING' => 'diproses',
        'PAID' => 'berhasil',
        'EXPIRED' => 'kadaluarsa',
        'FAILED' => 'gagal',
        'CANCELLED' => 'dibatalkan',
        'REFUNDED' => 'dikembalikan',
        'UNKNOWN' => 'menunggu',
    ],

    // Testing Mode
    'testing' => [
        'enabled' => env('PAYLABS_TESTING', false),
        'simulate_success' => env('PAYLABS_SIMULATE_SUCCESS', true),
        'skip_signature' => env('PAYLABS_SKIP_SIGNATURE', false),
        'dummy_response' => [
            'responseCode' => '00',
            'responseMessage' => 'Success',
            'transactionId' => 'T' . time() . rand(1000, 9999),
            'status' => 'PENDING',
            'amount' => 100000,
            'currency' => 'IDR',
            'paymentChannel' => 'QRIS',
            'vaNumber' => null,
            'qrCode' => 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TEST' . time(),
            'expiredTime' => now()->addMinutes(30)->toISOString(),
            'checkoutUrl' => null,
            'deepLink' => null,
        ],
    ],

    // Logging
    'logging' => [
        'enabled' => env('LOG_PAYMENTS', true),
        'channel' => env('LOG_PAYMENTS_CHANNEL', 'daily'),
        'level' => env('LOG_PAYMENTS_LEVEL', 'debug'),
    ],
];
