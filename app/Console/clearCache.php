<?php namespace App\Console;

use Sonic\Config\Environment;
use Sonic\Console;

class clearCache extends Console
{
    public function handler(...$args): void
    {
        @unlink(Environment::$cached_file);
    }
}