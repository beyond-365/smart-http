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
    public function getAccessName();

    /**
     * 中间件具体的实现
     *
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function before(RequestInterface $request);

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
