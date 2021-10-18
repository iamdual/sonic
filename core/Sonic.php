<?php namespace Sonic;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use App\Controller\Errors;
use Sonic\Config\Application;
use Sonic\Routing\Route;
use Sonic\Routing\RouteMatcher;

final class Sonic
{
    private function callHandler(array $handler, array $params = []): void
    {
        list($class, $method) = $handler;
        call_user_func_array([new $class, $method], $params);
    }

    private function callMiddleware(string $mwClass): bool
    {
        return call_user_func([new $mwClass, 'handler']);
    }

    private function callExtension(string $extClass): void
    {
        call_user_func([new $extClass, 'init']);
    }

    private function triggerError(string $method): void
    {
        call_user_func([new Errors(), $method]);
    }

    private function initSessionAndLocale(): void
    {
        $config = Application::getInstance();

        // Start session if enabled
        if ($config->get('session.enabled', false)) {
            session_start($config->get('session.config', []));
        }

        // Set timezone if set
        if ($config->get('app.timezone')) {
            date_default_timezone_set($config->get('app.timezone'));
        }

        // Set i18n via gettext if enabled
        if ($config->get('i18n.enabled', false)) {
            $i18n_lang = Request::url()->languageCode() ?: $config->get('i18n.default');
            $i18n_charset = $config->get('i18n.charset', 'UTF-8');
            $i18n_locales = $config->get('i18n.locales', []);
            $i18n_locale = $i18n_locales[$i18n_lang] ?? $i18n_lang;
            putenv('LC_ALL=' . $i18n_locale);
            setlocale(LC_ALL, $i18n_locale);
            $i18n_domains = $config->get('i18n.domains', []);
            if (!empty($i18n_domains[0])) {
                \textdomain($i18n_domains[0]);
                foreach ($i18n_domains as $i => $i18n_domain) {
                    \bindtextdomain($i18n_domain, APP . '/Locale');
                    \bind_textdomain_codeset($i18n_domain, $i18n_charset);
                }
            }
        }
    }

    private function loadHelpers(): mixed
    {
        $autoload = require APP . '/Config/autoload.php';
        if (!empty($autoload['helper'])) {
            foreach ($autoload['helper'] as $helper) {
                require APP . '/Helper/' . $helper . '.php';
            }
        }
        return $autoload;
    }

    public function response(): void
    {
        $this->initSessionAndLocale();
        $autoload = $this->loadHelpers();

        if (!empty($autoload['extension'])) {
            foreach ($autoload['extension'] as $extClass) {
                $this->callExtension($extClass);
            }
        }
        if (!empty($autoload['middleware'])) {
            foreach ($autoload['middleware'] as $mwClass) {
                if ($this->callMiddleware($mwClass) !== true) {
                    return;
                }
            }
        }

        // Call events for initialization
        Event::call('core.init');

        // Get routes and try to match
        $routes = require APP . '/Config/routes.php';
        $routeMatcher = new RouteMatcher(Request::url()->path(), Request::method());
        $matched = $routeMatcher->getMatched($routes);
        if ($matched === null) {
            $this->triggerError('notFound');
            return;
        }

        /** @var Route $route */
        $route = $matched->getRoute();

        /** @var array $params */
        $params = $matched->getParams();

        /** @var ?array $middleware */
        $middleware = $route->getMiddleware();
        if (!empty($middleware)) {
            foreach ($middleware as $mwCallback) {
                if ($this->callMiddleware($mwCallback) !== true) {
                    return;
                }
            }
        }

        // Call the requested handler
        $this->callHandler($route->getHandler(), $params);

        // Call events for the end of the request
        Event::call('core.end');
    }

    public function console(): void
    {
        $this->loadHelpers();

        global $argv, $argc;
        $handlerClass = null;
        $handlerArgs = [];
        for ($i = 1; $i < $argc; $i++) {
            if ($i === 1) {
                $handlerClass = '\\App\\Console\\' . $argv[$i];
            } else {
                $handlerArgs[] = (string)$argv[$i];
            }
        }

        if (!$handlerClass) {
            exit('Please enter a handler.' . PHP_EOL);
        }
        if (!class_exists($handlerClass)) {
            exit('Handler not found: ' . $handlerClass . PHP_EOL);
        }

        $this->callHandler([$handlerClass, 'handler'], $handlerArgs);
    }
}