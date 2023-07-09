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

    /**
     * @param string $key Event key
     * @param callable $callback Event callback function
     * @return void
     */
    public static function add(string $key, callable $callback): void
    {
        self::$events[$key][] = $callback;
    }

    /**
     * @param string $key Event key
     * @param ...$args
     * @return void
     */
    public static function call(string $key, ...$args): void
    {
        if (empty(self::$events[$key])) {
            return;
        }

        foreach (self::$events[$key] as $callback) {
            call_user_func_array($callback, $args);
        }
    }
}