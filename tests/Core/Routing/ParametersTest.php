<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Routing;

use PHPUnit\Framework\TestCase;
use Sonic\Request\Method;
use Sonic\Routing\RouteCollector;
use Sonic\Routing\RouteMatch;
use Sonic\Routing\RouteMatcher;

final class ParametersTest extends TestCase
{
    private \Closure $routes;

    protected function setUp(): void
    {
        $this->routes = static function (RouteCollector $routing) {
            $routing->group('/p', function () use ($routing) {
                $routing->post('/n([0-9]+)/s([a-z]+)/a(.+)', []);
            });
            $routing->put('/a*b', []);
        };
    }

    /**
     * @covers \Sonic\Routing\RouteMatcher
     */
    public function testRouteParameter(): void
    {
        $routeMatcher = new RouteMatcher('/p/n2468/shello/a@world', Method::POST);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);
        self::assertEquals('/p/n([0-9]+)/s([a-z]+)/a(.+)', $matched->getRoute()->getRule());
        self::assertEquals(['2468', 'hello', '@world'], $matched->getParams());
        self::assertEquals([], $matched->getRoute()->getHandler());
        self::assertEquals([Method::POST], $matched->getRoute()->getMethods());
    }

    /**
     * @covers \Sonic\Routing\RouteMatcher
     */
    public function testRouteParameter2(): void
    {
        $routeMatcher = new RouteMatcher('/a*b', Method::PUT);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNull($matched);
    }

    /**
     * @covers \Sonic\Routing\RouteMatcher
     */
    public function testRouteParameter3(): void
    {
        $routeMatcher = new RouteMatcher('/ab', Method::PUT);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);
        self::assertEquals('/a*b', $matched->getRoute()->getRule());
        self::assertEquals([], $matched->getParams());
        self::assertEquals([], $matched->getRoute()->getHandler());
        self::assertEquals([Method::PUT], $matched->getRoute()->getMethods());
    }
}
