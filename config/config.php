<?php

return [
    'app' => [
        'name' => 'TLCDesk',
        'env' => \App\Core\Env::get('APP_ENV', 'production'),
        'url' => \App\Core\Env::get('APP_URL', 'http://localhost'),
    ],
    'database' => [
        'host' => \App\Core\Env::get('DB_HOST', 'localhost'),
        'database' => \App\Core\Env::get('DB_DATABASE', 'tlcdesk'),
        'username' => \App\Core\Env::get('DB_USERNAME', 'root'),
        'password' => \App\Core\Env::get('DB_PASSWORD', ''),
    ]
];
