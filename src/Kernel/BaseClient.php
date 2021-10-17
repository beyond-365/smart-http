<?php

namespace Beyond\SmartHttp\Kernel;

use Beyond\SmartHttp\Kernel\Http\HttpRequest;


abstract class BaseClient extends HttpRequest
{
    /**
     * 注册中间件
     */
    public function registerMiddleware()
    {
        parent::registerMiddleware();
    }

    /**
     * @param callable $name
     * @return HttpRequest|void
     */
    public function pushMiddlewareByKey($name)
    {
        parent::pushMiddleware($this->app[$name], $name);
    }

}
