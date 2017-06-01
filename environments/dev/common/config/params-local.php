<?php
return [
    'loginUrl'=>'http://login.localsongtang.net',
    'logoutUrl'=>'http://login.localsongtang.net/site/logout',

    'ucenterAppUrl' => 'http://ucenter.localsongtang.net',
    'oaAppUrl' => 'http://oa.localsongtang.net',
    'oaAppAdminUrl' => 'http://oa.localsongtang.net/admin',
    'yunAppUrl' => 'http://yun.localsongtang.net',
    'yunAppAdminUrl' => 'http://yun.localsongtang.net/admin',
    'apiAppUrl'=> 'http://api.localsongtang.net',
    'testAppUrl'=> 'http://test.localsongtang.net',

    'qiniu-accessKey' => '1nwvYZqaucoH14DZTD41GkKM1JmksrjlfNafgdu_',
    'qiniu-secretKey' => 'sV_VSSqbypX7XxoahfacjdQtoFVxr7BiLXFBrGV4',
    'qiniu-bucket' => 'songtangtest',
    'qiniu-domain' => 'http://7xoavb.com1.z0.glb.clouddn.com/',
    'qiniu-domain-beaut' => 'http://7xoavb.com1.z0.glb.clouddn.com/',

    'qiniu-oa-accessKey' => '1nwvYZqaucoH14DZTD41GkKM1JmksrjlfNafgdu_',
    'qiniu-oa-secretKey' => 'sV_VSSqbypX7XxoahfacjdQtoFVxr7BiLXFBrGV4',
    'qiniu-oa-bucket' => 'songtangoa',
    'qiniu-oa-domain' => 'http://oqs27rpel.bkt.clouddn.com/',
    'qiniu-oa-domain-beaut' => 'http://oqs27rpel.bkt.clouddn.com/',

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
