<?php


namespace Beyond\SmartHttp;


use Psr\Log\LoggerInterface;
use Beyond\SmartHttp\Kernel\ServiceContainer;

class Application extends ServiceContainer
{
    protected $defaultProviders = [];

    private function initProvider()
    {
        $this->providers = $this->defaultProviders;
    }

    /**
     * Application constructor.
     * @param array $config
     * @param LoggerInterface|null $log
     * @param array $values
     */
    public function __construct(array $config, LoggerInterface $log = null, array $values = [])
    {
        $this->setLog($log);

        $this->initProvider();

        parent::__construct($config, $values);
    }

}
