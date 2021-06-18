<?php namespace App\Middleware;

use Sonic\Middleware;
use Sonic\Response;

class CorsHeaders extends Middleware
{
    public function handler(): bool
    {
        Response::headers([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET,POST,PUT,DELETE',
            'Access-Control-Allow-Headers' => 'Content-Type'
        ]);

        return true; // continue
    }
}