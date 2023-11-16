<?php

use App\Middleware\CorsHeaders;
use App\Extension\AutoRouting\AutoRouting;

return [
  'helper' => ['common'],
  'middleware' => [
      // CorsHeaders::class,
  ],
  'extension' => [
      // AutoRouting::class,
  ]
];