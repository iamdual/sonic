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

    public function getAll(): array
    {
        return $this->headers;
    }

    public function contentType(): ?string
    {
        return $this->headers['content-type'] ?? null;
    }

    public function userAgent(): ?string
    {
        return $this->headers['user-agent'] ?? null;
    }

    public function accept(): ?string
    {
        return $this->headers['accept'] ?? null;
    }

    public function acceptLanguage(): ?string
    {
        return $this->headers['accept-language'] ?? null;
    }

    public function acceptEncoding(): ?string
    {
        return $this->headers['accept-encoding'] ?? null;
    }

    public function clientIp(bool $use_headers = true): string
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        if ($use_headers) {
            $headers = [
                'HTTP_CF_CONNECTING_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_REAL_IP',
            ];
            foreach ($headers as $header) {
                if (isset($_SERVER[$header])) {
                    // Some header values coming as comma separated
                    $first_ip = explode(',', $_SERVER[$header], 2)[0];
                    if (filter_var($first_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        $ip = $first_ip;
                    }
                    break;
                }
            }
        }

        return $ip;
    }
}