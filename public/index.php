<?php declare(strict_types=1);
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

if (PHP_MAJOR_VERSION < 8) {
    die('Sonic only works with PHP >=8.0');
}

define('SONIC', __DIR__ . '/..');
define('APP', SONIC . '/app');
define('CORE', SONIC . '/core');
require CORE . '/bootstrap.php';

(new \Sonic\Sonic())->response();