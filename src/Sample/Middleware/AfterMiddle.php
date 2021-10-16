<?php


namespace Beyond\SmartHttp\Sample\Middleware;


use Beyond\SmartHttp\Kernel\Middleware\ResponseMiddleware;
use Psr\Http\Message\RequestInterface;

/**
 * 响应中间件
 *
 * Class AfterMiddle
 * @package Beyond\SmartHttp\Sample\Middleware
 */
class AfterMiddle extends ResponseMiddleware
{

    /**
     * @inheritDoc
     */
    public static function getAccessName()
    {
        return 'after_middleware';
    }

    /**
     * @inheritDoc
     */
    public function getHeaderKey()
    {
        return 'After';
    }

    /**
     * @inheritDoc
     */
    public function getHeaderValue(RequestInterface $request)
    {
        return 'after';
    }
}