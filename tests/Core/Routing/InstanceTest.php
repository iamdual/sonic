<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Routing;

use PHPUnit\Framework\TestCase;
use Sonic\Request\Method;
use Sonic\Routing\RouteCollector;
use Sonic\Routing\RouteMatch;
use Sonic\Routing\RouteMatcher;

final class InstanceTest extends TestCase
{
    private \Closure $routes;

    protected function setUp(): void
    {
        $this->routes = static function (RouteCollector $routing) {
            $routing->get('/', []);
            $routing->group('/book', function () use ($routing) {
                $routing->get('/([0-9]+)', ['foo' => 'bar']);
                $routing->post('/([0-9]+)', ['foo' => 'bar']);
            });
            $routing->route('/any', []);
        };
    }

    /**
     * @covers \Sonic\Routing\RouteMatcher
     */
    public function testRouteInstance(): void
    {
        $routeMatcher = new RouteMatcher('/', Method::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);

        $routeMatcher = new RouteMatcher('/book', Method::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNull($matched);

        $routeMatcher = new RouteMatcher('/book/1234', Method::POST);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);
        self::assertEquals('/book/([0-9]+)', $matched->getRoute()->getRule());
        self::assertEquals(['foo' => 'bar'], $matched->getRoute()->getHandler());
        self::assertEquals(['1234'], $matched->getParams());

        $routeMatcher = new RouteMatcher('/book/1234', Method::DELETE);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNull($matched);

        $routeMatcher = new RouteMatcher('/any', Method::OPTIONS);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);
    }
}
