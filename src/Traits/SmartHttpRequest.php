<?php


namespace Beyond\SmartHttp\Traits;


use Psr\Http\Message\ResponseInterface;
use Beyond\Supports\Traits\HasHttpRequest;

trait SmartHttpRequest
{

    use HasHttpRequest {
        request as supportRequest;
    }

    /**
     * 发送请求
     *
     * @param string $url
     * @param string $method
     * @param array $option
     * @return ResponseInterface
     */
    public function request($url, $method = 'POST', $option = [])
    {
        $method = strtoupper($method);
        $this->setOptions($option);

        $response = $this->supportRequest($method, $url, $this->getHttpOptions());

        $response->getBody()->rewind();
        return $response;
    }

    /**
     * @param array $defaultOptions
     */
    public function setOptions(array $defaultOptions)
    {
        if ($this->getBaseUri()) {
            $options['base_uri'] = $this->getBaseUri();
        }

        $options = array_merge(
            $defaultOptions,
            ['handler' => $this->getHandlerStack()],
            ['debug' => boolval($this->app->offsetGet('config')['http.debug'])]
        );

        $this->setHttpOptions($options);
    }
}
