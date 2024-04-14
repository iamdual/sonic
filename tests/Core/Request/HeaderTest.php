<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Request;

use PHPUnit\Framework\TestCase;
use Sonic\Request;

final class HeaderTest extends TestCase
{
    /**
     * @covers \Sonic\Request
     */
    public function testRequestClientIp(): void
    {
        $_SERVER['REMOTE_ADDR'] = '1.0.0.1';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '1.1.1.1,8.8.8.8,192.168.1.1';
        $_SERVER['HTTP_X_REAL_IP'] = '2.2.2.2';
        self::assertEquals('1.1.1.1', Request::header()->clientIp());
        self::assertEquals('1.0.0.1', Request::header()->clientIp(false));

        unset($_SERVER['HTTP_CF_CONNECTING_IP'], $_SERVER['HTTP_X_FORWARDED_FOR'], $_SERVER['HTTP_X_REAL_IP']);
    }

    /**
     * @covers \Sonic\Request
     */
    public function testRequestClientIp2(): void
    {
        $_SERVER['REMOTE_ADDR'] = '8.8.8.8';
        $_SERVER['HTTP_CF_CONNECTING_IP'] = '127.0.0.1'; // reserved ip addresses coming from header is not allowed
        self::assertEquals('8.8.8.8', Request::header()->clientIp());
        self::assertEquals('8.8.8.8', Request::header()->clientIp(false));

        unset($_SERVER['HTTP_CF_CONNECTING_IP'], $_SERVER['HTTP_X_FORWARDED_FOR'], $_SERVER['HTTP_X_REAL_IP']);
    }
}
