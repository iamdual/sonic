<?php namespace Sonic\Request;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Config\Manager;
use Sonic\Singleton;

final class URL
{
    use Singleton;

    private string $uri;
    private string $path;
    private array $segments;
    private string $languageCode;

    private const SEPARATOR = '/';
    private const QUESTION_MARK = '?';

    private function __construct()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_uri = $_SERVER['REQUEST_URI'];
        } elseif (isset($_GET['_path'])) {
            $request_uri = $_GET['_path'];
        } else {
            $request_uri = self::SEPARATOR;
        }

        $path_side = explode(self::QUESTION_MARK, $request_uri, 2)[0];
        $segments = explode(self::SEPARATOR, $path_side);
        array_shift($segments); // explode('/', '/') == ['', '']

        $i18n_config = Manager::getInstance('i18n');
        if ($i18n_config->get('enabled', false)) {
            $this->languageCode = $i18n_config->get('default');
            if (in_array($segments[0], $i18n_config->get('languages', []), true)) {
                $this->languageCode = $segments[0];
                array_shift($segments);
            }
        }

        $path = '';
        foreach ($segments as $i => $segment) {
            $path .= self::SEPARATOR . $segment;
        }
        if (empty($path)) {
            $path = self::SEPARATOR;
        }

        $this->uri = $request_uri;
        $this->path = $path;
        $this->segments = $segments;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function segment(int $index): ?string
    {
        return $this->segments[$index] ?? null;
    }

    public function segments(): array
    {
        return $this->segments;
    }

    public function languageCode(): ?string
    {
        return $this->languageCode ?? null;
    }
}