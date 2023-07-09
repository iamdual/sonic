<?php namespace Sonic\Routing;
use Sonic\Request\Method;

/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */
final class Route
{
    private string $rule;
    private array $handler;
    /** @var Method[] */
    private array $methods;
    private array $middleware;

    /**
     * @param string $rule Route rule in regular expression
     * @param array $handler Handler class
     */
    public function __construct(string $rule, array $handler)
    {
        $this->rule = $rule;
        $this->handler = $handler;
    }

    /**
     * @return void
     * @var $methods Method[] Allowed request methods
     */
    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
    }

    /**
     * @return void
     * @var $mwClasses string[] Middleware classes
     */
    public function setMiddleware(array $mwClasses): void
    {
        $this->middleware = $mwClasses;
    }

    /**
     * @return string|null Returns route rule in regular expression
     */
    public function getRule(): ?string
    {
        return $this->rule ?? null;
    }

    /**
     * @return array|null Returns handler class
     */
    public function getHandler(): ?array
    {
        return $this->handler ?? null;
    }

    /**
     * @return Method[]|null Returns allowed methods
     */
    public function getMethods(): ?array
    {
        return $this->methods ?? null;
    }

    /**
     * @return array|null Returns attached middleware classes
     */
    public function getMiddleware(): ?array
    {
        return $this->middleware ?? null;
    }
}