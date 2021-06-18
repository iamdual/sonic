<?php declare(strict_types=1);

namespace Sonic\Tests\Core\TestApp\Controller;

use Sonic\Controller;
use Sonic\Response;

class RestfulBook extends Controller
{
    public function book_list()
    {
        $books = [
            ['title' => 'Don Quixote', 'author' => 'Miguel de Cervantes'],
            ['title' => 'Moby Dick', 'author' => 'Herman Melville']
        ];

        Response::statusCode(200);
        Response::json(['success' => true, 'books' => $books]);
    }

    public function book_insert()
    {
        Response::statusCode(200);
        Response::json(['success' => true, 'message' => 'Book has been added!']);
    }

    public function book_details($id)
    {
        $details = [
            'id' => (int)$id,
            'title' => 'Don Quixote',
            'author' => 'Miguel de Cervantes'
        ];

        Response::statusCode(200);
        Response::json(['success' => true, 'details' => $details]);
    }
}