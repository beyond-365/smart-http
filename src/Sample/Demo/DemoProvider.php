<?php


namespace Beyond\SmartHttp\Sample\Demo;


use Beyond\SmartHttp\Sample\Middleware\AfterMiddle;
use Beyond\SmartHttp\Sample\Middleware\DemoMiddleware;
use Beyond\SmartHttp\Sample\Middleware\SignMiddleware;
use Pimple\Container;
use Beyond\SmartHttp\Kernel\BaseProvider;

class DemoProvider extends BaseProvider
{

//    public function register(Container $pimple)
//    {
//        parent::register($pimple);
//
//        $this->initMiddle();
//
////        $pimple['demo_module'] = 'demo';
//
//        // 中间件
//        $pimple['serial_middleware'] = function ($app) {
//            return new SerialIdMiddle($app);
//        };
//
//        $pimple['demo'] = function ($app) {
//            return new Demo($app);
//        };
//    }

//    function modules()
//    {
//        return [
//            'demo' => 'Beyond\SmartHttp\Test\Demo',
//        ];
//    }

    function registerList()
    {
        return [
            'demo' => function ($app) {
                return new Demo($app);
            },
            DemoMiddleware::getAccessName() => function ($app) {
                return new DemoMiddleware($app);
            },
            SignMiddleware::getAccessName() => function ($app) {
                return new SignMiddleware($app);
            },
            AfterMiddle::getAccessName() => function ($app) {
                return new AfterMiddle($app);
            },
        ];
    }
}