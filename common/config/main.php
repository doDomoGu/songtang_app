<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            //'class' => 'yii\caching\FileCache',
            //'cachePath' => '@common/runtime/cache',
            'class' => 'yii\redis\Cache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            //'hostname' => '115.29.167.9',
            //'password'=> 'r-bp1520b0b452fd84.redis.rds.aliyuncs.com:Gljxyt110909',
            'port' => 6379,
            'database' => 0,
        ],
    ],
];
