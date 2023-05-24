<?php namespace Sonic\Config;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */
class Config
{
    private static array $instance;
    private array $params;
    private string $current;

    public static function getInstance(string $namespace): self
    {
        if (isset(self::$instance[$namespace])) {
            return self::$instance[$namespace];
        }

        return self::$instance[$namespace] = new Config($namespace);
    }

    public static function parseExpression(string $expression): array
    {
        $parts = explode('.', $expression, 2);
        if (isset($parts[1])) {
            $namespace = $parts[0];
            $key = $parts[1];
        } else {
            $namespace = 'app';
            $key = $parts[0];
        }
        return [$namespace, $key];
    }

    public function __construct(string $namespace, ?string $config_dir = null)
    {
        $this->current = $namespace;

        if (!$config_dir) {
            $config_dir = APP . '/Config';
        }

        if (is_file("$config_dir/$namespace.php")) {
            $this->params[$namespace] = require "$config_dir/$namespace.php";
        }
    }

    public function get(string $key, mixed $default = null)
    {
        return $this->params[$this->current][$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->params[$this->current][$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($this->params[$this->current][$key]);
    }

    public function getParams(): ?array
    {
        return $this->params[$this->current] ?? null;
    }
}