<?php

return [
    'timezone' => getenv('APP_TIMEZONE'),
    'name' => getenv('APP_NAME') ?: 'Sonic',
    'url' => getenv('APP_URL') ?: 'http://github.com/iamdual/sonic',
];