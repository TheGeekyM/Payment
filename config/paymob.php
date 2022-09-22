<?php

return [
    'sandbox' => env('PAYMENT_USE_SANDBOX', true),

    'hmac' => env('PAYMOB_HMAC'),

    'api_key' => env('PAYMOB_API_KEY'),

    'integration_id' => env('PAYMOB_INTEGRATION_ID'),

    'url' => env('PAYMOB_URL'),
];
