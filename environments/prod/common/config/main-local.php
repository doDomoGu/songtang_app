<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=songtang_common',
            'username' => 'root',
            'password' => 'gljgogo',
            'charset' => 'utf8',
        ],
        'db_ucenter' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=songtang_ucenter',
            'username' => 'root',
            'password' => 'gljgogo',
            'charset' => 'utf8',
        ],
        'db_oa' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=songtang_oa',
            'username' => 'root',
            'password' => 'gljgogo',
            'charset' => 'utf8',
        ],
        'db_yun' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=songtang_yun',
            'username' => 'root',
            'password' => 'gljgogo',
            'charset' => 'utf8',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'login\models\UserIdentity',
            'enableAutoLogin' => true,
            //'loginUrl' => '/',
            'idParam'=>'_songtang_user_identity',
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true,'domain' => '.songtang.net','path'=>'/'],
        ],
        'session' => [
            'class'=>'yii\web\Session',
            // this is the name of the session cookie used for login on the frontend
            //'name' => 'advanced-frontend',
            'cookieParams' => [
                'domain' => '.songtang.net',
                'lifetime' => 0,
                'httpOnly' => true,
                'path' => '/',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
