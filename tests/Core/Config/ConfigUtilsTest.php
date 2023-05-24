<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Config;

use PHPUnit\Framework\TestCase;
use Sonic\Config\Config;

final class ConfigUtilsTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @covers \Sonic\Config\Config
     */
    public function testConfigExpression(): void
    {
        [$namespace, $key] = Config::parseExpression('app.timezone');
        $this->assertEquals('app', $namespace);
        $this->assertEquals('timezone', $key);

        [$namespace, $key] = Config::parseExpression('core.env.caching');
        $this->assertEquals('core', $namespace);
        $this->assertEquals('env.caching', $key);
    }
}
