<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Routing;

use PHPUnit\Framework\TestCase;
use Sonic\Request\Method;
use Sonic\Routing\RouteCollector;
use Sonic\Routing\RouteMatch;
use Sonic\Routing\RouteMatcher;

final class MatcherTest extends TestCase
{
    private \Closure $routes;

    protected function setUp(): void
    {
        $this->routes = static function (RouteCollector $routing) {
            $routing->route('/', ['Controller' => 'Home']);
            $routing->group('/book', function () use ($routing) {
                $routing->route('/([0-9]+)', ['Controller' => 'BookDetails']);
            });
            $routing->route('/r1', ['Controller' => 'R1']);
            $routing->route('/r2/', ['Controller' => 'R2']);
        };
    }

    protected function tearDown(): void
    {
        RouteCollector::flushRoutes();
    }

    /**
     * @covers \Sonic\Routing\RouteMatcher
     */
    public function testRouteMatcher(): void
    {
        $routeMatcher = new RouteMatcher('/', Method::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertEquals('', $matched->getRoute()->getRule());
        self::assertIsArray($matched->getRoute()->getHandler());
        self::assertEquals('Home', $matched->getRoute()->getHandler()['Controller']);

        $routeMatcher = new RouteMatcher('', Method::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertEquals('', $matched->getRoute()->getRule());
        self::assertEquals('Home', $matched->getRoute()->getHandler()['Controller']);

        $routeMatcher = new RouteMatcher('/book/123/', Method::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertEquals('/book/([0-9]+)', $matched->getRoute()->getRule());
        self::assertEquals('BookDetails', $matched->getRoute()->getHandler()['Controller']);

        $routeMatcher = new RouteMatcher('/r1/', Method::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertEquals('/r1', $matched->getRoute()->getRule());
        self::assertEquals('R1', $matched->getRoute()->getHandler()['Controller']);

        $routeMatcher = new RouteMatcher('/r2', Method::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertEquals('/r2', $matched->getRoute()->getRule());
        self::assertEquals('R2', $matched->getRoute()->getHandler()['Controller']);
    }
}
