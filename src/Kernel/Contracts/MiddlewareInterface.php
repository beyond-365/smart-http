<?php

namespace Beyond\SmartHttp\Kernel\Contracts;

use Psr\Http\Message\RequestInterface;

/**
 * Interface MiddlewareInterface
 * @package SmartHttp\Kernel\Contracts
 */
interface MiddlewareInterface
{
    /**
     * 中间件名
     *
     * @return string
     */
    public static function getAccessName();

    /**
     * 键名
     *
     * @return string
     */
    public function getHeaderKey();

    /**
     * 键值
     *
     * @param RequestInterface $request
     * @return string
     */
    public function getHeaderValue(RequestInterface $request);
}
