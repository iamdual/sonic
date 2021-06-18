<?php namespace Sonic\Routing;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Event;

final class RouteMatcher
{
    private string $path;
    private string $method;

    public function __construct(string $path, string $method)
    {
        $this->path = $path;
        $this->method = $method;
    }

    public function getMatched(callable $routes): ?RouteMatch
    {
        $routeCollector = new RouteCollector();

        // Apply user specific routes
        $routes($routeCollector);

        // Apply event specific routes
        Event::call('core.routes', $routeCollector);

        // Try to match with route rules
        foreach ($routeCollector->getRoutes() as $route) {
            $route_rule_pattern = '#^' . $route->getRule() . '$#';
            if (preg_match($route_rule_pattern, $this->path, $params)) {

                if (!empty($route->getMethods())
                    && !in_array($this->method, $route->getMethods(), true)) {
                    continue;
                }

                $params = array_slice($params, 1);
                return new RouteMatch($route, $params);
            }
        }

        return null;
    }
}