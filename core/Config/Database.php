<?php namespace Sonic\Config;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Singleton;

final class Database
{
    use Singleton;

    private array $params;

    private function __construct()
    {
        $this->params = require APP . '/Config/database.php';
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    public function set(string $key, mixed $value = null): void
    {
        $this->params[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($this->params[$key]);
    }

    public function getParams(): array
    {
        return $this->params;
    }
}