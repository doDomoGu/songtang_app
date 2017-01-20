<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=songtang_common',
            'username' => 'gljgljglj',
            'password' => 'gljgogo',
            'charset' => 'utf8',
        ],
        'db_ucenter' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=songtang_ucenter',
            'username' => 'gljgljglj',
            'password' => 'gljgogo',
            'charset' => 'utf8',
        ],
        'db_oa' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=songtang_oa',
            'username' => 'gljgljglj',
            'password' => 'gljgogo',
            'charset' => 'utf8',
        ],
        'db_yun' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=songtang_yun',
            'username' => 'gljgljglj',
            'password' => 'gljgogo',
            'charset' => 'utf8',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'login\models\UserIdentity',
            'enableAutoLogin' => true,
            //'loginUrl' => '/',
            'idParam'=>'_local_songtang_user_identity',
            'identityCookie' => ['name' => '_local_identity-frontend', 'httpOnly' => true,'domain' => '.localsongtang.net','path'=>'/'],
        ],
        'session' => [
            'class'=>'yii\web\Session',
            // this is the name of the session cookie used for login on the frontend
            //'name' => 'advanced-frontend',
            'cookieParams' => [
                'domain' => '.localsongtang.net',
                'lifetime' => 0,
                'httpOnly' => true,
                'path' => '/',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'targets' => [
                'sms'=>[
                    'class' => 'yii\log\DbTarget',  //使用数据库记录日志
                    'levels' => ['error', 'warning'],
                    'categories' => ['sms'],
                    'logTable'=> 'log_sms'
                ]
            ]
        ]
    ],
];
