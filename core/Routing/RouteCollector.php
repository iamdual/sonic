<?php namespace Sonic\Routing;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Request;

final class RouteCollector
{
    private static array $routes = [];
    private ?string $group_path = null;
    private ?array $group_middleware = null;

    public function group(string $path, callable $callback, ?array $middleware = null): void
    {
        $this->group_path = $path;
        $this->group_middleware = $middleware;
        $callback();
        $this->group_path = null;
        $this->group_middleware = null;
    }

    public function route(string $path, array $handler, ?array $methods = null, ?array $middleware = null): void
    {
        $path = $this->group_path . $path;
        if (empty($middleware) && !empty($this->group_middleware)) {
            $middleware = $this->group_middleware;
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
        $this->route($path, $handler, methods: [Request::GET], middleware: $middleware);
    }

    public function post(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Request::POST], middleware: $middleware);
    }

    public function put(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Request::PUT], middleware: $middleware);
    }

    public function patch(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Request::PATCH], middleware: $middleware);
    }

    public function delete(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Request::DELETE], middleware: $middleware);
    }

    public function head(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Request::HEAD], middleware: $middleware);
    }

    public function options(string $path, array $handler, ?array $middleware = null): void
    {
        $this->route($path, $handler, methods: [Request::OPTIONS], middleware: $middleware);
    }

    /** @return Route[] */
    public function getRoutes(): array
    {
        return self::$routes;
    }
}