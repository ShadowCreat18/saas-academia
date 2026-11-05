<?php

return [
    'key'           => env('STRIPE_KEY'),
    'secret'        => env('STRIPE_SECRET'),
    'webhook_secret'=> env('STRIPE_WEBHOOK_SECRET'),
    'prices' => [
        'basico'  => env('STRIPE_PRICE_BASICO_MONTHLY'),
        'elite'   => env('STRIPE_PRICE_ELITE_MONTHLY'),
        'premium' => env('STRIPE_PRICE_PREMIUM_MONTHLY'),
    ],
];
