<?php namespace Sonic\Routing;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Request\Method;
use Sonic\Request\URL;

final class RouteCollector
{
    private static array $routes = [];
    private ?string $group_path = null;
    private ?array $group_middleware = null;

    /**
     * @param string $path Group request path
     * @param callable $callback Group callback function
     * @param array|null $middleware Group middleware classes
     * @return void
     */
    public function group(string $path, callable $callback, ?array $middleware = null): void
    {
        $this->group_path = $path;
        $this->group_middleware = $middleware;
        $callback();
        $this->group_path = null;
        $this->group_middleware = null;
    }

    /**
     * @param string $path Route path
     * @param array $handler Route handler
     * @param Method[]|null $methods Allowed methods
     * @param array|null $middleware Middleware classes
     * @return void
     */
    public function route(string $path, array $handler, ?array $methods = null, ?array $middleware = null): void
    {
        $path = $this->group_path . $path;
        $path = rtrim($path, URL::PATH_SEPARATOR);

        if (!empty($this->group_middleware)) {
            if (!empty($middleware)) {
                $middleware = array_merge($middleware, $this->group_middleware);
            } else {
                $middleware = $this->group_middleware;
            }
        }

        $route = new Route($path, $handler);
        if (!empty($methods)) {
            $route->setMethods($methods);
        }
        if (!empty($middleware)) {
            $route->setMiddleware($middleware);
        }
        self::$routes[] = $route;
    }

    public function get(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Method::GET], middleware: $middleware);
    }

    public function post(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Method::POST], middleware: $middleware);
    }

    public function put(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Method::PUT], middleware: $middleware);
    }

    public function patch(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Method::PATCH], middleware: $middleware);
    }

    public function delete(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Method::DELETE], middleware: $middleware);
    }

    public function head(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Method::HEAD], middleware: $middleware);
    }

    public function options(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Method::OPTIONS], middleware: $middleware);
    }

    /**
     * @return Route[] Returns all of routes
     */
    static public function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * Flushes routes from memory. This is commonly used in tests.
     * @return void
     */
    static public function flushRoutes(): void
    {
        self::$routes = [];
    }
}