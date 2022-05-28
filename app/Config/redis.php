<?php

return [
    'default' => [
        'address' => getenv('REDIS_ADDRESS') ?: '127.0.0.1',
        'port' => getenv('REDIS_PORT') ?: '6379',
    ]
];