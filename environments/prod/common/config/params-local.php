<?php
return [
    'loginUrl'=>'http://login.songtang.net',
    'logoutUrl'=>'http://login.songtang.net/site/logout',

    'ucenterAppUrl' => 'http://ucenter.songtang.net',
    'oaAppUrl' => 'http://oa.songtang.net',
    'oaAppAdminUrl' => 'http://oa.songtang.net/admin',
    'yunAppUrl' => 'http://yun.songtang.net',
    'yunAppAdminUrl' => 'http://yun.songtang.net/admin',
    'apiAppUrl'=> 'https://api.songtang.net',

    'qiniu-accessKey' => 'A9vqV-lyLBGKTkGpMBEkfqyUevJN-TKVi57jNpt-',
    'qiniu-secretKey' => 'zCOYX5Uu31qAEpmJ7ZRndPnVlm-56CAH9Kq3gW-K',
    'qiniu-bucket' => 'songtangyun',
    'qiniu-domain'=> 'http://ojh4ydms2.bkt.clouddn.com/',
    'qiniu-domain-beaut'=> 'https://yun-source.songtang.net/',

    'qiniu-oa-accessKey' => 'A9vqV-lyLBGKTkGpMBEkfqyUevJN-TKVi57jNpt-',
    'qiniu-oa-secretKey' => 'zCOYX5Uu31qAEpmJ7ZRndPnVlm-56CAH9Kq3gW-K',
    'qiniu-oa-bucket' => 'songtangoa',
    'qiniu-oa-domain'=> 'http://ojh4ydms2.bkt.clouddn.com/',
    'qiniu-oa-domain-beaut'=> 'http://oa-source.songtang.net/',



    'aliyun_sms_config'=> [
        'accessKey'=> 'LTAIaNUr1BPK7bAa',
        'accessSecret' => 'EyZv4WFoxrVzNMPzlrYnQd2LaSsOhr',
        'sign' => '欣萌网络',
        'regionId' => 'cn-hangzhou',
        'template' => [
            'param' => ['product'=>'[花火在线桌游]'],
            'scenario' => [
                'user_register'=>[
                    'code' =>'SMS_25490100',
                    'text' =>'验证码${code}，您正在注册${product}，如非本人操作，请忽略。'
                ],
                'forget_password'=>[
                    'code' =>'SMS_25490XXX',
                    'text' =>'XXXX验证码${code}，您正在注册${product}，如非本人操作，请忽略。'
                ],
                'new_msg'=>[
                    'code' =>'SMS_38910017',
                    'text' =>'您有一条新消息,(${title})'
                ]
            ]
        ],
    ]
];
