<?php namespace App\Extension\AutoRouting;
/**
 * Sonic Simple AutoRouting Extension
 *
 * @license Apache License 2.0
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */
class Compiler
{
    const PAGES_DIR = '_pages';
    const CONTROLLER_PATH = APP . '/Controller/' . self::PAGES_DIR . '/';
    const CONTROLLER_NAMESPACE = 'App\\Controller\\' . self::PAGES_DIR . '\\';

    public string $filename;
    public string $class;
    public string $method;

    public function __construct($uri)
    {
        // Normalize the path
        $uri = strtolower(trim($uri, '/'));

        // Clean the unallowed characters on the path
        $uri = preg_replace('/[^a-z0-9_]/i', '_', $uri);

        // Generate the controller filename
        $filename = self::CONTROLLER_PATH . $uri . '.php';

        // Generate the class name
        $class = self::CONTROLLER_NAMESPACE . str_replace('/', '\\', $uri);

        $this->filename = $filename;
        $this->class    = $class;
        $this->method   = 'index';
    }
}