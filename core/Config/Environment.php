<?php namespace Sonic\Config;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Exception\EnvironmentException;
use Sonic\Singleton;

final class Environment
{
    use Singleton;

    public static string $env_file = SONIC . '/.env';
    public static string $cached_file = SONIC . '/.env.cached.php';
    private array $env = [];

    private function __construct()
    {
        if (is_file(self::$cached_file)) {
            $cached_env = require self::$cached_file;
            foreach ($cached_env as $key => $value) {
                $this->set($key, $value);
            }
            return;
        }

        if (!is_file(self::$env_file)) {
            return;
        }

        try {
            $this->readEnvironmentFile();
            $this->generateCachedFile();
        } catch (EnvironmentException $e) {
            throw new EnvironmentException($e->getMessage());
        }
    }

    public function set(string $key, string $value): void
    {
        $_ENV[$key] = $this->env[$key] = $value;
        putenv($key . '=' . $value);
    }

    public function get(string $key, ?string $default = null): ?string
    {
        return $this->env[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->env[$key]);
    }

    private function readEnvironmentFile(): void
    {
        $handle = fopen(self::$env_file, 'r');
        if (!$handle) {
            throw new EnvironmentException(sprintf(
                'Environment file %s cannot be read', self::$env_file));
        }

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if (empty($line) || $line[0] === '#') {
                continue;
            }
            if (!($assignment = explode('=', $line, 2)) || !isset($assignment[1])) {
                continue;
            }
            $key = trim($assignment[0]);
            $val = trim($assignment[1]);
            $val = trim($val, '"\'');
            $this->set($key, $val);
        }

        fclose($handle);
    }

    private function generateCachedFile(): void
    {
        $config = Application::getInstance();
        if ($config->get('cache.env.enabled', true) == false) {
            return;
        }
        
        $handle = fopen(self::$cached_file, 'w');
        if (!$handle) {
            throw new EnvironmentException(sprintf(
                'Cached environment file %s cannot be written', self::$cached_file));
        }

        $content = '<?php /* This file is auto-generated by Sonic */ return [';
        foreach ($this->env as $key => $value) {
            $value = str_replace("'", "\'", $value);
            $content .= "'{$key}'=>'{$value}',";
        }
        $content .= ']; ?>';
        fwrite($handle, $content);
        fclose($handle);
    }
}