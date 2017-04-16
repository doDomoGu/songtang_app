<?php
namespace api\controllers;

use ucenter\models\User;
use Yii;

class UserController extends BaseController{
    public $format = [
        'info-get'=>[
            'param'=>[
                'id'=>[
                    'type' => 'int',
                    'required' => true,
                    'explain' => '用户ID'
                ]
            ],
            'title' => '获取用户信息',
            'explain' => '获取用户信息.....'
        ],
        ];

    public $allowArr = [
            'info-get'=>['id'=>'int'],
            'login'=>['username'=>'str','password'=>'str'],
            'wx-code-to-session'=>['code'=>'str'],
            'wx-encrypted-data'=>['session_key'=>'str','encryptedData'=>'str','iv'=>'str'],
            'wx-bind-user'=>['user_id'=>'str','session_3rd'=>'str'],
            'wx-unbind-user'=>['user_id'=>'str','session_3rd'=>'str'],
            'wx-get-3rd-session'=>['code'=>'str']
        ];
    public $requireArr = [
            'info-get'=>['id'],
            'login'=>['username','password'],
            'wx-code-to-session'=>['code'],
            'wx-encrypted-data'=>['session_key','encryptedData','iv'],
            'wx-bind-user'=>['user_id','session_3rd'],
            'wx-unbind-user'=>['user_id','session_3rd'],
            'wx-get-3rd-session'=>['code']
            //'add'=>['name']
        ];

    public function actions()
    {
        return [
            'info-get'=>[
                'class'=>'api\controllers\user\info\get',
            ],
            /*'login'=>[
                'class'=>'api\controllers\user\login\index',
            ],
            'wx-code-to-session'=>[
                'class'=>'api\controllers\user\wx\codeToSession',
            ],
            'wx-get-3rd-session'=>[
                'class'=>'api\controllers\user\wx\get3rdSession',
            ],
            'wx-encrypted-data'=>[
                'class'=>'api\controllers\user\wx\encryptedData',
            ],
            'wx-bind-user'=>[
                'class'=>'api\controllers\user\wx\bindUser',
            ],
            'wx-unbind-user'=>[
                'class'=>'api\controllers\user\wx\unbindUser',
            ]*/
        ];
    }
}
