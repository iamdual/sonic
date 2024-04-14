<?php namespace Sonic\Connection;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Config\Config;

final class Redis
{
    private static array $instance;

    /**
     * @param string $conf_id Redis config ID
     * @return \Redis
     * @throws \RedisException
     */
    public static function getInstance(string $conf_id = 'default'): \Redis
    {
        if (isset(self::$instance[$conf_id])) {
            return self::$instance[$conf_id];
        }

        $params = Config::getInstance('redis')->getParams();

        $address = $params[$conf_id]['address'];
        $port = $params[$conf_id]['port'] ?? null;
        $database = $params[$conf_id]['database'] ?? null;
        $auth = $params[$conf_id]['auth'] ?? null;
        $prefix = $params[$conf_id]['prefix'] ?? null;

        $connection = new \Redis();
        $connection->connect($address, $port);
        if ($database) {
            $connection->select((int)$database);
        }
        if ($auth) {
            $connection->auth($auth);
        }
        if ($prefix) {
            $connection->setOption(\Redis::OPT_PREFIX, $prefix);
        }
        return self::$instance[$conf_id] = $connection;
    }
}