<?php namespace Sonic;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */
trait Singleton
{
    private static $instance;

    public static function getInstance(): static
    {
        if (empty(self::$instance)) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    private function __clone()
    {
    }
}