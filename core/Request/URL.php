<?php namespace Sonic\Request;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Config\Application;
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
        $config = Application::getInstance();

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

        if ($config->get('i18n.enabled', false)) {
            $this->languageCode = $config->get('i18n.default');
            if (in_array($segments[0], $config->get('i18n.languages', []), true)) {
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

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getSegment(int $index): ?string
    {
        return $this->segments[$index] ?? null;
    }

    public function getSegments(): array
    {
        return $this->segments;
    }

    public function getLanguageCode(): ?string
    {
        return $this->languageCode ?? null;
    }
}