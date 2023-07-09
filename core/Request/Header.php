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

    /**
     * @param $key string Header key
     * @param string|null $default Default return value
     * @return string|null
     */
    public function get(string $key, ?string $default = null): ?string
    {
        return $this->headers[strtolower($key)] ?? $default;
    }

    /**
     * @return array Returns headers in key/value pair
     */
    public function getAll(): array
    {
        return $this->headers;
    }

    /**
     * @return string|null Returns "Content-Type" header, if exists
     */
    public function contentType(): ?string
    {
        return $this->headers['content-type'] ?? null;
    }

    /**
     * @return string|null Returns "User-Agent" header, if exists
     */
    public function userAgent(): ?string
    {
        return $this->headers['user-agent'] ?? null;
    }

    /**
     * @return string|null Returns "Accept" header, if exists
     */
    public function accept(): ?string
    {
        return $this->headers['accept'] ?? null;
    }

    /**
     * @return bool Check is client accepts JSON
     */
    public function acceptsJson(): bool
    {
        if ($accept = $this->accept()) {
            return str_starts_with(strtolower($accept), 'application/json');
        }
        return false;
    }

    /**
     * @return string|null Returns "Accept-Language" header, if exists
     */
    public function acceptLanguage(): ?string
    {
        return $this->headers['accept-language'] ?? null;
    }

    /**
     * @return string|null Returns "Accept-Encoding" header, if exists
     */
    public function acceptEncoding(): ?string
    {
        return $this->headers['accept-encoding'] ?? null;
    }

    /**
     * @return string|null Returns "Host" header, if exists
     */
    public function httpHost(): ?string
    {
        return $this->headers['host'] ?? null;
    }

    /**
     * @return string|null Returns "Host" header, but only its hostname
     */
    public function domain(): ?string
    {
        if (!$http_host = $this->httpHost()) {
            return null;
        }
        return parse_url($http_host, PHP_URL_HOST);
    }

    /**
     * @param bool $use_headers Find the client IP address by looking at the header, enabled by default
     * @return string|null
     */
    public function clientIp(bool $use_headers = true): ?string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;

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