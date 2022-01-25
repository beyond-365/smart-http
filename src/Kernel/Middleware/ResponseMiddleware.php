<?php


namespace Beyond\SmartHttp\Kernel\Middleware;


use Beyond\Supports\Collection;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class ResponseMiddleware extends BaseMiddleware
{
    /**
     * @var Collection|array|null
     */
    private $appendResponse = null;

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
     * @param Collection|array $appendResponse
     * @return ResponseMiddleware
     */
    public function setAppendResponse($appendResponse):ResponseMiddleware
    {
        $this->appendResponse = $appendResponse;
        return $this;
    }

    /**
     * @return array|Collection|null
     */
    public function getAppendResponse()
    {
        return $this->appendResponse;
    }

    /**
     * 追加响应结果
     *
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function appendResponse(ResponseInterface $response)
    {
        $appendResponse = $this->getAppendResponse();

        if (!is_array($appendResponse) && !$appendResponse instanceof Collection) {
            return $response;
        }

        if ($appendResponse instanceof Collection) {
            $appendResponse = $appendResponse->toArray();
        }

        $result = json_decode($response->getBody()->__toString(), true);
        return $response->withBody(Utils::streamFor(json_encode(array_merge($result, $appendResponse))));
    }

}
