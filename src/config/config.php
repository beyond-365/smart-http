<?php

return [
    'host' => 'https://apis.map.qq.com/',

    'http' => [
        'timeout'      => 3.0,
        'debug'        => true,
        'logging'      => false,
        'log_template' => '{"sdk_uri":"{url}","code":"{code}","request":"{req_body}","body":"{res_body}","error":"{error}"}'
    ],
];
