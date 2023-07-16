<?php namespace Sonic;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Request\Method;

final class Request
{
    /**
     * @return Request\Header
     */
    public static function header(): Request\Header
    {
        return Request\Header::getInstance();
    }

    /**
     * @return Request\URL
     */
    public static function url(): Request\URL
    {
        return Request\URL::getInstance();
    }

    /**
     * @return Request\Session
     */
    public static function session(): Request\Session
    {
        return Request\Session::getInstance();
    }

    /**
     * @return Request\Cookie
     */
    public static function cookie(): Request\Cookie
    {
        return Request\Cookie::getInstance();
    }

    /**
     * @param string $key Key for query parameter
     * @param mixed|null $default Default return value
     * @param bool $string_only Prevent to return as an array, disabled by default
     * @return mixed
     */
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

    /**
     * @param string $key Key for form body
     * @param mixed|null $default Default return value
     * @param bool $string_only Prevent to return as an array, disabled by default
     * @return mixed
     */
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

    /**
     * @param string $key Key for server variables
     * @param string|null $default
     * @return mixed
     */
    public static function server(string $key, ?string $default = null): mixed
    {
        return $_SERVER[$key] ?? $default;
    }

    /**
     * @return string|null Returns raw requested body
     */
    public static function rawData(): ?string
    {
        return file_get_contents('php://input') ?: null;
    }

    /**
     * @return mixed Returns requested JSON data
     */
    public static function json(): mixed
    {
        $decoded = json_decode(self::rawData());
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        return null;
    }

    /**
     * @return Method|null Returns request method enumeration
     */
    public static function method(): ?Method
    {
        return $_SERVER['REQUEST_METHOD'] ? Method::from(strtoupper($_SERVER['REQUEST_METHOD'])) : null;
    }
}