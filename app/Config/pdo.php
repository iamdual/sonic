<?php

return [
    'default' => [
        'pdo_dsn' => getenv('DB_PDO_DSN') ?:
            'pgsql:host=localhost;port=5432;dbname=sonic;user=dual;password=1234',
        'username' => getenv('DB_USERNAME') ?: null,
        'password' => getenv('DB_PASSWORD') ?: null,
    ],
];