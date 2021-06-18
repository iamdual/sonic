<?php

return [
    'pdo.default' => [
        'pdo_dsn' => getenv('DB_PDO_DSN') ?:
            'pgsql:host=localhost;port=5432;dbname=sonic;user=dual;password=1234',
        'username' => getenv('DB_USERNAME') ?: null,
        'password' => getenv('DB_PASSWORD') ?: null,
    ],
    'redis.default' => [
        'address' => getenv('REDIS_ADDRESS') ?: '127.0.0.1',
        'port' => getenv('REDIS_PORT') ?: '6379',
    ]
];