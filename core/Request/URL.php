<?php namespace Sonic\Request;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Config\Config;
use Sonic\Singleton;

final class URL
{
    use Singleton;

    private string $uri;
    private string $path;
    private array $segments;
    private ?string $languageCode;
    private string $languagePrefix;

    public const PATH_SEPARATOR = '/';
    public const QUERY_STRING_SEPARATOR = '?';

    private function __construct()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_uri = $_SERVER['REQUEST_URI'];
        } elseif (isset($_GET['_path'])) {
            $request_uri = $_GET['_path'];
        } else {
            $request_uri = self::PATH_SEPARATOR;
        }

        $path_side = explode(self::QUERY_STRING_SEPARATOR, $request_uri, 2)[0];
        $segments = explode(self::PATH_SEPARATOR, $path_side);
        array_shift($segments); // explode('/', '/') == ['', '']

        $i18n_config = Config::getInstance('i18n');
        $this->languageCode = $i18n_config->get('default');
        if ($i18n_config->get('enabled', false)) {
            if (in_array($segments[0], $i18n_config->get('languages', []), true)) {
                $this->languageCode = $segments[0];
                $this->languagePrefix = $segments[0] . self::PATH_SEPARATOR;
                array_shift($segments);
            }
        }

        $path = '';
        foreach ($segments as $i => $segment) {
            $path .= self::PATH_SEPARATOR . $segment;
        }

        $this->uri = $request_uri;
        $this->path = $path;
        $this->segments = $segments;
    }

    /**
     * @return string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * @param int $index Segment index
     * @return string|null Returns the segment of the path
     */
    public function segment(int $index): ?string
    {
        return $this->segments[$index] ?? null;
    }

    /**
     * @return array Returns segments of the path
     */
    public function segments(): array
    {
        return $this->segments;
    }

    /**
     * @return string|null Returns chosen language from URL
     */
    public function languageCode(): ?string
    {
        return $this->languageCode ?? null;
    }

    /**
     * @return string Returns language prefix
     */
    public function languagePrefix(): string
    {
        return $this->languagePrefix ?? '';
    }
}