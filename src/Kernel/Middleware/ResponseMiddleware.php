<?php


namespace Beyond\SmartHttp\Kernel\Middleware;


use Beyond\Supports\Collection;
use GuzzleHttp\Psr7\AppendStream;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class ResponseMiddleware extends BaseMiddleware
{
    /**
     * @var Collection|array|callable|null
     */
    private $appendResponseData = null;

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, ResponseInterface $response)
    {
        return $this->appendResponse($response)->withHeader($this->getHeaderKey(), $this->getHeaderValue($request));
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

    /**
     * @param Collection|array|callable|null $appendResponse
     * @return ResponseMiddleware
     */
    public function setAppendResponse($appendResponse):ResponseMiddleware
    {
        $this->appendResponseData = $appendResponse;
        return $this;
    }

    /**
     * @return array|Collection|\Closure|null
     */
    public function getAppendResponse()
    {
        return $this->appendResponseData;
    }

    /**
     * 追加响应结果
     *
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function appendResponse(ResponseInterface $response)
    {
        $appendResponseData = $this->getAppendResponse();
        if ($appendResponseData instanceof \Closure) {
            $response = $appendResponseData($response);
        } else {
            if (!is_array($appendResponseData) && !$appendResponseData instanceof Collection) {
                return $response;
            }

            if ($appendResponseData instanceof Collection) {
                $appendResponseData = $appendResponseData->toArray();
            }

            $composed = new AppendStream();
            $stream = Utils::streamFor(json_encode(array_merge(json_decode($response->getBody()->__toString(), true), $appendResponseData), JSON_UNESCAPED_UNICODE));
            $composed->addStream($stream);
            $response = $response->withBody($composed);
        }

        return $response;
    }

}
