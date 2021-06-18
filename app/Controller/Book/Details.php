<?php namespace App\Controller\Book;

use Sonic\Response;

class Details extends \Sonic\Controller
{
    public function index()
    {
        $details = [
            'title' => 'Don Quixote',
            'author' => 'Miguel de Cervantes'
        ];

        Response::statusCode(200);
        Response::json(['success' => true, 'details' => $details]);
    }
}