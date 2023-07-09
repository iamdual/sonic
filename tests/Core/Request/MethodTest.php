<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Request;

use PHPUnit\Framework\TestCase;
use Sonic\Request;

final class MethodTest extends TestCase
{
    /**
     * @covers \Sonic\Request
     */
    public function testRequestMethod(): void
    {
        unset($_SERVER['REQUEST_METHOD']);
        $_SERVER['REQUEST_METHOD'] = 'GET';

        self::assertEquals(Request\Method::GET, Request::method());
        self::assertTrue(Request::isMethod(Request\Method::GET));
        self::assertFalse(Request::isMethod(Request\Method::POST));
    }
}
