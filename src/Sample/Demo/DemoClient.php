<?php


namespace Beyond\SmartHttp\Sample\Demo;


use Beyond\SmartHttp\Kernel\BaseClient;
use Beyond\SmartHttp\Kernel\Exceptions\InvalidArgumentException;
use Beyond\SmartHttp\Kernel\ServiceContainer;
use Beyond\SmartHttp\Sample\Middleware\AfterMiddle;
use Beyond\SmartHttp\Sample\Middleware\DemoMiddleware;
use Beyond\SmartHttp\Sample\Middleware\SignMiddleware;

abstract class DemoClient extends BaseClient
{
    /**
     * DemoClient constructor.
     * @param ServiceContainer $app
     * @throws InvalidArgumentException
     */
    public function __construct(ServiceContainer $app)
    {
        parent::__construct($app);

        $this->setBaseUri($app->offsetGet('config')->get('host', ''));
    }

    public function registerMiddleware()
    {
        parent::registerMiddleware();

        $this->pushMiddlewareByKey(SignMiddleware::getAccessName());
        $this->pushMiddlewareByKey(DemoMiddleware::getAccessName());
        $this->pushMiddlewareByKey(AfterMiddle::getAccessName());

    }
}