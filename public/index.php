<?php declare(strict_types=1);
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

if (PHP_VERSION_ID < 80100) {
    die('Sonic only works with PHP >=8.1');
}

const SONIC = __DIR__ . '/..';
const APP = SONIC . '/app';
const CORE = SONIC . '/core';
require CORE . '/bootstrap.php';

(new \Sonic\Sonic())->response();
