<?php namespace Sonic\Request;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Singleton;

final class Header
{
    use Singleton;

    private array $headers = [];

    private function __construct()
    {
        if (is_callable('getallheaders')) {
            foreach (getallheaders() as $key => $value) {
                $this->headers[strtolower($key)] = $value;
            }
        } else {
            foreach ($_SERVER as $key => $value) {
                if (str_starts_with($key, 'HTTP_')) {
                    $key = str_replace('_', '-', substr($key, 5));
                    $this->headers[strtolower($key)] = $value;
                }
            }
        }
    }

    public function get($key): ?string
    {
        return $this->headers[strtolower($key)] ?? null;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getContentType(): ?string
    {
        return $this->headers['content-type'] ?? null;
    }

    public function getUserAgent(): ?string
    {
        return $this->headers['user-agent'] ?? null;
    }

    public function getAccept(): ?string
    {
        return $this->headers['accept'] ?? null;
    }

    public function getAcceptLanguage(): ?string
    {
        return $this->headers['accept-language'] ?? null;
    }

    public function getAcceptEncoding(): ?string
    {
        return $this->headers['accept-encoding'] ?? null;
    }

    public function hasNoCache(): bool
    {
        return $this->get('cache-control') === 'no-cache';
    }

    public function isJsonRequest(): bool
    {
        return str_starts_with(strtolower($this->getContentType()), 'application/json');
    }

    public function isXmlHttpRequest(): bool
    {
        return $this->get('x-requested-with') === 'XMLHttpRequest';
    }

    public function acceptsJson(): bool
    {
        return str_starts_with(strtolower($this->getAccept()), 'application/json');
    }

    public function getClientIp(bool $use_headers = true): string
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        if ($use_headers) {
            $headers = [
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED'];
            foreach ($headers as $header) {
                if (isset($_SERVER[$header])) {
                    $ip = $_SERVER[$header];
                    break;
                }
            }
        }

        return $ip;
    }
}