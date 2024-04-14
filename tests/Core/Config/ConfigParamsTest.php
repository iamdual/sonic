<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Config;

use PHPUnit\Framework\TestCase;
use Sonic\Config\Config;

final class ConfigParamsTest extends TestCase
{
    const CONFIG_DIR = SONIC . '/tests/Core/TestApp/Config';

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Config\Config
     */
    public function testConfigApp(): void
    {
        $app_params = new Config('app', self::CONFIG_DIR);
        $this->assertNotEmpty($app_params->getParams());

        $this->assertEquals('Antarctica/Macquarie', $app_params->get('timezone'));
        $this->assertEquals('Sonic Test', $app_params->get('name'));
        $this->assertTrue($app_params->has('name'));
        $this->assertFalse($app_params->has('iamdual'));

        $app_params->set('iamdual', 'existence');
        $this->assertTrue($app_params->has('iamdual'));
    }

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Config\Config
     */
    public function testConfigCore(): void
    {
        $core_params = new Config('core', self::CONFIG_DIR);
        $this->assertNotEmpty($core_params->getParams());

        $this->assertFalse($core_params->get('env.caching'));
        $this->assertTrue($core_params->has('env.caching'));
    }
}
