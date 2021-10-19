<?php declare(strict_types=1);

namespace Sonic\Tests\Core\TestApp\Extension;

use Sonic\Event;
use Sonic\Extension;

class LoadTimer extends Extension
{
    private static int $firstNumber = 0;
    private static int $lastNumber = 0;

    public function init(): void
    {
        Event::add('core.init', [__CLASS__, 'onInit']);
        Event::add('core.loaded', [__CLASS__, 'onEnd']);
    }

    public function onInit(): void
    {
        self::$firstNumber = 10;
    }

    public function onEnd(): void
    {
        self::$lastNumber = 8;

        echo 'Result: ' . (self::$firstNumber - self::$lastNumber);
    }
}