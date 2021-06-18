<?php namespace Sonic\Response;
/**
 *             Sonic Web Framework
 * @license Apache License 2.0
 * @link    https://github.com/iamdual/sonic
 * @author  Ekin Karadeniz (iamdual@icloud.com)
 */

use Sonic\Response;
use Sonic\Singleton;

final class View
{
    use Singleton;

    public function render(string $path, array $params = [], string $layout = 'default'): void
    {
        extract($params, flags: EXTR_SKIP);
        $view = APP . "/View/{$path}.php";
        require APP . "/View/_layouts/{$layout}.php";
    }

    public function single(string $path, array $params = []): void
    {
        extract($params, flags: EXTR_SKIP);
        require APP . "/View/{$path}.php";
    }

    public function error(int $code, array $params = []): void
    {
        Response::statusCode($code);
        $this->single("_errors/{$code}", $params);
    }

    public function notFound(array $params = []) {
        $this->error(404, $params);
    }
}