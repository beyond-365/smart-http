<?php


namespace Beyond\SmartHttp\Sample\Demo;


use Beyond\SmartHttp\Kernel\Exceptions\AuthorizationException;
use Beyond\SmartHttp\Kernel\Exceptions\BadRequestException;
use Beyond\SmartHttp\Kernel\Exceptions\ResourceNotFoundException;
use Beyond\SmartHttp\Kernel\Exceptions\ServiceInvalidException;
use Beyond\SmartHttp\Kernel\Exceptions\ValidationException;

class Demo extends DemoClient
{

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
        $this->baseUri = 'http://legend.api';
        $url = '/api/coupon/select';

        $response = $this->request($url, 'POST', [
            'json'    => [
                "goods_list"     => [
                    [
                        "activity_id" => 168,
                        "goods_id"    => 132,
                        "fee"         => 3000,
                        "number"      => 1,
                        "cart_id"     => "1929",
                        "amount"      => "3000",
                        "discount"    => 150
                    ]
                ],
                "current_coupon" => [
                    [
                        "type"        => "checked",
                        "stock_id"    => "1229300000000382",
                        "coupon_code" => "211013152213000000007583027"
                    ]
                ]
            ],
            'headers' => [
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOjYwNiwiaWF0IjoxNjM0Mzc2OTI5LCJleHAiOjE2MzQzODQxMjl9.gqewPyIi4NOc_nAm9lKJ8_ZeKs-PCO_L1yel8991kng',
            ]
        ]);

        return $this->unwrapResponse($response);
    }
}