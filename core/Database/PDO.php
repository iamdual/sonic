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
        if (isset(self::$instance[$conf_id])) {
            return self::$instance[$conf_id];
        }

        $db_conf = Manager::getInstance('pdo')->getParams();

        $pdo_dsn = $db_conf[$conf_id]['pdo_dsn'];
        $username = $db_conf[$conf_id]['username'] ?? null;
        $password = $db_conf[$conf_id]['password'] ?? null;

        $connection = new \PDO($pdo_dsn, $username, $password);
        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return self::$instance[$conf_id] = $connection;
    }
}