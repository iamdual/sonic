<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Routing;

use PHPUnit\Framework\TestCase;
use Sonic\Request\Method;
use Sonic\Routing\RouteCollector;
use Sonic\Routing\RouteMatch;
use Sonic\Routing\RouteMatcher;
use Sonic\Tests\Core\TestApp\Controller\RestfulBook;

final class HandlerTest extends TestCase
{
    private \Closure $routes;

    protected function setUp(): void
    {
        $this->routes = static function (RouteCollector $routing) {
            $routing->group('/book', function () use ($routing) {
                $routing->get('/', [RestfulBook::class, 'book_list']);
                $routing->post('/insert', [RestfulBook::class, 'book_insert']);
                $routing->get('/([0-9]+)', [RestfulBook::class, 'book_details']);
            });
        };
    }

    protected function tearDown(): void
    {
        RouteCollector::flushRoutes();
    }

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Routing\RouteMatcher
     * @covers \Sonic\Tests\Core\TestApp\Controller\RestfulBook
     */
    public function testBookListJsonResponse(): void
    {
        $routeMatcher = new RouteMatcher('/book', Method::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);

        list($class, $method) = $matched->getRoute()->getHandler();
        self::assertEquals('book_list', $method);
        $controller = new $class();
        self::assertInstanceOf(RestfulBook::class, $controller);

        ob_start();
        call_user_func_array([$controller, $method], $matched->getParams());
        $output = ob_get_clean();

        $json = json_decode($output);
        self::assertTrue($json->success);
        self::assertCount(2, $json->books);
    }

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Routing\RouteMatcher
     * @covers \Sonic\Tests\Core\TestApp\Controller\RestfulBook
     */
    public function testBookInsertJsonResponse(): void
    {
        $routeMatcher = new RouteMatcher('/book/insert', Method::POST);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);

        list($class, $method) = $matched->getRoute()->getHandler();
        self::assertEquals('book_insert', $method);
        $controller = new $class();
        self::assertInstanceOf(RestfulBook::class, $controller);

        ob_start();
        call_user_func_array([$controller, $method], $matched->getParams());
        $output = ob_get_clean();

        $json = json_decode($output);
        self::assertTrue($json->success);
    }

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Routing\RouteMatcher
     * @covers \Sonic\Tests\Core\TestApp\Controller\RestfulBook
     */
    public function testBookDetailsJsonResponse(): void
    {
        $routeMatcher = new RouteMatcher('/book/2468', Method::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);
        self::assertEquals(['2468'], $matched->getParams());

        list($class, $method) = $matched->getRoute()->getHandler();
        self::assertEquals('book_details', $method);
        $controller = new $class();
        self::assertInstanceOf(RestfulBook::class, $controller);

        ob_start();
        call_user_func_array([$controller, $method], $matched->getParams());
        $output = ob_get_clean();

        $json = json_decode($output);
        self::assertTrue($json->success);
        self::assertEquals(2468, $json->details->id);
        self::assertEquals('Don Quixote', $json->details->title);
    }
}
