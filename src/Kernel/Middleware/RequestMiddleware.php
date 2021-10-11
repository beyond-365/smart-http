<?php


namespace Beyond\SmartHttp\Kernel\Middleware;


use Psr\Http\Message\RequestInterface;

abstract class RequestMiddleware extends BaseMiddleware
{
    /**
     * @param callable $handler
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $request = $this->handle($request);
            return $handler($request, $options);
        };
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function handle(RequestInterface $request)
    {
       return $request->withHeader($this->getHeaderKey(), $this->getHeaderValue($request));
    }

}
