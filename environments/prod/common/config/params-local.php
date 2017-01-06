<?php
return [
    'loginUrl'=>'http://login.songtang.net',
    'logoutUrl'=>'http://login.songtang.net/site/logout',

    'ucenterAppUrl' => 'http://ucenter.songtang.net',
    'oaAppUrl' => 'http://oa.songtang.net',
    'oaAppAdminUrl' => 'http://oa.songtang.net/admin',
    'yunAppUrl' => 'http://yun.songtang.net',
    'yunAppAdminUrl' => 'http://yun.songtang.net/admin',

    'qiniu-accessKey' => '1nwvYZqaucoH14DZTD41GkKM1JmksrjlfNafgdu_',
    'qiniu-secretKey' => 'sV_VSSqbypX7XxoahfacjdQtoFVxr7BiLXFBrGV4',
    'qiniu-bucket' => 'songtangyun',
    'qiniu-domain' => 'http://7xnt87.com1.z0.glb.clouddn.com/',
    'qiniu-domain-beaut' => 'http://source.songtang.net/',


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
