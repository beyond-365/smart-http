<?php


namespace Beyond\SmartHttp\Sample\Middleware;


use Psr\Http\Message\RequestInterface;
use Beyond\SmartHttp\Kernel\Middleware\RequestMiddleware;

class SignMiddleware extends RequestMiddleware
{

    public static function getAccessName()
    {
        return 'sign_middleware';
    }

    public function getHeaderKey()
    {
        return 'sig';
    }

    public function getHeaderValue(RequestInterface $request)
    {
//        dd($this->input($request));
        return 'debug-getHeaderValue';
    }
}