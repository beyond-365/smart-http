<?php


namespace Beyond\SmartHttp\Sample\Middleware;


use Beyond\SmartHttp\Kernel\Middleware\ResponseMiddleware;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * 响应中间件
 *
 * Class AfterMiddle
 * @package Beyond\SmartHttp\Sample\Middleware
 */
class AfterMiddle extends ResponseMiddleware
{

    /**
     * @inheritDoc
     */
    public static function getAccessName()
    {
        return 'after_middleware';
    }

    /**
     * @inheritDoc
     */
    public function getHeaderKey()
    {
        return 'After';
    }

    /**
     * @inheritDoc
     */
    public function getHeaderValue(RequestInterface $request)
    {
        return 'after';
    }
    
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, ResponseInterface $response)
    {
        $this->setAppendResponse(function (ResponseInterface $response) {
            $content = json_decode($response->getBody()->__toString(), true);
            return $response->withBody(Utils::streamFor(json_encode($content['data'] ?? [], JSON_UNESCAPED_UNICODE)));
        });

        return parent::handle($request, $response);
    }

}