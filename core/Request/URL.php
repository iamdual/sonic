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
    private ?string $defaultLanguage;

    public const PATH_SEPARATOR = '/';
    public const QUERY_STRING_SEPARATOR = '?';

    private function __construct()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_uri = $_SERVER['REQUEST_URI'];
        } else {
            $request_uri = self::PATH_SEPARATOR;
        }

        $path_side = explode(self::QUERY_STRING_SEPARATOR, $request_uri, 2)[0];
        $raw_segments = explode(self::PATH_SEPARATOR, $path_side);
        $segments = [];

        foreach ($raw_segments as $segment) {
            if ($segment === '') {
                continue;
            }
            $segments[] = $segment;
        }

        $i18n_config = Config::getInstance('i18n');
        if ($i18n_config->get('enabled', false)) {
            $this->defaultLanguage = $i18n_config->get('default');
            $languages = $i18n_config->get('languages', []);
            if (isset($segments[0]) && in_array($segments[0], $languages, strict: true)) {
                $this->languageCode = $segments[0];
                array_shift($segments);
            } else {
                $this->languageCode = $this->defaultLanguage;
            }
        }

        $this->uri = $request_uri;
        $this->path = self::PATH_SEPARATOR . implode(self::PATH_SEPARATOR, $segments);
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
        if ($this->languageCode === $this->defaultLanguage) {
            return '';
        }
        return $this->languageCode ? $this->languageCode . self::PATH_SEPARATOR : '';
    }
}