<?php


namespace Beyond\SmartHttp\Sample\Demo;


use Beyond\SmartHttp\Kernel\BaseClient;
use Beyond\SmartHttp\Kernel\Exceptions\AuthorizationException;
use Beyond\SmartHttp\Kernel\Exceptions\BadRequestException;
use Beyond\SmartHttp\Kernel\Exceptions\ResourceNotFoundException;
use Beyond\SmartHttp\Kernel\Exceptions\ServiceInvalidException;
use Beyond\SmartHttp\Kernel\Exceptions\ValidationException;
use Beyond\SmartHttp\Kernel\ServiceContainer;
use Beyond\SmartHttp\Sample\Middleware\AfterMiddle;
use Beyond\SmartHttp\Sample\Middleware\DemoMiddleware;
use Beyond\SmartHttp\Sample\Middleware\SignMiddleware;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;

abstract class DemoClient extends BaseClient
{
    /**
     * DemoClient constructor.
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        parent::__construct($app);

        if ($host = $app->offsetGet('config')->get('host', '')) {
            $this->setBaseUri($host);
        }
    }

    public function registerMiddleware()
    {
        parent::registerMiddleware();

        $this->pushMiddlewareByKey(SignMiddleware::getAccessName());
        $this->pushMiddlewareByKey(DemoMiddleware::getAccessName());
        $this->pushMiddlewareByKey(AfterMiddle::getAccessName());
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