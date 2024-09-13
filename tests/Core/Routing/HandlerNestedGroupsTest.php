<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Routing;

use PHPUnit\Framework\TestCase;
use Sonic\Request\Method;
use Sonic\Routing\RouteCollector;
use Sonic\Routing\RouteMatch;
use Sonic\Routing\RouteMatcher;
use Sonic\Tests\Core\TestApp\Controller\RestfulBook;
use Sonic\Tests\Core\TestApp\Middleware\Authenticate;
use Sonic\Tests\Core\TestApp\Middleware\Secondary;

final class HandlerNestedGroupsTest extends TestCase
{
    private \Closure $routes;

    protected function setUp(): void
    {
        $this->routes = static function (RouteCollector $routing) {
            $routing->group('/book', function () use ($routing) {
                $routing->get('/', [RestfulBook::class, 'book_list']);
                $routing->group('/admin', function () use ($routing) {
                    $routing->post('/insert', [RestfulBook::class, 'book_insert']);
                    $routing->put('/([0-9]+)', [RestfulBook::class, 'book_update']);
                }, middleware: [Authenticate::class]);
            }, middleware: [Secondary::class]);
            $routing->get('/single', [RestfulBook::class, 'book_list']);
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

        [$class, $method] = $matched->getRoute()->getHandler();
        self::assertEquals('book_list', $method);
        $controller = new $class();
        self::assertInstanceOf(RestfulBook::class, $controller);

        $middleware = $matched->getRoute()->getMiddleware();
        self::assertNotNull($middleware);
        self::assertIsArray($middleware);
        self::assertCount(1, $middleware);
        self::assertTrue(in_array(Secondary::class, $middleware));
        self::assertFalse(in_array(Authenticate::class, $middleware));

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
        $routeMatcher = new RouteMatcher('/book/admin/insert', Method::POST);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);

        [$class, $method] = $matched->getRoute()->getHandler();
        self::assertEquals('book_insert', $method);
        $controller = new $class();
        self::assertInstanceOf(RestfulBook::class, $controller);

        $middleware = $matched->getRoute()->getMiddleware();
        self::assertNotNull($middleware);
        self::assertIsArray($middleware);
        self::assertCount(2, $middleware);
        self::assertTrue(in_array(Secondary::class, $middleware));
        self::assertTrue(in_array(Authenticate::class, $middleware));

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
    public function testBookUpdateJsonResponse(): void
    {
        $routeMatcher = new RouteMatcher('/book/admin/2469', Method::PUT);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);
        self::assertEquals(['2469'], $matched->getParams());

        [$class, $method] = $matched->getRoute()->getHandler();
        self::assertEquals('book_update', $method);
        $controller = new $class();
        self::assertInstanceOf(RestfulBook::class, $controller);

        $middleware = $matched->getRoute()->getMiddleware();
        self::assertNotNull($middleware);
        self::assertIsArray($middleware);
        self::assertCount(2, $middleware);
        self::assertTrue(in_array(Secondary::class, $middleware));
        self::assertTrue(in_array(Authenticate::class, $middleware));

        ob_start();
        call_user_func_array([$controller, $method], $matched->getParams());
        $output = ob_get_clean();

        $json = json_decode($output);
        self::assertTrue($json->success);
        self::assertNotEmpty($json->updated_at);
        self::assertEquals(2469, $json->details->id);
        self::assertEquals('Don Quixote', $json->details->title);
    }

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Routing\RouteMatcher
     * @covers \Sonic\Tests\Core\TestApp\Controller\RestfulBook
     */
    public function testBookSingleJsonResponse(): void
    {
        $routeMatcher = new RouteMatcher('/single', Method::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);

        [$class, $method] = $matched->getRoute()->getHandler();
        self::assertEquals('book_list', $method);
        $controller = new $class();
        self::assertInstanceOf(RestfulBook::class, $controller);

        $middleware = $matched->getRoute()->getMiddleware();
        self::assertNull($middleware);

        ob_start();
        call_user_func_array([$controller, $method], $matched->getParams());
        $output = ob_get_clean();

        $json = json_decode($output);
        self::assertTrue($json->success);
    }
}
