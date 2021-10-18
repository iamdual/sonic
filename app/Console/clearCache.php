<?php namespace App\Console;

use Sonic\Console;

class clearCache extends Console
{
    public function handler(...$args): void
    {
        @unlink(SONIC . '/.env.cached.php');
    }
}