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
        Event::add('core.routes', [ServerTime::class, 'serverTimeRouter']);
    }

    // All the event handler functions has to be static.
    public static function serverTimeRouter(RouteCollector $routing)
    {
        $routing->get('/server-time', [ServerTime::class, 'serverTimeHandler']);
    }

    // All the route handlers are calling dynamically by creating as a new instance.
    public function serverTimeHandler()
    {
        Response::json([
            "date" => date("Y-m-d H:i:s"),
            "epoch" => time(),
        ]);
    }
}