<?php namespace Sonic;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */
final class Response
{
    public static function view(): Response\View
    {
        return Response\View::getInstance();
    }

    /**
     * @param $obj
     * @return void Set response header and print JSON
     */
    public static function json($obj): void
    {
        self::header('Content-Type', 'application/json');
        echo json_encode($obj);
    }

    /**
     * @param int $code Status code
     * @return void
     */
    public static function statusCode(int $code): void
    {
        http_response_code($code);
    }

    /**
     * @param string $key Header key
     * @param string $value Header value
     * @return void
     */
    public static function header(string $key, string $value): void
    {
        \header($key . ': ' . $value, replace: true);
    }

    /**
     * @param array $pairs Key/value pairs
     * @return void
     */
    public static function headers(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            self::header($key, $value);
        }
    }

    /**
     * @param string $path Redirect path
     * @param bool $permanent Permanent redirect
     * @return void
     */
    public static function redirect(string $path, bool $permanent = false): void
    {
        $status_code = $permanent ? 301 : 302;
        self::header('Location', $path);
        self::statusCode($status_code);
    }
}