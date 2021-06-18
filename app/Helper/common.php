<?php

function config(string $key, ?string $default = null): mixed
{
    return \Sonic\Config\Application::getInstance()->get($key, $default);
}

function env(string $key, ?string $default = null): ?string
{
    return \Sonic\Config\Environment::getInstance()->get($key, $default);
}

function get(string $key, ?string $default = null): ?string
{
    return $_GET[$key] ?? $default;
}

function post(string $key, ?string $default = null): ?string
{
    return $_POST[$key] ?? $default;
}

function __(string $key, string $domain) {
    return \dgettext($domain, $key);
}