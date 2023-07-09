<?php declare(strict_types=1);

namespace Sonic\Tests\Core\TestApp\Middleware;

use Sonic\Middleware;
use Sonic\Request;
use Sonic\Response;

class Authenticate extends Middleware
{
    public function handler(): bool
    {
        if (Request::get('token-for-test') === 'iamdual') {
            return true; // continue
        }

        Response::statusCode(403);
        Response::json(['success' => false, 'message' => 'Unauthorized token.']);

        return false; // don't continue
    }
}