<?php
$params = array_merge(
    require(__DIR__ . '/../../../common/config/params.php'),
    require(__DIR__ . '/../../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'api',
    'name'=> '颂唐API',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'modules' => [
/*        'ucenter' => [
            'class' => 'api\modules\ucenter\Module',
        ],
        'login' => [
            'class' => 'api\modules\login\Module',
        ],*/
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' =>true,
            'rules' => [
                //'DELETE v1/users'  => 'v1/users/delete',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/users'],
                    'except' => ['delete'],
                    'extraPatterns' => [
                        //'GET versions' => 'version',
                        //'GET search/<id:\d+>' => 'search',
                        'POST login' => 'login',
                        'GET signup-test' => 'signup-test',
                    ],
                    //'pluralize' => true

                ],

            ]
        ],

    ],
    'params' => $params,
];
