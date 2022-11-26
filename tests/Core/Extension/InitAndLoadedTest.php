<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Extension;

use PHPUnit\Framework\TestCase;
use Sonic\Event;
use Sonic\Tests\Core\TestApp\Extension\Subtraction;

final class InitAndLoadedTest extends TestCase
{
    protected function setUp(): void
    {
        call_user_func([new Subtraction, 'init']);
    }

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Tests\Core\TestApp\Extension\Subtraction
     */
    public function testHandler(): void
    {
        Event::call('core.init');

        ob_start();
        Event::call('core.finish');
        $output = ob_get_clean();

        self::assertEquals('Result: 2', $output);
    }
}
