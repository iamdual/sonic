<?php

use App\Controller\Book\Actions;
use App\Controller\Book\Details;
use App\Controller\Book\Main as BookMain;
use App\Controller\Main;
use App\Middleware\Authenticate;
use Sonic\Routing\RouteCollector;

return static function (RouteCollector $routing) {
    $routing->route('/', [Main::class, 'index']);

    $routing->group('/book', function () use ($routing) {
        $routing->get('/', [BookMain::class, 'index']);
        $routing->get('/details/([0-9]+)', [Details::class, 'index']);
        $routing->post('/([0-9]+)', [Actions::class, 'add']);
        $routing->delete('/([0-9]+)', [Actions::class, 'delete'], middleware: [Authenticate::class]);
    });
};