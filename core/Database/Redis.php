<?php namespace Sonic\Database;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Config\Database;

final class Redis
{
    private static array $instance;

    public static function getInstance(string $conf_id = 'default'): \Redis
    {
        $conf_key = 'redis.' . $conf_id;

        if (isset(self::$instance[$conf_key])) {
            return self::$instance[$conf_key];
        }

        $db_conf = Database::getInstance()->getParams();

        $address = $db_conf[$conf_key]['address'];
        $port = $db_conf[$conf_key]['port'] ?? null;
        $database = $db_conf[$conf_key]['database'] ?? null;
        $auth = $db_conf[$conf_key]['auth'] ?? null;

        $connection = new \Redis();
        $connection->connect($address, $port);
        if ($database) {
            $connection->select((int)$database);
        }
        if ($auth) {
            $connection->auth($auth);
        }
        return self::$instance[$conf_key] = $connection;
    }
}