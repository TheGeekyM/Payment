<?php

return [
    'sandbox' => env('PAYMENT_USE_SANDBOX', true),

    'url' => env('TAMARA_URL'),

    'jwt_token' => env('TAMARA_JWT_TOKEN'),

    'notification_token' => env('TAMARA_NOTIFICATION_TOKEN'),

    'callback_url' => env('TAMARA_CALLBACK_URL'),
];
