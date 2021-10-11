<?php

namespace Beyond\SmartHttp\Kernel;

use Beyond\SmartHttp\Kernel\Exceptions\AuthorizationException;
use Beyond\SmartHttp\Kernel\Exceptions\BadRequestException;
use Beyond\SmartHttp\Kernel\Exceptions\ResourceNotFoundException;
use Beyond\SmartHttp\Kernel\Exceptions\ServiceInvalidException;
use Beyond\SmartHttp\Kernel\Exceptions\ValidationException;
use Beyond\SmartHttp\Kernel\Http\HttpRequest;
use Beyond\SmartHttp\Test\Middleware\SignMiddleware;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;


abstract class BaseClient extends HttpRequest
{
    /**
     * 注册中间件
     */
    public function registerMiddleware()
    {
        parent::registerMiddleware();

        $this->pushMiddlewareByKey(SignMiddleware::getAccessName());
    }

    /**
     * @param callable $name
     * @return HttpRequest|void
     */
    public function pushMiddlewareByKey($name)
    {
        parent::pushMiddleware($this->app[$name], $name);
    }

    /**
     * 处理请求异常 request
     *
     * @param $url
     * @param string $method
     * @param array $options
     * @return ResponseInterface
     * @throws AuthorizationException
     * @throws BadRequestException
     * @throws ResourceNotFoundException
     * @throws ServiceInvalidException
     * @throws ValidationException
     */
    public function request($url, $method = 'POST', array $options = [])
    {
        try {
            return parent::request($url, $method, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $contents = $response->getBody()->getContents();
            $content = json_decode($contents);

            $message = property_exists($content, 'message') ? $content->message : '';
            $code = property_exists($content, 'code') ? $content->code : 100000;

            switch ($statusCode) {
                case 404:
                    throw new ResourceNotFoundException($message, $code);
                case 400:
                case 422:
                    throw new ValidationException($message, $code);
                    break;
                case 401:
                    throw new AuthorizationException($message, $code);
                default:
                    throw new ServiceInvalidException($message ? $message : 'Service Invalid', $code);
            }
        } catch (ServerException $e) {
            $response = $e->getResponse();
            $contents = $response->getBody()->getContents();
            $content = json_decode($contents);
            $message = property_exists($content, 'message') ? $content->message : 'Service Invalid';
            $code = property_exists($content, 'code') ? $content->code : 100000;

            throw new ServiceInvalidException($message, $code);
        } catch (RequestException $e) {
            // 在发送网络错误(连接超时、DNS错误等)时
            throw new BadRequestException($e->getMessage(), 400);
        }
    }
}
