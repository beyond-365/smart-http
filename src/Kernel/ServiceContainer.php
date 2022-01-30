<?php


namespace Beyond\SmartHttp\Kernel;


use Beyond\Supports\Config;
use GuzzleHttp\Client;
use Pimple\Container;
use Psr\Log\LoggerInterface;
use Beyond\SmartHttp\Kernel\Providers\ConfigProvider;
use Beyond\SmartHttp\Kernel\Providers\HttpClientProvider;

/**
 * Class ServiceContainer
 * @package SmartHttp\Kernel
 *
 * @property Config $config
 * @property Client $httpClient
 *
 */
class ServiceContainer extends Container
{
    /**
     * 服务提供者
     *
     * @var array
     */
    protected $providers = [];

    /**
     * 默认配置
     *
     * @var array
     */
    protected $defaultConfig = [
        'http' => [
            'timeout'      => 3.0,
            'debug'        => true,
            'logging'      => true,
            'log_template' => '{"sdk_uri":"{url}","code":"{code}","request":"{req_body}","body":"{res_body}","error":"{error}"}'
        ],
    ];

    /**
     * 自定义配置
     *
     * @var array
     */
    protected $customerConfig = [];

    /**
     * @var LoggerInterface|null
     */
    protected $log = null;

    /**
     * ServiceContainer constructor.
     * @param array $config
     * @param array $values
     */
    public function __construct(array $config, array $values = array())
    {
        $this->registerProviders();

        parent::__construct($values);

        $this->customerConfig = $config;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return array_replace_recursive($this->defaultConfig, $this->customerConfig);
    }

    /**
     * 服务提供者
     */
    private function registerProviders()
    {
        foreach ($this->getProviders() as $provider) {
            parent::register(new $provider);
        }
    }

    /**
     * @return array
     */
    private function getProviders()
    {
        return array_merge([
            ConfigProvider::class,
            HttpClientProvider::class
        ], $this->providers);
    }

    /**
     * @return LoggerInterface|null
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param LoggerInterface|null $log
     */
    public function setLog($log)
    {
        $this->log = $log;
    }
}
