<?php

function config(string $expression, ?string $default = null): mixed
{
    $parts = explode('.', $expression, 2);
    if (isset($parts[1])) {
        $namespace = $parts[0];
        $key = $parts[1];
    } else {
        $namespace = 'app';
        $key = $parts[0];
    }
    return \Sonic\Config\Manager::getInstance($namespace)->get($key, $default);
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