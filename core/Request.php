<?php namespace Sonic;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */
final class Request
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const PATCH = 'PATCH';
    public const DELETE = 'DELETE';
    public const HEAD = 'HEAD';
    public const OPTIONS = 'OPTIONS';

    public static function header(): Request\Header
    {
        return Request\Header::getInstance();
    }

    public static function url(): Request\URL
    {
        return Request\URL::getInstance();
    }

    public static function session(): Request\Session
    {
        return Request\Session::getInstance();
    }

    public static function cookie(): Request\Cookie
    {
        return Request\Cookie::getInstance();
    }

    public static function get(string $key, mixed $default = null, bool $string_only = false): mixed
    {
        if (isset($_GET[$key])) {
            if ($string_only && !is_string($_GET[$key])) {
                return null;
            }
            return $_GET[$key];
        }
        return $default;
    }

    public static function post(string $key, mixed $default = null, bool $string_only = false): mixed
    {
        if (isset($_POST[$key])) {
            if ($string_only && !is_string($_POST[$key])) {
                return null;
            }
            return $_POST[$key];
        }
        return $default;
    }

    public static function server(string $key, ?string $default = null): mixed
    {
        return $_SERVER[$key] ?? $default;
    }

    public static function rawData(): ?string
    {
        return file_get_contents('php://input') ?: null;
    }

    public static function json(): mixed
    {
        $decoded = json_decode(self::rawData());
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        return null;
    }

    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function isMethod(string $method): bool
    {
        return $_SERVER['REQUEST_METHOD'] === $method;
    }
}