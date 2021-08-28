<?php

namespace Beyond\SmartHttp\Kernel;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

abstract class BaseProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
    }
}