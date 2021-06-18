<?php

return [
    'app.timezone' => getenv('APP_TIMEZONE'),
    'app.name' => getenv('APP_NAME') ?: 'Sonic',
    'app.url' => getenv('APP_URL') ?: 'http://github.com/iamdual/sonic',

    'i18n.enabled' => true,
    'i18n.default' => 'en',
    'i18n.languages' => ['en', 'fr', 'tr'],
    'i18n.domains' => ['messages'],

    'session.enabled' => true,
    'session.config' => [
        'name' => '_sonicsessid',
        'save_handler' => 'files'
    ]
];