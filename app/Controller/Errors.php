<?php namespace App\Controller;

use Sonic\Controller;
use Sonic\Request;
use Sonic\Response;

class Errors extends Controller
{
    public function notFound()
    {
        if (Request::header()->acceptsJson()) {
            Response::statusCode(404);
            Response::json(['success' => false, 'message' => '404 Not found']);
            return;
        }

        Response::view()->notFound();
    }
}