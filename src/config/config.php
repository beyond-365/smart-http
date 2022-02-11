<?php

return [
    'host' => 'https://apis.map.qq.com/',

    'http' => [
        'timeout'      => 3.0,
        'debug'        => false,
        'logging'      => true,
        'log_template' => '{"sdk_uri":"{uri}","code":"{code}","request":"{req_body}","body":"{res_body}","error":"{error}"}'
    ],
];
