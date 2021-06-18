<?php declare(strict_types=1);

namespace Sonic\Tests\Core\Routing;

use PHPUnit\Framework\TestCase;
use Sonic\Request;
use Sonic\Routing\RouteCollector;
use Sonic\Routing\RouteMatch;
use Sonic\Routing\RouteMatcher;
use Sonic\Tests\Core\TestApp\Controller\RestfulBook;
use Sonic\Tests\Core\TestApp\Middleware\Authenticate;

final class MiddlewareTest extends TestCase
{
    private \Closure $routes;

    protected function setUp(): void
    {
        $this->routes = static function (RouteCollector $routing) {
            $routing->group('/secret-book', function () use ($routing) {
                $routing->get('', [RestfulBook::class, 'book_list']);
                $routing->put('/insert', [RestfulBook::class, 'book_insert']);
                $routing->get('/([0-9]+)', [RestfulBook::class, 'book_details']);
            }, middleware: [Authenticate::class]);
            $routing->get('/open', [RestfulBook::class, 'book_list']);
        };
    }

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Routing\RouteMatcher
     * @covers \Sonic\Tests\Core\TestApp\Controller\RestfulBook
     */
    public function testSecretBookJsonResponse(): void
    {
        $routeMatcher = new RouteMatcher('/secret-book/insert', Request::PUT);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);

        $middleware = $matched->getRoute()->getMiddleware();
        self::assertNotNull($middleware);
        self::assertIsArray($middleware);

        foreach ($middleware as $mwClass) {
            $mwReturn = null;

            ob_start();
            $mwInstance = new $mwClass();
            $mwReturn = $mwInstance->handler();
            $output = ob_get_clean();
            $json = json_decode($output);

            if ($mwClass === Authenticate::class) {
                self::assertFalse($mwReturn);
                self::assertFalse($json->success);
                self::assertEquals('Unauthorized token.', $json->message);
            }
        }
    }

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Routing\RouteMatcher
     * @covers \Sonic\Tests\Core\TestApp\Controller\RestfulBook
     */
    public function testSecretBookJsonResponse2(): void
    {
        $_GET['token'] = 'iamdual'; // Just a trick for middleware auth
        $routeMatcher = new RouteMatcher('/secret-book/1234', Request::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);

        $middleware = $matched->getRoute()->getMiddleware();
        self::assertNotNull($middleware);
        self::assertIsArray($middleware);

        foreach ($middleware as $mwClass) {
            $mwReturn = null;

            ob_start();
            $mwInstance = new $mwClass();
            $mwReturn = $mwInstance->handler();
            $output = ob_get_clean();

            if ($mwClass === Authenticate::class) {
                self::assertTrue($mwReturn);
            }
        }

        list($class, $method) = $matched->getRoute()->getHandler();
        self::assertEquals('book_details', $method);
        $controller = new $class();
        self::assertInstanceOf(RestfulBook::class, $controller);

        ob_start();
        call_user_func_array([$controller, $method], $matched->getParams());
        $output = ob_get_clean();
        $json = json_decode($output);

        self::assertTrue($json->success);
        self::assertEquals('Don Quixote', $json->details->title);
        self::assertEquals('Miguel de Cervantes', $json->details->author);

        unset($_GET['token']);
    }

    /**
     * @runInSeparateProcess
     * @covers \Sonic\Routing\RouteMatcher
     * @covers \Sonic\Tests\Core\TestApp\Controller\RestfulBook
     */
    public function testOpenJsonResponse(): void
    {
        $routeMatcher = new RouteMatcher('/open', Request::GET);
        $matched = $routeMatcher->getMatched($this->routes);
        self::assertNotNull($matched);
        self::assertInstanceOf(RouteMatch::class, $matched);

        $middleware = $matched->getRoute()->getMiddleware();
        self::assertNull($middleware);
    }
}
