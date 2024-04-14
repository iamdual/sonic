<?php namespace Sonic;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use App\Controller\Errors;
use Sonic\Config\Config;
use Sonic\Routing\RouteMatcher;

final class Sonic
{
    private function callHandler(array $handler, array $params = []): mixed
    {
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }

        [$class, $method] = $handler;
        return call_user_func_array([new $class, $method], $params);
    }

    private function triggerError(string $error_type): void
    {
        call_user_func([new Errors(), $error_type]);
    }

    private function initSessionAndLocale(): void
    {
        $session_config = Config::getInstance('session');
        $app_config = Config::getInstance('app');
        $i18n_config = Config::getInstance('i18n');

        // Start session if enabled
        if ($session_config->get('enabled', false)) {
            session_start($session_config->get('options', []));
        }

        // Set timezone if set
        if ($app_config->get('timezone')) {
            date_default_timezone_set($app_config->get('timezone'));
        }

        // Set i18n via gettext if enabled
        if ($i18n_config->get('enabled', false)) {
            $i18n_lang = Request::url()->languageCode();
            $i18n_charset = $i18n_config->get('charset', 'UTF-8');
            $i18n_locales = $i18n_config->get('locales', []);
            $i18n_locale = $i18n_locales[$i18n_lang] ?? $i18n_lang;
            putenv('LANGUAGE=' . $i18n_locale);
            putenv('LC_ALL=' . $i18n_locale);
            setlocale(LC_ALL, $i18n_locale);
            $i18n_domains = $i18n_config->get('domains', []);
            if (!empty($i18n_domains[0])) {
                \textdomain($i18n_domains[0]);
                foreach ($i18n_domains as $i18n_domain) {
                    \bindtextdomain($i18n_domain, APP . '/Locale');
                    \bind_textdomain_codeset($i18n_domain, $i18n_charset);
                }
            }
        }
    }

    public function response(): void
    {
        $this->initSessionAndLocale();

        // Call events for initialization
        Event::call('core.init');

        $autoload = require APP . '/Config/autoload.php';
        if (!empty($autoload['helper'])) {
            foreach ($autoload['helper'] as $helper) {
                require APP . '/Helper/' . $helper . '.php';
            }
        }
        if (!empty($autoload['extension'])) {
            foreach ($autoload['extension'] as $extClass) {
                $this->callHandler([$extClass, 'init']);
            }
        }
        if (!empty($autoload['middleware'])) {
            foreach ($autoload['middleware'] as $mwClass) {
                if ($this->callHandler([$mwClass, 'handler']) !== true) {
                    Event::call('core.finish');
                    return;
                }
            }
        }

        // Get routes and try to match
        $routes = require APP . '/Config/routes.php';
        $routeMatcher = new RouteMatcher(Request::url()->path(), Request::method());
        $matched = $routeMatcher->getMatched($routes);
        if ($matched === null) {
            $this->triggerError('notFound');
            Event::call('core.finish');
            return;
        }
        $route = $matched->getRoute();
        $middleware = $route->getMiddleware();
        if (!empty($middleware)) {
            foreach ($middleware as $mwClass) {
                if ($this->callHandler([$mwClass, 'handler']) !== true) {
                    Event::call('core.finish');
                    return;
                }
            }
        }

        // Call the requested handler
        $this->callHandler($route->getHandler(), $matched->getParams());

        // Call events for the end of the request
        Event::call('core.finish');
    }

    public function console(): void
    {
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