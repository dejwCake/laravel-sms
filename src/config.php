<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default SMS driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default SMS driver user.
    |
    | Supported: "log", "null", "nexmo", "clockwork"
    |
    */

    'default' => env('SMS_DRIVER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    |
    | Here you can define the settings for each driver.
    |
    */

    'drivers' => [

        'aws' => [
            'driver' => 'aws',
            'apiKey' => env('AWS_SNS_API_KEY', null),
            'apiSecret' => env('AWS_SNS_API_SECRET', null),
            'apiRegion' => env('AWS_SNS_API_REGION', null),
        ],

        'clockwork' => [
            'driver' => 'clockwork',
            'apiKey' => env('CLOCKWORK_API_KEY', null),
        ],

        'log' => [
            'driver' => 'log',
        ],

        'mail' => [
            'domain' => env('MAIL_SMS_DOMAIN', null),
        ],

        'nexmo' => [
            'driver' => 'nexmo',
            'apiKey' => env('NEXMO_API_KEY', null),
            'apiSecret' => env('NEXMO_API_SECRET', null),
        ],

        'null' => [
            'driver' => 'null',
        ],

        'o2sk' => [
            'driver' => 'o2sk',
            'apiKey' => env('O2_SK_API_KEY', null),
        ],

        'requestbin' => [
            'path' => env('REQUESTBIN_PATH', null),
        ],

        'textlocal' => [
            'driver' => 'textlocal',
            'apiKey' => env('TEXTLOCAL_API_KEY', null),
        ],

        'twilio' => [
            'driver' => 'twilio',
            'accountId' => env('TWILIO_ACCOUNT_ID', null),
            'apiToken' => env('TWILIO_API_TOKEN', null),
        ],
    ],
];
