<?php

namespace Beyond\SmartHttp\Kernel;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

abstract class BaseProvider implements ServiceProviderInterface
{
    /**
     * @return mixed
     */
    abstract function registerList();

    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        foreach ($this->registerList() as $name => $value) {
            $pimple[$name] = $value;
        }
    }

}