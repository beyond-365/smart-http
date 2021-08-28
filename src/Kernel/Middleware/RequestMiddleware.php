<?php


namespace Beyond\SmartHttp\Kernel\Middleware;


use GuzzleHttp\Psr7\Query;
use Psr\Http\Message\RequestInterface;
use Beyond\SmartHttp\Kernel\Contracts\MiddlewareInterface;

abstract class RequestMiddleware implements MiddlewareInterface
{
    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function before(RequestInterface $request)
    {
       return $request->withHeader($this->getHeaderKey(), $this->getHeaderValue($request));
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

        return array_merge($query, $params);
    }
}
