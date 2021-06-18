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

    public static function json($obj): void
    {
        self::header('Content-Type', 'application/json');
        echo json_encode($obj);
    }

    public static function statusCode(int $code): void
    {
        http_response_code($code);
    }

    public static function header(string $key, string $value): void
    {
        \header($key . ': ' . $value, replace: true);
    }

    public static function headers(array $peers): void
    {
        foreach ($peers as $key => $value) {
            self::header($key, $value);
        }
    }

    public static function redirect(string $path, bool $permanent = false): void
    {
        $status_code = $permanent ? 301 : 302;
        self::header('Location', $path);
        self::statusCode($status_code);
    }
}