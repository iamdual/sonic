<?php namespace App\Console;

use Sonic\Config\Environment;
use Sonic\Console;

class ClearCache extends Console
{
    public function handler(...$args): void
    {
        echo '.env file cache has been cleared.' . PHP_EOL;
        @unlink(Environment::$cached_file);
    }
}