<?php
namespace Beyond\SmartHttp\Kernel\Middleware;


use Beyond\SmartHttp\Kernel\Contracts\MiddlewareInterface;
use Beyond\SmartHttp\Kernel\ServiceContainer;
use GuzzleHttp\Psr7\Query;
use Psr\Http\Message\RequestInterface;

/**
 * Class BaseMiddleware
 * @package YardCenter\Kernel\Middleware
 */
abstract class BaseMiddleware implements MiddlewareInterface
{
    /**
     * @var ServiceContainer
     */
    protected $app;

    /**
     * BaseMiddleware constructor.
     *
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * 获取请求的所有参数
     *
     * @param RequestInterface $request
     * @return array
     * Date: 2020/3/17 Time: 下午2:38
     */
    protected function input(RequestInterface $request)
    {
        $params = [];
        $query = Query::parse($request->getUri()->getQuery());
        $body = $request->getBody()->getContents();
        $contentType = $request->getHeaderLine('Content-Type');

        if (strripos($contentType, 'application/json') !== false) {
            $params = json_decode($body, true);
        }

        if (strripos($contentType, 'application/x-www-form-urlencoded') !== false) {
            parse_str($body, $params);
        }

        // xml
        if (stripos($contentType, 'xml') !== false) {
            $params = json_decode(json_encode(simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE), true);
        }

        return array_merge($query, $params);
    }
}
