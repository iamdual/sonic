<?php

return [
    'enabled' => true,

    // Session configuration options:
    // https://www.php.net/manual/en/session.configuration.php
    'options' => [
        'name' => '_sonicsessid',
        'save_handler' => 'files'
    ]
];