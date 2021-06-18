<?php namespace App\Controller\Book;

use Sonic\Response;

class Main extends \Sonic\Controller
{
    public function index()
    {
        $books = [
            ['title' => 'Don Quixote', 'author' => 'Miguel de Cervantes'],
            ['title' => 'Moby Dick', 'author' => 'Herman Melville']
        ];

        Response::statusCode(200);
        Response::json(['success' => true, 'books' => $books]);
    }
}