<?php

return [
  
  

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'clients'),
    ],

   
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'clients',
        ],

        'api' => [
            'driver' => 'sanctum', 
            'provider' => 'clients',
            'hash' => false,
        ],
        'clinic' => [
            'driver' => 'session',
            'provider' => 'clinics',
        ]
    ],

   

    'providers' => [
        'clients' => [
            'driver' => 'eloquent',
            'model' => App\Models\Client::class,
        ],

        'clinics' => [
            'driver' => 'eloquent',
            'model' => App\Models\Clinic::class,
        ],
    ],

    

    'passwords' => [
        'clients' => [
            'provider' => 'clients',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

   

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];
