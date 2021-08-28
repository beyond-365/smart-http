<?php


namespace Beyond\SmartHttp;


use Psr\Log\LoggerInterface;
use Beyond\SmartHttp\Kernel\ServiceContainer;
use Beyond\SmartHttp\Test\Demo\DemoProvider;

class Application extends ServiceContainer
{
    /**
     * @var string[]
     */
    protected $defaultProviders = [
        DemoProvider::class,
    ];

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
