<?php

return [
    'database' => [
        'database_type' => env('RAVE_WIDGET_DB_CONNECTION', env('DB_CONNECTION')),
        'database_name' => env('RAVE_WIDGET_DB_HOST', env('DB_DATABASE')),
        'server' => env('RAVE_WIDGET_DB_HOST', env('DB_HOST')),
        'username' => env('RAVE_WIDGET_DB_USERNAME', env('DB_USERNAME')),
        'password' => env('RAVE_WIDGET_DB_PASSWORD', env('DB_PASSWORD')),
    ],

    'widget' => [
        'payment_request_table_name' =>  env('RAVE_WIDGET_REQUEST_TABLE', 'rave_payment_request'),
    ],

    'rave' => [
        'public_key' => env('RAVE_PUBLIC_KEY', ''),
        'secret_key' => env('RAVE_SECRET_KEY', ''),
        'environment' => env('RAVE_WIDGET_ENV', env('APP_ENV')),
    ]
];