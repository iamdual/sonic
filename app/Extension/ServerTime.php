<?php namespace App\Extension;

use Sonic\Event;
use Sonic\Extension;
use Sonic\Response;
use Sonic\Routing\RouteCollector;

class ServerTime extends Extension
{
    // The "core.routes" event is passing the RouteCollector as a parameter to the given function.
    public function init(): void
    {
        Event::add('core.routes', [__CLASS__, 'serverTimeRouter']);
    }

    public static function serverTimeRouter(RouteCollector $routing): void
    {
        $routing->get('/server-time', [__CLASS__, 'serverTimeHandler']);
    }

    public static function serverTimeHandler()
    {
        Response::json([
            "date" => date("Y-m-d H:i:s"),
            "epoch" => time(),
        ]);
    }
}