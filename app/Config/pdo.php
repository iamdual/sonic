<?php

return [
    'default' => [
        'dsn' => getenv('PGSQL_DSN') ?: 'pgsql:host=localhost;port=5432;dbname=sonic;user=dual;password=1234',
        'username' => getenv('PGSQL_USERNAME') ?: null,
        'password' => getenv('PGSQL_PASSWORD') ?: null,
    ],
];