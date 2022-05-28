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
        $app_config = Manager::getInstance('app', self::CONFIG_DIR);

        $this->assertEquals('Antarctica/Macquarie', $app_config->get('timezone'));
        $this->assertEquals('Sonic Test', $app_config->get('name'));
        $this->assertTrue($app_config->has('name'));
        $this->assertFalse($app_config->has('iamdual'));

        $app_config->set('iamdual', 'existency');
        $this->assertTrue($app_config->has('iamdual'));
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
