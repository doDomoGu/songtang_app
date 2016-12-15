<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'login\models\UserIdentity',
            'enableAutoLogin' => true,
            //'loginUrl' => '/',
            'idParam'=>'_songtang_user_identity',
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true,'domain' => '.localsongtang.net','path'=>'/'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            //'name' => 'advanced-frontend',
            'cookieParams' => [
                'domain' => '.localsongtang.net',
                'lifetime' => 0,
                'httpOnly' => true,
                'path' => '/',
            ],
        ],
    ],
];
