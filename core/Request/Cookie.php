<?php namespace Sonic\Request;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Singleton;

final class Cookie
{
    use Singleton;

    public function set(string $key, string $val, array $options = []): bool
    {
        if (!isset($options['expires'])) {
            $options['expires'] = time() + 2630000; // about a month
        }
        if (!isset($options['path'])) {
            $options['path'] = '/';
        }
        return setcookie($key, $val, $options);
    }

    public function get(string $key, ?string $default = null): ?string
    {
        return $_COOKIE[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }

    public function delete(string $key): bool
    {
        unset($_COOKIE[$key]);
        return setcookie($key, '', -1);
    }

    public function getAll(): array
    {
        return $_COOKIE ?? [];
    }
}