<?php namespace App\Controller\Book;

use Sonic\Response;

class Actions extends \Sonic\Controller
{
    public function add()
    {
        Response::statusCode(200);
        Response::json(['success' => true, 'message' => 'Book added!']);
    }

    public function delete()
    {
        Response::statusCode(200);
        Response::json(['success' => true, 'message' => 'Book deleted!']);
    }
}