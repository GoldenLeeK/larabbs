<?php
return [

    //接口调用频率限制
    'rate_limits' => [
        //调用频率
        'access' => env('RATE_LIMITS', '60,1'),
        //登录相关
        'sign' => env('SIGN_RATE_LIMITS', '10,1'),
    ],
];