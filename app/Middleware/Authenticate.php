<?php namespace App\Middleware;

use Sonic\Middleware;
use Sonic\Request;
use Sonic\Response;

class Authenticate extends Middleware
{
    public function handler(): bool
    {
        if (Request::header()->get('Token') === 'iamdual') {
            return true; // continue
        }

        Response::statusCode(403);
        Response::json(['success' => false, 'message' => 'Unauthorized token.']);

        return false; // terminate the request
    }
}