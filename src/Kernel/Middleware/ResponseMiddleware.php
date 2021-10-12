<?php


namespace Beyond\SmartHttp\Kernel\Middleware;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class ResponseMiddleware extends BaseMiddleware
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, ResponseInterface $response)
    {
        return $response->withHeader($this->getHeaderKey(), $this->getHeaderValue($request));
    }

    /**
     * @param callable $handler
     * @return \Closure
     */
    function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $promise = $handler($request, $options);
            return $promise->then(function (ResponseInterface $response) use ($request) {
                return $this->handle($request, $response);
            });
        };
    }
}
