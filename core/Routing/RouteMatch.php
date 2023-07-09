<?php namespace Sonic\Routing;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */
final class RouteMatch
{
    private Route $route;
    private array $params;

    /**
     * @param Route $route Route object
     * @param array $params Route params to be passed to the handler
     */
    public function __construct(Route $route, array $params = [])
    {
        $this->route = $route;
        $this->params = $params;
    }

    /**
     * @return Route Returns route object
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * @return array Returns route params to be passed to the handler
     */
    public function getParams(): array
    {
        return $this->params;
    }
}