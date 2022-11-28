<?php namespace Sonic\Routing;
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
    private array $methods;
    private array $middleware;

    public function __construct(string $rule, array $handler)
    {
        $this->rule = $rule;
        $this->handler = $handler;
    }

    /** @var $methods string[] */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
    }

    /** @var $mwClasses string[] */
    public function setMiddleware(array $mwClasses)
    {
        $this->middleware = $mwClasses;
    }

    public function getRule(): ?string
    {
        return $this->rule ?? null;
    }

    public function getHandler(): ?array
    {
        return $this->handler ?? null;
    }

    public function getMethods(): ?array
    {
        return $this->methods ?? null;
    }

    /** @return ?string[] */
    public function getMiddleware(): ?array
    {
        return $this->middleware ?? null;
    }
}