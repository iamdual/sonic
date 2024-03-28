<?php namespace Sonic\Routing;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Event;
use Sonic\Request\Method;
use Sonic\Request\URL;

final class RouteMatcher
{
    private string $path;
    private Method $method;

    /**
     * @param string $path Request path
     * @param Method $method Request method enumeration
     */
    public function __construct(string $path, Method $method)
    {
        $path = rtrim($path, URL::PATH_SEPARATOR);
        $this->path = $path;
        $this->method = $method;
    }

    /**
     * @param callable $routes The route function came from Config/routes.php
     * @return RouteMatch|null
     */
    public function getMatched(callable $routes): ?RouteMatch
    {
        $routeCollector = new RouteCollector();

        // Apply the application specific routes
        $routes($routeCollector);

        // Apply the event specific routes
        // Objects are always passed by reference. So, RouteCollector is passed as an argument to the given callback.
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