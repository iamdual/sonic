<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Request;

use PHPUnit\Framework\TestCase;
use Sonic\Request;

final class MethodTest extends TestCase
{
    /**
     * @covers \Sonic\Request
     */
    public function testRequestMethod1(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        self::assertEquals(Request\Method::GET, Request::method());

        unset($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @covers \Sonic\Request
     */
    public function testRequestMethod2(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'puT';
        self::assertEquals(Request\Method::PUT, Request::method());

        unset($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @covers \Sonic\Request
     */
    public function testRequestMethod3(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'pOsT';
        self::assertEquals(Request\Method::POST, Request::method());

        unset($_SERVER['REQUEST_METHOD']);
    }
}
