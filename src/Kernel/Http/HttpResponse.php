<?php

namespace Beyond\SmartHttp\Kernel\Http;

use Beyond\Supports\Collection;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpResponse
 * @package SmartHttp\Kernel\Http
 */
class HttpResponse extends Response
{
    /**
     * 将 ResponseInterface 类型实例 new 自身，这样就可使用父类（\GuzzleHttp\Psr7\Response）的方法
     *
     * @param ResponseInterface $response
     *
     * @return static
     */
    public static function wrapResponse(ResponseInterface $response)
    {
        return new static(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getReasonPhrase()
        );
    }

    /**
     * 将响应正文转化为JSON
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * 将响应正文转化为数组
     *
     * @return array
     */
    public function toArray()
    {
        $array = json_decode($this->getBodyContents(), true);

        if (JSON_ERROR_NONE === json_last_error()) {
            return (array)$array;
        }

        return [];
    }

    /**
     * 将响应正文转化为集合
     *
     * @return Collection
     */
    public function toCollection()
    {
        return new Collection($this->toArray());
    }

    /**
     * 将响应正文转化为对象
     *
     * @return object
     */
    public function toObject()
    {
        return (object)json_decode($this->getBodyContents());
    }

    /**
     * 获取响应的正文内容
     *
     * @return string
     */
    public function getBodyContents()
    {
        $this->getBody()->rewind();
        $contents = $this->getBody()->getContents();
        $this->getBody()->rewind();

        return $contents;
    }

    /**
     * 直接输出响应正文
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getBodyContents();
    }
}
