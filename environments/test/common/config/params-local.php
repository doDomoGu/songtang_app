<?php
return [
    'loginUrl'=>'http://login.sina.com',
    'logoutUrl'=>'http://login.sina.com/site/logout',

    'ucenterAppUrl' => 'http://ucenter.sina.com',
    'oaAppUrl' => 'http://oa.sina.com',
    'oaAppAdminUrl' => 'http://oa.sina.com/admin',
    'yunAppUrl' => 'http://yun.sina.com',
    'yunAppAdminUrl' => 'http://yun.sina.com/admin',
    'apiAppUrl'=> 'https://api.sina.com',

   /* 'qiniu-accessKey' => '1nwvYZqaucoH14DZTD41GkKM1JmksrjlfNafgdu_',
    'qiniu-secretKey' => 'sV_VSSqbypX7XxoahfacjdQtoFVxr7BiLXFBrGV4',
    'qiniu-bucket' => 'songtangtest',
    'qiniu-domain' => 'http://7xoavb.com1.z0.glb.clouddn.com/',
    'qiniu-domain-beaut' => 'http://7xoavb.com1.z0.glb.clouddn.com/',*/


    'qiniu-accessKey' => 'A9vqV-lyLBGKTkGpMBEkfqyUevJN-TKVi57jNpt-',
    'qiniu-secretKey' => 'zCOYX5Uu31qAEpmJ7ZRndPnVlm-56CAH9Kq3gW-K',
    'qiniu-bucket' => 'songtangyun',
    'qiniu-domain'=> 'http://ojh4ydms2.bkt.clouddn.com/',
    'qiniu-domain-beaut'=> 'http://yun-source.songtang.net/',

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
