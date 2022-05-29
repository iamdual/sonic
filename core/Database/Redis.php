<?php namespace Sonic\Database;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Config\Manager;

final class Redis
{
    private static array $instance;

    public static function getInstance(string $conf_id = 'default'): \Redis
    {
        if (isset(self::$instance[$conf_id])) {
            return self::$instance[$conf_id];
        }

        $db_conf = Manager::getInstance('redis')->getParams();

        $address = $db_conf[$conf_id]['address'];
        $port = $db_conf[$conf_id]['port'] ?? null;
        $database = $db_conf[$conf_id]['database'] ?? null;
        $auth = $db_conf[$conf_id]['auth'] ?? null;

        $connection = new \Redis();
        $connection->connect($address, $port);
        if ($database) {
            $connection->select((int)$database);
        }
        if ($auth) {
            $connection->auth($auth);
        }
        return self::$instance[$conf_id] = $connection;
    }
}