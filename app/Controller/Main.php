<?php namespace App\Controller;

use Sonic\Controller;
use Sonic\Response;

class Main extends Controller
{
    public function index()
    {
        Response::view()->render('welcome',
            [
                'title' => _('Hello world!'),
                'site_name' => config('app.name')
            ]
        );
    }
}