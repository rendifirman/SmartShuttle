<?php
// config/paylabs.php (update)
return [
    /*
    |--------------------------------------------------------------------------
    | Paylabs Configuration
    |--------------------------------------------------------------------------
    */

    // Environment
    'environment' => env('PAYLABS_ENVIRONMENT', 'sandbox'),

    // Merchant Credentials
    // Support legacy keys: MID / PRIVATE_KEY / PUBLIC_KEY
    'mid' => env('PAYLABS_MID', env('MID', '010529')),
    'merchant_name' => env('PAYLABS_MERCHANT_NAME', 'Smart Shuttle'),
    'store_id' => env('PAYLABS_STORE_ID', ''),

    // API Endpoints
    // For Pay-in v2.3 (QRIS) base URLs are typically:
    // SIT  => https://sit-pay.paylabs.co.id
    // PROD => https://pay.paylabs.co.id
    'base_url' => env(
        'PAYLABS_BASE_URL',
        env('PAYLABS_ENVIRONMENT', 'sandbox') === 'production'
            ? 'https://pay.paylabs.co.id'
            : 'https://sit-pay.paylabs.co.id'
    ),
    'api_version' => 'v2.3',

    // QRIS Specific Settings
    'qris' => [
        'merchant_name' => env('QRIS_MERCHANT_NAME', 'SMART SHUTTLE'),
        'merchant_city' => env('QRIS_MERCHANT_CITY', 'JAKARTA'),
        'terminal_id' => env('QRIS_TERMINAL_ID', '001'),
        'store_id' => env('QRIS_STORE_ID', '001'),
        'fee_type' => env('QRIS_FEE_TYPE', 'BEN'), // BEN: Merchant, OUR: Customer
    ],

    // URLs
    'callback_url' => env('PAYLABS_CALLBACK_URL', 'http://localhost:8000/api/payment/callback-v23'),
    'return_url' => env('PAYLABS_RETURN_URL', 'http://localhost:8000/customer/detail-pemesanan'),
    'notify_url' => env('PAYLABS_NOTIFY_URL', 'http://localhost:8000/api/payment/callback-v23'),

    // Security
    'private_key' => env('PAYLABS_PRIVATE_KEY', env('PRIVATE_KEY', '')),
    'private_key_file' => env('PAYLABS_PRIVATE_KEY_FILE', env('PAYLABS_PRIVATE_KEY_PATH', storage_path('app/keys/paylabs_private.pem'))),
    'public_key' => env('PAYLABS_PUBLIC_KEY', env('PUBLIC_KEY', '')),
    'public_key_file' => env('PAYLABS_PUBLIC_KEY_FILE', env('PAYLABS_PUBLIC_KEY_PATH', storage_path('app/keys/paylabs_public.pem'))),

    // Timeout Settings
    'timeout' => 30,
    'connect_timeout' => 10,

    // Payment Settings
    'payment' => [
        'expiry_minutes' => env('PAYMENT_EXPIRY_MINUTES', 30),
        'default_method' => env('DEFAULT_PAYMENT_METHOD', 'qris'),
        'currency' => 'IDR',
        'country' => 'ID',
    ],

    // Testing Mode - DISABLED by default for security
    'testing' => [
        'enabled' => env('PAYLABS_TESTING', false),
        'simulate_success' => env('PAYLABS_SIMULATE_SUCCESS', false),
        'skip_signature' => env('PAYLABS_SKIP_SIGNATURE', false),
    ],
];
