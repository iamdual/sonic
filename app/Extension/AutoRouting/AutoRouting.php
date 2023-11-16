<?php namespace App\Extension\AutoRouting;
/**
 * Sonic Simple AutoRouting Extension
 *
 * @license Apache License 2.0
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Event;
use Sonic\Extension;
use Sonic\Request;
use Sonic\Routing\RouteCollector;

class AutoRouting extends Extension
{
    // init() is always run if extension is set on the autoload.php
    public function init(): void
    {
        // The "core.routes" event is passing the RouteCollector as a parameter to the given callback function.
        Event::add('core.routes', [__CLASS__, 'autoRouting']);
    }

    public static function autoRouting(RouteCollector $routing): void
    {
        $requestPath = Request::url()->path();
        $controller  = new Compiler($requestPath);
        if (is_file($controller->filename)) {
            $routing->route(Request::url()->path(), [$controller->class, $controller->method]);
        }
    }
}