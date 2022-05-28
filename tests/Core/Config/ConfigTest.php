<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Config;

use PHPUnit\Framework\TestCase;
use Sonic\Config\Manager;

final class ConfigTest extends TestCase
{
    const CONFIG_DIR = SONIC . '/tests/Core/TestApp/Config';

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Config\Manager
     */
    public function testConfigApp(): void
    {
        $config_app = Manager::getInstance('app', self::CONFIG_DIR);

        $this->assertEquals('Antarctica/Macquarie', $config_app->get('timezone'));
        $this->assertEquals('Sonic Test', $config_app->get('name'));
        $this->assertTrue($config_app->has('name'));
        $this->assertFalse($config_app->has('iamdual'));

        $config_app->set('iamdual', 'existsency');
        $this->assertTrue($config_app->has('iamdual'));
    }

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Config\Manager
     */
    public function testConfigCore(): void
    {
        $config_core = Manager::getInstance('core', self::CONFIG_DIR);
        $this->assertFalse($config_core->get('env.caching'));
        $this->assertTrue($config_core->has('env.caching'));
    }
}
