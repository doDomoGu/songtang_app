<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            //'class' => 'yii\caching\FileCache',
            'class' => 'yii\redis\Cache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            //'hostname' => 'localhost',
            'hostname' => 'r-bp1520b0b452fd84.redis.rds.aliyuncs.com',
            'password'=> 'Gljxyt110909',
            'port' => 6379,
            'database' => 0,
        ],
    ],
];
