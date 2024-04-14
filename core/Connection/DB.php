<?php namespace Sonic\Connection;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Config\Config;

final class DB
{
    private static array $instance;

    /**
     * @param string $conf_id Database config ID
     * @return \PDO
     */
    public static function getInstance(string $conf_id = 'default'): \PDO
    {
        if (isset(self::$instance[$conf_id])) {
            return self::$instance[$conf_id];
        }

        $params = Config::getInstance('pdo')->getParams();

        $dsn = $params[$conf_id]['dsn'];
        $username = $params[$conf_id]['username'] ?? null;
        $password = $params[$conf_id]['password'] ?? null;

        $connection = new \PDO($dsn, $username, $password);
        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return self::$instance[$conf_id] = $connection;
    }
}