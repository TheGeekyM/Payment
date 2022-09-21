<?php

return [
    'sandbox' => env('PAYMENT_USE_SANDBOX', true),

    'url_v1' => env('TABBY_URL_V1'),

    'url_v2' => env('TABBY_URL_V2'),

    'merchant_code' => env('TABBY_MERCHANT_CODE'),

    'callback_url' => env('TABBY_CALLBACK_URL'),

    'public_key' => env('TABBY_PUBLIC_KEY'),

    'private_key' => env('TABBY_PRIVATE_KEY'),
];
