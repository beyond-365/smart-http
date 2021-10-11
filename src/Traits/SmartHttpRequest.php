<?php


namespace Beyond\SmartHttp\Traits;


use GuzzleHttp\TransferStats;
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
        if (property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        $options = array_merge(
//            static::$default, //TODO
            $defaultOptions,
            ['handler' => $this->getHandlerStack()],
            ['debug' => boolval($this->app->offsetGet('config')['http.debug'])]
//            ['on_stats' => function (TransferStats $stats) {
//                echo $stats->getEffectiveUri() . "\n";
//                echo $stats->getTransferTime() . "\n";
//                var_dump($stats->getHandlerStats());
//
//                // You must check if a response was received before using the
//                // response object.
//                if ($stats->hasResponse()) {
//                    echo $stats->getResponse()->getStatusCode();
//                } else {
//                    // Error data is handler specific. You will need to know what
//                    // type of error data your handler uses before using this
//                    // value.
//                    var_dump($stats->getHandlerErrorData());
//                }
//            }]

        );

        $this->setHttpOptions($options);
    }
}
