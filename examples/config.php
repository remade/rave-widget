<?php

return [
    'database' => [
        'database_type' => 'mysql',
        'database_name' => 'test_rave_php_plugin',
        'server' => '10.0.2.2',
        'username' => 'root',
        'password' => 'root',
    ],

    'widget' => [
        'payment_request_table_name' => 'rave_payment_request',
    ],

    'rave' => [
        'public_key' => 'FLWPUBK-11-***************************-X',
        'secret_key' => 'FLWSECK-11-***************************-X',
        'environment' => 'test',
    ]
];

