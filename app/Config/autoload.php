<?php

use App\Middleware\CorsHeaders;
use App\Extension\ServerTime;

return [
    'helper' => ['common'],
    'middleware' => [
        CorsHeaders::class,
    ],
    'extension' => [
        ServerTime::class
    ]
];