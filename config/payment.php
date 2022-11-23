<?php

return [
    'sand_box' => env('PAYMENT_USE_SANDBOX'),
    'algo' => env('PAYMENT_ALGO'),
    'key' => env('PAYMENT_KEY'),
    'service_name' => env('PAYMENT_SERVICE_NAME'),
    'success_url' => env('PAYMENT_SUCCESS_URL'),
    'failure_url' => env('PAYMENT_FAILURE_URL'),
    'callback_url' => env('PAYMENT_CALLBACK_URL'),
];
