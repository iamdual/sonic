<?php namespace Sonic\Database;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Config\Manager;

final class PDO
{
    private static array $instance;

    public static function getInstance(string $conf_id = 'default'): \PDO
    {
        $conf_key = 'pdo.' . $conf_id;

        if (isset(self::$instance[$conf_key])) {
            return self::$instance[$conf_key];
        }

        $db_conf = Manager::getInstance('database')->getParams();

        $pdo_dsn = $db_conf[$conf_key]['pdo_dsn'];
        $username = $db_conf[$conf_key]['username'] ?? null;
        $password = $db_conf[$conf_key]['password'] ?? null;

        $connection = new \PDO($pdo_dsn, $username, $password);
        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return self::$instance[$conf_key] = $connection;
    }
}