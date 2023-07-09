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

    public function set(string $key, string $val, int $expiry = 0, string $path = '/', string $domain = ''): bool
    {
        if (!$expiry) {
            $expiry = time() + (86400 * 31);
        }
        return setcookie($key, $val, $expiry, $path, $domain);
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
}