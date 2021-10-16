<?php


namespace Beyond\SmartHttp\Sample\Middleware;


use Beyond\SmartHttp\Kernel\Middleware\RequestMiddleware;
use Psr\Http\Message\RequestInterface;

class DemoMiddleware extends RequestMiddleware
{

    /**
     * @inheritDoc
     */
    public static function getAccessName()
    {
        return 'demo_middleware';
    }

    /**
     * @inheritDoc
     */
    public function getHeaderKey()
    {
        return 'demo-key';
    }

    /**
     * @inheritDoc
     */
    public function getHeaderValue(RequestInterface $request)
    {
        return 'demo-header-value';
    }
}