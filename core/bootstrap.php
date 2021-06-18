<?php defined('SONIC') or exit;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

// Load composer
require SONIC . '/vendor/autoload.php';

// Load environment
Sonic\Config\Environment::getInstance();

// Facade
(new \Sonic\Sonic())->response();