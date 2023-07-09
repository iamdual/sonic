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

    /**
     * Renders a view together with layout
     * @param string $path View path
     * @param array $params View parameters in key/value pair
     * @param string $layout Layout path
     * @return void
     */
    public function render(string $path, array $params = [], string $layout = 'default'): void
    {
        extract($params, flags: EXTR_SKIP);
        $view = APP . "/View/$path.php";
        require APP . "/View/_layouts/$layout.php";
    }

    /**
     * Renders a view
     * @param string $path View path
     * @param array $params View parameters in key/value pair
     * @return void
     */
    public function single(string $path, array $params = []): void
    {
        extract($params, flags: EXTR_SKIP);
        require APP . "/View/$path.php";
    }

    /**
     * Renders an error by its code
     * @param int|string $code Error code
     * @param array $params
     * @return void
     */
    public function error(int|string $code, array $params = []): void
    {
        if (is_int($code)) {
            Response::statusCode($code);
        }
        $this->single("_errors/$code", $params);
    }

    /**
     * Renders not found error
     * @param array $params
     * @return void
     */
    public function notFound(array $params = []): void
    {
        $this->error(404, $params);
    }
}