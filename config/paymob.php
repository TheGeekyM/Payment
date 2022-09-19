<?php

return [
    'sandbox' => env('PAYMENT_USE_SANDBOX', true),

    'hmac' => env('PAYMOB_HMAC'),

    'api_key' => env('PAYMOB_API_KEY'),

    'integration_id' => env('PAYMOB_INTEGRATION_ID'),

    'auth_url' => 'https://accept.paymob.com/api/auth/tokens',

    'order_registration_url' => 'https://accept.paymob.com/api/ecommerce/orders',

    'payment_key_url' => 'https://accept.paymob.com/api/acceptance/payment_keys',

    'payment_url' => 'https://accept.paymobsolutions.com/api/acceptance/payments/pay',
];
