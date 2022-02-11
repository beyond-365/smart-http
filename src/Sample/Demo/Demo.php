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
        $response = $this->request('/admin/login', 'POST', [
            'json'    => [
                "account" => "debug",
                "password" =>  "debug"
            ]
        ]);

        $responseHeader = $response->getHeaders();
        $result =  $this->unwrapResponse($response);
        return compact('result', 'responseHeader');
    }
}