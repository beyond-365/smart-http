<?php
namespace Beyond\SmartHttp\Kernel\Http;


use Beyond\Supports\Config;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;
use Beyond\SmartHttp\Kernel\ServiceContainer;
use Beyond\SmartHttp\Traits\SmartHttpRequest;

class HttpRequest
{
    use SmartHttpRequest {
        request as performRequest;
    }

    /**
     * Pimple 容器
     *
     * @var ServiceContainer
     */
    protected $app;

    /**
     * option 选项中 baseUri,子类可覆盖
     *
     * @var string
     */
    private $baseUri = '';

    /**
     * @var Config
     */
    private $config;

    /**
     * HttpRequest constructor.
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
        $this->config = new Config($this->getDefaultConfig());
    }

    /**
     * 发送表单上传附件
     *
     * @param $url
     * @param array $files
     * @param array $form
     * @param array $query
     * @return ResponseInterface
     */
    public function upload($url, array $files = [], array $form = [], array $query = [])
    {
        $multipart = [];

        foreach ($files as $name => $file) {
            $multipart [] = [
                'name' => $name,
                'contents' => $file,
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', compact('multipart', 'query'));
    }

    /**
     * @param $url
     * @param string $method
     * @param array $options
     * @return ResponseInterface
     */
    public function request($url, $method = 'POST', array $options = [])
    {
        if (empty($this->getMiddleware())) {
            $this->registerMiddleware();
        }

        return $this->performRequest($url, $method, $options);
    }

    /**
     * 重写获取客户端，通过 Pimple 容器获取
     *
     * @return Client
     */
    public function getClient()
    {
        if (!($this->httpClient instanceof ClientInterface)) {
            $this->httpClient = $this->app->offsetExists('http_client') ? $this->app['http_client'] : new Client();
        }

        return $this->httpClient;
    }

    /**
     * @return array
     */
    public function getDefaultConfig()
    {
        return $this->app->getConfig();
    }

    /**
     * @param array $customerConfig
     * @return $this
     */
    public function setConfig(array $customerConfig = [])
    {
        $this->config = $this->config->merge($customerConfig);

        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 注册中间件
     */
    protected function registerMiddleware()
    {
        $this->registerDefaultMiddleware();
    }

    /**
     * 注册默认中间件
     */
    private function registerDefaultMiddleware()
    {
        if ($this->config->get('http.logging', false)) {
            $this->pushMiddleware($this->logMiddleware(), 'log');
        }
    }

    /**
     * 日志中间件
     *
     * @return callable
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter(
            $this->app->offsetGet('config')->get('http.log_template', MessageFormatter::CLF)
        );

        return Middleware::log($this->app->getLog(), $formatter);
    }

}
