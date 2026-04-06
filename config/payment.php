<?php

return [

    'default' => env('PAYMENT_GATEWAY', 'paypal'),

    'max_retry_attempts' => 3,

    'gateways' => [

        'paypal' => [
            'enabled' => false,
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'client_secret' => env('PAYPAL_CLIENT_SECRET'),
            'mode' => env('PAYPAL_MODE', 'sandbox'),
            'base_url' => env('PAYPAL_MODE', 'sandbox') === 'live'
                ? 'https://api-m.paypal.com'
                : 'https://api-m.sandbox.paypal.com',
            'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
        ],

        'tap' => [
            'enabled' => true,
            'secret_key' => env('TAP_SECRET_KEY'),
            'public_key' => env('TAP_PUBLIC_KEY'),
            'base_url' => env('TAP_BASE_URL', 'https://api.tap.company/v2'),
            'webhook_secret' => env('TAP_WEBHOOK_SECRET'),
        ],

    ],

];
