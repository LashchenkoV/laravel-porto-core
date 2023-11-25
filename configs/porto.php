<?php

return [
    'ship' => [
        'paths' => [
            'configs' => 'app/Ship/Configs',
            'commands' => 'app/Ship/Commands',
            'helpers' => 'app/Ship/Helpers',
            'languages' => 'app/Ship/Resources/Languages',
            'migrations' => 'app/Ship/Migrations',
            'views' => 'app/Ship/Mails/Templates/',
        ],
    ],

    'api' => [
        'url' => env('API_URL', 'http://localhost'),
        'prefix' => env('API_PREFIX', '/'),
        'enable_version_prefix' => true,
        'default_middlewares' => ['api'],

        /*
        |--------------------------------------------------------------------------
        | Rate Limit (throttle)
        |--------------------------------------------------------------------------
        |
        | Attempts per minutes.
        | `attempts` is the number of attempts per `expires` in minutes.
        |
        */
        'throttle' => [
            'enabled' => env('GLOBAL_API_RATE_LIMIT_ENABLED', true),
            'attempts' => env('GLOBAL_API_RATE_LIMIT_ATTEMPTS_PER_MIN', '60'),
            'expires' => env('GLOBAL_API_RATE_LIMIT_EXPIRES_IN_MIN', '1'),
        ],
    ],
];
