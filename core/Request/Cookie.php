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

    /**
     * @param string $key Cookie key
     * @param string $value Cookie value
     * @param array $options Options can be found here: https://www.php.net/manual/en/function.setcookie.php
     * @return bool
     */
    public function set(string $key, string $value, array $options = []): bool
    {
        if (!isset($options['expires'])) {
            $options['expires'] = time() + 2630000; // about a month
        }
        if (!isset($options['path'])) {
            $options['path'] = '/';
        }
        return setcookie($key, $value, $options);
    }

    /**
     * @param string $key Cookie key
     * @param string|null $default Default return value
     * @return string|null
     */
    public function get(string $key, ?string $default = null): ?string
    {
        return $_COOKIE[$key] ?? $default;
    }

    /**
     * @param string $key Cookie key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * @param string $key Cookie key
     * @return bool
     */
    public function delete(string $key): bool
    {
        unset($_COOKIE[$key]);
        return setcookie($key, '', -1);
    }

    /**
     * @return array Returns cookies in key/value pair
     */
    public function getAll(): array
    {
        return $_COOKIE ?? [];
    }
}