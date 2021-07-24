<?php

function config(string $key, ?string $default = null): mixed
{
    return \Sonic\Config\Application::getInstance()->get($key, $default);
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

function __(string $key, string $domain)
{
    return \dgettext($domain, $key);
}