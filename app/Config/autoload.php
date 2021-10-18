<?php

use App\Extension\ServerTime;
use App\Middleware\CorsHeaders;

return [
    'helper' => ['common'],
    'middleware' => [
        CorsHeaders::class,
    ],
    'extension' => [
        ServerTime::class
    ]
];