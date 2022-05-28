<?php namespace Sonic\Config;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */
class Manager
{
    private static array $instance;
    private array $params;
    private string $current;
    private string $config_dir;

    public static function getInstance(string $namespace, ?string $config_dir = null)
    {
        if (isset(self::$instance[$namespace])) {
            return self::$instance[$namespace];
        }

        return self::$instance[$namespace] = new Manager($namespace, $config_dir);
    }

    private function __construct(string $namespace, ?string $config_dir = null)
    {
        $this->current = $namespace;

        if (!$config_dir) {
            $this->config_dir = APP . '/Config';
        } else {
            $this->config_dir = $config_dir;
        }

        if (is_file("$this->config_dir/$namespace.php")) {
            $this->params[$namespace] = require "$this->config_dir/$namespace.php";
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

    public function getParams(): array
    {
        return $this->params[$this->current];
    }
}