<?php

namespace Beyond\SmartHttp\Kernel\Providers;

use Beyond\Supports\Config;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Beyond\SmartHttp\Kernel\ServiceContainer;

/**
 * Class ConfigProvider
 * @package SmartHttp\Kernel\Providers
 */
class ConfigProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        /**
         * @param ServiceContainer $app
         * @return Config
         */
        $pimple['config'] = function ($app) {
            return new Config($app->getConfig());
        };
    }
}