<?php namespace Sonic;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */
final class Event
{
    private static array $events = [];

    public static function add(string $key, callable $callable): void
    {
        self::$events[$key][] = $callable;
    }

    public static function get(string $key): array
    {
        return self::$events[$key] ?? [];
    }

    public static function call(string $key, ...$args): void
    {
        foreach (self::get($key) as $callable) {
            call_user_func_array($callable, $args);
        }
    }
}