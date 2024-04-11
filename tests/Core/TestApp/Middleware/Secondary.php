<?php declare(strict_types=1);

namespace Sonic\Tests\Core\TestApp\Middleware;

use Sonic\Middleware;

class Secondary extends Middleware
{
    public function handler(): bool
    {
        return true; // continue
    }
}