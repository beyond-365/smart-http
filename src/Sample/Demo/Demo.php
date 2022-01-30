<?php


namespace Beyond\SmartHttp\Sample\Demo;


use Beyond\SmartHttp\Kernel\Exceptions\AuthorizationException;
use Beyond\SmartHttp\Kernel\Exceptions\BadRequestException;
use Beyond\SmartHttp\Kernel\Exceptions\ResourceNotFoundException;
use Beyond\SmartHttp\Kernel\Exceptions\ServiceInvalidException;
use Beyond\SmartHttp\Kernel\Exceptions\ValidationException;
use Beyond\SmartHttp\Kernel\ServiceContainer;

class Demo extends DemoClient
{
    public function __construct(ServiceContainer $app)
    {
        parent::__construct($app);
        $this->setBaseUri('http://legend.api/');
    }

    /**
     * @return array|string
     * @throws AuthorizationException
     * @throws BadRequestException
     * @throws ResourceNotFoundException
     * @throws ServiceInvalidException
     * @throws ValidationException
     */
    public function location()
    {
        $url = '/admin/v2/order/remark-list';

        $response = $this->request($url, 'POST', [
            'json'    => [
                'order_sn' => 190082258710528
            ],
            'headers' => [
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOjIxLCJpYXQiOjE2NDM1MjY3MDMsImV4cCI6MTY0MzU0NDcwM30.ORep-clESsHwaPV8vLHnR55IBn2Z_ZS6oL4KlqqTkXE',
            ]
        ]);

        $responseHeader = $response->getHeaders();
        $result =  $this->unwrapResponse($response);
        return compact('result', 'responseHeader');
    }
}