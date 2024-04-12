<?php

function config(string $expression, ?string $default = null): mixed
{
    [$namespace, $key] = \Sonic\Config\Config::parseExpression($expression);
    return \Sonic\Config\Config::getInstance($namespace)->get($key, $default);
}

function env(string $key, ?string $default = null): ?string
{
    return \Sonic\Config\Environment::getInstance()->get($key, $default);
}

function get(string $key, mixed $default = null, bool $string_only = false): mixed
{
    return \Sonic\Request::get($key, $default, $string_only);
}

function post(string $key, mixed $default = null, bool $string_only = false): mixed
{
    return \Sonic\Request::post($key, $default, $string_only);
}

function __(string $key, string $domain): string
{
    return \dgettext($domain, $key);
}

function db($conf_id = 'default'): PDO
{
    return \Sonic\Connection\DB::getInstance($conf_id);
}
